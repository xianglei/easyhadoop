<?php

class Exa_settings_model extends CI_Model
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
	
	public function MakeEtcHosts()
	{
		$sql = 'select * from exa_nodes';
		
		$query = $this->db->query($sql);
		$str = "127.0.0.1\tlocalhost\n";
		foreach($query->result() as $result)
		{
			$str .= $result->ip . "\t" . $result->hostname . "\n";
		}
		return $str;
	}
	
	public function CountNodeSettings()
	{
		$sql = "select a.*,b.id from exa_nodes_settings a,exa_nodes b  where a.ip != '0' and a.ip = b.ip group by a.ip,a.filename";// select setiings belongs to the specified node
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}
	
	public function MakeHadoopSettings($pKey = array(), $pValue = array(), $pDesc = array(), $pIp = "0")
	{
		if(count($pKey) > 0)
		{
			$xml = "";
			$xml .= "<configuration>\n";
			
			for($i = 0; $i<count($pKey); $i++)
			{
				$xml .= "  <property>\n";
				$xml .= "    <name>". $pKey[$i] ."</name>\n";
				$xml .= "    <value>" . $this->ConvertHadoopSettings($pValue[$i], $pIp) . "</value>\n";
				$xml .= "    <description>" . $pDesc[$i] . "</description>\n";
				$xml .= "  </property>\n\n";
			}
			
			$xml .= "</configuration>\n";
		}
		
		$ret = array();
		$ret = $xml;
		
		return $ret;
	}
	
	public function GetSettingsById($pId)
	{
		$sql = "select * from exa_nodes_settings where id = ". $pId;
		if($query = $this->db->query($sql)):
			$result = $query->result();
			return $result[0];
		else:
			return FALSE;
		endif;
	}
	
	public function RemoveSettings($pId)
	{
		echo $sql = "delete from exa_nodes_settings where id=".$pId;
		if($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function CreateNodeSettings($filename, $content, $ip = '0')
	{
		$sql = "select * from exa_nodes_settings where ip = '".$ip."' and filename = '".$filename."'";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if($count == 0)
		{
			$sql = "insert exa_nodes_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."', ip='".$ip."'";
		}
		else
		{
			$sql = "update exa_nodes_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."',create_time=now() where ip = '".$ip."' and filename = '".$filename."'";
		}
		if($this->db->simple_query($sql)):
			$ret = TRUE;
		else:
			$ret = FALSE;
		endif;
		return $ret;
	}
	
	public function SaveEditSettings($set_id, $filename, $content, $ip)
	{
		$sql = "update exa_nodes_settings set filename='".$filename."', content = '".trim($content)."', ip='".$ip."', create_time=now() where id='".$set_id."'";
		if($this->db->simple_query($sql)):
			$ret = TRUE;
		else:
			$ret = FALSE;
		endif;
		return $ret;
	}
	
	public function ListNodeSettings($limit, $offset)
	{
		$sql = "select a.*,b.id as node_id from exa_nodes_settings a,exa_nodes b  where a.ip != '0' and a.ip = b.ip group by a.ip,a.filename limit ". $offset. ", ".$limit;
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function ListHadoopSettings($filename = "")
	{
		if("" == $filename)
			$sql = "select * from exa_hadoop_settings";
		else
			$sql = "select * from exa_hadoop_settings where filename='".$filename."'";
		
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function ListSettings($pIp = '0')
	{
		if($pIp != "0")
			$sql = 'select * from exa_nodes_settings where ip = "' . $pIp.'"';
		else
			$sql = 'select * from exa_nodes_settings where ip = \'0\'';
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function GetHadoopSettingsName()
	{
		$sql = "select distinct filename as filename from exa_hadoop_settings";
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	private function  get_hosts_field($data,$field,$default=''){
		if(!count($data)){
			return $default;
		}
		$obj = $data[0];
		return $obj->$field;
	}
	
	public function ConvertHadoopSettings($value = "", $pIp = "0")
	{
		if(preg_match('/(?<=\{)([^\}]*?)(?=\})/', $value))
		{
			switch($value)
			{
				case "hdfs://{namenode}:9000":
					$sql = "select hostname from exa_nodes where namenode = 1 ";
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = "hdfs://".$this->get_hosts_field($result,'hostname','localhost').":9000";
					break;
				case "{jobtracker}:9001":
					$sql = "select hostname from exa_nodes where jobtracker = 1";
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = $this->get_hosts_field($result,'hostname','localhost'). ":9001";
					break;
				case "{mount_snn}":
					$sql = "select * from exa_nodes_storage a, exa_nodes b where b.secondarynamenode = 1 and b.id = a.node_id ";
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = $this->get_hosts_field($result,'snn_storage');
					break;
				case "{mount_name}":
					$sql = "select * from exa_nodes_storage a, exa_nodes b where b.namenode = 1 and b.id = a.node_id ";
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = $this->get_hosts_field($result,'nn_storage');
					break;
				case "{mount_data}":
					if($pIp == "0")
					{
						$sql = "select * from exa_nodes_storage a, exa_nodes b where b.datanode = 1 and b.id = a.node_id";
					}
					else
					{
						$sql = "select * from exa_nodes_storage a, exa_nodes b where b.ip = '".$pIp."' and b.id = a.node_id";
					}
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = $this->get_hosts_field($result,'dn_storage');
					break;
				case "{mount_mrlocal}":
					if($pIp == "0")
					{
						$sql = "select * from exa_nodes_storage a, exa_nodes b where b.tasktracker = 1 and b.id = a.node_id";
					}
					else
					{
						$sql = "select * from exa_nodes_storage a, exa_nodes b where b.ip = '".$pIp."' and b.id = a.node_id";
					}
					$query = $this->db->query($sql);
					$result = $query->result();
					$return = $this->get_hosts_field($result,'local_storage');
					break;
				case "{mount_mrsystem}":
					$return = "/mapred/system";
					break;
			}
		}
		else
		{
			$return = $value;
		}
		return $return;
	}
	
	public function MakeRackAware()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$result = $this->nodes->ListNodes();
		$str = "";
		foreach($result as $item)
		{
			$str .= "	\"".$item->ip."\":\"".substr($item->rack, 1)."\",\n";
		}
		$str = substr($str,1);
		$str = "rack = {".$str."}";
		
		$rack = "#!/usr/bin/python
#-*-coding:UTF-8-*-
import sys

".$str."
if __name__==\"__main__\":
	print \"/\" + rack.get(sys.argv[1],\"default\")\n";
		unset($str);
		$str = "";
		$str['filename'] = $this->config->item('hadoop_conf_folder') . "RackAware.py";
		$str['chmod'] = "777";
		$str['content'] = $rack;
		
		return $str;
	}
	
	public function PushRackAware()
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$nn = $this->nodes->GetNamenode();
		$jt = $this->nodes->GetJobtracker();
		
		$this->exa_host = $nn->ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		$rack = $this->MakeRackAware();
		$content = $rack['content'];
		$filename = $rack['filename'];
		$chmod = $rack['chmod'];
		
		$str = "[";
		$ret = "";
		
		try
		{
			$this->transport->open();
			$this->exa->FileTransfer($filename, $content);
			$command = "chmod " . $chmod . " ".$filename;
			$ret = $this->exa->RunCommand($command);
			if (trim($ret) == ""):
				$str .= '{"filename":"'. $filename.'","status":"success","node":"'.$nn->ip.'","role":"namenode"},';
			else:
				$str .= '{"filename":"'. $filename.'","status":"failed","node":"'.$nn->ip.'","role":"namenode"},';
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		$this->exa_host = $jt->ip;
		$this->exa_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->exa_host, $this->exa_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->exa = new ExadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$this->exa->FileTransfer($filename, $content);
			$command = "chmod " . $chmod . " ".$filename;
			$ret = $this->exa->RunCommand($command);
			if (trim($ret) == ""):
				$str .= '{"filename":"'. $filename.'","status":"success","node":"'.$jt->ip.'","role":"jobtracker"}';
			else:
				$str .= '{"filename":"'. $filename.'","status":"failed","node":"'.$jt->ip.'","role":"jobtracker"}';
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str."]";
	}
	
	public function PushEtcHosts()
	{
		$str = '[';
		$this->load->model('exa_nodes_model', 'nodes');
		$list = $this->nodes->ListNodes();
		
		$filename = '/etc/hosts';
		$content = $this->MakeEtcHosts();
		
		foreach ($list as $node):
			$this->exa_host = $node->ip;
			$this->exa_port = $this->config->item('agent_thrift_port');
			$this->socket = new TSocket($this->exa_host, $this->exa_port);
			$this->socket->setSendTimeout(300000);
			$this->socket->setRecvTimeout(300000);
			$this->transport = new TBufferedTransport($this->socket);
			$this->protocol = new TBinaryProtocol($this->transport);
			$this->exa = new ExadoopClient($this->protocol);
			try
			{
				$this->transport->open();
				$ret = $this->exa->FileTransfer($filename, $content);
				if (trim($ret) == "OK" ):
					$str .= '{"filename":"'. $filename.'","status":"success","node":"'.$node->ip.'"},';
				else:
					$str .= '{"filename":"'. $filename.'","status":"failed","node":"'.$node->ip.'"},';
				endif;
				$this->transport->close();
			}
			catch(Exception $e)
			{
				$str = 'Caught exception: '.  $e->getMessage(). "\n";
			}
		endforeach;
		return substr($str,0,-1).']';
	}
	
	public function PushGlobalSettings()
	{
		$sql = "select * from exa_nodes_settings where ip = '0'";
		$qry = $this->db->query($sql);
		$sets = $qry->result();
		
		$this->load->model('exa_nodes_model', 'nodes');
		$list = $this->nodes->ListNodes();
		
		$str = "[";
		foreach($sets as $set)
		{
			$filename = $this->config->item('hadoop_conf_folder') . $set->filename;
			$content = $set->content;
			foreach($list as $node)
			{
				$this->exa_host = $node->ip;
				$this->exa_port = $this->config->item('agent_thrift_port');
				$this->socket = new TSocket($this->exa_host, $this->exa_port);
				$this->socket->setSendTimeout(300000);
				$this->socket->setRecvTimeout(300000);
				$this->transport = new TBufferedTransport($this->socket);
				$this->protocol = new TBinaryProtocol($this->transport);
				$this->exa = new ExadoopClient($this->protocol);
				try
				{
					$this->transport->open();
					$ret = $this->exa->FileTransfer($filename, $content);
					if (trim($ret) == "OK" ):
						$str .= '{"filename":"'. $filename.'","status":"success","node":"'.$node->ip.'"},';
					else:
						$str .= '{"filename":"'. $filename.'","status":"failed","node":"'.$node->ip.'"},';
					endif;
					$this->transport->close();
				}
				catch(Exception $e)
				{
					$str = 'Caught exception: '.  $e->getMessage(). "\n";
				}
			}
		}
		
		return substr($str,0,-1).']';
	}
	
	public function PushNodeSettings()
	{
		$sql = "select * from exa_nodes_settings where ip != '0'";
		$qry = $this->db->query($sql);
		$sets = $qry->result();
		
		$str = "[";
		foreach($sets as $set)
		{
			$ip = $set->ip;
			$filename = $this->config->item('hadoop_conf_folder') . $set->filename;
			$content = $set->content;
			
			$this->exa_host = $ip;
			$this->exa_port = $this->config->item('agent_thrift_port');
			$this->socket = new TSocket($this->exa_host, $this->exa_port);
			$this->socket->setSendTimeout(300000);
			$this->socket->setRecvTimeout(300000);
			$this->transport = new TBufferedTransport($this->socket);
			$this->protocol = new TBinaryProtocol($this->transport);
			$this->exa = new ExadoopClient($this->protocol);
			try
			{
				$this->transport->open();
				$ret = $this->exa->FileTransfer($filename, $content);
				if (trim($ret) == "OK" ):
					$str .= '{"filename":"'. $filename.'","status":"success","node":"'.$ip.'"},';
				else:
					$str .= '{"filename":"'. $filename.'","status":"failed","node":"'.$ip.'"},';
				endif;
				$this->transport->close();
			}
			catch(Exception $e)
			{
				$str = 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		
		return substr($str,0,-1).']';
	}
}

?>