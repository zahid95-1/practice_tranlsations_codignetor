<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TradingController extends MY_Controller {
	private $mt5_instance="";
	function __construct()
	{
		parent::__construct();
		$this->load->model('GroupModel');
		$this->load->model('TradingAccount');
		$this->load->model('UserModel');
		$this->load->model('EmailConfigModel');
		$this->load->model('PaymentModel');

		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function openNewAccount()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getGroupList 		=$this->GroupModel->getGroup('',$request['role'],$_SESSION['unique_id']);
				self::renderView('open_new_account', $getGroupList,'','Open New Account');
			}else{
				$getGroupList 		=$this->GroupModel->getGroup('',$request['role'],$request['unique_id']);
				self::response(200,$getGroupList);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function maintain the mt5 trading account creations and prepare api for android application
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function createLiveAccount(){

		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Trading account info  Field Options-------------*/
			$this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
			$this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
			//$this->form_validation->set_rules('pass_main', 'Password Main', 'required|min_length[8]|callback_password_check');
			//$this->form_validation->set_rules('pass_investor', 'Password Investor', 'required|min_length[8]|callback_password_check');
			$this->form_validation->set_rules('leverage', 'Leverage','trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'message'    		=>"You have to select at least one group card",
					'group_id'			=>strip_tags(form_error('group_id')),
					'group_name'		=>strip_tags(form_error('group_name')),
					'pass_main'			=>strip_tags(form_error('pass_main')),
					'pass_investor'		=>strip_tags(form_error('pass_investor')),
					'leverage'			=>strip_tags(form_error('leverage')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_open_account']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/open-account');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{

				try {
					/*--------Create MT5 Trade Account------*/

					if ($request['type'] == 'api') {
						$getHeader		=$this->input->request_headers();
						$uniqueKey		=$getHeader['Authorization'];
						$userInfo 		= $this->UserModel->getUser($uniqueKey);
					}else{
						$userInfo = $this->UserModel->getUser($this->session->userdata('unique_id'));
					}

					/*---Get Agent ID---*/
					$agentId='';
					if ($userInfo->parent_id){
						$p_id=$userInfo->parent_id;
						$checkParentIb	=$this->db->query("SELECT ib_accounts.mt5_login_id FROM `users` u
															INNER JOIN ib_accounts
															ON ib_accounts.unique_id=u.unique_id
															where u.unique_id='$p_id' and u.ib_status=1")->row();
						if ($checkParentIb){
							$agentId=$checkParentIb->mt5_login_id;
						}
					}

					/*---Get Agent ID---*/
					$permitted_chars 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$investorPassword	=substr(str_shuffle($permitted_chars), 0, 10);

					$lastTradingId='';
//					if (ConfigData['prefix']=='IGM') {
//						$checkParentIb = $this->db->query("SELECT * from `trading_accounts` order by id desc")->row();
//						$lastTradingId = $checkParentIb->mt5_login_id;
//					}

					$getTradingAccountResponse	=$this->mt5_instance->createTradingAccount($_REQUEST,$userInfo,$investorPassword,$agentId,$lastTradingId);

					if ($getTradingAccountResponse!=false) {

						$groupName   =$_REQUEST['group_name'];
						$leverage    =$_REQUEST['leverage'];

						/*--------Create Trade Account Informations------*/
						unset($_REQUEST['plan']);
						unset($_REQUEST['group_name']);

						$mt5TradingObject			=json_decode($getTradingAccountResponse);

						$data['mt5_login_id'] 		=$mt5TradingObject->answer->Login;
						$data['balance'] 			=$mt5TradingObject->answer->Balance;
						$data['created_by'] 		= $userInfo->user_id;
						$data['group_id'] 			=$_REQUEST['group_id'];
						$data['account_type_status'] =isset($_REQUEST['accountType'])?$_REQUEST['accountType']:'';
						$data['mt5_response'] 		= $getTradingAccountResponse;
						$data['client'] 			= $request['type'];
						$data['leverage'] 			= $_REQUEST['leverage'];
						$data['user_id'] 			= $userInfo->user_id;
						$data['pass_main'] 			= openssl_decrypt($userInfo->raw_pwd,"AES-128-ECB",'password');
						$data['pass_investor'] 		= $investorPassword;

						if ($data['account_type_status']==1){
							$getLiveRate 	= $this->PaymentModel->getLiveRate();
							$data['live_rate'] 						= $getLiveRate['live_rate'];
						}

						$getGroupID = $this->TradingAccount->insertTradingAccount($data);

						if ($getGroupID) {

							//Email Send
							$mailHtml 	=$this->EmailConfigModel->createTradingAccount($userInfo->first_name.' '.$userInfo->last_name ,$data['mt5_login_id'],$data['pass_main'],$groupName,$leverage,$investorPassword);
							self::sendEmail($userInfo->email, 'Congratulations! Your Live Trading Account has been created', $mailHtml);

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Create Trading Account - '.$mt5TradingObject->answer->Login.'',$request['userId']);


							if ($request['type'] == 'api') {
								unset($data['mt5_response']);
								self::response(200, $data);
							} else if ($request['type'] == 'web') {
								$_SESSION['success_trading_account'] = 'Successfully Create Trading Account';
								redirect('user/my-mt5-account-list');
							}
						}

					}else{

						/*--------Maintain Mt5 Creating Error Response------*/
						$responseData	=array(
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_open_account']	=json_encode($responseData,true);
							$_SESSION['request_data']	=json_encode($_REQUEST,true);
							redirect(base_url() . 'user/open-account');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}catch (Exception $error){
					print_r($error->getMessage());
					exit();
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function openNewDemoAccount()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getGroupList 		=$this->GroupModel->getDemoGroup();
			if($request['type'] == 'web') {
				self::renderView('open_new_demo_account', $getGroupList,'','Open New Account');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function createLiveDemoAccount(){

		$request	=self::isAuth();

		if($request['auth']==true) {

			/*---Validate Trading account info  Field Options-------------*/
			$this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
			$this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
			$this->form_validation->set_rules('pass_main', 'Password Main','trim|required');
			$this->form_validation->set_rules('pass_investor', 'Password Investor','trim|required');
			$this->form_validation->set_rules('leverage', 'Leverage','trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'message'    		=>"You have to select at least one group card",
					'group_id'			=>strip_tags(form_error('group_id')),
					'group_name'		=>strip_tags(form_error('group_name')),
					'pass_main'			=>strip_tags(form_error('pass_main')),
					'pass_investor'		=>strip_tags(form_error('pass_investor')),
					'leverage'			=>strip_tags(form_error('leverage')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_open_account']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/open-demo-account');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{

				try {
					/*--------Create MT5 Trade Account------*/

					if ($request['type'] == 'api') {
						$getHeader		=$this->input->request_headers();
						$uniqueKey		=$getHeader['Authorization'];
						$userInfo 		= $this->UserModel->getUser($uniqueKey);
					}else{
						$userInfo = $this->UserModel->getUser($this->session->userdata('unique_id'));
					}

					/*---Get Agent ID---*/
					$agentId='';
					if ($userInfo->parent_id){
						$p_id=$userInfo->parent_id;
						$checkParentIb	=$this->db->query("SELECT ib_accounts.mt5_login_id FROM `users` u
															INNER JOIN ib_accounts
															ON ib_accounts.unique_id=u.unique_id
															where u.unique_id='$p_id' and u.ib_status=1")->row();
						if ($checkParentIb){
							$agentId=$checkParentIb->mt5_login_id;
						}
					}

					/*---Get Agent ID---*/
					$permitted_chars 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$investorPassword	=substr(str_shuffle($permitted_chars), 0, 10);

					$lastTradingId='';
					$getTradingAccountResponse	=$this->mt5_instance->createDemoTradingAccount($_REQUEST,$userInfo,$investorPassword,$agentId,$lastTradingId);

					if ($getTradingAccountResponse!=false) {

						$groupName   =$_REQUEST['group_name'];
						$leverage    =$_REQUEST['leverage'];

						/*--------Create Trade Account Informations------*/
						unset($_REQUEST['plan']);
						unset($_REQUEST['group_name']);

						$mt5TradingObject			=json_decode($getTradingAccountResponse);

						$data['mt5_login_id'] 		=$mt5TradingObject->answer->Login;
						$data['balance'] 			=$mt5TradingObject->answer->Balance;
						$data['created_by'] 		= $userInfo->user_id;
						$data['group_id'] 			=$_REQUEST['group_id'];
						$data['account_type_status'] =isset($_REQUEST['accountType'])?$_REQUEST['accountType']:'';
						$data['mt5_response'] 		= $getTradingAccountResponse;
						$data['client'] 			= $request['type'];
						$data['leverage'] 			= $_REQUEST['leverage'];
						$data['user_id'] 			= $userInfo->user_id;
						$data['pass_main'] 			= openssl_decrypt($userInfo->raw_pwd,"AES-128-ECB",'password');
						$data['pass_investor'] 		= openssl_decrypt($userInfo->raw_pwd,"AES-128-ECB",'password');

						if ($data['account_type_status']==1){
							$getLiveRate 	= $this->PaymentModel->getLiveRate();
							$data['live_rate'] 						= $getLiveRate['live_rate'];
						}

						$getGroupID = $this->TradingAccount->insertDemoTradingAccount($data);

						if ($getGroupID) {

							//Email Send
							$mailHtml 	=$this->EmailConfigModel->createTradingAccount($userInfo->first_name.' '.$userInfo->last_name ,$data['mt5_login_id'],$data['pass_main'],$groupName,$leverage,$investorPassword);
							self::sendEmail($userInfo->email, 'Congratulations! Your Demo Trading Account has been created', $mailHtml);

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Create Demo Trading Account - '.$mt5TradingObject->answer->Login.'',$request['userId']);


							if ($request['type'] == 'api') {
								self::response(200, 'Successfully Create Demo Trading Account');
							} else if ($request['type'] == 'web') {
								$_SESSION['success_trading_account'] = 'Successfully Create Demo Trading Account';
								redirect('user/my-mt5-demo-account-list');
							}
						}

					}else{

						/*--------Maintain Mt5 Creating Error Response------*/
						$responseData	=array(
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_open_account']	=json_encode($responseData,true);
							$_SESSION['request_data']	=json_encode($_REQUEST,true);
							redirect(base_url() . 'user/open-demo-account');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}catch (Exception $error){
					print_r($error->getMessage());
					exit();
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function myMt5DemoAccountList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingDemoAccountList($request['userId']);
			if($request['type'] == 'web') {
				self::renderView('my_mt5_demo_account', $getTradingAccount,'','Mt5 Account List');
			}else{
				self::response(200,$getTradingAccount);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function maintain all the mt5 account list
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function myMt5AccountList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
			if($request['type'] == 'web') {
				self::renderView('my_mt5_account', $getTradingAccount,'','Mt5 Account List');
			}else{
				self::response(200,$getTradingAccount);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function getAccountDetails(){
		if (isset($_REQUEST['accountId'])){
			$getBalance = $this->mt5_instance->getAvailableBalance($_REQUEST['accountId']);
			if ($getBalance!=false){
				$getBalanceObj=$getBalance->answer[0];
				if ($getBalanceObj){
					print_r(json_encode($getBalanceObj));
					exit();
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
	}

	/**
	 *	 This Function maintain the interface of leverage view. Its applied only for web view
	 *   Param : Request
	 *   Return :  View
	 *   Version : 1.0.1
	 */
	public function changeLeverage(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
			if($request['type'] == 'web') {
				self::renderView('change_leverage', $getTradingAccount,'','Change Leverage');
			}else{
				$leverage=array(50,100,200,300,400,500);
				$itemList=array('liveAccount'=>$getTradingAccount,'leverage'=>$leverage);
				self::response(200,$itemList);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function maintain the updating leverage data. Call MT5 API also
	 *   Param : Request
	 *   Return :  View
	 *   Version : 1.0.1
	 */
	public function updateLeverage(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$this->form_validation->set_rules('mt5_login_id', 'MT5 account ID', 'trim|required');
			$this->form_validation->set_rules('leverage', 'Leverage', 'trim|required');
			if ($this->form_validation->run() == FALSE)
			{
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'leverage'				=>strip_tags(form_error('leverage')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_change_leverage']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/change-leverage');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountByLoginId($_REQUEST['mt5_login_id']);
				if ($getTradingAccount){
					$getTradingUpdateResponse	=$this->mt5_instance->updateTradingAccount($getTradingAccount,$_REQUEST['leverage']);
					if ($getTradingUpdateResponse!=false) {

						$_REQUEST['mt5_response']			=$getTradingUpdateResponse;
						$updateStatus 	=$this->TradingAccount->updateTradingAccountInfo($_REQUEST);

						if ($updateStatus) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Leverage For -'.$_REQUEST['mt5_login_id'].'',$request['userId']);

							if ($request['type'] == 'api') {
								$getTradingAccount 	=$this->TradingAccount->getTradingAccountByLoginId($_REQUEST['mt5_login_id']);
								self::response(200, $getTradingAccount);
							} else if ($request['type'] == 'web') {
								$_SESSION['success_change_leverage'] = 'Successfully Change Leverage';
								redirect(base_url() . 'user/change-leverage');
							}
						}
					}else{
						/*--------Maintain Mt5 Creating Error Response------*/
						$responseData	=array(
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_change_leverage']	=json_encode($responseData,true);
							$_SESSION['request_data']	=json_encode($_REQUEST,true);
							redirect(base_url() . 'user/change-leverage');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
						}
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function accountList()
	{
		$title['title']					='Account List';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/trading/account_list');
		$this->load->view('includes/footer');
	}

	public function changeMt5Password(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingAccountList($request['userId']);
			if($request['type'] == 'web') {
				self::renderView('change_mt5_pass', $getTradingAccount,'','Change Password');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	function password_check($password) {
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);

		if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
			$this->form_validation->set_message('password_check', 'The {field} must be at least 8 characters long and contain at least one upper case letter, one lower case letter, and one number.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function updateMt5Password(){
		$request	=self::isAuth();
		if($request['auth']==true) {

			$this->form_validation->set_rules('mt5_login_id', 'MT5 account ID', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_password_check');

			if ($this->form_validation->run() == FALSE)
			{
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'password'				=>strip_tags(form_error('password')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_change_mt5_password']	=json_encode($responseData,true);
					$_SESSION['request_data']				=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/change-mt5-pass');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				$getTradingAccount 	=$this->TradingAccount->getTradingAccountByLoginId($_REQUEST['mt5_login_id']);

				if ($getTradingAccount){
					$updateMasterPasswordResponse	=$this->mt5_instance->updateMt5MasterPassword($_REQUEST);

					if ($updateMasterPasswordResponse!=false) {


						$_REQUEST['pass_main']			=openssl_encrypt($_REQUEST['password'],"AES-128-ECB",'password');
						unset($_REQUEST['password']);

						$updateStatus 	=$this->TradingAccount->updateTradingAccountInfo($_REQUEST);

						if ($updateStatus) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Update Mt5 Password For - '.$_REQUEST['mt5_login_id'].'',$request['userId']);

							if ($request['type'] == 'api') {
								$response=array(
									'status'=>200,
									'message'=>'Successfully Update Master Password',
									'data'=>array(
										'accountId'=>$_REQUEST['mt5_login_id'],
										'password'=>$_REQUEST['pass_main'],
									),
								);
								print_r(json_encode($response));
								exit();
							} else if ($request['type'] == 'web') {
								$_SESSION['success_change_mt5_password'] = 'Successfully Update Master Password';
								redirect(base_url() . 'user/change-mt5-pass');
							}
						}
					}else{
						/*--------Maintain Mt5 Creating Error Response------*/
						$responseData	=array(
							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
						);

						if($request['type'] == 'web'){
							$_SESSION['error_change_mt5_password']	=json_encode($responseData,true);
							$_SESSION['request_data']				=json_encode($_REQUEST,true);
							redirect(base_url() . 'user/change-mt5-pass');

						}else if($request['type'] == 'api'){
							self::response(400,$responseData);
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
	public function isAuth(){
		$getHeader	=$this->input->request_headers();
		$eventFrom=array();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
				$uniqueKey		=$getHeader['Authorization'];
				$getUserInfo 	= $this->UserModel->getUser($uniqueKey);

				if ($getUserInfo){
					$eventFrom=array(
						'type'=>'api',
						'auth'=>true,
						'userId'    =>$getUserInfo->user_id,
						'unique_id' =>$getUserInfo->unique_id,
						'role' 		=>$getUserInfo->role,
						'email'     =>$getUserInfo->email,
						'fullName'  =>$getUserInfo->first_name.' '.$getUserInfo->last_name
					);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			if ($this->session->userdata('username') != '' && ($this->session->userdata('role') ==1)){
				$eventFrom=array('type'=>'web','auth'=>true,'userId'=>$this->session->userdata('user_id'),'role'=>$this->session->userdata('role'));
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
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/trading/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
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


