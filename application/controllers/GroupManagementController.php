<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupManagementController extends MY_Controller {

	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
		$this->load->library('CMT5Request');
		$this->load->library('form_validation');
		$this->load->model('GroupModel');
		$this->load->model('UserModel');
		$this->load->model('TradingAccount');
		$this->mt5_instance =new CMT5Request();

		$this->load->model('PermissionModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name
	}

	/**
	 *	 This Function Maintaining Group Listing JSON response for API and View Response For Web
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function groupList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getGroupList 	= $this->GroupModel->getGroup();

			if($request['type'] == 'web'){
				self::renderView('group_list',$getGroupList,'','Group List');
			}else if($request['type'] == 'api'){
				self::response(200,$getGroupList);
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
	 *	 This Function provides interface for creating group
	 *   Param : ''
	 *   Return : View
	 *   Version : 1.0.1
	 */
	public function createGroup(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			self::renderView('create_group','','','Create Group');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	/**
	 *	 This Function Maintaining JSON response for API and View Response For Web
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function storeGroup(){

		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Group Field Options-------------*/
			$this->form_validation->set_rules('group_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('mt5_group_name', 'Group Name','trim|required');
			$this->form_validation->set_rules('minimum_deposit', 'Minimum Deposit','trim|required');
			$this->form_validation->set_rules('spread_from', 'Spread From','trim|required');
			$this->form_validation->set_rules('commission', 'Commission','trim|required');
			$this->form_validation->set_rules('status', 'Commission','trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'group_name'		=>strip_tags(form_error('group_name')),
					'mt5_group_name'	=>strip_tags(form_error('mt5_group_name')),
					'minimum_deposit'	=>strip_tags(form_error('minimum_deposit')),
					'spread_from'		=>strip_tags(form_error('spread_from')),
					'commission'		=>strip_tags(form_error('commission')),
					'group_status'		=>strip_tags(form_error('status')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_group']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);
					redirect(base_url() . 'admin/group-management/create-group');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				try {
					/*--------Create MT5 Group Functionality------*/
//					$getResponseGroup	=$this->mt5_instance->createGroup($_REQUEST);
//					if ($getResponseGroup!=false) {

					/*--------Store our DB Group Functionality------*/
					$_REQUEST['created_by'] = $this->session->userdata('user_id');

					$getGroupID = $this->GroupModel->insertGroup($_REQUEST);
					if ($getGroupID) {

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Create Group | '.$_REQUEST['group_name'].'');

						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Create Group');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_group'] = 'Successfully Create Group';
							redirect('admin/group-management/group-list');
						}
					}
//					}else{
//
//						/*--------Handelling Mt5 Creating Error Response------*/
//						$responseData	=array(
//							'status'    		=> 400,
//							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
//						);
//
//						if($request['type'] == 'web'){
//							$_SESSION['error_group']	=json_encode($responseData,true);
//							$_SESSION['request_data']	=json_encode($_REQUEST,true);
//							redirect(base_url() . 'admin/group-management/create-group');
//
//						}else if($request['type'] == 'api'){
//							self::response(400,$responseData);
//						}
//					}
				}catch (Exception $e){
					print_r($e->getMessage());
					exit();
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Provides for editing group data
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function editGroup($groupId=''){
		$request	=self::isAuth(false);
		if($request['auth']) {
			$singleGroup 	= $this->GroupModel->getGroup($groupId);
			$params			=array('groupId'=>$groupId);
			if ($request['type'] == 'api') {
				if ($singleGroup) {
					self::response(200, $singleGroup);
				}else{
					self::response(400, 'Not found');
				}
			} else if ($request['type'] == 'web') {
				self::renderView('edit_group',$singleGroup,$params,'Edit Group');
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
	 *	 This Function Maintaining JSON response for API and View Response For Web
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function updateGroup(){

		$request	=self::isAuth(false);

		if($request['auth']==true) {

			/*---Validate Group Field Options-------------*/
			$this->form_validation->set_rules('id', 'Group id', 'trim|required');
			$this->form_validation->set_rules('group_name', 'Name', 'trim|required');
//			$this->form_validation->set_rules('mt5_group_name', 'Group Name','trim|required');
			$this->form_validation->set_rules('minimum_deposit', 'Minimum Deposit','trim|required');
			$this->form_validation->set_rules('spread_from', 'Spread From','trim|required');
			$this->form_validation->set_rules('commission', 'Commission','trim|required');
			$this->form_validation->set_rules('status', 'Commission','trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'id'				=>strip_tags(form_error('id')),
					'group_name'		=>strip_tags(form_error('group_name')),
					'mt5_group_name'	=>strip_tags(form_error('mt5_group_name')),
					'minimum_deposit'	=>strip_tags(form_error('minimum_deposit')),
					'spread_from'		=>strip_tags(form_error('spread_from')),
					'commission'		=>strip_tags(form_error('commission')),
					'group_status'		=>strip_tags(form_error('status')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_group']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);

					redirect(base_url() . 'edit-group-user-list/'.$_REQUEST['id']);

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				/*--------Create Group Functionality------*/
				$checkUpdateStatus = $this->GroupModel->updateGroup($_REQUEST);

				if($checkUpdateStatus) {
					if ($request['type']  == 'api') {
						self::response(200,'Successfully Update Group');
					}else if ($request['type']  == 'web') {
						$_SESSION['success_group']='Successfully Update Group';
						redirect('admin/group-management/group-list');
					}
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 *	 This Function Return Update Client View With Group User Data and UserList
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function updateClientGroup(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			$dataItem 	= $this->GroupModel->getMt5ClientGroupData();
			if($request['type'] == 'web'){
				self::renderView('update_client_group',$dataItem,'','Update CLient Group');
			}else if ($request['type']  == 'api') {
				self::response(200,$dataItem);
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
	 *	 This Function Provides User Details under this group.
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function viewDetails($groupId){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$getAllUserList = $this->GroupModel->groupUserlistByGroupID($groupId);
			if($request['type'] == 'web'){
				self::renderView('user_list',$getAllUserList,'','View Details');
			}else if($request['type'] == 'api'){
				self::response(200,$getAllUserList);
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
	 *	 This Function Maintaining the changing client group
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function changeClientGroup($request=""){

		$request	=self::isAuth(false);

		if($request['auth']==true) {

			$this->form_validation->set_rules('mt5_login_id', 'User account ID', 'trim|required');
			$this->form_validation->set_rules('group_id', 'Group Name', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'mt5_login_id'			=>strip_tags(form_error('mt5_login_id')),
					'group_id'			=>strip_tags(form_error('group_id')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_client_group']	=json_encode($responseData,true);
					redirect(base_url() . 'admin/group-management/update-client-group');
				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else {
				$gid = $_REQUEST['group_id'];
				$getGroup = $this->db->query("SELECT mt5_group_name FROM `groups` where id='$gid'")->row();
				$getTradingUpdateResponse = $this->mt5_instance->updateTradingUserGroup($_REQUEST, $getGroup->mt5_group_name);
				if ($getTradingUpdateResponse != false) {
					$getTradingAccount = $this->TradingAccount->getTradingAccountByLoginId($_REQUEST['mt5_login_id']);
					if ($getTradingAccount) {
						$checkUpdateStatus = $this->GroupModel->updateClientGroup($_REQUEST);
						if ($checkUpdateStatus) {

							$this->load->model('ActivityLogModel');
							$this->ActivityLogModel->createActiviyt('Change Client Group To | '.$getGroup->mt5_group_name.'');

							if ($request['type'] == 'api') {
								self::response(200, 'Successfully Update Group');
							} else if ($request['type'] == 'web') {
								$_SESSION['success_client_group'] = 'Successfully Update Group';
								redirect('admin/group-management/update-client-group');
							}
						}
					} else {
						self::response(400, 'Wrong account ID');
					}
				}else{
					/*--------Handelling Mt5 Creating Error Response------*/
					$responseData	=array(
						'status'    		=> 400,
						'mt5_error'			=>"Account Limit Reached. You can't update/change group",
					);

					if($request['type'] == 'web'){
						$_SESSION['error_client_group']		=json_encode($responseData,true);
						$_SESSION['request_data']			=json_encode($_REQUEST,true);
						redirect(base_url() . 'admin/group-management/update-client-group');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
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
			$this->load->view('admin/group_management/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
			$this->load->view('includes/footer');
		}
	}
