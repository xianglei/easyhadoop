<?php

class NodeMonitor extends Socket
{
	private $cAgentRunShell = "RunShellScript";
	
	public function GetJson($pHost,$pRole)
	{
		switch ($pRole)
		{
			case 'namenode':
				$port = "50070";
				break;
			case 'datanode':
				$port = '50075';
				break;
			case 'jobtracker':
				$port = '50030';
				break;
			case 'tasktracker':
				$port = '50060';
				break;
			case 'secondarynamenode':
				$port = '50090';
				break;
			
			default:
				return FALSE;
				break;
		}
		
		$fp = fopen("http://".$pHost.":".$port, "r");
		while(!feof($fp))
		{
			$json .= $fread($fp,1024);
		}
		fclose($fp);
		
		$arr = $this->ParseJson($json);
		
		return $arr;
	}
	
	public function ParseJson($pJson)
	{
		$arr = json_decode($pJson);
		return $arr;
	}
	
	public function JumpGanglia($pUrl)
	{
		$str = "<script>this.location='".$pUrl."';</script>";
	}
	
	public function CheckHadoopProcess($pHost, $pRole)
	{
		$this->mHost = $pHost;
		switch ($pRole)
		{
			case "namenode":
				$jps = "NameNode";
				break;
			case "jobtracker":
				$jps = "jobtracker";
				break;
			case "secondarynamenode":
				$jps = "secondarynamenode";
				break;
			case "datanode":
				$jps = "datanode";
				break;
			case "tasktracker":
				$jps = "tasktracker";
				break;
			default:
				return "Unknown Role name";
				break;
		}
		
		$command = "ps aux | grep -w ".$jps." | grep -v grep | awk '{print $2}'";
		$this->mCommand = $this->cAgentRunShell.":".$command;
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = $lang['notConnected'];
		}
		return $str;
	}
	
}

?>