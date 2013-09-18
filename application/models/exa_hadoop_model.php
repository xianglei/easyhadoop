<?php
class Exa_hadoop_model extends CI_Model
{
	public $exa_host;
	public $exa_port;
	public $socket;
	public $transport;
	public $protocol;
	public $exa;
	
	public function __construct()
	{ 
		parent::__construct();
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/Exadoop/Exadoop.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TTransport.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
	}
	
	public function HadoopControl($pHost, $pRole, $pOperate, $pSudo = FALSE, $pSudoUser = "", $pSudoPassword = "")
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(10000);
		$this->socket->setRecvTimeout(10000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		switch($pRole)
		{
			case "namenode":
				$user = "hdfs";
				break;
			case "secondarynamenode":
				$user = "hdfs";
				break;
			case "datanode":
				$user = "hdfs";
				break;
			case "jobtracker":
				$user = "mapred";
				break;
			case "tasktracker":
				$user = "mapred";
				break;
		}
		
		try
		{
			$this->transport->open();
			if($pSudo == TRUE)
			{
				$cmd = 'echo "' . $pSudoPassword . '" | sudo -S -u ' . $user . ' /usr/sbin/hadoop-daemon.sh '. $pOperate .' ' . $pRole;
			}
			else
			{
				$cmd = 'sudo -u ' . $user . ' /usr/sbin/hadoop-daemon.sh ' . $pOperate . ' ' . $pRole;
			}
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function ViewHadoopLogs($pHost, $pHostname, $pRole, $pSudo = FALSE, $pSudoUser = '', $pSudoPassword = '')
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(10000);
		$this->socket->setRecvTimeout(10000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		switch($pRole)
		{
			case "namenode":
				$user = "hdfs";
				break;
			case "secondarynamenode":
				$user = "hdfs";
				break;
			case "datanode":
				$user = "hdfs";
				break;
			case "jobtracker":
				$user = "mapred";
				break;
			case "tasktracker":
				$user = "mapred";
				break;
		}
		
		try
		{
			$this->transport->open();
			if($pSudo == TRUE)
			{
				$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u '.$user.' tail -n 200 /var/log/hadoop/'.$user.'/hadoop-'.$user.'-'.$pRole.'-'.$pHostname.'.log';
			}
			else
			{
				$cmd = 'tail -n 200 /var/log/hadoop/'.$user.'/hadoop-'.$user.'-'.$pRole.'-'.$pHostname.'.log';
			}
			$str = $this->exa->RunCommand($cmd);
			
			$str = str_replace('ERROR', "<b><font color=red>ERROR</font></b>",$str);
			$str = str_replace('FATAL', "<b><font color=red>FATAL</font></b>",$str);
			$str = str_replace('WARN', "<b><font color=orange>WARN</font></b>",$str);
			
			$tmp = explode("\n", $str);
			$tmp = array_reverse($tmp);
			$str = implode("\n", $tmp);
			
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function GetPID($pIp, $pRole)
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/node/GetRolePID/'.$token.'/'.$pRole;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
}
?>