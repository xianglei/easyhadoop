<?php
class Exa_hdfs_model extends CI_Model
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
	
	public function Chown($pFolder, $pUser, $pGroup, $pRecursive = TRUE)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pRecursive == TRUE)
		{
			$r = " -R ";
		}
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -chown '.$r.' '.$pUser.':'.$pGroup.' '. $pFolder;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -chown '.$r.' '.$pUser.':'.$pGroup.' '.$pFolder;
		}
		echo $cmd;
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
	
	public function Chmod($pFolder, $pMod, $pRecursive = TRUE)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pRecursive == TRUE)
		{
			$r = " -R ";
		}
		else
		{
			$r = " ";
		}
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -chmod '.$r.' '.$pMod.' '. $pFolder;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -chmod '.$r.' '.$pMod.' '.$pFolder;
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
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop dfsadmin -safemode get';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop dfsadmin -safemode get';
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
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop dfsadmin -safemode enter';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop dfsadmin -safemode enter';
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
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop dfsadmin -safemode leave';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop dfsadmin -safemode leave';
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
	
	public function LsJson($pFolder = "")
	{
		$str = $this->Ls($pFolder);
		if(trim($str) != "")
		{
			$rows = explode("\n",$str);
			$cols = array();
			$i = 0;
			foreach($rows as $row)
			{
				$tmp = explode(" ",$row);
				$cols[$i]['upfolder'] = $tmp[6]."/../";
				$cols[$i]['priv'] = $tmp[0];
				$cols[$i]['user'] = $tmp[1];
				$cols[$i]['group'] = $tmp[2];
				$cols[$i]['size'] = $tmp[3];
				$cols[$i]['date'] = $tmp[4];
				$cols[$i]['time'] = $tmp[5];
				$cols[$i]['name'] = $tmp[6];
				$i++;
			}
			return json_encode($cols);
		}
		else
		{
			$json = '[{"upfolder":"", "priv":"", "user":"", "group":"", "size":"", "date":"", "time":"", "name":""}]';
			return $json;
		}
	}
	
	public function Ls($pFolder = "")
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pFolder == '')
		{
			$pFolder = '/';
		}
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -ls '.$pFolder . ' 2>/dev/null | sed "1d" |  awk \'{print $1,$3,$4,$5,$6,$7,$8}\'';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -ls '.$pFolder. ' 2>/dev/null | sed "1d" |  awk \'{print $1,$3,$4,$5,$6,$7,$8}\'';
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
	
	public function Rm($pHost, $pFile, $pSudo = FALSE, $pSudoUser = '', $pSudoPassword = '')//$pFile, 需要传入HDFS绝对地址
	{
		$this->exa_host = $pHost;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -rm '.$pFile;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -rm '.$pFile;
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
	
	public function Rmr($pFolder)
	{
	
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -rmr '.$pFolder;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -rmr '.$pFolder;
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
	
	public function Mkdir($pFolder)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -mkdir '.$pFolder;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -mkdir '.$pFolder;
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
	
	public function Mv($pSrc, $pDest)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$pSudoPassword = $nn->ssh_pass;
		$pSudo = ($nn->is_sudo == 0) ? FALSE : TRUE;
		
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
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u hdfs /usr/bin/hadoop fs -mv '.$pSrc. ' ' . $pDest;
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop fs -mv '.$pSrc. ' ' . $pDest;
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
	
	public function FormatNamenode()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($this->nodes->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S; echo "Y" |sudo -u hdfs /usr/bin/hadoop namenode -format';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop namenode -format';
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
	
	public function DFSAdminReport()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($nn->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S -u hdfs /usr/bin/hadoop dfsadmin -report';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop dfsadmin -report';
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
	
	public function DFSAdminRefreshNodes()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($nn->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S -u hdfs /usr/bin/hadoop dfsadmin -refreshNodes';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/bin/hadoop dfsadmin -refreshNodes';
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
	
	public function StartBalancer()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($nn->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S -u hdfs /usr/sbin/hadoop-daemon.sh --config $HADOOP_CONF_DIR start balancer $@';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/sbin/hadoop-daemon.sh --config $HADOOP_CONF_DIR start balancer $@';
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
	
	public function StopBalancer()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($nn->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S -u hdfs /usr/sbin/hadoop-daemon.sh --config $HADOOP_CONF_DIR stop balancer $@';
		}
		else
		{
			$cmd = 'sudo -u hdfs /usr/sbin/hadoop-daemon.sh --config $HADOOP_CONF_DIR stop balancer $@';
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
	
	public function TailBalancerLog()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$ip = $nn->ip;
		$hostname = $nn->hostname;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$sudo = ($nn->is_sudo != "0") ? TRUE : FALSE;
		if($sudo == TRUE)
		{
			$cmd = 'echo "'.$nn->ssh_pass.'" | sudo -S -u hdfs tail -n200 /var/log/hadoop/hdfs/hadoop-hdfs-balancer-'.$hostname.'.log';
		}
		else
		{
			$cmd = 'sudo -u hdfs tail -n200 /var/log/hadoop/hdfs/hadoop-hdfs-balancer-'.$hostname.'.log';
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