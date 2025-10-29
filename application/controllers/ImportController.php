<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportController extends MY_Controller {

	private $mt5_instance="";
	public $controllerName='';
	public $actionName	='';

	function __construct()
	{
		parent::__construct();
		$this->load->library('CMT5Request');
		$this->load->library('form_validation');
		$this->load->model('RegisterModel');
		$this->load->model('EmailConfigModel');
		$this->mt5_instance =new CMT5Request();
	}

	public function importbankDetails(){
		$start_limit=$_REQUEST['start_limit'];
		$end_limit=$_REQUEST['end_limit'];

		$billingDetails	=$this->db->query("SELECT  bds.`Bank Country` as country,u.unique_id,bds.`Account Name` AS account_name,bds.`Account No.` AS account_number,bds.`IFSC Code` AS trx_code,bds.`IBAN No.` AS international_bank_account_number,bds.`Bank Name` AS bank_name,bds.`Bank Address` AS bank_address FROM `bank_details` b 
										LEFT JOIN users u ON u.unique_id = b.unique_id 
										LEFT JOIN bankdetails_sheet bds ON bds.Email = u.email 
										LIMIT $start_limit,$end_limit")->result();

		foreach ($billingDetails as $key=>$data){

			$countryName		=$data->country;
			$countryId			=$this->db->query("SELECT id FROM `country` WHERE name LIKE '$countryName'")->row();

				$updateItem=array(
					'account_name'=>$data->account_name,
					'account_number'=>$data->account_number,
					'trx_code'=>$data->trx_code,
					'international_bank_account_number'=>$data->international_bank_account_number,
					'bank_name'=>$data->bank_name,
					'bank_address'=>$data->bank_address,
					'status'=>1,
					'country_id'=>$countryId->id,
				);
				$this->db->set($updateItem);
				$this->db->where('unique_id', $data->unique_id);
				$updateStatus=$this->db->update('bank_details');
				echo "Updated ".$data->unique_id; echo "<pre/>";
		}

		exit();
	}

	public function removeTradingAccount(){
		$start_limit=$_REQUEST['start_limit'];
		$end_limit=$_REQUEST['end_limit'];

		$getTradingAccountList 	=$this->db->query("SELECT mt5_login_id,id,user_id FROM `trading_accounts` limit $start_limit,$end_limit")->result();
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

		$selectedItem=array();
		if ($getUserBalanceBatch!=false) {
			if ($getUserBalanceBatch->answer) {
				foreach ($getUserBalanceBatch->answer as $key => $itemData) {
					$selectedItem[]=$itemData->Login;
				}
			}
		}

		foreach ($selectedItem as $Key=>$item){
			unset($tradeAccountId[$item]);
		}

		if ($tradeAccountId) {
			$this->db->where_in('id', $tradeAccountId);
			$this->db->delete('trading_accounts');
		}

		echo "<pre>";
		print_r($tradeAccountId);
		exit();
	}
}
