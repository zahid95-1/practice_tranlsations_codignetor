<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class RegisterModel extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function checkDuplicateMobile($mobile)
	{
		$this->db->select('mobile');
		$this->db->from('users');
		$this->db->like('mobile', $mobile);
		return $this->db->count_all_results();
	}
	
	function checkDuplicateEmail($email)
	{
		$this->db->select('email');
		$this->db->from('users');
		$this->db->like('email', $email);
		return $this->db->count_all_results();
	}
	
	function insertUser($data)
	{
		if($this->db->insert('users', $data))
		{
		    $getmaxId = $this->db->query("SELECT user_id,parent_id FROM `users` WHERE user_id = (SELECT max(user_id) FROM `users`)");
                foreach($getmaxId->result() as $maxId){
                        $Id = $maxId->user_id ;  
                        $parent_id = $maxId->parent_id;
                }
                
            $getparentId = $this->db->query("SELECT user_id,parent_id,unique_id FROM `users` WHERE unique_id = '$parent_id'");
              
                foreach($getparentId->result() as $parentId){
                        $parent_Id = $parentId->user_id ; 
                        
                }
            
			
			
			 /*=====================storing level Info===============*/
			$getlevelparentId = $this->db->query("SELECT * FROM `users` WHERE user_id =$Id")->row();
			$uid = $getlevelparentId->unique_id;
			$level = 1;
			
			
			while($getlevelparentId->parent_id <> NULL){
			    $parentIID = $getlevelparentId->parent_id;
			    $getlevelroleId = $this->db->query("SELECT * FROM `users` WHERE unique_id ='$parentIID'")->row();
			    $parentRole = $getlevelroleId->role;

			    $dataL = array(
                'user_id' => $uid,
                'upline_id' => $getlevelparentId->parent_id,
                'level_no' =>$level,
                'ref_percentage' => 0,
                'created_by' => $Id,
                'created_datetime' => date('d/m/Y H:i:s')
                );
                $this->db->insert('user_level_info',$dataL);
                $this->db->insert('user_level_info_log',$dataL);
                $getlevelparentId = $this->db->query("SELECT * FROM `users` WHERE unique_id = '".$getlevelparentId->parent_id."'")->row();
                
                
                $level++;
			}
			/*=====================end storing level Info===============*/
			
			
		    
			 
			$dataI = array(
            'id' => $Id,
            'parent_id' => $parent_Id
            );
            $this->db->insert('pctable',$dataI);
			return  $this->db->insert_id();
				
		}
		else
		{
			return false;
		}
	}

	function updateUpline($userID,$uplineID)
	{
		$checkcon_1 = $this->db->query("SELECT * FROM `users` where parent_id = '$userID'")->result();
		$checkcon_2 = $this->db->query("SELECT * FROM `ib_commission` where unique_id = '$userID'")->result();
		$checkcon_3 = $this->db->query("SELECT * FROM `ib_calculation`where ibcommission_to = '$userID'")->result();



		if((count($checkcon_1) == 0) && (count($checkcon_2) == 0) && (count($checkcon_2) == 0) ){
		$dataU = array("parent_id" => $uplineID);
			$this->db->set($dataU);
			$this->db->where('unique_id', $userID);
			$update=$this->db->update('users');


			$dataL = array("status" => 0);
			$this->db->set($dataL);
			$this->db->where('user_id', $userID);
			$update=$this->db->update('user_level_info_log');

			$this->db->query("DELETE FROM `user_level_info` where user_id = '$userID'");


		    $getmaxId = $this->db->query("SELECT user_id,parent_id FROM users WHERE unique_id = '$userID'");
                foreach($getmaxId->result() as $maxId){
                        $Id = $maxId->user_id ;  
                        $parent_id = $maxId->parent_id;
                }
                
            $getparentId = $this->db->query("SELECT user_id,parent_id,unique_id FROM `users` WHERE unique_id = '$parent_id'");
              
                foreach($getparentId->result() as $parentId){
                        $parent_Id = $parentId->user_id ; 
                        
                }
            
			
			
			 /*=====================storing level Info===============*/
			$getlevelparentId = $this->db->query("SELECT * FROM `users` WHERE user_id = '$Id'")->row();
			$uid = $getlevelparentId->unique_id;
			$level = 1;
			
			
			while($getlevelparentId->parent_id <> NULL){
			    $parentIID = $getlevelparentId->parent_id;
			    
			  
			   
			    $dataL = array(
                'user_id' => $uid,
                'upline_id' => $parentIID,
                'level_no' =>$level,
                'ref_percentage' => 0,
                'created_by' => $Id,
                'created_datetime' => date('d/m/Y H:i:s')
                );
                $this->db->insert('user_level_info',$dataL);
                $this->db->insert('user_level_info_log',$dataL);
                $getlevelparentId = $this->db->query("SELECT * FROM `users` WHERE unique_id = '".$getlevelparentId->parent_id."'")->row();
                
                
                $level++;
			}
		}
			/*=====================end storing level Info===============*/
		
	}
	
		public function blankKycUploadList(){
		$getAttachment=$this->db->query("SELECT users.first_name, users.last_name, users.email, users.mobile, users.unique_id, users.user_id,kyc_attachment.* FROM `users`
										LEFT JOIN kyc_attachment
										ON kyc_attachment.user_id=users.unique_id
										WHERE kyc_attachment.residency_proof IS NULL and kyc_attachment.identity_proof IS  NULL");

		$total_items	=$getAttachment->num_rows();

		$result=array(
			'total_pages'=>$total_items,
			'current_page'=>1,
			'kyc_list'=>array(),
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

			$resultItem 		= $this->db->query("SELECT users.first_name, users.last_name, users.email, users.mobile, users.unique_id, users.user_id,kyc_attachment.* FROM `users`
										LEFT JOIN kyc_attachment
										ON kyc_attachment.user_id=users.unique_id
										WHERE kyc_attachment.residency_proof IS NULL and kyc_attachment.identity_proof IS  NULL LIMIT $start_item,$items_per_page")->result();

			$result=array(
				'total_pages'=>$total_items,
				'current_page'=>$current_page,
				'kyc_list'=>$resultItem,
			);

		}

		return $result;
	}

	public function getKycList(){
		$getAttachment=$this->db->query("SELECT kyc_attachment.*, users.first_name, users.last_name, users.email, users.mobile, users.unique_id, users.user_id
										FROM kyc_attachment
										LEFT JOIN users ON users.unique_id = kyc_attachment.user_id
										WHERE kyc_attachment.user_id IS NOT NULL
										ORDER BY kyc_attachment.id DESC");

		$total_items	=$getAttachment->num_rows();

		$result=array(
			'total_pages'=>$total_items,
			'current_page'=>1,
			'kyc_list'=>array(),
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

			$resultItem 		= $this->db->query("SELECT kyc_attachment.*, users.first_name, users.last_name, users.email, users.mobile, users.unique_id, users.user_id
										FROM kyc_attachment
										LEFT JOIN users ON users.unique_id = kyc_attachment.user_id
										WHERE kyc_attachment.user_id IS NOT NULL
										ORDER BY kyc_attachment.id DESC LIMIT $start_item,$items_per_page")->result();

			$result=array(
				'total_pages'=>$total_items,
				'current_page'=>$current_page,
				'kyc_list'=>$resultItem,
			);

		}

		return $result;
	}


	public function getData($start, $length,$searchValue = null) {


		$this->db->select('MDV_Registered_Account.*, CONCAT(MDV_Registered_Account.first_name, " ", MDV_Registered_Account.last_name) AS full_name,
    	DATE_FORMAT(MDV_Registered_Account.created_datetime, "%m/%d/%Y %H:%i:%s") AS formatted_created_datetime,
    	SUM(payments.entered_amount) as totalPayment');
		$this->db->from('MDV_Registered_Account');
		$this->db->join('payments', 'MDV_Registered_Account.user_id = payments.user_id', 'left');
		$this->db->group_by('MDV_Registered_Account.user_id');

		//$this->db->join('users', 'users.user_id = activity_log.user_id', 'left');

		if (isset($_REQUEST['startDate']) && !empty($_REQUEST['startDate']) && isset($_REQUEST['endDate']) && !empty($_REQUEST['endDate'])) {
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) >=', $_REQUEST['startDate']);
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) <=', $_REQUEST['endDate']);
		}

		// Apply search filter
		if ($searchValue) {
			$this->db->like('MDV_Registered_Account.email', $searchValue);
			$this->db->or_like('MDV_Registered_Account.first_name', $searchValue);
			$this->db->or_like('MDV_Registered_Account.last_name', $searchValue);
		}

		$this->db->order_by('MDV_Registered_Account.created_datetime', 'desc');
		$this->db->limit($length, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function getTotalCount() {
		return $this->db->count_all('MDV_Registered_Account');
	}

	public function getFilteredCount() {
		$this->db->from('MDV_Registered_Account');

		// Add date range conditions if provided
		if (isset($_REQUEST['startDate']) && !empty($_REQUEST['startDate']) && isset($_REQUEST['endDate']) && !empty($_REQUEST['endDate'])) {
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) >=', $_REQUEST['startDate']);
			$this->db->where('DATE(MDV_Registered_Account.created_datetime) <=', $_REQUEST['endDate']);
		}

		// Implement your additional filtering logic if needed

		return $this->db->count_all_results();
	}

}	
