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
		return count;
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
		echo $sql = "update ehm_host_settings set filename='".$filename."', content='".$content."', ip='".$ip."' where set_id='".$set_id."'";
		if($query = $this->db->query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function delete_settings($set_id)
	{
		$sql = "delete from ehm_host_settings where set_id=".$set_id;
		if($query = $this->db->query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function insert_settings($filename, $content, $ip = '0')
	{
		$sql = "insert ehm_host_settings set filename='".$filename."', content='".str_replace("'", "\'", $content)."', ip='".$ip."'";
		if($query = $this->db->query($sql)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}

}

?>