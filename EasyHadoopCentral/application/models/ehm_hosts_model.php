<?php

class Ehm_hosts_model extends CI_Model
{

	public $hostname;
	public $host;
	public $role;
	public $ssh_user;
	public $ssh_pass;
	public $table_name = 'ehm_hosts';

	public $ehm_host;
	public $ehm_port;
	public $socket;
	public $transport;
	public $protocol;
	public $ehm;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    	public function execute_shell_script($host, $commands)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($commands);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	public function get_hosts_list($limit = "20", $offset = "0",$q="")
	{
		if($q=="")
		{
			if ($query = $this->db->get($this->table_name, $limit, $offset)):
				return $query->result(); // Return value is an objected matrix
			else:
				return FALSE;
			endif;
		}
		else
		{

			$sql = "select * from ehm_hosts where hostname like '%$q%' or ip like '%$q%' limit  $offset, $limit";
			
			if ($num = $this->db->query($sql))
			{
				return $num->result();
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	public function get_all_hosts()
	{
		$sql  = "select * from ehm_hosts";
		if ($query = $this->db->query($sql)):
			return $query->result(); // Return value is an objected matrix
		else:
			return FALSE;
		endif;
	}
	
	public function count_hosts($q="")
	{
		if($q=="")
		{
		
			if ($num = $this->db->count_all($this->table_name)):
				return $num;
			else:
				return FALSE;
			endif;
		}
		else
		{
			$sql = "select * from ehm_hosts where hostname like '%$q%' or ip like '%$q%'";
			if ($num = $this->db->query($sql)):
				return $num->num_rows();
			else:
				return FALSE;	
			endif;		
			
		}

	}
	//for tear test data
	public function tear_insert()
	{
		for($i=0;$i<30;$i++)
		{
		$sql="select * from ehm_hosts ";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		
		foreach($result as $row)
		{
			$row['host_id']='';
			$row['hostname']=$row['hostname'].$i;
			$this->db->insert('ehm_hosts', $row); 
		}
		}
	}
	
	public function count_hosts_by_role($role)
	{
		switch ($role)
		{
			case "datanode":
				$sql = "select * from ehm_hosts where role like '%datanode%'";
				break;
			case "namenode":
				$sql = "select * from ehm_hosts where role like 'namenode%'";
				break;
			case "secondarynamenode":
				$sql = "select * from ehm_hosts where role like '%secondarynamenode%'";
				break;
			case "jobtracker":
				$sql = "select * from ehm_hosts where role like '%jobtracker%'";
				break;
			case "tasktracker":
				$sql = "select * from ehm_hosts where role like '%tasktracker%'";
				break;
			default:
				return false;
		}
		if ($num = $this->db->query($sql)):
			return $num;
		else:
			return FALSE;
		endif;
	}

	public function get_host($host)
	{
		if ($query = $this->db->get_where($this->table_name, array('ip'=>$host))):
			$result = $query->result();
			return $result[0]; // Return value is an objected array
		else:
			return FALSE;
		endif;
	}
	
	public function get_divide_setted_host()
	{
		$sql = "select a.host_id,a.role,a.hostname,b.set_id,b.filename,b.content,b.ip from ehm_hosts as a, ehm_host_settings as b where a.ip = b.ip group by b.ip";
		if ($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function get_host_by_host_id($host_id)
	{
		if ($query = $this->db->get_where($this->table_name, array('host_id'=>$host_id))):
			$result = $query->result();
			return $result[0]; // Return value is an objected array
		else:
			return FALSE;
		endif;
	}
	
	public function get_datanode_list($limit, $offset)
	{
		//$sql = "select * from ehm_hosts where role like '%datanode%' limit ".$limit.", ".$offset;echo $sql;
		if($query = $this->db->query("select * from ehm_hosts where role like '%datanode%' limit ".$offset.", ".$limit)):
			$result = $query->result();
			return $result;
		else:
			return FALSE;
		endif;
	}
	
	public function get_namenode_list()
	{
		if($query = $this->db->query("select * from ehm_hosts where role like 'namenode%'")):
			$result = $query->result();
			return $result;
		else:
			return FALSE;
		endif;
	}
	
	public function get_jobtracker_list()
	{
		if($query = $this->db->query("select * from ehm_hosts where role like '%jobtracker%'")):
			$result = $query->result();
			return $result;
		else:
			return FALSE;
		endif;
	}
	
	public function get_tasktracker_list($limit, $offset)
	{
		if($query = $this->db->query("select * from ehm_hosts where role like '%tasktracker%' limit ".$offset.", ".$limit)):
			$result = $query->result();
			
			return $result;
		else:
			return FALSE;
		endif;
	}

	public function get_dead_tasktracker_list($where)
	{
		if($query = $this->db->query("select * from ehm_hosts where role like '%tasktracker%'  and hostname not in $where")):
			$result=array();
			foreach ($query->result() as $row)
			{
				$result[]= $row->hostname;
			}
			
			return $result;
		else:
			return FALSE;
		endif;
	}
	
	public function get_secondarynamenode()
	{
		if($query = $this->db->query("select * from ehm_hosts where role like '%secondarynamenode%'")):
			$result = $query->result();
			return $result;
		else:
			return FALSE;
		endif;
	}
	public function start_admin_server($ssh_user,$ssh_pass)
	{
	    $ip=$_SERVER["SERVER_ADDR"];
	    $cmd="netstat -na | awk '{print $4}' |grep ':".$this->config->item('agent_thrift_port')."'";
		$command="";
		//get config port daemon from netstat 
		$servers=exec($cmd);
		$cmd = 'python '. __DIR__ .'/../../expect.py -m ssh -u '. $ssh_user .' -p '. $ssh_pass. ' -c "python '. __DIR__ .'/../../NodeAgent.py -s restart -b '.$ip.'" -d '.$ip;
		
		$status = '{"status":"FALSE"}';
		if(!strstr($servers,":"))
		{
			$command = $cmd;
		}
		else
		{
			if ($fp = @fsockopen($ip, $this->config->item('ehm_port'), $errstr, $errno, 5))
			{
				$status = '{"status":"TRUE"}';
			}
			else
			{
				$command = $cmd;
			}
		}
		if(strlen($command)>4)
		{
			exec($command);
			$status = '{"status":"TRUE"}';
		}
		return $cmd.$status;
	}
	
	public function get_node_dist($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		$url = 'http://'.$this->ehm_host.':'.$this->ehm_port.'/node/dist/'.$token;
		try
		{
			$str = file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
	}
	
	public function insert_host($host, $role, $ssh_user = '', $ssh_pass = '', $rack = '1')
	{
		$admin_ip=$_SERVER["SERVER_ADDR"];
		
		$str = "";
		if($ssh_pass != "")
		{
			try
			{
				$this->load->model('ehm_management_model','manage');
				$ip = $host;
				$command = 'python '. __DIR__ .'/../../expect.py -m scp -u '. $ssh_user .' -p '. $ssh_pass. ' -f ' . __DIR__ . '/../../NodeAgent-1.2.0-1.x86_64.rpm -d '.$ip;
				$str = $this->execute_shell_script($admin_ip,$command);//exec($command);
				$command = 'python '. __DIR__ .'/../../expect.py -m ssh -u '. $ssh_user .' -p '. $ssh_pass. ' -c "rpm -ivh ~/NodeAgent-1.2.0-1.x86_64.rpm" -d '.$ip;
				//$str .= exec($command);
				$str .= $this->execute_shell_script($admin_ip,$command);//exec($command);
			}
			catch(Exception $e)
			{
				$str = 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		
		$json = $this->hosts->get_node_dist(trim($v));
		$tmp = json_decode($json, TRUE);
		$hostname = $tmp['os.hostname'];
		
		$sql = "insert ehm_hosts set hostname='".$hostname."', ip='".$host."', role='".$role."', ssh_user='".$ssh_user."', ssh_pass='".$ssh_pass."', rack='".$rack."'";
		if($this->db->simple_query($sql)):
			return $str;
		else:
			return FALSE;
		endif;
	}
	
	public function update_host($host_id, $hostname, $host, $role, $ssh_user = '', $ssh_pass = '', $rack = '1')
	{
	
		$json = $this->hosts->get_node_dist(trim($host));
		$tmp = json_decode($json, TRUE);
		$hostname = $tmp['os.hostname'];
		
		$sql = "update ehm_hosts set hostname='".$hostname."' , ip='".$host."', role='".$role."', ssh_user='".$ssh_user."', ssh_pass='".$ssh_pass."', rack='".$rack."' where host_id='".$host_id."'";
		if ($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function delete_host($host_id)
	{
		$sql = "delete from ehm_hosts where host_id=".$host_id;
		if ($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}

	public function __destruct()
	{
	}
	public function ping_admin_host()
	{
		$ip=$_SERVER["SERVER_ADDR"];
		if ($fp = @fsockopen($ip, $this->config->item('agent_http_port'), $errstr, $errno, 5)):
			fclose($fp);
			$status = '{"status":"TRUE", "ip":"'.$ip.'"}';
		else:
			$status = '{"status":"FALSE", "ip":"'.$ip.'"}';
		endif;
		return $status;
	}

	public function ping_host($host_id)
	{
		$query = $this->db->get_where($this->table_name, array('host_id'=>$host_id));
		$result = $query->result();
		if ($fp = @fsockopen($result[0]->ip, $this->config->item('agent_thrift_port'), $errstr, $errno, 5)):
			fclose($fp);
			$status = '{"status":"TRUE", "ip":"'.$result[0]->ip.'"}';
		else:
			$status = '{"status":"FALSE", "ip":"'.$result[0]->ip.'"}';
		endif;
		return $status;
	}
	
	public function make_rackaware()
	{
		$result = $this->get_all_hosts();
		$str = "";
		foreach($result as $item)
		{
			$str .= "	\"".$item->ip."\":\"rack".$item->rack."\",\n";
		}
		$str = substr($str,1);
		$str = "rack = {".$str."}";
		
		$rack = "#!/usr/bin/python
#-*-coding:UTF-8-*-
import sys

".$str."
if __name__==\"__main__\":
	print \"/\" + rack.get(sys.argv[1],\"rack0\")\n";
		unset($str);
		$str = "";
		$str['filename'] = $this->config->item('hadoop_conf_folder') . "RackAware.py";
		$str['chmod'] = "777";
		$str['content'] = $rack;
		
		return $str;
	}
}

?>
