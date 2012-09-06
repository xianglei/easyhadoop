<?php

class Socket
{
	protected $mCommand;
	protected $mHost;
	public $mReturn;
	protected $mForceClose;
	
	protected function SocketCommand()
	{
		//global $lang;
		if($fp = @fsockopen($this->mHost, 30050, $errstr, $errno, 30))
		{
			$this->mCommand = $this->EncryptCommand();
			fwrite($fp, $this->mCommand."\n");
			if($this->mForceClose == TRUE)
			{
				$str .= fread($fp,1024);
			}
			else
			{
				while (!feof($fp))
				{
					$str .= fread($fp,1024);
				}
			}
			fclose($fp);
			$this->mReturn = $str;
			return TRUE;
		}
		else
		{
			//$this->mReturn = $lang['notConnected'];
			return FALSE;
		}
	}
	
	public function SocketConnectTest($pHost)
	{
		if($fp = @fsockopen($pHost, 30050, $errstr, $errno, 30))
		{
			fclose($fp);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function EncryptCommand($pCmd = "")
	{
		if($pCmd != "")
		{
			$this->mCommand = $pCmd;
		}
		$cmd = $this->mCommand;
		
		$key = "\x88";
		$len1 = strlen($cmd);
		$len2 = strlen($key);
		if($len1 > $len2) $key = str_repeat($key, ceil($len1 / $len2));
		return $cmd ^ $key;
	}
}

?>