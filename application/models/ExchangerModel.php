<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExchangerModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertExchanger($data)
	{
		if($this->db->insert('exchanger', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function insertBankDetails($data)
	{
		if($this->db->insert('client_bank_details', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	

		function saveExchangeTransfer($data)
		{
			if($this->db->insert('transfer_info', $data))
			{
				return  $this->db->insert_id();
			}
			else
			{
				return false;
			}
		}

	function updateExchanger($data,$exchangerId)
	{
		
		$this->db->set($data);
		$this->db->where('exchanger_id', $exchangerId);
		$updateStatus=$this->db->update('exchanger');

		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}

	public function getBankList(){
		$getBankList = $this->db->query("select  * from client_bank_details;")->result();
		return $getBankList;

	}

	public function getExchagerWithdraw(){
		$getExchagerWithdraw = $this->db->query("SELECT transfer_info_id,coverage_account_no,(select name from exchanger where exchanger_id = from_exchanger_id) as invester,(select name from exchanger where exchanger_id = to_exchanger_id) as exchanger,c.bank_name,c.account_no,c.ifsc_code,c.branch_name,amount,from_currency,note,t.created_datetime FROM `transfer_info` t inner join client_bank_details c on c.id = t.bank_id where type = 2;")->result();
		return $getExchagerWithdraw;

	}

	public function getExchagerDeposit(){
		$getExchagerDeposit = $this->db->query("SELECT transfer_info_id,(select name from exchanger where exchanger_id = from_exchanger_id) as invester,(select name from exchanger where exchanger_id = to_exchanger_id) as exchanger,
			amount,from_currency,note,created_datetime FROM `transfer_info` where type = 1
")->result();

		return $getExchagerDeposit;

	}


	public function getExchagerList(){
		
      	$getExchangerList = $this->db->query("select  exchanger_id
	,name
	,email
	,mobile
    ,total_deposit
    ,total_withdrawal
    ,(total_deposit-total_withdrawal) as remaining
    FROM (SELECT exchanger_id
	,name
	,email
	,mobile
	,COALESCE((
			SELECT sum(amount)
			FROM transfer_info t
			WHERE t.to_exchanger_id = e.exchanger_id
				AND type = 1
			), 0) AS total_deposit
	,COALESCE((
			SELECT sum(amount)
			FROM transfer_info t
			WHERE t.to_exchanger_id = e.exchanger_id
				AND type = 2
			), 0) AS total_withdrawal
FROM `exchanger` e) T;")->result();
		return $getExchangerList;
	}

		public function getCurrencyList(){
		
      	$getCurrencyList = $this->db->query("select  * from currency;")->result();
		return $getCurrencyList;
	}
}	
