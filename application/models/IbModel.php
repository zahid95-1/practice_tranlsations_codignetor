<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IbModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function addSymbolValue($data)
	{
		if($this->db->insert('symbol_shares', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function insertIBPlan($data)
	{
		if($this->db->insert('ib_plan', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function insertCommissionGroup($data,$role)
	{
		$planID = $data['plan_id'];
		$groupID = $data['group_id'];
		$check = $this->db->query("SELECT * from ib_commission ibc inner join users u on u.unique_id = ibc.unique_id  where ibc.status = 1 and plan_id = $planID and group_id = $groupID  and level_no = 1 and u.role = 0 and  $role= 0")->result();

		if(count($check) <= 0){

			if($this->db->insert('ib_commission', $data))
			{
				return  $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}else
		{		return false;
		}
	}
	function insertCommissionRef($data)
	{
		$planID = $data['plan_id'];
		$groupID = $data['group_id'];
		$check = $this->db->query("SELECT * from ib_commission_ref ibc inner join users u on u.unique_id = ibc.unique_id  where ibc.status = 1 and plan_id = $planID and group_id = $groupID  and level_no = 1 and u.role = 0 and  ".$_SESSION['role'] ."= 0")->result();

		if(count($check) <= 0){

			if($this->db->insert('ib_commission_ref', $data))
			{
				return  $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}else
		{		return false;
		}
	}


	function getIbList(){
		$getIbList = $this->db->query("SELECT * from ib_plan where status = 1")->result();

		return $getIbList;
	}

	function getSymbolList(){
		$getSymbolList = $this->db->query("SELECT * from mt5_symbols ")->result();

		return $getSymbolList;
	}

	function getSymbolShareList(){
		$getSymbolShareList = $this->db->query("SELECT * from mt5_symbols ms inner join symbol_shares ss on ss.symbol_id = ms.id ")->result();
		return $getSymbolShareList;
	}



	function getIbCommissionGroupData($groupID,$planID){
		/*$getIbCommissionGroupData = $this->db->query("
		SELECT ibp.plan_name,g.group_name,ib.unique_id,ib.level_no,ib.value
		FROM `ib_commission` ib
		inner join users u on u.unique_id = ib.unique_id
		inner join `groups` g on g.id = ib.group_id
		inner join ib_plan ibp on ibp.plan_id = ib.plan_id
		where ib.plan_id = $planID and ib.group_id = $groupID;")->result();*/
		
		$getIbCommissionGroupData = $this->db->query("
		SELECT u.unique_id AS master_ib
		,iba.plan_id,iba.group_id
	,u.email
	,u.mobile
	,iba.mt5_login_id
	,CONCAT (
		first_name,' '
		,last_name 
		) AS username
	,u.created_datetime AS joining_date
FROM (
	SELECT ibc.unique_id AS parent_id
		,max(level_no) AS level_no
		,value
	FROM `ib_commission` ibc
	GROUP BY ibc.unique_id
	) T
LEFT JOIN users u ON u.parent_id = T.parent_id
LEFT JOIN ib_accounts iba ON iba.unique_id = u.unique_id
WHERE level_no = 1
	AND (
		SELECT unique_id
		FROM users
		WHERE ROLE = 0
		) = T.parent_id and iba.plan_id = $planID and iba.group_id = $groupID")->result();
		
		/*$getIbCommissionGroupData = $this->db->query("
		SELECT DISTINCT Ibcommto,value,plan_id,group_id,email,mobile,first_name,last_name,case when '$UniqueID' = Ibcommto THEN 'Self'
		else level_no END as level_number
        FROM (
        	SELECT  CASE 
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
        		,plan_id
        		,group_id
        	FROM `user_level_info` uli
        	INNER JOIN ib_commission ib ON ib.level_no = uli.level_no
        	WHERE ib.unique_id = '$UniqueID'
        		AND plan_id = $planID
        		AND group_id = $groupID
        	) T
        INNER JOIN users u ON u.unique_id = T.Ibcommto
        WHERE ibcommto IN (
        		SELECT unique_id
        		FROM ib_accounts
        		)")->result();*/

		return $getIbCommissionGroupData;

	}
	
		function getIbCommissionPlanData($planID){
	
		
		$getIbCommissionPlanData = $this->db->query("
		SELECT u.unique_id AS master_ib
		,iba.plan_id
	,u.email
	,u.mobile
	,iba.mt5_login_id
	,CONCAT (
		first_name,' '
		,last_name 
		) AS username
	,u.created_datetime AS joining_date
FROM (
	SELECT ibc.unique_id AS parent_id
		,max(level_no) AS level_no
		,value
	FROM `ib_commission` ibc
	GROUP BY ibc.unique_id
	) T
LEFT JOIN users u ON u.parent_id = T.parent_id
LEFT JOIN ib_accounts iba ON iba.unique_id = u.unique_id
WHERE level_no = 1
	AND (
		SELECT unique_id
		FROM users
		WHERE ROLE = 0
		) = T.parent_id and iba.plan_id = $planID ")->result();
		
	

		return $getIbCommissionPlanData;

	}

/*	function getIbCommissionGroupMasterData($groupID,$planID,$masterIB){
		
		$getIbCommissionGroupMasterData = $this->db->query("
		SELECT u.unique_id
		,iba.plan_id,iba.group_id
	,u.email
	,u.mobile
	,iba.mt5_login_id
	,CONCAT (
		first_name,' '
		,last_name
		) AS username
	,(select CONCAT (first_name,' ',last_name) FROM users where unique_id = T.parent_id ) as parent_ib
	,value
	,(level_no - 1) AS level_no
FROM (
	SELECT ibc.unique_id AS parent_id
		,max(level_no) AS level_no
		,value
	FROM `ib_commission` ibc
	WHERE ibc.unique_id IN (
			(
				SELECT user_id
				FROM `user_level_info`
				WHERE upline_id = '$masterIB'
				)
			)
		OR ibc.unique_id = '$masterIB'
	GROUP BY ibc.unique_id
	) T
LEFT JOIN users u ON u.parent_id = T.parent_id
LEFT JOIN ib_accounts iba ON iba.unique_id = u.unique_id 
where iba.plan_id = $planID and iba.group_id = $groupID")->result();
		
		

		return $getIbCommissionGroupMasterData;

	}*/
		function getIbCommissionGroupMasterData($planID,$masterIB){
		
		$getIbCommissionGroupMasterData = $this->db->query("
		SELECT u.unique_id
		,iba.plan_id
	,u.email
	,u.mobile
	,iba.mt5_login_id
	,CONCAT (
		first_name,' '
		,last_name
		) AS username
	,(select CONCAT (first_name,' ',last_name) FROM users where unique_id = T.parent_id ) as parent_ib
	,value
	,(level_no - 1) AS level_no
FROM (
	SELECT ibc.unique_id AS parent_id
		,max(level_no) AS level_no
		,value
	FROM `ib_commission` ibc
	WHERE ibc.unique_id IN (
			(
				SELECT user_id
				FROM `user_level_info`
				WHERE upline_id = '$masterIB'
				)
			)
		OR ibc.unique_id = '$masterIB'
	GROUP BY ibc.unique_id
	) T
LEFT JOIN users u ON u.parent_id = T.parent_id
LEFT JOIN ib_accounts iba ON iba.unique_id = u.unique_id 
where iba.plan_id = $planID")->result();
		
		

		return $getIbCommissionGroupMasterData;

	}

	function getIbCommissionGroup(){
	/*	$getIbCommissionGroup = $this->db->query("SELECT DISTINCT ibc.plan_id
	,ibc.group_id
	,ibp.plan_name
	,g.group_name
	,value
FROM `ib_commission` ibc
INNER JOIN `groups` g ON g.id = ibc.group_id
INNER JOIN ib_plan ibp ON ibp.plan_id = ibc.plan_id
INNER JOIN `users` u ON u.user_id = ibc.user_id
WHERE u.ROLE = 0;")->result();*/

        	$getIbCommissionGroup = $this->db->query("SELECT ibc.plan_id
        	,ibp.plan_name
        	,GROUP_CONCAT(DISTINCT CONCAT (
        			g.group_name
        			,' - '
        			,value
        			) ORDER BY g.group_name ASC) AS group_value_pairs
        FROM `ib_commission` ibc
        INNER JOIN `groups` g ON g.id = ibc.group_id
        INNER JOIN ib_plan ibp ON ibp.plan_id = ibc.plan_id
        INNER JOIN `users` u ON u.user_id = ibc.user_id
        WHERE u.ROLE = 0
        GROUP BY ibc.plan_id
        	,ibp.plan_name;")->result();





		return $getIbCommissionGroup;
	}

	function getIbCommissionGroupLevel(){
		$getIbCommissionGroup = $this->db->query("SELECT distinct ibc.plan_id,ibc.group_id,ibp.plan_name,g.group_name,value from `ib_commission_lvl` ibc inner join `groups` g on g.id = ibc.group_id inner join ib_plan ibp on ibp.plan_id = ibc.plan_id inner join `users` u on u.user_id = ibc.user_id where u.role = 0 GROUP BY ibc.plan_id,ibc.group_id;")->result();

		return $getIbCommissionGroup;
	}

	function getIbCommissionRefLevel(){
		$getIbCommissionRef = $this->db->query("SELECT distinct ibc.plan_id,ibc.group_id,ibp.plan_name,g.group_name,ibc.ref_link_name,value from `ib_commission_ref` ibc inner join `groups` g on g.id = ibc.group_id inner join ib_plan ibp on ibp.plan_id = ibc.plan_id inner join `users` u on u.user_id = ibc.user_id where u.role = 0 GROUP BY ibc.plan_id,ibc.group_id;")->result();

		return $getIbCommissionRef;
	}

	function getIbCommissionGroupLevelDetails($groupID,$planID){
		$getIbCommissionGroup = $this->db->query("SELECT ibc.value as level_share,ibc.level_no,ibc.plan_id,ibc.group_id,ibp.plan_name,g.group_name,value from `ib_commission_lvl` ibc inner join `groups` g on g.id = ibc.group_id inner join ib_plan ibp on ibp.plan_id = ibc.plan_id inner join `users` u on u.user_id = ibc.user_id where u.role = 0 and ibc.plan_id='$planID'  and ibc.group_id='$groupID';")->result();
		return $getIbCommissionGroup;
	}
	function getIbCommissionRefLevelDetails($groupID,$planID){
		$getIbCommissionRef = $this->db->query("SELECT ibc.value as level_share,ibc.level_no,ibc.plan_id,ibc.group_id,ibp.plan_name,g.group_name,value,ibc.unique_id,ibc.ref_link_name from `ib_commission_ref` ibc inner join `groups` g on g.id = ibc.group_id inner join ib_plan ibp on ibp.plan_id = ibc.plan_id inner join `users` u on u.user_id = ibc.user_id where u.role = 0 and ibc.plan_id='$planID'  and ibc.group_id='$groupID';")->result();
		return $getIbCommissionRef;
	}

	function getIbClientList($IBto,$limit=0,$where,$requestType='',$ibStatus=0){
	 
	 
		$limited='';
		if ($limit){
			$limited='limit '.$limit.'';
		}

		/*$getIbClientList = $this->db->query("SELECT iba.mt5_login_id as ib_account ,u.unique_id, CONCAT(u.first_name,u.last_name) as username ,u.email,u.mobile,ibc.lot,ibc.calculated_commission
			,u.parent_id,ibc.level
			FROM `ib_calculation`ibc
			inner join users u on u.unique_id = ibc.trader
			inner join ib_accounts iba on iba.unique_id = ibc.trader
			where ibcommission_to = '$IBto' ORDER By level ASC $limited;")->result();*/

		/**
		 * Using THis Secitons Only For API .Otheriwse it will not call anywhere
		*/
		if ($requestType=='api'){
			$query = $this->db->query("SELECT case when ibc.mt5_login_id IS NULL then '-' else ibc.mt5_login_id end as ib_account
									,CONCAT (
										u.first_name
										,' '
										,COALESCE(u.last_name,'')
										) AS username
									,u.email
									,u.user_id
									,u.unique_id
									,(SELECT CONCAT(first_name,' ',COALESCE(last_name,'')) from users where unique_id = u.parent_id) as upline_ib
									,ulf.level_no
									,u.mobile
									,c.nicename AS country_name
									,CASE 
										WHEN ibcal.calculated_commission IS NULL
											THEN 0
										ELSE SUM(ibcal.calculated_commission)
										END AS calculated_commission
									,(SELECT value  FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and  ib.plan_id = ibc.plan_id and ib.group_id = ibc.group_id  and u.ref_link_name = ib.ref_link_name ) and level_no = (SELECT max(level_no) FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and u.ref_link_name = ib.ref_link_name ) )) as commission_share_value
								FROM `user_level_info` ulf
								INNER JOIN users u ON u.unique_id = ulf.user_id
								left JOIN ib_accounts ibc ON ibc.unique_id = u.unique_id
								INNER JOIN country c ON c.id = u.country_id
								
								LEFT JOIN ib_calculation ibcal ON ibcal.ibcommission_to = ulf.upline_id
									AND ibcal.trader = u.unique_id
								WHERE ulf.status = 1 and u.ib_status='$ibStatus' and upline_id = '$IBto' $where
								 GROUP BY u.user_id
									 ORDER By level");

			$total_items	=$query->num_rows();

			$getIbClientList=array(
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

				$resultItem = $this->db->query("SELECT case when ibc.mt5_login_id IS NULL then '-' else ibc.mt5_login_id end as ib_account
									,CONCAT (
										u.first_name
										,' '
										,COALESCE(u.last_name,'')
										) AS username
									,u.email
									,u.user_id
									,u.unique_id
									,(SELECT CONCAT(first_name,' ',COALESCE(last_name,'')) from users where unique_id = u.parent_id) as upline_ib
									,ulf.level_no
									,u.mobile
									,c.nicename AS country_name
									,CASE 
										WHEN ibcal.calculated_commission IS NULL
											THEN 0
										ELSE SUM(ibcal.calculated_commission)
										END AS calculated_commission
									,(SELECT value  FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and ib.plan_id = ibc.plan_id and ib.group_id = ibc.group_id  and u.ref_link_name = ib.ref_link_name ) and level_no = (SELECT max(level_no) FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and u.ref_link_name = ib.ref_link_name ) )) as commission_share_value
								FROM `user_level_info` ulf
								INNER JOIN users u ON u.unique_id = ulf.user_id
								left JOIN ib_accounts ibc ON ibc.unique_id = u.unique_id
								INNER JOIN country c ON c.id = u.country_id
								
								LEFT JOIN ib_calculation ibcal ON ibcal.ibcommission_to = ulf.upline_id
									AND ibcal.trader = u.unique_id
								WHERE ulf.status = 1 and u.ib_status=$ibStatus and upline_id = '$IBto' $where
								 GROUP BY u.user_id
									 ORDER By level LIMIT $start_item,$items_per_page")->result();

				$getIbClientList=array(
					'totalItem'=>$total_items,
					'current_page'=>$current_page,
					'history'=>$resultItem,
				);
			}

		}else{
			/**
			 * Calling Only For Desktop Version
			 */

if($ibStatus==1){
    $getIbClientList = $this->db->query("SELECT case when ibc.mt5_login_id IS NULL then '-' else ibc.mt5_login_id end as ib_account
									,CONCAT (
										u.first_name
										,' '
										,COALESCE(u.last_name,'')
										) AS username
									,u.email
									,u.user_id
									,u.unique_id
									,(SELECT CONCAT(first_name,' ',COALESCE(last_name,'')) from users where unique_id = u.parent_id) as upline_ib
									,ulf.level_no
									,u.mobile
									,c.nicename AS country_name
									,CASE 
										WHEN ibcal.calculated_commission IS NULL
											THEN 0
										ELSE SUM(ibcal.calculated_commission)
										END AS calculated_commission
									,(SELECT value  FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and ib.plan_id = ibc.plan_id and ib.group_id = ibc.group_id  and u.ref_link_name = ib.ref_link_name ) and level_no = (SELECT max(level_no) FROM `ib_commission` ib where unique_id = (select parent_id from users u where unique_id = '$IBto' and u.ref_link_name = ib.ref_link_name ) )) as commission_share_value
								FROM `user_level_info` ulf
								INNER JOIN users u ON u.unique_id = ulf.user_id
								left JOIN ib_accounts ibc ON ibc.unique_id = u.unique_id
								INNER JOIN country c ON c.id = u.country_id
								
								LEFT JOIN ib_calculation ibcal ON ibcal.ibcommission_to = ulf.upline_id
									AND ibcal.trader = u.unique_id
								WHERE ulf.status = 1 and u.role!=0 and u.ib_status=$ibStatus and u.parent_id = '$IBto' $where
								 GROUP BY u.user_id
									 ORDER By level ASC $limited")->result();
}else{
    $getIbClientList = $this->db->query("SELECT 
    CASE 
        WHEN ibc.mt5_login_id IS NULL THEN '-' 
        ELSE ibc.mt5_login_id 
    END AS ib_account,
    CONCAT(u.first_name, ' ', COALESCE(u.last_name, '')) AS username,
    u.email,
    u.user_id,
    u.unique_id,
    (SELECT CONCAT(first_name, ' ', COALESCE(last_name, '')) FROM users WHERE unique_id = u.parent_id) AS upline_ib,
    ulf.level_no,
    u.mobile,
    c.nicename AS country_name
FROM 
    `user_level_info` ulf
INNER JOIN 
    users u ON u.unique_id = ulf.user_id
LEFT JOIN 
    trading_accounts ibc ON ibc.user_id = u.user_id 
INNER JOIN 
    country c ON c.id = u.country_id
WHERE 
    ulf.status = 1 
    AND u.role != 0 
   
    AND upline_id ='$IBto'
ORDER BY 
    ulf.level_no ASC $limited;")->result();
									 
}
		}
		return $getIbClientList;
	}

	function getIbClientLevelList($IBto){
		$getIbClientLevelList = $this->db->query("SELECT level, sum(calculated_commission) as ib_commission
					FROM `ib_calculation`ibc 
					where ibcommission_to = '$IBto' group by level ORDER By level ASC ;
			")->result();

		return $getIbClientLevelList;
	}


	function getIbLevelwiseDepositHistory($sessionID){
		$getIbLevelwiseDepositHistory = $this->db->query("SELECT level_no, sum(entered_amount) as total_deposit FROM `user_level_info` ulf INNER JOIN users u ON u.unique_id = ulf.user_id LEFT join payments p on p.user_id = u.user_id WHERE ulf.status = 1 and upline_id = '$sessionID' and p.status = 1 GROUP BY level_no;

			")->result();

		return $getIbLevelwiseDepositHistory;
	}

	function getIbLevelwiseWithdrawalHistory($sessionID){
		$getIbLevelwiseWithdrawalHistory = $this->db->query("SELECT level_no, sum(requested_amount) as total_withdrawal FROM `user_level_info` ulf INNER JOIN users u ON u.unique_id = ulf.user_id LEFT join withdrawal w on w.unique_id = u.unique_id WHERE ulf.status = 1 and upline_id = '$sessionID' and w.status = 2 GROUP BY level_no;

			")->result();

		return $getIbLevelwiseWithdrawalHistory;
	}





	function getUserIbListing(){
		$getUserIbListing = $this->db->query("SELECT u.unique_id
	,u.user_id
	,CONCAT (
		u.first_name
		,' '
		,COALESCE(u.last_name, '')
		) AS username
	,u.email
	,u.mobile
    ,c.name AS country_name
    ,g.group_name
    ,sum(calculated_commission) AS total_ib_commission
	,iba.created_at
    ,(
		SELECT CONCAT (
				uuu.first_name
				,' '
				,COALESCE(uuu.last_name, '')
				)
		FROM users uuu
		WHERE uuu.unique_id = u.parent_id and uuu.ib_status = 1
		) AS master_ib
      ,iba.mt5_login_id
    ,(select value  FROM `ib_commission` where unique_id = iba.unique_id LIMIT 1) as downline_share

    FROM `ib_accounts` iba INNER join (select unique_id,parent_id, user_id,email,mobile,first_name,last_name,country_id from users where ib_status = 1) u on u.unique_id = iba.unique_id 
    INNER JOIN `country` c ON c.id = u.country_id and c.is_active = 1
    INNER JOIN `groups` g ON g.id = iba.group_id 
    LEFT JOIN `ib_calculation` ibc ON ibc.ibcommission_to = u.unique_id
    GROUP BY ibc.ibcommission_to
	,iba.unique_id
    ORDER BY iba.created_at DESC;")->result();

		return $getUserIbListing;
	}



function getIBuserList(){
		$getIBuserList = $this->db->query("select * from users where (ib_status = 1 or role = 0) and status = 1 and ib_block = 0 ")->result();

		return $getIBuserList;
	}
function getIBBlockeduserList(){
		$getIBBlockeduserList = $this->db->query("select * from users where (ib_status = 1 or role = 0) and status = 1 and ib_block = 1 ")->result();

		return $getIBBlockeduserList;
	}
function getOldIbListing(){
		$getOldIbListing = $this->db->query("select * from old_ib_details")->result();

		return $getOldIbListing;
	}



	function getUserList(){
		$getUserList = $this->db->query("select * from users where  status = 1 and role <> 0")->result();

		return $getUserList;
	}

	function getUserIbCommissionGroup(){
	    
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
		
		$getUserIbCommissionGroup = $this->db->query("SELECT iba.unique_id
                    	,g.id as group_id
                    	,g.group_name
                    	,p.plan_name
                    	,p.plan_id
                    	,ibc.value
                    	,uli.level_no + 1 AS 'downline_level'
                    	,uli.level_no
                    FROM `ib_accounts` iba
                    INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
                    INNER JOIN `ib_commission` ibc ON ibc.plan_id = iba.plan_id
                    INNER JOIN groups g ON g.id = ibc.group_id
                    INNER JOIN `users` u ON ibc.unique_id = u.parent_id
                    	AND u.ref_link_name = ibc.ref_link_name
                    INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
                    	AND uli.STATUS = 1
                    WHERE u.unique_id = '$sessionUser'
                    	AND uli.user_id = '$sessionUser'
                    	AND (
                    		SELECT ROLE
                    		FROM `users`
                    		WHERE unique_id = uli.upline_id
                    		) = 0
                    	AND uli.level_no = ibc.level_no;
						
 ;")->result();

		return $getUserIbCommissionGroup;
	}
	
	function getUserIbCommissionGroup_vr(){
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
		
		$getUserIbCommissionGroup = $this->db->query("SELECT ibc.plan_id
                        	,ibc.group_id
                        	,ibc.unique_id AS upline
                        	,u.unique_id
                        	,uli.level_no + 1 AS 'downline_level'
                        	,uli.level_no
                        	,ibc.value AS value
                        	,g.group_name
                        	,p.plan_name
                        FROM `ib_commission` ibc
                        INNER JOIN groups g ON g.id = ibc.group_id
                        INNER JOIN ib_plan p ON p.plan_id = ibc.plan_id
                        INNER JOIN users u ON u.parent_id = ibc.unique_id
                        	AND u.ref_link_name = ibc.ref_link_name
                        INNER JOIN ib_accounts iba ON iba.unique_id = u.unique_id
                        INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id
                        	AND uli.STATUS = 1
                        WHERE  u.unique_id = '$sessionUser'
                        	AND uli.user_id = '$sessionUser'
                        	AND (
                        		SELECT ROLE
                        		FROM `users`
                        		WHERE unique_id = uli.upline_id
                        		) = 0
                        	AND uli.level_no = ibc.level_no;
 ;")->result();

		return $getUserIbCommissionGroup;
	}


	function getUserIbCommissionRef(){
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
		

		$checkRole = $this->db->query("SELECT uu.role from users u inner join users uu on uu.unique_id = u.parent_id  where u.unique_id = '$sessionUser' ")->row();

		if($checkRole->role <= 0){
			$getUserIbCommissionRef = $this->db->query("SELECT iba.unique_id
					,g.group_name
					,g.id AS group_id
					,p.plan_name
					,p.plan_id
					,ibc.value
					,uli.level_no + 1 AS 'downline_level'
					,uli.level_no
					,ibc.ref_link_name
				FROM `ib_accounts` iba
				INNER JOIN `groups` g ON g.id = iba.group_id
				INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
				INNER JOIN `ib_commission_ref` ibc ON ibc.group_id = iba.group_id
					AND ibc.plan_id = iba.plan_id
				INNER JOIN `users` u ON ibc.unique_id = u.parent_id

				INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id and uli.status = 1
				WHERE u.unique_id = '$sessionUser'
					AND uli.user_id = '$sessionUser'
					AND (
						SELECT ROLE
						FROM `users`
						WHERE unique_id = uli.upline_id
						) = 0 and uli.level_no = ibc.level_no
 ;")->result();
		}else{
			$getUserIbCommissionRef = $this->db->query("SELECT iba.unique_id
					,g.group_name
					,g.id AS group_id
					,p.plan_name
					,p.plan_id
					,ibc.value
					,uli.level_no + 1 AS 'downline_level'
					,uli.level_no
					,ibc.ref_link_name
				FROM `ib_accounts` iba
				INNER JOIN `groups` g ON g.id = iba.group_id
				INNER JOIN `ib_plan` p ON p.plan_id = iba.plan_id
				INNER JOIN `ib_commission_ref` ibc ON ibc.group_id = iba.group_id
					AND ibc.plan_id = iba.plan_id
				INNER JOIN `users` u ON ibc.unique_id = u.parent_id and ibc.ref_link_name = u.ref_link_name

				INNER JOIN `user_level_info` uli ON uli.user_id = iba.unique_id and uli.status = 1
				WHERE u.unique_id = '$sessionUser'
					AND uli.user_id = '$sessionUser'
					AND (
						SELECT ROLE
						FROM `users`
						WHERE unique_id = uli.upline_id
						) = 0 and uli.level_no = ibc.level_no
 ;")->result();
		}
		

		return $getUserIbCommissionRef;
	}
	function getGroupList(){
		$getGroup = $this->db->query("SELECT * from `groups`")->result();
		return $getGroup;
	}

	public function changeIbStatus($data,$user_id) {

		$this->db->set($data);
		$this->db->where('unique_id', $user_id);
		$update=$this->db->update('users');

		return true;
	}


	public function removeIb($user_id) {
		$data						=array('ib_block'=>1,
											'ib_block_date' => date("Y-m-d H:i:s"));
		$this->db->set($data);
		$this->db->where('unique_id', $user_id);
		$update=$this->db->update('users');
		return true;
	}
  
  public function changeIb($normal_user,$ib_user) {
		$data						=array('parent_id'=>$ib_user);
		$this->db->set($data);
		$this->db->where('unique_id', $normal_user);
		$update=$this->db->update('users');
    
    	$data						=array('status'=>2,
                                          'updated_datetime'=> date("Y-m-d H:i:s"),
                                          'updated_by' => 1);
		$this->db->set($data);
		$this->db->where('user_id', $normal_user);
		$update=$this->db->update('user_level_info');
    
    	$data						=array('status'=>2,
                                          'updated_datetime'=> date("Y-m-d H:i:s"),
                                          'updated_by' => 1);
		$this->db->set($data);
		$this->db->where('user_id', $normal_user);
		$update=$this->db->update('user_level_info_log');
		
    	$getlevelparentId = $this->db->query("SELECT * FROM `users` where unique_id = '$normal_user'")->result();

		foreach($getlevelparentId as $getlevelparentIdvalue){
			$uid = $getlevelparentIdvalue->unique_id;
			$level = 1;
			while($getlevelparentIdvalue->parent_id <> NULL){

				$dataL = array(
					'user_id' => $uid,
					'upline_id' => $getlevelparentIdvalue->parent_id,
					'level_no' =>$level,
					'ref_percentage' => 0,
					'created_by' => $uid,
					'created_datetime' => date('d/m/Y H:i:s')
				);

				$abc = $this->db->insert('user_level_info',$dataL);
				$abc = $this->db->insert('user_level_info_log',$dataL);
				//echo $this->db->last_query(). ";";
				$getlevelparentIdvalue = $this->db->query("SELECT * FROM `users` WHERE unique_id = '".$getlevelparentIdvalue->parent_id."'")->row();
				$level++;
			}

		}
		/*=====================end storing level Info===============*/
		return true;
	}

	function insertIbAccounts($data)
	{
		if($this->db->insert('ib_accounts', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	public function getDashboardInfo($ibTo){

		$getTotalIbCommission      = self::getCommissionBalance($ibTo);

		$withdrawCommission			=$this->db->query("SELECT SUM(transfer_amount) as withdrawTotalCommission FROM `commission_transfer` WHERE unique_id='$ibTo' and status=1")->row();
		$withdrawFromAdmin			=$this->db->query("SELECT SUM(requested_amount) as withdrawalTotal FROM `withdrawal` WHERE unique_id='$ibTo' and status=2 and ib_withdraw_status=1")->row();

		$getTotalDownLineVolume = $this->db->query("SELECT SUM(li.volume / 10000) AS totalVolume
                	,CONCAT (
                		u.first_name
                		,' '
                		,u.last_name
                		) AS client_name
                	,CASE 
                		WHEN trader = 'SSM345696'
                			THEN 'Self'
                		ELSE ibc.LEVEL
                		END AS level
                FROM `ib_calculation` ibc
                INNER JOIN lot_informations li ON li.id = ibc.lot_id
                INNER JOIN users u ON u.unique_id = ibc.trader
                WHERE ibcommission_to = '$ibTo'")->row();

		$totalClient			   =$this->db->query("select count(1) as totalClient from (SELECT case when ibc.mt5_login_id IS NULL then '-' else ibc.mt5_login_id end as ib_account
	,CONCAT (
		u.first_name
		,' '
		,COALESCE(u.last_name,'')
		) AS username
	,u.email
	,u.user_id
	,u.unique_id
	,(SELECT CONCAT(first_name,' ',COALESCE(last_name,'')) from users where unique_id = u.parent_id) as upline_ib
	,ulf.level_no
	,u.mobile
	,c.nicename AS country_name
	,CASE 
		WHEN ibcal.calculated_commission IS NULL
			THEN 0
		ELSE SUM(ibcal.calculated_commission)
		END AS calculated_commission
FROM `user_level_info` ulf
INNER JOIN users u ON u.unique_id = ulf.user_id
left JOIN ib_accounts ibc ON ibc.unique_id = u.unique_id
INNER JOIN country c ON c.id = u.country_id

LEFT JOIN ib_calculation ibcal ON ibcal.ibcommission_to = ulf.upline_id
	AND ibcal.trader = u.unique_id
WHERE upline_id = '$ibTo'
 GROUP BY u.user_id) T;")->row();

		$totalSubIb				   =$this->db->query("select count(email) as 'totablSubIB' from (SELECT email,concat(u.first_name,u.last_name) as name,sum(ibc.lot) as total_lot,sum(ibc.calculated_commission) as commission FROM `user_level_info` uli left join users u on u.unique_id = uli.user_id left join ib_calculation ibc on ibc.ibcommission_to = uli.user_id where uli.upline_id = '$ibTo' GROUP BY ibc.ibcommission_to) T")->row();
		//$listSubIb				   =$this->db->query("SELECT * FROM `users` WHERE parent_id='$ibTo' and ib_status=1")->result();

		$subIbList				=$this->db->query("SELECT email,concat(u.first_name,u.last_name) as name,sum(ibc.lot) as total_lot,sum(ibc.calculated_commission) as commission FROM `user_level_info` uli
                                    left join users u on u.unique_id = uli.user_id
                                    left join ib_calculation ibc on ibc.ibcommission_to = uli.user_id
                                    where uli.upline_id = '$ibTo'
                                    GROUP BY ibc.ibcommission_to
                                    ORDER BY sum(ibc.calculated_commission) DESC LIMIT 5;")->result();

		$upline			=$this->db->query("select concat(first_name,' ',last_name) as upline  from users where unique_id =  (SELECT parent_id FROM `users` WHERE unique_id='$ibTo')")->row();

		$wFromAdmin=0;
		if ($withdrawFromAdmin){
			$wFromAdmin=$withdrawFromAdmin->withdrawalTotal;
		}
		$transferWith=($withdrawCommission->withdrawTotalCommission)?$withdrawCommission->withdrawTotalCommission:0;
		$totalWithdraw=$transferWith+$wFromAdmin;
		$dataItem  =array(
			'withdraw_commission'=>($totalWithdraw)?$totalWithdraw:0,
			'available_commission'=>($getTotalIbCommission)?$getTotalIbCommission:0,
			'total_volume'=>($getTotalDownLineVolume->totalVolume)?$getTotalDownLineVolume->totalVolume:0,
			'total_client'=>($totalClient)?$totalClient->totalClient:0,
			'active_traders'=>0,
			'active_sub_ib'=>$totalSubIb->totablSubIB,
			'top_5_sub_ibs'=>$subIbList,
			'upline'=>$upline->upline,
		);

		return $dataItem;
	}

		public function getLotInformations($uniqueId,$requestType=''){

			/*$getTotalDownLineVolume    =$this->db->query("select li.*, sum(ibc.calculated_commission) as total_commission from lot_informations li
			inner join users u on u.user_id = li.user_id
			inner join ib_calculation ibc on ibc.ibcommission_to = u.unique_id
			where u.unique_id = '$uniqueId';")->result();*/

			if ($requestType=='api'){
				$query = $this->db->query("SELECT li.mt5_login_id
                	,ibc.created_datetime
                	,CONCAT (
                		u.first_name
                		,' '
                		,u.last_name
                		) AS client_name
                	,CASE 
                		WHEN trader = 'SSM345696'
                			THEN 'Self'
                		ELSE ibc.LEVEL
                		END AS level
                	,li.deal_id 
                	,li.symbol
                	,li.price
                	,li.profit
                	,(li.volume / 10000) AS lot
                	,calculated_commission
                	,li.action
                FROM `ib_calculation` ibc
                INNER JOIN lot_informations li ON li.id = ibc.lot_id
                INNER JOIN users u ON u.unique_id = ibc.trader
                WHERE ibcommission_to = '$uniqueId'");

				$total_items	=$query->num_rows();

				$result=array(
					'totalItem'=>$total_items,
					'current_page'=>1,
					'history'=>[],
				);

				if ($total_items>0) {
					$items_per_page =10;
					$current_page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

					// Calculate the total number of pages
					$total_pages = ceil($total_items / $items_per_page);

					// Make sure the current page is within the valid range
					$current_page = max(1, min($current_page, $total_pages));

					// Calculate the starting item index for the current page
					$start_item = ($current_page - 1) * $items_per_page;

					$resultItem = $this->db->query("SELECT li.mt5_login_id
												,ibc.created_datetime
												,CONCAT (
													u.first_name
													,' '
													,u.last_name
													) AS client_name
												,CASE 
													WHEN trader = 'SSM345696'
														THEN 'Self'
													ELSE ibc.LEVEL
													END AS level
												,li.deal_id 
												,li.symbol
												,li.price
												,li.profit
												,(li.volume / 10000) AS lot
												,calculated_commission
												,li.action
											FROM `ib_calculation` ibc
											INNER JOIN lot_informations li ON li.id = ibc.lot_id
											INNER JOIN users u ON u.unique_id = ibc.trader
											WHERE ibcommission_to = '$uniqueId' LIMIT $start_item,$items_per_page")->result();

					$result=array(
						'totalItem'=>$total_items,
						'current_page'=>$current_page,
						'history'=>$resultItem,
					);
				}
				return  $result;
			}else {
				if (isset($_REQUEST['filtering_options'])) {
					$filter = $_REQUEST['filtering_options'];
					if ($filter == 1) {
						$start_date = date('Y-m-d') . ' 00:00:00';
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 2) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -3 day'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 3) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -7 day'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 4) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '-1 months'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 5) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '-3 months'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 6) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '-6 months'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 8) {
						$start_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '-12 months'));
						$end_date = date('Y-m-d') . ' 23:30:00';
					} elseif ($filter == 7) {
						$start_date = date('Y-m-d', strtotime($_REQUEST['from_date'])) . ' 00:00:00';;
						$end_date = date('Y-m-d', strtotime($_REQUEST['to_date'])) . ' 23:30:00';
					}

					$getTotalDownLineVolume = $this->db->query("SELECT li.mt5_login_id
                	,ibc.created_datetime
                	,CONCAT (
                		u.first_name
                		,' '
                		,u.last_name
                		) AS client_name
                	,CASE 
                		WHEN trader = 'SSM345696'
                			THEN 'Self'
                		ELSE ibc.LEVEL
                		END AS level
                	,li.deal_id 
                	,li.symbol
                	,li.price
                	,li.profit
                	,(li.volume / 10000) AS lot
                	,calculated_commission
                	,li.action
                FROM `ib_calculation` ibc
                INNER JOIN lot_informations li ON li.id = ibc.lot_id
                INNER JOIN users u ON u.unique_id = ibc.trader
                WHERE ibcommission_to = '$uniqueId' and  ibc.created_datetime between '$start_date' and '$end_date'")->result();

				} else {
					$getTotalDownLineVolume = $this->db->query("SELECT li.mt5_login_id
                	,ibc.created_datetime
                	,CONCAT (
                		u.first_name
                		,' '
                		,u.last_name
                		) AS client_name
                	,CASE 
                		WHEN trader = 'SSM345696'
                			THEN 'Self'
                		ELSE ibc.LEVEL
                		END AS level
                	,li.deal_id 
                	,li.symbol
                	,li.price
                	,li.profit
                	,(li.volume / 10000) AS lot
                	,calculated_commission
                	,li.action
                FROM `ib_calculation` ibc
                INNER JOIN lot_informations li ON li.id = ibc.lot_id
                INNER JOIN users u ON u.unique_id = ibc.trader
                WHERE ibcommission_to = '$uniqueId'")->result();
				}

				return $getTotalDownLineVolume;
			}
	}


	public function getCommissionBalance($userUniqueId){

		$commissionResult	=$this->db->query("select sum(calculated_commission) as total_commission from ib_calculation ibc inner join users u on u.unique_id = ibc.ibcommission_to
		where ibc.ibcommission_to ='$userUniqueId'")->row();

		$transferAmount		=$this->db->query("SELECT SUM(`transfer_amount`) as totalTransfer FROM `commission_transfer` WHERE unique_id='$userUniqueId' and status=1")->row();

		$currentCommission	=$commissionResult->total_commission-$transferAmount->totalTransfer;

		//$currentCommission=20;

		return $currentCommission;
	}

	public function checkValidCommissionAmount($unique_id,$requestBody=''){
		$requestAmount				=$requestBody['amount'];
		$commissionBalance			=self::getCommissionBalance($unique_id);
		if ($commissionBalance>$requestAmount){
			return true;
		}else{
			return false;
		}
	}

	function insertCommissionTransfer($data)
	{
		if($this->db->insert('commission_transfer', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	public function getCommissionTransfer($uniqueId=''){
		if ($uniqueId) {
			$result = $this->db->query("SELECT users.unique_id,users.email,users.first_name,users.last_name,P.created_at,CT.status,CT.transfer_amount,CT.mt5_login_id as from_account,P.mt5_login_id as to_account FROM `commission_transfer` CT
										INNER JOIN `payments` P
										ON P.id=CT.payment_id
										INNER JOIN `users`
										ON users.unique_id=CT.unique_id where users.unique_id='$uniqueId' ORDER BY P.created_at DESC")->result();
		}else{
			$result = $this->db->query("SELECT CT.id,users.unique_id,users.email,users.first_name,users.last_name,P.created_at,CT.status,CT.transfer_amount,CT.mt5_login_id as from_account,P.mt5_login_id as to_account FROM `commission_transfer` CT
										INNER JOIN `payments` P
										ON P.id=CT.payment_id
										INNER JOIN `users`
										ON users.unique_id=CT.unique_id ORDER BY P.created_at DESC")->result();
		}
		return $result;
	}

	public function checkPendingStatus($uniqueId=''){
		$result = $this->db->query("SELECT SUM(transfer_amount) as totalAmount FROM `commission_transfer` where unique_id='$uniqueId' and status=0")->row();
		return $result;
	}

	public function commissionTransferDetails($commisionTransferId){
	    $sql="SELECT CT.remark,CT.id,u.unique_id,c.name,u.first_name,u.last_name,u.email,u.gender,u.mobile,u.city,u.zip,kyc.identity_proof,kyc.residency_proof,bd.account_name,bd.account_number,bd.trx_code,bd.international_bank_account_number,bd.bank_name,bd.bank_address,coin.name as coin_name,up.wallet_address,u.unique_id,P.created_at,CT.status,CT.transfer_amount,CT.mt5_login_id as from_account,P.mt5_login_id as to_account FROM `commission_transfer` CT
										INNER JOIN payments P
										ON P.id=CT.payment_id
										INNER JOIN users u
										ON u.unique_id=CT.unique_id
										left outer join country c
										ON c.id=u.country_id
										left outer join kyc_attachment kyc
										ON kyc.user_id=u.user_id
										left outer join bank_details bd
										ON bd.unique_id=u.unique_id
										left outer join user_payment_info up on up.unique_id =CT.unique_id 
										left outer join coin on coin.coin_id = up.coin_id	
										where CT.id=$commisionTransferId";

		$result = $this->db->query($sql)->row();
		return $result;
	}

	public function ApprovedCommissionTransfer($request){
		$ctId						=$request['transferId'];
		$commissionTransfer			=$this->db->query("SELECT * FROM `commission_transfer` WHERE id='$ctId'")->row();

		$updateItem=array('status'=>$request['status']);
		$this->db->set($updateItem);
		$this->db->where('id', $ctId);
		$updateStatus=$this->db->update('commission_transfer');

		$this->db->set($updateItem);
		$this->db->where('id', $commissionTransfer->payment_id);
		$updatePaymentStatus=$this->db->update('payments');

		if ($updatePaymentStatus){
			return true;
		}
	}

}	
