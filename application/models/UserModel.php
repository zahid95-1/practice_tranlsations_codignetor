<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class UserModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function getUser($uniqueId='',$status=1){
		$result='';
		if ($uniqueId) {
			$result	=$this->db->query("SELECT * FROM `users` u INNER JOIN `country` c ON u.country_id = c.id where u.role=$status and u.unique_id='$uniqueId'")->row();
		}else{
			$result= $this->db->query("SELECT * FROM users")->result();
		}
		return $result;
	}


	function getUserPlanGroup($uniqueId){
		
			$result	=$this->db->query("SELECT plan_id, group_id  FROM `ib_accounts` ib inner join users u on ib.unique_id = u.parent_id where u.unique_id = '$uniqueId'")->row();
		
		return $result;
	}

	function getIbUser($ib_status=''){
		$getUserData = $this->db->query("SELECT * FROM users u INNER JOIN country c ON u.country_id = c.id where u.role=1 and u.ib_status=$ib_status ORDER BY u.user_id DESC")->result();
		return $getUserData;
	}

	function ibRequest($data){
		if ($data['ib_request']=='on'){
			$id	=$data['userId'];
			$updateItem=array('ib_status'=>2); //Requesting Status
			$this->db->set($updateItem);
			$this->db->where('user_id', $id);
			$updateStatus=$this->db->update('users');
			if($updateStatus)
			{
				return  true;
			}
			else
			{
				return false;
			}
		}

	}

	public function getCloseTrade($userId){
		$query			=$this->db->query("SELECT * FROM `lot_informations` WHERE action = 1 and entry_status=1 and user_id = $userId");
		$total_items	=$query->num_rows();

		$result='';
		if ($total_items>0) {
			$start_item=0;
			$items_per_page=2;
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

			return $result;
		}else{
			return array();
		}
	}

	public function getLiveTraders($userId){
		$sql="SELECT li.mt5_login_id,u.first_name,u.last_name,li.symbol,(li.volume/10000) as `volume`,li.price,'Buy' as `status`,profit as floating_pl  FROM `lot_informations` li inner join users u on u.user_id = li.user_id
                    where li.action=0 and  `position_id` NOT IN (
								SELECT `position_id`
						FROM `lot_informations`
						where action = 1
						
					)
					AND position_id>0 and li.user_id = $userId and entry_status=0 ORDER BY li.created_at DESC LIMIT 2";

		$result		=$this->db->query($sql)->result();

		if($result){
			return $result;
		}else{
			return array();
		}
	}

	public function getDashboardData($uniqueId='',$objMt5=''){

		$userInfo	=$this->db->query("SELECT user_id,ib_status FROM `users` u where u.unique_id='$uniqueId'")->row();
		$userId     =$userInfo->user_id;

		$paymentTotal	   =$this->db->query("SELECT SUM(`entered_amount`) as 'totalPayment' FROM `payments` WHERE user_id=$userId and status=1")->row();
		$transferTotal	   =$this->db->query("SELECT SUM(`transfer_amount`) as 'internalTransfer' FROM `internal_transfer` WHERE user_id=$userId")->row();

		$depositBalance	  =$paymentTotal->totalPayment-$transferTotal->internalTransfer;

		$withdrawData	  =$this->db->query("SELECT SUM(`requested_amount`) as 'paidWithdraw' FROM `withdrawal` WHERE status=2 AND unique_id='$uniqueId'")->row();

		//Live Balance Functionals
		$currentBalance=0;
		$query = $this->db->query("SELECT mt5_login_id FROM `trading_accounts` WHERE `user_id` = $userId");
		$result = $query->result_array();
		$mt5LoginIds = array_column($result, 'mt5_login_id');
// 		$getUserBalanceBatch	=$objMt5->getUserBalance($mt5LoginIds);
// 		if ($getUserBalanceBatch!=false){
// 			if ($getUserBalanceBatch->answer){
// 				$currentBalance = array_sum(array_column($getUserBalanceBatch->answer, 'Balance'));
// 			}
// 		}

		$liveTradingInfo  =$this->db->query("SELECT gp.minimum_deposit,us.first_name,us.last_name,trdact.id,trdact.mt5_login_id,trdact.client,trdact.leverage,trdact.balance,trdact.created_at,gp.group_name,gp.mt5_group_name,gp.minimum_deposit FROM `trading_accounts` trdact
										INNER JOIN `groups` gp
										ON gp.id=trdact.group_id
										INNER JOIN `users` us
										ON us.user_id=trdact.user_id where us.user_id=$userId limit 5")->result();

		$totalLiveTradingAccount=$this->db->query("SELECT count(*) as 'totalLiveAccount' from `trading_accounts` where user_id=$userId ")->row();

		$transactions	   =$this->db->query("SELECT * FROM `payments` WHERE user_id=$userId and payment_mode<>5 limit 5")->result();

		$dataItem=array(
			'totalLiveTradingAccount'		=>$totalLiveTradingAccount->totalLiveAccount,
			'withdrawBalance'				=>$withdrawData->paidWithdraw,
			'depositBalance'				=>$depositBalance,
			'currentBalance'				=>($currentBalance)?$currentBalance:0,
			'liveTradingInfo'			    =>$liveTradingInfo,
			'liveTrade'			    		=>self::getLiveTraders($userId),
			'closeTrade'			    	=>self::getCloseTrade($userId),
			'transactions'			        =>$transactions,
			'is_ib'			        		=>$userInfo->ib_status,
			'promotions'			        =>array(
				array(
					'image'				=>base_url()."assets/promotions/pm_shinning_star_1.jpg",
					'terms_conditions'=>'<p>Introducing Shining Star Markets: Unlocking Diversified Trading for You!</p>
					<p>Diversified Trading: At Shining Star Markets, we believe in providing you with a wide range of trading options. Experience the thrill of trading across multiple markets and explore endless opportunities</p>
					<p>Fixed Spread: Say goodbye to unpredictable spreads that can eat into your profits. With Shining Star Markets, we offer fixed spreads, ensuring transparency and allowing you to plan your trades more effectively. No surprises, just a straightforward and reliable trading experience.</p>
					<p>1:1000 Leverage: Amplify your trading potential with our impressive leverage of 1:1000. This means you can control larger positions with a smaller amount of capital, maximizing your potential returns. Take advantage of this powerful tool and trade with confidence.</p>
					<p>Exceptional Customer Support:&nbsp;We value our traders and are dedicated to providing excellent customer support. Our team of experts is available 24/7 to assist you with any queries or concerns you may have. We strive to create a seamless trading experience for our clients, so you can focus on what matters most &ndash; your trades.</p>
					<p><br></p>
					<p><br></p>'
				),
				array(
					'image'				=>base_url()."assets/promotions/pm_shinning_star_2_4.jpg",
					'terms_conditions'=>'<p>Deposit $15,000 USD in your Trading Account with Shining Star Markets and Enjoy a 4 Days 3 Nights Dubai Trip! Here are the terms and conditions for this exciting offer:</p>
					<p>1. Eligibility: To qualify for this offer, you must deposit a minimum of $15,000 USD into your Shining Star Markets trading account.</p>
					<p>2. Contact from our Team: Once your deposit is confirmed, our dedicated team will reach out to you to initiate the visa process for your Dubai trip. We will guide you through the necessary steps to ensure a smooth and hassle-free experience.</p>
					<p>3. Required Documents: To proceed with the visa application, we will need the following documents from you:</p>
					<p>&nbsp; &nbsp;- Scan copy of your passport: Please provide a clear and legible copy of your passport information page.</p>
					<p>&nbsp; &nbsp;- 1 passport-size photo in white background: Ensure that the photo meets the specifications for visa applications.</p>
					<p>&nbsp; &nbsp;- PAN card: Submit a copy of your Permanent Account Number (PAN) card for verification purposes.</p>
					<p>4. Visa Application and Ticket Booking: Our team will handle the visa application process on your behalf. We will submit the necessary documents and information to the appropriate authorities. Once your visa is approved, we will proceed with booking your flight tickets for the designated travel dates.</p>
					<p>5. Processing Time: The visa application process typically takes 15-20 days. This includes the time required for document verification, application processing, and visa issuance. Please note that the processing time may vary depending on the current procedures and regulations.</p>
					<p>6. Dubai Trip Details: Upon successful completion of the visa process, you will receive a 4 Days 3 Nights trip to Dubai. Explore the iconic city, indulge in its vibrant culture, and create unforgettable memories.</p>
					<p>7. Additional Costs: While the Dubai trip is complimentary, please note that any additional expenses incurred during your stay, such as accommodation, meals, transportation, and activities, will be your responsibility.</p>
					<p>8. Travel Insurance: It is highly recommended to obtain travel insurance to ensure coverage for any unforeseen circumstances or emergencies during your trip.</p>
					<p>9. Terms and Conditions: This offer is subject to the terms and conditions set by Shining Star Markets. Please review and understand the terms before participating in the promotion.</p>
					<p>10. Limited Time Offer: This promotion is available for a limited time only. Don&apos;t miss this fantastic opportunity to trade with Shining Star Markets and experience an exciting trip to Dubai.</p>
					<p>Deposit $15,000 USD into your Trading Account with Shining Star Markets today and embark on a remarkable journey to Dubai!</p>
					<p><br></p>',
				),
				array(
					'image'				=>base_url()."assets/promotions/pm_shinning_star_3.jpeg",
					'terms_conditions'=>'<p>Make a total Deposit of $40,000 USD from your team and Enjoy a 4 Days 3 Nights Dubai Trip! Here are the terms and conditions for this exciting offer:</p>
					<p>1. Eligibility: To qualify for this offer, your team must deposit a minimum of $40,000 USD into a Shining Star Markets trading account.&nbsp;</p>
					<p>2. Contact from our Team: Once your deposit is confirmed, our dedicated team will reach out to you to initiate the visa process for your Dubai trip. We will guide you through the necessary steps to ensure a smooth and hassle-free experience.</p>
					<p>3. Required Documents: To proceed with the visa application, we will need the following documents from you:</p>
					<p>&nbsp; &nbsp;- Scan copy of your passport: Please provide a clear and legible copy of your passport information page.</p>
					<p>&nbsp; &nbsp;- 1 passport-size photo in white background: Ensure that the photo meets the specifications for visa applications.</p>
					<p>&nbsp; &nbsp;- PAN card: Submit a copy of your Permanent Account Number (PAN) card for verification purposes.</p>
					<p>4. Visa Application and Ticket Booking: Our team will handle the visa application process on your behalf. We will submit the necessary documents and information to the appropriate authorities. Once your visa is approved, we will proceed with booking your flight tickets for the designated travel dates.</p>
					<p>5. Processing Time: The visa application process typically takes 15-20 days. This includes the time required for document verification, application processing, and visa issuance. Please note that the processing time may vary depending on the current procedures and regulations.</p>
					<p>6. Dubai Trip Details: Upon successful completion of the visa process, you will receive a 4 Days 3 Nights trip to Dubai. Explore the iconic city, indulge in its vibrant culture, and create unforgettable memories.</p>
					<p>7. Additional Costs: While the Dubai trip is complimentary, please note that any additional expenses incurred during your stay, such as accommodation, meals, transportation, and activities, will be your responsibility.</p>
					<p>8. Travel Insurance: It is highly recommended to obtain travel insurance to ensure coverage for any unforeseen circumstances or emergencies during your trip.</p>
					<p>9. Terms and Conditions: This offer is subject to the terms and conditions set by Shining Star Markets. Please review and understand the terms before participating in the promotion.</p>
					<p>10. Limited Time Offer: This promotion is available for a limited time only. Don&apos;t miss this fantastic opportunity to trade with Shining Star Markets and experience an exciting trip to Dubai.</p>
					<p><br></p>
					<p><br></p>
					<p><br></p>',
				)
			),
		);

		return $dataItem;
	}

	public function getUserDropdownList($ibStatus=''){
		if ($ibStatus){
			$getAllGroupUsers 	= $this->db->query("SELECT gp.group_name,trd.id,trd.mt5_login_id,trd.account_type_status,users.first_name,users.last_name,users.email,users.unique_id as unique_id FROM `trading_accounts` trd 
											INNER JOIN `users` ON users.user_id=trd.user_id
											INNER JOIN `groups` gp ON gp.id=trd.group_id
											WHERE users.ib_status=1
                                            GROUP BY users.unique_id")->result();
		}else{
			$getAllGroupUsers 	= $this->db->query("SELECT gp.group_name,trd.id,trd.mt5_login_id,trd.account_type_status,users.first_name,users.last_name,users.email,users.unique_id as unique_id FROM `trading_accounts` trd 
											INNER JOIN `users` ON users.user_id=trd.user_id
											INNER JOIN `groups` gp ON gp.id=trd.group_id
                                            GROUP BY users.unique_id")->result();
		}
		return $getAllGroupUsers;
	}
	public function getMinWithdrawal(){
		$getMinWithdrawal 	= $this->db->query("select min_withdrawal from setting")->row();
		return $getMinWithdrawal;
	}

	public function getUserList(){
		$getAllGroupUsers 	= $this->db->query("SELECT * FROM `users` where status=1 order by first_name")->result();
		return $getAllGroupUsers;
	}

	function getOnlyManagementUser($unique_id='',$status=1){
		$result='';
		if ($unique_id) {
			$result= $this->db->query("SELECT users.*,roles.role_name FROM `users`
                                    INNER JOIN roles
                                    ON roles.role_id=users.role WHERE `parent_id` IS NULL AND unique_id='$unique_id' ORDER BY users.created_datetime DESC")->row();
		}else{
            $result= $this->db->query("SELECT users.*,roles.role_name FROM `users`
                                    INNER JOIN roles
                                    ON roles.role_id=users.role WHERE `parent_id` IS NULL ORDER BY users.created_datetime DESC")->result();
		}
		return $result;
	}

	public function getMainModiules(){
		$result= $this->db->query("SELECT * FROM `modules`")->result();
		return $result;
	}
}
