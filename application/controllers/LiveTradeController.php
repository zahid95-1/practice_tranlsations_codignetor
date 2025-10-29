

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LiveTradeController extends MY_Controller {

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

	//Manual Update Using BTN
	public function getLiveTrade()
	{
		$getTradingAccountList 	=$this->TradingAccount->getTradingAccountForCron();
		$tradeAccount=array();
		$tradeAccountId=array();
		$tradeUserIdArray		=array();
		if ($getTradingAccountList){
			foreach ($getTradingAccountList as $Key=>$item){
				$tradeAccount[]						 =$item->mt5_login_id;
				$tradeAccountId[$item->mt5_login_id] =$item->id;
				$tradeUserIdArray[$item->mt5_login_id] =$item->user_id;
			}
		}


		$getLotInfo	=$this->mt5_instance->getLotSize($tradeAccount);
		if ($getLotInfo!=false){
			if ($getLotInfo->answer){
				foreach ($getLotInfo->answer as $key=>$itemData){
					$tradingAccountId	=$tradeAccountId[$itemData->Login];
					$tradeUserId		=$tradeUserIdArray[$itemData->Login];
					$dataItem=array(
						'trading_account_id'=>$tradingAccountId,
						'user_id'			=>$tradeUserId,
						'deal_id'			=>$itemData->Deal,
						'entry_status'		=>$itemData->Entry,
						'position_id'		=>$itemData->PositionID,
						'symbol'			=>$itemData->Symbol,
						'price'				=>$itemData->Price,
						'profit'			=>$itemData->Profit,
						'action'			=>$itemData->Action,
						'mt5_login_id'		=>$itemData->Login,
						'contract_size'		=>$itemData->ContractSize,
						'volume'			=>$itemData->Volume,
						'deal_generated_date'=>(date('Y-m-d H:i:s',$itemData->Time)),
						'mt5_response'		=>json_encode($itemData),
					);
					self::createOrUpdateByDealWise($dataItem);
				}

			}
		}
	}

	public function manualLoad()
	{
		$requestedLimit=!empty($_REQUEST['limit'])?$_REQUEST['limit']:20;
		$getTradingAccountList 	=$this->db->query("SELECT mt5_login_id,id,user_id FROM `trading_accounts` where deal_update_cron_status=0 ORDER BY `created_at` DESC limit $requestedLimit")->result();
		$tradeAccount=array();
		$tradeAccountId=array();
		$tradeUserIdArray		=array();
		if ($getTradingAccountList){
			foreach ($getTradingAccountList as $Key=>$item){
				$tradeAccount[]						 =$item->mt5_login_id;
				$tradeAccountId[$item->mt5_login_id] =$item->id;
				$tradeUserIdArray[$item->mt5_login_id] =$item->user_id;
			}
		}

		$getLotInfo	=$this->mt5_instance->getLotSizeManual($tradeAccount);
		$getLastRecord	    = $this->db->query("select id,mt5_login_id from trading_accounts ORDER BY created_at ASC LIMIT 1 ")->row();

		$matchTradingAccountArray=array();

		if ($getLotInfo!=false){
			if ($getLotInfo->answer){
				$lastTradeLoginId='';

				foreach ($getLotInfo->answer as $key=>$itemData){
					$tradingAccountId	=$tradeAccountId[$itemData->Login];
					$tradeUserId		=$tradeUserIdArray[$itemData->Login];

					if ($getLastRecord->mt5_login_id==$itemData->Login){
						$updateItem		=array('deal_update_cron_status'=>0,'deal_loaded_status'=>0);
						$this->db->set($updateItem);
						$updateStatus=$this->db->update('trading_accounts');
					}else {
						$dataItem=array(
							'trading_account_id'=>$tradingAccountId,
							'user_id'			=>$tradeUserId,
							'deal_id'			=>$itemData->Deal,
							'entry_status'		=>$itemData->Entry,
							'position_id'		=>$itemData->PositionID,
							'symbol'			=>$itemData->Symbol,
							'price'				=>$itemData->Price,
							'profit'			=>$itemData->Profit,
							'action'			=>$itemData->Action,
							'mt5_login_id'		=>$itemData->Login,
							'contract_size'		=>$itemData->ContractSize,
							'volume'			=>$itemData->Volume,
							'deal_generated_date'=>(date('Y-m-d H:i:s',$itemData->Time)),
							'mt5_response'		=>json_encode($itemData),
						);

						self::createOrUpdateByDealWise($dataItem);

						if ($key>0) {
							//Update Trading Account
							if ($itemData->Login != $lastTradeLoginId) {
								array_push($matchTradingAccountArray, $lastTradeLoginId);
								$tradingAccountIdLast	=$tradeAccountId[$lastTradeLoginId];
								if ($tradingAccountIdLast){
									$updateItem		=array('deal_update_cron_status'=>1,'deal_loaded_status' =>1);
									$this->db->set($updateItem);
									$this->db->where('id', $tradingAccountIdLast);
									$updateStatus=$this->db->update('trading_accounts');
								}
							}
						}
					}

					$lastTradeLoginId=$itemData->Login;
				}
			}
			sleep(1);
		}

		/*----------Update All Data----------*/
		if ($matchTradingAccountArray){
			foreach ($matchTradingAccountArray as $Key=>$item){
				unset($tradeAccountId[$item]);
			}
		}

		if (!empty($tradeAccountId)) {

			if (array_key_exists($getLastRecord->mt5_login_id, $tradeAccountId)){
				$updateItem		=array('deal_update_cron_status'=>0,'deal_loaded_status'=>0);
				$this->db->set($updateItem);
				$updateStatus=$this->db->update('trading_accounts');
				echo "Update All 0";
			}else{
				$data = array(
					'deal_update_cron_status' => 1, // new value for the 'status' column
				);
				$this->db->where_in('id', $tradeAccountId); // specify the 'IN' operator
				$this->db->update('trading_accounts', $data); // perform the update query
			}
		}
	}

    public function getGroupId($tradId=null){

        $getGroupInfo = $this->mt5_instance->getGroupNameData($tradId);
          
        if ($getGroupInfo) {
            $selectedGroupName = $getGroupInfo->answer->group;
            
             // Example group name: Vr19_Pro_FX\Standard (no manual backslash escaping required)
            // Prepare the SQL query with a placeholder
            $sql = "SELECT * FROM `groups` WHERE `mt5_group_name` = ? ORDER BY `mt5_group_name` ASC";
            
            // Run the query as a prepared statement with parameter binding
            $query = $this->db->query($sql, array($selectedGroupName));
        
            // Fetch the result
            $getSingleGroup = $query->row();
    
            if($getSingleGroup){
                return $getSingleGroup->id;
            }
        }else{
            return '';
        }
    }
    
    
	//Trigger using CRONJOB
	public function index()
	{
	    
		$getTradingAccountList 	=$this->db->query("SELECT mt5_login_id,id,user_id FROM `trading_accounts` where deal_update_cron_status=0 ORDER BY `created_at` DESC limit 50")->result();
		$tradeAccount=array();
		$tradeAccountId=array();
		$tradeUserIdArray		=array();
		if ($getTradingAccountList){
			foreach ($getTradingAccountList as $Key=>$item){
				$tradeAccount[]						 =$item->mt5_login_id;
				$tradeAccountId[$item->mt5_login_id] =$item->id;
				$tradeUserIdArray[$item->mt5_login_id] =$item->user_id;
			}
		}

		$getLotInfo	=$this->mt5_instance->getLotSize($tradeAccount);
		$getLastRecord	    = $this->db->query("select id,mt5_login_id from trading_accounts ORDER BY created_at ASC LIMIT 1 ")->row();

		$matchTradingAccountArray=array();

		if ($getLotInfo!=false){
			if ($getLotInfo->answer){
				$lastTradeLoginId='';

				foreach ($getLotInfo->answer as $key=>$itemData){
         				$tradingAccountId	=$tradeAccountId[$itemData->Login];
					$tradeUserId		=$tradeUserIdArray[$itemData->Login];

                    $groupId = $this->getGroupId($itemData->Login);
 
					if ($getLastRecord->mt5_login_id==$itemData->Login){
						$updateItem		=array('deal_update_cron_status'=>0,'deal_loaded_status'=>0);
						$this->db->set($updateItem);
						$updateStatus=$this->db->update('trading_accounts');

                       

						$dataItem=array(
							'trading_account_id'=>$tradingAccountId,
							'user_id'			=>$tradeUserId,
							'deal_id'			=>$itemData->Deal,
							'group_id'			=>$groupId,
							'entry_status'		=>$itemData->Entry,
							'position_id'		=>$itemData->PositionID,
							'symbol'			=>$itemData->Symbol,
							'price'				=>$itemData->Price,
							'profit'			=>$itemData->Profit,
							'action'			=>$itemData->Action,
							'mt5_login_id'		=>$itemData->Login,
							'contract_size'		=>$itemData->ContractSize,
							'volume'			=>$itemData->Volume,
							'deal_generated_date'=>(date('Y-m-d H:i:s',$itemData->Time)),
							'mt5_response'		=>json_encode($itemData),
						);

						self::createOrUpdateByDealWise($dataItem);

					}else {
						$dataItem=array(
							'trading_account_id'=>$tradingAccountId,
							'user_id'			=>$tradeUserId,
							'deal_id'			=>$itemData->Deal,
							'group_id'			=>$groupId,
							'entry_status'		=>$itemData->Entry,
							'position_id'		=>$itemData->PositionID,
							'symbol'			=>$itemData->Symbol,
							'price'				=>$itemData->Price,
							'profit'			=>$itemData->Profit,
							'action'			=>$itemData->Action,
							'mt5_login_id'		=>$itemData->Login,
							'contract_size'		=>$itemData->ContractSize,
							'volume'			=>$itemData->Volume,
							'deal_generated_date'=>(date('Y-m-d H:i:s',$itemData->Time)),
							'mt5_response'		=>json_encode($itemData),
						);

						self::createOrUpdateByDealWise($dataItem);

						if ($key>0) {
							//Update Trading Account
							if ($itemData->Login != $lastTradeLoginId) {
								array_push($matchTradingAccountArray, $lastTradeLoginId);
								$tradingAccountIdLast	=$tradeAccountId[$lastTradeLoginId];
								if ($tradingAccountIdLast){
									$updateItem		=array('deal_update_cron_status'=>1,'deal_loaded_status' =>1);
									$this->db->set($updateItem);
									$this->db->where('id', $tradingAccountIdLast);
									$updateStatus=$this->db->update('trading_accounts');
								}
							}
						}
					}

					$lastTradeLoginId=$itemData->Login;
				}
			}
			sleep(1);
		}

		/*----------Update All Data----------*/
		if ($matchTradingAccountArray){
			foreach ($matchTradingAccountArray as $Key=>$item){
				unset($tradeAccountId[$item]);
			}
		}

		if (!empty($tradeAccountId)) {

			if (array_key_exists($getLastRecord->mt5_login_id, $tradeAccountId)){
				$updateItem		=array('deal_update_cron_status'=>0,'deal_loaded_status'=>0);
				$this->db->set($updateItem);
				$updateStatus=$this->db->update('trading_accounts');
				echo "Update All 0";
			}else{
				$data = array(
					'deal_update_cron_status' => 1, // new value for the 'status' column
				);
				$this->db->where_in('id', $tradeAccountId); // specify the 'IN' operator
				$this->db->update('trading_accounts', $data); // perform the update query
			}
		}
	}

	//Only Balance Will Be Updated Every 10 Second
	public function getLiveBalance(){

		$getTradingAccountList 	=$this->db->query("SELECT mt5_login_id,id,user_id FROM `trading_accounts` where balance_update_cron_status=0 limit 200")->result();
		$tradeAccount=array();
		$tradeAccountId=array();
		$tradeUserIdArray		=array();
		if ($getTradingAccountList){
			foreach ($getTradingAccountList as $Key=>$item){
				$tradeAccount[]						 =$item->mt5_login_id;
				$tradeAccountId[$item->mt5_login_id] =$item->id;
				$tradeUserIdArray[$item->mt5_login_id] =$item->user_id;
			}
		}

		$getUserBalanceBatch	=$this->mt5_instance->getUserBalance($tradeAccount);
		$getLastRecord	    = $this->db->query("select id,mt5_login_id from trading_accounts ORDER BY ID DESC LIMIT 1")->row();

		if ($getUserBalanceBatch!=false){
			if ($getUserBalanceBatch->answer) {
				foreach ($getUserBalanceBatch->answer as $key => $itemData) {
					$tradingAccountId 	= $tradeAccountId[$itemData->Login];
					$tradeUserId 		= $tradeUserIdArray[$itemData->Login];

					if ($getLastRecord->mt5_login_id==$itemData->Login){
						$updateItem		=array(
							'balance_update_cron_status'=>1,
							'balance'=>$itemData->Balance,
							'profit'=>$itemData->Profit,
							'margin'=>$itemData->Margin,
							'margin_free'=>$itemData->MarginFree,
							'equity'=>$itemData->Equity
						);

						$this->db->set($updateItem);
						$this->db->where('id', $tradingAccountId);
						$updateStatus=$this->db->update('trading_accounts');

						$updateItemAllReset		=array('balance_update_cron_status'=>0);
						$this->db->set($updateItemAllReset);
						$updateResetStatus=$this->db->update('trading_accounts');
						echo "All Reset To 0";
						break;
					}else{
						$updateItem		=array(
							'balance_update_cron_status'=>1,
							'balance'=>$itemData->Balance,
							'profit'=>$itemData->Profit,
							'margin'=>$itemData->Margin,
							'margin_free'=>$itemData->MarginFree,
							'equity'=>$itemData->Equity
						);

						$this->db->set($updateItem);
						$this->db->where('id', $tradingAccountId);
						$updateStatus=$this->db->update('trading_accounts');

						echo $itemData->Login.'<br>';
					}
				}
			}
		}
	}

	public function createOrUpdateByDealWise($requestData){
		$checkDealExistOrNot 	=$this->TradingAccount->checkLot($requestData['deal_id']);
		if ($checkDealExistOrNot==0){
			$this->TradingAccount->insertLot($requestData);
			echo "insert-".$requestData['mt5_login_id'].'-DealID-'.$requestData['deal_id'];echo "<br/>";
		}else{
			echo "Nothings Inserted-".$requestData['mt5_login_id'].'-DealID-'.$requestData['deal_id'];echo "<br/>";
		}
	}

	public function liveTrades(){
		$request	=self::isAuth();

		if($request['auth']==true) {
			$liveTraders		=$this->TradingAccount->getLiveTradersAll();

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
			$dataitem	=$this->TradingAccount->getCloseTradersAll('',$_REQUEST);
			$filterOption=$fromData=$toDate='';
			$dataArray=array();
			if (isset($_REQUEST['filtering_options']) && !empty($_REQUEST['filtering_options'])){
			    $filterOption       =$_REQUEST['filtering_options'];
                $fromData           =$_REQUEST['from_date'];
                $toDate             =$_REQUEST['to_date'];
                $dataitem['filter']=array('filterId'=>$filterOption,'from_date'=>$fromData,'to_date'=>$toDate);
            }
			self::renderView('close_traders',$dataitem,'','Live Traders');
		}else{
			if($this->session->userdata('login_from')=='admin'){
				redirect(base_url() . 'user/dashboard');
			}else{
				redirect(base_url() . 'login');
			}
		}
	}

	public function getLiveSymbols(){
		$getSymbols	=$this->mt5_instance->getLiveSymbols();
		if ($getSymbols){
			if ($getSymbols->answer){
				foreach ($getSymbols->answer as $key=>$symbols){
					$getLastRecord	    = $this->db->query("select * from mt5_symbols where symbol_name='".$symbols."'")->row();
					if ($getLastRecord){
						$updateItem		=array('symbol_name'=>$symbols);
						$this->db->set($updateItem);
						$this->db->update('mt5_symbols');
					}else{
						$data=array(
							'symbol_name'=>$symbols
						);
						$this->db->insert('mt5_symbols', $data);
					}
				}
			}
			echo "<h1>Imported/Update Complete All Symbols</h1>";
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
					$eventFrom=array('type'=>'api','auth'=>true);
				}
			}else{
				self::response(400,'Unauthorize user');
			}
		}else{
			$checkPermission	=$this->PermissionModel->checkExistPermission($this->session->userdata('user_id'),$this->actionName);
			if ($checkPermission) {
				if ($this->session->userdata('username') != '') {
					$eventFrom=array('type'=>'web','auth'=>true);
				}
			}else{
				redirect(base_url() . 'error/404');
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
		$this->load->view('admin/traders/'.$fileName.'',array('dataItem'=>$data,'params'=>$params));
		$this->load->view('includes/footer');
	}
}
