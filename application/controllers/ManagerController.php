<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManagerController extends MY_Controller {

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
		$this->load->model('RegisterModel');
		$this->mt5_instance =new CMT5Request();

		$this->load->model('PermissionModel');

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
	public function addManager()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			self::renderView('index','','','Manager List');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

    public function createManager()
    {
        $request	=self::isAuth(false);

        $this->form_validation->set_rules('first_name', 'First Name','trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('phone', 'Mobile No', 'trim|required');
        $this->form_validation->set_rules('role', 'Role', 'trim|required');

        /*=====================================log1_11/6/2020====================================*/
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="error-msg">', '</div>');

        $email 				= $this->security->xss_clean($this->input->post('email'));
        $checExistEmail = $this->RegisterModel->checkDuplicateEmail($email);

        if ($checExistEmail>0){
            $this->form_validation->set_rules('email', 'Email exist','trim|required');
        }

        if ($this->form_validation->run() == FALSE)
        {
            /*--------Error Response------*/
            $responseData	=array(
                'first_name'		=>strip_tags(form_error('first_name')),
                'last_name'		=>strip_tags(form_error('last_name')),
                'email'				=>strip_tags(form_error('email')),
                'phone'				=>strip_tags(form_error('phone')),
                'password'			=>strip_tags(form_error('password')),
                'role'			=>strip_tags(form_error('role')),
            );

            $_SESSION['error_new_user']			=json_encode($responseData,true);
            $_SESSION['request_data']			=json_encode($_REQUEST,true);
            $data['errorMsg'] 					='Unable to save user. Please try again';

            redirect(base_url() . 'admin/manager/add-new-manager',$data);
        }else{

            $first_name 		= $this->security->xss_clean($this->input->post('first_name'));
            $last_name 			= $this->security->xss_clean($this->input->post('last_name'));
            $email 				= $this->security->xss_clean($this->input->post('email'));
            $mobile 			= $this->security->xss_clean($this->input->post('phone'));
            $role 			    = $this->security->xss_clean($this->input->post('role'));
            $password 			= md5($this->security->xss_clean($this->input->post('password')));
            $now 				= date('Y-m-d H:i:s');
            $rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');

            $insertData = array(
                'unique_id'=>'GS'.rand(1000,9999).rand(10,99),
                'email'=>$email,
                'mobile'=>$mobile,
                'first_name'=>$first_name,
                'password'=>$password,
                'last_name'=>$last_name,
                'created_by'=>$email,
                'role'=>$role,
                'raw_pwd'=>$rawpwd,
                'created_datetime'=>$now);

            $insertUser = $this->RegisterModel->insertUser($insertData);

            if($request['type'] == 'web'){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Create Manager | '.$email.'');

                if ($insertUser) {
                    $_SESSION['success_manager'] = 'Successfully Create Manager';
                    redirect(base_url() . 'admin/manager/manager-management');
                }
            }else if($request['type'] == 'api'){
                self::response(400,'Successfully Create Manager');
            }
        }
    }

	public function assignPermission($userUniqID){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$dataItem['userItem']				=$this->UserModel->getOnlyManagementUser($userUniqID);
			$dataItem['mainModiules']			=$this->UserModel->getMainModiules();

			self::renderView('permission',$dataItem,'','Manage Manager');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function managementManager()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$userList 	=$this->UserModel->getOnlyManagementUser();
			self::renderView('management',$userList,'','Manage Manager');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function authStatusChange(){

		if (isset($_REQUEST['subModulesId']) && isset($_REQUEST['modulesId']) && isset($_REQUEST['status'])){

			$mId		=$_REQUEST['modulesId'];
			$sId		=$_REQUEST['subModulesId'];
			$status		=$_REQUEST['status'];
			$uId		=$_REQUEST['userId'];

			$checkExist	=$this->db->query("SELECT * FROM `auth` where modules_id=$mId and sub_modules_id=$sId and user_id=$uId")->row();

			if ($checkExist){
				$dataL = array("status" =>$_REQUEST['status']);
				$this->db->set($dataL);
				$this->db->where('id', $checkExist->id);
				$update=$this->db->update('auth');
				if ($update){
					echo 1;
					exit();
				}
			}else{

				$dataL = array("status" =>$_REQUEST['status'],'user_id'=>$uId,'modules_id'=>$mId,'sub_modules_id'=>$sId);
				if($this->db->insert('auth', $dataL))
				{
					echo 1;
					exit();
				}

			}
		}
	}

	/**
	 *	 This Function Maintaining the authenticaitons
	 *   Return : Array
	 *   Version : 1.0.1
	 */
	public function isAuth($checkAuth=true){
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
		    if ($checkAuth==true){
                $checkPermission	=$this->PermissionModel->checkExistPermission($this->session->userdata('user_id'),$this->actionName);
                if ($checkPermission) {
                    if ($this->session->userdata('username') != '') {
                        $eventFrom=array('type'=>'web','auth'=>true);
                    }
                }else{
                    redirect(base_url() . 'error/404');
                }
            }else{
                $eventFrom=array('type'=>'web','auth'=>true);
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
		$this->load->view('admin/manager/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
