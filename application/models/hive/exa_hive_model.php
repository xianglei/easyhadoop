<?php

class Exa_hive_model extends CI_Model
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
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/Exadoop/Exadoop.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TTransport.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
		
		$this->load->database();
	}

	public function Operate($pIp, $pOperate, $pSudoPass, $pArgv) //pOperate = start or stop, $pArgv = hiveserver or hiveserver2 or hwi
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		try
		{
			if($pOperate == "start")
			{
				$cmd = 'cd /tmp/ && echo "'.$pSudoPass.'" | sudo -S -u hive nohup hive --service '. $pArgv . ' & 2 > /dev/null';
			}
			else
			{
				$cmd = 'cd /tmp/ && echo "'.$pSudoPass.'" | sudo -S ps aux | grep hive | awk \'{print $1,$2}\' | grep hive | awk \'{print $2}\' | xargs kill -9';
			}
			$this->transport->open();
			$ret = $this->exa->RunCommand($cmd);
			
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}

}

?>