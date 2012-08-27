<?php

class Socket
{
	private $mSocketHandle;
	
	public function Connect($pIp,$pPort,$pTimeOut)
	{
		if($fp = fsockopen($pIp, $pPort, $errstr, $errno, $pTimeOut))
		{
			$this->mSocketHandle = $fp;
		}
		else
		{
			return FLASE;
		}
	}
	
	public function SendCommand($pCommand)
	{
		if(is_resource($this->mSocketHandle))
		{
			fwrite($this->mSocketHandle, $pCommand."\n");
			$str = $this->ReadAll();
			
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function ReadAll()
	{
		if(is_resource($this->mSocketHandle))
		{
			while(!feof($this->mSocketHandle))
			{
				$str .= fread($fp,1024);
			}
			
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function ReadOne()
	{
		if(is_resource($this->mSocketHandle))
		{
			$str = fread($this->mSocketHandle,4096);
			
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function DisConnect()
	{
		if(is_resource($this->mSocketHandle)):
			fclose($this->mSocketHandle);
		else:
			return FALSE;
		endif;
	}
}

?>