<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends MY_Controller {

	public $controllerName='';
	public $actionName	='';

	function __construct()
	{
		parent::__construct();

		$CI = &get_instance();
		$controller = $CI->router->fetch_class();  //Controller name
		$method     = $CI->router->fetch_method();  //Method name

		$this->load->model('PermissionModel');
		$this->load->model('DashboardModel');

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
	public function dashboard()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$dashboardInfo['totalClients'] = $this->DashboardModel->totalClients();
			$dashboardInfo['ibClients'] = $this->DashboardModel->IBClients();
			$dashboardInfo['liveAccounts'] = $this->DashboardModel->liveAccounts();
			$dashboardInfo['totalFund'] = $this->DashboardModel->totalFund();
			$dashboardInfo['totalWithdrawal'] = $this->DashboardModel->totalWithdrawal();
			$dashboardInfo['totalIBCommission'] = $this->DashboardModel->totalIBCommission();

			$dashboardInfo['depositData'] = $this->DashboardModel->depositData();
			$dashboardInfo['withdrawData'] = $this->DashboardModel->withdrawData();
			$dashboardInfo['internalTransferData'] = $this->DashboardModel->internalTransferData();
			$dashboardInfo['IBCommissionData'] = $this->DashboardModel->IBCommissionData();

			$dashboardInfo['Clients'] = $this->DashboardModel->Clients();
			$dashboardInfo['IBpartners'] = $this->DashboardModel->IBpartners();

			$title['title'] = 'Dashboard';
			$this->load->view('includes/header', $title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/dashboard', $dashboardInfo);
			$this->load->view('includes/footer');
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
				$getUserInfo 	= $this->UserModel->getUser($uniqueKey,0); //0 for admin access
				if ($getUserInfo){
					$eventFrom=array('type'=>'api','auth'=>true,'userId'=>$getUserInfo->user_id,'unique_id'=>$getUserInfo->unique_id);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			$checkPermission	=$this->PermissionModel->checkExistPermission($this->session->userdata('user_id'),$this->actionName);
			if ($checkPermission) {
				if ($this->session->userdata('username') != '') {
					$eventFrom=array('type'=>'web','auth'=>true,'userId'=>$this->session->userdata('user_id'),'unique_id'=>$this->session->userdata('unique_id'));
				}
			}else{
				redirect(base_url() . 'error/404');
			}
		}
		return $eventFrom;
	}

	public function login_admin()
	{

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==1)) {
			if (isset($_SESSION['login_from'])){
				$decodeAdminJson=json_decode($_SESSION['admin_options']);

				$session_data = array(
					'user_id' => $decodeAdminJson->user_id,
					'username' => $decodeAdminJson->username,
					'role' => $decodeAdminJson->role,
					'status' => $decodeAdminJson->status,
				);

				unset($_SESSION['admin_options']);
				unset($_SESSION['login_from']);

				$this->session->set_userdata($session_data);
				redirect(base_url() . 'admin/dashboard');
			}

		}else{
			redirect(base_url() . 'login');
		}
	}
}
