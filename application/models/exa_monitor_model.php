<?php
class Exa_monitor_model extends CI_Model
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
		
		$this->load->database();
	}
	
	public function MemStats($pHost)
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_http_port');
		
		$token = $this->config->item('token');
		try
		{
			$url = 'http://'.$pHost.':'.$this->exa_port.'/node/GetMemInfo/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	public function CpuStats($pHost)
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_http_port');
		
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$pHost.':'.$this->exa_port.'/node/GetCpuInfo/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return str_replace("'","\"",$str);
	}
	
	public function CpuDetail($pHost)
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_http_port');
		
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$pHost.':'.$this->exa_port.'/node/GetCpuDetail/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function LoadAverage($pHost)
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_http_port');
		
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$pHost.':'.$this->exa_port.'/node/GetLoadAvg/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function NetTrafficStats($pHost)
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_http_port');
		
		$token = $this->config->item('token');
		
		try
		{
			$url = 'http://'.$pHost.':'.$this->exa_port.'/node/GetNetTraffic/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function GetJobtrackerJmx($qry = "")
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetJobtracker();
		
		$this->exa_host = $node->ip;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/jmx/GetJmx/'.$token.'/'.$this->exa_host.'/'.$this->config->item('jobtracker_port').'/'.$qry;
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
	
	public function GetTasktrackerJmx($host, $qry = "")
	{
		$this->exa_host = $host;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$host.':'.$this->exa_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('tasktracker_port').'/'.$qry;
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
	
	public function GetNamenodeJmx($qry = "")
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetNamenode();
		
		$this->exa_host = $node->ip;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/jmx/GetJmx/'.$token.'/'.$this->exa_host.'/'.$this->config->item('namenode_port').'/'.$qry;
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
	
	public function GetDatanodeJmx($host, $qry = "")
	{
		$this->exa_host = $host;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$host.':'.$this->exa_port.'/jmx/GetJmx/'.$token.'/'.$host.'/'.$this->config->item('datanode_port').'/';
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
	
	public function GetSecondaryNamenodeJmx($qry = "")
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetNamenode();
		
		$this->exa_host = $node->ip;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/jmx/GetJmx/'.$token.'/'.$this->exa_host.'/'.$this->config->item('secondarynamenode_port').'/';
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
}
?>