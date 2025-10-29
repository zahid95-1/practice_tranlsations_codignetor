<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function totalClients()
	{
		$getTotalClients    =$this->db->query("select count(1) as total_clients from users where status = 1;")->row();
		return $getTotalClients;
	}
	function IBClients()
	{
		$getIBClients    =$this->db->query("select count(1) as total_ib_clients from users where status = 1 and ib_status = 1;")->row();
		return $getIBClients;
	}
	function liveAccounts()
	{
		$getLiveAccounts    =$this->db->query("select count(1) as total_live_accounts from trading_accounts where status = 1;")->row();
		return $getLiveAccounts;
	}
	function totalFund()
	{
		$gettotalFund    =$this->db->query("select sum(entered_amount) as total_fund from payments   where status = 1;")->row();
		return $gettotalFund;
	}

	function totalWithdrawal()
	{
		$gettotalWithdrawal  =$this->db->query("select sum(requested_amount) as total_withdrawal from withdrawal where status = 2;")->row();
		return $gettotalWithdrawal;
	}
	function totalIBCommission()
	{
		$gettotalIBCommission  =$this->db->query("select sum(calculated_commission) as total_ib_commission from ib_calculation where status = 1;")->row();
		return $gettotalIBCommission;
	}

	function depositData()
	{
		$getdepositdata  =$this->db->query("SELECT p.created_at,u.email,p.mt5_login_id, case when payment_mode = 1 then 'Wire Transfer' when payment_mode = 2 then 'Crypto' when payment_mode = 3 then 'Paypal' when payment_mode = 7 then 'Stripe' WHEN payment_mode = 4 then 'Cash' WHEN payment_mode = 5 then 'Internal Transfer' END as 'payment_mode' ,p.entered_amount FROM `payments` p inner join users u on u.user_id = p.user_id where p.status = 1 order by p.created_at desc LIMIT 5")->result();
		return $getdepositdata;
	}

	function withdrawData()
	{
		$getwithdrawdata  = $this->db->query("SELECT p.requested_datetime,u.email,p.mt5_login_id, p.requested_amount ,case when p.status = 1 then 'Requested' when p.status = 2 then 'Paid' end as withdraw_status FROM `withdrawal` p inner join users u on u.unique_id = p.unique_id order by p.requested_datetime desc LIMIT 5")->result();
		return $getwithdrawdata;
	}

	function internalTransferData()
	{
		$internalTransferData  = $this->db->query("SELECT p.request_datetime,u.email,p.mt5_login_id, p.transfer_amount ,case when p.status = 1 then 'Approve' when p.status = 0 then 'Pending' end as internaltransfer_status FROM `internal_transfer` p inner join users u on u.user_id = p.user_id order by p.request_datetime desc LIMIT 5")->result();
		return $internalTransferData;
	}

	function IBCommissionData()
	{
		$IBCommissionData  = $this->db->query("SELECT p_created_datetime as created_datetime, u.email, iba.mt5_login_id, p_calculated_commission as calculated_commission FROM (SELECT p.created_datetime AS p_created_datetime, p.calculated_commission AS p_calculated_commission, p.ibcommission_to AS p_ibcommission_to FROM `ib_calculation` p ORDER BY p.created_datetime DESC LIMIT 5) p INNER JOIN users u ON u.unique_id = p.p_ibcommission_to INNER JOIN ib_accounts iba ON iba.unique_id = p.p_ibcommission_to LIMIT 5;;")->result();
		return $IBCommissionData;
	}


	function Clients()
	{
		$Clients  = $this->db->query("SELECT u.created_datetime,concat(u.first_name,' ',u.last_name) as username,u.email,u.mobile,c.nicename as country_name FROM `users` u inner join country c on c.id = u.country_id where role = 1 order by created_datetime desc limit 5")->result();
		return $Clients;
	}


	function IBpartners()
	{

		$IBpartners  = $this->db->query("SELECT iba.created_at,u.first_name,u.last_name,u.email,iba.mt5_login_id,c.nicename as country_name,u.mobile
 FROM `ib_accounts` iba inner join users u on u.unique_id = iba.unique_id
inner join country c on c.id = u.country_id
order by iba.created_at desc limit 5")->result();
		return $IBpartners;
	}

	
	

	




	


	

}	
