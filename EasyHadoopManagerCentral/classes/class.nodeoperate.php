<?php

class NodeOperator
{
	public function ChangeHddUser($pMountPoint, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "chown -R hadoop:hadoop ".$pMountPoint;
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function GetHddList($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "df -h | grep -v mapper | grep -w -v / | grep -v -w /boot | grep -v -w /lib | grep -v -w /lib64 | grep -v -w /sbin | grep -v -w /proc | grep -v -w /sys | grep -v -w /var | grep -v -w /bin | grep -v -w /root | grep -v -w /home | grep -v -w /selinux | grep -v -w /usr | awk '{print $1,$2,$3,$4,$5,$6}'";
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function HadoopStart($pRole, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "sudo -u hadoop hadoop-daemon.sh start ".$pRole;
		
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function HadoopStop($pRole, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "sudo -u hadoop hadoop-daemon.sh stop ".$pRole;
		
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function HadoopRestart($pRole , $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "sudo -u hadoop hadoop-daemon.sh stop ".$pRole;
		
		$str1 = $client->RunCommand($command);
		sleep(1);
		$command = "sudo -u hadoop hadoop-daemon.sh start ".$pRole;
		
		$str2 = $client->RunCommand($command);
		
		$ret = $str1.$str2;
		return $ret;
	}
	
	public function ViewLogs($pHost, $pRole, $pHostname, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "tail -n 1000 /var/log/hadoop/hadoop/hadoop-hadoop-".$pRole."-".$pHostname.".log";
		$str = $client->RunCommand($command);
		
		$str = str_replace('ERROR', "<b><font color=red>ERROR</font></b>",$str);
		$str = str_replace('WARN', "<b><font color=orange>WARN</font></b>",$str);
		return $str;
	}
	
	public function FormatNamenode($pHost, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "Y|sudo -u hadoop hadoop namenode -format";
		$str = $client->RunCommand($command);
		
		return $str;
	}
}

?>