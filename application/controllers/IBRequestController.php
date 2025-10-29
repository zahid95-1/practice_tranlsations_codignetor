<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IBRequestController extends MY_Controller {

	private $mt5_instance="";
	function __construct()
	{
		parent::__construct();
		$this->load->model('TradingAccount');
		$this->load->model('UserModel');
		$this->load->library('form_validation');
		$this->load->model('IbModel');
		$this->load->model('PaymentModel');
		$this->load->model('EmailConfigModel');

		$this->load->library('CMT5Request');
		$this->mt5_instance =new CMT5Request();

		$this->load->library('Ciqrcode');
	}

	public function QRcode($data = 'test'){
		QRcode::png(
			$data
			,$outputfile = false
			,$level = QR_ECLEVEL_H
			,$size = 3
			,$margin = 1
		);
	}

	public function ibDashboard(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {

				$unqId=$request['unique_id'];
				$dashboardData=$this->IbModel->getDashboardInfo($request['unique_id']);
				$ibAccount = $this->db->query("SELECT mt5_login_id from ib_accounts where unique_id = '$unqId'")->row();
				$ibCommissionCnt = $this->db->query("SELECT max(ref_link_name) as cnt from ib_commission where unique_id = '$unqId'")->row();
				$ibCommission = $ibCommissionCnt->cnt;
				$commisionRunningBalance=0;
				// $getBalance=$this->mt5_instance->getRunningBalance($ibAccount->mt5_login_id);
				// $commisionRunningBalance=0;
				// if ($getBalance!=false){
				// 	$commisionRunningBalance=$getBalance->answer->balance->user;
				// }
				$dashboardData['available_commission']=$commisionRunningBalance;
				$dashboardData['ib_commission']=$ibCommission;



				$ibCommissionRefCnt = $this->db->query("SELECT max(ref_link_name) as cnt from ib_commission_ref where unique_id = '$unqId'")->row();
				$ibCommissionRef = $ibCommissionRefCnt->cnt;
				$dashboardData['ib_commission_ref']=$ibCommissionRef;



				self::renderView('ib-dashboard',$dashboardData,'','Ib Dashboard');
			}else{
				if ($request['ib_status']==1){
					$unqId=$request['unique_id'];
					$dashboardData=$this->IbModel->getDashboardInfo($request['unique_id']);
					$ibCommissionCnt = $this->db->query("SELECT count(1) as cnt from ib_commission where unique_id = '$unqId'")->row();
				$ibCommission = $ibCommissionCnt->cnt;
					$ibAccount = $this->db->query("SELECT mt5_login_id from ib_accounts where unique_id = '$unqId'")->row();
					$getBalance=$this->mt5_instance->getRunningBalance($ibAccount->mt5_login_id);
					$commisionRunningBalance=0;
					if ($getBalance!=false){
						$commisionRunningBalance=$getBalance->answer->balance->user;
					}
					$dashboardData['available_commission']=$commisionRunningBalance;
					$dashboardData['ib_commission']=$ibCommission;
					self::response(200, $dashboardData);
				}else {
					$dataItem=array(
						'message'=>"You are not listed as IB"
					);
					self::response(200,$dataItem );
				}
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function mySubIbs(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getIbClient['IbClient'] = $this->IbModel->getIbClientList($request['unique_id'],'','','',1);
				$getIbClient['IbClientLevel'] = $this->IbModel->getIbClientLevelList($request['unique_id']);
				self::renderView('my-ibs',$getIbClient,'','My Sub IB');
			}elseif($request['type'] == 'api') {
				$getIbClient['IbClient'] = $this->IbModel->getIbClientList($request['unique_id'],'','',$request['type'],1);
				self::response(200,$getIbClient['IbClient']);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}


	public function myClient(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getIbClient['IbClient'] = $this->IbModel->getIbClientList($request['unique_id'],'','','',0);
				$getIbClient['IbClientLevel'] = $this->IbModel->getIbClientLevelList($request['unique_id']);
				self::renderView('my-clients',$getIbClient,'','My Client');
			}elseif($request['type'] == 'api') {
				$getIbClient['IbClient'] = $this->IbModel->getIbClientList($request['unique_id'],'','',$request['type'],0);
				self::response(200,$getIbClient['IbClient']);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}
	
	public function LevelWiseDepositHistory(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getIbLevelwiseDepositHistory['DepositHistory'] = $this->IbModel->getIbLevelwiseDepositHistory($request['unique_id']);
				self::renderView('level-wise-deposit-history',$getIbLevelwiseDepositHistory,'','Deposit History');
			}elseif($request['type'] == 'api') {
			$getIbLevelwiseDepositHistory['DepositHistory'] = $this->IbModel->getIbLevelwiseDepositHistory($request['unique_id']);
				self::response(200,	$getIbLevelwiseDepositHistory['DepositHistory']);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function LevelWiseWithdrawalHistory(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web') {
				$getIbLevelwiseWithdrawalHistory['WithdrawalHistory'] = $this->IbModel->getIbLevelwiseWithdrawalHistory($request['unique_id']);
				self::renderView('level-wise-withdrawal-history',$getIbLevelwiseWithdrawalHistory,'','Withdrawal History');
			}elseif($request['type'] == 'api') {
			$getIbLevelwiseWithdrawalHistory['WithdrawalHistory'] = $this->IbModel->getIbLevelwiseWithdrawalHistory($request['unique_id']);
				self::response(200,	$getIbLevelwiseWithdrawalHistory['WithdrawalHistory']);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function myCommission(){
		$request	=self::isAuth();

		if($request['auth']==true) {
				if($request['type'] == 'web') {
					$getIbCommision['lotInformations']	 = $this->IbModel->getLotInformations($request['unique_id'],$request['type']);
					$filterOption=$fromData=$toDate='';
					$dataArray=array();
					if (isset($_REQUEST['filtering_options'])){
						$filterOption       =$_REQUEST['filtering_options'];
						$fromData           =$_REQUEST['from_date'];
						$toDate             =$_REQUEST['to_date'];
						$getIbCommision['filter']=array('filterId'=>$filterOption,'from_date'=>$fromData,'to_date'=>$toDate);
					}
					self::renderView('my-commission',$getIbCommision,'','Commission');
				}else{
					$getIbCommision= $this->IbModel->getLotInformations($request['unique_id'],$request['type']);
					self::response(200,$getIbCommision);
				}
		}else{
			if ($request['type'] == 'api'){
				self::response(200,array('message'=>"You are not in IB list"));
			}else {
				redirect(base_url() . 'login');
			}
		}
	}



	public function ibWithdraw(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$dataList['trading_account_from'] 	=$this->TradingAccount->getIbTransferAccount($request['unique_id']);
			$dataList['trading_account'] 		=$this->TradingAccount->getTradingAccountList($request['userId']);
			$dataList['commission_details'] 	= $this->IbModel->getCommissionTransfer($request['unique_id']);
			if($request['type'] == 'web') {
				self::renderView('ib-withdraw',$dataList,'','Commission Transfer');
			}else{
				$getBalance = $this->mt5_instance->getRunningBalance($dataList['trading_account_from'][0]->mt5_login_id);
				$dataList['available_balance'] 		=$getBalance->answer->balance->user;
				self::response(200,$dataList);
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function getCommissionAmount(){
		$request	=self::isAuth();

		if ($_REQUEST['mt5_login_id']){
			$getBalance=$this->mt5_instance->getRunningBalance($_REQUEST['mt5_login_id']);
			$commissionBalance=0;
			if ($getBalance!=false){
				$commissionBalance=$getBalance->answer->balance->user;
			}
			if($request['type'] == 'web') {
			print_r($commissionBalance);
			exit();
			}else{
				$data = array('balance'=>$commissionBalance);
				self::response(200,$data);
			}
		}
	}

	/**
	 * Commission Transfer Save functionality Build.
	 * This functions provides the Deposit and Trasnfer Amount to mt5
	 */
	public function commissionTransfer(){
		$request	=self::isAuth();
		if($request['auth']==true) {
			if($request['type'] == 'web' || $request['type']=='api') {

				//Validate Field
				$this->form_validation->set_rules('from_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('to_mt5_login_id', 'Account', 'trim|required');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required');

				if ($_REQUEST['from_mt5_login_id']) {
//					$checkValidWithdrawAmmount = $this->IbModel->checkValidCommissionAmount($request['unique_id'],$_REQUEST);
//					if (empty($checkValidWithdrawAmmount)) {
//						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
//					}

					$getBalance = $this->mt5_instance->getAvailableBalance($_REQUEST['from_mt5_login_id']);
					$commisionRunningBalance=0;
					if ($getBalance!=false){
						$commisionRunningBalance=$getBalance->answer[0]->Equity;
					}

					if ($commisionRunningBalance>0 && $_REQUEST['amount']>$commisionRunningBalance) {
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be less than balance', 'required');
					}elseif ($_REQUEST['amount']==0 || $_REQUEST['amount']==''){
						$this->form_validation->set_rules('verified_status', 'Withdraw amount should be greater than 0', 'required');
					}

					$checkPendingTransferStatus=$this->IbModel->checkPendingStatus($request['unique_id']);
					$dbBalance=$checkPendingTransferStatus->totalAmount+$_REQUEST['amount'];
					if ($dbBalance>$commisionRunningBalance){
						$this->form_validation->set_rules('verified_status', 'withdrawal request is already in queue', 'required');
					}
				}

				if ($this->form_validation->run() == FALSE)
				{
					/*--------Error Response------*/
					$responseData	=array(
						'from_mt5_login_id'				=>strip_tags(form_error('from_mt5_login_id')),
						'to_mt5_login_id'				=>strip_tags(form_error('to_mt5_login_id')),
						'amount'						=>strip_tags(form_error('amount')),
						'verified_status'				=>strip_tags(form_error('verified_status')),
					);

					if($request['type'] == 'web'){

						$_SESSION['error_transfer']	=json_encode($responseData,true);
						$_SESSION['request_data']	=json_encode($_REQUEST,true);
						redirect(base_url() . 'user/ib-withdraw');

					}else if($request['type'] == 'api'){
						self::response(400,$responseData);
					}
				}else{

					//$getResponseGroup = $this->mt5_instance->commissionTransferAmount($_REQUEST);
					//if ($getResponseGroup!=false) {

						//Deposit Balance In CRM
						$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$tnx = "COMMISSION-TRANSFER" . substr(str_shuffle($permitted_chars), 0, 24);
						$dataP = array(
							'user_id' => $request['userId'],
							'from_currency' => 'INR',
							'mt5_login_id' => $_REQUEST['to_mt5_login_id'],
							'entered_amount' => $_REQUEST['amount'],
							'to_currency' => 'INR',
							'amount' => $_REQUEST['amount'],
							'transaction_proof_attachment' => '',
							'payment_mode' => 6, //Commission Transfer
							'transaciton_detail' => 'Commisison Transfer',
							'gateway_id' => $tnx,
							'gateway_url' => 'NULL',
							'is_roi' => 1,
							'status' => 0,
							'created_at' => date("Y-m-d H:i:s")
						);

						$insertPaymentId = $this->PaymentModel->insertPayment($dataP);

						if ($insertPaymentId) {

							$dataForTransfer = array(
								'unique_id' => $request['unique_id'],
								'payment_id' => $insertPaymentId,
								'mt5_login_id' => $_REQUEST['from_mt5_login_id'],
								'transfer_amount' => $_REQUEST['amount'],
								'remark' => isset($_REQUEST['meta_descriptions'])?$_REQUEST['meta_descriptions']:'',
								'status' =>0,
								'created_by' =>$request['userId'],
							);

							$transferAmount = $this->IbModel->insertCommissionTransfer($dataForTransfer);

							if ($transferAmount) {

								$this->load->model('ActivityLogModel');
								$this->ActivityLogModel->createActiviyt('Commision Transfer From- '.$_REQUEST['from_mt5_login_id'].' , To - '.$_REQUEST['to_mt5_login_id'].' , Amount='.$_REQUEST['amount'].'',$request['userId']);

//								$mailHtml 	=$this->EmailConfigModel->fundDeposit($request['fullName'],$_REQUEST['amount'],$_REQUEST['amount'],$_REQUEST['from_mt5_login_id']);
//								self::sendEmail($request['email'],'Fund Deposit Successful',$mailHtml);

								if ($request['type'] == 'api') {
									self::response(200, 'Successfully Transfer');
								} else if ($request['type'] == 'web') {
									$_SESSION['success_transfer'] = 'Successfully Transfer';
									redirect(base_url() . 'user/ib-withdraw');
								}
							}
						}
//					}else{
//						/*--------Handelling Mt5 Creating Error Response------*/
//						$responseData	=array(
//							'status'    		=> 400,
//							'mt5_error'			=>"There is somethings wrong with mt5 server. Please contact with mt5 admin",
//						);
//
//						if($request['type'] == 'web'){
//							$_SESSION['error_transfer']			=json_encode($responseData,true);
//							$_SESSION['request_data']			=json_encode($_REQUEST,true);
//							redirect(base_url() . 'user/ib-withdraw');
//
//						}else if($request['type'] == 'api'){
//							self::response(400,$responseData);
//						}
//					}
				}

			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function ibRequest()
	{
		$request	=self::isAuth();

		if($request['auth']==true) {
			if($request['type'] == 'web') {
				self::renderView('ib-request','','','Ib Request');
			}else{
				self::response(200,"Not Found");
			}
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function changeIbStatus(){
		$request	=self::isAuth();

		if($request['auth']==true) {

			$this->form_validation->set_rules('ib_request', 'Ib Request', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				/*--------Error Response------*/
				$responseData	=array(
					'ib_request'			=>strip_tags(form_error('ib_request')),
				);

				if($request['type'] == 'web'){
					$_SESSION['error_ib_request']	=json_encode($responseData,true);
					redirect(base_url() . 'user/ib-request');
				}else if($request['type'] == 'api'){
					self::response(400,$responseData);
				}

			}else{
				$_REQUEST['userId']	=$request['userId'];
				$ibRequested 	= $this->UserModel->ibRequest($_REQUEST);  //request IB user
				if ($ibRequested) {

					$this->load->model('ActivityLogModel');
					$this->ActivityLogModel->createActiviyt('Ib Request Placed',$request['userId']);

					if ($request['type'] == 'api') {
						self::response(200, 'Successfully submit your request');
					} else if ($request['type'] == 'web') {
						$_SESSION['success_ib_request'] = 'Successfully submit your request';
						redirect('user/ib-request');
					}
				}else{
					self::response(400,'Wrong Info');
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
						'ib_status'  =>$getUserInfo->ib_status,
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
	
		public function OpenIbDashboard(){
		if(isset($_SESSION['open_ib'])){
			unset($_SESSION['open_ib']);
		}
		$_SESSION['open_ib']	=true;
		if (isset($_SESSION['ib_status']) && $_SESSION['ib_status']==1) {
			redirect(base_url() . 'user/ib-dashboard');
		}else{
			redirect(base_url() . 'login');
		}
	}

	public function CloseIbDashboard(){
		unset($_SESSION['open_ib']);
		redirect(base_url() . 'user/dashboard');
	}
	
}
