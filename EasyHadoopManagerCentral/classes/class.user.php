<?php

class User extends Mysql
{
	public function AuthUser($pUsername, $pPassword)
	{
		$sql = "select count(*) from ehm_user where username = '".$pUsername."', password = '".md5($pPassword)."'";
		$mysql->Query($sql);
		$arr = $mysql->FetchArray();
		if($arr[0] == 0)
		{
			return FALSE;
		}
		else
		{
			return $arr['role'];
		}
	}
	
	public function ChangePassword($pUsername,$pPassword,$pNewPassword)
	{
		if($this->AuthUser($pUsername, $pPassword))
		{
			$sql = "update ehm_user set password = '".md5($pNewPassword)."' where username = '".$pUsername."'";
			$mysql->Query($sql);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>