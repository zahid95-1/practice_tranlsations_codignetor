<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegistrationController extends MY_Controller {

	public $facebookHelper;
	public $facebookObj;
	public $facebookRedirectLink;

	function __construct()
	{
		parent::__construct();
		require_once APPPATH."libraries/vendor/autoload.php";
		require_once APPPATH."libraries/facebook/vendor/autoload.php";

		$this->googleClient=new \Google_Client();
		$this->googleClient->setClientId ("700040225580-7ia0esmkktlv63slrittivboqnep2gbj.apps.googleusercontent.com");
		$this->googleClient->setClientSecret ("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");
		$this->googleClient->setRedirectUri (ConfigData['mail_site_link']."/authorize-gmail");
		$this->googleClient->addScope ('email');
		$this->googleClient->addScope ('profile');

		$this->facebookObj=new \Facebook\Facebook(array(
			'app_id'=>'613058604072535',
			'app_secret'=>'98cc300c0feb7376e314f6b7d9fc5186',
			'default_graph_version'=>'v2.3',
		));

		$this->facebookHelper=$this->facebookObj->getRedirectLoginHelper();
		$this->facebookRedirectLink=ConfigData['mail_site_link'].'/authorize-facebook';

		$this->load->library('form_validation');
		$this->load->model('RegisterModel');
		$this->load->model('EmailConfigModel');
	}

	/**
	 * Index Page for this controller.
	 * This functions use for display the registrations view
	 * @version 1.0.0
	 * @return HTML
	 */

	/*=====================storing level Info===============*/
	function Remove(){

		$getlevelparentId = $this->db->query("SELECT * FROM `users`")->result();

		foreach($getlevelparentId as $getlevelparentIdvalue){
			$uid = $getlevelparentIdvalue->unique_id;
			$level = 1;
			while($getlevelparentIdvalue->parent_id <> NULL){

				$dataL = array(
					'user_id' => $uid,
					'upline_id' => $getlevelparentIdvalue->parent_id,
					'level_no' =>$level,
					'ref_percentage' => 0,
					'created_by' => $uid,
					'created_datetime' => date('d/m/Y H:i:s')
				);

				$abc = $this->db->insert('user_level_info',$dataL);
				$abc = $this->db->insert('user_level_info_log',$dataL);
				echo $this->db->last_query(). ";";
				$getlevelparentIdvalue = $this->db->query("SELECT * FROM `users` WHERE unique_id = '".$getlevelparentIdvalue->parent_id."'")->row();
				$level++;
			}

		}
		/*=====================end storing level Info===============*/
	}

	public function GetVersion()
	{
		$getHeader  =$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}

		$getversion = $this->db->query("SELECT * FROM `version` ;")->row();

		if($request == 'api'){
			$responseData = array(
				'status'    => 200,
				'data'      => $getversion,
			);
			print_r(json_encode($responseData,true));
			exit();

		}
	}

	public function secondStepView(){
		$this->load->view('basic/step_2_register');
	}

	public function index()
	{
		$permission								=array('email');
		$button['googleBtn']					=$this->googleClient->createAuthUrl ();
		$button['facebookBtn']					=$this->facebookHelper->getLoginUrl($this->facebookRedirectLink,$permission);
		$_SESSION['referal_key']				=$_REQUEST['reffid'];
		$_SESSION['link_key']				=$_REQUEST['link'];
		$this->load->view('basic/register',$button);
	}

	/**
	 * This functions use for saving registrations data and given response json format for API
	 *
	 * @version 1.0.0
	 * @return JSON
	 */

	// Add the password validation callback function
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
			$this->form_validation->set_message('validate_captcha', 'Please check the Captcha box.');
			return FALSE;
		}
	}

	// Define the callback function
	public function check_unique_id($parent_id) {
		// Load the database library (if not already loaded)
		$this->load->database();

		// Query the users table to check if the parent_id exists in the unique_id column
		$this->db->where('unique_id', $parent_id);
		$query = $this->db->get('users');

		// If there is a row returned, parent_id exists in the unique_id column
		if ($query->num_rows() > 0) {
			return true; // Validation passes
		} else {
			$this->form_validation->set_message('check_unique_id', 'The {field} does not exist in the users table.');
			return false; // Validation fails
		}
	}

	public function step_registrations(){

		unset($_SESSION['referal_key']);
		unset($_SESSION['link_key']);

		//$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required');
		$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required|callback_check_unique_id');

		$this->form_validation->set_rules('first_name', $this->lang->line('first_name'), 'trim|required',
			array('required' => $this->lang->line('form_validation_required'))
		);

		$this->form_validation->set_rules(
			'email',
			$this->lang->line('email'),
			'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
			array(
				'required'     => $this->lang->line('form_validation_required'),
				'min_length'   => $this->lang->line('form_validation_min_length'),
				'max_length'   => $this->lang->line('form_validation_max_length'),
				'valid_email'  => $this->lang->line('form_validation_valid_email'),
				'is_unique'    => $this->lang->line('form_validation_is_unique'
				))
		);

		$this->form_validation->set_message('is_unique', 'This email id already exists in system. Please use another mail id');

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'first_name'					=>strip_tags(form_error('first_name')),
				'email'							=>strip_tags(form_error('email')),
				'phone'							=>strip_tags(form_error('phone')),
				'parent_id'						=>strip_tags(form_error('parent_id')),
				'ref_link_name'					=>strip_tags(form_error('link_id')),
			);

			if($request == 'web'){
				$_SESSION['error_new_registration']	=json_encode($responseData,true);
				$_SESSION['request_data']			=json_encode($_REQUEST,true);
				redirect(base_url() . 'register?reffid='.$_REQUEST['parent_id'].'&link='.$_REQUEST['link_id']);
			}else if($request == 'api'){
				print_r(json_encode($responseData,true));
				exit();
			}
		}else{
			if ($request=='api') {
				$responseData = array(
					'status' => 200,
					'message' =>'Successfully verified your mail and referal key!',
					'data' =>$_REQUEST,
				);

				$res = json_encode($responseData, true);
				print_r($res);
				exit();
			}else {
				/*--------Clear Data------*/
				$_SESSION['request_registration_data'] = json_encode($_REQUEST, true);
				redirect(base_url() . 'register-second-step?reffid=' . $_REQUEST['parent_id'].'&link='.$_REQUEST['link_id']);
			}
		}
	}

	public function save_register_v2(){

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if (!isset($_SESSION['request_registration_data']) && $request=='web'){
			redirect(base_url() . 'register?reffid='.ConfigData['admin_prefix']);
		}

		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('phone', 'Mobile No', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');

		if ($request=='web') {
			// Set your password validation rules
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_password_check');

			$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validate_captcha');
		}else{   //if Calling From API
			$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required|callback_check_unique_id');
			$this->form_validation->set_rules('first_name', 'First Name','trim|required');
			$this->form_validation->set_rules('email', 'Email','required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]');
		}

		if ($this->form_validation->run() == FALSE)
		{
			if($request == 'web'){
				$responseData	=array(
					'status'    					=> 400,
					'phone'							=>strip_tags(form_error('phone')),
					'password'						=>strip_tags(form_error('password')),
					'country'						=>strip_tags(form_error('country')),
					'grecaptcharesponse'			=>strip_tags(form_error('g-recaptcha-response')),
				);

				$_SESSION['error_new_registration']	  =json_encode($responseData,true);
				$_SESSION['request_data']			  =json_encode($_REQUEST,true);
				redirect(base_url() . 'register-second-step?reffid='.$_REQUEST['reffid'].'&link='.$_REQUEST['link']);
			}else if($request == 'api'){
				$responseData	=array(
					'status'    					=> 400,
					'first_name'					=>strip_tags(form_error('first_name')),
					'email'							=>strip_tags(form_error('email')),
					'parent_id'						=>strip_tags(form_error('parent_id')),
					'ref_link_name'						=>strip_tags(form_error('link_id')),
					'phone'							=>strip_tags(form_error('phone')),
					'password'						=>strip_tags(form_error('password')),
					'country'						=>strip_tags(form_error('country')),
				);

				print_r(json_encode($responseData,true));
				exit();
			}

		}else{

			/*--------Clear Data------*/
			$birth_date 		= $this->security->xss_clean($this->input->post('birth_date'));
			$mobile 			= $this->security->xss_clean($this->input->post('phone'));
			$countryID 			= $this->security->xss_clean($this->input->post('country'));
			$password 			= md5($this->security->xss_clean($this->input->post('password')));
			$rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');
			$city 				= $this->security->xss_clean($this->input->post('city'));
			$state 				= $this->security->xss_clean($this->input->post('state'));


			$now 		= date('Y-m-d H:i:s');

			if (isset($_SESSION['request_registration_data']) && $request=='web'){
				$obj				=json_decode($_SESSION['request_registration_data']);
				$email 		       =$obj->email;
				$first_name 		=$obj->first_name;
				$last_name 		    =isset($obj->last_name)?$obj->last_name:'';

			       /*--------Store Users------*/
					$insertData = array(
						'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
						'email'=>$email,
						'mobile'=>$mobile,
						'first_name'=>$first_name,
						'parent_id'=>$obj->parent_id,
						'ref_link_name'=>$obj->link_id,
						'password'=>$password,
						'country_id'=>$countryID,
						'raw_pwd'=>$rawpwd,
						'last_name'=>$last_name,
						'birth_date'=>date('Y-m-d',strtotime($birth_date)),
						'created_by'=>$email,
						'city'=>($city)?$city:'',
						'state'=>($state)?$state:'',
						'created_datetime'=>$now);

					$getRegisterData = $this->RegisterModel->insertUser($insertData);

					if ($getRegisterData) {
						//Email Send
						$mailHtml = $this->EmailConfigModel->registrations($email, $this->security->xss_clean($this->input->post('password')), $first_name . ' ' . $last_name);
						self::sendEmail($email, 'Congratulations! Your account has been successfully registered', $mailHtml);

						$this->session->set_flashdata('msg', 'Congratulation! You have successfully registered.'); //set success msg if
						redirect('login');
					}

			}else{  //Register Once Call From API
				/*--------Clear Data------*/
				$first_name 		= $this->security->xss_clean($this->input->post('first_name'));
				$last_name 			= $this->security->xss_clean($this->input->post('last_name'));
				$email 				= $this->security->xss_clean($this->input->post('email'));

				if($this->security->xss_clean($this->input->post('parent_id')) == ''){
					$parent_id  = NULL;
				}else{
					$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
				}

				$now 		= date('Y-m-d H:i:s');

				/*--------Store Users------*/
				$insertData = array(
					'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
					'email'=>$email,
					'mobile'=>$mobile,
					'first_name'=>$first_name,
					'parent_id'=>$parent_id,
					'password'=>$password,
					'country_id'=>$countryID,
					'raw_pwd'=>$rawpwd,
					'last_name'=>$last_name,
					'birth_date'=>date('Y-m-d',strtotime($birth_date)),
					'created_by'=>$email,
					'city'=>($city)?$city:'',
					'state'=>($state)?$state:'',
					'created_datetime'=>$now);

				$getRegisterData = $this->RegisterModel->insertUser($insertData);
				if ($getRegisterData){

					//Email Send
					$mailHtml 	=$this->EmailConfigModel->registrations($email,$this->security->xss_clean($this->input->post('password')),$first_name.' '.$last_name);
					self::sendEmail($email,'Congratulations! Your account has been successfully registered',$mailHtml);

					if($request == 'api'){

						$where = '(email='."'$email'".' or mobile ='."'$email'".')';
						$this->db->where($where);
						$this->db->select('user_id,unique_id,email,role,ib_status,status');
						$query_role = $this->db->get('users');
						$result_role = $query_role->row_array(); // get the row first

						$responseData = array(
							'status' 	=> 200,
							'message' 	=> "Congratulation! You have successfully registered.",
							'data' 		=> $result_role
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}
				}
			}

		}
	}

	public function save_register(){

		$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required');
		$this->form_validation->set_rules('first_name', 'First Name','trim|required');
		$this->form_validation->set_rules('email', 'Email','required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('phone', 'Mobile No', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($request=='web') {
			// Set your password validation rules
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_password_check');

			$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validate_captcha');
		}


		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'first_name'					=>strip_tags(form_error('first_name')),
				'email'							=>strip_tags(form_error('email')),
				'phone'							=>strip_tags(form_error('phone')),
				'password'						=>strip_tags(form_error('password')),
				'parent_id'						=>strip_tags(form_error('parent_id')),
				'country'						=>strip_tags(form_error('country')),
				'grecaptcharesponse'			=>strip_tags(form_error('g-recaptcha-response')),
			);

			if($request == 'web'){
				$this->load->view('basic/register');
			}else if($request == 'api'){
				print_r(json_encode($responseData,true));
				exit();
			}

		}else{
			/*--------Clear Data------*/
			$birth_date 		= $this->security->xss_clean($this->input->post('birth_date'));
			$first_name 		= $this->security->xss_clean($this->input->post('first_name'));
			$last_name 			= $this->security->xss_clean($this->input->post('last_name'));
			$email 				= $this->security->xss_clean($this->input->post('email'));
			$mobile 			= $this->security->xss_clean($this->input->post('phone'));
			$countryID 			= $this->security->xss_clean($this->input->post('country'));
			$password 			= md5($this->security->xss_clean($this->input->post('password')));
			$rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');
			$city 				= $this->security->xss_clean($this->input->post('city'));
			$state 				= $this->security->xss_clean($this->input->post('state'));

			if($this->security->xss_clean($this->input->post('parent_id')) == ''){
				$parent_id  = NULL;
			}else{
				$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
				$link_id = $this->security->xss_clean($this->input->post('link_id'));
			}

			$now 		= date('Y-m-d H:i:s');

			/*--------Store Users------*/
			$insertData = array(
				'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
				'email'=>$email,
				'mobile'=>$mobile,
				'first_name'=>$first_name,
				'parent_id'=>$parent_id,
				'ref_link_name'=>$link_id,
				'password'=>$password,
				'country_id'=>$countryID,
				'raw_pwd'=>$rawpwd,
				'last_name'=>$last_name,
				'birth_date'=>date('Y-m-d',strtotime($birth_date)),
				'created_by'=>$email,
				'city'=>($city)?$city:'',
				'state'=>($state)?$state:'',
				'created_datetime'=>$now);

			$getRegisterData = $this->RegisterModel->insertUser($insertData);

			if ($getRegisterData){

				//Email Send
				$mailHtml 	=$this->EmailConfigModel->registrations($email,$this->security->xss_clean($this->input->post('password')),$first_name.' '.$last_name);
				self::sendEmail($email,'Congratulations! Your account has been successfully registered',$mailHtml);

				if($request == 'api'){

					$where = '(email='."'$email'".' or mobile ='."'$email'".')';
					$this->db->where($where);
					$this->db->select('user_id,unique_id,email,role,ib_status,status');
					$query_role = $this->db->get('users');
					$result_role = $query_role->row_array(); // get the row first

					$responseData = array(
						'status' 	=> 200,
						'message' 	=> "Congratulation! You have successfully registered.",
						'data' 		=> $result_role
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Congratulation! You have successfully registered.'); //set success msg if
					redirect('login');
				}


			}

		}
	}

	public function save_register_new_ui(){

		$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required');
		$this->form_validation->set_rules('first_name', 'First Name','trim|required');
		$this->form_validation->set_rules('email', 'Email','required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('phone', 'Mobile No', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');

		$this->form_validation->set_message('is_unique', 'This email id already exists in system. Please use another mail id');


		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($request=='web') {
			// Set your password validation rules
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_password_check');

			$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_validate_captcha');
		}


		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'first_name'					=>strip_tags(form_error('first_name')),
				'email'							=>strip_tags(form_error('email')),
				'phone'							=>strip_tags(form_error('phone')),
				'password'						=>strip_tags(form_error('password')),
				'parent_id'						=>strip_tags(form_error('parent_id')),
				'country'						=>strip_tags(form_error('country')),
				'grecaptcharesponse'			=>strip_tags(form_error('g-recaptcha-response')),
			);

			print_r(json_encode($responseData,true));
			exit();

		}else{
			/*--------Clear Data------*/
			$birth_date 		= $this->security->xss_clean($this->input->post('birth_date'));
			$first_name 		= $this->security->xss_clean($this->input->post('first_name'));
			$last_name 			= $this->security->xss_clean($this->input->post('last_name'));
			$email 				= $this->security->xss_clean($this->input->post('email'));
			$mobile 			= $this->security->xss_clean($this->input->post('phone'));
			$countryID 			= $this->security->xss_clean($this->input->post('country'));
			$password 			= md5($this->security->xss_clean($this->input->post('password')));
			$rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');
			$city 				= $this->security->xss_clean($this->input->post('city'));
			$state 				= $this->security->xss_clean($this->input->post('state'));

			if($this->security->xss_clean($this->input->post('parent_id')) == ''){
				$parent_id  = NULL;
			}else{
				$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
				$link_id = $this->security->xss_clean($this->input->post('link_id'));
			}

			$now 		= date('Y-m-d H:i:s');

			/*--------Store Users------*/
			$insertData = array(
				'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
				'email'=>$email,
				'mobile'=>$mobile,
				'first_name'=>$first_name,
				'parent_id'=>$parent_id,
				'ref_link_name'=>$link_id,
				'password'=>$password,
				'country_id'=>$countryID,
				'raw_pwd'=>$rawpwd,
				'last_name'=>$last_name,
				'birth_date'=>date('Y-m-d',strtotime($birth_date)),
				'created_by'=>$email,
				'city'=>($city)?$city:'',
				'state'=>($state)?$state:'',
				'created_datetime'=>$now);

			$getRegisterData = $this->RegisterModel->insertUser($insertData);

			if ($getRegisterData){

				//Email Send
				$mailHtml 	=$this->EmailConfigModel->registrations($email,$this->security->xss_clean($this->input->post('password')),$first_name.' '.$last_name);
				self::sendEmail($email,'Congratulations! Your account has been successfully registered',$mailHtml);

				$where = '(email='."'$email'".' or mobile ='."'$email'".')';
				$this->db->where($where);
				$this->db->select('user_id,unique_id,email,role,ib_status,status');
				$query_role = $this->db->get('users');
				$result_role = $query_role->row_array(); // get the row first

				$responseData = array(
					'status' 	=> 200,
					'message' 	=> "Congratulation! You have successfully registered.",
					'data' 		=> $result_role
				);

				$res = json_encode($responseData,true);
				print_r($res);
				exit();

			}

		}
	}

	public function save_otp_less(){

		$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required');
		$this->form_validation->set_rules('first_name', 'First Name','trim|required');
		$this->form_validation->set_rules('parent_id', 'Refferral ID', 'trim|required');

		$getHeader	=$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'status'    					=> 400,
				'first_name'					=>strip_tags(form_error('first_name')),
				'parent_id'						=>strip_tags(form_error('parent_id')),
			);

			if($request == 'web'){
				$this->load->view('basic/register');
			}else if($request == 'api'){
				print_r(json_encode($responseData,true));
				exit();
			}

		}else{
			/*--------Clear Data------*/
			$first_name 		= $this->security->xss_clean($this->input->post('first_name'));
			$last_name 			= $this->security->xss_clean($this->input->post('last_name'));
			$email 				= $this->security->xss_clean($this->input->post('email'));
			$mobile 			= $this->security->xss_clean($this->input->post('phone'));
			$countryID 			= $this->security->xss_clean($this->input->post('country'));
			$password 			= md5($first_name.'@2023');
			$rawpwd 			= openssl_encrypt($this->security->xss_clean($first_name.'@2023'),"AES-128-ECB",'password');

			if ($email){
				$getUser = $this->db->query("SELECT * FROM `users` WHERE email ='$email'")->row();
				if ($getUser){
					$session_data = array(
						'user_id' => $getUser->user_id,
						'username' => ($getUser->username)?$getUser->username:$getUser->first_name,
						'role' => $getUser->role,
						'status' =>$getUser->status,
						'unique_id' =>$getUser->unique_id,
						'enc_pass' => serialize($password),
						'ib_status' =>$getUser->ib_status,
						'email' =>$getUser->email,
					);
					$this->session->set_userdata($session_data);

					if ($request == 'web') {
						$responseData = array(
							'status' => 200,
							'message' => "Exist email user",
							'redirect' => base_url() . 'user/dashboard'
						);

						$res = json_encode($responseData, true);
						print_r($res);
						exit();
					}else{
						$where = '(email='."'$email'".' or mobile ='."'$email'".')';
						$this->db->where($where);
						$this->db->select('user_id,unique_id,email,role,ib_status,status');
						$query_role = $this->db->get('users');
						$result_role = $query_role->row_array(); // get the row first

						$responseData = array(
							'status' 	=> 200,
							'message' 	=> "Already have register email.",
							'data' 		=> $result_role
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}
				}
			}

			if ($mobile){
				$getUser = $this->db->query("SELECT * FROM `users` WHERE mobile ='$mobile'")->row();
				if ($getUser){
					if ($request == 'web') {
						$session_data = array(
							'user_id' => $getUser->user_id,
							'username' => ($getUser->username)?$getUser->username:$getUser->first_name,
							'role' => $getUser->role,
							'status' =>$getUser->status,
							'unique_id' =>$getUser->unique_id,
							'enc_pass' => serialize($password),
							'ib_status' =>$getUser->ib_status,
							'email' =>$getUser->email,
						);
						$this->session->set_userdata($session_data);

						$responseData = array(
							'status' => 200,
							'message' => "Exist email user",
							'redirect' => base_url() . 'user/dashboard'
						);

						$res = json_encode($responseData, true);
						print_r($res);
						exit();
					}else{
						$mobile=$getUser->mobile;
						$where = '(email='."'$email'".' or mobile ='."'$mobile'".')';
						$this->db->where($where);
						$this->db->select('user_id,unique_id,email,role,ib_status,status');
						$query_role = $this->db->get('users');
						$result_role = $query_role->row_array(); // get the row first

						$responseData = array(
							'status' 	=> 200,
							'message' 	=> "Already have register mobile.",
							'data' 		=> $result_role
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}
				}
			}

			//New Registrations
			if($this->security->xss_clean($this->input->post('parent_id')) == ''){
				$parent_id  = NULL;
			}else{
				$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
				$link_id = $this->security->xss_clean($this->input->post('link_id'));
			}

			$now 		= date('Y-m-d H:i:s');
			/*--------Store Users------*/
			$insertData = array(
				'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
				'email'=>$email,
				'mobile'=>$mobile,
				'first_name'=>$first_name,
				'parent_id'=>$parent_id,
				'link_id'=>$link_id,
				'password'=>$password,
				'country_id'=>$countryID,
				'raw_pwd'=>$rawpwd,
				'last_name'=>$last_name,
				'created_by'=>$email,
				'created_datetime'=>$now);

			$getRegisterData = $this->RegisterModel->insertUser($insertData);

			if ($getRegisterData){

				if($request == 'api'){

					$where = '(email='."'$email'".' or mobile ='."'$email'".')';
					$this->db->where($where);
					$this->db->select('user_id,unique_id,email,role,ib_status,status');
					$query_role = $this->db->get('users');
					$result_role = $query_role->row_array(); // get the row first

					$responseData = array(
						'status' 	=> 200,
						'message' 	=> "Congratulation! You have successfully registered.",
						'data' 		=> $result_role
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){

					$where = '(email='."'$email'".' or mobile ='."'$mobile'".')';
					$this->db->where($where);
					$this->db->select('username,user_id,unique_id,email,role,ib_status,status,first_name,password');
					$query_role = $this->db->get('users');
					$getUser = $query_role->row(); // get the row first

					$session_data = array(
						'user_id' => $getUser->user_id,
						'username' => ($getUser->username)?$getUser->username:$getUser->first_name,
						'role' => $getUser->role,
						'status' =>$getUser->status,
						'unique_id' =>$getUser->unique_id,
						'enc_pass' => serialize($password),
						'ib_status' =>$getUser->ib_status,
						'email' =>$getUser->email,
					);
					$this->session->set_userdata($session_data);

					$responseData = array(
						'status' 	=> 200,
						'message' 	=> "Congratulation! You have successfully registered.",
						'redirect' => base_url() . 'user/dashboard'
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}
			}

		}
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
