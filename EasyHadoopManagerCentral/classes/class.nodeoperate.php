<?php

class Node extends Socket
{
	private $cAgentRunShell = "RunShellScript";
	
	public function HadoopStart($pHost, $pRole)
	{
		$this->mHost = $pHost;
		$this->mCommand = $this->cAgentRunShell.":hadoop-daemon.sh start ".$pRole;
		
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		return $str;
	}
	
	public function HadoopStop($pHost, $pRole)
	{
		$this->mHost = $pHost;
		$this->mCommand = $this->cAgentRunShell.":hadoop-daemon.sh start ".$pRole;
		
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		return $str;
	}
	
	public function HadoopRestart($pHost, $pRole)
	{
		$this->mHost = $pHost;
		$this->mCommand = $this->cAgentRunShell.":hadoop-daemon.sh stop ".$pRole;
		
		if($this->SocketCommand())
		{
			$str1 = $this->mReturn;
		}
		sleep(3);
		$this->mHost = $pHost;
		$this->mCommand = $this->cAgentRunShell.":hadoop-daemon.sh start ".$pRole;
		
		if($this->SocketCommand())
		{
			$str2 = $this->mReturn;
		}
		$ret = $str1.$str2;
		
		return $ret;
	}
}

?>