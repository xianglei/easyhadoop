<?php

class NodeMonitor
{
	public function GetMemInfo($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$json = $client->GetMemInfo();
		return $json;
	}
	
	public function GetCpuInfo($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$json = $client->GetMemInfo();
		return $json;
	}
	
	public function GetNetInfo($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$json = $client->GetNetInfo();
		return $json;
	}
	
	public function GetLoadAvg($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$json = $client->GetLoadAvg();
		return $json;
	}
	
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
		$fp = @fopen($url, "r");
		while(!@feof($fp))
		{
			$json .= @fread($fp,1024);
		}
		@fclose($fp);
		
		$arr = $this->ParseJson($json);
		
		return $arr;
	}
	
	public function ParseJson($pJson)
	{
		$arr = json_decode($pJson);
		return $arr;
	}
	
	public function GetJsonObject($pObjArray,$pKeyword)
	{
		foreach($pObjArray as $k => $v)
		{
			if($v->{$pKeyword} > 0)
			{
				break;
			}
		}
		return $v->{$pKeyword};
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
			@fclose($fp);
			return TRUE;
		}
		else
		{
			@fclose($fp);
			return FALSE;
		}
	}
	
	public function GetRealSize($size)
	{ 
		$kb = 1024;         // Kilobyte
		$mb = 1024 * $kb;   // Megabyte
		$gb = 1024 * $mb;   // Gigabyte
		$tb = 1024 * $gb;   // Terabyte
		$pb = 1024 * $tb;	// Pettabyte
		$eb = 1024 * $pb;	// Exabyte
		$zb = 1024 * $eb;	// Zettabyte
		$yb = 1024 * $zb;	// Yottabyte
		$bb = 1024 * $yb;	// Brontobyte

		if($size < $kb)
		{ 
			return $size." B";
		}
		elseif($size < $mb)
		{ 
			return round($size/$kb,2)." KB";
		}
		elseif($size < $gb)
		{ 
			return round($size/$mb,2)." MB";
		}
		elseif($size < $tb)
		{ 
			return round($size/$gb,2)." GB";
		}
		elseif($size < $pb)
		{ 
			return round($size/$tb,2)." TB";
		}
		elseif($size < $eb)
		{ 
			return round($size/$pb,2)." PB";
		}
		elseif($size < $zb)
		{ 
			return round($size/$eb,2)." EB";
		}
		elseif($size < $yb)
		{ 
			return round($size/$zb,2)." ZB";
		}
		elseif($size < $bb)
		{ 
			return round($size/$yb,2)." YB";
		}
		else
		{ 
			return round($size/$bb,2)." BB";
		}
	}
	
}

?>