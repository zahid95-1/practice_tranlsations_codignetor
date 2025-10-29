<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertPayment($data)
	{
		if($this->db->insert('payments', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function updatePayment($data,$userModel=''){
		$id		=$data['paymentId'];
		$unqId=$email='';
		if ($userModel){
			$unqId=$userModel->unique_id;
			$email=$userModel->email;
		}
		$updateItem=array('status'=>$data['payment_status'],'remarks'=>$data['remark'],'unique_id'=>$unqId,'email'=>$email);
		$this->db->set($updateItem);
		$this->db->where('id', $id);
		$updateStatus=$this->db->update('payments');
		if($updateStatus)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}

	public function getDepositHistory($paymentId="",$userId="",$requestType=''){
		$result='';
		if ($paymentId) {
			$result		=$this->db->query("SELECT p.* FROM `payments` p right join `trading_accounts` as td on td.mt5_login_id=p.mt5_login_id where p.id='" . $paymentId . "' and p.user_id=$userId")->row();
		}else{
			if ($requestType=='api'){

				$query 			= $this->db->query("SELECT p.*,td.live_rate,td.account_type_status FROM `payments` p right join `trading_accounts` as td on td.mt5_login_id=p.mt5_login_id where p.user_id=$userId");
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

					$resultItem 		= $this->db->query("SELECT p.*,td.live_rate,td.account_type_status FROM `payments` p right join `trading_accounts` as td on td.mt5_login_id=p.mt5_login_id where p.user_id=$userId LIMIT $start_item,$items_per_page")->result();

					$result=array(
						'totalItem'=>$total_items,
						'current_page'=>$current_page,
						'history'=>$resultItem,
					);

				}
			}else {
				$result		=$this->db->query("SELECT p.*,td.live_rate,td.account_type_status FROM `payments` p right join `trading_accounts` as td on td.mt5_login_id=p.mt5_login_id where p.user_id=$userId")->result();
			}
		}
		return $result;
	}

	public function getDepositByStatus($status=0){
		if ($status==0){
			$result		=$this->db->query("SELECT td.live_rate,td.account_type_status,p.gateway_url,u.first_name,u.last_name,u.email,u.unique_id,p.created_at,p.id,p.user_id,p.mt5_login_id,p.entered_amount,p.gateway_id,p.transaciton_detail,p.payment_mode,p.transaction_proof_attachment,p.status FROM `payments` p
										INNER JOIN `trading_accounts` td ON td.mt5_login_id=p.mt5_login_id 
										INNER JOIN users u
										ON u.user_id=p.user_id where p.status=$status AND p.payment_mode != 6  ORDER BY created_at DESC")->result();
		}else{
			$result		=$this->db->query("SELECT td.live_rate,td.account_type_status,p.gateway_url,u.first_name,u.last_name,u.email,u.unique_id,p.created_at,p.id,p.user_id,p.mt5_login_id,p.entered_amount,p.gateway_id,p.transaciton_detail,p.payment_mode,p.transaction_proof_attachment,p.status FROM `payments` p
										INNER JOIN `trading_accounts` td ON td.mt5_login_id=p.mt5_login_id 
										INNER JOIN users u
										ON u.user_id=p.user_id where p.status=$status ORDER BY created_at DESC")->result();
		}

		return $result;
	}

	public function getDepositDetailsWithUser($depositId=''){
		$result		=$this->db->query("SELECT p.*,td.live_rate,td.account_type_status,td.group_id,u.unique_id,u.first_name,u.last_name,u.email,u.gender,u.mobile,u.city,u.zip,c.name,kyc.identity_proof,kyc.residency_proof,bd.account_name,bd.account_number,bd.trx_code,bd.international_bank_account_number,bd.bank_name,bd.bank_address,coin.name as coin_name,up.wallet_address FROM `payments` p 
										left outer join users u
										ON u.user_id=p.user_id
										LEFT JOIN trading_accounts td
									    ON td.mt5_login_id=p.mt5_login_id
										LEFT JOIN country c
										ON c.id=u.country_id
										LEFT JOIN kyc_attachment kyc
										ON kyc.user_id=u.unique_id
										LEFT JOIN bank_details bd
										ON bd.unique_id=u.unique_id
										left outer join user_payment_info up on up.unique_id =u.unique_id
										left outer join coin on coin.coin_id = up.coin_id
										where p.id=$depositId")->row();
		return $result;
	}

	public function checkValidAmount($request){
		$loginId		=$request['mt5_login_id'];
		$requestAmount	=$request['amount'];

		$result		=$this->db->query("SELECT SUM(`entered_amount`) as totalAmount FROM `payments` WHERE `mt5_login_id`=$loginId")->row();
		if ($result->totalAmount>$requestAmount){
			return true;
		}else{
			return false;
		}
	}

	public function getTotalBalanceByLoginId($mt5_login_id){
		$tradingAccount	=$this->db->query("SELECT id,user_id FROM `trading_accounts` WHERE `mt5_login_id`=$mt5_login_id")->row();

		if ($tradingAccount){
			$tradeID		=$tradingAccount->id;
			$userId			=$tradingAccount->user_id;

			$result			=$this->db->query("SELECT SUM(`profit`) as totalAmount FROM `lot_informations` WHERE `trading_account_id`=$tradeID")->row();

			$withdraw		=$this->db->query("SELECT SUM(`requested_amount`) as totalWithdraw FROM `withdrawal` WHERE `mt5_login_id`=$mt5_login_id and status=2")->row();
			$transferAmount	=$this->db->query("SELECT SUM(`transfer_amount`) as totalTransfer FROM `internal_transfer` WHERE `mt5_login_id`=$mt5_login_id and user_id=$userId and status=1")->row();

			$withdrawAmount=0;
			if ($withdraw->totalWithdraw){
				$withdrawAmount=$withdraw->totalWithdraw;
			}
			$transfer=0;
			if ($transferAmount){
				$transfer=$transferAmount->totalTransfer;
			}

			if ($result->totalAmount>0) {
				return $result->totalAmount - ($transfer + $withdrawAmount);
			}else{
				return  0;
			}

		}else{
			return  0;
		}

	}


	public function depositCryptoPayment($data){
		if($this->db->insert('payments', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}

	}

	public function getWithdrawData($paymentId="",$unique_id="",$requestType='',$defaultTable='withdrawal'){
		if ($requestType=='api'){

			$query = "SELECT wd.*,bd.account_name,bd.account_number,coin.name as coin_name,up.wallet_address FROM $defaultTable wd 
			left outer join `bank_details` bd ON bd.bank_details_id=wd.bank_id and bd.status = 1
			left outer join coin on coin.coin_id = wd.coin_id
			left outer join user_payment_info up on up.unique_id ='$unique_id' and up.is_active = 1
			WHERE wd.unique_id='$unique_id' ORDER BY wd.requested_datetime DESC";

			$total_items	=$this->db->query($query)->num_rows();

			$result=array(
				'totalItem'=>$total_items,
				'current_page'=>1,
				'history'=>array(),
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

				$queryResult = "SELECT wd.*,bd.account_name,bd.account_number,coin.name as coin_name,up.wallet_address FROM $defaultTable wd 
			left outer join `bank_details` bd ON bd.bank_details_id=wd.bank_id and bd.status = 1
			left outer join coin on coin.coin_id = wd.coin_id
			left outer join user_payment_info up on up.unique_id ='$unique_id' and up.is_active = 1
			WHERE wd.unique_id='$unique_id' ORDER BY wd.requested_datetime DESC LIMIT $start_item,$items_per_page";

				$resultItem = $this->db->query($queryResult)->result();

				$result=array(
					'totalItem'=>$total_items,
					'current_page'=>$current_page,
					'history'=>$resultItem,
				);
			}

			}else {
			$sql = "SELECT wd.*,bd.account_name,bd.account_number,coin.name as coin_name,up.wallet_address FROM $defaultTable wd 
			left outer join `bank_details` bd ON bd.bank_details_id=wd.bank_id and bd.status = 1
			left outer join coin on coin.coin_id = wd.coin_id
			left outer join user_payment_info up on up.unique_id ='$unique_id' and up.is_active = 1
			WHERE wd.unique_id='$unique_id' ORDER BY wd.requested_datetime DESC";
			$result = $this->db->query($sql)->result();
		}
		return $result;
	}

	function insertInternalTransferPayment($data)
	{
		if($this->db->insert('internal_transfer', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	public function getMt5TransactionsSummery(){
		$sql="SELECT users.unique_id,users.email,users.ib_status,tda.mt5_login_id,tda.leverage,SUM(p.entered_amount) as total_payment FROM `trading_accounts` tda
				LEFT OUTER JOIN `payments` p
				ON p.user_id=tda.user_id
				LEFT JOIN `users`
				ON users.user_id=tda.user_id
				GROUP BY tda.user_id ORDER BY tda.created_at DESC";
		$result			=$this->db->query($sql)->result();
		return $result;
	}

	public function getUserModel($paymentId){
		$result			=$this->db->query("SELECT u.unique_id,u.first_name,u.last_name,u.email FROM `payments` p left outer join  `users` u on u.user_id=p.user_id WHERE `id`=$paymentId")->row();
		return $result;
	}

	public function getInternalTransferHistoryData($userId='',$request=''){
		$this->db->query("update internal_transfer u set notification = 1 where notification = 0");
		if ($userId) {

			if ($request=='api'){
				$query = $this->db->query("SELECT P.created_at,IT.status,IT.transfer_amount,IT.mt5_login_id as to_account,P.mt5_login_id as from_account FROM `internal_transfer` IT
						INNER JOIN payments P
						ON P.id=IT.payment_id
						WHERE IT.user_id=$userId ORDER BY P.created_at DESC");
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

					$resultItem = $this->db->query("SELECT P.created_at,IT.status,IT.transfer_amount,IT.mt5_login_id as to_account,P.mt5_login_id as from_account FROM `internal_transfer` IT
						INNER JOIN payments P
						ON P.id=IT.payment_id
						WHERE IT.user_id=$userId  ORDER BY P.created_at DESC LIMIT $start_item,$items_per_page")->result();

					$result=array(
						'totalItem'=>$total_items,
						'current_page'=>$current_page,
						'history'=>$resultItem,
					);
				}
			}else {
				$result = $this->db->query("SELECT P.created_at,IT.status,IT.transfer_amount,IT.mt5_login_id as to_account,P.mt5_login_id as from_account FROM `internal_transfer` IT
						INNER JOIN payments P
						ON P.id=IT.payment_id
						WHERE IT.user_id=$userId ORDER BY P.created_at DESC")->result();
			}
		}else{
			$result = $this->db->query("SELECT users.unique_id,users.email,users.first_name,users.last_name,P.created_at,IT.status,IT.transfer_amount,IT.mt5_login_id as to_account,P.mt5_login_id as from_account FROM `internal_transfer` IT
						INNER JOIN payments P
						ON P.id=IT.payment_id
                        INNER JOIN users
                        ON users.user_id=IT.user_id ORDER BY P.created_at DESC")->result();
		}
		return $result;
	}

	function get_currency_symbol($currency = '')
	{
		$symbols = array(
			'AED' => '&#1583;.&#1573;', // ?
			'AFN' => '&#65;&#102;',
			'ALL' => '&#76;&#101;&#107;',
			'AMD' => '&#1423;',
			'ANG' => '&#402;',
			'AOA' => '&#75;&#122;', // ?
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => '&#402;',
			'AZN' => '&#1084;&#1072;&#1085;',
			'BAM' => '&#75;&#77;',
			'BBD' => '&#36;',
			'BDT' => '&#2547;', // ?
			'BGN' => '&#1083;&#1074;',
			'BHD' => '.&#1583;.&#1576;', // ?
			'BIF' => '&#70;&#66;&#117;', // ?
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => '&#36;&#98;',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => '&#78;&#117;&#46;', // ?
			'BWP' => '&#80;',
			'BYR' => '&#112;&#46;',
			'BYN' => '&#66;&#114;',
			'BZD' => '&#66;&#90;&#36;',
			'CAD' => '&#36;',
			'CDF' => '&#70;&#67;',
			'CHF' => '&#67;&#72;&#70;',
			'CLF' => '', // ?
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUC' => '&#8396;',
			'CUP' => '&#8396;',
			'CVE' => '&#36;', // ?
			'CZK' => '&#75;&#269;',
			'DJF' => '&#70;&#100;&#106;', // ?
			'DKK' => '&#107;&#114;',
			'DOP' => '&#82;&#68;&#36;',
			'DZD' => '&#1583;&#1580;', // ?
			'EGP' => '&#163;',
			'ERN' => '&#78;&#102;&#107;', // ?
			'ETB' => '&#66;&#114;',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;', // ?
			'GGP' => '&#163;',
			'GHS' => '&#162;',
			'GIP' => '&#163;',
			'GMD' => '&#68;', // ?
			'GNF' => '&#70;&#71;', // ?
			'GTQ' => '&#81;',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => '&#76;',
			'HRK' => '&#107;&#110;',
			'HTG' => '&#71;', // ?
			'PKE' => '&#36;',
			'HUF' => '&#70;&#116;',
			'IDR' => '&#82;&#112;',
			'ILS' => '&#8362;',
			'IMP' => '&#163;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1583;', // ?
			'IRR' => '&#65020;',
			'IRT' => '&#65020;',
			'ISK' => '&#107;&#114;',
			'JEP' => '&#163;',
			'JMD' => '&#74;&#36;',
			'JOD' => '&#74;&#68;', // ?
			'JPY' => '&#165;',
			'KES' => '&#75;&#83;&#104;', // ?
			'KGS' => '&#1083;&#1074;',
			'KHR' => '&#6107;',
			'KMF' => '&#67;&#70;', // ?
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1583;.&#1603;', // ?
			'KYD' => '&#36;',
			'KZT' => '&#1083;&#1074;',
			'LAK' => '&#8365;',
			'LBP' => '&#163;',
			'LKR' => '&#8360;',
			'LRD' => '&#36;',
			'LSL' => '&#76;', // ?
			'LTL' => '&#76;&#116;',
			'LVL' => '&#76;&#115;',
			'LYD' => '&#1604;.&#1583;', // ?
			'MAD' => '&#1583;.&#1605;.', //?
			'MDL' => '&#76;',
			'MGA' => '&#65;&#114;', // ?
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => '&#75;',
			'MNT' => '&#8366;',
			'MOP' => '&#77;&#79;&#80;&#36;', // ?
			'MRO' => '&#85;&#77;', // ?
			'MUR' => '&#8360;', // ?
			'MVR' => '.&#1923;', // ?
			'MWK' => '&#77;&#75;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => '&#77;&#84;',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => '&#67;&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#65020;',
			'PAB' => '&#66;&#47;&#46;',
			'PEN' => '&#83;&#47;&#46;',
			'PGK' => '&#75;', // ?
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#71;&#115;',
			'QAR' => '&#65020;',
			'RON' => '&#108;&#101;&#105;',
			'RSD' => '&#1044;&#1080;&#1085;&#46;',
			'RUB' => '&#1088;&#1091;&#1073;',
			'RWF' => '&#1585;.&#1587;',
			'SAR' => '&#65020;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#163;', // ?
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => '&#76;&#101;', // ?
			'SOS' => '&#83;',
			'SPL' => '&#163;',
			'SRD' => '&#36;',
			'STD' => '&#68;&#98;', // ?
			'SVC' => '&#36;',
			'SYP' => '&#163;',
			'SZL' => '&#76;', // ?
			'THB' => '&#3647;',
			'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
			'TMT' => '&#109;',
			'TND' => '&#1583;.&#1578;',
			'TOP' => '&#84;&#36;',
			'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
			'TTD' => '&#36;',
			'TVD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => '',
			'UAH' => '&#8372;',
			'UGX' => '&#85;&#83;&#104;',
			'USD' => '&#36;',
			'UYU' => '&#36;&#85;',
			'UZS' => '&#1083;&#1074;',
			'VEF' => '&#66;&#115;',
			'VND' => '&#8363;',
			'VUV' => '&#86;&#84;',
			'WST' => '&#87;&#83;&#36;',
			'XAF' => '&#70;&#67;&#70;&#65;',
			'XCD' => '&#36;',
			'XDR' => '',
			'XOF' => '',
			'XPF' => '&#70;',
			'ZAR' => '&#82;',
			'ZMW' => '&#90;&#75;',
		);
		if ($currency) {
			if (isset($symbols[$currency])) {
				return $symbols[$currency];
			}
		}
		return $symbols;
	}

	public function getRateSettings(){
		$getSettingsModel =$this->db->query("SELECT dep_with_rate,rate_currency FROM setting")->row();
		$dep_with_rate=0;
		$rate_currency='USD';
		if ($getSettingsModel){
			$dep_with_rate=$getSettingsModel->dep_with_rate;
			$rate_currency=$getSettingsModel->rate_currency;
		}
		return array(
			'dep_with_rate'=>$dep_with_rate,
			'rate_currency'=>$rate_currency,
			'symbol'=>self::get_currency_symbol($rate_currency)
		);
	}

	public function getLiveRate(){
		$getRateSettings 	= self::getRateSettings();
		$toCurrency			=$getRateSettings['rate_currency'];
		$json 				= file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=USD&to_currency='.$toCurrency.'&apikey=MQUDE6RUMIQ48098');
		$data 				= json_decode($json,true);

		$convertPrice='';
		$exchangeRate = null;
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $innerKey => $innerValue) {
					if (strpos($innerKey, 'Exchange Rate') !== false) {
						$exchangeRate = $innerValue;
						break;
					}
				}
			}
		}

		return array('live_rate'=>$exchangeRate,'live_rate_response'=>$json);
	}
}

