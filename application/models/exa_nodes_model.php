<?php
class Exa_nodes_model extends CI_Model
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

	public function CreateNode($pIp, $pPort, $pOs, $pUser, $pPass, $pSudo, $pRack, 
							$pNamenode = 0, $pDatanode = 0, $pSecondaryNamenode = 0,
							$pJobtracker = 0, $pTasktracker = 0)
	{
		$this->load->model('Exa_ssh_model', 'ssh');
		$this->ssh->Connect($pIp, $pPort);
		$this->ssh->AuthPassword($pUser, $pPass);
		
		$cmd = 'hostname';
		$hostname = trim($this->ssh->Execute($cmd));
		
		switch($pOs)
		{
			case "ubuntu":
				$agent = 'NodeAgent-1.2.1_1-amd64.deb';
				$cmd = 'dpkg -i ';
				break;
			case "centos5":
				$agent = 'NodeAgent-1.2.1-1.el5.x86_64.rpm';
				$cmd = 'rpm -Uvh ';
				break;
			case "centos6":
				$agent = 'NodeAgent-1.2.1-1.el6.x86_64.rpm';
				$cmd = 'rpm -Uvh ';
				break;
			case "redhat5":
				$agent = 'NodeAgent-1.2.1-1.el5.x86_64.rpm';
				$cmd = 'rpm -Uvh ';
				break;
			case "redhat6":
				$agent = 'NodeAgent-1.2.1-1.el6.x86_64.rpm';
				$cmd = 'rpm -Uvh ';
				break;
		}
		$src = $this->config->item('src_folder'). "../" . $agent;
		@ini_set('memory_limit', '-1');
		$content = file_get_contents($src);
		$dest = $this->config->item('dest_folder') . $agent;
		
		$this->ssh->SCP($dest, $content);
		
		$this->ssh->Execute($cmd . $dest);
		
		//$hostname = 'localhost';//temp testing only
		$sql = "insert exa_nodes set hostname = '".$hostname."', ip = '".$pIp."', rack = '".$pRack."', ssh_port = '".$pPort."', ssh_user = '".$pUser."', ssh_pass = '".$pPass."',os = '". $pOs."', is_sudo = ". $pSudo. ", namenode = ". $pNamenode.", datanode = ". $pDatanode. ", secondarynamenode = ". $pSecondaryNamenode. ", jobtracker = ". $pJobtracker . ", tasktracker = ". $pTasktracker . ", create_time = NOW()";
		if ($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function EditNode($pId, $pIp, $pPort, $pOs, $pUser, $pPass, $pSudo, $pRack, 
							$pNamenode = 0, $pDatanode = 0, $pSecondaryNamenode = 0,
							$pJobtracker = 0, $pTasktracker = 0)//Update Node
	{
		$this->load->model('Exa_ssh_model', 'ssh');
		$this->ssh->Connect($pIp, $pPort);
		$this->ssh->AuthPassword($pUser, $pPass);
		
		$cmd = 'hostname';
		$hostname = trim($this->ssh->Execute($cmd));
		
		//$hostname = 'localhost';// temp test only
		$sql = "update exa_nodes set hostname = '".$hostname."', ip = '".$pIp."', rack = '".$pRack."', ssh_port = '".$pPort."', ssh_user = '".$pUser."', ssh_pass = '".$pPass."',os = '". $pOs."', is_sudo = ". $pSudo. ", namenode = ". $pNamenode.", datanode = ". $pDatanode. ", secondarynamenode = ". $pSecondaryNamenode. ", jobtracker = ". $pJobtracker . ", tasktracker = ". $pTasktracker . ", create_time = NOW() where id = " . $pId;
		if ($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function GetNode($pId)
	{
		$sql = "select * from exa_nodes where id = ". $pId;
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result[0];
	}
	
	public function ListNodes()
	{
		$sql = "select * from exa_nodes order by id asc";
		$qry = $this->db->query($sql);
		return $qry->result();
	}
	
	public function PaginationNodes($limit = "20", $offset = "0")
	{
		if ($query = $this->db->get('exa_nodes', $limit, $offset)):
			return $query->result(); // Return value is an objected matrix
		else:
			return FALSE;
		endif;
	}
	
	public function GetNamenode()
	{
		$sql = "select * from exa_nodes where namenode = 1";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result[0];
	}
	
	public function GetJobtracker()
	{
		$sql = "select * from exa_nodes where jobtracker = 1";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result[0];
	}
	
	public function GetSecondaryNamenode()
	{
		$sql = "select * from exa_nodes where secondarynamenode = 1";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result[0];
	}
	
	public function GetDatanodesList($limit, $offset)
	{
		$sql = 'select * from exa_nodes where datanode = 1 limit '.$offset.', '.$limit;
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	
	public function CountDatanodes()
	{
		$sql = 'select * from exa_nodes where datanode = 1';
		$qry = $this->db->query($sql);
		$num = $qry->num_rows();
		return $num;
	}
	
	public function GetTasktrackersList($limit, $offset)
	{
		$sql = 'select * from exa_nodes where tasktracker = 1 limit '.$offset.', '.$limit;
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	
	public function CountTasktrackers()
	{
		$sql = 'select * from exa_nodes where tasktracker = 1';
		$qry = $this->db->query($sql);
		$num = $qry->num_rows();
		return $num;
	}
	
	public function CountNodes()
	{
		$count = $this->db->count_all_results('exa_nodes');
		return $count;
	}
	
	public function RemoveNode($pId)
	{
		$sql = "delete from exa_nodes where id = ".$pId;
		$this->db->simple_query($sql);
		$sql = 'delete from exa_nodes_storage where node_id = ' . $pId;
		$this->db->simple_query($sql);
	}
	
	public function SetMountPoint($pNodeId, $pIp, $pMountPoint = array(), $pSudo = FALSE, $pSudoUser = '', $pSudoPassword = '')
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		if($pMountPoint[0] != "")//如果勾选的硬盘checkbox不为空
		{
			$cmd = "";
			$cmd_hdfs = "";
			$cmd_mapred = "";
			$mount_name = "";
			$mount_sname = "";
			$mount_data = "";
			$mount_mrlocal = "";
			$mount_mrsystem = "";
			$str = "";
			for($i = 0; $i < count($pMountPoint); $i++)
			{
				if($pMountPoint[$i] == "/")
				{
					$pMountPoint[$i] = '';
				}
				$mount_name .= $pMountPoint[$i] . "/dfs/name,";
				$mount_sname .= $pMountPoint[$i] . "/dfs/snn,";
				$mount_data .= $pMountPoint[$i] . "/dfs/data,";
				if($pSudo == FALSE)
				{
					$cmd_hdfs .= "mkdir -p ".$pMountPoint[$i]."/dfs; " . "chown -R hdfs:hadoop ".$pMountPoint[$i]. "/dfs;";
				}
				else
				{
					$cmd_hdfs .= "echo \"". $pSudoPassword ."\" | sudo -S mkdir -p ".$pMountPoint[$i]."/dfs; " . "chown -R hdfs:hadoop ".$pMountPoint[$i]. "/dfs;";
				}
			}
			
			for($i = 0; $i < count($pMountPoint); $i++)
			{
				if($pMountPoint[$i] == "/")
				{
					$pMountPoint[$i] = '';
				}
				$mount_mrlocal .= $pMountPoint[$i] . "/mapred/local,";
				$mount_mrsystem .= $pMountPoint[$i] . "/mapred/system,";
				if($pSudo == FALSE)
				{
					$cmd_mapred .= "mkdir -p ".$pMountPoint[$i]."/mapred; " . "chown -R mapred:hadoop ".$pMountPoint[$i]. "/mapred;";
				}
				else
				{
					$cmd_mapred .= "echo \"". $pSudoPassword ."\" | sudo -S mkdir -p ".$pMountPoint[$i]."/mapred; " . "chown -R mapred:hadoop ".$pMountPoint[$i]. "/mapred;";
				}
			}
			
			echo $cmd = $cmd_hdfs . $cmd_mapred;
			
			$mount_name = substr($mount_name,0,-1);
			$mount_sname = substr($mount_sname,0,-1);
			$mount_data = substr($mount_data,0,-1);
			$mount_mrlocal = substr($mount_mrlocal,0,-1);
			$mount_mrsystem = substr($mount_mrsystem,0,-1);
			
			$sql = "select node_id from exa_nodes_storage where node_id = '". $pNodeId ."'";
			$qry = $this->db->query($sql);
			$count = $qry->num_rows();
			
			if($count == 0)
			{
				$sql = "insert ";
				$where = '';
			}
			else
			{
				$sql = "update ";
				$where = 'where node_id = "'.$pNodeId.'"';
			}
			$sql = $sql . " exa_nodes_storage set node_id = '".$pNodeId."', nn_storage = '".$mount_name."', dn_storage = '".$mount_data."', local_storage = '". $mount_mrlocal ."', system_storage = '". $mount_mrsystem ."', snn_storage = '". $mount_sname ."'" . $where;
			$this->db->simple_query($sql);
			
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
		}
		else//如果勾选的硬盘checkbox为空
		{
			$sql = "select node_id,id from exa_nodes_storage where node_id = '". $pNodeId ."'";//选择已有的mount记录
			$qry = $this->db->query($sql);
			$count = $qry->num_rows();
			if($count == 0)//如果不存在现有记录
			{
				$str = "Empty mount list";
			}
			else
			{
				$result = $qry->result();
				$id = $result[0]->id;
				$sql = "delete from exa_nodes_storage where id = '".$id."'";//删除现有记录
				$this->db->simple_query($sql);
				$str = "";
			}
		}
		return $str;
	}
	
	public function GetMountPoint($pIp)
	{
		$this->exa_host = $pIp;
		$this->exa_port = $this->config->item('agent_http_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$token = $this->config->item('token');
		
		$url = 'http://'.$this->exa_host.':'.$this->exa_port.'/node/GetHddInfo/'.$token;
		try
		{
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}

		return $str;
	}

	public function GetSavedMountPoint($pIp)
	{
		$sql = "select a.ip, a.id,b.nn_storage from exa_nodes a, exa_nodes_storage b where a.ip = '".$pIp."' and a.id = b.node_id";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		if($qry->num_rows != 0)//如果有mount记录
		{
			$result = $result[0];
			$mount = $result->nn_storage;
			if($mount != "")
			{
				$tmp = explode(",", $mount);//按逗号拆分硬盘$tmp = array("/dfs/name","/data/dfs/name","/data1/dfs/name");
				$i = 0;
				foreach($tmp as $mnt)
				{
					$tmp2 = explode("/",$mnt);//按/拆分路径$tmp2 = array("","dfs","name"); $tmp2 = array("", "data","dfs","name");
					array_pop($tmp2);//去掉name$tmp2 = array("","dfs");
					array_pop($tmp2);//去掉dfs $tmp2 = array("");
					$a =  join("/",$tmp2);//拼新路径 $a = "", $a = "/data";
					if($a == "")
					{
						$b[$i] = '/';
					}
					else
					{
						$b[$i] = $a;
					}
					$i++;
				}
				$json['mounted_at'] = $b;
			}
			else
			{
				$json['mounted_at'] = '';
			}
		}
		else//没有mount记录
		{
			$json['mounted_at'] = '';
		}
		$str = json_encode($json);
		return $str;
	}
}
?>