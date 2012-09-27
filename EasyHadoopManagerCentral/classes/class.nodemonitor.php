<?php

class NodeMonitor
{
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
		$url = "http://".$pHost.":".$port."/jmx";
		$fp = fopen($url, "r");
		while(!feof($fp))
		{
			$json .= fread($fp,1024);
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
	
	public function CheckHadoopProcess($pRole, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		switch ($pRole)
		{
			case "namenode":
				$jps = "namenode";
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
		#$this->mCommand = $this->cAgentRunShell.":".$command;
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function CheckAgentAlive($pHost, $pPort)
	{
		if(@$fp = fsockopen($pHost,$pPort,$errstr,$errno,5))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
}

?>