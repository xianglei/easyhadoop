<?php
class Exa_mapred_model extends CI_Model
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
	
	public function SubmitJavaJob($pHost, $pSrcPath, $pJarFile, $pArguments)//SrcPath like /tmp/
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$ip = $jt->ip;
		
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(10000);
		$this->socket->setRecvTimeout(10000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		@ini_set('memory_limit', '-1');
		$content = read_file($pSrcPath.$pJarFile);
		
		try
		{
			$this->transport->open();
			$str = $this->exa->FileTransfer("/tmp/".$pJarFile, $content);
			unset ($content);
			if (trim($str) == ""):
				$str = 'filename: '. '/tmp/' . $pJarFile.' ==> status: success" ==> node: '.$host;
			else:
				$str = 'filename: '. '/tmp/' . $pJarFile.' ==> status: '.$str.' ==> node: '.$host;
			endif;
			if($pSudo == TRUE)
			{
				$cmd = 'echo "'.$pSudoPassword.'" | sudo -S -u mapred /usr/bin/hadoop jar /tmp/'.$pJarFile. ' ' . $pArguments;
			}
			else
			{
				$cmd = 'sudo -u mapred /usr/bin/hadoop jar /tmp/'.$pJarFile. ' ' . $pArguments;
			}
			$str .= $this->exa->RunCommand($cmd);
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