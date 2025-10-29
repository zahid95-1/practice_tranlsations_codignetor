<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyLiveTradeController extends MY_Controller {

	private $mt5_instance="";
	function __construct()
	{
		parent::__construct();
		$this->load->library('CMT5Request');
		$this->load->library('form_validation');
		$this->load->model('GroupModel');
		$this->load->model('UserModel');
		$this->load->model('TradingAccount');
		$this->mt5_instance =new CMT5Request();
	}

	public function liveTrades(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$liveTraders		=$this->TradingAccount->getLiveTradersAll($request['userId'],$request['type']);
			if($request['type'] == 'web'){
			self::renderView('live_traders',$liveTraders,'','Live Traders');
				}else if($request['type'] == 'api'){
					self::response(200,$liveTraders);
				}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function closeTraders(){

		$request	=self::isAuth();

		if($request['auth']==true) {

			$dataitem		=$this->TradingAccount->getCloseTradersAll($request['userId'],$_REQUEST);
			$filterOption=$fromData=$toDate='';
			$dataArray=array();
			if (isset($_REQUEST['filtering_options']) && !empty($_REQUEST['filtering_options'])){
				$filterOption       =$_REQUEST['filtering_options'];
				$fromData           =$_REQUEST['from_date'];
				$toDate             =$_REQUEST['to_date'];
				$dataitem['filter']	=array('filterId'=>$filterOption,'from_date'=>$fromData,'to_date'=>$toDate);
			}
			if($request['type'] == 'web'){
				self::renderView('close_traders',$dataitem,'','Live Traders');
			}else{
				self::response(200,$dataitem);
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
		$this->load->view('user/traders/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
