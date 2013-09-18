<?php
class Exa_aux_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_byte_abbr($bytes, $precision = 2, $key = FALSE) # key = FALSE means return number only, TRUE means return with abbr
	{
		$this->load->helper('number');
		if($key == FALSE)
		{
			$num = byte_format($bytes, $precision);
			$num = explode(" ",$num);
			$num = $num[0];
		}
		else
		{
			$num = byte_format($bytes, $precision);
			$num = $num;
		}
		return $num;
	}
	
	public function parse_json($json, $key = FALSE) #key = FALSE means return object, TRUE means return array;
	{
		$return = json_decode($json, $key);
		return $return;
	}
	
	private function parse_json_object($json_object,$keyword)
	{
		foreach($json_object as $k => $v)
		{
			//var_dump($v);
			if(@$v->{$keyword} != "")
			{
				return $v->{$keyword};
			}
		}
	}
	
	public function parse_jmx_json($json, $role) #$role = namenode or datanode or jobtracker or tasktracker or secondarynamenode
	{
		$obj = $this->parse_json($json, FALSE);
		unset($json);
		if($role == 'jobtracker')
		{
			$json['map_slots'] = intval($this->parse_json_object($obj->{'beans'}, 'map_slots'));
			$json['reduce_slots'] = intval($this->parse_json_object($obj->{'beans'}, 'reduce_slots'));
			$json['running_maps'] = intval($this->parse_json_object($obj->{'beans'}, 'running_maps'));
			$json['running_reduces'] = intval($this->parse_json_object($obj->{'beans'}, 'running_reduces'));
			
			$json['percent_map_running'] = round(($json['running_maps']/$json['map_slots'])*100);
			$json['percent_map_not_running'] = 100 - $json['percent_map_running'];
			$json['percent_reduce_running'] = round(($json['running_reduces']/$json['reduce_slots'])*100);
			$json['percent_reduce_not_running'] = 100 - $json['percent_reduce_running'];
			
			$json = json_encode($json);
		}
		elseif($role == 'tasktracker')
		{
			$json['map_task_slots'] = intval($this->parse_json_object($obj->{"beans"},"mapTaskSlots"));
			$json['maps_running'] = intval($this->parse_json_object($obj->{"beans"},"maps_running"));

			$json['reduce_task_slots'] = intval($this->parse_json_object($obj->{"beans"},"reduceTaskSlots"));
			$json['reduces_running'] = intval($this->parse_json_object($obj->{"beans"},"reduces_running"));

			$json['percent_map_running'] = round(($json['maps_running']/$json['map_task_slots'])*100);
			$json['percent_map_remain'] = 100 - $json['percent_map_running'];
			$json['percent_reduce_running'] = round(($json['reduces_running']/$json['reduce_task_slots'])*100);
			$json['percent_reduce_remain'] = 100 - $json['percent_reduce_running'];
			
			$json = json_encode($json);
		}
		elseif($role == 'namenode')
		{
			//var_dump($obj);
			$json['total'] = $this->parse_json_object($obj->{"beans"}, "Total");
			$json['free'] = $this->parse_json_object($obj->{"beans"},"Free");
			$json['nondfs'] = $this->parse_json_object($obj->{"beans"},"NonDfsUsedSpace");
			$json['dfs'] = $this->parse_json_object($obj->{"beans"},"Used");
			$json['total_abbr'] = $this->get_byte_abbr($json['total'],2,TRUE);
			$json['free_abbr'] = $this->get_byte_abbr($json['free'],2,TRUE);
			$json['dfs_abbr'] = $this->get_byte_abbr($json['dfs'],2,TRUE);
			$json['nondfs_abbr'] = $this->get_byte_abbr($json['nondfs'],2,TRUE);

			$json['percent_free'] = round(($json['free']/$json['total'])*100);
			$json['percent_nondfs'] = round(($json['nondfs']/$json['total'])*100);
			$json['percent_dfs'] = 100 - ($json['percent_free'] + $json['percent_nondfs']);
			
			$json = json_encode($json);
		}
		elseif($role == 'datanode')
		{
			$json['total'] = $this->parse_json_object($obj->{"beans"},"Capacity");
			$json['used'] = $this->parse_json_object($obj->{"beans"},"DfsUsed");
			$json['total_abbr'] = $this->get_byte_abbr($json['total'], 2, TRUE);
			$json['used_abbr'] = $this->get_byte_abbr($json['used'], 2, TRUE);

			$json['percent_used'] = round(($json['used']/$json['total'])*100);
			$json['percent_remain'] = 100 - $json['percent_used'];

			$json['volume_info'] = $this->parse_json_object($obj->{"beans"},"VolumeInfo");
			$json['volume_info'] = $this->parse_json($json['volume_info'], TRUE);#Need views or jquery to explode volume_info array
			
			$json = json_encode($json);
		}
		elseif($role == 'secondarynamenode')
		{
			$json = '{"":""}';
		}
		else
		{
			//unset($json);
			$json = '{"Exception":"No Role found"}';
		}
		return $json;
	}
	
	public function priv_string_to_oct($str)
	{
		$tmp = str_split($str);
		
		$dir = ($tmp[0] == "d") ? 1 : 0;
		
		$ur = ($tmp[1] == "r") ? 4 : 0;
		$uw = ($tmp[2] == "w") ? 2 : 0;
		$ux = ($tmp[3] == "x") ? 1 : 0;
		
		$gr = ($tmp[4] == "r") ? 4 : 0;
		$gw = ($tmp[5] == "w") ? 2 : 0;
		$gx = ($tmp[6] == "x") ? 1 : 0;
		
		$or = ($tmp[7] == "r") ? 4 : 0;
		$ow = ($tmp[8] == "w") ? 2 : 0;
		$ox = ($tmp[9] == "x") ? 1 : 0;
		
		$u = $ur + $uw + $ux;
		$g = $gr + $gw + $gx;
		$o = $or + $ow + $ox;
		
		$conv = strval($u . $g . $o);
		
		$ret['origin'] = $str;
		$ret['convert'] = $conv;
		$ret['is_dir'] = $dir;
		$ret['ur'] = $ur;
		$ret['uw'] = $uw;
		$ret['ux'] = $ux;
		$ret['gr'] = $gr;
		$ret['gw'] = $gw;
		$ret['gx'] = $gx;
		$ret['or'] = $or;
		$ret['ow'] = $ow;
		$ret['ox'] = $ox;
		return json_encode($ret);
	}
	
	public function oct_to_priv_string($num)
	{
		$tmp = str_split($num);
		$u = $this->oct2string($tmp[0]);
		$g = $this->oct2string($tmp[1]);
		$o = $this->oct2string($tmp[2]);
		
		$ret['origin'] = $num;
		$ret['convert'] = $u.$g.$o;
		
		return json_encode($ret);
	}
	
	private function oct2string($num)
	{
		switch($num)
		{
			case "7":
				$str = "rwx";
				break;
			case "6":
				$str = "rw-";
				break;
			case "5":
				$str = "r-x";
				break;
			case "4":
				$str = "r--"; 
				break;
			case "3":
				$str = "-wx";
				break;
			case "2":
				$str = "-w-";
				break;
			case "1":
				$str = "--x";
				break;
			case "0":
				$str = '---';
				break;
		}
		return $str;
	}
}
?>