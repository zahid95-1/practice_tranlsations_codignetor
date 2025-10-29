<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DepositManagementController extends MY_Controller {

	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
		$this->load->model('TradingAccount');
		$this->load->model('UserModel');
		$this->load->model('PaymentModel');
		$this->load->model('WithdrawModel');
		$this->load->model('EmailConfigModel');

		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();

		$this->load->model('PermissionModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function pendingDepositList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getDepositByStatus(0);//pending
			if($request['type'] == 'web'){
				self::renderView('pending_deposit_list',$getDepositHistory,'','Pending Deposit');
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

	public function rejectedDepositList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getDepositByStatus(2);//pending
			if($request['type'] == 'web'){
				self::renderView('rejected_deposit_list',$getDepositHistory,'','Rejected Deposit');
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

	public function approveDepositList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getDepositHistory 	= $this->PaymentModel->getDepositByStatus(1);//pending
			if($request['type'] == 'web'){
				self::renderView('approve_deposit_list',$getDepositHistory,'','Approved Deposit');
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

	public function changePaymentStatus(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$this->form_validation->set_rules('paymentId', 'Payment ID', 'trim|required');
			$this->form_validation->set_rules('payment_status', 'Payment status', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'paymentId'		=>strip_tags(form_error('paymentId')),
					'payment_status'			=>strip_tags(form_error('payment_status')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user-single-deposit-item-details/'.$_REQUEST['paymentId'].'');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				$getUserFromPaymentModel 	= $this->PaymentModel->getUserModel($_REQUEST['paymentId']);//pending

				/*-------------if and only payment is approved--------*/
				if ($_REQUEST['payment_status']==1) {
					$getResponseGroup = $this->mt5_instance->depositAmount($_REQUEST);
					if ($getResponseGroup!=false) {

						$updateStatus 			= $this->PaymentModel->updatePayment($_REQUEST,$getUserFromPaymentModel);//Confrim
						$updateTradingBalance 	= $this->TradingAccount->updateBalance($_REQUEST,$getResponseGroup);

						if ($updateStatus && $updateTradingBalance) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Approved Deposit Request | '.$_REQUEST['mt5_login_id'].'');

							$mailHtml 	=$this->EmailConfigModel->fundDeposit($getUserFromPaymentModel->first_name.' '.$getUserFromPaymentModel->last_name,$_REQUEST['enterAmount'],$_REQUEST['mt5_login_id']);
							self::sendEmail($getUserFromPaymentModel->email,'Fund Deposit Successful',$mailHtml);

							if ($request['type'] == 'web') {
								$_SESSION['success_deposit_message'] = 'Successfully Done Deposit';
								redirect(base_url() . 'user-single-deposit-item-details/'.$_REQUEST['paymentId'].'');
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
							redirect(base_url() . 'user-single-deposit-item-details/'.$_REQUEST['paymentId'].'');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}else{
					$updateStatus 	= $this->PaymentModel->updatePayment($_REQUEST);//pending
					if ($updateStatus) {

						if ($_REQUEST['payment_status']==0){
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Pending Deposit Request | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['enterAmount'].'');
						}else{
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Rejected Deposit Request | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['enterAmount'].'');
						}


						if ($request['type'] == 'web') {
							$_SESSION['success_deposit_message'] = 'Successfully Change Status';
							redirect(base_url() . 'user-single-deposit-item-details/'.$_REQUEST['paymentId'].'');
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

	public function depositDetails($singleDepositId=''){
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getDepositSingleData 	= $this->PaymentModel->getDepositDetailsWithUser($singleDepositId);//pending
			if($request['type'] == 'web'){
				self::renderView('deposit_details',$getDepositSingleData,'','Deposit Details');
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

	public function userDepositAmount(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$userList 	=$this->UserModel->getUserDropdownList();
				self::renderView('user_deposit',$userList,'','User Deposit');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function approveDepositAmount(){

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
					'mt5_login_id'		=>strip_tags(form_error('mt5_login_id')),
					'amount'			=>strip_tags(form_error('amount')),
					'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/deposit/user-deposit-create');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				$_REQUEST['enterAmount']		=$_REQUEST['amount'];
				$_REQUEST['remark']				=$_REQUEST['meta_descriptions'];
				$getResponseGroup 				= $this->mt5_instance->depositAmount($_REQUEST,'By Admin');

				if ($getResponseGroup!=false) {
					$getUserInfo = $this->UserModel->getUser($_REQUEST['unique_id']);
					$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$tnx = "WIRE" . substr(str_shuffle($permitted_chars), 0, 24);

					$dataP = array(
						'user_id' => $getUserInfo->user_id,
						'from_currency' => 'INR',
						'mt5_login_id' => $_REQUEST['mt5_login_id'],
						'entered_amount' => $_REQUEST['amount'],
						'to_currency' => 'INR',
						'amount' => $_REQUEST['amount'],
						'transaction_proof_attachment' => '',
						'payment_mode' => 4, //wire transfer
						'transaciton_detail' => $_REQUEST['meta_descriptions'],
						'gateway_id' => $tnx,
						'gateway_url' => 'NULL',
						'is_roi' => 1,
						'status' =>1,
						'created_at' => date("Y-m-d H:i:s")
					);

					$insertPaymentId 		= $this->PaymentModel->insertPayment($dataP);
					$updateTradingBalance 	= $this->TradingAccount->updateBalance($_REQUEST,$getResponseGroup);

					if ($insertPaymentId && $updateTradingBalance) {

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Deposit Balance To : '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'');

						$mailHtml = $this->EmailConfigModel->fundDeposit($getUserInfo->first_name.' '.$getUserInfo->last_name, $_REQUEST['amount'], $_REQUEST['mt5_login_id']);
						self::sendEmail($getUserInfo->email, 'Fund Deposit Successful', $mailHtml);

						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Create Payment');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_deposit'] = 'Successfully Deposit Amount.Tnx ID : ' . $tnx . '';
							redirect('admin/deposit/user-deposit-create');
						}
					}
				}

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
					$eventFrom=array('type'=>'api','auth'=>true,'userId'=>$getUserInfo->user_id,'unique_id'=>$getUserInfo->unique_id);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			if ($validate==false){
				if ($this->session->userdata('username') != '') {
					$eventFrom = array('type' => 'web', 'auth' => true, 'userId' => $this->session->userdata('user_id'), 'unique_id' => $this->session->userdata('unique_id'));
				}
			}else {
				$checkPermission = $this->PermissionModel->checkExistPermission($this->session->userdata('user_id'), $this->actionName);
				if ($checkPermission) {
					if ($this->session->userdata('username') != '') {
						$eventFrom = array('type' => 'web', 'auth' => true, 'userId' => $this->session->userdata('user_id'), 'unique_id' => $this->session->userdata('unique_id'));
					}
				} else {
					redirect(base_url() . 'error/404');
				}
			}
		}
		return $eventFrom;
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
		$title['title']			=$requestTitle;
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/deposit/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
