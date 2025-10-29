<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TradingAccount extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertTradingAccount($data)
	{
		if($this->db->insert('trading_accounts', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function insertDemoTradingAccount($data)
	{
		if($this->db->insert('demo_trading_accounts', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	public function getTradingDemoAccountList($userid='',$formAccount='')
	{
		if ($userid) {
			if ($formAccount){
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.id,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.account_type_status,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `demo_trading_accounts` trdact
										INNER JOIN `demo_groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userid and not trdact.mt5_login_id=$formAccount ORDER BY created_at DESC" )->result();
			}else {
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.account_type_status,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.account_type_status,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `demo_trading_accounts` trdact
										INNER JOIN `demo_groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userid ORDER BY created_at DESC")->result();
			}
		}else{
			$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.mt5_login_id,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.account_type_status,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `demo_trading_accounts` trdact
										INNER JOIN `demo_groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id ORDER BY created_at DESC")->result();
		}

		if ($result) {
			return $result;
		}else{
			return array();
		}
	}

	public function getTradingAccount($tradingId=""){
		$result='';
		if ($tradingId) {
			$result		=$this->db->query("SELECT * FROM `trading_accounts` where id='" . $tradingId . "'")->row();
		}else{
			$result		=$this->db->query("SELECT * FROM `trading_accounts`")->result();
		}
		return $result;
	}

	public function getTradingAccountByMt5LoginId($mt5AccountId=""){

		$result		=$this->db->query("SELECT * FROM `trading_accounts` where mt5_login_id='" . $mt5AccountId . "'")->row();

		if ($result) {
		return $result;
		}else{
			return  '';
		}
	}

	public function getIbTransferAccount($unique_id='')
	{
		if ($unique_id) {
			$result = $this->db->query("SELECT unique_id,id,mt5_login_id FROM `ib_accounts` WHERE unique_id='$unique_id'")->result();
		} else {
			$result = $this->db->query("SELECT * FROM `ib_accounts`")->result();
		}
		return $result;
	}

	public function getTradingAccountList($userid='',$formAccount=''){
		if ($userid) {
			if ($formAccount){
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.pass_investor,trdact.pass_main,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.id,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.account_type_status,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `trading_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userid and not trdact.mt5_login_id=$formAccount ORDER BY created_at DESC" )->result();
			}else {
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.pass_investor,trdact.pass_main,trdact.id,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.account_type_status,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.account_type_status,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `trading_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userid ORDER BY created_at DESC")->result();
			}
		}else{
			$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.pass_investor,trdact.pass_main,trdact.id,trdact.mt5_login_id,trdact.live_rate,case when trdact.account_type_status = 1 then 'Live Rate' when trdact.account_type_status = 2 then 'Fixed Rate' END as 'account_type',trdact.account_type_status,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `trading_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id ORDER BY created_at DESC")->result();
		}

		if ($result) {
			return $result;
		}else{
			return array();
		}
	}

	public function getIbAccountList($userid='',$formAccount=''){
		if ($userid) {
			if ($formAccount){
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.margin,trdact.margin_free,trdact.profit,trdact.equity,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `trading_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userid and not trdact.mt5_login_id=$formAccount ORDER BY created_at DESC" )->result();
			}else {
				$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.mt5_login_id,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `ib_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.unique_id=trdact.unique_id where us.unique_id='$userid' ORDER BY trdact.created_at DESC")->result();
			}
		}else{
			$result = $this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.mt5_login_id,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `ib_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.unique_id=trdact.unique_id  ORDER BY trdact.created_at DESC")->result();
		}

		if ($result) {
			return $result;
		}else{
			return '';
		}
	}

	public function getTradingAccountByLoginId($mt5_login_id=""){
		$result='';
		if ($mt5_login_id) {
			$result		=$this->db->query("SELECT id,mt5_login_id,leverage FROM `trading_accounts` where mt5_login_id='" . $mt5_login_id . "'")->row();
		}else{
			$result		='';
		}
		return $result;
	}

	function updateTradingAccountInfo($request)
	{
		$mt5_login_id	=$request['mt5_login_id'];
		unset($request['mt5_login_id']);
		$data=array('pass_main'=>isset($request['pass_main'])?$request['pass_main']:'','leverage'=>isset($request['leverage'])?$request['leverage']:'');
		$this->db->set($data);
		$this->db->where('mt5_login_id', $mt5_login_id);
		$updateStatus=$this->db->update('trading_accounts');
		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}

	public function getTradingAccountForCron($tradingId=""){
		$result		=$this->db->query("SELECT mt5_login_id,id,user_id FROM `trading_accounts`")->result();
		return $result;
	}

	/*---------Lot Informations Functioanlity --------------*/
	public function checkLot($dealId=""){
		$result='';
		if ($dealId) {
			$result = $this->db->query("SELECT count(1) as cnt FROM `lot_informations` where deal_id='$dealId'")->row();
			return $result->cnt;
		}
	}

	function insertLot($data)
	{
		if($this->db->insert('lot_informations', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function updateLot($data,$id)
	{
		$this->db->set($data);
		$this->db->where('id', $id);
		$updateStatus=$this->db->update('lot_informations');
		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}

	/*public function getLiveTradersAll($userId=''){
		if ($userId){
			$sql="SELECT lot_informations.*,users.unique_id,users.email FROM `lot_informations`
					LEFT JOIN `users`
					ON users.user_id=lot_informations.user_id
					WHERE `position_id` IN (
								SELECT `position_id`
						FROM `lot_informations`
                        WHERE lot_informations.user_id=$userId
						GROUP BY position_id
						HAVING COUNT(position_id)=1
					)
					and lot_informations.user_id=$userId AND position_id>0";
		}else {
			$sql="SELECT lot_informations.*,users.unique_id,users.email FROM `lot_informations`
					LEFT JOIN `users`
					ON users.user_id=lot_informations.user_id
					WHERE `position_id` IN (
								SELECT `position_id`
						FROM `lot_informations`
						GROUP BY position_id
						HAVING COUNT(position_id)=1
					)
					AND position_id>0";

		}
		$result = $this->db->query($sql)->result();
		return $result;
	}*/
	public function getLiveTradersAll($userId='',$requestType=''){
		if ($userId){
			if ($requestType=='api'){
				$sql="SELECT li.mt5_login_id,u.first_name,u.last_name,li.symbol,(li.volume/10000) as `volume`,li.price,'Buy' as `status`,profit as floating_pl  FROM `lot_informations` li inner join users u on u.user_id = li.user_id
                    where li.action=0 and  `position_id` NOT IN (
								SELECT `position_id`
						FROM `lot_informations`
						where action = 1
						
					)
					AND position_id>0 and li.user_id = $userId and entry_status=0 ORDER BY li.created_at DESC";
				$query = $this->db->query($sql);
				$total_items	=$query->num_rows();

				$result=array(
					'totalItem'=>$total_items,
					'current_page'=>1,
					'history'=>[],
				);
				if ($total_items>0) {
					$items_per_page = 10;
					$current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$sql="SELECT li.mt5_login_id,u.first_name,u.last_name,li.symbol,(li.volume/10000) as `volume`,li.price,'Buy' as `status`,profit as floating_pl  FROM `lot_informations` li inner join users u on u.user_id = li.user_id
                    where li.action=0 and  `position_id` NOT IN (
								SELECT `position_id`
						FROM `lot_informations`
						where action = 1
						
					)
					AND position_id>0 and li.user_id = $userId and entry_status=0 ORDER BY li.created_at DESC LIMIT $start_item,$items_per_page";
					$resultItem = $this->db->query($sql)->result();

					$result=array(
						'totalItem'=>$total_items,
						'current_page'=>$current_page,
						'history'=>$resultItem,
					);
				}
			}else{
				$sql="SELECT li.mt5_login_id,u.first_name,u.last_name,li.symbol,(li.volume/10000) as `volume`,li.price,'Buy' as `status`,profit as floating_pl  FROM `lot_informations` li inner join users u on u.user_id = li.user_id
                    where li.action=0 and  `position_id` NOT IN (
								SELECT `position_id`
						FROM `lot_informations`
						where action = 1
						
					)
					AND position_id>0 and li.user_id = $userId and entry_status=0 ORDER BY li.created_at DESC";
				$result = $this->db->query($sql)->result();
			}
		}else {
			$sql="SELECT li.mt5_login_id,u.first_name,u.last_name,li.symbol,(li.volume/10000) as `volume`,li.price,'Buy' as `status`,profit as floating_pl  FROM `lot_informations` li inner join users u on u.user_id = li.user_id
                    where li.action=0 and  `position_id` NOT IN (
								SELECT `position_id`
						FROM `lot_informations`
						where action = 1
						
					)
					AND position_id>0 and entry_status=0 ORDER BY li.created_at DESC";

			$result = $this->db->query($sql)->result();

		}

		return $result;
	}
	
	

	/*public function getCloseTradersAll($userId=''){
		if ($userId){
			$result		=$this->db->query("SELECT lot_informations.*,users.unique_id,users.email 
					FROM lot_informations
					LEFT JOIN `users`
					ON users.user_id=lot_informations.user_id
					WHERE `position_id` IN (
						SELECT `position_id`
						FROM `lot_informations`
						WHERE lot_informations.user_id=$userId
						GROUP BY position_id
						HAVING COUNT(position_id) > 1
					)
					AND position_id>0 and lot_informations.user_id=$userId
					GROUP BY position_id")->result();

			return $result;
		}else{
			$result		=$this->db->query("SELECT lot_informations.*,users.unique_id,users.email 
					FROM lot_informations
					LEFT JOIN `users`
					ON users.user_id=lot_informations.user_id
					WHERE `position_id` IN (
						SELECT `position_id`
						FROM lot_informations
						GROUP BY position_id
						HAVING COUNT(position_id) > 1
					)
					AND position_id>0 
					GROUP BY position_id")->result();

			return $result;
		}
	}*/
	public function getCloseTradersAll($userId='',$request=''){
		$items_per_page = 10;
		$current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
		$dataList=array();

        if (isset($_REQUEST['filtering_options']) && !empty($_REQUEST['filtering_options'])){
            $filter=$_REQUEST['filtering_options'];
            if ($filter==1){
                $start_date =date('Y-m-d').' 00:00:00';
                $end_date =date('Y-m-d').' 23:30:00';
            }elseif ($filter==2){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .' -3 day'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==3){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .' -7 day'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==4){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .'-1 months'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==5){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .'-3 months'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==6){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .'-6 months'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==8){
                $start_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') .'-12 months'));
                $end_date    =date('Y-m-d').' 23:30:00';
            }elseif ($filter==7){
                $start_date  = date('Y-m-d',strtotime($_REQUEST['from_date'])).' 00:00:00';;
                $end_date    =date('Y-m-d',strtotime($_REQUEST['to_date'])).' 23:30:00';
            }

            if ($userId){

				$query			=$this->db->query("SELECT * FROM `lot_informations` WHERE action = 1 and entry_status=1 and deal_generated_date between '$start_date' and '$end_date' and user_id=$userId");
				$total_items	=$query->num_rows();

				$dataList = array(
					'closeTrade' =>'',
					'total_pages' => '',
					'current_page' => '',
					'totalItem'=>$total_items,
				);

				if ($total_items>0) {

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$result		=$this->db->query("SELECT li.position_id
                                    ,li.mt5_login_id,u.first_name,u.last_name
                                    ,li.symbol
                                    ,(li.volume / 10000) AS `volume`
                                    ,(
                                        SELECT lii.price
                                        FROM lot_informations lii
                                        WHERE lii.position_id = li.position_id
                                            AND lii.action = 0
                                        ) AS open_price
                                    ,(
                                        SELECT liii.created_at
                                        FROM lot_informations liii
                                        WHERE liii.position_id = li.position_id
                                            AND liii.action = 0 and  liii.created_at between '$start_date' and '$end_date'
                                        ) AS trade_open_datetime
                                    ,li.price AS close_price
                                    ,li.deal_generated_date AS trade_close_datetime
                                    ,li.profit
                                    ,'Sold' AS STATUS
                                FROM `lot_informations` li
                                INNER JOIN users u ON u.user_id = li.user_id
                                WHERE action = 1 and li.user_id = $userId and li.entry_status=1 and li.deal_generated_date between '$start_date' and '$end_date' ORDER BY li.created_at DESC LIMIT $start_item,$items_per_page")->result();

					$dataList = array(
						'closeTrade' => $result,
						'total_pages' => $total_pages,
						'current_page' => $current_page,
						'totalItem'=>$total_items,
					);
				}

                return $dataList;
            }else{

				$query			=$this->db->query("SELECT * FROM `lot_informations` WHERE action = 1 and entry_status=1 and deal_generated_date between '$start_date' and '$end_date' and user_id IS NOT NULL");
				$total_items	=$query->num_rows();

				$dataList = array(
					'closeTrade' =>'',
					'total_pages' => '',
					'current_page' => '',
					'totalItem'=>$total_items,
				);

				if ($total_items>0) {

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$result		=$this->db->query("SELECT li.position_id
						 ,li.mt5_login_id,u.first_name,u.last_name
						,li.symbol
						,(li.volume / 10000) AS `volume`
						,(
							SELECT lii.price
							FROM lot_informations lii
							WHERE lii.position_id = li.position_id
								AND lii.action = 0
							) AS open_price
						,(
							SELECT liii.created_at
							FROM lot_informations liii
							WHERE liii.position_id = li.position_id
								 AND liii.action = 0 and  liii.deal_generated_date between '$start_date' and '$end_date'
							) AS trade_open_datetime
						,li.price AS close_price
						,li.deal_generated_date AS trade_close_datetime
						,li.profit
						,'Sold' AS STATUS
					FROM `lot_informations` li
					INNER JOIN users u ON u.user_id = li.user_id
					WHERE action = 1 and li.entry_status=1 and li.deal_generated_date between '$start_date' and '$end_date' ORDER BY li.deal_generated_date DESC LIMIT $start_item,$items_per_page")->result();

					$dataList = array(
						'closeTrade' => $result,
						'total_pages' => $total_pages,
						'current_page' => $current_page,
						'totalItem'=>$total_items,
					);
				}

				return $dataList;
			}
        }else{
            if ($userId){

				$query			=$this->db->query("SELECT * FROM `lot_informations` WHERE action = 1 and entry_status=1 and user_id = $userId");
				$total_items	=$query->num_rows();

				$dataList = array(
					'closeTrade' =>'',
					'total_pages' => '',
					'current_page' => '',
					'totalItem'=>$total_items,
				);

				if ($total_items>0) {

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$result		=$this->db->query("SELECT li.position_id
                                    ,li.mt5_login_id,u.first_name,u.last_name
                                    ,li.symbol
                                    ,(li.volume / 10000) AS `volume`
                                    ,(
                                        SELECT lii.price
                                        FROM lot_informations lii
                                        WHERE lii.position_id = li.position_id
                                            AND lii.action = 0
                                        ) AS open_price
                                    ,(
                                        SELECT liii.created_at
                                        FROM lot_informations liii
                                        WHERE liii.position_id = li.position_id
                                            AND liii.action = 0
                                        ) AS trade_open_datetime
                                    ,li.price AS close_price
                                    ,li.deal_generated_date AS trade_close_datetime
                                    ,li.profit
                                    ,'Sold' AS STATUS
                                FROM `lot_informations` li
                                INNER JOIN users u ON u.user_id = li.user_id
                                WHERE action = 1 and li.user_id = $userId and li.entry_status=1 ORDER BY li.deal_generated_date DESC LIMIT $start_item,$items_per_page")->result();

					$dataList = array(
						'closeTrade' => $result,
						'total_pages' => $total_pages,
						'current_page' => $current_page,
						'totalItem'=>$total_items,
					);
				}

				return  $dataList;

            }else{

				$query			=$this->db->query('SELECT * FROM `lot_informations` WHERE action = 1 and entry_status=1 and user_id IS NOT NULL');
				$total_items	=$query->num_rows();

				$dataList = array(
					'closeTrade' =>'',
					'total_pages' => '',
					'current_page' => '',
					'totalItem'=>$total_items,
				);

				if ($total_items>0) {

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$result = $this->db->query("SELECT li.position_id
							 ,li.mt5_login_id,u.first_name,u.last_name
								,li.symbol
								,(li.volume / 10000) AS `volume`
								,(
									SELECT lii.price
									FROM lot_informations lii
									WHERE lii.position_id = li.position_id
										AND lii.action = 0
									) AS open_price
								,(
									SELECT liii.created_at
									FROM lot_informations liii
									WHERE liii.position_id = li.position_id
										AND liii.action = 0
									) AS trade_open_datetime
								,li.price AS close_price
								,li.deal_generated_date AS trade_close_datetime
								,li.profit
								,'Sold' AS STATUS
							FROM `lot_informations` li
							INNER JOIN users u ON u.user_id = li.user_id
							WHERE action = 1 and li.entry_status=1 ORDER BY li.deal_generated_date DESC LIMIT $start_item,$items_per_page")->result();

					$dataList = array(
						'closeTrade' => $result,
						'total_pages' => $total_pages,
						'current_page' => $current_page,
						'totalItem'=>$total_items,
					);
				}
				return $dataList;
            }
        }

	}

	public function updateBalance($requestData,$mt5Response){
		if ($mt5Response) {
			$updateItem = array(
				'balance' => $mt5Response->answer->balance->user,
			);

			$this->db->set($updateItem);
			$this->db->where('mt5_login_id', $requestData['mt5_login_id']);
			$updateStatus = $this->db->update('trading_accounts');

			return $updateStatus;
		}
	}

	public function getBalanceByMt($accountId=""){
		$result='';
		if ($accountId) {
			$result		=$this->db->query("SELECT balance FROM `trading_accounts` where mt5_login_id='" . $accountId . "'")->row();
		}
		return $result;
	}

}	
