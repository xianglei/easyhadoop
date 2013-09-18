<?php
class Exa_linux_model extends CI_Model
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
	
	public function HddChown($pHost, $pMountPoint, $pUser, $pRole, $pSudo = FALSE, $pSudoUser = '', $pSudoPassword = '')
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
		
		if($pSudo == TRUE)
		{
			$cmd = 'echo "'.$pSudoPassword.'" | sudo -S chown -R '.$user.':hadoop '. $pMountPoint ;
		}
		else
		{
			$cmd = 'chown -R '.$user.':hadoop ' . $pMountPoint;
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