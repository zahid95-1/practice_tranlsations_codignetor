<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WithdrawModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertWithdraw($data)
	{
		if($this->db->insert('withdrawal', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	public function getWithdrawDataList($withdrawId='',$status=1){
		if ($withdrawId) {
			$sql = "SELECT wd.*,td.account_type_status,td.live_rate,u.first_name,u.last_name,u.email,bd.account_name,bd.account_number,coin.name as coin_name,up.wallet_address 
					FROM `withdrawal` wd 
					left outer join `trading_accounts` td ON td.mt5_login_id=wd.mt5_login_id 
					left outer join `bank_details` bd ON bd.bank_details_id=wd.bank_id and bd.status = 1
					left outer join coin on coin.coin_id = wd.coin_id
					left outer join users u ON u.unique_id=wd.unique_id
					left outer join user_payment_info up on up.unique_id =wd.unique_id and up.is_active = 1
					WHERE wd.id='$withdrawId' where wd.status=$status ORDER BY wd.requested_datetime DESC";
		}else{
			$sql = "SELECT wd.*,td.account_type_status,td.live_rate,u.first_name,u.last_name,u.email,bd.account_name,bd.account_number,coin.name as coin_name,up.wallet_address 
					FROM `withdrawal` wd 
					left outer join `trading_accounts` td ON td.mt5_login_id=wd.mt5_login_id 
					left outer join `bank_details` bd ON bd.bank_details_id=wd.bank_id and bd.status = 1
					left outer join coin on coin.coin_id = wd.coin_id
					left outer join users u ON u.unique_id=wd.unique_id
					left outer join user_payment_info up on up.unique_id =wd.unique_id and up.is_active = 1
					where wd.status=$status ORDER BY wd.requested_datetime DESC";
		}
		$result		=$this->db->query($sql)->result();
		return $result;
	}

	public function getWithdrawDetailsWithUser($withdrawId=''){
		$result		=$this->db->query("SELECT w.*,u.unique_id,u.first_name,u.last_name,u.email,u.gender,u.mobile,u.city,u.zip,c.name,kyc.identity_proof,kyc.residency_proof,bd.account_name,bd.account_number,bd.trx_code,bd.international_bank_account_number,bd.bank_name,bd.bank_address,coin.name as coin_name,up.wallet_address FROM `withdrawal` w 
										left outer join users u
										ON u.unique_id=w.unique_id
										left outer join country c
										ON c.id=u.country_id
										left outer join kyc_attachment kyc
										ON kyc.user_id=u.unique_id
										left outer join bank_details bd
										ON bd.unique_id=u.unique_id
										left outer join user_payment_info up on up.unique_id =w.unique_id 
										left outer join coin on coin.coin_id = up.coin_id
										where w.id=$withdrawId ORDER BY w.requested_datetime DESC")->row();

		return $result;
	}

	function updateWithdrawStatus($data){
		$id		=$data['withdrawId'];
		$mt5_response		=$data['mt5_response'];
		$updateItem=array('status'=>$data['status'],'admin_remark'=>$data['remark'],'mt5_response'=>json_encode($mt5_response));
		$this->db->set($updateItem);
		$this->db->where('id', $id);
		$updateStatus=$this->db->update('withdrawal');
		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}
	public function getWithdrawUserList(){
		$getAllGroupUsers 	= $this->db->query("SELECT gp.group_name,trd.id,trd.mt5_login_id,users.first_name,users.last_name,users.unique_id as unique_id FROM `trading_accounts` trd 
											INNER JOIN `users` ON users.user_id=trd.user_id
											INNER JOIN `groups` gp ON gp.id=trd.group_id")->result();

		return $getAllGroupUsers;
	}
}	
