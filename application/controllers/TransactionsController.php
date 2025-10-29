<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsController extends MY_Controller {

	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
		$this->load->model('PaymentModel');
		$this->load->model('WithdrawModel');
		$this->load->model('UserModel');
		$this->load->model('EmailConfigModel');
		$this->load->model('TradingAccount');
		$this->load->model('IbModel');

		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();

		$this->load->model('PermissionModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name
	}

	/**
	 *	 This Function Providing the settings page
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function mt5TransactionsSummary()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$getSummery 	= $this->PaymentModel->getMt5TransactionsSummery();//pending
				self::renderView('mt5_transactions_summery',$getSummery,'','Mt5 Transaction Summary');
			}else if($request['type'] == 'api'){
				self::response(200,'');
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function addBonus(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$userModelData 	= $this->UserModel->getUserDropdownList();
				self::renderView('add_bonus',$userModelData,'','Add Bonus');
			}else if($request['type'] == 'api'){
				self::response(200,'');
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function bonusList(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				self::renderView('bonus_list','','','Add Bonus');
			}else if($request['type'] == 'api'){
				self::response(200,'');
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function commissionTransferHistory(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$dataList = $this->IbModel->getCommissionTransfer();

			if($request['type'] == 'web'){
				self::renderView('commission_transfer_history',$dataList);
			}else if($request['type'] == 'api'){
				self::response(200,$dataList);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function commissionTransferHistoryDetails($id){
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getDepositSingleData 	= $this->IbModel->commissionTransferDetails($id); //pending
			if($request['type'] == 'web'){
				self::renderView('commission_transfer_details',$getDepositSingleData,'','Deposit Details');
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositSingleData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	/**
	 * @return CI_Loader
	 */
	public function changeCommissionTransferStatus()
	{
		$request	=self::isAuth(false);
		if($request['auth']==true) {

			$this->form_validation->set_rules('transferId', 'Transfer ID', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'transferId'		=>strip_tags(form_error('transferId')),
					'status'			=>strip_tags(form_error('status')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'commission-transfer-details/'.$_REQUEST['transferId'].'');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				/*-------------if and only payment is approved--------*/
				if ($_REQUEST['status']==1) {
                    $unqId=$_REQUEST['unique_id'];
                    $ibAccount = $this->db->query("SELECT mt5_login_id from ib_accounts where unique_id = '$unqId' ")->row();

					$getResponseGroup = $this->mt5_instance->commissionTransferAmount($_REQUEST,$ibAccount);

					if ($getResponseGroup!=false) {

						$updateStatus 	= $this->IbModel->ApprovedCommissionTransfer($_REQUEST);

						if ($updateStatus) {

							$getUserInfo 	= $this->UserModel->getUser($_REQUEST['unique_id']);

							$mailHtml 	=$this->EmailConfigModel->fundDeposit($getUserInfo->first_name.' '.$getUserInfo->last_name,$_REQUEST['enterAmount'],$_REQUEST['enterAmount'],$_REQUEST['to_account']);
							self::sendEmail($getUserInfo->email,'Fund Deposit Successful',$mailHtml);

							if ($request['type'] == 'web') {
								$_SESSION['success_approved_message'] = 'Successfully Done Withdraw';
								redirect(base_url() . 'commission-transfer-details/'.$_REQUEST['transferId'].'');
							} else if ($request['type'] == 'api') {
								self::response(200, 'Successfully Done Approve');
							}
						}
					}else{

						/*--------Handelling Mt5 Creating Error Response------*/
						$responseData	=array(
							'status'    		=> 400,
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_payment_status']	=json_encode($responseData,true);
							$_SESSION['request_data']			=json_encode($_REQUEST,true);
							redirect(base_url() . 'commission-transfer-details/'.$_REQUEST['transferId'].'');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}else{

					$updateStatus 	= $this->IbModel->ApprovedCommissionTransfer($_REQUEST);
					if ($updateStatus) {
						if ($request['type'] == 'web') {
							$_SESSION['success_approved_message'] = 'Successfully Change Status';
							redirect(base_url() . 'commission-transfer-details/'.$_REQUEST['transferId'].'');
						} else if ($request['type'] == 'api') {
							self::response(200, 'Successfully Done Approve');
						}
					}
				}
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function internalTransferHistory(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getInternalTransferHistoryData();
			if($request['type'] == 'web'){
				self::renderView('internal_transfer_history',$getDepositHistory);
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositHistory);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function createBonus(){

		$request	=self::isAuth(false);

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('meta_descriptions', 'Transaction Refrence Number', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/transaction/add-bonus');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				$dataItem = array(
					 'unique_id' => 		$_REQUEST['unique_id'],
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>$_REQUEST['meta_descriptions'],
					'withdrawal_type' 	=> 4,
					'status' 			=>2, //Paid
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

//				if ($insertPaymentId){
//
//					//Send Mail
//					$mailHtml 	=$this->EmailConfigModel->fundWithdraw($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
//					self::sendEmail($request['email'],'Fund Withdrawal request has been successfully placed.',$mailHtml);
//
//					if ($request['type'] == 'api') {
//						self::response(200, 'Successfully Withdraw');
//					} else if ($request['type'] == 'web') {
//						$_SESSION['success_withdraw'] = 'Successfully Withdraw';
//						redirect(base_url() . 'add-bonus');
//					}
//				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function internalTransferUserWise(){
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$userList 	=$this->UserModel->getUserDropdownList();
				self::renderView('internal_transfer_user_wise',$userList,'','Internal transfer');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function saveInternalTransferUserWise(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			if($request['type'] == 'web') {

				$this->form_validation->set_rules('from_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('to_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
				$this->form_validation->set_rules('from_unique_id', 'Amount', 'trim|required');

				if ($_REQUEST['from_mt5_login_id']==$_REQUEST['to_mt5_login_id']){
					$this->form_validation->set_rules('invalid_id', 'mt5 account number should not be same', 'trim|required');
				}

				if ($_REQUEST['from_mt5_login_id']) {
					$getBalance 				= self::getWithdrawBalance($_REQUEST['from_mt5_login_id']);
					if ($_REQUEST['amount']>$getBalance) {
						$this->form_validation->set_rules('amount', 'transfer amount should be less than original balance', 'required');
					}elseif ($_REQUEST['amount']==0 || $_REQUEST['amount']==''){
						$this->form_validation->set_rules('amount', 'Withdraw amount should be greater than 0', 'required');
					}
				}

				if ($this->form_validation->run() == FALSE)
				{
					/*--------Error Response------*/
					$responseData	=array(
						'from_mt5_login_id'				=>strip_tags(form_error('from_mt5_login_id')),
						'to_mt5_login_id'				=>strip_tags(form_error('to_mt5_login_id')),
						'amount'						=>strip_tags(form_error('amount')),
						'from_unique_id'				=>strip_tags(form_error('from_unique_id')),
						'to_unique_id'					=>strip_tags(form_error('to_unique_id')),
						'invalid_id'					=>strip_tags(form_error('invalid_id')),
					);

					if($request['type'] == 'web'){

						$_SESSION['error_transfer']	=json_encode($responseData,true);
						$_SESSION['request_data']	=json_encode($_REQUEST,true);
						redirect(base_url() . 'admin/transaction/user-wise-internal-transfer');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}else{


					$_REQUEST['mt5_login_id']		=$_REQUEST['from_mt5_login_id'];
					$_REQUEST['enterAmount']		=$_REQUEST['amount'];
					$_REQUEST['remark']				='Master Int. Trans. Out to Acc '.$_REQUEST['to_mt5_login_id'].'';
					$getResponseGroup 				= $this->mt5_instance->withdrawAmount($_REQUEST);
					if ($getResponseGroup!=false) {

						$_REQUEST['remark']				='Add Amount From '.$_REQUEST['from_mt5_login_id'].'';
						$depositResponse 				= $this->mt5_instance->depositAmountInternalTransfer($_REQUEST);
						if ($depositResponse!=false){

							$getUserInfo 	= $this->UserModel->getUser($_REQUEST['from_unique_id']);

							$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
							$tnx = "TRANSFER" . substr(str_shuffle($permitted_chars), 0, 24);

							$dataP = array(
								'user_id' => $getUserInfo->user_id,
								'from_currency' => 'INR',
								'mt5_login_id' => $_REQUEST['to_mt5_login_id'],
								'entered_amount' => $_REQUEST['amount'],
								'to_currency' => 'INR',
								'amount' => $_REQUEST['amount'],
								'transaction_proof_attachment' => '',
								'payment_mode' =>6, //wire transfer
								'transaciton_detail' => '',
								'gateway_id' => $tnx,
								'gateway_url' => 'NULL',
								'is_roi' => 1,
								'status' => 1,
								'created_at' => date("Y-m-d H:i:s")
							);

							$insertPaymentId = $this->PaymentModel->insertPayment($dataP);
							if ($insertPaymentId) {
								$dataForTransfer = array(
									'user_id' => $getUserInfo->user_id,
									'payment_id' => $insertPaymentId,
									'mt5_login_id' => $_REQUEST['from_mt5_login_id'],
									'transfer_amount' => $_REQUEST['amount'],
									'status' => 1,
								);

								$transferAmount = $this->PaymentModel->insertInternalTransferPayment($dataForTransfer);
								if ($transferAmount) {

									$mailHtml 	=$this->EmailConfigModel->fundTransfer($getUserInfo->first_name.' '.$getUserInfo->last_name,$_REQUEST['amount'],$_REQUEST['from_mt5_login_id'],$_REQUEST['to_mt5_login_id']);
									self::sendEmail($getUserInfo->email,'Successful Fund Transfer from One Trading Account to Another',$mailHtml);

									if ($request['type'] == 'api') {
										self::response(200, 'Successfully Transfer');
									} else if ($request['type'] == 'web') {
										$_SESSION['success_transfer'] = 'Successfully Transfer';
										redirect(base_url() . 'admin/transaction/user-wise-internal-transfer');
									}
								}
							}
						}
					}else{
						/*--------Handelling Mt5 Creating Error Response------*/
						$responseData	=array(
							'status'    		=> 400,
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_transfer']			=json_encode($responseData,true);
							$_SESSION['request_data']			=json_encode($_REQUEST,true);
							redirect(base_url() . 'admin/transaction/user-wise-internal-transfer');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}

			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function getWithdrawBalance($accountId){
		if ($accountId){
			$getBalance = $this->mt5_instance->getAvailableBalance($accountId);
			if ($getBalance!=false){
				$getBalanceObj=$getBalance->answer[0];
				if ($getBalanceObj){
					$originalBalance=$getBalanceObj->Equity-($getBalanceObj->Credit+$getBalanceObj->Margin);
					return $originalBalance;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
	}



	public function internalTransfer(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$userList 	=$this->UserModel->getUserDropdownList();
				self::renderView('internal_transfer',$userList,'','Internal transfer');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function saveInternalTransfer(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			if($request['type'] == 'web') {

				$this->form_validation->set_rules('from_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('to_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required');

				if ($this->form_validation->run() == FALSE)
				{
					/*--------Error Response------*/
					$responseData	=array(
						'from_mt5_login_id'				=>strip_tags(form_error('from_mt5_login_id')),
						'to_mt5_login_id'				=>strip_tags(form_error('to_mt5_login_id')),
						'amount'						=>strip_tags(form_error('amount')),
					);

					if($request['type'] == 'web'){

						$_SESSION['error_transfer']	=json_encode($responseData,true);
						$_SESSION['request_data']	=json_encode($_REQUEST,true);
						redirect(base_url() . 'admin/transaction/user-internal-transfer');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}else{

					$getResponseGroup = $this->mt5_instance->internalTransferAmount($_REQUEST);
					if ($getResponseGroup!=false) {

						$getUserInfo 	= $this->UserModel->getUser($_REQUEST['unique_id']);

						$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$tnx = "TRANSFER" . substr(str_shuffle($permitted_chars), 0, 24);

						$dataP = array(
							'user_id' => $getUserInfo->user_id,
							'from_currency' => 'INR',
							'mt5_login_id' => $_REQUEST['to_mt5_login_id'],
							'entered_amount' => $_REQUEST['amount'],
							'to_currency' => 'INR',
							'amount' => $_REQUEST['amount'],
							'transaction_proof_attachment' => '',
							'payment_mode' =>6, //wire transfer
							'transaciton_detail' => '',
							'gateway_id' => $tnx,
							'gateway_url' => 'NULL',
							'is_roi' => 1,
							'status' => 1,
							'created_at' => date("Y-m-d H:i:s")
						);

						$insertPaymentId = $this->PaymentModel->insertPayment($dataP);
						if ($insertPaymentId) {
							$dataForTransfer = array(
								'user_id' => $getUserInfo->user_id,
								'payment_id' => $insertPaymentId,
								'mt5_login_id' => $_REQUEST['from_mt5_login_id'],
								'transfer_amount' => $_REQUEST['amount'],
								'status' => 1,
							);
							$transferAmount = $this->PaymentModel->insertInternalTransferPayment($dataForTransfer);
							if ($transferAmount) {

								$mailHtml 	=$this->EmailConfigModel->fundTransfer($getUserInfo->first_name.' '.$getUserInfo->last_name,$_REQUEST['amount'],$_REQUEST['from_mt5_login_id'],$_REQUEST['to_mt5_login_id']);
								self::sendEmail($getUserInfo->email,'Successful Fund Transfer from One Trading Account to Another',$mailHtml);

								if ($request['type'] == 'api') {
									self::response(200, 'Successfully Transfer');
								} else if ($request['type'] == 'web') {
									$_SESSION['success_transfer'] = 'Successfully Transfer';
									redirect(base_url() . 'admin/transaction/user-internal-transfer');
								}
							}
						}
					}else{
						/*--------Handelling Mt5 Creating Error Response------*/
						$responseData	=array(
							'status'    		=> 400,
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_transfer']			=json_encode($responseData,true);
							$_SESSION['request_data']			=json_encode($_REQUEST,true);
							redirect(base_url() . 'admin/transaction/user-internal-transfer');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}

			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining the authenticaitons
	 *   Return : Array
	 *   Version : 1.0.1
	 */
	public function isAuth($validate=true){
		$getHeader	=$this->input->request_headers();
		$eventFrom=array();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
				$uniqueKey		=$getHeader['Authorization'];
				$getUserInfo 	= $this->UserModel->getUser($uniqueKey,0); //0 for admin access
				if ($getUserInfo){
					$eventFrom=array('type'=>'api','auth'=>true);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			if ($validate==true) {
				$checkPermission = $this->PermissionModel->checkExistPermission($this->session->userdata('user_id'), $this->actionName);
				if ($checkPermission) {
					if ($this->session->userdata('username') != '') {
						$eventFrom = array('type' => 'web', 'auth' => true);
					}
				} else {
					redirect(base_url() . 'error/404');
				}
			}else{
				if ($this->session->userdata('username') != '') {
					$eventFrom = array('type' => 'web', 'auth' => true);
				}
			}
		}
		return $eventFrom;
	}

	/**
	 *	 This Function Maintaining the api response
	 *   Return : JSON
	 *   Version : 1.0.1
	 */

	public function response($status='',$data=''){
		if ($status==200){
			$dataItem=array(
				'status'=>200,
				'data'=>$data,
			);
			print_r(json_encode($dataItem,true));
			exit();
		}else{
			$dataItem=array(
				'status'=>400,
				'message'=>$data,
			);
			print_r(json_encode($dataItem,true));
			exit();
		}
	}

	/**
	 *	 This Function Providing View Layout
	 */
	public function renderView($fileName,$data='',$params='',$requestTitle=''){
		$title['title']					=$requestTitle;
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/transactions/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}

	public function sendEmail($toEmail,$subject,$htmlContent)
	{
		$companyName =ConfigData['m_company_name'];

		//Load email library
		$this->load->library('email');


		//SMTP & mail configuration
		$config = $this
			->EmailConfigModel
			->getConfig();

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		// Recipient
		//$to ='aiubzahid@gmail.com';
		$to =$toEmail;

		$mailFrom=$this
			->EmailConfigModel
			->getFromEmail();

		$this->email->from($mailFrom,$companyName);
		$this->email->to($to);
		//$this->email->cc('keshriedutech@gmail.com');
		//$this->email->bcc('iambommanakavya@gmail.com');
		$this->email->subject($subject);
		$this->email->message($htmlContent);

		if($this->email->send()){
			//echo 1;
		}
	}
}
