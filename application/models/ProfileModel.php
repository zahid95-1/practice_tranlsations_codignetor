<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class ProfileModel extends CI_Model {
	
	
	function changepasswordUser($old_pwd,$new_pwd,$re_pwd,$uid)
	{

		
		$getUserData=$this->db->query("SELECT * FROM users where unique_id='".$uid."'")->row();

		if($getUserData->password == md5($old_pwd) && $new_pwd == $re_pwd) {
		
			$data = array('password' => md5($new_pwd),
				'raw_pwd' => openssl_encrypt($this->security->xss_clean($new_pwd),"AES-128-ECB",'password'));
			$this->db->set($data);
			$this->db->where('unique_id', $uid);
			$updateStatus=$this->db->update('users');
			return true;
		}else{
			return false;
		}
		
	}
	function changepinUser($old_pin,$new_pin,$re_pin,$uid)
	{
		$getUserData=$this->db->query("SELECT * FROM users where unique_id='".$uid."'")->row();
		if($getUserData->pin <> NULL){
			$condition = $getUserData->pin == md5($old_pin) && $new_pin == $re_pin;
		}else{
			$condition = $new_pin == $re_pin;
		}
		if($condition) {
			$data = array('pin' => md5($new_pin));
			$this->db->set($data);
			$this->db->where('unique_id', $uid);
			$updateStatus=$this->db->update('users');
			return true;
		}else{
			return false;
		}
		
	}
	function bankdetails($data,$uid)
	{
		$getBankData=$this->db->query("SELECT * FROM bank_details where unique_id='".$uid."'")->result();
		if(count($getBankData) > 0){
			$this->db->set('status',0);
			$this->db->where('unique_id', $uid);
			$updateStatus=$this->db->update('bank_details');
		}
			
		$this->db->insert('bank_details', $data);
			return true;
		
		
	}

	function deleteAccount($uid)
	{
			$getUser=$this->db->query("SELECT * FROM users where unique_id='".$uid."' ")->row();
			if($getUser->is_deleted == 0){
				$data = array('is_deleted'=> 1,
				'account_deletion_date'=>date('Y-m-d H:i:s')	);
			}else {
				$data = array('is_deleted'=> 0,
				'account_deletion_date'=>date('Y-m-d H:i:s')	);
			}
			
			$this->db->set($data);
			$this->db->where('unique_id', $uid);
			$updateStatus=$this->db->update('users');
		
			return true;
	}

	

	function coinpaymentdetails($data,$uid)
	{
		$getCoinPaymentData=$this->db->query("SELECT * FROM user_payment_info  where unique_id='".$uid."' and is_active = 1")->result();
		if(count($getCoinPaymentData) > 0){
			$this->db->set('is_active',0);
			$this->db->where('unique_id', $uid);
			$updateStatus=$this->db->update('user_payment_info');

		}
			$this->db->insert('user_payment_info', $data);
			return true;
		
	}
	function getcoinpaymentdetails($uid)
	{
			$getCPaymentData=$this->db->query("SELECT * FROM `user_payment_info` inner join `coin` ON coin.coin_id=user_payment_info.coin_id where user_payment_info.unique_id='".$uid."' and user_payment_info.is_active = 1")->row();
			if ($getCPaymentData){
				return $getCPaymentData;
			}else{
				return  '';
			}

		
	}
	function getcoin()
	{
			$getcoinData=$this->db->query("SELECT * FROM coin where is_active=1")->result();
			return $getcoinData;
		
	}

	function getbankDetails($uid)
	{
		$getBankDetails=$this->db->query("SELECT * FROM bank_details where unique_id='".$uid."' and status = 1")->row();
		if ($getBankDetails){
			return $getBankDetails;
		}else{
			return  null;
		}
	}

	public function getKycAttachment($unique_id){
		$checkKycAttachment=$this->db->query("SELECT * FROM kyc_attachment where user_id='".$unique_id."'")->row();
		return $checkKycAttachment;
	}

	public function getUserInfo($unique_id){
		$getUserInfo=$this->db->query("SELECT * FROM users where unique_id='".$unique_id."'")->row();
		return $getUserInfo;
	}
}	
?>
