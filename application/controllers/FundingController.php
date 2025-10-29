<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FundingController extends MY_Controller {
	private $mt5_instance="";
	private $apiContext;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('TradingAccount');
		$this->load->model('UserModel');
		$this->load->model('PaymentModel');
		$this->load->model('ProfileModel');
		$this->load->model('WithdrawModel');
		$this->load->model('EmailConfigModel');

		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();

		require_once APPPATH."libraries/paypal/vendor/autoload.php";
		$this->load->config('paypal');

		$getSettingsModel =$this->db->query("SELECT paypal_client_id,paypal_client_secret,paypal_status,stripe_client_id,stripe_client_secret,stripe_status FROM setting")->row();

		// Setup PayPal API context
		$this->apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$getSettingsModel->paypal_client_id,
				$getSettingsModel->paypal_client_secret
			)
		);
		$this->apiContext->setConfig(array(
			'mode' => 'sandbox', // Change to 'live' when going live
		));

		require_once APPPATH."libraries/stripe/vendor/autoload.php";
		\Stripe\Stripe::setApiKey($getSettingsModel->stripe_client_secret);
	}
	
	

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	 
	 

  
	public function deposit()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				self::renderView('deposit');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function depositWireTransfer(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
				self::renderView('deposit-wire-transfer',$getTradingAccount);
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function withdrawHistory(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getWithdrawData('',$request['unique_id'],$request['type']);
			if($request['type'] == 'web'){
				self::renderView('withdraw_history',$getDepositHistory);
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositHistory);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function internalTransferHistory(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getInternalTransferHistoryData($request['userId'],$request['type']);
			if($request['type'] == 'web'){
				self::renderView('internal_transfer_history',$getDepositHistory);
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositHistory);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function depositHistory(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getDepositHistory('',$request['userId'],$request['type']);
			if($request['type'] == 'web'){
				self::renderView('deposit_history',$getDepositHistory);
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositHistory);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function saveDepositStripe(){

		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'		=>strip_tags(form_error('mt5_login_id')),
					'amount'			=>strip_tags(form_error('amount')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_paypal_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/deposit/stripe');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$tnx="WIRE".substr(str_shuffle($permitted_chars), 0, 24);

				$dataP = array(
					'user_id' => $request['userId'],
					'from_currency' => 'USD',
					'mt5_login_id' =>$_REQUEST['mt5_login_id'],
					'entered_amount' => $_REQUEST['amount'],
					'to_currency' => 'USD',
					'amount' => $_REQUEST['amount'],
					'payment_mode' =>7, //wire transfer
					'transaciton_detail' => $_REQUEST['meta_descriptions'],
					'gateway_id' => $tnx,
					'gateway_url' => 'NULL',
					'is_roi' => 1,
					'status' =>0,
					'created_at' => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->PaymentModel->insertPayment($dataP);
				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Deposit Place Using Paypal For this ('.$_REQUEST['mt5_login_id'].') Account',$request['userId']);

					if ($request['type'] == 'api') {
						//self::response(200, 'Successfully Create Payment');
					} else if ($request['type'] == 'web') {
						$_SESSION['payment_request_data']=json_encode ($dataP);
						self::createStripePayment ($insertPaymentId,$dataP);
					}
				}

			}
		}else{
			redirect(base_url() . 'login');
		}

	}

	public function createStripePayment($insertPaymentId,$dataP) {

		$session = \Stripe\Checkout\Session::create([
			'payment_method_types' => ['card'],
			'line_items' => [[
				'price_data' => [
					'currency' => 'usd', // Change this to the desired currency
					'product_data' => [
						'name' => $dataP['mt5_login_id'], // Change this to your product name
					],
					'unit_amount' =>$dataP['amount']* 100, // Amount in cents
				],
				'quantity' => 1,
			]],
			'mode' => 'payment',
			'success_url' =>base_url('user/deposit/execute-payment-stripe?orderId='.$insertPaymentId.'&gatewayId='.$dataP['gateway_id'].''),
			'cancel_url' => base_url('user/deposit/cancel-payment-stripe?orderId='.$insertPaymentId.'&gatewayId='.$dataP['gateway_id'].''),
		]);

		try {

			redirect($session->url);

		} catch (Exception $ex) {
			// Handle error
			echo $ex->getMessage();
		}

		echo "<pre>";
		print_r($session);
		exit();

	}

	public function initialDeposit(){

		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|required');


			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'		=>strip_tags(form_error('mt5_login_id')),
					'amount'			=>strip_tags(form_error('amount')),
					'payment_type'		=>strip_tags(form_error('payment_type')),
				);
				self::response(400,$responseData);
			}else{

			 	$uId	=$request['userId'];
				$checkTradingAccount=$this->db->query("SELECT * FROM trading_accounts where mt5_login_id='".$_REQUEST['mt5_login_id']."' and user_id=$uId")->row();
				if (empty($checkTradingAccount)){
					self::response(400,'This Mt5 Account Not Yours!');
				}

				$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$tnx="TRX".substr(str_shuffle($permitted_chars), 0, 24);

				$dataP = array(
					'user_id' => $request['userId'],
					'from_currency' => 'USD',
					'mt5_login_id' =>$_REQUEST['mt5_login_id'],
					'entered_amount' => $_REQUEST['amount'],
					'to_currency' => 'USD',
					'amount' => $_REQUEST['amount'],
					'payment_mode' =>$_REQUEST['payment_type'], //wire transfer
					'transaciton_detail' =>($_REQUEST['meta_descriptions'])?$_REQUEST['meta_descriptions']:'',
					'gateway_id' => $tnx,
					'gateway_url' => 'NULL',
					'is_roi' => 1,
					'status' =>0,
					'created_at' => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->PaymentModel->insertPayment($dataP);
				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Deposit Place Using Paypal For this ('.$_REQUEST['mt5_login_id'].') Account',$request['userId']);

					if ($request['type'] == 'api') {
						$returnData=array(
						  'trxId'=>	$dataP['gateway_id'],
						  'mt5_login_id'=>	$dataP['mt5_login_id'],
						  'amount'=>	$dataP['entered_amount'],
						  'message'=>	'Successfully initilizations',
						);
						self::response(200, $returnData);
					}
				}

			}
		}else{
			redirect(base_url() . 'login');
		}

	}

	public function initialDepositExecuted(){

		$this->form_validation->set_rules('trx_id', 'Trx id', 'trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'trx_id'		=>strip_tags(form_error('trx_id')),
			);
			self::response(400,$responseData);
		}else{
			try {
				$OrderId=$_REQUEST['trx_id'];
				$getPaymentData=$this->db->query("SELECT * FROM payments where gateway_id='".$OrderId."'")->row();

				if ($getPaymentData){
					$mode='Stripe';
					if ($getPaymentData->payment_mode==3){
						$mode='Paypal';
					}
					$requestData['enterAmount']				=$getPaymentData->amount;
					$requestData['mt5_login_id']			=$getPaymentData->mt5_login_id;
					$requestData['remark']					='Using Mobile APP'.$mode;

					$getResponseGroup 	= $this->mt5_instance->depositAmount($requestData,'Using Stripe');
					if ($getResponseGroup!=false) {
						$this->db->set(array('status'=>1));
						$this->db->where('gateway_id', $OrderId);
						$this->db->update('payments');
						self::response(200, 'Successfully Deposit Amount.Tnx ID : '.$OrderId.'');
					}
				}else{
					self::response(400,'You have placed wrong ID');
				}

			} catch (\Exception $ex) {
				// Handle error
				echo $ex->getMessage() ?? 'An error occurred';
			}
		}
	}


	public function saveDepositPaypal(){

		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'		=>strip_tags(form_error('mt5_login_id')),
					'amount'			=>strip_tags(form_error('amount')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_paypal_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/deposit/paypal');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$tnx="WIRE".substr(str_shuffle($permitted_chars), 0, 24);

				$dataP = array(
					'user_id' => $request['userId'],
					'from_currency' => 'USD',
					'mt5_login_id' =>$_REQUEST['mt5_login_id'],
					'entered_amount' => $_REQUEST['amount'],
					'to_currency' => 'USD',
					'amount' => $_REQUEST['amount'],
					'payment_mode' =>3, //wire transfer
					'transaciton_detail' => $_REQUEST['meta_descriptions'],
					'gateway_id' => $tnx,
					'gateway_url' => 'NULL',
					'is_roi' => 1,
					'status' =>0,
					'created_at' => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->PaymentModel->insertPayment($dataP);
				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Deposit Place Using Paypal For this ('.$_REQUEST['mt5_login_id'].') Account',$request['userId']);

					if ($request['type'] == 'api') {
						//self::response(200, 'Successfully Create Payment');
					} else if ($request['type'] == 'web') {
						$_SESSION['payment_request_data']=json_encode ($dataP);
						self::createPaypalPayment ($insertPaymentId,$dataP);
					}
				}

			}
		}else{
			redirect(base_url() . 'login');
		}

	}

	public function createPaypalPayment($insertPaymentId,$dataP) {

		// Set payer details
		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod("paypal");

		// Set item details
		$item = new \PayPal\Api\Item();
		$item->setName($dataP['mt5_login_id'])
			->setCurrency('USD')
			->setQuantity(1)
			->setPrice($dataP['amount']);

		$itemList = new \PayPal\Api\ItemList();
		$itemList->setItems(array($item));

		// Set transaction details
		$transaction = new \PayPal\Api\Transaction();
		$transaction->setAmount(
			(new \PayPal\Api\Amount())
				->setCurrency("USD")
				->setTotal($dataP['amount'])
				->setDetails((new \PayPal\Api\Details())->setSubtotal($dataP['amount']))
		);
		$transaction->setItemList($itemList);

		// Set redirect URLs
		$redirectUrls = new \PayPal\Api\RedirectUrls();


		$redirectUrls->setReturnUrl(base_url('user/deposit/execute-payment?orderId='.$insertPaymentId.'&gatewayId='.$dataP['gateway_id'].''))
			->setCancelUrl(base_url('user/deposit/cancel-payment?orderId='.$insertPaymentId.'&gatewayId='.$dataP['gateway_id'].''));

		// Set payment details
		$payment = new \PayPal\Api\Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setTransactions(array($transaction))
			->setRedirectUrls($redirectUrls);

		try {

			$payment->create($this->apiContext);
			$approvalUrl = $payment->getApprovalLink();
			redirect($approvalUrl);

		} catch (Exception $ex) {
			// Handle error
			echo $ex->getMessage();
		}
	}

	public function executePaymentStripe(){

		$OrderId=isset($_REQUEST['orderId'])?$_REQUEST['orderId']:'';
		$gatewayId=isset($_REQUEST['gatewayId'])?$_REQUEST['gatewayId']:'';

		if (!$OrderId) {
			// Handle missing parameters
			echo "Payment ID or Payer ID missing.";
			return;
		}

		try {
			$getPaymentData=$this->db->query("SELECT * FROM payments where id='".$OrderId."'")->row();

			$requestData['enterAmount']				=$getPaymentData->amount;
			$requestData['mt5_login_id']			=$getPaymentData->mt5_login_id;
			$requestData['remark']					='Using Paypal';
			$getResponseGroup 	= $this->mt5_instance->depositAmount($requestData,'Using Stripe');

			if ($getResponseGroup!=false) {

				$this->db->set(array('status'=>1));
				$this->db->where('id', $OrderId);
				$this->db->update('payments');

				$_SESSION['success_deposit'] = 'Successfully Deposit Amount.Tnx ID : '.$gatewayId.'';
				redirect('user/deposit/stripe');

			}

		} catch (\Exception $ex) {
			// Handle error
			echo $ex->getMessage() ?? 'An error occurred';
		}
	}

	public function executePayment(){

		$paymentId = $this->input->get('paymentId');
		$payerId = $this->input->get('PayerID');

		$OrderId=isset($_REQUEST['orderId'])?$_REQUEST['orderId']:'';
		$gatewayId=isset($_REQUEST['gatewayId'])?$_REQUEST['gatewayId']:'';

		if (!$paymentId || !$payerId || !$OrderId) {
			// Handle missing parameters
			echo "Payment ID or Payer ID missing.";
			return;
		}

		$payment = \PayPal\Api\Payment::get($paymentId, $this->apiContext);

		$execution = new \PayPal\Api\PaymentExecution();
		$execution->setPayerId($payerId);

		try {
			$result 	= $payment->execute($execution, $this->apiContext);
			if ($result->state=='approved'){

				if ($result->transactions[0]){

					$getPaymentData=$this->db->query("SELECT * FROM payments where id='".$OrderId."'")->row();

					$requestData['enterAmount']				=$getPaymentData->amount;
					$requestData['mt5_login_id']			=$getPaymentData->mt5_login_id;
					$requestData['remark']					='Using Paypal';
					$getResponseGroup 	= $this->mt5_instance->depositAmount($requestData,'Using Paypal');

					if ($getResponseGroup!=false) {

						$this->db->set(array('status'=>1));
						$this->db->where('id', $OrderId);
						$this->db->update('payments');

						$_SESSION['success_deposit'] = 'Successfully Deposit Amount.Tnx ID : '.$gatewayId.'';
						redirect('user/deposit/paypal');

					}


				}else{
					$_SESSION['success_deposit'] = 'Somethings Wrong. Contact with Admin for approved your payment';
					redirect('user/deposit/paypal');
				}

			}
		} catch (\Exception $ex) {
			// Handle error
			echo $ex->getMessage() ?? 'An error occurred';
		}
	}

	// Define the callback function
	public function check_mt5_login_id($parent_id) {
		// Load the database library (if not already loaded)
		$this->load->database();

		// Query the users table to check if the parent_id exists in the unique_id column
		$this->db->where('mt5_login_id', $parent_id);
		$query = $this->db->get('trading_accounts');

		// If there is a row returned, parent_id exists in the unique_id column
		if ($query->num_rows() > 0) {
			return true; // Validation passes
		} else {
			$this->form_validation->set_message('check_mt5_login_id', 'The {field} does not exist in the Tradding.');
			return false; // Validation fails
		}
	}

	public function saveDepositWireTransfer(){
		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			//$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required|callback_check_mt5_login_id');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			//$this->form_validation->set_rules('meta_descriptions', 'Transaction Refrence Number', 'trim|required');

			if (empty($_FILES['deposit_proof']['name']))
			{
				$this->form_validation->set_rules('deposit_proof', 'Document', 'required');
			}

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'		=>strip_tags(form_error('mt5_login_id')),
					'amount'			=>strip_tags(form_error('amount')),
					'deposit_proof'		=>strip_tags(form_error('deposit_proof')),
					//'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/deposit/wire-transfer');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				if ($_FILES['deposit_proof']['name']) {
					/*-------Uploaded Site Logo Image---------*/
					$_FILES['file']['name'] 	= time().$_FILES['deposit_proof']['name'];
					$_FILES['file']['type'] 	= $_FILES['deposit_proof']['type'];
					$_FILES['file']['tmp_name'] = $_FILES['deposit_proof']['tmp_name'];
					$_FILES['file']['error'] 	= $_FILES['deposit_proof']['error'];
					$_FILES['file']['size'] 	= $_FILES['deposit_proof']['size'];

					if (!file_exists('assets/users/deposit_proof/'.$request['unique_id'].'')) {
						mkdir('assets/users/deposit_proof/'.$request['unique_id'].'', 0777, true);
					}

					//upload to a Folder
					$config['upload_path'] = 'assets/users/deposit_proof/'.$request['unique_id'];
					$config['allowed_types'] = 'jpg|png|pdf|jpeg';


					// Load and initialize upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$fileName = $_FILES['deposit_proof']['name'];

					// Upload file to server
					if (!$this->upload->do_upload('file')) {

						if($request['auth'] == 'api'){
							$responseArray=array(
								"uid" 			 => $request['unique_id'],
								'error'			 => $this->upload->display_errors(),
								'status' 		 => 400
							);
							print_r(json_encode($responseArray));
							exit();
						}else if($request['auth'] == 'web'){
							$this->session->set_flashdata('msg', 'Failed to Upload, allowed types are jpg|png|pdf|jpeg'); //set success msg if
							redirect(base_url() . 'user/deposit/wire-transfer');
						}
						$error = array(
							'error' => $this->upload->display_errors()
						);
						print_r($error);
						exit;
					} else {
						// Uploaded file data
						$imageData = $this->upload->data();
						$uploadDepositProf = $imageData['file_name'];
					}

					$uploadDepositProf	="assets/users/deposit_proof/".$request['unique_id'].'/'.$uploadDepositProf;

					$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$tnx="WIRE".substr(str_shuffle($permitted_chars), 0, 24);

					$dataP = array(
						'user_id' => $request['userId'],
						'from_currency' => 'INR',
						'mt5_login_id' =>$_REQUEST['mt5_login_id'],
						'entered_amount' => $_REQUEST['amount'],
						'to_currency' => 'INR',
						'amount' => $_REQUEST['amount'],
						'transaction_proof_attachment' => $uploadDepositProf,
						'payment_mode' =>1, //wire transfer
						'transaciton_detail' => $_REQUEST['meta_descriptions'],
						'gateway_id' => $tnx,
						'gateway_url' => 'NULL',
						'is_roi' => 1,
						'status' =>0,
						'created_at' => date("Y-m-d H:i:s")
					);

					$insertPaymentId = $this->PaymentModel->insertPayment($dataP);

					if ($insertPaymentId){

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Deposit Requested For this ('.$_REQUEST['mt5_login_id'].') Account',$request['userId']);

//						$mailHtml 	=$this->EmailConfigModel->fundDeposit($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
//						self::sendEmail($request['email'],'Fund Deposit Successful',$mailHtml);

						if ($request['type'] == 'api') {
							unset($dataP['transaction_proof_attachment']);
							if (ConfigData['prefix']=='TG'){
								self::response(200, $dataP);
							}else{
								self::response(200, 'Successfully Deposit');
							}

						} else if ($request['type'] == 'web') {
							$_SESSION['success_deposit'] = 'Successfully Deposit Amount.Tnx ID : '.$tnx.'.After confirmations balance will add in your wallet';
							redirect('user/deposit/wire-transfer');
						}
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function depositPaypal(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
				self::renderView('deposit-with-paypal',$getTradingAccount);
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function depositStripe(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
				self::renderView('deposit-with-stripe',$getTradingAccount);
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function withdrawAmount(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 			=$this->TradingAccount->getTradingAccountList(	$request['userId']	);
				$getBankDetails 			=$this->ProfileModel->getbankDetails(	$request['unique_id']	);
				$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$request['unique_id']	);
				$kycAttachments	 		    = $this->ProfileModel->getKycAttachment(	$request['unique_id']	);
				$minWithdraw	 		    = $this->UserModel->getMinWithdrawal();

				$detailsItem=array(
					'tradeAccount'=>$getTradingAccount,
					'bankAccount'=>$getBankDetails,
					'coinPaymentAddress'=>$getCoinpaymentAddress,
					'kycAttachments'=>$kycAttachments,
					'minWithdraw'=>$minWithdraw,
				);
			if($request['type'] == 'web') {
				self::renderView('withdraw',$detailsItem);
			}else if($request['type'] == 'api'){
				self::response(200,$detailsItem);
			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function withdrawIbCommission(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {

				$getTradingAccount 			=$this->TradingAccount->getIbAccountList(	$request['unique_id']	);
				$getBankDetails 			=$this->ProfileModel->getbankDetails(	$request['unique_id']	);
				$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$request['unique_id']	);
				$kycAttachments	 		    = $this->ProfileModel->getKycAttachment(	$request['unique_id']	);

				$detailsItem=array(
					'tradeAccount'=>$getTradingAccount,
					'bankAccount'=>$getBankDetails,
					'coinPaymentAddress'=>$getCoinpaymentAddress,
					'kycAttachments'=>$kycAttachments,
				);
				self::renderView('ib_withdraw',$detailsItem);
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

	public function getAvailableBalance(){
		if ($_REQUEST['mt5_login_id']){
			$getBalance = $this->mt5_instance->getAvailableBalance($_REQUEST['mt5_login_id']);
			if ($getBalance!=false){
				if ($getBalance->answer[0]) {
					$getBalanceObj = $getBalance->answer[ 0 ];
					if ( $getBalanceObj ) {
						$originalBalance = $getBalanceObj->Equity - ( $getBalanceObj->Credit + $getBalanceObj->Margin );
						print_r ( $originalBalance );
						exit();
					} else {
						echo 0;
						exit();
					}
				}else{
					echo 0;
					exit();
				}
			}else{
				echo 0;
				exit();
			}
		}
	}

	public function getTradingAccount(){
		if ($_REQUEST['from_account_id']){
			$request			=self::isAuth();
			$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId'],$_REQUEST['from_account_id']);
			$dataItem=array(	'tradingtoAcocunt'=>$getTradingAccount);
			print_r(json_encode($dataItem));
			exit();
		}
	}

	public function saveWithdrawIbCommission(){
		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			//$this->form_validation->set_rules('meta_descriptions', 'Transaction Refrence Number', 'trim|required');


			if ($_REQUEST['payout_id']==''){
				$this->form_validation->set_rules('payout_id', 'Bank/Wallet details is missing. Go to your "Profile" section and update.', 'trim|required');
			}

			if ($_REQUEST['mt5_login_id']) {
				if ($_REQUEST['totalBalance'] && $_REQUEST['amount']<=0) {
					$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
				}
			}

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					//'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
					'verified_status'		=>strip_tags(form_error('verified_status')),
					'payout_id'				=>strip_tags(form_error('payout_id')),
					'withdraw_type'			=>strip_tags(form_error('withdraw_type')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/ib-commission-withdraw');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				$coinId=$bankId='';
				if ($_REQUEST['withdraw_type']==1){
					$bankId	=$_REQUEST['payout_id'];
				}elseif($_REQUEST['withdraw_type']==2){
					$coinId	=$_REQUEST['payout_id'];
				}

				$dataWithdraw = array(
					'unique_id' => $request['unique_id'],
					'bank_id' 			=> $bankId,
					'coin_id' 			=> $coinId,
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>isset($_REQUEST['meta_descriptions'])?$_REQUEST['meta_descriptions']:'',
					'withdrawal_type' 	=>$_REQUEST['withdraw_type'],
					'ib_withdraw_status' =>1,
					'status' 			=> 1, //Requested
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

				$insertPaymentId = $this->WithdrawModel->insertWithdraw($dataWithdraw);

				if ($insertPaymentId){

					//Send Mail
					$mailHtml 	=$this->EmailConfigModel->fundWithdraw($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
					self::sendEmail($request['email'],'Fund Withdrawal request has been successfully placed.',$mailHtml);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Create Withdraw Request');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_withdraw'] = 'Successfully Create Withdraw Request';
						redirect(base_url() . 'user/ib-commission-withdraw');
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function saveWithdrawAmount(){
		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('withdraw_type', 'Withdraw Type', 'trim|required');

			/*------Payment ID------------*/
			$coinId=$bankId='';
			if ($request['type']=='api'){
				if ($_REQUEST['withdraw_type']==1){
					$getBankDetails 			=$this->ProfileModel->getbankDetails(	$request['unique_id']	);
					if (empty($getBankDetails)){
						$this->form_validation->set_rules('payout_id', 'Bank/Wallet/Coin details is missing. Go to your "Profile" section and update.', 'trim|required');
					}else {
						$bankId = $getBankDetails->bank_details_id;
					}
				}elseif($_REQUEST['withdraw_type']==2){
					$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$request['unique_id']	);
					if (empty($getCoinpaymentAddress)){
						$this->form_validation->set_rules('payout_id', 'Bank/Wallet/Coin details is missing. Go to your "Profile" section and update.', 'trim|required');
					}else {
						$coinId	=$getCoinpaymentAddress->id;
					}
				}
			}

			/*--------Use for Web----------*/
			if (isset($_REQUEST['payout_id']) && $_REQUEST['payout_id']=='' && $request['type']=='web'){
				$this->form_validation->set_rules('payout_id', 'Bank/Wallet details is missing. Go to your "Profile" section and update.', 'trim|required');
			}

			if ($_REQUEST['mt5_login_id']) {

				if ($request['type'] == 'api') {
					$balance = self::getWithdrawBalance($_REQUEST['mt5_login_id']);
					if ($balance>0 && $_REQUEST['amount']>$balance) {
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
					}elseif ($_REQUEST['amount']==0 || $_REQUEST['amount']==''){
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be greater than 0', 'required');
					}
				}else{
					$balance = self::getWithdrawBalance($_REQUEST['mt5_login_id']);
					if ($_REQUEST['amount']>$balance) {
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
					}elseif ($_REQUEST['amount']==0 || $_REQUEST['amount']==''){
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be greater than 0', 'required');
					}
				}
			}

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					//'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
					'verified_status'		=>strip_tags(form_error('verified_status')),
					'payout_id'				=>strip_tags(form_error('payout_id')),
					'withdraw_type'			=>strip_tags(form_error('withdraw_type')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/withdraw');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				if ($request['type'] == 'web') {
					if ($_REQUEST['withdraw_type'] == 1 && $bankId == '') {
						$bankId = $_REQUEST['payout_id'];
					} elseif ($_REQUEST['withdraw_type'] == 2 && $coinId == '') {
						$coinId = $_REQUEST['payout_id'];
					}
				}

				$dataWithdraw = array(
					'unique_id' => $request['unique_id'],
					'bank_id' 			=> $bankId,
					'coin_id' 			=> $coinId,
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>isset($_REQUEST['meta_descriptions'])?$_REQUEST['meta_descriptions']:'',
					'withdrawal_type' 	=>$_REQUEST['withdraw_type'],
					'status' 			=> 1, //Requested
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

				$insertPaymentId = $this->WithdrawModel->insertWithdraw($dataWithdraw);

				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Withdraw Request Place From - '.$_REQUEST['mt5_login_id'].'',$request['userId']);

					//Send Mail
					$mailHtml 	=$this->EmailConfigModel->fundWithdraw($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
					self::sendEmail($request['email'],'Fund Withdrawal request has been successfully placed.',$mailHtml);

					if ($request['type'] == 'api') {
						$response=array(
							'status'=>200,
							'message'=>'Fund Withdrawal request has been successfully placed',
							'data'=>array(
								'accountId'=>$_REQUEST['mt5_login_id']
							),
						);
						print_r(json_encode($response));
						exit();
					} else if ($request['type'] == 'web') {
						$_SESSION['success_withdraw'] = 'Successfully Create Withdraw Request';
						redirect(base_url() . 'user/withdraw');
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}


	public function internalTransfer(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
				self::renderView('internal_transfer',$getTradingAccount);
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function saveInternalTransfer(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web' || $request['type']=="api") {

				$this->form_validation->set_rules('from_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('to_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required');

				if ($_REQUEST['from_mt5_login_id']) {

					if ($request['type']=='api'){
						$getBalance 				= self::getWithdrawBalance($_REQUEST['from_mt5_login_id']);
						$_REQUEST['totalBalance']	=0;

						if ($getBalance){
							$_REQUEST['totalBalance']=$getBalance;
						}
					}

					if ($_REQUEST['amount']<=0) {
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be greater than 0', 'required');
					}else if ($_REQUEST['totalBalance'] && $_REQUEST['amount']>$_REQUEST['totalBalance']) {
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
					}else if($_REQUEST['from_mt5_login_id']==$_REQUEST['to_mt5_login_id']){
						$this->form_validation->set_rules('verified_status', 'You can not transfer in same account', 'required');
					}
				}

				if ($this->form_validation->run() == FALSE)
				{
					/*--------Error Response------*/
					$responseData	=array(
						'from_mt5_login_id'				=>strip_tags(form_error('from_mt5_login_id')),
						'to_mt5_login_id'			=>strip_tags(form_error('to_mt5_login_id')),
						'amount'				=>strip_tags(form_error('amount')),
						'verified_status'				=>strip_tags(form_error('verified_status')),
					);

					if($request['type'] == 'web'){

						$_SESSION['error_transfer']	=json_encode($responseData,true);
						$_SESSION['request_data']	=json_encode($_REQUEST,true);
						redirect(base_url() . 'user/internal-transfer');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}else{

					$_REQUEST['mt5_login_id']		=$_REQUEST['from_mt5_login_id'];
					$_REQUEST['enterAmount']		=$_REQUEST['amount'];
					$_REQUEST['remark']				='Int. Transfer to '.$_REQUEST['to_mt5_login_id'].'';
					$getResponseGroup 				= $this->mt5_instance->withdrawAmount($_REQUEST);

					if ($getResponseGroup!=false) {

						$_REQUEST['remark']				='Add Amount From '.$_REQUEST['from_mt5_login_id'].'';
						$depositResponse 				= $this->mt5_instance->depositAmountInternalTransfer($_REQUEST);
						if ($depositResponse!=false) {
							$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
							$tnx = "TRANSFER" . substr(str_shuffle($permitted_chars), 0, 24);

							$dataP = array(
								'user_id' => $request['userId'],
								'from_currency' => 'INR',
								'mt5_login_id' => $_REQUEST['to_mt5_login_id'],
								'entered_amount' => $_REQUEST['amount'],
								'to_currency' => 'INR',
								'amount' => $_REQUEST['amount'],
								'transaction_proof_attachment' => '',
								'payment_mode' => 5, //wire transfer
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
									'user_id' => $request['userId'],
									'payment_id' => $insertPaymentId,
									'mt5_login_id' => $_REQUEST['from_mt5_login_id'],
									'transfer_amount' => $_REQUEST['amount'],
									'status' => 1,
								);
								$transferAmount = $this->PaymentModel->insertInternalTransferPayment($dataForTransfer);
								if ($transferAmount) {

									$mailHtml 	=$this->EmailConfigModel->fundTransfer($request['fullName'],$_REQUEST['amount'],$_REQUEST['from_mt5_login_id'],$_REQUEST['to_mt5_login_id']);
									self::sendEmail($request['email'],'Successful Fund Transfer from One Trading Account to Another',$mailHtml);

									if ($request['type'] == 'api') {
										self::response(200, 'Successfully Transfer '.$_REQUEST['amount'].' To '.$_REQUEST['to_mt5_login_id']);
									} else if ($request['type'] == 'web') {
										$_SESSION['success_transfer'] = 'Successfully Transfer';
										redirect(base_url() . 'user/internal-transfer');
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
							redirect(base_url() . 'user/internal-transfer');

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
	public function isAuth(){
		$getHeader	=$this->input->request_headers();
		$eventFrom=array();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
				$uniqueKey		=$getHeader['Authorization'];
				$getUserInfo 	= $this->UserModel->getUser($uniqueKey);
				if ($getUserInfo){
					$eventFrom=array('type'=>'api',
						'auth'=>true,
						'userId'    =>$getUserInfo->user_id,
						'unique_id' =>$getUserInfo->unique_id,
						'email'     =>$getUserInfo->email,
						'fullName'  =>$getUserInfo->first_name.' '.$getUserInfo->last_name
					);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			if ($this->session->userdata('username') != '' && ($this->session->userdata('role') ==1)){
				$getUserInfo 	= $this->UserModel->getUser($this->session->userdata('unique_id'));
				$eventFrom=array(
					'type'      =>'web',
					'auth'       =>true,
					'userId'     =>$this->session->userdata('user_id'),
					'unique_id'  =>$this->session->userdata('unique_id'),
					'email'      =>$getUserInfo->email,
					'fullName'   =>$getUserInfo->first_name.' '.$getUserInfo->last_name
				);
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
	public function renderView($fileName,$data='',$params=''){
		$title['title']			='Funding';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/funding/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
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
	
		public function ibComWithdrawAmount(){

		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 			=$this->TradingAccount->getIbTransferAccount($request['unique_id']);
			$getBankDetails 			=$this->ProfileModel->getbankDetails(	$request['unique_id']	);
			$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$request['unique_id']	);
			$kycAttachments	 		    = $this->ProfileModel->getKycAttachment(	$request['unique_id']	);
			$minWithdraw	 		    = $this->UserModel->getMinWithdrawal();
			$getCommBalance 		    =$this->IbModel->newCommissionBalance($request['unique_id']);

			$detailsItem=array(
				'tradeAccount'=>$getTradingAccount,
				'bankAccount'=>$getBankDetails,
				'coinPaymentAddress'=>$getCoinpaymentAddress,
				'kycAttachments'=>$kycAttachments,
				'minWithdraw'=>$minWithdraw,
				'comBalance'=>$getCommBalance,
			);
			if($request['type'] == 'web') {
				self::renderView('ib_com_withdraw',$detailsItem);
			}else if($request['type'] == 'api'){
				self::response(200,$detailsItem);
			}

		}else{
			redirect(base_url() . 'login');
		}
	}
	
	public function ibComWithdrawHistory(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getWithdrawData('',$request['unique_id'],$request['type'],'ib_com_withdrawal');
			if($request['type'] == 'web'){
				self::renderView('ib_com_withdraw_history',$getDepositHistory);
			}else if($request['type'] == 'api'){
				self::response(200,$getDepositHistory);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}
	
		public function saveIbComWithdrawAmount(){
		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('withdraw_type', 'Withdraw Type', 'trim|required');

			/*------Payment ID------------*/
			$coinId=$bankId='';
			if ($request['type']=='api'){
				if ($_REQUEST['withdraw_type']==1){
					$getBankDetails 			=$this->ProfileModel->getbankDetails(	$request['unique_id']	);
					if (empty($getBankDetails)){
						$this->form_validation->set_rules('payout_id', 'Bank/Wallet/Coin details is missing. Go to your "Profile" section and update.', 'trim|required');
					}else {
						$bankId = $getBankDetails->bank_details_id;
					}
				}elseif($_REQUEST['withdraw_type']==2){
					$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$request['unique_id']	);
					if (empty($getCoinpaymentAddress)){
						$this->form_validation->set_rules('payout_id', 'Bank/Wallet/Coin details is missing. Go to your "Profile" section and update.', 'trim|required');
					}else {
						$coinId	=$getCoinpaymentAddress->id;
					}
				}
			}

			/*--------Use for Web----------*/
			if (isset($_REQUEST['payout_id']) && $_REQUEST['payout_id']=='' && $request['type']=='web'){
				$this->form_validation->set_rules('payout_id', 'Bank/Wallet details is missing. Go to your "Profile" section and update.', 'trim|required');
			}

			if ($_REQUEST['mt5_login_id']) {
				$balance = $this->IbModel->newCommissionBalance($request['unique_id']);
				if ($_REQUEST['amount']>$balance) {
					$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
				}elseif ($_REQUEST['amount']==0 || $_REQUEST['amount']==''){
					$this->form_validation->set_rules('verified_status', 'Withdraw amount should be greater than 0', 'required');
				}
			}

			$checkPendingTransferStatus=$this->IbModel->checkIbComPendingStatus($request['unique_id']);
			if ($checkPendingTransferStatus->totalCount){
				$this->form_validation->set_rules('verified_status', 'withdrawal request is already in queue', 'required'); // Removed 'required'
				$this->form_validation->set_message('required', 'The withdrawal request is already in queue'); // Set custom error message
			}

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					'verified_status'		=>strip_tags(form_error('verified_status')),
					'payout_id'				=>strip_tags(form_error('payout_id')),
					'withdraw_type'			=>strip_tags(form_error('withdraw_type')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/ib-com-withdraw-manual-from-crm');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				if ($request['type'] == 'web') {
					if ($_REQUEST['withdraw_type'] == 1 && $bankId == '') {
						$bankId = $_REQUEST['payout_id'];
					} elseif ($_REQUEST['withdraw_type'] == 2 && $coinId == '') {
						$coinId = $_REQUEST['payout_id'];
					}
				}

				$dataWithdraw = array(
					'unique_id' => $request['unique_id'],
					'bank_id' 			=> $bankId,
					'coin_id' 			=> $coinId,
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>isset($_REQUEST['meta_descriptions'])?$_REQUEST['meta_descriptions']:'',
					'withdrawal_type' 	=>$_REQUEST['withdraw_type'],
					'status' 			=>1, //Requested
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

				$insertPaymentId = $this->WithdrawModel->insertIbComWithdraw($dataWithdraw);

				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('$'.$_REQUEST['amount'].' Withdraw Request Place From Ib Commission - '.$_REQUEST['mt5_login_id'].'',$request['userId']);

					//Send Mail
					$mailHtml 	=$this->EmailConfigModel->fundWithdraw($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
					self::sendEmail($request['email'],'IB Commission Fund Withdrawal request has been successfully placed.',$mailHtml);

					if ($request['type'] == 'api') {
						$response=array(
							'status'=>200,
							'message'=>'Fund Withdrawal request has been successfully placed',
							'data'=>array(
								'accountId'=>$_REQUEST['mt5_login_id']
							),
						);
						print_r(json_encode($response));
						exit();
					} else if ($request['type'] == 'web') {
						$_SESSION['success_withdraw'] = 'Successfully Create Withdraw Request';
						redirect(base_url() . 'user/ib-com-withdraw-manual-from-crm');
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}
	
	public function getAvailableIbCommBalance(){
		$request	=self::isAuth();
		$getBalance 	=$this->IbModel->newCommissionBalance($request['unique_id']);
		print_r($getBalance);
		exit();
	}
	
}
