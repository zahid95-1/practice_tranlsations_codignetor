<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IBManagementController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('IbModel');
		$this->load->model('UserModel');
		$this->load->model('PermissionModel');
		$this->load->model('GroupModel');
		$this->load->library('form_validation');
		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();
		$this->load->model('EmailConfigModel');

		$CI = &get_instance();
		$this->controllerName = $CI->router->fetch_class();  //Controller name
		$this->actionName     = $CI->router->fetch_method();  //Method name
	}

	public function ibUserList()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getUserIbList['UserIbList'] = $this->IbModel->getUserIbListing();
			if($request['type'] == 'web'){
				self::renderView('ib_users_list',$getUserIbList,'','Ib User List');
			}else if($request['type'] == 'api'){
				self::response(200,$getUserIbList);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}






	public function oldibList()
	{

		$data['OldIbList'] = $this->IbModel->getOldIbListing();
		$title['title']    ='Old IB Data';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/ib_management/old_ib_data',$data);
		$this->load->view('includes/footer');


	}

	public function UserIBCommGroup()
	{


		$getHeader  =$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}
		$getUserIbCommissionGroup['UserIbCommissionGroup'] = $this->IbModel->getUserIbCommissionGroup();

		if($request == 'web'){
			$title['title'] ='User Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/user_left_side_bar');
			$this->load->view('user/ib_request/ib_commission_group',$getUserIbCommissionGroup);
			$this->load->view('includes/footer');
		}else if($request == 'api'){


			$sessionUser = $getHeader['uid'];



			$getselfr = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->result();
			$getReflinkCnt = $this->db->query("SELECT max(ref_link_name) as ref_link_cnt FROM `ib_commission`where  unique_id = '$sessionUser'")->row();

			if(count($getselfr) > 0){
				for($i= 1; $i<= $getReflinkCnt->ref_link_cnt;$i++){

					$getselfrr = $this->db->query("SELECT * FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' and level_no = (SELECT MAX(level_no) FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' )")->result();
						
						foreach($getselfrr as $getselfrdetails){
							
							$getUserIbCommissionGroup["refferal_link_".$i] =  base_url()."register?reffid=".$sessionUser."&link=".$i;
							
							$getUserIbCommissionGroup["share_value_".$i] = $getselfrdetails->value;
					}

				}
			}	

			$responseData = array(
								'status'    => 200,
								'data'      => $getUserIbCommissionGroup,
							); 
			self::response(200,$responseData);
		}


	}



	public function AddMoreRefLink($groupID,$planID)
	{


		$getHeader  =$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}
		$getUserIbCommissionGroup['UserIbCommissionGroup'] = $this->IbModel->getUserIbCommissionGroup();

		if($request == 'web'){
			$title['title'] ='User Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/user_left_side_bar');
			$this->load->view('user/ib_request/add_more_ref_link',$getUserIbCommissionGroup);
			$this->load->view('includes/footer');
		}else if($request == 'api'){


			$sessionUser = $getHeader['uid'];



			$getselfr = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->result();
			$getReflinkCnt = $this->db->query("SELECT max(ref_link_name) as ref_link_cnt FROM `ib_commission`where  unique_id = '$sessionUser'")->row();

			if(count($getselfr) > 0){
				for($i= 1; $i<= $getReflinkCnt->ref_link_cnt;$i++){

					$getselfrr = $this->db->query("SELECT * FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' and level_no = (SELECT MAX(level_no) FROM `ib_commission`where  unique_id = '$sessionUser' and ref_link_name = '$i' )")->result();
						
						foreach($getselfrr as $getselfrdetails){
							
							$getUserIbCommissionGroup["refferal_link_".$i] =  base_url()."register?reffid=".$sessionUser."&link=".$i;
							
							$getUserIbCommissionGroup["share_value_".$i] = $getselfrdetails->value;
					}

				}
			}	

			$responseData = array(
								'status'    => 200,
								'data'      => $getUserIbCommissionGroup,
							); 
			self::response(200,$responseData);
		}


	}

	public function UserIBCommRef()
	{


		$getHeader  =$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}
		$getUserIbCommissionRef['UserIbCommissionRef'] = $this->IbModel->getUserIbCommissionRef();

		if($request == 'web'){
			$title['title'] ='User Commission Ref';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/user_left_side_bar');
			$this->load->view('user/ib_request/ib_commission_ref',$getUserIbCommissionRef);
			$this->load->view('includes/footer');
		}else if($request == 'api'){
			$responseData = array(
				'status'    => 200,
				'data'      => $getUserIbCommissionRef,
			);
			self::response(200,$responseData);
		}


	}

	public function UserIBCommGroupByLevel()
	{
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$getComGroup['ComGroup'] = $this->IbModel->getIbCommissionGroupLevel();
			if($request['type'] == 'web'){
				self::renderView('commission_group_level',$getComGroup,'','Ib Commission Group');
			}else if($request['type'] == 'api'){
				self::response(200,$getComGroup);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function ibCommissionGroupLevelView()
	{
		$data['ibplanlist'] = $this->IbModel->getIbList();
		$data['grouplist'] = $this->IbModel->getGroupList();

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Edit Commission';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/commission_group_level_create',$data);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function ibCommissionRefLevelViewAddMore()
	{
		$data['ibplanlist'] = $this->IbModel->getIbList();
		$data['grouplist'] = $this->IbModel->getGroupList();

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Edit Commission';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/commission_ref_level_create_more',$data);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}

	/*=======Start IB Commission Generation Based on Refereal Link=======*/

	public function UserIBCommRefByLevel()
	{
		$request	=self::isAuth(false);
		if($request['auth']==true) {
			$getComRef['ComRef'] = $this->IbModel->getIbCommissionRefLevel();
			if($request['type'] == 'web'){
				self::renderView('commission_ref_level',$getComRef,'','Ib Commission Group');
			}else if($request['type'] == 'api'){
				self::response(200,$getComRef);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	public function ibCommissionRefLevelView()
	{
		$data['ibplanlist'] = $this->IbModel->getIbList();
		$data['grouplist'] = $this->IbModel->getGroupList();

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Edit Commission';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/commission_ref_level_create',$data);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}



		public function saveIbCommisionRef()
	{
		$planId				=$this->input->post('plan_id');
		$group_id			=$this->input->post('group_id');
		$checkGroupAndPlan  = $this->db->query("SELECT * FROM `ib_commission_ref` where plan_id = '$planId'  and group_id='$group_id'")->row();
		if (!empty($checkGroupAndPlan)){
			$this->session->set_flashdata('msg', 'You can not create same group and same plan ! Please select another group and plan');
			redirect(base_url().'admin/ib-management/commission-ref-by-level');
		}else {
			if (count ($_REQUEST[ 'LevelSetting' ])>=1) {
				foreach ( $_REQUEST[ 'LevelSetting' ] as $key => $getLevelData ) {
					$commissionLevelData = array(
						"plan_id" => $this->input->post ( 'plan_id' ),
						"group_id" => $this->input->post ( 'group_id' ),
						"user_id" => $this->session->userdata ( 'user_id' ),
						"unique_id" => $this->session->userdata ( 'unique_id' ),
						"ref_link_name" => $key,
						"level_no" => 1,
						"flat_percentage" => 1,
						"value" => $getLevelData[ 'share_value' ],
						"created_by" => $this->session->userdata ( 'user_id' ),
						"status" => 1,
						"created_datetime" => date ( "Y-m-d H:i:s" ));

					$this->db->insert ( 'ib_commission_ref', $commissionLevelData );
				}

				$this->session->set_flashdata ( 'msg', 'Commission Refferal Links  added successfully.' );
				redirect ( base_url () . 'admin/ib-management/commission-ref-by-level' );
			}else{
				$this->session->set_flashdata('msg', 'At least one level settings required');
				redirect(base_url().'admin/ib-management/commission-ref-by-level');
			}
		}
	}

			public function saveIbCommisionRefMore()
	{

		
		$planId				=$this->input->post('plan_id');
		$group_id			=$this->input->post('group_id');

	

		$checkcount  = $this->db->query("SELECT max(ref_link_name) as cnt FROM `ib_commission_ref` where plan_id = '$planId'  and group_id='$group_id'")->row();
			if (count ($_REQUEST[ 'LevelSetting' ])>=1) {
				$cnt = $checkcount->cnt +1;
				
					foreach ( $_REQUEST[ 'LevelSetting' ] as $key => $getLevelData ) {
						$commissionLevelData = array(
							"plan_id" => $this->input->post ( 'plan_id_' ),
							"group_id" => $this->input->post ( 'group_id_' ),
							"user_id" => $this->session->userdata ( 'user_id' ),
							"unique_id" => $this->session->userdata ( 'unique_id' ),
							"ref_link_name" => $cnt,
							"level_no" => 1,
							"flat_percentage" => 1,
							"value" => $getLevelData[ 'share_value_'],
							"created_by" => $this->session->userdata ( 'user_id' ),
							"status" => 1,
							"created_datetime" => date ( "Y-m-d H:i:s" ));
						$this->db->insert ( 'ib_commission_ref', $commissionLevelData );
						$cnt++;
					
				}

				$this->session->set_flashdata ( 'msg', 'Commission Refferal Links  added successfully.' );
				redirect ( base_url () . 'admin/ib-management/commission-ref-by-level' );
			}else{
				$this->session->set_flashdata('msg', 'At least one level settings required');
				redirect(base_url().'admin/ib-management/commission-ref-by-level');
			}
		
	}
	/*=======End IB Commission Generation Based on Refereal Link=======*/

	public function saveIbCommisionLevel()
	{
		$planId				=$this->input->post('plan_id');
		$group_id			=$this->input->post('group_id');
		$checkGroupAndPlan  = $this->db->query("SELECT * FROM `ib_commission_lvl` where plan_id = '$planId'  and group_id='$group_id'")->row();
		if (!empty($checkGroupAndPlan)){
			$this->session->set_flashdata('msg', 'You can not create same group and same plan ! Please select another group and plan');
			redirect(base_url().'admin/ib-management/commission-group-level');
		}else {
			if (count ($_REQUEST[ 'LevelSetting' ])>=1) {
				foreach ( $_REQUEST[ 'LevelSetting' ] as $key => $getLevelData ) {
					$commissionLevelData = array(
						"plan_id" => $this->input->post ( 'plan_id' ),
						"group_id" => $this->input->post ( 'group_id' ),
						"user_id" => $this->session->userdata ( 'user_id' ),
						"unique_id" => $this->session->userdata ( 'unique_id' ),
						"level_no" => $key,
						"flat_percentage" => 1,
						"value" => $getLevelData[ 'share_value' ],
						"created_by" => $this->session->userdata ( 'user_id' ),
						"status" => 1,
						"created_datetime" => date ( "Y-m-d H:i:s" ));

					$this->db->insert ( 'ib_commission_lvl', $commissionLevelData );
				}

				$this->session->set_flashdata ( 'msg', 'Commission group level added successfully.' );
				redirect ( base_url () . 'admin/ib-management/commission-group-by-level' );
			}else{
				$this->session->set_flashdata('msg', 'At least one level settings required');
				redirect(base_url().'admin/ib-management/commission-group-level');
			}
		}
	}

	public function viewLevelCommissionGroup($groupID,$planID){

		$getComGroupData['ComGroupData'] = $this->IbModel->getIbCommissionGroupLevelDetails($groupID,$planID);
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/commission_group_level_view',$getComGroupData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function viewLevelCommissionRef($groupID,$planID){

		$getComRefData['ComRefData'] = $this->IbModel->getIbCommissionRefLevelDetails($groupID,$planID);
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/commission_ref_level_view',$getComRefData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function GetDownlineCommissionShare()
	{
		$getHeader  =$this->input->request_headers();
		if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
		}else{
			$request = 'web';
		}

		if($request == 'web'){
			$sessionUser = $_SESSION['unique_id'];
		}else if($request == 'api'){
			$sessionUser = $getHeader['uid'];
		}
		$getself = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->row();
		$getselfr = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$sessionUser' ;")->result();
		if($request == 'api'){
			$responseData = array(
				'status'    => 200,
				'data'      => $getself,
			);
			self::response(200,$responseData);
		}
	}

	public function ibRequest()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			$getIbRequestUser 	= $this->UserModel->getIbUser(2); //request IB user
			if($request['type'] == 'web'){
				self::renderView('ib_users_request',$getIbRequestUser,'','Ib Request');
			}else if($request['type'] == 'api'){
				self::response(200,$getIbRequestUser);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function rejectedIbRequest()
	{

		$request	=self::isAuth();
		if($request['auth']==true) {
			$getIbRequestUser 	= $this->UserModel->getIbUser(3); //Rejected IB user
			if($request['type'] == 'web'){
				self::renderView('rejected_ib_users_request',$getIbRequestUser,'','Rejected Request');
			}else if($request['type'] == 'api'){
				self::response(200,$getIbRequestUser);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}


	public function approveIbAdminRequest(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {


			$this->form_validation->set_rules('group_id', 'Group Id', 'trim|required');
			$this->form_validation->set_rules('unique_id', 'User Unique id', 'trim|required');
			$this->form_validation->set_rules('ib_plan_id', 'Plan', 'trim|required');
            $this->form_validation->set_rules('ib_comm_calc_type', 'IB Comm Calc Type', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'ib_request'			=>strip_tags(form_error('ib_request')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_ib_request']	=json_encode($responseData,true);
					echo json_encode($responseData,true);
					exit;
					redirect(base_url() . 'admin/ib-management/ib-user-request');
				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				
				$getUserInfo 	= $this->UserModel->getUser($_REQUEST['unique_id'],1);
				$getGroup 		= $this->GroupModel->getGroup($_REQUEST['group_id']);

				if ($getUserInfo && $getGroup){
					$createMt5IBaccount = $this->mt5_instance->createIbAccount($getUserInfo,$getGroup);

					if ($createMt5IBaccount!=false) {
						$mt5TradingObject			=json_decode($createMt5IBaccount);
						$dataItem	=array(
							'unique_id'				=>$getUserInfo->unique_id,
							'plan_id'			=>$_REQUEST['ib_plan_id'],
							'group_id'				=>$_REQUEST['group_id'],
							'mt5_login_id'			=>$mt5TradingObject->answer->Login,
							'client'				=>'web',
							'mt5_response'			=>$createMt5IBaccount,
							'status'				=>1,
							'created_by'			=>$getUserInfo->id,
						);

						$createIbAccount  = $this->IbModel->insertIbAccounts($dataItem);
						if ($createIbAccount) {

							$data						=array('ib_status'=>1,'ib_calc_type'=> $_REQUEST['ib_comm_calc_type']);
							$lastUpdatedStatus			=$this->IbModel->changeIbStatus($data,$_REQUEST['unique_id']);

							if ($lastUpdatedStatus) {
								if ($request['type'] == 'api') {
									self::response(200, 'Successfully Create Ib Account');
								} else if ($request['type'] == 'web') {
									$_SESSION['success_trading_account'] = 'Successfully Create Trading Account';
									redirect('admin/ib-management/ib-user-request');
								}
							}
						}
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

	public function approveIbRequest(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {

			$this->form_validation->set_rules('unique_id', 'User Unique id', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'unique_id'			=>strip_tags(form_error('unique_id')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_ib_request']	=json_encode($responseData,true);
					redirect(base_url() . 'admin/ib-management/ib-user-request');
				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				

				$getUserPlanGroup 		= $this->UserModel->getUserPlanGroup($_REQUEST['unique_id']);
				$getGroup 		= $this->GroupModel->getGroup($getUserPlanGroup->group_id);

				$getUserInfo 	= $this->UserModel->getUser($_REQUEST['unique_id'],1);

				if ($getUserPlanGroup){
					$createMt5IBaccount = $this->mt5_instance->createIbAccount($getUserInfo,$getGroup );

					if ($createMt5IBaccount!=false) {
						$mt5TradingObject			=json_decode($createMt5IBaccount);
						$dataItem	=array(
							'unique_id'				=>$getUserInfo->unique_id,
							'plan_id'				=>$getUserPlanGroup->plan_id ,
							'group_id'				=>$getUserPlanGroup->group_id ,
							'mt5_login_id'			=>$mt5TradingObject->answer->Login,
							'client'				=>'web',
							'mt5_response'			=>$createMt5IBaccount,
							'status'				=>1,
							'created_by'			=>$getUserInfo->id,
						);
				}
				$createIbAccount  = $this->IbModel->insertIbAccounts($dataItem);

				if($createIbAccount){
					$data						=array('ib_status'=>1);
					$lastUpdatedStatus			=$this->IbModel->changeIbStatus($data,$_REQUEST['unique_id']);
				}


				

				if ($lastUpdatedStatus) {

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Activated Ib Account Request | '.$_REQUEST['unique_id'].'');

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Create Ib Account');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_trading_account'] = 'Successfully Approved IB Request.';
						redirect('admin/ib-management/ib-user-request');
					}
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

	public function RejectedRequest(){
		$request	=self::isAuth(false);
		if($request['auth']==true) {

			$this->form_validation->set_rules('unique_id', 'User Unique id', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'unique_id'			=>strip_tags(form_error('unique_id')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_ib_request']	=json_encode($responseData,true);
					redirect(base_url() . 'admin/ib-management/ib-user-request');
				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				$data						=array('ib_status'=>3);
				$lastUpdatedStatus			=$this->IbModel->changeIbStatus($data,$_REQUEST['unique_id']);

				if ($lastUpdatedStatus) {

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Rejected Ib Account Request | '.$_REQUEST['unique_id'].'');

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully Create Ib Account');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_trading_account'] = 'Successfully Rejected IB Request.';
						redirect('admin/ib-management/ib-user-request');
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

	public function ibCommissionSetting()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web'){
				self::renderView('commission_setting','','','Commission Settings');
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

	public function viewCommissionGroup($groupID,$planID){
		$getComGroupData['ComGroupData'] = $this->IbModel->getIbCommissionGroupData($groupID,$planID);

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/view_commission_group',$getComGroupData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}
	
	public function viewCommissionPlan($planID){
		$getComGroupData['ComGroupData'] = $this->IbModel->getIbCommissionPlanData($planID);

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/view_commission_group',$getComGroupData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}
	/*public function viewCommissionGroupMaster($groupID,$planID,$masterIB){
		$getComGroupMasterData['ComGroupMasterData'] = $this->IbModel->getIbCommissionGroupMasterData($groupID,$planID,$masterIB);

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group Master';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/view_commission_group_master',$getComGroupMasterData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}*/
	
	public function viewCommissionGroupMaster($planID,$masterIB){
		$getComGroupMasterData['ComGroupMasterData'] = $this->IbModel->getIbCommissionGroupMasterData($planID,$masterIB);

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='View Commission Group Master';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/view_commission_group_master',$getComGroupMasterData);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function generateIBCommission_latest(){
		if (ConfigData['ib_currency']==true){




			$getInfo = $this->db->query("SELECT l.*,u.unique_id,a.id as ib_account_id,a.group_id,a.plan_id,ss.symbol_value from lot_informations l 
                inner join mt5_symbols s on s.symbol_name = l.symbol
                inner join symbol_shares ss on ss.symbol_id = s.id
            inner join trading_accounts t on t.id = l.trading_account_id and l.deal_generated_date >= t.created_at
            inner join users u on u.user_id = t.user_id 

            left join ib_accounts a on a.unique_id = u.unique_id  where ib_com_generated = 0 and entry_status = 1 LIMIT 2")->result();


			foreach ($getInfo as $getInfoValue) {
				$lot_generated_date = $getInfoValue->deal_generated_date;
				$UniqueID = $getInfoValue->unique_id;
				$groupID = $getInfoValue->group_id;
				$planID = $getInfoValue->plan_id;
				$symbolValue = $getInfoValue->symbol_value;
				$getUpline = $this->db->query("SELECT parent_id from users where unique_id = '$UniqueID' ")->row();

				$parentID = $getUpline->parent_id;

				$generateIBCommission = $this->db->query("SELECT *,uuu.ref_link_name as user_ref_link_name
                                                            FROM (
                                                                SELECT CASE 
                                                                        WHEN (
                                                                                SELECT ROLE
                                                                                FROM users
                                                                                WHERE unique_id = uli.upline_id
                                                                                ) = 0
                                                                            THEN uli.user_id
                                                                        ELSE uli.upline_id
                                                                        END AS Ibcommto
                                                                    ,uli.user_id AS trader
                                                                    ,ib.unique_id AS ibcommissionfrom
                                                                    ,uli.level_no
                                                                    ,value
                                                                    ,ib.plan_id
                                                                    ,ib.group_id
                                                                    ,ib.ref_link_name as ibcomm_ref_link_name
                                                                FROM `user_level_info` uli
                                                                INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
                                                                WHERE uli.user_id = '$UniqueID' and ib.unique_id = '$parentID' and uli.status = 1
                                                                ) T 
                                                            INNER JOIN ib_accounts ii ON ii.unique_id = T.ibcommto
                                                                AND T.plan_id = ii.plan_id
                                                                AND T.group_id = ii.group_id and level_no <> 1 and Ibcommto <> trader
                                                            INNER JOIN users uuu on uuu.unique_id = T.Ibcommto and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' ) 

                                                                ")->result();


				foreach ($generateIBCommission as $generateIBCommissionvalue) {

					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $generateIBCommissionvalue->trader,
						"ib_commission_from" => $generateIBCommissionvalue->ibcommissionfrom,
						"ibcommission_to" => $generateIBCommissionvalue->Ibcommto,
						"level" => $generateIBCommissionvalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateIBCommissionvalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue * 10)/100) *
								$generateIBCommissionvalue->value),
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s"),
						"user_ref_link_name" => $generateIBCommissionvalue->user_ref_link_name.'2',
						"ibcomm_ref_link_name" => $generateIBCommissionvalue->ibcomm_ref_link_name,
					);


					$c_lotID = $getInfoValue->id;
					$c_dealID = $getInfoValue->deal_id;
					$c_IbCommFrom = $generateIBCommissionvalue->ibcommissionfrom;
					$c_IbCommTo = $generateIBCommissionvalue->Ibcommto;
					$c_Trader = $generateIBCommissionvalue->trader;
					$c_Level = $generateIBCommissionvalue->level_no;

					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$c_Trader' and ibcommission_to = '$c_IbCommTo' and level = '$c_Level' AND (deal_id IS NULL OR deal_id ='$c_dealID')")->row();

					if($checkIfExists->cnt <= 0  && round((($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 2;
					}

				}


				/*start for direct upline*/
				$generateforupline = $this->db->query("SELECT iba.unique_id
				,g.group_name
				,g.id AS group_id
				,p.plan_name
				,p.plan_id
				,ibc.value
				,uli.level_no + 1 AS 'downline_level'
				,uli.level_no
				,ibc.unique_id AS ibcommissionfrom
				,ibc.ref_link_name as ibcomm_ref_link_name
				,u.ref_link_name as user_ref_link_name
			FROM `ib_accounts` iba
			INNER JOIN `groups` g ON g.id = iba.group_id
			INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
			INNER JOIN `ib_commission` ibc ON ibc.group_id = iba.group_id
				AND ibc.plan_id = iba.plan_id
			INNER JOIN `users` u ON ibc.unique_id = u.parent_id and u.ref_link_name = ibc.ref_link_name
			INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
			INNER JOIN users uuu ON uuu.unique_id = '$parentID'
				AND (
					uuu.ib_block_date IS NULL
					OR uuu.ib_block_date > '$lot_generated_date'
					)
			WHERE u.unique_id = '$parentID'
				AND uli.user_id = '$parentID'
				AND (
					SELECT ROLE
					FROM `users`WHERE unique_id = uli.upline_id
					) = 0
				AND uli.level_no = ibc.level_no
				AND uli.STATUS = 1;")->result();
				foreach($generateforupline as $generateforuplinevalue){


					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $UniqueID,
						"ib_commission_from" => $generateforuplinevalue->ibcommissionfrom,
						"ibcommission_to" => $parentID,
						"level" => 1,//$generateforuplinevalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateforuplinevalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue*10)/100) * $generateforuplinevalue->value),
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s"),
						"user_ref_link_name" => $generateIBCommissionvalue->user_ref_link_name.'1',
						"ibcomm_ref_link_name" => $generateIBCommissionvalue->ibcomm_ref_link_name,
					);

					$c_lotID = $getInfoValue->id;
					$c_IbCommFrom = $generateforuplinevalue->ibcommissionfrom;


					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$UniqueID' and ibcommission_to = '$parentID' and level = 1")->row();

					if($checkIfExists->cnt == 0 && round((($getInfoValue->volume / 10000) * $generateforuplinevalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 0;
					}
				}
				/*end for direct upline*/
				$IBstatusData = array("ib_com_generated" => 1);
				$this->db->set($IBstatusData);
				$this->db->where('id', $getInfoValue->id);
				$update = $this->db->update('lot_informations');
				echo "loaded1";


			}


		}else{


			$getInfo = $this->db->query("SELECT l.*,u.unique_id,a.id as ib_account_id,a.group_id,a.plan_id from lot_informations l 
            inner join trading_accounts t on t.id = l.trading_account_id and l.deal_generated_date >= t.created_at
            inner join users u on u.user_id = t.user_id 
            left join ib_accounts a on a.unique_id = u.unique_id  where ib_com_generated = 0 and entry_status = 1  and l.symbol like '%XAUUSD%' LIMIT 2")->result();


			foreach ($getInfo as $getInfoValue) {
				$lot_generated_date = $getInfoValue->deal_generated_date;
				$UniqueID = $getInfoValue->unique_id;
				$groupID = $getInfoValue->group_id;
				$planID = $getInfoValue->plan_id;
				$getUpline = $this->db->query("SELECT parent_id from users where unique_id = '$UniqueID' ")->row();

				$parentID = $getUpline->parent_id;

				$generateIBCommission = $this->db->query("SELECT *
                                                            FROM (
                                                                SELECT CASE 
                                                                        WHEN (
                                                                                SELECT ROLE
                                                                                FROM users
                                                                                WHERE unique_id = uli.upline_id
                                                                                ) = 0
                                                                            THEN uli.user_id
                                                                        ELSE uli.upline_id
                                                                        END AS Ibcommto
                                                                    ,uli.user_id AS trader
                                                                    ,ib.unique_id AS ibcommissionfrom
                                                                    ,uli.level_no
                                                                    ,value
                                                                    ,ib.plan_id
                                                                    ,ib.group_id
                                                                FROM `user_level_info` uli
                                                                INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
                                                                WHERE uli.user_id = '$UniqueID' and ib.unique_id = '$parentID' and uli.status = 1
                                                                ) T
                                                            INNER JOIN ib_accounts ii ON ii.unique_id = T.ibcommto
                                                                AND T.plan_id = ii.plan_id
                                                                AND T.group_id = ii.group_id and level_no <> 1 and Ibcommto <> trader
                                                            INNER JOIN users uuu on uuu.unique_id = T.Ibcommto and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' )

                                                                ")->result();


				foreach ($generateIBCommission as $generateIBCommissionvalue) {

					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $generateIBCommissionvalue->trader,
						"ib_commission_from" => $generateIBCommissionvalue->ibcommissionfrom,
						"ibcommission_to" => $generateIBCommissionvalue->Ibcommto,
						"level" => $generateIBCommissionvalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateIBCommissionvalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);


					$c_lotID = $getInfoValue->id;
					$c_dealID = $getInfoValue->deal_id;
					$c_IbCommFrom = $generateIBCommissionvalue->ibcommissionfrom;
					$c_IbCommTo = $generateIBCommissionvalue->Ibcommto;
					$c_Trader = $generateIBCommissionvalue->trader;
					$c_Level = $generateIBCommissionvalue->level_no;

					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$c_Trader' and ibcommission_to = '$c_IbCommTo' and level = '$c_Level' AND (deal_id IS NULL OR deal_id ='$c_dealID')")->row();

					if($checkIfExists->cnt <= 0  && round((($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}

				}


				/*start for direct upline*/
				$generateforupline = $this->db->query("SELECT iba.unique_id ,g.group_name ,g.id AS group_id ,p.plan_name ,p.plan_id ,ibc.value ,uli.level_no + 1 AS 'downline_level' ,uli.level_no,ibc.unique_id as ibcommissionfrom FROM `ib_accounts` iba INNER JOIN `groups` g ON g.id = iba.group_id INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id INNER JOIN `ib_commission` ibc ON ibc.group_id = iba.group_id AND ibc.plan_id = iba.plan_id INNER JOIN `users` u ON ibc.unique_id = u.parent_id INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id INNER JOIN users uuu on uuu.unique_id = '$parentID' and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' ) WHERE u.unique_id = '$parentID' AND uli.user_id = '$parentID' AND ( SELECT ROLE FROM `users`WHERE unique_id = uli.upline_id ) = 0 and uli.level_no = ibc.level_no and uli.status = 1;")->result();
				foreach($generateforupline as $generateforuplinevalue){


					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $UniqueID,
						"ib_commission_from" => $generateforuplinevalue->ibcommissionfrom,
						"ibcommission_to" => $parentID,
						"level" => 1,//$generateforuplinevalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateforuplinevalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) * $generateforuplinevalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);

					$c_lotID = $getInfoValue->id;
					$c_IbCommFrom = $generateforuplinevalue->ibcommissionfrom;


					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$UniqueID' and ibcommission_to = '$parentID' and level = 1")->row();

					if($checkIfExists->cnt == 0 && round((($getInfoValue->volume / 10000) * $generateforuplinevalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}
				}
				/*end for direct upline*/
				$IBstatusData = array("ib_com_generated" => 1);
				$this->db->set($IBstatusData);
				$this->db->where('id', $getInfoValue->id);
				$update = $this->db->update('lot_informations');
				echo "loaded2";


			}
		}

	}

	public function generateIBCommission_latest_comp(){
	
			$getInfo = $this->db->query("SELECT l.*
                                    	,u.unique_id
                                    	,a.id AS ib_account_id
                                    	,a.group_id
                                    	,a.plan_id
                                    	
                                    FROM lot_informations l
                                    
                                    INNER JOIN trading_accounts t ON t.id = l.trading_account_id
                                    	AND l.deal_generated_date >= t.created_at
                                    INNER JOIN users u ON u.user_id = t.user_id
                                    LEFT JOIN ib_accounts a ON a.unique_id = u.unique_id
                                    WHERE ib_com_generated = 0
                                    	AND entry_status = 1 LIMIT 2")->result();


			foreach ($getInfo as $getInfoValue) {
				$lot_generated_date = $getInfoValue->deal_generated_date;
				$UniqueID = $getInfoValue->unique_id;
				$groupID = $getInfoValue->group_id;
				$planID = $getInfoValue->plan_id;
				/*$symbolValue = $getInfoValue->symbol_value;*/
				$getUpline = $this->db->query("SELECT parent_id from users where unique_id = '$UniqueID' ")->row();

				$parentID = $getUpline->parent_id;

				$generateIBCommission = $this->db->query("SELECT *,uuu.ref_link_name as user_ref_link_name
                                                            FROM (
                                                                SELECT CASE 
                                                                        WHEN (
                                                                                SELECT ROLE
                                                                                FROM users
                                                                                WHERE unique_id = uli.upline_id
                                                                                ) = 0
                                                                            THEN uli.user_id
                                                                        ELSE uli.upline_id
                                                                        END AS Ibcommto
                                                                    ,uli.user_id AS trader
                                                                    ,ib.unique_id AS ibcommissionfrom
                                                                    ,uli.level_no
                                                                    ,value
                                                                    ,ib.plan_id
                                                                    ,ib.group_id
                                                                    ,ib.ref_link_name as ibcomm_ref_link_name
                                                                FROM `user_level_info` uli
                                                                INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
                                                                WHERE uli.user_id = '$UniqueID' and ib.unique_id = '$parentID' and uli.status = 1
                                                                ) T 
                                                            INNER JOIN ib_accounts ii ON ii.unique_id = T.ibcommto
                                                                AND T.plan_id = ii.plan_id
                                                                AND T.group_id = ii.group_id and level_no <> 1 and Ibcommto <> trader
                                                            INNER JOIN users uuu on uuu.unique_id = T.Ibcommto and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' ) 

                                                                ")->result();


				foreach ($generateIBCommission as $generateIBCommissionvalue) {

					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $generateIBCommissionvalue->trader,
						"ib_commission_from" => $generateIBCommissionvalue->ibcommissionfrom,
						"ibcommission_to" => $generateIBCommissionvalue->Ibcommto,
						"level" => $generateIBCommissionvalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateIBCommissionvalue->value,
						/*"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue * 10)/100) *
								$generateIBCommissionvalue->value),*/
						"calculated_commission" => $generateIBCommissionvalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s"),
						"user_ref_link_name" => $generateIBCommissionvalue->user_ref_link_name.'2',
						"ibcomm_ref_link_name" => $generateIBCommissionvalue->ibcomm_ref_link_name,
					);


					$c_lotID = $getInfoValue->id;
					$c_dealID = $getInfoValue->deal_id;
					$c_IbCommFrom = $generateIBCommissionvalue->ibcommissionfrom;
					$c_IbCommTo = $generateIBCommissionvalue->Ibcommto;
					$c_Trader = $generateIBCommissionvalue->trader;
					$c_Level = $generateIBCommissionvalue->level_no;

					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$c_Trader' and ibcommission_to = '$c_IbCommTo' and level = '$c_Level' AND (deal_id IS NULL OR deal_id ='$c_dealID')")->row();

					if($checkIfExists->cnt <= 0  && round(($generateIBCommissionvalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 2;
					}

				}


				/*start for direct upline*/
				$generateforupline = $this->db->query("SELECT iba.unique_id
				,g.group_name
				,g.id AS group_id
				,p.plan_name
				,p.plan_id
				,ibc.value
				,uli.level_no + 1 AS 'downline_level'
				,uli.level_no
				,ibc.unique_id AS ibcommissionfrom
				,ibc.ref_link_name as ibcomm_ref_link_name
				,u.ref_link_name as user_ref_link_name
			FROM `ib_accounts` iba
			INNER JOIN `groups` g ON g.id = iba.group_id
			INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
			INNER JOIN `ib_commission` ibc ON ibc.group_id = iba.group_id
				AND ibc.plan_id = iba.plan_id
			INNER JOIN `users` u ON ibc.unique_id = u.parent_id and u.ref_link_name = ibc.ref_link_name
			INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
			INNER JOIN users uuu ON uuu.unique_id = '$parentID'
				AND (
					uuu.ib_block_date IS NULL
					OR uuu.ib_block_date > '$lot_generated_date'
					)
			WHERE u.unique_id = '$parentID'
				AND uli.user_id = '$parentID'
				AND (
					SELECT ROLE
					FROM `users`WHERE unique_id = uli.upline_id
					) = 0
				AND uli.level_no = ibc.level_no
				AND uli.STATUS = 1;")->result();
				foreach($generateforupline as $generateforuplinevalue){


					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $UniqueID,
						"ib_commission_from" => $generateforuplinevalue->ibcommissionfrom,
						"ibcommission_to" => $parentID,
						"level" => 1,//$generateforuplinevalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateforuplinevalue->value,
						/*"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue*10)/100) * $generateforuplinevalue->value),*/
						"calculated_commission" => $generateforuplinevalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s"),
						"user_ref_link_name" => $generateIBCommissionvalue->user_ref_link_name.'1',
						"ibcomm_ref_link_name" => $generateIBCommissionvalue->ibcomm_ref_link_name,
					);

					$c_lotID = $getInfoValue->id;
					$c_IbCommFrom = $generateforuplinevalue->ibcommissionfrom;


					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$UniqueID' and ibcommission_to = '$parentID' and level = 1")->row();

					if($checkIfExists->cnt == 0 && round(($generateforuplinevalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 0;
					}
				}
				/*end for direct upline*/
				$IBstatusData = array("ib_com_generated" => 1);
				$this->db->set($IBstatusData);
				$this->db->where('id', $getInfoValue->id);
				$update = $this->db->update('lot_informations');
				echo "loaded1";


			}


	

	}

	public function generateIBCommissionRef(){


		if (ConfigData['ib_currency']==true){




			$getInfo = $this->db->query("SELECT l.*,u.unique_id,a.id as ib_account_id,a.group_id,a.plan_id,ss.symbol_value from lot_informations l 
                inner join mt5_symbols s on s.symbol_name = l.symbol
                inner join symbol_shares ss on ss.symbol_id = s.id
            inner join trading_accounts t on t.id = l.trading_account_id and l.deal_generated_date >= t.created_at
            inner join users u on u.user_id = t.user_id 

            left join ib_accounts a on a.unique_id = u.unique_id  where ib_com_generated = 0 and entry_status = 1 LIMIT 2")->result();


			foreach ($getInfo as $getInfoValue) {
				$lot_generated_date = $getInfoValue->deal_generated_date;
				$UniqueID = $getInfoValue->unique_id;
				$groupID = $getInfoValue->group_id;
				$planID = $getInfoValue->plan_id;
				$symbolValue = $getInfoValue->symbol_value;
				$getUpline = $this->db->query("SELECT parent_id from users where unique_id = '$UniqueID' ")->row();

				$parentID = $getUpline->parent_id;

				$generateIBCommission = $this->db->query("SELECT *
                                                            FROM (
                                                                SELECT CASE 
                                                                        WHEN (
                                                                                SELECT ROLE
                                                                                FROM users
                                                                                WHERE unique_id = uli.upline_id
                                                                                ) = 0
                                                                            THEN uli.user_id
                                                                        ELSE uli.upline_id
                                                                        END AS Ibcommto
                                                                    ,uli.user_id AS trader
                                                                    ,ib.unique_id AS ibcommissionfrom
                                                                    ,uli.level_no
                                                                    ,value
                                                                    ,ib.plan_id
                                                                    ,ib.group_id
                                                                    ,ib.ref_link_name
                                                                FROM `user_level_info` uli
                                                                INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
                                                                WHERE uli.user_id = '$UniqueID' and ib.unique_id = '$parentID' and uli.status = 1
                                                                ) T
                                                            INNER JOIN ib_accounts ii ON ii.unique_id = T.ibcommto
                                                                AND T.plan_id = ii.plan_id
                                                                AND T.group_id = ii.group_id and level_no <> 1 and Ibcommto <> trader
                                                            INNER JOIN users uuu on uuu.unique_id = T.Ibcommto and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' ) 

                                                                ")->result();


				foreach ($generateIBCommission as $generateIBCommissionvalue) {

					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $generateIBCommissionvalue->trader,
						"ib_commission_from" => $generateIBCommissionvalue->ibcommissionfrom,
						"ibcommission_to" => $generateIBCommissionvalue->Ibcommto,
						"level" => $generateIBCommissionvalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateIBCommissionvalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue * 10)/100) *
								$generateIBCommissionvalue->value),
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);


					$c_lotID = $getInfoValue->id;
					$c_dealID = $getInfoValue->deal_id;
					$c_IbCommFrom = $generateIBCommissionvalue->ibcommissionfrom;
					$c_IbCommTo = $generateIBCommissionvalue->Ibcommto;
					$c_Trader = $generateIBCommissionvalue->trader;
					$c_Level = $generateIBCommissionvalue->level_no;

					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$c_Trader' and ibcommission_to = '$c_IbCommTo' and level = '$c_Level' AND (deal_id IS NULL OR deal_id ='$c_dealID')")->row();

					if($checkIfExists->cnt <= 0  && round((($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 2;
					}

				}


				/*start for direct upline*/
				$generateforupline = $this->db->query("SELECT iba.unique_id
											,g.group_name
											,g.id AS group_id
											,p.plan_name
											,p.plan_id
											,ibc.value
											,uli.level_no + 1 AS 'downline_level'
											,uli.level_no
											,ibc.unique_id AS ibcommissionfrom
										FROM `ib_accounts` iba
										INNER JOIN `groups` g ON g.id = iba.group_id
										INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
										INNER JOIN `ib_commission` ibc ON ibc.group_id = iba.group_id
											AND ibc.plan_id = iba.plan_id
										INNER JOIN `users` u ON ibc.unique_id = u.parent_id and u.ref_link_name = ibc.ref_link_name
										INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
										INNER JOIN users uuu ON uuu.unique_id = '$parentID'
											AND (
												uuu.ib_block_date IS NULL
												OR uuu.ib_block_date > '$lot_generated_date'
												)
										WHERE u.unique_id = '$parentID'
											AND uli.user_id = '$parentID'
											AND (
												SELECT ROLE
												FROM `users`WHERE unique_id = uli.upline_id
												) = 0
											AND uli.level_no = ibc.level_no
											AND uli.STATUS = 1;")->result();
				foreach($generateforupline as $generateforuplinevalue){


					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $UniqueID,
						"ib_commission_from" => $generateforuplinevalue->ibcommissionfrom,
						"ibcommission_to" => $parentID,
						"level" => 1,//$generateforuplinevalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateforuplinevalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) *
							((($symbolValue*10)/100) * $generateforuplinevalue->value),
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);

					$c_lotID = $getInfoValue->id;
					$c_IbCommFrom = $generateforuplinevalue->ibcommissionfrom;


					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$UniqueID' and ibcommission_to = '$parentID' and level = 1")->row();

					if($checkIfExists->cnt == 0 && round((($getInfoValue->volume / 10000) * $generateforuplinevalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}else{
						echo 0;
					}
				}
				/*end for direct upline*/
				$IBstatusData = array("ib_com_generated" => 1);
				$this->db->set($IBstatusData);
				$this->db->where('id', $getInfoValue->id);
				$update = $this->db->update('lot_informations');
				echo "loaded1";


			}


		}else{


			$getInfo = $this->db->query("SELECT l.*,u.unique_id,a.id as ib_account_id,a.group_id,a.plan_id from lot_informations l 
            inner join trading_accounts t on t.id = l.trading_account_id and l.deal_generated_date >= t.created_at
            inner join users u on u.user_id = t.user_id 
            left join ib_accounts a on a.unique_id = u.unique_id  where ib_com_generated = 0 and entry_status = 1  and l.symbol like '%XAUUSD%' LIMIT 2")->result();


			foreach ($getInfo as $getInfoValue) {
				$lot_generated_date = $getInfoValue->deal_generated_date;
				$UniqueID = $getInfoValue->unique_id;
				$groupID = $getInfoValue->group_id;
				$planID = $getInfoValue->plan_id;
				$getUpline = $this->db->query("SELECT parent_id from users where unique_id = '$UniqueID' ")->row();

				$parentID = $getUpline->parent_id;

				$generateIBCommission = $this->db->query("SELECT *
                                                            FROM (
                                                                SELECT CASE 
                                                                        WHEN (
                                                                                SELECT ROLE
                                                                                FROM users
                                                                                WHERE unique_id = uli.upline_id
                                                                                ) = 0
                                                                            THEN uli.user_id
                                                                        ELSE uli.upline_id
                                                                        END AS Ibcommto
                                                                    ,uli.user_id AS trader
                                                                    ,ib.unique_id AS ibcommissionfrom
                                                                    ,uli.level_no
                                                                    ,value
                                                                    ,ib.plan_id
                                                                    ,ib.group_id
                                                                    ,ib.ref_link_name
                                                                FROM `user_level_info` uli
                                                                INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
                                                                WHERE uli.user_id = '$UniqueID' and ib.unique_id = '$parentID' and uli.status = 1
                                                                ) T
                                                            INNER JOIN ib_accounts ii ON ii.unique_id = T.ibcommto
                                                                AND T.plan_id = ii.plan_id
                                                                AND T.group_id = ii.group_id and level_no <> 1 and Ibcommto <> trader
                                                            INNER JOIN users uuu on uuu.unique_id = T.Ibcommto and (uuu.ib_block_date IS NULL or uuu.ib_block_date >'$lot_generated_date' )

                                                                ")->result();


				foreach ($generateIBCommission as $generateIBCommissionvalue) {

					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $generateIBCommissionvalue->trader,
						"ib_commission_from" => $generateIBCommissionvalue->ibcommissionfrom,
						"ibcommission_to" => $generateIBCommissionvalue->Ibcommto,
						"level" => $generateIBCommissionvalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateIBCommissionvalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);


					$c_lotID = $getInfoValue->id;
					$c_dealID = $getInfoValue->deal_id;
					$c_IbCommFrom = $generateIBCommissionvalue->ibcommissionfrom;
					$c_IbCommTo = $generateIBCommissionvalue->Ibcommto;
					$c_Trader = $generateIBCommissionvalue->trader;
					$c_Level = $generateIBCommissionvalue->level_no;

					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$c_Trader' and ibcommission_to = '$c_IbCommTo' and level = '$c_Level' AND (deal_id IS NULL OR deal_id ='$c_dealID')")->row();

					if($checkIfExists->cnt <= 0  && round((($getInfoValue->volume / 10000) * $generateIBCommissionvalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}

				}


				/*start for direct upline*/
				$generateforupline = $this->db->query("SELECT iba.unique_id
						,g.group_name
						,g.id AS group_id
						,p.plan_name
						,p.plan_id
						,ibc.value
						,uli.level_no + 1 AS 'downline_level'
						,uli.level_no
						,ibc.unique_id AS ibcommissionfrom
					FROM `ib_accounts` iba
					INNER JOIN `groups` g ON g.id = iba.group_id
					INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
					INNER JOIN `ib_commission` ibc ON ibc.group_id = iba.group_id
						AND ibc.plan_id = iba.plan_id
					INNER JOIN `users` u ON ibc.unique_id = u.parent_id and u.ref_link_name = ibc.ref_link_name
					INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
					INNER JOIN users uuu ON uuu.unique_id = '$parentID'
						AND (
							uuu.ib_block_date IS NULL
							OR uuu.ib_block_date > '$lot_generated_date'
							)
					WHERE u.unique_id = '$parentID'
						AND uli.user_id = '$parentID'
						AND (
							SELECT ROLE
							FROM `users`WHERE unique_id = uli.upline_id
							) = 0
						AND uli.level_no = ibc.level_no
						AND uli.STATUS = 1;")->result();
				foreach($generateforupline as $generateforuplinevalue){


					$data = array("lot_id" => $getInfoValue->id,
						"trader" => $UniqueID,
						"ib_commission_from" => $generateforuplinevalue->ibcommissionfrom,
						"ibcommission_to" => $parentID,
						"level" => 1,//$generateforuplinevalue->level_no,
						"ib_account_id" => $getInfoValue->ib_account_id,
						"lot" => ($getInfoValue->volume / 10000),//($getInfoValue->contract_size/$getInfoValue->volume),
						"commission_share" => $generateforuplinevalue->value,
						"calculated_commission" => ($getInfoValue->volume / 10000) * $generateforuplinevalue->value,
						"created_by" => '1',
						"created_datetime" => date("Y-m-d H:i:s")
					);

					$c_lotID = $getInfoValue->id;
					$c_IbCommFrom = $generateforuplinevalue->ibcommissionfrom;


					$checkIfExists = $this->db->query("select count(1) as cnt from ib_calculation where lot_id = '$c_lotID' and ib_commission_from = '$c_IbCommFrom' and  trader = '$UniqueID' and ibcommission_to = '$parentID' and level = 1")->row();

					if($checkIfExists->cnt == 0 && round((($getInfoValue->volume / 10000) * $generateforuplinevalue->value), 2) > 0){
						$this->db->insert('ib_calculation', $data);
					}
				}
				/*end for direct upline*/
				$IBstatusData = array("ib_com_generated" => 1);
				$this->db->set($IBstatusData);
				$this->db->where('id', $getInfoValue->id);
				$update = $this->db->update('lot_informations');
				echo "loaded2";


			}
		}

	}

	public function IbCommissionServerUpdate_latest()
	{
		/*Start Update Ib Commission in MT5*/
		$updatemt5 = $this->db->query("SELECT calc_id,calculated_commission as ib_commission, ibcommission_to,iba.mt5_login_id,l.deal_id,ibc.trader FROM `ib_calculation` ibc inner join ib_accounts iba on iba.unique_id = ibc.ibcommission_to
                    INNER join lot_informations l on l.id = ibc.lot_id
         WHERE ibc.mt5_update = 0 LIMIT 10;")->result();
		foreach($updatemt5 as $updatemt5value){
			$trader = $updatemt5value->trader;
			$gettrader = $this->db->query("SELECT t.mt5_login_id  from users u inner join trading_accounts t on t.user_id = u.user_id and u.unique_id = '$trader';")->row();

			$requestedData=array('remark'=>'AC '.$updatemt5value->deal_id.' - '.$gettrader->mt5_login_id,'enterAmount'=>$updatemt5value->ib_commission,'mt5_login_id'=>$updatemt5value->mt5_login_id);
			$response = $this->mt5_instance->depositAmount($requestedData);

			if($response == true){
				$this->db->query("UPDATE ib_calculation SET mt5_update = 1 WHERE calc_id = $updatemt5value->calc_id");
				print_r($requestedData);
			}


		}
		/*End Update Ib Commission in MT5*/
	}

	public function ibCommissionGroup()
	{

		$request	=self::isAuth();
		if($request['auth']==true) {
			$getComGroup['ComGroup'] = $this->IbModel->getIbCommissionGroup();
			if($request['type'] == 'web'){
				self::renderView('commission_group',$getComGroup,'','Ib Commission Group');
			}else if($request['type'] == 'api'){
				self::response(200,$getComGroup);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}


		public function saveIbCommissionGroupAdmin(){
		if($this->session->userdata('role') ==0){
			$level=1;
		}else{
			$level = $this->input->post('downline_level');
		}
		$UserUniqueID = $this->session->userdata('unique_id');

		$ibCommissionGroupData = array("plan_id" => $this->input->post('plan_id'),
			"group_id" => $this->input->post('group_id'),
			"plan_id" => $this->input->post('plan_id'),
			"user_id" => $this->session->userdata('user_id'),
			"unique_id" => $this->session->userdata('unique_id'),
			"level_no" => $level,
			"flat_percentage" => 1,
			"value" => $this->input->post('downline_share'),
			"created_by" => $this->session->userdata('user_id'),
			"status" => 1,
			"created_datetime" => 	date("Y-m-d H:i:s"));
		$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,0);


		for($i=1;$i<$level;$i++){

			if($i == 1){ //self


				$ibCommissionGroupData = array("plan_id" => $this->input->post('plan_id'),
					"group_id" => $this->input->post('group_id'),
					"plan_id" => $this->input->post('plan_id'),
					"user_id" => $this->session->userdata('user_id'),
					"unique_id" => $this->session->userdata('unique_id'),
					"level_no" => $i,
					"flat_percentage" => 1,
					"value" => $this->input->post('sharefromupline') - $this->input->post('downline_share'),
					"created_by" => $this->session->userdata('user_id'),
					"status" => 1,
					"created_datetime" => 	date("Y-m-d H:i:s"));
				$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,0);
			}else{

				$groupID = $this->input->post('group_id');
				$planID =  $this->input->post('plan_id');

				$getfromUpline = $this->db->query("SELECT * FROM `ib_commission` ibc inner join users u on u.parent_id = ibc.unique_id where u.unique_id = '$UserUniqueID'
 					and group_id = '$groupID' and plan_id = '$planID'  and  $i = (level_no+1);
								")->result();

				foreach($getfromUpline as $getfromUplinevalue){
					$ibCommissionGroupData = array("plan_id" => $this->input->post('plan_id'),
						"group_id" => $this->input->post('group_id'),
						"plan_id" => $this->input->post('plan_id'),
						"user_id" => $this->session->userdata('user_id'),
						"unique_id" => $this->session->userdata('unique_id'),
						"level_no" => $i,
						"flat_percentage" => 1,
						"value" => $getfromUplinevalue->value,
						"created_by" => $this->session->userdata('user_id'),
						"status" => 1,
						"created_datetime" => 	date("Y-m-d H:i:s"));

					$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,0);


				}


			}
		}




		if($addCommissionGroup){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Commission Group Added');

			$this->session->set_flashdata('msg', 'Commission group added successfully.'); //set success msg if

			if($this->session->userdata('role') ==0){
				redirect(base_url().'admin/ib-management/commission-group');
			}else{
				redirect(base_url().'user-ib-commission-group');
			}
		}else{
			$this->session->set_flashdata('msg', 'Failed to add. Group and plan already exists'); //set success msg if
			if($this->session->userdata('role') ==0){
				redirect(base_url().'admin/ib-management/edit-commission-group');
			}else{
				redirect(base_url().'user-ib-commission-group');
			}

		}
	}

public function saveIbCommissionGroup(){
/*print_r($_REQUEST);
exit;*/
     
        $getData = $this->IbModel->getUserIbCommissionGroup();
/*print_r($getUserIbCommissionGroup);
exit;*/
		$getHeader	=$this->input->request_headers();	
			if (isset($getHeader['type']) && $getHeader['type'] === 'api'){
			$request= 'api';
			$UserUniqueID = $getHeader['uid'];
			}else{
				$request = 'web';
				$UserUniqueID = $this->session->userdata('unique_id');
			}

			$getUserID = $this->db->query("SELECT user_id,role FROM `users` where unique_id = '$UserUniqueID'")->row();
			$UserID = $getUserID->user_id;
			$role = $getUserID->role;
	
		/*$ref_link_count = $_REQUEST['ref_link_count'];
		$groupID = $_REQUEST['group_id'];*/
		$planID =  $_REQUEST['plan_id_1'];
		
		
		
		$refLinkCount = $_REQUEST['ref_link_count'];
		
		

	/*	$checkcnt = $this->db->query("SELECT max(ref_link_name) as cnt FROM `ib_commission` where plan_id = '$planID'  and group_id='$groupID' and unique_id = '$UserUniqueID'")->row();
*/
        $checkcnt = $this->db->query("SELECT max(ref_link_name) as cnt FROM `ib_commission` where plan_id = '$planID' and unique_id = '$UserUniqueID'")->row();

		
		if($checkcnt->cnt > 0){
			$maxentry = $checkcnt->cnt + $refLinkCount;
			$c_entry  = $checkcnt->cnt + 1;
		}else{
			$c_entry = 1;
			$maxentry = $refLinkCount;
		}

		$dncnt = 0;
		for($entry=$c_entry;$entry<=$maxentry;$entry++){
		$dncnt++;

		if($role ==0){
			$level=1;
		}else{
			$level = $_REQUEST['downline_level'];
		}
		
		foreach($getData as $datagetUserIbCommissionGroup){
		    $ibCommissionGroupData = array(
			"group_id" => $_REQUEST['group_id_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id],
			"plan_id" => $_REQUEST['plan_id_'.$dncnt],
			"user_id" => $UserID,
			"plan_name" => $_REQUEST['p_name_'.$dncnt],
			"unique_id" => $UserUniqueID,
			"level_no" => $level,
			"ref_link_name" => $entry,
			"flat_percentage" => 1,
			"value" => $_REQUEST['level_share_value_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id],
			"created_by" => $UserID,
			"status" => 1,
			"created_datetime" => 	date("Y-m-d H:i:s")
			);

		$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,$role);
		}
		
		for($i=1;$i<$level;$i++){
        foreach($getData as $datagetUserIbCommissionGroup){
            if($i == 1){ //self

            
				$ibCommissionGroupData = array(
					"group_id" =>  $_REQUEST['group_id_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id],
					"plan_id" => $_REQUEST['plan_id_'.$dncnt],
					"user_id" => $UserID,
					"plan_name" => $_REQUEST['p_name_'.$dncnt],
					"unique_id" => $UserUniqueID,
					"level_no" => $i,
					"ref_link_name" => $entry,
					"flat_percentage" => 1,
					"value" => $_REQUEST['sharefromupline_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id] -  $_REQUEST['level_share_value_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id],
					"created_by" => $UserID,
					"status" => 1,
					"created_datetime" => 	date("Y-m-d H:i:s"));
				$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,$role);
			}else{

				$groupID = $_REQUEST['group_id_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id];
				$planID =  $_REQUEST['plan_id_'.$dncnt];

				$getfromUpline = $this->db->query("SELECT * FROM `ib_commission` ibc inner join users u on u.parent_id = ibc.unique_id where u.unique_id = '$UserUniqueID'
 					and group_id = '$groupID' and plan_id = '$planID'  and  $i = (level_no+1) and ibc.ref_link_name = u.ref_link_name;
								")->result();

				foreach($getfromUpline as $getfromUplinevalue){
					$ibCommissionGroupData = array(
						"group_id" => $_REQUEST['group_id_'.$dncnt.'_'.$datagetUserIbCommissionGroup->group_id],
			            "plan_id" => $_REQUEST['plan_id_'.$dncnt],
						"user_id" => $UserID,
						"plan_name" => $_REQUEST['p_name_'.$dncnt],
						"unique_id" => $UserUniqueID,
						"level_no" => $i,
						"ref_link_name" => $entry,
						"flat_percentage" => 1,
						"value" => $getfromUplinevalue->value,
						"created_by" => $UserID,
						"status" => 1,
						"created_datetime" => 	date("Y-m-d H:i:s"));

					$addCommissionGroup= $this->IbModel->insertCommissionGroup($ibCommissionGroupData,$role);


				}


			}
        }
			
		}


		}


		



		if($addCommissionGroup){
			if($request == 'api'){
					$responseData = array(
					'status' 	=> 200,
					'data' 		=> "success",
					'uid' =>  $UserUniqueID
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Commission Group Added');

					$this->session->set_flashdata('msg', 'Commission group added successfully.'); //set success msg if

					if($role ==0){
						redirect(base_url().'admin/ib-management/commission-group');
					}else{
						redirect(base_url().'user-ib-commission-group');
					}
				}

			
		}else{

				if($request == 'api'){
					$responseData = array(
					'status' 	=> 400,
					'data' 		=> "failure",
					'uid' =>  $UserUniqueID
					);

					$res = json_encode($responseData,true);
					print_r($res);
					exit();
				}else if($request == 'web'){
					$this->session->set_flashdata('msg', 'Failed to add. Group and plan already exists'); //set success msg if
					if($role ==0){
						redirect(base_url().'admin/ib-management/edit-commission-group');
					}else{
						redirect(base_url().'user-ib-commission-group');
					}
				}

			

		}
	}
	
	
	public function reflinknames(){

		$uniqueID = $this->input->post('unique_id');

		$getRefLinkNames = $this->db->query("SELECT * FROM `ib_commission` ib where unique_id = '$uniqueID' and level_no = (select max(level_no) from `ib_commission` ib where unique_id = '$uniqueID' );")->result();

		$dataItem=array(
				'getReferralLink'			=>$getRefLinkNames
			);

		print_r(json_encode($dataItem));
			exit();

	}

	public function saveIbCommissionRef(){

		$groupID = $this->input->post('group_id_1');
		$planID =  $this->input->post('plan_id_1');

		$checkcnt = $this->db->query("SELECT max(ref_link_name) as cnt FROM `ib_commission_ref` where plan_id = '$planID'  and group_id='$groupID'")->row();

		for($entry=1;$entry<=$checkcnt->cnt;$entry++){
			echo $entry;
			
		
		if($this->session->userdata('role') ==0){
			$level=1;
		}else{
			$level = $this->input->post('downline_level_'.$entry);
		}

		$UserUniqueID = $this->session->userdata('unique_id');

		$ibCommissionRefData = array("plan_id" => $this->input->post('plan_id_'.$entry),
			"group_id" => $this->input->post('group_id_'.$entry),
			"plan_id" => $this->input->post('plan_id_'.$entry),
			"ref_link_name" => $this->input->post('ref_link_name_'.$entry),
			"user_id" => $this->session->userdata('user_id'),
			"unique_id" => $this->session->userdata('unique_id'),
			"level_no" => $level,
			"flat_percentage" => 1,
			"value" => $this->input->post('downline_share_'.$entry),
			"created_by" => $this->session->userdata('user_id'),
			"status" => 1,
			"created_datetime" => 	date("Y-m-d H:i:s"));
		$addCommissionRef= $this->IbModel->insertCommissionRef($ibCommissionRefData);


		for($i=1;$i<$level;$i++){

			if($i == 1){ //self


				$ibCommissionRefData = array("plan_id" => $this->input->post('plan_id_'.$entry),
					"group_id" => $this->input->post('group_id_'.$entry),
					"plan_id" => $this->input->post('plan_id_'.$entry),
					"ref_link_name" => $this->input->post('ref_link_name_'.$entry),
					"user_id" => $this->session->userdata('user_id'),
					"unique_id" => $this->session->userdata('unique_id'),
					"level_no" => $i,
					"flat_percentage" => 1,
					"value" => $this->input->post('sharefromupline_'.$entry) - $this->input->post('downline_share_'.$entry),
					"created_by" => $this->session->userdata('user_id'),
					"status" => 1,
					"created_datetime" => 	date("Y-m-d H:i:s"));
				$addCommissionRef= $this->IbModel->insertCommissionRef($ibCommissionRefData);
			}else{

				

				$getfromUpline = $this->db->query("SELECT * FROM `ib_commission` ibc inner join users u on u.parent_id = ibc.unique_id where u.unique_id = '$UserUniqueID'
 					and group_id = '$groupID' and plan_id = '$planID'  and  $i = (level_no+1);
								")->result();

				


				foreach($getfromUpline as $getfromUplinevalue){
					$ibCommissionRefData = array("plan_id" => $this->input->post('plan_id_'.$entry),
						"group_id" => $this->input->post('group_id_'.$entry),
						"plan_id" => $this->input->post('plan_id_'.$entry),
						"ref_link_name" => $this->input->post('ref_link_name_'.$entry),
						"user_id" => $this->session->userdata('user_id'),
						"unique_id" => $this->session->userdata('unique_id'),
						"level_no" => $i,
						"flat_percentage" => 1,
						"value" => $getfromUplinevalue->value,
						"created_by" => $this->session->userdata('user_id'),
						"status" => 1,
						"created_datetime" => 	date("Y-m-d H:i:s"));

					$addCommissionRef= $this->IbModel->insertCommissionRef($ibCommissionRefData);


				}

				
			}
		}

	}


		if($addCommissionGroup){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Commission Ref Group Added');

			$this->session->set_flashdata('msg', 'Commission Ref group added successfully.'); //set success msg if

			if($this->session->userdata('role') ==0){
				redirect(base_url().'admin/ib-management/commission-ref');
			}else{
				exit;
				redirect(base_url().'user-ib-commission-ref');
			}
		}else{
			$this->session->set_flashdata('msg', 'Failed to add. Group and plan already exists'); //set success msg if
			if($this->session->userdata('role') ==0){
				redirect(base_url().'admin/ib-management/edit-commission-ref');
			}else{
				redirect(base_url().'user-ib-commission-ref');
			}

		}
	
}

	public function ibEditCommissionGroup()
	{
		$data['ibplanlist'] = $this->IbModel->getIbList();
		$data['grouplist'] = $this->IbModel->getGroupList();

		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Edit Commission';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/edit_commission_group',$data);
			$this->load->view('includes/footer');
		}else{

			redirect(base_url() . 'login');

		}
	}

	public function ibPlan()
	{
		$request	=self::isAuth();
		if($request['auth']==true) {
			$getIbPlan['iblist'] = $this->IbModel->getIbList();
			if($request['type'] == 'web'){
				self::renderView('ib_plan',$getIbPlan,'','Ib Plan');
			}else if($request['type'] == 'api'){
				self::response(200,$getIbPlan);
			}
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}
	public function ibPlanEdit()
	{
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Plan Edit';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/ib_plan_edit');
			$this->load->view('includes/footer');
		}else{

			redirect(base_url() . 'login');

		}
	}
	public function ibSaveData()
	{
		$IBPlanData = array("plan_name" => $this->input->post('ibplan'),
			"created_by" => $this->session->userdata('user_id'),
			"status" => 1,
			"created_datetime" => 	date("Y-m-d H:i:s")
		) ;
		$addIBplan= $this->IbModel->insertIBPlan($IBPlanData);
		if($addIBplan){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Create Ib Plan');

			$this->session->set_flashdata('msg', 'IB Plan added successfully.'); //set success msg if
			redirect(base_url().'admin/ib-management/ib-plan');
		}else{
			$this->session->set_flashdata('msg', 'Failed to add.'); //set success msg if
			redirect(base_url().'admin/ib-management/edit-ib-plan');
		}

	}

	public function add_symbol_value()
	{
		$request    =self::isAuth();



		if(($this->input->post('symbol_id') <> NULL) && ($this->input->post('symbol_value') <> NULL)){

			$data = array("symbol_id" => $this->input->post('symbol_id'),
				"symbol_value" => $this->input->post('symbol_value'),
				"created_by" => '1',
				"created_datetime" => date("Y-m-d H:i:s"));

			$symbolId = $this->input->post('symbol_id');

			$check = $this->db->query("SELECT * FROM `symbol_shares` where symbol_id = '$symbolId' ;")->result();

			if(count($check) <= 0){

				$this->load->model('ActivityLogModel');
				$this->ActivityLogModel->createActiviyt('Create Symbol | '.$this->input->post('symbol_value').'');

				$this->IbModel->addSymbolValue($data);
				$this->session->set_flashdata('msg','Successfully Added Value');
			}else{
				$this->session->set_flashdata('msg','Its Duplicate Entry, Cannot Add');
			}

		}

		$getData['symbols'] = $this->IbModel->getSymbolList();
		$getData['symbolShareList'] = $this->IbModel->getSymbolShareList();

		if($request['auth']==true) {
			if($request['type'] == 'web'){
				$title['title'] ='Symbol Share';
				$this->load->view('includes/header',$title);
				$this->load->view('includes/left_side_bar');
				$this->load->view('admin/ib_management/symbol_share',$getData);
				$this->load->view('includes/footer');

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


	public function assignIB()
	{
		$request	=self::isAuth();

		if($this->input->post('normal_user') <> $this->input->post('ib_user')){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Change Parent Ib From : | '.$this->input->post('normal_user').' | To: '.$this->input->post('ib_user').'');

			$this->IbModel->changeIB($this->input->post('normal_user'),$this->input->post('ib_user'));
			$this->session->set_flashdata('msg','Successfully Changed IB');
		}else{
			$this->session->set_flashdata('msg','Client and IB cannot be same');
		}
		$getData['ibuserlist'] = $this->IbModel->getIBuserList();
		$getData['userlist'] = $this->IbModel->getUserList();

		if($request['auth']==true) {
			if($request['type'] == 'web'){

				$title['title']	='Assign IB';
				$this->load->view('includes/header',$title);
				$this->load->view('includes/left_side_bar');
				$this->load->view('admin/ib_management/assign_ib',$getData);
				$this->load->view('includes/footer');

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

	public function removeIB()
	{

		if($this->input->post('unique_id') ){

			$this->load->model('ActivityLogModel');
			$this->ActivityLogModel->createActiviyt('Remove Ib | '.$this->input->post('unique_id').'');

			$this->IbModel->removeIB($this->input->post('unique_id'));
			$this->session->set_flashdata('msg','Successfully Blocked IB');
		}
		$data['ibblockeduserlist'] = $this->IbModel->getIBBlockeduserList();
		$data['ibuserlist'] = $this->IbModel->getIbUserList();
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Remove IB';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/remove_ib',$data);
			$this->load->view('includes/footer');
		}else{
			redirect(base_url() . 'login');

		}
	}


	public function ibClient($Ibto)
	{
		$where = " and 1 = 1 ";
		if($this->input->post('search')){
			$levelNo = $this->input->post('level_no');
			$username = $this->input->post('username');

			if($this->input->post('level_no'))
				$where .= " and level_no = $levelNo ";

			if($this->input->post('username'))
				$where .= " and CONCAT (u.first_name,' ',u.last_name) LIKE '%$username%'" ;

		}
		$getIbClient['IbClient'] = $this->IbModel->getIbClientList($Ibto,$limit=0,$where);
		$getIbClient['IbClientLevel'] = $this->IbModel->getIbClientLevelList($Ibto);
		if($this->session->userdata('username') != '' && ($this->session->userdata('role') ==0)) {
			$title['title']	='Ib Client List';
			$this->load->view('includes/header',$title);
			$this->load->view('includes/left_side_bar');
			$this->load->view('admin/ib_management/ib_client_list',$getIbClient);
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
		$this->load->view('admin/ib_management/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
	
	public function resendIbEmail($Ibto){

		$getUserInfo = $this->db->query("SELECT * FROM users where unique_id='".$Ibto."'")->row();
		$ibAccount = $this->db->query("SELECT * FROM ib_accounts where unique_id='".$Ibto."'")->row();
		$decrypted_pass		=openssl_decrypt($getUserInfo->raw_pwd,"AES-128-ECB",'password');

		$mailHtml = $this->EmailConfigModel->ibAccountApproval($getUserInfo->first_name.' '.$getUserInfo->last_name, $ibAccount->mt5_login_id, $decrypted_pass);
		self::sendEmail($getUserInfo->email, 'Congratulations! You IB Account Approved', $mailHtml);

		$this->session->set_flashdata('msg', 'Successfully Resend Mail To : '.$getUserInfo->email);
		redirect(base_url() . 'admin/ib-management/ib-users-list');
	}
	
	
		public function sendEmail($toEmail,$subject,$htmlContent)
	{

		$companyName =ConfigData['m_company_name'];

		//Load email library
		$this->load->library('email');


		//SMTP & mail configuration
		$config = $this
			->EmailConfigModel
			->getConfig();


		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		// Recipient
		//$to ='aiubzahid@gmail.com';
		$to =$toEmail;

		$mailFrom=$this
			->EmailConfigModel
			->getFromEmail();

		$this->email->from($mailFrom,$companyName);
		$this->email->to($to);
		//$this->email->cc('keshriedutech@gmail.com');
		//$this->email->bcc('iambommanakavya@gmail.com');
		$this->email->subject($subject);
		$this->email->message($htmlContent);


		if($this->email->send()){
			//echo 1;
		}
	}
	
}
