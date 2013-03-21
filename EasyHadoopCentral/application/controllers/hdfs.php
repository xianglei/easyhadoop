<?php

class Hdfs extends CI_Controller
{
	protected $fields	= array(
			"id"		=> false,
			"parent_id"	=> false,
			"position"	=> false,
			"left"		=> false,
			"right"		=> false,
			"level"		=> false
		);
	protected $hadoop = "sudo -u hadoop hadoop";
	public  $cmd;

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('hdfs_header',$data);
		
		#generate navigation bar
		$data['common_index_page'] = $this->lang->line('common_index_page');
		$data['common_node_manager'] = $this->lang->line('common_node_manager');
		$data['common_node_monitor'] = $this->lang->line('common_node_monitor');
		$data['common_install'] = $this->lang->line('common_install');
		$data['common_host_settings'] = $this->lang->line('common_host_settings');
		$data['common_node_operate'] = $this->lang->line('common_node_operate');
		$data['common_user_admin'] = $this->lang->line('common_user_admin');
		$data['common_log_out'] = $this->lang->line('common_log_out');
		$data['common_hadoop_node_operate'] = $this->lang->line('common_hadoop_node_operate');
		$data['common_hbase_node_operate'] = $this->lang->line('common_hbase_node_operate');
		$data['common_hadoop_host_settings'] = $this->lang->line('common_hadoop_host_settings');
		$data['common_hbase_host_settings'] = $this->lang->line('common_hbase_host_settings');
		$data['common_install_hadoop'] = $this->lang->line('common_install_hadoop');
		$data['common_install_hbase'] = $this->lang->line('common_install_hbase');
		$this->load->view('nav_bar', $data);	
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$this->load->view('ehm_hosts_hdfs_nav', $data);		
		
		$this->load->view('ehm_hosts_hdfs_op', $data);
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
		
	}
	public function get_children()
	{
		$data["id"]=$this->input->get('id');
		$data["id"]=urldecode($data["id"]);
		$data["id"]=stripcslashes($data["id"]);
		if($data["id"]==1)
		$data["id"]="/";
		$tmp=$this->hdfs_scandir($data["id"]);
		if(!$tmp)
		return null;
		foreach($tmp as $k => $v) {
			//if($v["type"]=="default")
			//$state="";
			//else
			$state="closed";
			$result[] = array(
				"attr" => array("id" => "node_".$k, "rel" => $v["type"]),
				"data" => $k,
				"state" => $state//((int)$v[$this->fields["right"]] - (int)$v[$this->fields["left"]] > 1) ? "closed" : "",
				//"root"=>$this->cmd
			);
		}		
		
		exit(json_encode($result));	
	
	}
	public function create()
	{
		$title=$this->input->post('title');
		$id=$this->input->post('id');
		$this->cmd=$this->hadoop." fs -mkdir ".$id."/".$title;

		$this->promptJson(1,$this->exec($this->cmd),$id."/".$title);
		
		
	}
	public function rename()
	{
		$title=$this->input->post('title');
		$id=$this->input->post('id');
		$this->cmd=$this->hadoop." fs -mv  ".$id."   ".$title;

		$this->promptJson(1,$this->exec($this->cmd));
	
	}
	public function remove()
	{
		$id=$this->input->post('id');
		if((int)$id === "/") { return false; }
		$this->cmd=$this->hadoop." fs -rmr ".$id;
		$this->promptJson(1,$this->exec($this->cmd));
	
	
	
	}
	public function promptJson($code,$msg,$id="/")
	{
	
		exit(json_encode(array("status"=>$code  ,"msg" =>$msg,"id"=>$id),JSON_FORCE_OBJECT));
	}
	
	public function exec($cmd)
	{
		$this->load->model('ehm_management_model', 'mn');
		
		$re= $this->mn->execute_shell_script($this->mn->get_name_node(),$cmd);
		return $re;
		
	}
	protected function hdfs_scandir($path) {	

		if(!$path)
		$path="/";
		$dirs=array();
		$this->cmd=$this->hadoop." fs -ls $path| awk  '{print \$1,\$5,\$6,\$7,\$8}'";
		$dir_list=$this->exec($this->cmd);
		if(!stristr($dir_list,"\n"))
		return null;
		//print  $this->cmd." dir_list : $dir_list ";
		$file_stat=array();
		$return=explode("\n",$dir_list);
		if(sizeof($return)>1)
		{
			foreach($return as $k=>$v)
			{
				if($v)
				{
					
					if(strstr($v,"-"))
					{
						
						$line=explode(" ",$v);
						//$line[4]=str_replace("/","",$line[4]);	
						//drwxr-xr-x 0 2013-03-07 17:25 /data
						$info['perm']=$line[0];
						if(strstr($info['perm'],"d"))
						{
						$info['type']='folder';
						}
						else
						{
						$info['type']='default';
						}
						$info['size']=$line[1];
						$info['mtime']="";//strtotime($line[2].$line[3]);
						$file_stat[$line[4]]=$info;
						
						$dirs[]=$line[4];
					}
				}
			}
		}
		//print_r($this->file_stat);
		return $file_stat;
		
	}		
}