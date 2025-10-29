<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insertTicket($data)
	{
		if($this->db->insert('tickets', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function insertTicketFeedback($data)
	{
		if($this->db->insert('tickets_feedback', $data))
		{
			return  $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	function updateTicket($data,$userModel=''){
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

	public function getTicketListAll($userId,$status=1){
		if ($userId){
			$result		=$this->db->query("SELECT * FROM `tickets` as tk
						LEFT JOIN users 
						ON users.user_id=tk.user_id 
						WHERE tk.user_id=$userId and tk.status=$status")->result(); //open
		}else{
			$result		=$this->db->query("SELECT * FROM `tickets` as tk
						LEFT JOIN users 
						ON users.user_id=tk.user_id 
						WHERE tk.status=$status")->result();
		}
		if ($result) {
			return $result;
		}else{
			return array();
		}
	}
	public function getSingleTicketById($id){
		$result		=$this->db->query("SELECT tickets.*,users.first_name,users.last_name FROM `tickets` LEFT JOIN users ON users.user_id=tickets.user_id WHERE tickets.id=$id")->row(); //open
		return $result;
	}
}

