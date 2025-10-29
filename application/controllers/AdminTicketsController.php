<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTicketsController extends MY_Controller {

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

	public function closeTicket(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$getTicketList 	=$this->TicketModel->getTicketListAll('',2);//get Active Data Only
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
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$getTicketList 	=$this->TicketModel->getTicketListAll('',1);//get Active Data Only
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
		$request	=self::isAuth(false);

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

	public function saveFeedback(){
		$request	=self::isAuth(false);

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
					redirect(base_url() . 'admin/ticket/ticket-list/'.$_REQUEST['main_id']);

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{

				if (isset($_REQUEST['status'])&& !empty($_REQUEST['status'])){
					$IBstatusData = array("status" => $_REQUEST['status']);
					$this->db->set($IBstatusData);
					$this->db->where('id', $_REQUEST['main_id']);
					$update = $this->db->update('tickets');
				}

				$dataP = array(
					'ticket_id' 	=> $_REQUEST['ticket_id'],
					'comment' 		=>$_REQUEST['comment'],
					'created_by' 	=> $request['userId'],
					'created_at'     => date("Y-m-d H:i:s")
				);

				$insertPaymentId = $this->TicketModel->insertTicketFeedback($dataP);


				if ($insertPaymentId){

					if ($_REQUEST['status']==2){
						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Close Ticket | '.$_REQUEST['ticket_id'].'');
					}else{
						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Reopen Ticket | '.$_REQUEST['ticket_id'].'');
					}

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Place Ticket');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_ticket_creations'] = 'Successfully add comment.';
						redirect(base_url() . 'admin/ticket/ticket-list/'.$_REQUEST['main_id']);
					}
				}
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
		$title['title']			=$requestTitle;
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/ticket/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
