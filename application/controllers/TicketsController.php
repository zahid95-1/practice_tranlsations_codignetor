<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketsController extends MY_Controller {

	public $controllerName='';
	public $actionName	='';
	private $mt5_instance="";
	function __construct()
	{
		parent::__construct();
		$this->load->model('PermissionModel');
		$this->load->model('TicketModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name

		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();
	}

	/**
	 *	 This Function Providing the settings page
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function createTicketView()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				self::renderView('create','','','Create Ticket');
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

	public function saveTicket(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('department', 'Department', 'trim|required');
			$this->form_validation->set_rules('descriptions', 'Content', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'title'					=>strip_tags(form_error('title')),
					'department'			=>strip_tags(form_error('department')),
					'descriptions'			=>strip_tags(form_error('descriptions')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_create_ticket']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/add-ticket');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{

				$URL='';
				if ($_FILES['identity_proof']['name']) {
					/*-------Uploaded Site Logo Image---------*/
					$_FILES['file']['name'] 	= time().$_FILES['identity_proof']['name'];
					$_FILES['file']['type'] 	= $_FILES['identity_proof']['type'];
					$_FILES['file']['tmp_name'] = $_FILES['identity_proof']['tmp_name'];
					$_FILES['file']['error'] 	= $_FILES['identity_proof']['error'];
					$_FILES['file']['size'] 	= $_FILES['identity_proof']['size'];

					if (!file_exists('assets/users/ticket/'.$request['unique_id'].'')) {
						mkdir('assets/users/ticket/'.$request['unique_id'].'', 0777, true);
					}

					//upload to a Folder
					$config['upload_path'] = 'assets/users/ticket/'.$request['unique_id'];
					$config['allowed_types'] = 'jpg|png|pdf|jpeg';


					// Load and initialize upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$fileName = $_FILES['identity_proof']['name'];

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
							redirect(base_url() . 'user/add-ticket');
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

					$URL	="assets/users/ticket/".$request['unique_id'].'/'.$uploadDepositProf;
				}

				$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$ticketID='#'.ConfigData['prefix'].substr(str_shuffle($permitted_chars), 0, 6);

				$dataP = array(
					'user_id' 		=> $request['userId'],
					'ticket_id' 	=> $ticketID,
					'title' 		=>$_REQUEST['title'],
					'department' 	=> $_REQUEST['department'],
					'descriptions' => $_REQUEST['descriptions'],
					'ticket_attachment' => $URL,
					'status' =>1,
					'created_at' => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->TicketModel->insertTicket($dataP);
				if ($insertPaymentId){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Create One Ticket',$request['userId']);


					//	$mailHtml 	=$this->EmailConfigModel->fundDeposit($request['fullName'],$_REQUEST['amount'],$_REQUEST['mt5_login_id']);
					//	self::sendEmail($request['email'],'Fund Deposit Successful',$mailHtml);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Place Ticket');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_ticket_creations'] = 'Successfully Place Ticket.Ticket ID : '.$ticketID.'.We Will Update You Soon';
						redirect('user/ticket-list');
					}
				}
			}
		}
	}

	public function saveFeedback(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			/*---Validate Deposit info  Field Options-------------*/
			$this->form_validation->set_rules('comment', 'Title', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'comment'					=>strip_tags(form_error('comment')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_create_ticket']	=json_encode($responseData,true);
					$_SESSION['request_data']			=json_encode($_REQUEST,true);
					redirect(base_url() . 'user/ticket-list/'.$_REQUEST['main_id']);

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{

				$dataP = array(
					'ticket_id' 	=> $_REQUEST['ticket_id'],
					'comment' 		=>$_REQUEST['comment'],
					'created_by' 	=> $request['userId'],
					'created_at'     => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->TicketModel->insertTicketFeedback($dataP);

				if ($insertPaymentId){
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Place Ticket');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_ticket_creations'] = 'Successfully add comment.';
						redirect(base_url() . 'user/ticket-list/'.$_REQUEST['main_id']);
					}
				}
			}
		}
	}

	public function closeTicket(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getTicketList 	=$this->TicketModel->getTicketListAll($request['userId'],2);//get Active Data Only
			if($request['type'] == 'web'){
				self::renderView('ticket_list',$getTicketList,'','Ticket list');
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
	public function ticketListView(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getTicketList 	=$this->TicketModel->getTicketListAll($request['userId'],1);//get Active Data Only
			if($request['type'] == 'web'){
				self::renderView('ticket_list',$getTicketList,'','Ticket list');
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

	public function detailsTicket($id){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getSingleTicket 	=$this->TicketModel->getSingleTicketById($id);
			if($request['type'] == 'web'){
				self::renderView('ticket_details',$getSingleTicket,'','Ticket Details');
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
				$eventFrom=array('type'=>'web','auth'=>true,'userId'=>$this->session->userdata('user_id'),'unique_id'=>$this->session->userdata('unique_id'));
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
		$this->load->view('user/ticket/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
