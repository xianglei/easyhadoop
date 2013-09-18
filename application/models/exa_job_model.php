<?php

class Exa_job_model extends CI_Model
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

	public function ListJob()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'. $jt->ssh_pass .'" | sudo -S -u mapred /usr/bin/hadoop job -list 2>/dev/null | sed "1,2d" | awk \'{print $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22,$23,$24,$25,$26,$27,$28,$29}\'';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop job -list 2>/dev/null | sed "1,2d" | awk \'{print $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22,$23,$24,$25,$26,$27,$28,$29}\'';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return substr($str,0,-1);
	}
	
	public function ListJobJson()
	{
		$str = $this->ListJob();
		if(trim($str) != "")
		{
			$rows = explode("\n", $str);
			$cols = array();
			$i = 0;
			foreach($rows as $row)
			{
				$tmp = explode(" ",$row);
				$cols[$i]['job_id'] = $tmp[0];
				$cols[$i]['state'] = $tmp[1];
				$cols[$i]['start_time'] = $tmp[2];
				$cols[$i]['start_time_datetime'] = date("Y-m-d H:i:s", substr($tmp[2],0,-3));
				$cols[$i]['username'] = $tmp[3];
				$cols[$i]['priority'] = $tmp[4];
				array_shift($tmp);
				array_shift($tmp);
				array_shift($tmp);
				array_shift($tmp);
				array_shift($tmp);
				$cols[$i]['scheduling_info'] = implode(" ", $tmp);
				$i++;
			}
			return json_encode($cols);
		}
		else
		{
			return FALSE;
		}
	}
	
	public function KillJob($pJobId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop job -kill ' . $pJobId . ' 2>/dev/null | sed "1d"';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop job -kill ' . $pJobId . ' 2>/dev/null | sed "1d"';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function JobStatus($pJobId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop job -status ' . $pJobId . ' 2>/dev/null | sed "1d"';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop job -status ' . $pJobId . ' 2>/dev/null | sed "1d"';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function SetPriority($pJobId, $pPriority = 'NORMAL')
	{
		//Priority = "VERY_HIGH HIGH NORMAL LOW VERY_LOW"
		
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop job -set-priority ' . $pJobId . ' '.$pPriority . ' 2 > /dev/null | sed "1d"';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop job -set-priority ' . $pJobId . ' '.$pPriority . ' 2 > /dev/null | sed "1d"';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function SafemodeGet()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop mradmin -safemode get';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop mradmin -safemode get';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function SafemodeEnter()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop mradmin -safemode enter';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop mradmin -safemode enter';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function SafemodeLeave()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		$pSudoPassword = $jt->ssh_pass;
		$pSudo = ($jt->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop mradmin -safemode leave';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop mradmin -safemode leave';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function MRAdminRefreshNodes()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($jt->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$jt->ssh_pass.'" | sudo -S -u mapred /usr/bin/hadoop mradmin -refreshNodes';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop mradmin -refreshNodes';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function MRAdminRefreshQueues()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($jt->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$jt->ssh_pass.'" | sudo -S -u mapred /usr/bin/hadoop mradmin -refreshQueues';
		}
		else
		{
			$cmd = 'sudo -u mapred /usr/bin/hadoop mradmin -refreshQueues';
		}
		
		try
		{
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}

}

?>