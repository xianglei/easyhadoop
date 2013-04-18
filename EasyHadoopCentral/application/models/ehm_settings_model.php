<?php

class Ehm_settings_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_etc_hosts_list()
	{
		$sql = "select * from ehm_hosts";
		$query = $this->db->query($sql);
		$str = "127.0.0.1\tlocalhost\n";
		foreach($query->result() as $result)
		{
			$str .= $result->ip . "\t" . $result->hostname . "\n";
		}
		return $str;
	}
	
	public function get_general_settings_list()
	{
		$sql = "select * from ehm_host_settings where ip='0'";
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function get_node_settings_list($limit, $offset)
	{
		$sql = "select a.*,b.host_id from ehm_host_settings a,ehm_hosts b  where a.ip != '0' and a.ip = b.ip group by a.ip,a.filename limit ". $offset. ", ".$limit;
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function count_node_settings()
	{
		$sql = "select a.*,b.host_id from ehm_host_settings a,ehm_hosts b  where a.ip != '0' and a.ip = b.ip group by a.ip,a.filename";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		return $count;
	}
	
	public function get_settings_by_id($set_id)
	{
		$sql = "select * from ehm_host_settings where set_id = ". $set_id;
		if($query = $this->db->query($sql)):
			$result = $query->result();
			return $result[0];
		else:
			return FALSE;
		endif;
	}
	
	public function update_settings($set_id, $filename, $content, $ip = '0')
	{
		$sql = "update ehm_host_settings set filename='".$filename."', content='".$content."', ip='".$ip."' where set_id='".$set_id."'";
		if($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function delete_settings($set_id)
	{
		$sql = "delete from ehm_host_settings where set_id=".$set_id;
		if($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function insert_settings($filename, $content, $ip = '0')
	{
		$sql = "insert ehm_host_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."', ip='".$ip."'";
		if($this->db->simple_query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function get_hadoop_settings($filename = "")
	{
		if("" == $filename)
			$sql = "select * from ehm_hadoop_settings";
		else
			$sql = "select * from ehm_hadoop_settings where filename='".$filename."'";
		
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function get_hadoop_settings_category()
	{
		$sql = "select distinct filename as filename from ehm_hadoop_settings";
		if($query = $this->db->query($sql)):
			return $query->result();
		else:
			return FALSE;
		endif;
	}
	
	public function convert_hadoop_settings($value = "")
	{
		if(preg_match('/(?<=\{)([^\}]*?)(?=\})/', $value))
		{
			switch($value)
			{
				case "hdfs://{namenode}:9000":
					$sql = "select hostname from ehm_hosts where role like 'namenode%'";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = "hdfs://".$result->hostname.":9000";
					break;
				case "{jobtracker}:9001":
					$sql = "select hostname from ehm_hosts where role like '%jobtracker%'";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->hostname. ":9001";
					break;
				case "{mount_snn}":
					$sql = "select * from ehm_hosts where mount_snn != ''";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->mount_snn;
					break;
				case "{mount_name}":
					$sql = "select * from ehm_hosts where mount_name != ''";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->mount_name;
					break;
				case "{mount_data}":
					$sql = "select * from ehm_hosts where mount_data != ''";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->mount_data;
					break;
				case "{mount_mrlocal}":
					$sql = "select * from ehm_hosts where mount_mrlocal != ''";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->mount_mrlocal;
					break;
				case "{mount_mrsystem}":
					$sql = "select * from ehm_hosts where mount_mrsystem != ''";
					$query = $this->db->query($sql);
					$result = $query->result();
					$result = $result[0];
					$return = $result->mount_mrsystem;
					break;
			}
		}
		else
		{
			$return = $value;
		}
		return $return;
	}

}

?>
