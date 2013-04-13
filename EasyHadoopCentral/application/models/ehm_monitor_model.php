<?php

class Ehm_monitor_model extends CI_Model
{
	public $ehm_host;// ip address
	public $ehm_port;
	public $socket;
	public $transport;
	public $protocol;
	public $ehm;

	public function __construct()
	{ 
		parent::__construct();
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/EasyHadoop/EasyHadoop.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TTransport.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
	}
	
	public function get_jobtracker_jmx($host, $qry = "")
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetJmx($host, $this->config->item('jobtracker_port'), $qry);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_tasktracker_jmx($host, $qry = "")
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetJmx($host, $this->config->item('tasktracker_port'), $qry);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_namenode_jmx($host, $qry = "")
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$str = $this->ehm->GetJmx($host, $this->config->item('namenode_port'), $qry);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}

	public function get_datanode_jmx($host, $qry = "")
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$str = $this->ehm->GetJmx($host, $this->config->item('datanode_port'), $qry);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}

	public function get_secondarynamenode_jmx($host, $qry = "")
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$str = $this->ehm->GetJmx($host, $this->config->item('secondarynamenode_port'), $qry);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace('\\','',$str);
	}

	public function get_host_meminfo($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetMemInfo();
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	public function get_host_cpuinfo($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetCpuInfo();
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	public function get_host_cpuinfo_detail($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$this->load->model('ehm_installation_model', 'install');
		$ver = $this->install->get_sys_version($host);
		
		if($ver == "5")
		{
			$command = 'mpstat 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"idle\":"$10",\"intrs\":"$11"}"}\'';
		}
		elseif($ver == "6")
		{
			$command = 'mpstat 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"guest\":"$10",\"idle\":"$11"}"}\'';
		}
		elseif($ver == "ubuntu")
		{
			$command = 'mpstat 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"guest\":"$10",\"idle\":"$11"}"}\'';
		}
		else
		{
			return "False";
		}
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_host_cpuinfo_core_detail($host, $cores)//CPU cores total number
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$this->load->model('ehm_installation_model', 'install');
		$ver = $this->install->get_sys_version($host);
		
		try
		{
			$this->transport->open();
			for($i = 0; $i < $cores; $i++)
			{
				if($ver == "5")
				{
					$command = 'mpstat -P '.$i.' 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"idle\":"$10",\"intrs\":"$11"}"}\'';
				}
				elseif($ver == "6")
				{
					$command = 'mpstat -P '.$i.' 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"guest\":"$10",\"idle\":"$11"}"}\'';
				}
				elseif($ver == "ubuntu")
				{
					$command = 'mpstat -P '.$i.' 1 1 | tail -n 1 | awk \'{print "{\"user\":"$3",\"nice\":"$4",\"sys\":"$5",\"iowait\":"$6",\"irq\":"$7",\"soft\":"$8",\"steal\":"$9",\"guest\":"$10",\"idle\":"$11"}"}\'';
				}
				else
				{
					return "False";
				}
				$str = $this->ehm->RunCommand($command);
				$cpu[$i] = $str;
			}
			$this->transport->close();
			$str = json_encode($cpu);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_host_loadavginfo($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetLoadAvg();
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	//NetInfo still can not be used on centos 5.x yet, but can be used on centos 6.x
	public function get_host_netinfo($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetIfInfo();
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	public function get_process_id($host, $role)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		
		$grep = "proc_".$role;
		$command = "ps aux | grep ".$grep." | grep -v grep | awk '{print $2}'";
		try
		{
			$this->transport->open();
			$process_id = $this->ehm->RunCommand($command);
			if($process_id > 0)
			{
				$str = '{"role":"'.$role.'","process_id":"'.trim($process_id).'","status":"online"}';
			}
			else
			{
				$str = '{"role":"'.$role.'","process_id":0, "status":"offline"}';
			}
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function check_agent_alive($host)
	{
		if(@$fp = fsockopen($host,$this->config->item('ehm_port'),$errstr,$errno,5))
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
	
	public function get_tracker_html($host, $port, $url)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->GetTrackerHtml($host, $port, $url);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
}

?>