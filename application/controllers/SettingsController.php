<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsController extends MY_Controller {

	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
		$this->load->model('PermissionModel');
		$this->load->model('UserModel');

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
	public function getSettings()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$currencyCode=self::get_currency_symbol();
				self::renderView('index',$currencyCode,'','Settings');
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

	public function getDepositRate(){
		$getSettingsModel =$this->db->query("SELECT * FROM setting")->row();
		if ($getSettingsModel){
			$dep_with_rate	    	=$getSettingsModel->dep_with_rate;
			$rate_currency	    	=$getSettingsModel->rate_currency;
			$dataItem=array(
				'from'=>'1 USD',
				'dep_with_rate'=>$dep_with_rate,
				'rate_currency'=>$rate_currency,
			);
			$responseData = array(
				'status' 	=> 200,
				'message' 	=> "Deposit Rate",
				'data' 		=>$dataItem,
				'enable' 	=>ConfigData['enable_deposit_withdraw_rate'],
			);

			print_r(json_encode($responseData));
			exit();
		}else{
			$dataItem=array(
				'from'=>'1 USD',
				'dep_with_rate'=>'',
				'rate_currency'=>'',
				'enable' 	=>'',
			);
			$responseData = array(
				'status' 	=> 200,
				'message' 	=> "Country List",
				'data' 		=>$dataItem,
				'enable' 	=>ConfigData['enable_deposit_withdraw_rate'],
			);

			print_r(json_encode($responseData));
			exit();
		}
	}

	/**
	 *	 This Function Maintaining The Settings Email Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveEmailConfigurations(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){
				$data = array(
					'email_method' => $_REQUEST['email_method'],
					'email_host' => $_REQUEST['email_host'],
					'email_port' => $_REQUEST['email_port'],
					'email_enc' => $_REQUEST['enc'],
					'email_user_name' => $_REQUEST['email_user_name'],
					'email_password' => $_REQUEST['email_password'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					echo 1;
					exit();
				}
			}else{
				$insertData = array(
					'email_method' => $_REQUEST['email_method'],
					'email_host' => $_REQUEST['email_host'],
					'email_port' => $_REQUEST['email_port'],
					'email_enc' => $_REQUEST['enc'],
					'email_user_name' => $_REQUEST['email_user_name'],
					'email_password' => $_REQUEST['email_password'],
					'created_by' => $_SESSION['unique_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update Email SMTP',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update Email Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining The Settings SMS Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveSms(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){
				$data = array(
					'sms_method' => $_REQUEST['sms_method'],
					'sms_user_name' => $_REQUEST['sms_user_name'],
					'sms_token' => $_REQUEST['sms_token'],
					'sms_sender_id' => $_REQUEST['sms_sender_id'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					echo 1;
					exit();
				}
			}else{
				$insertData = array(
					'sms_method' => $_REQUEST['sms_method'],
					'sms_user_name' => $_REQUEST['sms_user_name'],
					'sms_token' => $_REQUEST['sms_token'],
					'sms_sender_id' => $_REQUEST['sms_sender_id'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining The Settings LOGO Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveLogo(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			if ($_FILES['logo_url']['name']) {
				/*-------Uploaded Site Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['logo_url']['name'];
				$_FILES['file']['type'] = $_FILES['logo_url']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['logo_url']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['logo_url']['error'];
				$_FILES['file']['size'] = $_FILES['logo_url']['size'];

				//upload to a Folder
				$config['upload_path'] = 'assets/settings/logo';
				$config['allowed_types'] = 'jpg|png|pdf|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['logo_url']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedFileLogoName = $imageData['file_name'];
				}
			}else{
				$uploadedFileLogoName='';
				if (isset($_REQUEST['previouslogoImageurl'])){
					$uploadedFileLogoName = $_REQUEST['previouslogoImageurl'];
				}
			}

			if ($_FILES['favicon_image']['name']) {
				/*-------Favicon  Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['favicon_image']['name'];
				$_FILES['file']['type'] = $_FILES['favicon_image']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['favicon_image']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['favicon_image']['error'];
				$_FILES['file']['size'] = $_FILES['favicon_image']['size'];

				//upload to a Folder
				$config['upload_path'] = 'assets/settings/logo';
				$config['allowed_types'] = 'jpg|png|pdf|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['image']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedFaviconImageName = $imageData['file_name'];
				}
			}else{
				$uploadedFaviconImageName='';
				if (isset($_REQUEST['previousFaviconImageurl'])){
					$uploadedFaviconImageName = $_REQUEST['previousFaviconImageurl'];
				}
			}

			$settingsModel=self::getModelData();
			if ($settingsModel){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Update Setting Logo',$request['userId']);

				$data = array(
					'logo_image' => $uploadedFileLogoName,
					'favicon_image' => $uploadedFaviconImageName,
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}else{
				$insertData = array(
					'logo_image' => $uploadedFileLogoName,
					'favicon_image' => $uploadedFaviconImageName,
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining The Settings Currency Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveCurrency(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Update Settings Currency',$request['userId']);

				$data = array(
					'from_currency' => $_REQUEST['from_currency'],
					'from_currency_symbol' => $_REQUEST['from_currency_symbol'],
					'to_currency' => $_REQUEST['to_currency'],
					'to_currency_symbol' => $_REQUEST['to_currency_symbol'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}else{
				$insertData = array(
					'from_currency' => $_REQUEST['from_currency'],
					'from_currency_symbol' => $_REQUEST['from_currency_symbol'],
					'to_currency' => $_REQUEST['to_currency'],
					'to_currency_symbol' => $_REQUEST['to_currency_symbol'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function savePaypalSettings(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Update Settings Currency',$request['userId']);

				$data = array(
					'paypal_client_id' => $_REQUEST['paypal_client_id'],
					'paypal_client_secret' => $_REQUEST['paypal_client_secret'],
					'paypal_status' => $_REQUEST['paypal_status'],
					'stripe_client_id' => $_REQUEST['stripe_client_id'],
					'stripe_client_secret' => $_REQUEST['stripe_client_secret'],
					'stripe_status' => $_REQUEST['stripe_status'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}else{
				$insertData = array(
					'paypal_client_id' => $_REQUEST['paypal_client_id'],
					'paypal_client_secret' => $_REQUEST['paypal_client_secret'],
					'paypal_status' => $_REQUEST['paypal_status'],
					'stripe_client_id' => $_REQUEST['stripe_client_id'],
					'stripe_client_secret' => $_REQUEST['stripe_client_secret'],
					'stripe_status' => $_REQUEST['stripe_status'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}


	/**
	 *	 This Function Maintaining The Settings Currency Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveWithdrawDepositRate(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){
				$data = array(
					'dep_with_rate' => $_REQUEST['dep_with_rate'],
					'rate_currency' => $_REQUEST['rate_currency'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);

				if ($update){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update withdraw Rate',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}else{
				$insertData = array(
					'dep_with_rate' => $_REQUEST['dep_with_rate'],
					'rate_currency' => $_REQUEST['rate_currency'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Set withdraw Rate',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining The Settings Min Withdrawal
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveMinWithdrawAmt(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Update Setting Minimum Withdraw | $'.$_REQUEST['min_withdraw_amt'].'',$request['userId']);

				$data = array(
					'min_withdrawal' => $_REQUEST['min_withdraw_amt'],
					'kyc_validations' => $_REQUEST['kyc_validations'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);

				if ($update){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						redirect(base_url() . 'admin/settings');
					}
				}
			}else{

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Set Setting Minimum Withdraw | $'.$_REQUEST['min_withdraw_amt'].'',$request['userId']);

				$insertData = array(
					'min_withdrawal' => $_REQUEST['min_withdraw_amt'],
					'kyc_validations' => $_REQUEST['kyc_validations'],
					'created_by' => $_SESSION['user_id']
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						redirect(base_url() . 'admin/settings');
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining The Settings Copy Right Configurations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveCopyRight(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel=self::getModelData();
			if ($settingsModel){
				$data = array(
					'copy_right_display_status' => $_REQUEST['copy_right_display_status'],
					'copy_right_text' => $_REQUEST['copy_right_text'],
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update Settings Copy Right',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {

						echo 1;
						exit();
					}
				}
			}else{
				$insertData = array(
					'copy_right_display_status' => $_REQUEST['copy_right_display_status'],
					'copy_right_text' => $_REQUEST['copy_right_text'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining For Save Meta Informations
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveMetaData(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$settingsModel = self::getModelData();
			if ($settingsModel) {
				$data = array(
					'meta_title' => $_REQUEST['meta_title'],
					'meta_descriptions' => $_REQUEST['meta_descriptions'],
				);

				$update = $this->db->where('id', $settingsModel->id)->update('setting', $data);
				if ($update) {

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update Settings Meta Data',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			} else {
				$insertData = array(
					'meta_title' => $_REQUEST['meta_title'],
					'meta_descriptions' => $_REQUEST['meta_descriptions'],
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem) {
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Maintaining For Save Background Image
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function saveBgImage(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			if ($_FILES['login_bg_image']['name']) {
				/*-------Uploaded Site Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['login_bg_image']['name'];
				$_FILES['file']['type'] = $_FILES['login_bg_image']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['login_bg_image']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['login_bg_image']['error'];
				$_FILES['file']['size'] = $_FILES['login_bg_image']['size'];

				//upload to a Folder
				$config['upload_path'] = 'assets/settings/bg_image';
				$config['allowed_types'] = 'jpg|png|pdf|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['image']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$loginBgImageName = $imageData['file_name'];
				}
			}else{
				$loginBgImageName='';
				if (isset($_REQUEST['previousLoginBgImage'])){
					$loginBgImageName = $_REQUEST['previousLoginBgImage'];
				}
			}

			if ($_FILES['sign_up_bg_image']['name']) {
				/*-------Favicon  Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['sign_up_bg_image']['name'];
				$_FILES['file']['type'] = $_FILES['sign_up_bg_image']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['sign_up_bg_image']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['sign_up_bg_image']['error'];
				$_FILES['file']['size'] = $_FILES['sign_up_bg_image']['size'];

				//upload to a Folder
				$config['upload_path'] = 'assets/settings/bg_image';
				$config['allowed_types'] = 'jpg|png|pdf|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['image']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$signUpBgImageName = $imageData['file_name'];
				}
			}else{
				$signUpBgImageName='';
				if (isset($_REQUEST['previousSignupImageUrl'])){
					$signUpBgImageName = $_REQUEST['previousSignupImageUrl'];
				}
			}

			$settingsModel=self::getModelData();
			if ($settingsModel){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Update Setting Login Background Image',$request['userId']);

				$data = array(
					'login_bg_image' => $loginBgImageName,
					'sign_up_bg_image' => $signUpBgImageName,
				);

				$update=$this->db->where('id', $settingsModel->id)->update('setting',$data);
				if ($update){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}else{

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Set Setting Login Background Image',$request['userId']);

				$insertData = array(
					'login_bg_image' => $loginBgImageName,
					'sign_up_bg_image' => $signUpBgImageName,
					'created_by' => $_SESSION['user_id'],
				);

				$insertDataItem = $this->db->insert('setting', $insertData);
				if ($insertDataItem){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Update  Settings');
					} else if ($request['type'] == 'web') {
						echo 1;
						exit();
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}


	/**
	 *	 This Function Gives Required Model Data
	 *   Return : Array
	 *   Version : 1.0.1
	 */
	public function getModelData(){
		$getSettingsModel =$this->db->query("SELECT * FROM setting")->row();
		if($getSettingsModel){
			return $getSettingsModel;
		}else{
			return '';
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
			if ($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)){
				$getUserInfo 	= $this->UserModel->getUser($this->session->userdata('unique_id'),0);
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

//	public function isAuth($validate=true){
//		$getHeader	=$this->input->request_headers();
//		$eventFrom=array();
//		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
//			if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
//				$uniqueKey		=$getHeader['Authorization'];
//				$getUserInfo 	= $this->UserModel->getUser($uniqueKey,0); //0 for admin access
//				if ($getUserInfo){
//					$eventFrom=array('type'=>'api','auth'=>true);
//				}
//			}else{
//				self::response(400,'Unauthorize user');
//			}
//		}else{
//			if ($validate==false){
//				if ($this->session->userdata('username') != '') {
//					$eventFrom = array('type' => 'web', 'auth' => true);
//				}
//			}else {
//				$checkPermission = $this->PermissionModel->checkExistPermission($this->session->userdata('user_id'), $this->actionName);
//				if ($checkPermission) {
//					if ($this->session->userdata('username') != '') {
//						$eventFrom = array('type' => 'web', 'auth' => true);
//					}
//				} else {
//					redirect(base_url() . 'error/404');
//				}
//			}
//		}
//		return $eventFrom;
//	}

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
		$this->load->view('admin/settings/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}

	function get_currency_symbol($currency = '')
	{
		$symbols = array(
			'AED' => '&#1583;.&#1573;', // ?
			'AFN' => '&#65;&#102;',
			'ALL' => '&#76;&#101;&#107;',
			'AMD' => '&#1423;',
			'ANG' => '&#402;',
			'AOA' => '&#75;&#122;', // ?
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => '&#402;',
			'AZN' => '&#1084;&#1072;&#1085;',
			'BAM' => '&#75;&#77;',
			'BBD' => '&#36;',
			'BDT' => '&#2547;', // ?
			'BGN' => '&#1083;&#1074;',
			'BHD' => '.&#1583;.&#1576;', // ?
			'BIF' => '&#70;&#66;&#117;', // ?
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => '&#36;&#98;',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => '&#78;&#117;&#46;', // ?
			'BWP' => '&#80;',
			'BYR' => '&#112;&#46;',
			'BYN' => '&#66;&#114;',
			'BZD' => '&#66;&#90;&#36;',
			'CAD' => '&#36;',
			'CDF' => '&#70;&#67;',
			'CHF' => '&#67;&#72;&#70;',
			'CLF' => '', // ?
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUC' => '&#8396;',
			'CUP' => '&#8396;',
			'CVE' => '&#36;', // ?
			'CZK' => '&#75;&#269;',
			'DJF' => '&#70;&#100;&#106;', // ?
			'DKK' => '&#107;&#114;',
			'DOP' => '&#82;&#68;&#36;',
			'DZD' => '&#1583;&#1580;', // ?
			'EGP' => '&#163;',
			'ERN' => '&#78;&#102;&#107;', // ?
			'ETB' => '&#66;&#114;',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;', // ?
			'GGP' => '&#163;',
			'GHS' => '&#162;',
			'GIP' => '&#163;',
			'GMD' => '&#68;', // ?
			'GNF' => '&#70;&#71;', // ?
			'GTQ' => '&#81;',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => '&#76;',
			'HRK' => '&#107;&#110;',
			'HTG' => '&#71;', // ?
			'PKE' => '&#36;',
			'HUF' => '&#70;&#116;',
			'IDR' => '&#82;&#112;',
			'ILS' => '&#8362;',
			'IMP' => '&#163;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1583;', // ?
			'IRR' => '&#65020;',
			'IRT' => '&#65020;',
			'ISK' => '&#107;&#114;',
			'JEP' => '&#163;',
			'JMD' => '&#74;&#36;',
			'JOD' => '&#74;&#68;', // ?
			'JPY' => '&#165;',
			'KES' => '&#75;&#83;&#104;', // ?
			'KGS' => '&#1083;&#1074;',
			'KHR' => '&#6107;',
			'KMF' => '&#67;&#70;', // ?
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1583;.&#1603;', // ?
			'KYD' => '&#36;',
			'KZT' => '&#1083;&#1074;',
			'LAK' => '&#8365;',
			'LBP' => '&#163;',
			'LKR' => '&#8360;',
			'LRD' => '&#36;',
			'LSL' => '&#76;', // ?
			'LTL' => '&#76;&#116;',
			'LVL' => '&#76;&#115;',
			'LYD' => '&#1604;.&#1583;', // ?
			'MAD' => '&#1583;.&#1605;.', //?
			'MDL' => '&#76;',
			'MGA' => '&#65;&#114;', // ?
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => '&#75;',
			'MNT' => '&#8366;',
			'MOP' => '&#77;&#79;&#80;&#36;', // ?
			'MRO' => '&#85;&#77;', // ?
			'MUR' => '&#8360;', // ?
			'MVR' => '.&#1923;', // ?
			'MWK' => '&#77;&#75;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => '&#77;&#84;',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => '&#67;&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#65020;',
			'PAB' => '&#66;&#47;&#46;',
			'PEN' => '&#83;&#47;&#46;',
			'PGK' => '&#75;', // ?
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#71;&#115;',
			'QAR' => '&#65020;',
			'RON' => '&#108;&#101;&#105;',
			'RSD' => '&#1044;&#1080;&#1085;&#46;',
			'RUB' => '&#1088;&#1091;&#1073;',
			'RWF' => '&#1585;.&#1587;',
			'SAR' => '&#65020;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#163;', // ?
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => '&#76;&#101;', // ?
			'SOS' => '&#83;',
			'SPL' => '&#163;',
			'SRD' => '&#36;',
			'STD' => '&#68;&#98;', // ?
			'SVC' => '&#36;',
			'SYP' => '&#163;',
			'SZL' => '&#76;', // ?
			'THB' => '&#3647;',
			'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
			'TMT' => '&#109;',
			'TND' => '&#1583;.&#1578;',
			'TOP' => '&#84;&#36;',
			'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
			'TTD' => '&#36;',
			'TVD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => '',
			'UAH' => '&#8372;',
			'UGX' => '&#85;&#83;&#104;',
			'USD' => '&#36;',
			'UYU' => '&#36;&#85;',
			'UZS' => '&#1083;&#1074;',
			'VEF' => '&#66;&#115;',
			'VND' => '&#8363;',
			'VUV' => '&#86;&#84;',
			'WST' => '&#87;&#83;&#36;',
			'XAF' => '&#70;&#67;&#70;&#65;',
			'XCD' => '&#36;',
			'XDR' => '',
			'XOF' => '',
			'XPF' => '&#70;',
			'ZAR' => '&#82;',
			'ZMW' => '&#90;&#75;',
		);
		if ($currency) {
			if (isset($symbols[$currency])) {
				return $symbols[$currency];
			}
		}
		return $symbols;
	}
}
