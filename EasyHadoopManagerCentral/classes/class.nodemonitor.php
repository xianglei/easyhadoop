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
}

?>