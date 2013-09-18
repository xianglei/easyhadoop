<?php

class Nodes extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
	}

	public function Index()
	{
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
		$data['common_nav_monitor'] = $this->lang->line('common_nav_monitor');
		$data['common_nav_nodes'] = $this->lang->line('common_nav_nodes');
		$data['common_nav_hadoop'] = $this->lang->line('common_nav_hadoop');
		$data['common_nav_eco'] = $this->lang->line('common_nav_eco');
		$data['common_nav_hdfs'] = $this->lang->line('common_nav_hdfs');
		$data['common_nav_job'] = $this->lang->line('common_nav_job');
		$data['common_nav_hui'] = $this->lang->line('common_nav_hui');
		$data['common_nav_logout'] = $this->lang->line('common_nav_logout');
		$data['common_nav_install'] = $this->lang->line('common_nav_install');
		$data['common_nav_user'] = $this->lang->line('common_nav_user');
		
		$data['common_nav_mem'] = $this->lang->line('common_nav_mem');
		$data['common_nav_cpu'] = $this->lang->line('common_nav_cpu');
		$data['common_nav_storage'] = $this->lang->line('common_nav_storage');
		$data['common_nav_load'] = $this->lang->line('common_nav_load');
		$data['common_nav_mapred'] = $this->lang->line('common_nav_mapred');
		$data['common_nav_nettraffic'] = $this->lang->line('common_nav_nettraffic');
		$data['common_nav_settings'] = $this->lang->line('common_nav_settings');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('main/exa_main_header', $data);
		
		$data['common_nodes'] = $this->lang->line('common_nodes');
		$data['common_nodes_create_node'] = $this->lang->line('common_nodes_create_node');
		$data['common_nodes_create_nodes'] = $this->lang->line('common_nodes_create_nodes');
		$data['common_nodes_edit_node'] = $this->lang->line('common_nodes_edit_node');
		
		$this->load->model('Exa_nodes_model', 'nodes');

		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_rack'] = $this->lang->line('common_rack');
		
		$this->load->view('main/nodes/exa_main_nodes_lists', $data);
		
		$this->load->view('main/exa_main_footer', $data);
		
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_save'] = $this->lang->line('common_save');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_ssh_user'] = $this->lang->line('common_ssh_user');
		$data['common_ssh_pass'] = $this->lang->line('common_ssh_pass');
		$data['common_ssh_port'] = $this->lang->line('common_ssh_port');
		$data['common_sudo'] = $this->lang->line('common_sudo');
		$data['common_role'] = $this->lang->line('common_role');
		$data['common_rack'] = $this->lang->line('common_rack');
		$data['common_os'] = $this->lang->line('common_os');
		$data['common_ubuntu'] = $this->lang->line('common_ubuntu');
		$data['common_centos_5'] = $this->lang->line('common_centos_5');
		$data['common_centos_6'] = $this->lang->line('common_centos_6');
		$data['common_redhat_5'] = $this->lang->line('common_redhat_5');
		$data['common_redhat_6'] = $this->lang->line('common_redhat_6');
		$data['common_suse_10'] = $this->lang->line('common_suse_10');
		$data['common_suse_11'] = $this->lang->line('common_suse_11');
		$data['common_nodes_storage_tips'] = $this->lang->line('common_nodes_storage_tips');
		$this->load->view('main/nodes/exa_main_nodes_create_node_modal', $data);
		$this->load->view('main/nodes/exa_main_nodes_create_nodes_modal', $data);
		$this->load->view('main/nodes/exa_main_nodes_edit_node_modal', $data);
		
		$data['common_setup_storage'] = $this->lang->line('common_setup_storage');
		$this->load->view('main/nodes/exa_main_nodes_hdd_modal', $data);
		
		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function CreateNode()
	{
		set_time_limit(0);
		$os = $this->input->post('os');
		$ipaddr = $this->input->post('ipaddr');
		$ssh_port = $this->input->post('ssh_port');
		$ssh_user = $this->input->post('ssh_user');
		$ssh_pass = $this->input->post('ssh_pass');

		$sudo = $this->input->post('sudo');
		$sudo = ($sudo != "1") ? 0 : 1;

		$namenode = $this->input->post('namenode');
		$namenode = ($namenode != "1") ? 0 : 1;

		$datanode = $this->input->post('datanode');
		$datanode = ($datanode != "1") ? 0 : 1;

		$secondarynamenode = $this->input->post('secondarynamenode');
		$secondarynamenode = ($secondarynamenode != "1") ? 0 : 1;

		$jobtracker = $this->input->post('jobtracker');
		$jobtracker = ($jobtracker != "1") ? 0 : 1;

		$tasktracker = $this->input->post('tasktracker');
		$tasktracker = ($tasktracker != "1") ? 0 : 1;
		
		$rack = $this->input->post('rack');
		if($rack == "" || substr($rack,0,1) != '/')
		{
			$rack = "/default";
		}
		
		$this->load->model('Exa_nodes_model', 'nodes');
		$this->nodes->CreateNode($ipaddr, $ssh_port, $os, $ssh_user, $ssh_pass, $sudo, $rack, $namenode, $datanode, $secondarynamenode,$jobtracker, $tasktracker);
		
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function CreateNodes()
	{
		set_time_limit(0);
		$os = $this->input->post('os');
		$ipaddr = $this->input->post('ipaddr');
		$ssh_port = $this->input->post('ssh_port');
		$ssh_user = $this->input->post('ssh_user');
		$ssh_pass = $this->input->post('ssh_pass');
		
		$sudo = $this->input->post('sudo');
		$sudo = ($sudo != "1") ? 0 : 1;

		$namenode = $this->input->post('namenode');
		$namenode = ($namenode != "1") ? 0 : 1;

		$datanode = $this->input->post('datanode');
		$datanode = ($datanode != "1") ? 0 : 1;

		$secondarynamenode = $this->input->post('secondarynamenode');
		$secondarynamenode = ($secondarynamenode != "1") ? 0 : 1;

		$jobtracker = $this->input->post('jobtracker');
		$jobtracker = ($jobtracker != "1") ? 0 : 1;

		$tasktracker = $this->input->post('tasktracker');
		$tasktracker = ($tasktracker != "1") ? 0 : 1;
		
		$rack = $this->input->post('rack');
		if($rack == "" || substr($rack,0,1) != '/')
		{
			$rack = "/default";
		}
		
		$ip_list = explode("\n",$ipaddr);
		$this->load->model('Exa_nodes_model', 'nodes');
		foreach ($ip_list as $k => $v):
			$this->nodes->CreateNode(trim($v), $ssh_port, $os, $ssh_user, $ssh_pass, $sudo, $rack, $namenode, $datanode, $secondarynamenode,$jobtracker, $tasktracker);
		endforeach;
		
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function ListNodes()
	{
		$this->load->database();
		
		$this->load->model('Exa_nodes_model', 'nodes');
		$list = $this->nodes->ListNodes();
		
		$json = json_encode($list);
		echo $json;
		//$count = count($list);
	}
	
	public function GetNode($models)
	{
		$json = $this->input->get('models');
		$json = json_decode($json, TRUE);
		$id = $json[0]['id'];
		$this->load->model('Exa_nodes_model', 'nodes');
		$json = $this->nodes->GetNode($id);
		echo $json;
	}
	
	public function RemoveNode()
	{
		$json = $this->input->get('models');
		$json = json_decode($json, TRUE);
		$id = $json[0]['id'];
		$this->load->model('Exa_nodes_model', 'nodes');
		$this->nodes->RemoveNode($id);
	}
	
	public function EditNode()
	{
		set_time_limit(0);
		$id = $this->input->post('id');
		$os = $this->input->post('os');
		$ipaddr = $this->input->post('ipaddr');
		$ssh_port = $this->input->post('ssh_port');
		$ssh_user = $this->input->post('ssh_user');
		$ssh_pass = $this->input->post('ssh_pass');
		
		$sudo = $this->input->post('is_sudo');
		$sudo = ($sudo != "1") ? 0 : 1;

		$namenode = $this->input->post('namenode');
		$namenode = ($namenode != "1") ? 0 : 1;

		$datanode = $this->input->post('datanode');
		$datanode = ($datanode != "1") ? 0 : 1;

		$secondarynamenode = $this->input->post('secondarynamenode');
		$secondarynamenode = ($secondarynamenode != "1") ? 0 : 1;

		$jobtracker = $this->input->post('jobtracker');
		$jobtracker = ($jobtracker != "1") ? 0 : 1;

		$tasktracker = $this->input->post('tasktracker');
		$tasktracker = ($tasktracker != "1") ? 0 : 1;
		
		$rack = $this->input->post('rack');
		if($rack == "" || substr($rack,0,1) != '/')
		{
			$rack = "/default";
		}
		
		$this->load->model('Exa_nodes_model', 'nodes');
		$this->nodes->EditNode($id, $ipaddr, $ssh_port, $os, $ssh_user, $ssh_pass, $sudo, $rack, $namenode, $datanode, $secondarynamenode,$jobtracker, $tasktracker);
		
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function SetMountPoint()
	{
		//var_dump($this->input->post());
		$node_id = $this->input->post('node_id');
		$ip = $this->input->post('ip');
		$mount_point = $this->input->post('mount_point');
		$this->load->model('exa_nodes_model', 'nodes');
		$res = $this->nodes->GetNode($node_id);
		$sudo = $res->is_sudo;
		if($sudo == "1")
		{
			$sudo = TRUE;
		}
		else
		{
			$sudo = FALSE;
		}
		$sudo_user = $res->ssh_user;
		$sudo_pass = $res->ssh_pass;
		
		$str = $this->nodes->SetMountPoint($node_id, $ip, $mount_point, $sudo, $sudo_user , $sudo_pass );
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function GetMountPoint($ip)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$json = $this->nodes->GetMountPoint($ip);
		echo $json;
	}

	public function GetSavedMountPoint($ip)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$json = $this->nodes->GetSavedMountPoint($ip);
		echo $json;
	}
}

?>