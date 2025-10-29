<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BulkEmailController extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('RegisterModel');
		$this->load->model('EmailConfigModel');
	}


	public function send(){
		$getUserList = $this->db->query("select user_id,email,first_name,last_name,unique_id from users where email_send_status=0 limit 100")->result();

//		foreach($getUserList as $key=>$item){
//
//			$mailHtml 	=$this->EmailConfigModel->resetLink($item->unique_id,$item->first_name.' '.$item->last_name);
//			$email		=$item->email;
//
//			//$email='keshriedutech@gmail.com';
//			$checkStatus=self::sendEmail($email,'Reset your '.ConfigData['site_name'].' CRM password',$mailHtml);
//
//			if($checkStatus){
//				$updateItem=array('email_send_status'=>1);
//				$this->db->set($updateItem);
//				$this->db->where('user_id', $item->user_id);
//				$updateStatus=$this->db->update('users');
//				echo "Send Email To".$email; echo "<pre/>";
//			}else{
//				echo "Fail";
//			}
//		}

	}


	public function sendEmail($toEmail,$subject,$htmlContent)
	{
		$companyName =ConfigData['m_company_name'];
		//Load email library
		$this->load->library('email');


		//SMTP & mail configuration
		$config = $this
			->EmailConfigModel
			->getConfig();

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		// Recipient
		//$to ='aiubzahid@gmail.com';
		$to =$toEmail;

		$mailFrom=$this
			->EmailConfigModel
			->getFromEmail();

		$this->email->from($mailFrom,$companyName);
		$this->email->to($to);
		//$this->email->cc('keshriedutech@gmail.com');
		//$this->email->bcc('iambommanakavya@gmail.com');
		$this->email->subject($subject);
		$this->email->message($htmlContent);


		if($this->email->send()){
			return 1;
		}
	}
}
