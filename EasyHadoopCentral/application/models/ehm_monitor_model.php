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
	
	public function get_jobtracker_jmx($host)
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
			$str = $this->ehm->GetJmx($host, $this->config->item('jobtracker_port'));
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_tasktracker_jmx($host)
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
			$str = $this->ehm->GetJmx($host, $this->config->item('tasktracker_port'));
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function get_namenode_jmx($host)
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
			$str = $this->ehm->GetJmx($host, $this->config->item('namenode_port'));
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}

	public function get_datanode_jmx($host)
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
			$str = $this->ehm->GetJmx($host, $this->config->item('datanode_port'));
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}

	public function get_secondarynamenode_jmx($host)
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
			$str = $this->ehm->GetJmx($host, $this->config->item('secondarynamenode_port'));
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
		
		$command = "mpstat";
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($command);
			$tmp_line = explode("\n", $str);
			$cpu_info = $tmp_line[3];
			$tmp_columns = explode(' ', $cpu_info);
			
			$i = 0;
			$detail_column = "";
			foreach ($tmp_columns as $v)
			{
				if($v != "")
				{
					$detail_column[$i] = $v;
					$i++;
				}
			}
			$cpu['user'] = $detail_column[3];
			$cpu['nice'] = $detail_column[4];
			$cpu['sys'] = $detail_column[5];
			$cpu['iowait'] = $detail_column[6];
			$cpu['irq'] = $detail_column[7];
			$cpu['soft'] = $detail_column[8];
			$cpu['steal'] = $detail_column[9];
			$cpu['idle'] = $detail_column[10];
			
			$str = json_encode($cpu);
			
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
		
		try
		{
			$this->transport->open();
			for($i = 0; $i < $cores; $i++)
			{
				$command = "mpstat -P ".$i;
				$str = $this->ehm->RunCommand($command);
				
				$tmp_line = explode("\n", $str);
				$cpu_info = $tmp_line[3];
				$tmp_columns = explode(' ', $cpu_info);
			
				$j = 0;
				$detail_column = "";
				foreach ($tmp_columns as $v)
				{
					if($v != "")
					{
						$detail_column[$j] = $v;
						$j++;
					}
				}
				$cpu['user'][$i] = $detail_column[3];
				$cpu['nice'][$i] = $detail_column[4];
				$cpu['sys'][$i] = $detail_column[5];
				$cpu['iowait'][$i] = $detail_column[6];
				$cpu['irq'][$i] = $detail_column[7];
				$cpu['soft'][$i] = $detail_column[8];
				$cpu['steal'][$i] = $detail_column[9];
				$cpu['idle'][$i] = $detail_column[10];
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