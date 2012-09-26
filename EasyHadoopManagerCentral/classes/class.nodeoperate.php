<?php

class NodeOperator extends EasyHadoopClient
{
	public function ChangeHddUser($pHost, $pMountPoint)
	{
		$command = "chown -R hadoop:hadoop ".$pMountPoint;
		$str = $this->RunCommand($command);
		return $str;
	}
	
	public function GetHddList()
	{

		$command = "df -h | grep -v mapper | grep -w -v / | grep -v -w /boot | grep -v -w /lib | grep -v -w /lib64 | grep -v -w /sbin | grep -v -w /proc | grep -v -w /sys | grep -v -w /var | grep -v -w /bin | grep -v -w /root | grep -v -w /home | grep -v -w /selinux | grep -v -w /usr | awk '{print $1,$2,$3,$4,$5,$6}'";
		$str = $this->RunCommand($command);
		return $str;
	}
	
	public function HadoopStart($pRole)
	{
		$command = "sudo -u hadoop hadoop-daemon.sh start ".$pRole;
		
		$str = $this->RunCommand($command);
		return $str;
	}
	
	public function HadoopStop($pRole)
	{
		$command = "sudo -u hadoop hadoop-daemon.sh stop ".$pRole;
		
		$str = $this->RunCommand($command);
		return $str;
	}
	
	public function HadoopRestart($pRole)
	{
		$command = "sudo -u hadoop hadoop-daemon.sh stop ".$pRole;
		
		$str1 = $this->RunCommand($command);
		sleep(1);
		$command = "sudo -u hadoop hadoop-daemon.sh start ".$pRole;
		
		$str2 = $this->RunCommand($command);
		
		$ret = $str1.$str2;
		return $ret;
	}
	
	public function ViewLogs($pHost, $pRole, $pHostname)
	{
		$command = "tail -n 1000 /var/log/hadoop/hadoop/hadoop-hadoop-".$pRole."-".$pHostname.".log";
		$str = $this->RunCommand($command);
		
		$str = str_replace('ERROR', "<b><font color=red>ERROR</font></b>",$str);
		$str = str_replace('WARN', "<b><font color=orange>WARN</font></b>",$str);
		return $str;
	}
	
	public function FormatNamenode($pHost)
	{
		$command = "Y|sudo -u hadoop hadoop namenode -format";
		$str = $this->RunCommand($command);
		
		return $str;
	}
}

?>