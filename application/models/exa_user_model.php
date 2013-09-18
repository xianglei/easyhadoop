<?php
/**
* 
*/
class Exa_user_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function CheckLogin($value)
	{
		if(get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		if(!is_numeric($value))
		{
			$value = mysql_real_escape_string($value);
		}
		return $value;
	}
	
	public function UpdatePassword($login)
	{
		if($login == TRUE)
		{
			$old_password = $this->input->post('old_password');
			$new_password1 = $this->input->post('new_password1');
			$new_password2 = $this->input->post('new_password2');
			$sql = "select * from exa_user where username='admin' and password='".md5($old_password)."'";
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
					$sql = "update exa_user set password='".md5($new_password1)."' where username='admin'";
					if($this->db->simple_query($sql))
						return '{"status":"Update success"}';
					else
						return '{"status":"Update failed"}';
				}
			}
		}
		else
		{
			return '{"status":"Not logged in}';
		}
	}
	
	public function Logout()
	{
		$this->session->sess_destroy();
	}
	
	public function Login($username, $password)
	{
		$username = self::CheckLogin(htmlspecialchars($username));
		$password = self::CheckLogin(htmlspecialchars($password));
		$sql = "select * from exa_user where username='".$username."' and password='".md5($password)."'";
		$result = $this->db->query($sql);
		$query = $result->result();
		if($result->num_rows() > 0)
		{
			$user_array = array('role'=>$query[0]->role, 'login'=>TRUE);
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