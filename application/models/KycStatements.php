<?php  
 class KycStatements extends CI_Model
    {
       public function saveKycAttachment($data) {
       
		   $getAttachment=$this->db->query("SELECT* FROM kyc_attachment where user_id='".$data['user_id']."'")->row();
		   if ($getAttachment){
			   $this->db->set($data);
			   $this->db->where('user_id', $data['user_id']);
			   $this->db->update('kyc_attachment');
			   return 1;
		   }else {
			   if ($this->db->insert('kyc_attachment', $data)) {
				   return $this->db->insert_id();
			   } else {
				   return false;
			   }
		   }
        }

	 public function updateIdentityVerifiedStatus($status,$user_id) {
		 $data = array(
			 'identity_verified_status' =>$status
		 );
		 $this->db->set($data);
		 $this->db->where('user_id', $user_id);
		 $this->db->update('kyc_attachment');

		 return true;
	 }

	 public function updatedResidencyVerifiedBackStatus($status,$user_id) {
		 $data = array(
			 'residency_proof_back_status' =>$status
		 );
		 $this->db->set($data);
		 $this->db->where('user_id', $user_id);
		 $this->db->update('kyc_attachment');

		 return true;
	 }

	 public function updatedResidencyVerifiedStatusBack($status,$user_id) {
		 $data = array(
			 'residency_proof_back_status' =>$status
		 );
		 $this->db->set($data);
		 $this->db->where('user_id', $user_id);
		 $this->db->update('kyc_attachment');

		 return true;
	 }

	 public function updatedResidencyVerifiedStatus($status,$user_id) {
		 $data = array(
			 'residency_verified_status' =>$status
		 );
		 $this->db->set($data);
		 $this->db->where('user_id', $user_id);
		 $this->db->update('kyc_attachment');

		 return true;
	 }

	 public function changeIbStatus($data,$user_id) {
		 $this->db->set($data);
		 $this->db->where('unique_id', $user_id);
		 $this->db->update('users');

		 return true;
	 }

    }
