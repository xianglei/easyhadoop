<?php

class Ehm_user_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function update_password()
	{
		$old_password = $this->input->post('old_password');
		$new_password1 = $this->input->post('new_password1');
		$new_password2 = $this->input->post('new_password2');
		$sql = "select * from ehm_user where username='admin' and password='".md5($old_password)."'";
		$result = $this->db->query($sql);
		if($result->num_rows() == 0)
		{
			return '{"status":"Old password not matched"}';
		}
		else
		{
			if($new_password1 != $new_password2)
			{
				return '{"status":"New password not matched"}';
			}
			else
			{
				$sql = "update ehm_user set password='".md5($new_password1)."' where username='admin'";
				$this->db->simple_query($sql);
				return '{"status":"Update success"}';
			}
		}
	}
	
	public function log_out()
	{
		$this->session->sess_destroy();
	}
	
	public function login($username, $password)
	{
		$sql = "select * from ehm_user where username='".$username."' and password='".md5($password)."'";
		$result = $this->db->query($sql);
		$query = $result->result();
		if($result->num_rows() > 0)
		{
			$user_array = array('role'=>$query->role, 'login'=>TRUE);
			$this->session->set_userdata($user_array);
			return TRUE;
		}
		else
		{
			$user_array = array('login'=>FALSE);
			$this->session->set_userdata($user_array);
			return FALSE;
		}
	}

}

?>