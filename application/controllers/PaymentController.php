<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('TradingAccount');
		$this->load->model('UserModel');
		$this->load->model('PaymentModel');
		$this->load->model('ProfileModel');
		$this->load->model('WithdrawModel');
		$this->load->model('EmailConfigModel');

		$this->load->model('ProfileModel');
	}

	public function crypto_payment()
	{

		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request_call= 'api';
			$uid = $getHeader['uid'];
			}else{
				$uid = $_SESSION['unique_id'];
				$request_call = 'web';
			}

		/*-----------------get UserID-----------------*/
        $getuserID = $this->db->query("SELECT user_id FROM users where unique_id = '$uid'")->result();
        foreach($getuserID as $getuserIDvalue){
        	$userIID = $getuserIDvalue->user_id;
        }
        
        /*------------------------------------------------*/

		$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($userIID);
		$getCoins = $this->ProfileModel->getcoin();
		$data['coin'] = $getCoins;
		$data['dataItem'] = $getTradingAccount;

		if($request_call == 'web'){
			$title['title']					='Cypto Payment';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/user_left_side_bar');
			$this->load->view('user/payment/crypto_payment',$data);
			$this->load->view('includes/footer');
		}else if($request_call == 'api'){
			$dataItem=array(
							'status'=>200,
							'data'=>$data,
						);
						print_r(json_encode($dataItem,true));
						exit();
		}
		
	}
	public function my_transaction()
	{
		$title['title']					='My Transactions';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/payment/my_transaction');
		$this->load->view('includes/footer');
	}

	public function checkout_coinpayment_amount()
	{
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request_call= 'api';
			$uid = $getHeader['uid'];
			}else{
				$uid = $_SESSION['unique_id'];
				$request_call = 'web';
			}


			/*-----------------get private key-----------------*/
        $PrivateKey_query = $this->db->query("SELECT value_name FROM basetable where key_name = 'PRIVATE_KEY'");
        foreach($PrivateKey_query->result() as $row_privatekey){
            $privatekey = $row_privatekey->value_name;
        }

        /*------------------------------------------------*/
        
        /*-----------------get public key-----------------*/
        $PublicKey_query = $this->db->query("SELECT value_name FROM basetable where key_name = 'PUBLIC_KEY'");
        foreach($PublicKey_query->result() as $row_publickey){
            $publickey = $row_publickey->value_name;
        }
        /*------------------------------------------------*/
        
        /*-----------------get merchant id-----------------*/
        $merchantId_query = $this->db->query("SELECT value_name FROM basetable where key_name = 'MERCHANT_ID'");
        foreach($merchantId_query->result() as $row_merchantId){
            $merchantId = $row_merchantId->value_name;
        }
        /*------------------------------------------------*/
        
        /*-----------------get secret IPN-----------------*/
        $secretIpn_query = $this->db->query("SELECT value_name FROM basetable where key_name = 'SECRET_IPN'");
        foreach($secretIpn_query->result() as $row_secretIpn){
            $secretIpn = $row_secretIpn->value_name;
        }
        /*------------------------------------------------*/
        
        /*-----------------get DEBUG Email-----------------*/
        $debugEmail_query = $this->db->query("SELECT value_name FROM basetable where key_name = 'DEBUG_EMAIL'");
        foreach($debugEmail_query->result() as $row_debugEmail){
            $debugEmail = $row_debugEmail->value_name;
        } 
        /*------------------------------------------------*/

        /*-----------------get UserID-----------------*/
        $getuserID = $this->db->query("SELECT user_id FROM users where unique_id = '$uid'")->result();
        foreach($getuserID as $getuserIDvalue){
        	$userIID = $getuserIDvalue->user_id;
        }
        
        /*------------------------------------------------*/

        $this->load->library('CoinPaymentsAPI');
        $coin = new CoinPaymentsAPI();
        $coin->Setup("$privatekey","$publickey");
        
        $basicInfo = $coin->GetBasicProfile();

        /*$username = $basicInfo['result']['public_name'];*/

        $amount = $_REQUEST['amount'];
        $email = $_REQUEST['email'];
        $mt5login_account = $_REQUEST['mt5_login_id'];

        $scurrency = "USD";
        /*$rcurrency = "BTC";*/
        $rcurrency = $_REQUEST['coin_address'];

        $request = [
            'amount' => $amount,
            'currency1' => $scurrency,
            'currency2' => $rcurrency,
            'buyer_email' => $email,
            'item' => "Donate to Forex",
            'address' => "",
            'ipn_url' => base_url()."webhook/webhook.php"
        ];
        
       
        $result = $coin->CreateTransaction($request);
        
        if ($result['error'] == "ok") {
        
        $c_amount = $result['result']['amount'];
        $c_txid = $result['result']['txn_id'];
        $c_status = $result['result']['status_url'];
        $c_address = $result['result']['address'];
        $c_qrcode = $result['result']['qrcode_url'];
        
        $paymentData = array(
                                'user_id' => $userIID, 
                                'unique_id' => $uid, 
                                'email' => $email,
                                'mt5_login_id' => $mt5login_account,
                                //'packageid' => $_POST['packageid'],
                                'payment_mode' => 2,
			                    'entered_amount' => $amount,
			                    'amount' => $c_amount,
								'from_currency' => $scurrency,
								'to_currency' => $rcurrency,
								'status' => '-1',//'initialized',
								'gateway_id' => $c_txid,
								'gateway_url' => $c_status,
								'created_at' => date("Y-m-d H:i:s")
								);
			

		
		$insertPayment = $this->PaymentModel->depositCryptoPayment($paymentData);

		if($insertPayment)
				{
					if($request_call == 'web'){
						$this->session->set_flashdata('p_rcurrency', $rcurrency); //set rcurrency transaction Created
					    $this->session->set_flashdata('p_camount', $c_amount); //set c_amount transaction Created
					    $this->session->set_flashdata('p_usd_amt', $amount); //set amount in doller transaction Created
					    $this->session->set_flashdata('p_status_url', $c_status); //set status url transaction Created
					    $this->session->set_flashdata('p_username', "Forex"); //set username transaction Created
					    $this->session->set_flashdata('p_address', $c_address); //set address transaction Created
					    $this->session->set_flashdata('p_qrcode', $c_qrcode); //set qrcode address transaction Created
					    $this->session->set_flashdata('msg', 'Transaction Created'); //set success msg if transaction Created
						redirect(base_url().'my-transaction');
					}else if($request_call == 'api'){
						$dataItem=array(
							'status'=>200,
							'data'=>$c_status,
						);
						print_r(json_encode($dataItem,true));
						exit();
					}
				    
				}
				else
				{
					if($request_call == 'web'){
						$data['errorMsg'] = $result['error'];
						$this->load->view('includes/header');
						$this->load->view('includes/user_left_side_bar');
						$this->load->view('payment/crypto_payment',$data);
						$this->load->view('includes/footer');
						die();
					}else if($request_call == 'api'){
						$dataItem=array(
							'status'=>400,
							'message'=>"Failed",
						);
						print_r(json_encode($dataItem,true));
						exit();
					}
				}
    }


		
	}

	

	





}
