<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RoleController extends MY_Controller {

	public $controllerName='';
	public $actionName	='';

	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('RoleModel');
		$this->load->library('form_validation');
		$this->load->model('PermissionModel');


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
	public function roleList()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$getRoleList 	= $this->RoleModel->getSingleRole();
				self::renderView('index',$getRoleList,'','Settings');
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

	public function createRoleView(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				self::renderView('create_role','','','Create Role');
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

	public function storeRole(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Role Field Options-------------*/
			$this->form_validation->set_rules('role_name', 'Role Name','required|min_length[4]|max_length[50]|is_unique[roles.role_name]');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'role_name'			=>strip_tags(form_error('role_name')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_role']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);

					redirect(base_url() . 'admin/role/create-role');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				try {
					/*--------Store our Role Informations------*/
					$_REQUEST['created_by'] 	= $this->session->userdata('user_id');
					$createRole 				= $this->RoleModel->insertRole($_REQUEST);

					if ($createRole) {

						$this->load->model('ActivityLogModel');
						$this->ActivityLogModel->createActiviyt('Create New Role | '.$_REQUEST['role_name'].'');

						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Create Role');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_role'] = 'Successfully Create Role';
							redirect('admin/role/role-list');
						}
					}
				}catch (Exception $e){
					print_r($e->getMessage());
					exit();
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function editRoleView($id){
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$getRoleList 	= $this->RoleModel->getSingleRole($id);
				self::renderView('edit_role',$getRoleList,'','Edit Role');
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

	public function updateRole(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {

			/*---Validate Role Field Options-------------*/
			$this->form_validation->set_rules('role_name', 'Role Name','required|min_length[4]|max_length[50]');
			$this->form_validation->set_rules('role_id', 'Role ID','required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'role_name'			=>strip_tags(form_error('role_name')),
					'role_id'			=>strip_tags(form_error('role_id')),
				);

				if($request['type'] == 'web'){

					$_SESSION['error_role']	=json_encode($responseData,true);
					$_SESSION['request_data']	=json_encode($_REQUEST,true);

					redirect(base_url() . 'admin/role/edit-role/'.$_REQUEST['role_id'].'');

				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}
			}else{
				try {
					/*--------Store our Role Informations------*/
					$_REQUEST['created_by'] 	= $this->session->userdata('user_id');
					$createRole 				= $this->RoleModel->insertRole($_REQUEST);

					if ($createRole) {
						if ($request['type'] == 'api') {
							self::response(200, 'Successfully Update Role');
						} else if ($request['type'] == 'web') {
							$_SESSION['success_role'] = 'Successfully Update Role';
							redirect(base_url() . 'admin/role/role-list');
						}
					}
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
		$title['title']					=$requestTitle;
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/role/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
