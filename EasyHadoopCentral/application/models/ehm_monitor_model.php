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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$host.':'.$this->ehm_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('jobtracker_port').'/Hadoop:service=JobTracker,name=JobTrackerMetrics';
		try
		{
			$str = file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$host.':'.$this->ehm_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('tasktracker_port').'/';
		try
		{
			$str = file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		$url = 'http://'.$host.':'.$this->ehm_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('namenode_port').'/Hadoop:service=NameNode,name=NameNodeInfo';
		try
		{
			$str = file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$host.':'.$this->ehm_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('datanode_port').'/';
		try
		{
			$str = file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		$url = 'http://'.$host.':'.$this->ehm_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('secondarynamenode_port').'/';
		try
		{
			$str = file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetMemInfo/'.$token;
			$str = @file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetCpuInfo/'.$token;
			$str = @file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetCpuDetail/'.$token;
			$str = @file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetCpuCoreDetail/'.$token;
			$str = @file_get_contents($url);
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetLoadAvg/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
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
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetRolePID/'.$token.'/'.$role;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function check_agent_alive($host)
	{
		if(@$fp = fsockopen($host,$this->config->item('agent_thrift_port'),$errstr,$errno,5))
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
		$this->ehm_port = $this->config->item('agent_http_port');
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
	
	public function get_network_traffic($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$host.':'.$this->ehm_port.'/node/GetIfTraffic/'.$token.'/';
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;// json
	}
}

?>