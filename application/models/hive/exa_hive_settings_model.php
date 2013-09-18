<?php

class Exa_hive_settings_model extends CI_Model
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
	
	public function ListHiveSettings()
	{
		$sql = 'select * from exa_hive_settings';
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	
	public function ListNodesHiveSettings()
	{
		$sql = 'select * from exa_nodes_hive_settings';
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	
	public function CountHiveSettings()
	{
		$sql = 'select * from exa_hive_settings';
		$qry = $this->db->query($sql);
		$num = $qry->num_rows();
		return $num;
	}
	
	public function GetHiveSettingById($pId)
	{
		$sql = "select * from exa_nodes_hive_settings where id = ". $pId;
		if($query = $this->db->query($sql)):
			$result = $query->result();
			return $result[0];
		else:
			return FALSE;
		endif;
	}
	
	public function MakeHiveSettings($pKey = array(), $pValue = array(), $pDesc = array())
	{
		if(count($pKey) > 0)
		{
			$xml = "<?xml version=\"1.0\"?>\n";
			$xml .= "<?xml-stylesheet type=\"text/xsl\" href=\"configuration.xsl\"?>\n";
			$xml .= "<configuration>\n";
			
			for($i = 0; $i<count($pKey); $i++)
			{
				$xml .= "  <property>\n";
				$xml .= "    <name>". $pKey[$i] ."</name>\n";
				$xml .= "    <value>" . $pValue[$i] . "</value>\n";
				$xml .= "    <description>" . $pDesc[$i] . "</description>\n";
				$xml .= "  </property>\n\n";
			}
			
			$xml .= "</configuration>\n";
		}
		
		$ret = array();
		$ret = $xml;
		
		return $ret;
	}
	
	public function CreateHiveSettings($filename, $content, $ip = '0')
	{
		$sql = "select * from exa_nodes_hive_settings where ip = '".$ip."' and filename = '".$filename."'";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if($count == 0)
		{
			$sql = "insert exa_nodes_hive_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."', ip='".$ip."'";
		}
		else
		{
			$sql = "update exa_nodes_hive_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."',create_time=now() where ip = '".$ip."' and filename = '".$filename."'";
		}
		if($this->db->simple_query($sql)):
			$ret = TRUE;
		else:
			$ret = FALSE;
		endif;
		return $ret;
	}
	
	public function RemoveHiveSettings($pId)
	{
		echo $sql = "delete from exa_nodes_hive_settings where id=".$pId;
		if($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function PushHiveSettings()
	{
		$sql = "select * from exa_nodes_hive_settings where ip != '0'";
		$qry = $this->db->query($sql);
		$sets = $qry->result();
		
		$str = "[";
		foreach($sets as $set)
		{
			$ip = $set->ip;
			$filename = $this->config->item('hive_conf_folder') . $set->filename;
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