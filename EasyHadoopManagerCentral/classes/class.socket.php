<?php

class Socket
{
	public $mCommand;
	public $mHost;
	
	public function SocketCommand()
	{
		if($fp = @fsockopen($this->mHost, 30050, $errstr, $errno, 300))
		{
			echo $pCommand;
			fwrite($fp, $this->mCommand."\n");
			while (!feof($fp))
			{
				$str .= fread($fp,1024);
			}
			fclose($fp);
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
}

?>