<?php
class Exa_install_model extends CI_Model
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
		$this->load->helper('file');
	}
	
	public function GetHadoopFileList()
	{
		$folder = $this->config->item('src_folder');
		$file_list_array = get_filenames($folder);
		return $file_list_array;
	}

	public function PushHadoopBin($pIp, $pFile)
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		@ini_set('memory_limit', '-1');
		$content = read_file($this->config->item('src_folder') . $pFile);
		
		try
		{
			$this->transport->open();
			$str = $this->exa->FileTransfer($this->config->item('dest_folder') . $pFile, $content);
			unset ($content);
			if (trim($str) == "")
			{
				$str = '{filename: "'.$this->config->item('dest_folder') . $pFile.'", status: "success", node: "'.$pIp.'"}';
			}
			else
			{
				$str = '{filename: "'.$this->config->item('dest_folder') . $pFile.'", status: "'.$str.'", node: "'.$pIp.'"}';
			}
			
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}

	public function GetDist($pIp)
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		try
		{
			$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/node/dist/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}

	public function InstallHadoopBin($pNodeId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetNode($pNodeId);
		$ip = $node->ip;
		$is_sudo = $node->is_sudo;
		$ssh_pass = $node->ssh_pass;
		$sudo = ($is_sudo == 0) ? FALSE : TRUE;
	
		$this->exa_host = $ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$token = $this->config->item('token');
		
		$sys_json = $this->GetDist($ip);
		$json = json_decode($sys_json,TRUE);
		
		$cmd = "";
		if($json['os.system'] == "centos" || $json['os.system'] == "redhat" || $json['os.system'] == "CentOS" || $json['os.system'] == "RedHat")
		{
			if(intval($json['os.version']) < 6)
			{
				if($sudo == TRUE)
				{
					$cmd = "echo \"".$ssh_pass. "\" | sudo -S chmod +x ".$this->config->item('dest_folder') . $this->config->item('bin_el5_filename'). " \n
					".$this->config->item('dest_folder')."/".$this->config->item('bin_el5_filename')." \n
					rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_el5_filename')
					."";
				}
				else
				{
					$cmd = "chmod +x ".$this->config->item('dest_folder') . $this->config->item('bin_el5_filename')." \n
					".$this->config->item('dest_folder')."/".$this->config->item('bin_el5_filename')." \n
					rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_el5_filename')
					."";
				}
			}
			elseif(intval($json['os.version']) >= 6)
			{
				if($sudo == TRUE)
				{
					$cmd = "echo \"".$ssh_pass."\" | sudo -S chmod +x ".$this->config->item('dest_folder'). $this->config->item('bin_el6_filename')." \n
					".$this->config->item('dest_folder')."/".$this->config->item('bin_el6_filename')." \n
					rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_el6_filename')
					."";
				}
				else
				{
					$cmd = "chmod +x ".$this->config->item('dest_folder') . $this->config->item('bin_el6_filename')." \n
					".$this->config->item('dest_folder')."/".$this->config->item('bin_el6_filename')." \n
					rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_el6_filename')
					."";
				}
			}
			else
			{
				return "Unsupport system version";
			}
		}
		elseif($json['os.system'] == "ubuntu" || $json['os.system'] == 'debian' || $json['os.system'] == "Ubuntu")
		{
			if($sudo == TRUE)
			{
				$cmd = "echo \"".$ssh_pass."\" | sudo -S chmod +x ". $this->config->item('dest_folder') . $this->config->item('bin_ubuntu_filename')." \n
				".$this->config->item('dest_folder')."/".$this->config->item('bin_ubuntu_filename')." \n
				rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_ubuntu_filename')
				."";
			}
			else
			{
				$cmd = "chmod +x ".$this->config->item('dest_folder') . $this->config->item('bin_ubuntu_filename')." \n
				".$this->config->item('dest_folder')."/".$this->config->item('bin_ubuntu_filename')." \n
				rm -f ".$this->config->item('dest_folder')."/".$this->config->item('bin_ubuntu_filename')
				."";
			}
		}
		elseif($json['os.system'] == "suse" || $json['os.system'] == "SuSE")
		{
			return "Will be supported soon";
		}
		else
		{
			return "Unknown system";
		}
		try
		{echo $cmd;
			$this->transport->open();
			$str = $this->exa->RunCommand($cmd);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
}
?>