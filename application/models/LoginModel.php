<?php  
 class LoginModel extends CI_Model
    {  
      function can_login($username, $decode_password)
        {  
          // fetch by email first
          //$this->db->where('pan', $pan);
          $where = '(email='."'$username'".' or mobile ='."'$username'".') and is_deleted = 0';
          $this->db->where($where);
          $query_hashedpassword = $this->db->get('users');
          $result_hashedpassword = $query_hashedpassword->row_array(); // get the row first

			if($query_hashedpassword->num_rows() > 0){
              $hashed_password = $result_hashedpassword['password'];
              $hashed_pin = $result_hashedpassword['pin'];
              if(($hashed_password == $decode_password) OR ($hashed_pin == $decode_password))  
               {  
                    return true;  
               }  
               else  
               {  
                    return false;       
               } 
                 
          }else{
              return false; 
          }
       }
       
       public function update_firstlogin($pan) {
            $data = array(
            	'firstlogin' => '1'
            	);
            $this->db->set($data);
            $this->db->where('pan', $pan);
            $this->db->update('users');
        }

	 function reset($password,$uniqueid)
	 {
		 $rawpwd 			= openssl_encrypt($this->security->xss_clean($this->input->post('password')),"AES-128-ECB",'password');

		 // check password
		 if($password){
			 $data = array(
				 'password' => md5($password),
				 'raw_pwd'	=>$rawpwd,
			 );

			 $this->db->set($data);
			 $this->db->where('unique_id', $uniqueid);
			 $this->db->update('users');

			 $this->session->set_flashdata('smsg', 'Password has been Changed Successfully. Please Login'); //set success msg
			 return true;

		 }else{
			 $this->session->set_flashdata('fmsg', 'Failed to Change.'); //set success msg
			 return false;
		 }
	 }
    }
