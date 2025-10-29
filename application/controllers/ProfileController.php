<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('DashboardModel');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();
		$this->load->model('UserModel');
		$this->load->model('ProfileModel');
		$this->load->library('form_validation');
	}

	public function kyc()
	{
		$getHeader	=$this->input->request_headers();	
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
		$this->load->view('user/profile/kyc_list');
		}else{
			$title['title']					='Kyc';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/user_left_side_bar');
			$this->load->view('user/profile/kyc_list');
			$this->load->view('includes/footer');
		}
	}
	public function change_password()
	{
		$title['title']					='Change Password';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/profile/change_password');
		$this->load->view('includes/footer');
	}

	public function change_pin()
	{
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
			$uid = $getHeader['uid'];
			}else{
				$uid = $_SESSION['unique_id'];
				$request = 'web';
			}
		
		if($request  == 'api'){
			$getUserInfo = $this->ProfileModel->getUserInfo($uid);
			$responseData = array(
					'status' 	=> 200,
					'data' 		=> "success"
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
		
	}else{
		$getUserInfo = $this->ProfileModel->getUserInfo($uid);
		self::renderView('change_pin',$getUserInfo,'','Pin');
		
		}

	}

	public function get_active_coins(){
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
			}
		$getCoins = $this->ProfileModel->getcoin();
		if($request == 'api'){
			$responseData = array(
					'status' 	=> 200,
					'data' 		=> $getCoins
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
		}
	}
	public function coinpayment_address()
	{
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
			$uid = $getHeader['uid'];
			}else{
				$uid = $_SESSION['unique_id'];
				$request = 'web';
			}
	$getCoinpaymentAddress = $this->ProfileModel->getcoinpaymentdetails($uid);
	$getCoins = $this->ProfileModel->getcoin();
	$data['caddress'] = $getCoinpaymentAddress;
	$data['coin'] = $getCoins;

	if($request  == 'web'){
		$title['title']					='Coin Payment Address';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/user_left_side_bar');
		$this->load->view('user/profile/coinpayment_address',$data);
		$this->load->view('includes/footer');
	}else{
		$responseData = array(
					'status' 	=> 200,
					'data' 		=> "success",
					'uid' => $getHeader['uid'],
					'coinpayment_address' => 	$data['caddress'] 
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
		}
		
	}


	public function submit_coinpayment_address()
	{
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
			}else{
				$request = 'web';
			}
			
		$data = array('wallet_address' => $_REQUEST['coinpayment_address'],
			'coin_id' =>$_REQUEST['coin'],
				'unique_id'=>$_REQUEST['uid']
		);
				$uid =$_REQUEST['uid'];
		$getCoinpaymentData = $this->ProfileModel->coinpaymentdetails($data,$uid);

		if($getCoinpaymentData){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Added Crypto Wallet Address');

			if($request == 'api'){
					$responseData = array(
					'status' 	=> 200,
					'data' 		=> "success",
					'uid' => $uid
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Crypto wallet address added successfully. .'); //set success msg if
					redirect(base_url().'user/coinpayment-address');
				}
		}else{
			if($request == 'api'){
					$responseData = array(
					'status' 	=> 400,
					'data' 		=> "failure",
					'uid' => $uid
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Update Failed'); //set success msg if
					redirect(base_url().'user/coinpayment-address');
				}
		}
	}

	
	public function deleteUserAccount()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {


			if($request['type'] == 'web'){
			$deleteAccount		=$this->ProfileModel->deleteAccount($this->session->userdata('unique_id'));
			redirect(base_url().'logout');
				}else if($request['type'] == 'api'){
			$deleteAccount		=$this->ProfileModel->deleteAccount($request['unique_id']);
				self::response(200,$deleteAccount);
		}
			
		}
	}
	public function bank_details()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$bankDetails		=$this->ProfileModel->getbankDetails($request['unique_id']);

			if($request['type'] == 'web'){
			self::renderView('bank_details',$bankDetails,'','Bank Details');
				}else if($request['type'] == 'api'){
					self::response(200,$bankDetails);
				}
				
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function submit_bank_details()
	{
		$userId='';
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';

				if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
					$uniqueKey		=$getHeader['Authorization'];
					$getUserInfo 	= $this->UserModel->getUser($uniqueKey);
					if ($getUserInfo){
						$userId=$getUserInfo->user_id;
					}
				}

			}else{
				$request = 'web';
			}

		$data = array('account_name' => $this->security->xss_clean($this->input->get_post('account_name')),
					  'account_number' => $this->security->xss_clean($this->input->get_post('account_number')),
					  'swift_code' => $this->security->xss_clean($this->input->get_post('swift_code')),
					  'trx_code' => $this->security->xss_clean($this->input->get_post('bank_trx_code')),
//					  'international_bank_account_number' => $this->security->xss_clean($this->input->post('international_bank_account_no')),
					  'bank_name' => $this->security->xss_clean($this->input->get_post('bank_name')),
					  'bank_address' => $this->security->xss_clean($this->input->get_post('bank_address')),
					  'country_id' => $this->security->xss_clean($this->input->get_post('country_id')),
					  'unique_id' => $this->security->xss_clean($this->input->get_post('uid')),
		);
		$data['created_by']=$this->security->xss_clean($this->input->get_post('uid'));
		$data['updated_by']=$this->security->xss_clean($this->input->get_post('uid'));
		$data['created_datetime']=date('Y-m-d H:i:s');
		$data['updated_datetime']=date('Y-m-d H:i:s');
		$data['status']='1';
		$data['status_change_datetime']=date('Y-m-d H:i:s');

		$uid = $this->security->xss_clean($this->input->get_post('uid'));
		$getBankData = $this->ProfileModel->bankdetails($data,$uid);

		if($getBankData){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Submit Bank Details Info',$userId);

			if($request == 'api'){
					$responseData = array(
					'status' 	=> 200,
					'data' 		=> "success",
					'uid' => $uid
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Band Details added successfully.'); //set success msg if
					redirect(base_url().'user/bank-details');
				}
		}else{
			if($request == 'api'){
					$responseData = array(
					'status' 	=> 400,
					'data' 		=> "failure",
					'uid' => $uid
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Password update Failed'); //set success msg if
					redirect(base_url().'user/bank-details');
				}
		}
	}

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

	public function update_password()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {

			$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
			$this->form_validation->set_rules('n_password', 'New Password', 'trim|required');
			$this->form_validation->set_rules('r_password', 'New Password', 'required|min_length[8]|callback_password_check');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'c_password'		=>strip_tags(form_error('c_password')),
					'n_password'		=>strip_tags(form_error('n_password')),
				);

				if($request['type'] == 'web'){
					redirect(base_url().'user/change-crm-password');
				}else if($request['type'] == 'api'){
					print_r(json_encode($responseData,true));
					exit();
				}

			}else{

				$old_pwd = $this->security->xss_clean($this->input->post('c_password'));
				$new_pwd = $this->security->xss_clean($this->input->post('n_password'));
				$re_pwd = $this->security->xss_clean($this->input->post('r_password'));

				if($request['type'] == 'web') {
					$uid = $this->security->xss_clean($this->input->post('uid'));
				}else if ($request['type'] == 'api'){
					$uid = $request['unique_id'];
				}

				$getProfileData = $this->ProfileModel->changepasswordUser($old_pwd,$new_pwd,$re_pwd,$uid);

				if($getProfileData){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update Password');

					if($request['type'] == 'api'){
						$responseData = array(
							'status' 	=> 200,
							'message' 		=> "successfully change password",
							'data' 		=> array(
								'uid'=>$uid
							)
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request['type'] == 'web'){
						$this->session->set_flashdata('msg', 'Password updated Successfully'); //set success msg if
						redirect(base_url().'user/change-crm-password');
					}
				}else{
					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Update Password');

					if($request['type'] == 'api'){
						$responseData = array(
							'status' 	=> 400,
							'message' 		=> "You current password not match",
							'data' 		=> array(
								'uid'=>$uid
							)
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request['type'] == 'web'){
						$this->session->set_flashdata('msg', 'Password update Failed'); //set success msg if
						redirect(base_url().'user/change-crm-password');
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


	public function update_pin()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {

			$this->form_validation->set_rules('c_pin', 'Confirm Pin', 'trim');
			$this->form_validation->set_rules('n_pin', 'New Pin', 'trim|required');
			$this->form_validation->set_rules('r_pin', 'New Pin', 'required|min_length[6]|max_length[6]');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'status'    		=> 400,
					'c_pin'		=>strip_tags(form_error('c_pin')),
					'n_pin'		=>strip_tags(form_error('n_pin')),
				);

				if($request['type'] == 'web'){
					redirect(base_url().'user/change-crm-password');
				}else if($request['type'] == 'api'){
					print_r(json_encode($responseData,true));
					exit();
				}
			}else{

				$old_pin = $this->security->xss_clean($this->input->post('c_pin'));
				$new_pin = $this->security->xss_clean($this->input->post('n_pin'));
				$re_pin = $this->security->xss_clean($this->input->post('r_pin'));

				if($request['type'] == 'web') {
					$uid = $this->security->xss_clean($this->input->post('uid'));
				}else if ($request['type'] == 'api'){
					$uid = $request['unique_id'];
				}

				$getProfileData = $this->ProfileModel->changepinUser($old_pin,$new_pin,$re_pin,$uid);

				if($getProfileData){

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Change Pin Number');

					if($request['type'] == 'api'){
						$responseData = array(
							'status' 	=> 200,
							'message' 		=> "successfully changed pin",
							'data' 		=> array(
								'uid'=>$uid
							)
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request['type'] == 'web'){
						$this->session->set_flashdata('msg', 'Pin updated Successfully'); //set success msg if
						redirect(base_url().'user/change-crm-pin');
					}
				}else{
					if($request['type'] == 'api'){
						$responseData = array(
							'status' 	=> 400,
							'message' 		=> "You current pin not match",
							'data' 		=> array(
								'uid'=>$uid
							)
						);

						$res = json_encode($responseData,true);
						print_r($res);
						exit();
					}else if($request['type'] == 'web'){
						$this->session->set_flashdata('msg', 'pin update Failed'); //set success msg if
						redirect(base_url().'user/change-crm-pin');
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
	public function isAuth(){
		$getHeader	=$this->input->request_headers();
		$eventFrom=array();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			if (isset($getHeader['Authorization']) && !empty($getHeader['Authorization'])){
				$uniqueKey		=$getHeader['Authorization'];
				$getUserInfo 	= $this->UserModel->getUser($uniqueKey);
				if ($getUserInfo){
					$eventFrom=array('type'=>'api',
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
		$this->load->view('user/profile/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}

}
