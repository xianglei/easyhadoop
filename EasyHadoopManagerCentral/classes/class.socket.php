<?php

class Socket
{
	protected $mCommand;
	protected $mHost;
	public $mReturn;
	
	public function SocketCommand()
	{
		global $lang;
		if($fp = @fsockopen($this->mHost, 30050, $errstr, $errno, 300))
		{
			fwrite($fp, $this->mCommand."\n");
			while (!feof($fp))
			{
				$str .= fread($fp,1024);
			}
			fclose($fp);
			$this->mReturn = $str;
			return TRUE;
		}
		else
		{
			$this->mReturn = $lang['notConnected'];
			return FALSE;
		}
	}
}

?>