<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExchangerManagementController extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('ExchangerModel');
		$this->load->model('PermissionModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name
	}

	/**
	 *	 This Function Maintaining Exhanger Listing JSON response for API and View Response For Web
	 *   Param : web/api
	 *   Return : Json Or View
	 *   Version : 1.0.1
	 */
	public function exchangerList()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getexchangerList 	= $this->ExchangerModel->getExchagerList();

			if($request['type'] == 'web'){
				self::renderView('exchanger-list',$getexchangerList,'','Exchanger List');
			}else if($request['type'] == 'api'){
				self::response(200,$getexchangerList);
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function exchanger_deposit()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getexchangerDeposit 	= $this->ExchangerModel->getExchagerDeposit();

			if($request['type'] == 'web'){
				self::renderView('exchanger-deposit',$getexchangerDeposit,'','Exchanger Deposit');
			}else if($request['type'] == 'api'){
				self::response(200,$getexchangerDeposit);
			}

		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}


	public function exchanger_withdraw()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getexchangerWithdraw	= $this->ExchangerModel->getExchagerWithdraw();

			if($request['type'] == 'web'){
				self::renderView('exchanger-withdraw',$getexchangerWithdraw,'','Exchanger Withdraw');
			}else if($request['type'] == 'api'){
				self::response(200,$getexchangerWithdraw);
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
	 *	 This Function provides interface for creating exchanger
	 *   Param : ''
	 *   Return : View
	 *   Version : 1.0.1
	 */
	public function addExchanger(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			self::renderView('add-exchanger','','','Add Exchanger');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	
}

public function add_bank_details(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			self::renderView('add-bank-details','','','Add Bank Details');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	
}




public function edit_exchanger(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			self::renderView('edit-exchanger','','','Edit Exchanger');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	
}

public function transfer_exchanger(){
		$request	=self::isAuth();
		$getexchangerList 	= $this->ExchangerModel->getExchagerList();
		$getCurrencyList 	= $this->ExchangerModel->getCurrencyList();
		$getBankList 	= $this->ExchangerModel->getBankList();

		$datalist = array('exchangerlist' => $getexchangerList,
						  'currencylist' =>	$getCurrencyList,
							'banklist' => $getBankList);
		if($request['auth']==true) {
			self::renderView('transfer-exchanger',$datalist,'','Transfer Exchanger');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
			
	
	
}




public function save_bank_details(){
			/*--------Store our DB Exchanger Functionality------*/
		$data = array('account_no'=>$_REQUEST['account_no'],
					  'ifsc_code'=>$_REQUEST['ifsc_code'],
					  'bank_name'=>$_REQUEST['bank_name'],
					  'branch_name'=>$_REQUEST['branch_name'],
					  'created_by'=> $this->session->userdata('user_id'),
					  'created_datetime' => date("Y-m-d H:i:s")
					);
		

		$InsertBankDetails = $this->ExchangerModel->insertBankDetails($data);
			if ($InsertBankDetails) {
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Added');
					} else  {
						$_SESSION['success_exchanger'] = 'Successfully Added';
						redirect('admin/exchanger-management/add-bank-details');
					}
				}
		}
public function save_edit_exchanger(){
			/*--------Store our DB Exchanger Functionality------*/
		$data = array('email'=>$_REQUEST['email'],
					  'mobile'=>$_REQUEST['phone'],
					  'updated_by'=> $this->session->userdata('user_id'),
					  'updated_datetime' => date("Y-m-d H:i:s")
					);
		$exchangerId = $_REQUEST['exchanger_id'];

		$updateExchanger = $this->ExchangerModel->updateExchanger($data,$exchangerId);
			if ($updateExchanger) {
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Updated Exchanger');
					} else  {
						$_SESSION['success_exchanger'] = 'Successfully Updated Exchanger';
						redirect('admin/exchanger-management/exchanger-list');
					}
				}
		}

public function save_transfer_exchanger(){
			/*--------Store our DB Exchanger Functionality------*/
		$data = array('from_exchanger_id'=>$_REQUEST['transfer_from'],
					  'to_exchanger_id'=>$_REQUEST['transfer_to'],
					  'from_currency'=>$_REQUEST['from_currency'],
					  'amount'=>$_REQUEST['amount'],
					  'coverage_account_no'=>$_REQUEST['coverage_account_no'],
					  'bank_id' =>$_REQUEST['bank_account_no'],
					  'type'=>$_REQUEST['transfer_type'],
					  'created_by'=> $this->session->userdata('user_id'),
					  'created_datetime' => date("Y-m-d H:i:s")
					);
		

		$saveTransfer = $this->ExchangerModel->saveExchangeTransfer($data);
			if ($saveTransfer) {
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Transferred');
					} else  {
						$_SESSION['success_exchanger'] = 'Successfully Transferred';
						redirect('admin/exchanger-management/exchanger-list');
					}
				}
		}


		public function save_new_exchanger(){
			/*--------Store our DB Exchanger Functionality------*/
		$data = array('name'=>$_REQUEST['e_name'],
					  'email'=>$_REQUEST['email'],
					  'mobile'=>$_REQUEST['phone'],
					  'created_by'=> $this->session->userdata('user_id'),
					  'created_datetime' => date("Y-m-d H:i:s")
					);

		$insertExchanger = $this->ExchangerModel->insertExchanger($data);
			if ($insertExchanger) {
					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Create Exchanger');
					} else  {
						$_SESSION['success_exchanger'] = 'Successfully Created Exchanger';
						redirect('admin/exchanger-management/exchanger-list');
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
			$this->load->view('admin/exchanger_management/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
			$this->load->view('includes/footer');
		}

}
