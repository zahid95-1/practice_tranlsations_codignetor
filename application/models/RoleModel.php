<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class RoleModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	function insertRole($data)
	{

		if (isset($data['role_id'])){
            $dataItem=array('role_name'=>$data['role_name'],'status'=>$data['status']);
			$roleId	=$data['role_id'];
			unset($data['role_id']);
			$this->db->set($dataItem);
			$this->db->where('role_id', $roleId);
			$updateStatus=$this->db->update('roles');
			if($updateStatus)
			{
				return  true;
			}
			else
			{
				return false;
			}
		}else {
            $dataItem=array('role_name'=>$data['role_name'],'status'=>1);
			if ($this->db->insert('roles', $dataItem)) {
				return $this->db->insert_id();
			} else {
				return false;
			}
		}
	}

	public function getSingleRole($roleId=""){
		$result='';
		if ($roleId) {
			$result		=$this->db->query("SELECT * FROM `roles` where role_id='" . $roleId . "' ORDER BY created_at DESC")->row();
		}else{
			$result		=$this->db->query("SELECT * FROM `roles` ORDER BY created_at DESC")->result();
		}
		return $result;
	}
}
