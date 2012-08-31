<?php

class User extends Mysql
{
	public $mRole;
	
	public function AuthUser($pUsername, $pPassword)
	{
		$sql = "select * from ehm_user where username = '".$pUsername."' and password = '".md5($pPassword)."'";
		$this->Query($sql);
		$arr = $this->FetchArray();
		if($arr['role'] == "")
		{
			return FALSE;
		}
		else
		{
			$this->mRole = $arr['role'];
			return TRUE;
		}
	}
	
	public function ChangePassword($pUsername,$pPassword,$pNewPassword)
	{
		if($this->AuthUser($pUsername, $pPassword))
		{
			$sql = "update ehm_user set password = '".md5($pNewPassword)."' where username = '".$pUsername."'";
			$this->Query($sql);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>