<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLogController extends MY_Controller {

	public $controllerName='';
	public $actionName	='';
	function __construct()
	{
		parent::__construct();
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
	public function getActivityLog()
	{
		$request	=self::isAuth(false);
		if($request['auth']==true && $_SESSION['user_id']==1) {
			if($request['type'] == 'web'){
				self::renderView('index','','','Settings');
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

	public function getTableData() {

		$this->load->model('ActivityLogModel');

		$draw = $this->input->get('draw');
		$start = $this->input->get('start');
		$length = $this->input->get('length');
		$searchValue = $this->input->get('search')['value']; // Get the search value


		// Fetch data from the model
		$data = $this->ActivityLogModel->getData($start, $length, $searchValue);

		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => $this->ActivityLogModel->getTotalCount(),
			"recordsFiltered" => $this->ActivityLogModel->getFilteredCount($searchValue),
			"data" => $data
		);

		// Return data as JSON
		header('Content-Type: application/json');
		echo json_encode($response);
		exit();
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
		$this->load->view('admin/activity_log/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
