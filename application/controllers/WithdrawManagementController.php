<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WithdrawManagementController extends MY_Controller {

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
		$this->load->model('ProfileModel');

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
	public function requestWithdrawList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',1);
			if($request['type'] == 'web'){
				self::renderView('requested_withdraw_history',$getWithdrawData,'','Request Withdraw');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function rejectedWithdrawList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',3);
			if($request['type'] == 'web'){
				self::renderView('rejected_withdraw_history',$getWithdrawData,'','Rejected Withdraw');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function userIbWithdrawAmount(){
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$userList['details'] 	=$this->UserModel->getUserDropdownList(1);
				$userList['min_withdrawal'] 	=$this->UserModel->getMinWithdrawal();
				

				$title['title']	='Withdrawal';
	            $this->load->view('includes/header',$title);
	            $this->load->view('includes/left_side_bar');
	            $this->load->view('admin/withdraw/user_ib_withdraw',$userList);
	            $this->load->view('includes/footer');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function userWithdrawAmount(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$userList 	=$this->UserModel->getUserDropdownList();
				self::renderView('user_withdraw',$userList);
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function getUserBankAndAccountDetails(){
		if (isset($_REQUEST['unique_id'])){
			$getUserInfo 				= $this->UserModel->getUser($_REQUEST['unique_id']);
			$getBankDetails 			=$this->ProfileModel->getbankDetails(	$_REQUEST['unique_id']	);
			$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$_REQUEST['unique_id']	);
			$getTradingAccount 			=$this->TradingAccount->getTradingAccountList(	$getUserInfo->user_id	);
			$kycAttachments	 			= $this->ProfileModel->getKycAttachment(	$_REQUEST['unique_id']	);

			$dataItem=array(
				'bankDetails'			=>$getBankDetails,
				'getTradingAccount'		=>$getTradingAccount,
				'coinPaymentAddress'    =>$getCoinpaymentAddress,
				'kycAttachments'    =>$kycAttachments,
			);

			print_r(json_encode($dataItem));
			exit();
		}
	}

	public function getIbUserBankAndAccountDetails(){
		if (isset($_REQUEST['unique_id'])){

			$getUserInfo 				= $this->UserModel->getUser($_REQUEST['unique_id']);
			$getBankDetails 			=$this->ProfileModel->getbankDetails(	$_REQUEST['unique_id']	);
			$getCoinpaymentAddress 		= $this->ProfileModel->getcoinpaymentdetails(	$_REQUEST['unique_id']	);
			$getTradingAccount 			=$this->TradingAccount->getIbAccountList( $getUserInfo->unique_id	);
			$kycAttachments	 			= $this->ProfileModel->getKycAttachment( $_REQUEST['unique_id']	);

			$dataItem=array(
				'bankDetails'			=>$getBankDetails,
				'getTradingAccount'		=>$getTradingAccount,
				'coinPaymentAddress'    =>$getCoinpaymentAddress,
				'kycAttachments'    =>$kycAttachments,
			);

			print_r(json_encode($dataItem));
			exit();
		}
	}

	public function paidUserWithdrawAmount(){
		$request	=self::isAuth(false);

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('payout_id', 'Payout Details', 'trim|required');
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			//$this->form_validation->set_rules('meta_descriptions', 'Transaction Refrence Number', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'payout_id'				=>strip_tags(form_error('payout_id')),
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					//'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
					'verified_status'		=>strip_tags(form_error('verified_status')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/withdraw/user-withdraw-create');

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
					'unique_id' => 		$_REQUEST['unique_id'],
					'bank_id' 			=>$bankId,
					'coin_id' 			=>$coinId,
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>$_REQUEST['meta_descriptions'],
					'withdrawal_type' 	=>$_REQUEST['withdraw_type'],
					'status' 			=>2, //Paid
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

				$_REQUEST['enterAmount']		=$_REQUEST['amount'];
				$_REQUEST['remark']				=$_REQUEST['meta_descriptions'];
				$getResponseGroup 				= $this->mt5_instance->withdrawAmount($_REQUEST);

				if ($getResponseGroup!=false) {

					$insertPaymentId 			= $this->WithdrawModel->insertWithdraw($dataWithdraw);
					$updateTradingBalance 		= $this->TradingAccount->updateBalance($_REQUEST, $getResponseGroup);

					if ($insertPaymentId && $updateTradingBalance) {

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Withdraw | '.$_REQUEST['mt5_login_id'].' | Amount : '.$_REQUEST['amount'].'',$request['userId']);

						//Send Mail
						$mailHtml = $this->EmailConfigModel->fundWithdraw($request['fullName'], $_REQUEST['amount'], $_REQUEST['mt5_login_id']);
						self::sendEmail($request['email'], 'Fund Withdrawal request has been successfully placed.', $mailHtml);

						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Withdraw');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_withdraw'] = 'Successfully Withdraw';
							redirect(base_url() . 'admin/withdraw/user-withdraw-create');
						}
					}
				}else{

					/*--------Handelling Mt5 Creating Error Response------*/
					$responseData	=array(
						'status'    		=> 400,
						'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
					);

					if($request['type'] == 'web'){
						$_SESSION['error_withdraw']			=json_encode($responseData,true);
						$_SESSION['request_data']			=json_encode($_REQUEST,true);
						redirect(base_url() . 'admin/withdraw/user-withdraw-create');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function paidUserIbWithdrawAmount(){
		$request	=self::isAuth(false);

		if($request['auth']==true) {

			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('payout_id', 'Payout Details', 'trim|required');
			$this->form_validation->set_rules('mt5_login_id', 'Account', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			//$this->form_validation->set_rules('meta_descriptions', 'Transaction Refrence Number', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'payout_id'				=>strip_tags(form_error('payout_id')),
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'amount'				=>strip_tags(form_error('amount')),
					//'meta_descriptions'		=>strip_tags(form_error('meta_descriptions')),
					'verified_status'		=>strip_tags(form_error('verified_status')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_withdraw']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/withdraw/user-ib-withdraw-create');

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
					'unique_id' => 		$_REQUEST['unique_id'],
					'bank_id' 			=>$bankId,
					'coin_id' 			=>$coinId,
					'requested_amount' => $_REQUEST['amount'],
					'mt5_login_id' 		=>$_REQUEST['mt5_login_id'],
					'user_remark' 		=>$_REQUEST['meta_descriptions'],
					'withdrawal_type' 	=>$_REQUEST['withdraw_type'],
					'status' 			=>2, //Paid
					'ib_withdraw_status'=>1, //Paid
					'withdrawal_fee' 	=>0,
					'withdrawal_code' => 'MT5_'.time(),
					'requested_datetime' => date("Y-m-d h:i:s")
				);

				$_REQUEST['enterAmount']		=$_REQUEST['amount'];
				$_REQUEST['remark']				=$_REQUEST['meta_descriptions'];
				$getResponseGroup 				= $this->mt5_instance->withdrawAmount($_REQUEST);

				if ($getResponseGroup!=false) {

					$insertPaymentId 			= $this->WithdrawModel->insertWithdraw($dataWithdraw);
					$updateTradingBalance 		= $this->TradingAccount->updateBalance($_REQUEST, $getResponseGroup);

					if ($insertPaymentId && $updateTradingBalance) {

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Ib Commission Withdraw | '.$_REQUEST['mt5_login_id'].'',$request['userId']);

						//Send Mail
						$mailHtml = $this->EmailConfigModel->fundWithdraw($request['fullName'], $_REQUEST['amount'], $_REQUEST['mt5_login_id']);
						self::sendEmail($request['email'], 'Fund Withdrawal request has been successfully placed.', $mailHtml);

						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Withdraw');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_withdraw'] = 'Successfully Withdraw';
							redirect(base_url() . 'admin/withdraw/user-ib-withdraw-create');
						}
					}
				}else{

					/*--------Handelling Mt5 Creating Error Response------*/
					$responseData	=array(
						'status'    		=> 400,
						'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
					);

					if($request['type'] == 'web'){
						$_SESSION['error_withdraw']			=json_encode($responseData,true);
						$_SESSION['request_data']			=json_encode($_REQUEST,true);
						redirect(base_url() . 'admin/withdraw/user-ib-withdraw-create');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}

			}

		}else{
			redirect(base_url() . 'login');
		}
	}

	public function approveWithdrawList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',2); //paid
			if($request['type'] == 'web'){
				self::renderView('approve_withdraw_history',$getWithdrawData,'','Approve Withdraw List');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}


	public function changeWithdrawStatus(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$this->form_validation->set_rules('withdrawId', 'Withdraw ID', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'withdrawId'		=>strip_tags(form_error('withdrawId')),
					'status'			=>strip_tags(form_error('status')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'user-single-withdraw-item-details/'.$_REQUEST['withdrawId'].'');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{

				/*-------------if and only payment is approved--------*/
				if ($_REQUEST['status']==2) {

					$getResponseGroup = $this->mt5_instance->withdrawAmount($_REQUEST);
					if ($getResponseGroup!=false) {

						$_REQUEST['mt5_response']	=$getResponseGroup;
						$updateStatus 			= $this->WithdrawModel->updateWithdrawStatus($_REQUEST);
						$updateTradingBalance 	= $this->TradingAccount->updateBalance($_REQUEST,$getResponseGroup);

						if ($updateStatus && $updateTradingBalance) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Approved Withdraw Request | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);

							$getUserInfo 	= $this->UserModel->getUser($_REQUEST['unique_id']);
							//Approve Confirmations Send to the user
							$mailHtml 	=$this->EmailConfigModel->fundTransferApprove($getUserInfo->first_name.' '.$getUserInfo->last_name,$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
							self::sendEmail($_REQUEST['email'],'Requested Payout Transferred',$mailHtml);

							if ($request['type'] == 'web') {
								$_SESSION['success_withdraw_message'] = 'Successfully Done Withdraw';
								redirect(base_url() . 'user-single-withdraw-item-details/'.$_REQUEST['withdrawId'].'');
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
							redirect(base_url() . 'user-single-withdraw-item-details/'.$_REQUEST['withdrawId'].'');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}else{
					$updateStatus 			= $this->WithdrawModel->updateWithdrawStatus($_REQUEST);
					if ($updateStatus) {

						if ($_REQUEST['status']==1){
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Request Withdraw Status To Pending | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);
						}else{
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Request Withdraw Status To Rejected | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);
						}


						if ($request['type'] == 'web') {
							$_SESSION['success_withdraw_message'] = 'Successfully Change Status';
							redirect(base_url() . 'user-single-withdraw-item-details/'.$_REQUEST['withdrawId'].'');
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


	public function withDrawDetails($singleDepositId=''){
		$request	=self::isAuth(false);

		if($request['auth']==true) {

			$getSingleWithdraw 	= $this->WithdrawModel->getWithdrawDetailsWithUser($singleDepositId);//pending
			if($request['type'] == 'web'){
				self::renderView('withdraw_details',$getSingleWithdraw,'','Withdraw Details');
			}else if($request['type'] == 'api'){
				self::response(200,$getSingleWithdraw);
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
		$this->load->view('admin/withdraw/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
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
	
		public function requestIbComWithdrawList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',1,'ib_com_withdrawal');
			if($request['type'] == 'web'){
				self::renderIbComView('requested_withdraw_history',$getWithdrawData,'','Request Withdraw');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	
	public function IbComWithDrawDetails($singleDepositId=''){
		$request	=self::isAuth(false);

		if($request['auth']==true) {

			$getSingleWithdraw 	= $this->WithdrawModel->getWithdrawDetailsWithUser($singleDepositId,'ib_com_withdrawal');//pending
			if($request['type'] == 'web'){
				self::renderIbComView('withdraw_details',$getSingleWithdraw,'','Withdraw Details');
			}else if($request['type'] == 'api'){
				self::response(200,$getSingleWithdraw);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	
		public function changeIbComWithdrawStatus(){
		$request	=self::isAuth(false);

		if($request['auth']==true) {

			$this->form_validation->set_rules('withdrawId', 'Withdraw ID', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'withdrawId'		=>strip_tags(form_error('withdrawId')),
					'status'			=>strip_tags(form_error('status')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_wire_transfer']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'user-single-ib-com-withdraw-item-details/'.$_REQUEST['withdrawId'].'');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				$balance = $this->IbModel->newCommissionBalance($request['unique_id']);
				if ($_REQUEST['enterAmount']<=$balance){
					$updateStatus 			= $this->WithdrawModel->updateWithdrawStatus($_REQUEST,'ib_com_withdrawal');
					if ($updateStatus) {
						if ($_REQUEST['status']==1){
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Request Withdraw Status To Pending | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);
						}elseif ($_REQUEST['status']==2){
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Request Withdraw Status To Paid | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);
						}else{
							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Request Withdraw Status To Rejected | '.$_REQUEST['mt5_login_id'].' | Amount : $'.$_REQUEST['amount'].'',$request['userId']);
						}

						if ($request['type'] == 'web') {
							$_SESSION['success_withdraw_message'] = 'Successfully Change Status';
							redirect(base_url() . 'user-single-ib-com-withdraw-item-details/'.$_REQUEST['withdrawId'].'');
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
	
		public function rejectedIbComWithdrawList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',3,'ib_com_withdrawal');
			if($request['type'] == 'web'){
				self::renderIbComView('rejected_withdraw_history',$getWithdrawData,'','Rejected Withdraw');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	
		public function approveIbComWithdrawList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getWithdrawData 	= $this->WithdrawModel->getWithdrawDataList('',2,'ib_com_withdrawal'); //paid
			if($request['type'] == 'web'){
				self::renderIbComView('approve_withdraw_history',$getWithdrawData,'','Approve Withdraw List');
			}else if($request['type'] == 'api'){
				self::response(200,$getWithdrawData);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
}
