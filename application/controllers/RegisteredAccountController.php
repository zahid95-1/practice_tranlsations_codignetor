<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegisteredAccountController extends MY_Controller {

	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('RegisterModel');
		$this->load->model('GroupModel');
		$this->load->model('UserModel');
		$this->load->model('ProfileModel');
		$this->load->model('TradingAccount');
		$this->load->model('EmailConfigModel');
		$this->load->model('PermissionModel');

		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();

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
	public function registerAccount()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				self::renderView('register_account', '','','Registered Account');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	public function getRegisterData(){

		$draw = $this->input->get('draw');
		$start = $this->input->get('start');
		$length = $this->input->get('length');
		$searchValue = $this->input->get('search')['value']; // Get the search value

		// Fetch data from the model
		$data = $this->RegisterModel->getData($start, $length, $searchValue);

		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => $this->RegisterModel->getTotalCount(),
			"recordsFiltered" => $this->RegisterModel->getFilteredCount($searchValue),
			"data" => $data
		);

		// Return data as JSON
		header('Content-Type: application/json');
		echo json_encode($response);
		exit();
	}

	public function exportCSV(){

		$this->db->select('MDV_Registered_Account.*, CONCAT(MDV_Registered_Account.first_name, " ", MDV_Registered_Account.last_name) AS full_name,
    	DATE_FORMAT(MDV_Registered_Account.created_datetime, "%m/%d/%Y %H:%i:%s") AS formatted_created_datetime,
    	SUM(payments.entered_amount) as totalPayment');
		$this->db->from('MDV_Registered_Account');
		$this->db->join('payments', 'MDV_Registered_Account.user_id = payments.user_id', 'left');
		$this->db->group_by('MDV_Registered_Account.user_id');

		if (isset($_REQUEST['startDate']) && !empty($_REQUEST['startDate']) && isset($_REQUEST['endDate']) && !empty($_REQUEST['endDate'])) {
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) >=', $_REQUEST['startDate']);
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) <=', $_REQUEST['endDate']);
		}

		$this->db->order_by('MDV_Registered_Account.created_datetime', 'desc');

		$query = $this->db->get();
		$datListItem = $query->result();


		$fileName =strtolower('Register User')."-". date("d-m-y") . ".csv";
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Pragma: no-cache");
		header("Expires: 0");

		$output = fopen("php://output", "w");

		$header_row = array(
			0 => 'SR NO',
			1 => 'Registration Date',
			2 => 'Name',
			3 => 'Email',
			4 => 'Mobile',
			5 => 'Country',
			6 => 'Deposit Balance',
			7 => 'Ib Status',
			8 => 'Wallet Balance',
			9 => 'Parent Ib Name',
		);

		//write the header
		fputcsv($output, $header_row);

		// loop for insert data into CSV file
		$index = 1;
		foreach ($datListItem as $statementFet) {
			$ibStatus='MAKE IB';
			if ($statementFet->ib_status==1){
				$ibStatus='ACTIVE IB';
			}

			$userId			=$statementFet->id;
			$parent_id			=$statementFet->parent_id;

			$balanceMt5	   =$this->db->query("SELECT SUM(balance) as totalBalanceMt5  FROM `trading_accounts` WHERE `user_id` = $userId")->row();

			$getIbUser=$this->db->query("SELECT* FROM users where unique_id='".$parent_id."'")->row();
			$firstName='';
			if ($getIbUser){
				$firstName=$getIbUser->first_name;
			}

			$wp_array = array(
				"index" => $index,
				"formatted_created_datetime" => $statementFet->formatted_created_datetime,
				"full_name" => $statementFet->full_name,
				"email" => $statementFet->email,
				"mobile" => $statementFet->mobile,
				"nicename" => $statementFet->nicename,
				"totalPayment" => $statementFet->totalPayment,
				"ib_status" => $ibStatus,
				"totalBalanceMt5" => $balanceMt5->totalBalanceMt5,
				"parent_ib" => $firstName
			);
			fputcsv($output, $wp_array);
			$index++;
		}

		fclose($output);
		exit;
	}

	public function getRestRegisterData(){
		if (isset($_REQUEST['accountId'])){
			$userId			=$_REQUEST['accountId'];
			$parent_id			=$_REQUEST['parent_id'];

			$balanceMt5	   =$this->db->query("SELECT SUM(balance) as totalBalanceMt5  FROM `trading_accounts` WHERE `user_id` = $userId")->row();

			$getIbUser=$this->db->query("SELECT * FROM users where unique_id='".$parent_id."'")->row();
			$firstName='';
			if ($getIbUser){
				$firstName=$getIbUser->first_name;
			}

			$dataListArray=array(
				'totalBalanceMt5'=>$balanceMt5->totalBalanceMt5,
				'parentIbName'=>($firstName)?$firstName:'Admin',
			);


			print_r (json_encode ($dataListArray));
			exit();
		}
	}

	public function getKycData(){
		if (isset($_REQUEST['accountId'])){

			$userId			=$_REQUEST['accountId'];
			$parent_id			=$_REQUEST['parent_id'];

			$getAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$parent_id."'")->row();

			$identity_proof='';
			$residency_proof='';
			$residency_proof_back='';
			$identity_proof_status='';
			$residency_proof_status='';
			$residency_proof_status_back='';
			$profile_proof=base_url() .'assets/images/users/avatar-1.jpg';

			if (!empty($getAttachment)){
				$identity_proof				=base_url()."assets/users/kyc/".$parent_id.'/'.$getAttachment->identity_proof;
				$residency_proof			=base_url()."assets/users/kyc/".$parent_id.'/'.$getAttachment->residency_proof;
				$residency_proof_back			=base_url()."assets/users/kyc/".$parent_id.'/'.$getAttachment->resedency_proof_back;
				$identity_proof_status		=$getAttachment->identity_verified_status;
				$residency_proof_status		=$getAttachment->residency_verified_status;
				$residency_proof_status_back=$getAttachment->residency_proof_back_status;

				if ($getAttachment->profile_image){
					$profile_proof = base_url() . "assets/users/kyc/" .$parent_id. '/' . $getAttachment->profile_image;
				}
			}

			$dataListArray=array(
				'identity_proof'=>$identity_proof,
				'unique_id'=>$parent_id,
				'residency_proof'=>$residency_proof,
				'residency_proof_back'=>$residency_proof_back,
				'profile_proof'=>$profile_proof,
				'identity_proof_status'=>$identity_proof_status,
				'residency_proof_status'=>$residency_proof_status,
				'residency_proof_status_back'=>$residency_proof_status_back,
			);


			print_r (json_encode ($dataListArray));
			exit();
		}
	}
	
	/**
	 * This Method Provides user created UI
	 */
	public function createUser()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				self::renderView('add_new_user', '','','Create User');
			}else{
				self::response(200,"Not Found");
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
	 * save_new_user provides for saving user informations
	 */
	public function save_new_user(){

		$this->form_validation->set_rules('first_name', 'First Name','trim|required');
		$this->form_validation->set_rules('email', 'Email','trim|required');
		$this->form_validation->set_rules('phone', 'Mobile No', 'trim|required');

		/*=====================================log1_11/6/2020====================================*/
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error-msg">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{
			/*--------Error Response------*/
			$responseData	=array(
				'first_name'		=>strip_tags(form_error('first_name')),
				'email'				=>strip_tags(form_error('email')),
				'phone'				=>strip_tags(form_error('phone')),
				'password'			=>strip_tags(form_error('password')),
			);

			$_SESSION['error_new_user']			=json_encode($responseData,true);
			$_SESSION['request_data']			=json_encode($_REQUEST,true);
			$data['errorMsg'] 					='Unable to save user. Please try again';

			redirect(base_url() . 'admin/account/add-new-user',$data);
		}
		else
		{
			$parentId 		= $this->security->xss_clean($this->input->post('parent_id'));
			$birth_date 		= $this->security->xss_clean($this->input->post('birth_date'));
			$first_name 		= $this->security->xss_clean($this->input->post('first_name'));
			$last_name 			= $this->security->xss_clean($this->input->post('last_name'));
			$email 				= $this->security->xss_clean($this->input->post('email'));
			$mobile 			= $this->security->xss_clean($this->input->post('phone'));
			$password 			= md5($this->security->xss_clean($this->input->post('password')));
			$country 			= $this->security->xss_clean($this->input->post('country'));
			$now 				= date('Y-m-d H:i:s');
			$rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');

			$insertData = array(
				'unique_id'=>ConfigData['prefix'].rand(1000,9999).rand(10,99),
				'email'=>$email,
				'mobile'=>$mobile,
				'country_id'=>$country,
				'first_name'=>$first_name,
				'parent_id'=>$parentId,
				'password'=>$password,
				'raw_pwd'=>$rawpwd,
				'last_name'=>$last_name,
				'birth_date'=>date('Y-m-d',strtotime($birth_date)),
				'created_by'=>$email,
				'created_datetime'=>$now);

			/*$checkDuplicateMobile = $this->Register_model->checkDuplicateMobile($mobile);*/
			$checkDuplicatePan = $this->RegisterModel->checkDuplicateEmail($email);

			if( $checkDuplicatePan == 0)
			{
				$insertUser = $this->RegisterModel->insertUser($insertData);

				if($insertUser)
				{
					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Register New User | '.$email.'');

					$mailHtml 	=$this->EmailConfigModel->registrations($email,$this->security->xss_clean($this->input->post('password')),$first_name.' '.$last_name);
					self::sendEmail($email,'Congratulations! Your account has been successfully registered',$mailHtml);

					redirect(base_url() . 'admin/account/registered-account');
				}else
				{
					/*--------Error Response------*/
					$responseData	=array(
						'unable_save'		=>'Unable to save user. Please try again',
					);

					$_SESSION['error_new_user']			=json_encode($responseData,true);
					redirect(base_url() . 'admin/account/add-new-user','');
				}
			}else
			{
				/*--------Error Response------*/
				$responseData	=array(
					'unable_save'		=>'Alerady exist email',
				);

				$_SESSION['error_new_user']			=json_encode($responseData,true);
				redirect(base_url() . 'admin/account/add-new-user','');
			}
		}
	}
	/**
	 * save_new_user provides for saving user informations
	 */
	public function add_manager()
	{

		$data = array(
			'manager_name' =>$_REQUEST['manager_name']
		);
		$this->db->set($data);
		$this->db->where('user_id', $_REQUEST['register_id']);
		$this->db->update('users');

		print_r($_REQUEST['manager_name']);
		exit();
	}

	public function Mt5DemoAccountList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingDemoAccountList();
			if($request['type'] == 'web') {
				self::renderView('mt5_demo_account_list', $getTradingAccount,'','Mt5 Account List');
			}else{
				self::response(200,$getTradingAccount);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 * save_new_user provides for saving user informations
	 */
	public function upload_kyc()
	{
	    
		$getHeader	=$this->input->request_headers();	
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
				$request = 'api';
			}else{
				$request = 'web';
			}

        if ($request=='api'){
			$this->form_validation->set_rules('specific_user_id', 'User id','trim|required');
			if ($this->form_validation->run() == FALSE)
			{
				$responseData	=array(
					'specific_user_id'		=>strip_tags(form_error('specific_user_id')),
				);
				if($request == 'api'){
					self::response(400,$responseData);
				}
			}
		}
		
		$editableAccess=0;
		if (isset($_REQUEST['edit_from_admin']) && $_REQUEST['edit_from_admin']==1) {
			$editableAccess=1; //
		}

		$fileContact=array();
		$uploadedIdentityProof=$uploadedResidencyProof=$uploadedProfileProof =$uploadedResidencyProofBack= '';
		if (isset($_FILES['identity_proof'])){
			if ($_FILES['identity_proof']['name']) {
				/*-------Uploaded Site Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['identity_proof']['name'];
				$_FILES['file']['type'] = $_FILES['identity_proof']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['identity_proof']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['identity_proof']['error'];
				$_FILES['file']['size'] = $_FILES['identity_proof']['size'];

				if (!file_exists('assets/users/kyc/'.$_REQUEST['specific_user_id'].'')) {
					mkdir('assets/users/kyc/'.$_REQUEST['specific_user_id'].'', 0777, true);
				}

				//upload to a Folder
				$config['upload_path'] = 'assets/users/kyc/'.$_REQUEST['specific_user_id'];
				$config['allowed_types'] = 'jpg|png|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['identity_proof']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {

					if($request == 'api'){
					$responseArray=array(
					"uid" 			 => $_REQUEST['specific_user_id'],
					'error'=> $this->upload->display_errors(),
					'status' 	=> 400
				);
					print_r(json_encode($responseArray));
					exit();
				}else if($request == 'web'){
					$_SESSION['identity_proof_error']  ='Failed to Upload, allowed types are jpg|png|pdf|jpeg';
					redirect(base_url() . 'user/kyc');
				}
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedIdentityProof = $imageData['file_name'];
				}
			}else{
				if (isset($_REQUEST['previouslogoImageurl'])){
					$uploadedIdentityProof = $_REQUEST['previouslogoImageurl'];
				}
			}

			if ($uploadedIdentityProof){
				$fileContact=array('identity_proof'=>$uploadedIdentityProof,'identity_verified_status'=>0);
			}

		}

		if (isset($_FILES['resedency_proof_back'])){
			if ($_FILES['resedency_proof_back']['name']) {
				/*-------Favicon  Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['resedency_proof_back']['name'];
				$_FILES['file']['type'] = $_FILES['resedency_proof_back']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['resedency_proof_back']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['resedency_proof_back']['error'];
				$_FILES['file']['size'] = $_FILES['resedency_proof_back']['size'];

				if (!file_exists('assets/users/kyc/'.$_REQUEST['specific_user_id'].'')) {
					mkdir('assets/users/kyc/'.$_REQUEST['specific_user_id'].'', 0777, true);
				}

				//upload to a Folder
				$config['upload_path'] = 'assets/users/kyc/'.$_REQUEST['specific_user_id'];
				$config['allowed_types'] = 'jpg|png|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['resedency_proof_back']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					if($request == 'api'){
						$responseArray=array(
							"uid" 			 => $_REQUEST['specific_user_id'],
							'error'=> $this->upload->display_errors(),
							'status' 	=> 400
						);
						print_r(json_encode($responseArray));
						exit();
					}else if($request == 'web'){
						$_SESSION['resedency_proof_back_error']  ='Failed to Upload, allowed types are jpg|png|pdf|jpeg';
						redirect(base_url() . 'user/kyc');
					}
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedResidencyProofBack = $imageData['file_name'];
				}
			}else{
				if (isset($_REQUEST['previousFaviconImageurl'])){
					$uploadedResidencyProofBack = $_REQUEST['previousFaviconImageurl'];
				}
			}

			if ($uploadedResidencyProofBack){
				$fileContact=array_merge($fileContact,array('resedency_proof_back'=>$uploadedResidencyProofBack,'residency_proof_back_status'=>0));
			}
		}

		if (isset($_FILES['resedency_proof'])){
			if ($_FILES['resedency_proof']['name']) {
				/*-------Favicon  Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['resedency_proof']['name'];
				$_FILES['file']['type'] = $_FILES['resedency_proof']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['resedency_proof']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['resedency_proof']['error'];
				$_FILES['file']['size'] = $_FILES['resedency_proof']['size'];

				if (!file_exists('assets/users/kyc/'.$_REQUEST['specific_user_id'].'')) {
					mkdir('assets/users/kyc/'.$_REQUEST['specific_user_id'].'', 0777, true);
				}

				//upload to a Folder
				$config['upload_path'] = 'assets/users/kyc/'.$_REQUEST['specific_user_id'];
				$config['allowed_types'] = 'jpg|png|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['resedency_proof']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {
					if($request == 'api'){
						$responseArray=array(
							"uid" 			 => $_REQUEST['specific_user_id'],
							'error'=> $this->upload->display_errors(),
							'status' 	=> 400
						);
						print_r(json_encode($responseArray));
						exit();
					}else if($request == 'web'){
						$_SESSION['resedency_proof_error']  ='Failed to Upload, allowed types are jpg|png|pdf|jpeg';
						redirect(base_url() . 'user/kyc');
					}
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedResidencyProof = $imageData['file_name'];
				}
			}else{
				if (isset($_REQUEST['previousFaviconImageurl'])){
					$uploadedResidencyProof = $_REQUEST['previousFaviconImageurl'];
				}
			}

			if ($uploadedResidencyProof){
				$fileContact=array_merge($fileContact,array('residency_proof'=>$uploadedResidencyProof,'residency_verified_status'=>0));
			}
		}

		if (isset($_FILES['profile_image'])){
			if ($_FILES['profile_image']['name']) {
				/*-------Uploaded Site Logo Image---------*/
				$_FILES['file']['name'] = time() . $_FILES['profile_image']['name'];
				$_FILES['file']['type'] = $_FILES['profile_image']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['profile_image']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['profile_image']['error'];
				$_FILES['file']['size'] = $_FILES['profile_image']['size'];

				if (!file_exists('assets/users/kyc/'.$_REQUEST['specific_user_id'].'')) {
					mkdir('assets/users/kyc/'.$_REQUEST['specific_user_id'].'', 0777, true);
				}

				//upload to a Folder
				$config['upload_path'] = 'assets/users/kyc/'.$_REQUEST['specific_user_id'];
				$config['allowed_types'] = 'jpg|png|jpeg';


				// Load and initialize upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$fileName = $_FILES['profile_image']['name'];

				// Upload file to server
				if (!$this->upload->do_upload('file')) {

					if($request == 'api'){
						$responseArray=array(
							"uid" 			 => $_REQUEST['specific_user_id'],
							'error'=> $this->upload->display_errors(),
							'status' 	=> 400
						);
						print_r(json_encode($responseArray));
						exit();
					}else if($request == 'web'){
						$_SESSION['profile_error']  ='Failed to Upload, allowed types are jpg|png|pdf|jpeg';
						redirect(base_url() . 'user/kyc');
					}
					$error = array(
						'error' => $this->upload->display_errors()
					);
					print_r($error);
					exit;
				} else {
					// Uploaded file data
					$imageData = $this->upload->data();
					$uploadedProfileProof = $imageData['file_name'];
				}
			}else{

				if (isset($_REQUEST['previouslogoImageurl'])){
					$uploadedProfileProof = $_REQUEST['previouslogoImageurl'];
				}
			}

			if ($uploadedProfileProof){
				$fileContact=array_merge($fileContact,array('profile_image'=>$uploadedProfileProof));
			}

		}

		$data =array_merge($fileContact,array(
			"user_id" 			 => $_REQUEST['specific_user_id'],
			"profile_verified_status" 	=> 1
		));

		$identity_proof=$residency_proof=$profile_proof=$residency_proof_back='';
		if ($uploadedIdentityProof) {
			$identity_proof = base_url() . "assets/users/kyc/" . $_REQUEST['specific_user_id'] . '/' . $uploadedIdentityProof;
		}
		if ($uploadedResidencyProof) {
			$residency_proof = base_url() . "assets/users/kyc/" . $_REQUEST['specific_user_id'] . '/' . $uploadedResidencyProof;
		}
		if ($uploadedProfileProof) {
			$profile_proof = base_url() . "assets/users/kyc/" . $_REQUEST['specific_user_id'] . '/' . $uploadedProfileProof;
		}
		if ($uploadedResidencyProofBack) {
			$residency_proof_back = base_url() . "assets/users/kyc/" . $_REQUEST['specific_user_id'] . '/' . $uploadedResidencyProofBack;
		}


		$this->load->model('KycStatements');
		$lastInsertedId=$this->KycStatements->saveKycAttachment($data);
		if ($lastInsertedId){

			if (isset($_REQUEST['edit_from_admin']) && $_REQUEST['edit_from_admin']==1) {
				$responseArray=array(
					"user_id" 			 => $_REQUEST['specific_user_id'],
					'identity_proof'	=>$identity_proof,
					'residency_proof'	=>$residency_proof,
					'residency_proof_back'	=>$residency_proof_back,
					'profile_proof'		=>$profile_proof,
				);
				print_r(json_encode($responseArray));
				exit();
			}else{

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Upload Kyc From',$_REQUEST['specific_user_id']);

				if ($request == 'api') {
					$responseArray = array(
						"uid" => $_REQUEST['specific_user_id'],
						'identity_proof' => $identity_proof,
						'residency_proof' => $residency_proof,
						'profile_image' => $profile_proof,
						'residency_proof_back'	=>$residency_proof_back,
						'data' => 'success',
						"profile_verified_status" => 1,
						"identity_verified_status" => 0,
						"residency_verified_status" => 0,
						'status' => 200
					);
					print_r(json_encode($responseArray));
					exit();
				} else if ($request == 'web') {
					$this->session->set_flashdata('msg', 'Successfully Uploaded'); //set success msg if
					redirect(base_url() . 'user/kyc');
				}
			}
			
		}

	}

	public function kyc_residency_attachment_verified_back(){
		$this->load->model('KycStatements');
		$status 		= $this->security->xss_clean($this->input->post('type'));
		$lastInsertedId=$this->KycStatements->updatedResidencyVerifiedStatusBack($status,$_REQUEST['userid']);
		if ($lastInsertedId){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Kyc verified',$_REQUEST['userid']);

			echo 1;
			exit();
		}
	}


	public function kyc_residency_attachment_verified(){
		$this->load->model('KycStatements');
		$status 		= $this->security->xss_clean($this->input->post('type'));
		$lastInsertedId=$this->KycStatements->updatedResidencyVerifiedStatus($status,$_REQUEST['userid']);
		if ($lastInsertedId){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Kyc verified',$_REQUEST['userid']);

			echo 1;
			exit();
		}
	}

	public function kyc_attachment_verified(){
		$this->load->model('KycStatements');
		$status 		= $this->security->xss_clean($this->input->post('type'));
		$lastInsertedId=$this->KycStatements->updateIdentityVerifiedStatus($status,$_REQUEST['userid']);
		if ($lastInsertedId){
			echo 1;
			exit();
		}
	}
	public function change_ib_status(){

	   $ibStatus=0;
		if ($_REQUEST['ib_status']==0){
			$ibStatus=1;
		}

		$data = array(
			"ib_status" 			 =>$ibStatus,
		);

		$this->load->model('KycStatements');
		$lastUpdatedStatus=$this->KycStatements->changeIbStatus($data,$_REQUEST['userid']);
		if ($lastUpdatedStatus){
			echo $ibStatus;
			exit();
		}
	}

	public function edit_user_profile(){
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']			='Edit User';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/accounts/edit_user_profile');
			$this->load->view('includes/footer');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	
	public function resendTradingAccountOpeningMail($mt5AccountId=''){
		$tradingAccount = $this->db->query("SELECT * FROM trading_accounts where mt5_login_id='".$mt5AccountId."'")->row();
		$getUserInfo = $this->db->query("SELECT * FROM users where user_id='".$tradingAccount->user_id."'")->row();
		$groupInfo 	= $this->db->query("SELECT * FROM groups where id='".$tradingAccount->group_id."'")->row();

		$mailHtml 	=$this->EmailConfigModel->createTradingAccount($getUserInfo->first_name.' '.$getUserInfo->last_name ,$mt5AccountId,$tradingAccount->pass_main,$groupInfo->group_name,$tradingAccount->leverage,$tradingAccount->pass_investor);
		self::sendEmail($getUserInfo->email,'Congratulations! Your Live Trading Account has been created',$mailHtml);

		$this->session->set_flashdata('msg', 'Successfully Resend Mail To : '.$getUserInfo->email);
		redirect(base_url() . 'admin/account/user-trading-account-list');
	}
	
	
	public function resendEmail(){
		$getUserInfo = $this->db->query("SELECT * FROM users where unique_id='".$_REQUEST['userId']."'")->row();
		$rawpwd 	 = openssl_decrypt($getUserInfo->raw_pwd,"AES-128-ECB",'password');

		$mailHtml = $this->EmailConfigModel->registrations($getUserInfo->email, $rawpwd, $getUserInfo->first_name . ' ' . $getUserInfo->last_name);
		self::sendEmail($getUserInfo->email, 'Congratulations! Your account has been successfully registered', $mailHtml);

		$this->session->set_flashdata('msg', 'Successfully Resend Mail To : '.$getUserInfo->email);
		redirect(base_url() . 'admin/account/registered-account');
	}
	

	public function update_user_info(){
		$uniqueId=$_REQUEST['unique_id'];
		unset($_REQUEST['unique_id']);
		$updatedData	=$_REQUEST;

		$this->db->set($updatedData);
		$this->db->where('unique_id', $uniqueId);
		$this->db->update('users');

		$_SESSION['updated_message']='Successfully Update User Profile';
		redirect(base_url() . 'admin/account/registered-account');
	}

	public function activate_account()
	{

			
			$deleteAccount		=$this->ProfileModel->deleteAccount($_REQUEST['userId']);
			$this->session->set_flashdata('msg', 'Successfully Changed User Status');
			redirect(base_url() . 'admin/account/registered-account');
				
			
		
	}

	public function login_user(){
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
		$getUserInfo = $this->db->query("SELECT * FROM users where unique_id='".$_REQUEST['userId']."'")->row();
		unset($_SESSION['admin_options']);
		unset($_SESSION['login_from']);
		$session_data = array(
			'user_id' => $getUserInfo->user_id,
			'username' => $getUserInfo->email,
			'role' => $getUserInfo->role,
			'status' => $getUserInfo->status,
			'unique_id' => $getUserInfo->unique_id,
			'ib_status' =>$getUserInfo->ib_status,
			'login_from' =>'admin',
			'admin_options' =>json_encode($_SESSION),
		);

		$this->session->set_userdata($session_data);

		redirect(base_url() . 'user/dashboard');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
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
			$dataitem['userlist'] 			=$this->UserModel->getUserList();
			$dataitem['groupList'] 			=$this->GroupModel->getGroup();
			if($request['type'] == 'web') {
				self::renderView('open_new_account', $dataitem,'','Open New Account');
			}else{
				self::response(200,"Not Found");
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

		$request	=self::isAuth(false);

		if($request['auth']==true) {

			/*---Validate Trading account info  Field Options-------------*/
			$this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
			$this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
			$this->form_validation->set_rules('leverage', 'Leverage','trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'message'    		=>"You have to select at least one group card",
					'group_id'			=>strip_tags(form_error('group_id')),
					'group_name'		=>strip_tags(form_error('group_name')),
					'leverage'			=>strip_tags(form_error('leverage')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_open_account']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/account/user-mt5-account-create');

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
						$userInfo = $this->UserModel->getUser($_REQUEST['unique_id']);
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
					$getTradingAccountResponse	=$this->mt5_instance->createTradingAccount($_REQUEST,$userInfo,$investorPassword,$agentId);

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
						$data['mt5_response'] 		= $getTradingAccountResponse;
						$data['client'] 			= $request['type'];
						$data['user_id'] 			= $userInfo->user_id;
						$data['pass_main'] 			= openssl_decrypt($userInfo->raw_pwd,"AES-128-ECB",'password');
						$data['pass_investor'] 		= $investorPassword;

						$getGroupID = $this->TradingAccount->insertTradingAccount($data);

						if ($getGroupID) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Create Trading Account |'.$userInfo->first_name.' '.$userInfo->last_name.'| Login id : '.$data['mt5_login_id'].'');

							//Email Send
							$mailHtml 	=$this->EmailConfigModel->createTradingAccount($userInfo->first_name.' '.$userInfo->last_name ,$data['mt5_login_id'],$data['pass_main'],$groupName,$leverage,$investorPassword);
							self::sendEmail($userInfo->email,'Congratulations! Your Live Trading Account has been created',$mailHtml);

							if ($request['type'] == 'api') {
								self::response(200, 'Successfully Create Trading Account');
							} else if ($request['type'] == 'web') {
								$_SESSION['success_trading_account'] = 'Successfully Create Trading Account';
								redirect('admin/account/user-mt5-account-create');
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
							redirect(base_url() . 'admin/account/user-mt5-account-create');

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
	
		public function blankKycUploadList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getKycList 	=$this->RegisterModel->blankKycUploadList();
			if($request['type'] == 'web') {
				self::renderView('non_kyc_verified_list', $getKycList,'','Kyc Verified List');
			}else{
				self::response(200,$getKycList);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}
	

	public function add_symbol_share(){

		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getSymbols 	=$this->RegisterModel->getSymbolList();
			if($request['type'] == 'web') {
				self::renderView('symbol_value', $getSymbols,'','Add Symbol');
			}else{
				self::response(200,$getSymbols);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function Mt5AccountList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getTradingAccount 	=$this->TradingAccount->getTradingAccountList();
			if($request['type'] == 'web') {
				self::renderView('mt5_account_list', $getTradingAccount,'','Mt5 Account List');
			}else{
				self::response(200,$getTradingAccount);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function kycVerifiedList()
	{
		$request	=self::isAuth(false);

		if($request['auth']==true) {
			$getKycList 	=$this->RegisterModel->getKycList();
			if($request['type'] == 'web') {
				self::renderView('kyc_verified_list', $getKycList,'','Kyc Verified List');
			}else{
				self::response(200,$getKycList);
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
			if ($validate==false){
				if ($this->session->userdata('username') != '') {
					$eventFrom = array('type' => 'web', 'auth' => true);
				}
			}else {
				$checkPermission = $this->PermissionModel->checkExistPermission($this->session->userdata('user_id'), $this->actionName);
				if ($checkPermission) {
					if ($this->session->userdata('username') != '') {
						$eventFrom = array('type' => 'web', 'auth' => true);
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
		$title['title']			=$requestTitle;
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/accounts/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
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
