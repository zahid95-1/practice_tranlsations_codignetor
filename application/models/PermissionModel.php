<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class PermissionModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	public function checkExistPermission($userId='',$actionName=''){
		$getAuthData	=$this->db->query("SELECT * FROM `sub_modules` sub
						INNER JOIN auth
						ON auth.sub_modules_id=sub.id
						WHERE `action_name` LIKE '%$actionName%' and user_id=$userId and status=1")->row();
		if ($getAuthData) {
			return true;
		}else{
			return  false;
		}
	}

	public function getAccesModiulesName($userId=''){
		$getModiulesName	=$this->db->query("SELECT auth.id,auth.sub_modules_id,modules.slug_name FROM `auth` 
									INNER JOIN modules
									ON modules.id=auth.modules_id
									WHERE auth.user_id=$userId AND auth.status=1 GROUP BY auth.modules_id")->result_array();

		return $getModiulesName;
	}

	public function getSubmodules($subId='',$userId=''){
		if ($subId) {
			$getSubmodules = $this->db->query("SELECT route from `sub_modules` where id=$subId")->row();
		}else{
			$getSubmodules=$this->db->query("SELECT sub_modules.route FROM `auth` 
											INNER JOIN sub_modules
											ON sub_modules.id=auth.sub_modules_id
											WHERE auth.user_id=$userId AND auth.status=1")->result_array();
		}
		return $getSubmodules;
	}
}
