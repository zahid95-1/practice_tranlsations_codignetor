<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserDashboardController extends MY_Controller {

	private $mt5_instance="";
	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('DashboardModel');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();
	}

	public function userDashboard()
	{
		$request	=self::isAuth();
		if ($request==true){

			if($request['type'] == 'web') {
				$dashboardInfo 		= $this->UserModel->getDashboardData($this->session->userdata('unique_id'),$this->mt5_instance);
				$title['title'] = 'Dashboard';
				$this->load->view('includes/header', $title);
				$this->load->view('includes/user_left_side_bar');
				$this->load->view('user/dashboard', array('dashboardData' => $dashboardInfo));
				$this->load->view('includes/footer');
			}else if($request['type'] == 'api'){
				$dashboardInfo 		= $this->UserModel->getDashboardData($request['unique_id'],$this->mt5_instance);
				unset($dashboardInfo['liveTradingInfo']);
				$responseData = array(
					'status' 	=> 200,
					'message' 	=> "Dashboard Information",
					'data' 		=>$dashboardInfo
				);
				print_r(json_encode($responseData,true));
				exit();
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function userWeBTrade(){
		$request	=self::isAuth();
		if ($request==true){
			if($request['type'] == 'web') {
				$title['title'] = 'Web Trade';
				$this->load->view('includes/header', $title);
				$this->load->view('includes/user_left_side_bar');
				$this->load->view('user/web_trade');
				$this->load->view('includes/footer');
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function getCountryList(){
		$countryList			=$this->db->query("SELECT id,name,iso FROM `country`")->result();
		$responseData = array(
			'status' 	=> 200,
			'message' 	=> "Country List",
			'data' 		=>$countryList
		);
		print_r(json_encode($responseData,true));
		exit();
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
				redirect(base_url() . 'admin/account/registered-account');
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
						'email'     =>$getUserInfo->email,
						'fullName'  =>$getUserInfo->first_name.' '.$getUserInfo->last_name
					);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			if ($this->session->userdata('username') != '' && ($this->session->userdata('role') ==1)){
				$getUserInfo 	= $this->UserModel->getUser($this->session->userdata('unique_id'));
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
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/ib_request/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
