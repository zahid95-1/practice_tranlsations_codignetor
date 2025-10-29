<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertGroup($data)
	{
		if($this->db->insert('groups', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function updateGroup($data)
	{
		$groupId	=$data['id'];
		unset($data['id']);
		$this->db->set($data);
		$this->db->where('id', $groupId);
		$updateStatus=$this->db->update('groups');

		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}

	function updateClientGroup($data){

		$userId		=$data['mt5_login_id'];
		unset($data['mt5_login_id']);
		$this->db->set($data);
		$this->db->where('mt5_login_id', $userId);
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

	function groupUserlistByGroupID($groupId){
		$getAllUserList = $this->db->query("SELECT trd.balance,u.unique_id,u.first_name,u.last_name,u.email,u.mobile,u.country_id,u.created_datetime,g.group_name,c.name as 'country_name' FROM `trading_accounts` trd
												INNER JOIN `groups` g
												ON g.id=trd.group_id
												INNER JOIN `users` u
												ON u.user_id=trd.user_id
 												RIGHT JOIN `country` c
 												ON c.id=u.country_id
												WHERE trd.group_id=$groupId;")->result();

		return $getAllUserList;
	}

	public function getGroup($groupId="",$roleId="",$uniqueID = ""){
		$result='';
      	$getIbGroup = $this->db->query("SELECT COALESCE(ib.group_id,NULL)  as group_id,count(1) as cnt from users u inner join ib_accounts ib on ib.unique_id = u.parent_id 
      		and u.unique_id = '$uniqueID';")->row();
      	$group_Id = $getIbGroup->group_id;
      	$cnt = $getIbGroup->cnt;
		if (((ConfigData['prefix']=='SSM') || (ConfigData['prefix']=='IGM' || ConfigData['prefix']=='CFX')) && ($cnt > 0)){
			$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `groups` where id='" . $group_Id . "'")->result();
		}

		elseif ($groupId) {
			$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `groups` where id='" . $groupId . "'")->row();
		}else{
			if ($roleId==1){
				$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `groups` where status = 1")->result();
			}else {
				if ($_SESSION['role']== 0) {
					$result = $this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `groups`")->result();
				} else {
					$result = $this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `groups` where status = 1")->result();
				}
			}

		}
		return $result;
	}

	public function getDemoGroup($groupId="",$roleId="",$uniqueID = ""){
		$result='';
		$getIbGroup = $this->db->query("SELECT COALESCE(ib.group_id,NULL)  as group_id,count(1) as cnt from users u inner join ib_accounts ib on ib.unique_id = u.parent_id 
      		and u.unique_id = '$uniqueID';")->row();
		$group_Id = $getIbGroup->group_id;
		$cnt = $getIbGroup->cnt;
		if (((ConfigData['prefix']=='SSM') || (ConfigData['prefix']=='IGM' || ConfigData['prefix']=='CFX')) && ($cnt > 0)){
			$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `demo_groups` where id='" . $group_Id . "'")->result();
		}

		elseif ($groupId) {
			$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `demo_groups` where id='" . $groupId . "'")->row();
		}else{
			if ($roleId==1){
				$result		=$this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `demo_groups` where status = 1")->result();
			}else {
				if ($_SESSION['role']== 0) {
					$result = $this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `demo_groups`")->result();
				} else {
					$result = $this->db->query("SELECT id,group_name,mt5_group_name,minimum_deposit,spread_from,commission,swap,status FROM `demo_groups` where status = 1")->result();
				}
			}

		}
		return $result;
	}

	public function getMt5ClientGroupData(){
		$getAllGroupUsers 	= $this->db->query("SELECT gp.group_name,trd.id,trd.mt5_login_id,users.first_name,users.last_name FROM `trading_accounts` trd 
											INNER JOIN `users` ON users.user_id=trd.user_id
											INNER JOIN `groups` gp ON gp.id=trd.group_id")->result();
		$getGroupList 		= $this->db->query("SELECT id,group_name FROM `groups`")->result();
		$dataItem=array(
			'userList'=>$getAllGroupUsers,
			'groupList'=>$getGroupList,
		);
		return $dataItem;
	}
}	
