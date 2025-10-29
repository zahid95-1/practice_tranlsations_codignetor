<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends MY_Controller {

	private $googleClient=NULL;
	public $facebookHelper;
	public $facebookObj;
	public $facebookRedirectLink;

	function __construct()
	{
		require_once APPPATH."libraries/vendor/autoload.php";
		require_once APPPATH."libraries/facebook/vendor/autoload.php";
		$this->googleClient=new \Google_Client();
		$this->googleClient->setClientId ("700040225580-7ia0esmkktlv63slrittivboqnep2gbj.apps.googleusercontent.com");
		$this->googleClient->setClientSecret ("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");
		$this->googleClient->setRedirectUri (ConfigData['mail_site_link']."/authorize-gmail");
		$this->googleClient->addScope ('email');
		$this->googleClient->addScope ('profile');
		parent::__construct();
		$this->load->model('PermissionModel');
		$this->load->model('RegisterModel');
		$this->load->model('EmailConfigModel');
		$this->load->library('form_validation');

		$this->facebookObj=new \Facebook\Facebook(array(
			'app_id'=>'613058604072535',
			'app_secret'=>'98cc300c0feb7376e314f6b7d9fc5186',
			'default_graph_version'=>'v2.3',
		));

		$this->facebookHelper=$this->facebookObj->getRedirectLoginHelper();
		$this->facebookRedirectLink=ConfigData['mail_site_link'].'/authorize-facebook';

	}

	public function authorizeGmail(){
		$token=$this->googleClient->fetchAccessTokenWithAuthCode ($_REQUEST['code']);
		if (!isset($token['error'])){
			$this->googleClient->setAccessToken ($token['access_token']);
			$googleService=new \Google_Service_Oauth2($this->googleClient);
			$userData=$googleService->userinfo->get();
			if ($userData){
				$email			=$userData->email;
				$firstName		=$userData->givenName;
				$lastName		=$userData->familyName;

				    /*--------If email exist then auto login------*/
					if ($email){
						$getUser = $this->db->query("SELECT * FROM `users` WHERE email ='$email'")->row();
						if ($getUser){
							$session_data = array(
								'user_id' => $getUser->user_id,
								'username' => ($getUser->username)?$getUser->username:$getUser->first_name.' '.$getUser->last_name,
								'role' => $getUser->role,
								'status' =>$getUser->status,
								'unique_id' =>$getUser->unique_id,
								'enc_pass' => serialize(md5($getUser->password)),
								'ib_status' =>$getUser->ib_status,
								'email' =>$getUser->email,
							);
							$this->session->set_userdata($session_data);

							$where = '(email='."'$email'".' or mobile ='."'$email'".')';
							$this->db->where($where);
							$this->db->select('user_id,unique_id,email,role,ib_status,status');
							$query_role = $this->db->get('users');
							$result_role = $query_role->row_array(); // get the row first
							unset($_SESSION['referal_key']);
							redirect(base_url() . 'user/dashboard');
						}else{
							$_SESSION['auth_email']=$email;
							$_SESSION['auth_first_name']=$firstName;
							$_SESSION['auth_last_name']=$lastName;
							redirect(base_url() . 'register?reffid='.$_SESSION['referal_key']);
						}
					}

			}
		}else{
			redirect(base_url() . '/login');
		}
	}

	public function authorizeFacebook(){
		try {
			$accessToken = $this->facebookHelper->getAccessToken();
			$response = $this->facebookObj->get('/me?fields=name,email', $accessToken);
			$graphUser = $response->getGraphUser();

			$name = $graphUser->getField('name');
			$email = $graphUser->getField('email');
			$id = $graphUser->getField('id');

			if ($email){
				$getUser = $this->db->query("SELECT * FROM `users` WHERE email ='$email'")->row();
				if ($getUser){
					$session_data = array(
						'user_id' => $getUser->user_id,
						'username' => ($getUser->username)?$getUser->username:$getUser->first_name.' '.$getUser->last_name,
						'role' => $getUser->role,
						'status' =>$getUser->status,
						'unique_id' =>$getUser->unique_id,
						'enc_pass' => serialize(md5($getUser->password)),
						'ib_status' =>$getUser->ib_status,
						'email' =>$getUser->email,
					);
					$this->session->set_userdata($session_data);

					$where = '(email='."'$email'".' or mobile ='."'$email'".')';
					$this->db->where($where);
					$this->db->select('user_id,unique_id,email,role,ib_status,status');
					$query_role = $this->db->get('users');
					$result_role = $query_role->row_array(); // get the row first
					unset($_SESSION['referal_key']);
					redirect(base_url() . 'user/dashboard');
				}else{
					$_SESSION['auth_email']=$email;
					$_SESSION['auth_first_name']=$name;
					$_SESSION['auth_last_name']='';
					redirect(base_url() . 'register?reffid='.$_SESSION['referal_key']);
				}
			}
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// Handle Facebook API response errors
			echo 'Graph returned an error: ' . $e->getMessage();
			exit();
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// Handle SDK errors
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit();
		}
	}

	public function index()
	{
		if (ConfigData['prefix']=='TEC' || ConfigData['prefix']=='CFX'){
			session_destroy();
			$this->load->view('basic/team_edge_login');
		}else{
			$permission								=array('email');
			$button['googleBtn']					=$this->googleClient->createAuthUrl ();
			$button['facebookBtn']					=$this->facebookHelper->getLoginUrl($this->facebookRedirectLink,$permission);
			$_SESSION['referal_key']				=ConfigData['admin_prefix'];
			$this->load->view('basic/login',$button);
		}

	}

	public function logout()
	{
		session_destroy();
		redirect(base_url() . 'login');
	}

	/**
	 *	 This Function Validate Login Functionality
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function validate_captcha() {

		$secret =ConfigData['recaptcha_secret_key'];
		$response = $this->input->post('g-recaptcha-response');
		$remoteip = $_SERVER['REMOTE_ADDR'];

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $secret,
			'response' => $response,
			'remoteip' => $remoteip
		);

		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);

		if ($response->success === true) {
			return TRUE;
		} else {
			$this->form_validation->set_message('validate_captcha', $this->lang->line('captcha_required'));
			return FALSE;
		}
	}

	/**
	 *	 This Function Validate Login Functionality
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function loginValidateNewDesign(){

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($request=="web") {
			$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validate_captcha');
		}

		if($this->form_validation->run())
		{
			//true
			$username = $this->input->post('username');
			$decode_password = md5($this->input->post('password'));

			//model function
			$this->load->model('LoginModel');
			if($this->LoginModel->can_login($username, $decode_password))
			{
				$where = '(email='."'$username'".' or mobile ='."'$username'".') and is_deleted = 0';
				$this->db->where($where);
				$query_role = $this->db->get('users');
				$result_role = $query_role->row_array(); // get the row first

				$role 		= $result_role['role'];
				$status 	= $result_role['status'];
				$auth_key 	= $result_role['secret_key'];
				$unique_id 	= $result_role['unique_id'];
				$user_id 	= $result_role['user_id'];
				$email 	= $result_role['email'];

				$session_data = array(
					'user_id' => $user_id,
					'username' => $username,
					'role' => $role,
					'status' => $status,
					'unique_id' => $unique_id,
					'enc_pass' => serialize($this->input->post('password')),
					'ib_status' =>$result_role['ib_status'],
					'email' => $email
				);
				$this->session->set_userdata($session_data);

				if($this->session->userdata('username') != '' && ($this->session->userdata('role') == 1))
				{
					$dataItem=array(
						'unique_id'=>$result_role['unique_id'],
						'full_name'=>$result_role['first_name'].' '.$result_role['last_name'],
						'user_id'=>$result_role['user_id'],
						'email'=>$result_role['email'],
						'role'=>$result_role['role'],
						'ib_status'=>$result_role['ib_status'],
						'status'=>$result_role['status'],
					);

					$responseData = array(
						'status' 	=> 200,
						'message' 	=> "success",
						'data' 		=>$dataItem,
						'redirect' 		=>base_url() . 'user/dashboard',
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();

				}else{

					$accessModel		=$this->PermissionModel->getAccesModiulesName($result_role['user_id']);
					$subModules			=$this->PermissionModel->getSubmodules('',$result_role['user_id']);

					if ($accessModel){
						$session_data = array(
							'user_id' => $user_id,
							'username' => $username,
							'role' => $role,
							'status' => $status,
							'unique_id' => $unique_id,
							'enc_pass' => serialize($this->input->post('password')),
							'ib_status' =>$result_role['ib_status'],
							'email' => $email,
							'accessModel' =>json_encode($accessModel),
							'accessSubModel' =>json_encode($subModules),
						);

						$this->session->set_userdata($session_data);

						$redirectRoute		=$this->PermissionModel->getSubmodules($accessModel[0]['sub_modules_id']);

						$responseData = array(
							'status' 	=> 200,
							'role' 		=> $role,
							'uid' => $unique_id,
							'data' => 'success',
							'redirect' =>base_url() . $redirectRoute->route,
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();

					}else{
						redirect(base_url() . 'error/404');
					}
				}

			}
			else
			{
				$responseData = array(
					'status' 		=> 400,
					'message'		=>'Invalid Username and Password/Contact Admin, As your Account is deleted',
					'data' 			=>array()
				);
				$res = json_encode($responseData,true);
				print_r($res);
				exit();
			}
		}else{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'username'						=>strip_tags(form_error('username')),
				'password'						=>strip_tags(form_error('password')),
				'grecaptcharesponse'			=>strip_tags(form_error('g-recaptcha-response')),
			);

			print_r(json_encode($responseData,true));
			exit();
		}
	}

	public function loginValidate(){

		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', $this->lang->line('username'), 'required', [
			'required' => $this->lang->line('form_validation_required')
		]);

		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required', [
			'required' => $this->lang->line('form_validation_required')
		]);

		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($request=="web") {
			$this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('g-recaptcha-response'), 'required|callback_validate_captcha', [
				'required' => $this->lang->line('form_validation_required')
			]);

		}

		if($this->form_validation->run())
		{
			//true
			$username = $this->input->post('username');
			$decode_password = md5($this->input->post('password'));

			//model function
			$this->load->model('LoginModel');
			if($this->LoginModel->can_login($username, $decode_password))
			{
				$where = '(email='."'$username'".' or mobile ='."'$username'".') and is_deleted = 0';
				$this->db->where($where);
				$query_role = $this->db->get('users');
				$result_role = $query_role->row_array(); // get the row first

				$role 		= $result_role['role'];
				$status 	= $result_role['status'];
				$auth_key 	= $result_role['secret_key'];
				$unique_id 	= $result_role['unique_id'];
				$user_id 	= $result_role['user_id'];
				$email 	= $result_role['email'];

				$session_data = array(
					'user_id' => $user_id,
					'username' => $username,
					'role' => $role,
					'status' => $status,
					'unique_id' => $unique_id,
					'enc_pass' => serialize($this->input->post('password')),
					'ib_status' =>$result_role['ib_status'],
					'email' => $email
				);
				$this->session->set_userdata($session_data);

				if($this->session->userdata('username') != '' && ($this->session->userdata('role') == 1))
				{
					if($request == 'api'){

						$dataItem=array(
							'unique_id'=>$result_role['unique_id'],
							'full_name'=>$result_role['first_name'].' '.$result_role['last_name'],
							'user_id'=>$result_role['user_id'],
							'email'=>$result_role['email'],
							'role'=>$result_role['role'],
							'ib_status'=>$result_role['ib_status'],
							'status'=>$result_role['status'],
						);

						$responseData = array(
						'status' 	=> 200,
						'message' 	=> "success",
						'data' 		=>$dataItem
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request == 'web'){
						redirect(base_url() . 'user/dashboard');
					}
					
				}else{

					$accessModel		=$this->PermissionModel->getAccesModiulesName($result_role['user_id']);
					$subModules			=$this->PermissionModel->getSubmodules('',$result_role['user_id']);

					if ($accessModel){
						$session_data = array(
							'user_id' => $user_id,
							'username' => $username,
							'role' => $role,
							'status' => $status,
							'unique_id' => $unique_id,
							'enc_pass' => serialize($this->input->post('password')),
							'ib_status' =>$result_role['ib_status'],
							'email' => $email,
							'accessModel' =>json_encode($accessModel),
							'accessSubModel' =>json_encode($subModules),
						);

						$this->session->set_userdata($session_data);

						if($request == 'api'){
							$responseData = array(
								'status' 	=> 200,
								'role' 		=> $role,
								'uid' => $unique_id,
								'data' => 'success'
							);

							$res = json_encode($responseData,true);
							print_r($res);
							exit();
						}else if($request == 'web'){
							$redirectRoute		=$this->PermissionModel->getSubmodules($accessModel[0]['sub_modules_id']);
							redirect(base_url() . $redirectRoute->route);
						}
					}else{
						redirect(base_url() . 'error/404');
					}
				}
			
			}
			else
			{
				if($request == 'api'){

						$responseData = array(
						'status' 		=> 400,
						'message'		=>'Invalid Username and Password/Contact Admin, As your Account is deleted',
						'data' 			=>array()
						);
						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request == 'web'){
						$this->session->set_flashdata('error', 'Invalid Username and Password/Contact Admin, As your Account is deleted');
						redirect(base_url() . 'login');
					}
				
			}
		}else{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'username'						=>strip_tags(form_error('username')),
				'password'						=>strip_tags(form_error('password')),
				'grecaptcharesponse'			=>strip_tags(form_error('g-recaptcha-response')),
			);

			if($request == 'web'){

			$_SESSION['error_login']	=json_encode($responseData,true);
			$_SESSION['request_data']	=json_encode($_REQUEST,true);

			redirect(base_url() . 'login');
			}else if($request == 'api'){
				print_r(json_encode($responseData,true));
				exit();
			}
			
		}
	}

	/**
	 *	 This Function Display the forgot password view
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function forgotPassword(){
		$this->load->view('basic/resetpassword');
	}

	/**
	 *	 This Function Validate the forgot password given mail
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function enterEmailV2(){

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Email', 'required');

		$username = $this->input->post('username');
		$this->db->where('email', $username);
		$query_hashedpassword = $this->db->get('users');

		$verifiedStatus=0;
		if ($query_hashedpassword->num_rows()==0 && $username){
			$this->form_validation->set_rules('verified_status', 'Please Enter Correct Email.', 'required');
			$verifiedStatus=1;
		}

		if($this->form_validation->run())
		{
			$username = $this->input->post('username');
			$this->db->where('email', $username);
			$query_hashedpassword = $this->db->get('users');
			$result_hashedpassword = $query_hashedpassword->row_array();

			if($query_hashedpassword->num_rows() > 0){
				$this->load->model('EmailConfigModel');
				$uniqueid = $result_hashedpassword['unique_id'];
				$mailHtml 	=$this->EmailConfigModel->forgetPasswordV2($uniqueid);
				self::sendEmail($result_hashedpassword['email'],'Reset Your '.ConfigData['site_name'].' Password',$mailHtml);

				$response=array(
					'status'=>200,
					'message'=>"Email has been sent to your registered email address. Please follow the instructions in your email to reset the password.",
				);
				print_r(json_encode($response));
				exit();
			}else{
				$this->session->set_flashdata('fmsg', 'Please Enter Correct Email.'); //set success msg
				redirect("forgot-password");
			}

		}else{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    		=> 400,
				'username'			=>($verifiedStatus)?'Incorrect email address.':strip_tags(form_error('username')),
			);
			print_r(json_encode($responseData,true));
			exit();
		}
	}

	public function enterEmail(){

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Email', 'required');

		$username = $this->input->post('username');
		$this->db->where('email', $username);
		$query_hashedpassword = $this->db->get('users');

		$verifiedStatus=0;
		if ($query_hashedpassword->num_rows()==0 && $username){
			$this->form_validation->set_rules('verified_status', 'Please Enter Correct Email.', 'required');
			$verifiedStatus=1;
		}

		if($this->form_validation->run())
		{
			$username = $this->input->post('username');
			$this->db->where('email', $username);
			$query_hashedpassword = $this->db->get('users');
			$result_hashedpassword = $query_hashedpassword->row_array();

			if($query_hashedpassword->num_rows() > 0){
				$this->load->model('EmailConfigModel');
				$uniqueid = $result_hashedpassword['unique_id'];
				$mailHtml 	=$this->EmailConfigModel->forgetPassword($uniqueid);
				self::sendEmail($result_hashedpassword['email'],'Reset Your '.ConfigData['site_name'].' Password',$mailHtml);

				if ($request=='api'){
					$response=array(
						'status'=>200,
						'message'=>"Email has been sent to your registered email address. Please follow the instructions in your email to reset the password.",
					);
					print_r(json_encode($response));
					exit();
				}else{
					$this->session->set_flashdata('smsg', 'Email has been sent to your registered email address. Please follow the instructions in your email to reset the password.'); //set success msg
					redirect("forgot-password");
				}

			}else{
				$this->session->set_flashdata('fmsg', 'Please Enter Correct Email.'); //set success msg
				redirect("forgot-password");
			}


		}else{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    		=> 400,
				'username'			=>($verifiedStatus)?'Incorrect email address.':strip_tags(form_error('username')),
			);
			if($request == 'web'){
				$this->session->set_flashdata('fmsg', 'Please Enter Correct Email.'); //set success msg
				redirect(base_url() . 'forgot-password');
			}else if($request == 'api'){
				print_r(json_encode($responseData,true));
				exit();
			}
		}
	}

	/**
	 *	 This Function Return view for changing password
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */

	public function changePasswordV2(){
		$this->load->view('basic/change_password_v2');
	}

	public function changePassword(){
		$this->load->view('basic/change_password');
	}

	/**
	 *	 This Function maintain for changing password
	 *   Param : Request
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function enterPasswordV2(){

		$getHeader	=$this->input->request_headers();

		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run()==false)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'password'			=>strip_tags(form_error('password')),
			);

			$responseData	=array(
				'status'    		=> 400,
				'username'			=>strip_tags(form_error('username')),
			);
			print_r(json_encode($responseData,true));
			exit();
		}else{

			$password = $this->security->xss_clean($this->input->post('password'));
			$uniqueid  = $_REQUEST['token'];

			//Validate ID
			$this->db->where('unique_id', $uniqueid);
			$getUser = $this->db->get('users');

			if($getUser->num_rows() > 0){
				//model function
				$this->load->model('LoginModel');
				$getStatus=$this->LoginModel->reset($password,$uniqueid);

				if ($getStatus==true){
					$responseData = array(
						'status' 	=> 200,
						'role' 		=> "1",
						'data' => 'success'
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}
			}else{
				$this->session->set_flashdata('fmsg', 'Invalid Token.Not found this user'); //set success msg
				redirect(base_url() . 'reset-password?token='.$_REQUEST['token'].'');
			}
		}
	}

	public function enterPassword(){

		$getHeader	=$this->input->request_headers();

		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run()==false)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'password'			=>strip_tags(form_error('password')),
			);

			if($request == 'web'){

				$_SESSION['error_password']	=json_encode($responseData,true);
				$_SESSION['request_data']	=json_encode($_REQUEST,true);
				redirect(base_url() . 'reset-password?token='.$_REQUEST['token'].'');

			}else if($request== 'api'){
				$responseData	=array(
					'status'    		=> 400,
					'username'			=>strip_tags(form_error('username')),
				);
				print_r(json_encode($responseData,true));
				exit();
			}
		}else{

			$password = $this->security->xss_clean($this->input->post('password'));
			$uniqueid  = $_REQUEST['token'];

			//Validate ID
			$this->db->where('unique_id', $uniqueid);
			$getUser = $this->db->get('users');

			if($getUser->num_rows() > 0){
				//model function
				$this->load->model('LoginModel');
				$getStatus=$this->LoginModel->reset($password,$uniqueid);

				if ($getStatus==true){
					if($request == 'api'){
						$responseData = array(
							'status' 	=> 200,
							'role' 		=> "1",
							'data' => 'success'
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request == 'web'){
						redirect(base_url() . 'login');
					}
				}
			}else{
				$this->session->set_flashdata('fmsg', 'Invalid Token.Not found this user'); //set success msg
				redirect(base_url() . 'reset-password?token='.$_REQUEST['token'].'');
			}
		}
	}

	public function sendEmail($toEmail,$subject,$htmlContent)
	{
		$companyName =ConfigData['m_company_name'];

		//Load email library
		$this->load->library('email');

		//$this->load->model('EmailConfigModel');
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

	public function CheckAuth(){
		$this->form_validation->set_rules('email', 'Email','required|min_length[6]|max_length[50]|valid_email');

		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'email'							=>strip_tags(form_error('email')),
			);

			print_r(json_encode($responseData,true));
			exit();

		}else{
			$email 				= $this->security->xss_clean($this->input->post('email'));
			$getUser 			= $this->db->query("SELECT * FROM `users` WHERE email ='$email'")->row();
			if ($getUser) {
				$returnData = array(
					'user_id' => $getUser->user_id,
					'username' => ( $getUser->username ) ? $getUser->username : $getUser->first_name . ' ' . $getUser->last_name,
					'role' => $getUser->role,
					'status' => $getUser->status,
					'unique_id' => $getUser->unique_id,
					'enc_pass' => serialize ( md5 ( $getUser->password ) ),
					'ib_status' => $getUser->ib_status,
					'email' => $getUser->email,
					'create_new'=>false,
				);

				print_r (json_encode ($returnData,true));
				exit();
			}else{
				$responseData	=array(
					'status'    					=>200,
					'message'						=>'Not exist in database',
					'create_new'					=>true,
				);

				print_r(json_encode($responseData,true));
				exit();
			}

		}
	}
}
