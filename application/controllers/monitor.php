<?php

class Monitor extends CI_Controller
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
		$this->load->helper('url');
		redirect($this->config->base_url() . 'index.php/monitor/memory/');
	}

	public function Memory()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/memory/';
		$config['total_rows'] = $this->nodes->CountNodes();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->PaginationNodes($config['per_page'], $offset);
		
		$data['common_sample'] = $this->lang->line('common_sample');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_role'] = $this->lang->line('common_role');
		
		$this->load->view('main/monitor/exa_main_monitor_mem', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function ClusterSummary()
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
		
		$this->load->view('main/monitor/exa_main_monitor_summary', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Cpu()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_cpu_info'] = $this->lang->line('common_cpu_info');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/cpu/';
		$config['total_rows'] = $this->nodes->CountNodes();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->PaginationNodes($config['per_page'], $offset);
		
		$data['common_sample'] = $this->lang->line('common_sample');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_role'] = $this->lang->line('common_role');
		
		$this->load->view('main/monitor/exa_main_monitor_cpu', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function LoadAvg()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_cpu_info'] = $this->lang->line('common_cpu_info');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		$data['common_load_avg'] = $this->lang->line('common_load_avg');
		$data['common_active_pid'] = $this->lang->line('common_active_pid');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/loadavg/';
		$config['total_rows'] = $this->nodes->CountNodes();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->PaginationNodes($config['per_page'], $offset);
		
		$data['common_sample'] = $this->lang->line('common_sample');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_role'] = $this->lang->line('common_role');
		
		$this->load->view('main/monitor/exa_main_monitor_load', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Network()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/network/';
		$config['total_rows'] = $this->nodes->CountNodes();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->PaginationNodes($config['per_page'], $offset);
		
		$data['common_sample'] = $this->lang->line('common_sample');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_role'] = $this->lang->line('common_role');
		$this->load->view('main/monitor/exa_main_monitor_net', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Hdfs()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$this->load->model('exa_nodes_model', 'nodes');
		$result = $this->nodes->GetNamenode();
		if(@$result->id != "")
			$data['namenode_host_id'] = $result->id;
		else
			$data['namenode_host_id'] = "0";
		unset ($result);
		
		#generate pagination
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/hdfs/';
		$config['total_rows'] = $this->nodes->CountDatanodes();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->GetDatanodesList($config['per_page'], $offset);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_hdd_status'] = $this->lang->line('common_hdd_status');
		$data['common_mount_point'] = $this->lang->line('common_mount_point');
		$data['common_hdfs_free'] = $this->lang->line('common_hdfs_free');
		$data['common_hdfs_used'] = $this->lang->line('common_hdfs_used');
		$data['common_hdfs_nondfs'] = $this->lang->line('common_hdfs_nondfs');
		$data['common_hdfs_reserved'] = $this->lang->line('common_hdfs_reserved');
		
		$this->load->view('main/monitor/exa_main_monitor_hdfs', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Mapred()
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
		
		$data['common_memory'] = $this->lang->line('common_memory');
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_net_status'] = $this->lang->line('common_net_status');
		
		$this->load->view('main/monitor/exa_main_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_map_status'] = $this->lang->line('common_map_status');
		$data['common_reduce_status'] = $this->lang->line('common_reduce_status');
		
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		$this->load->model('exa_nodes_model', 'nodes');
		
		$result = $this->nodes->GetJobtracker();
		if(@$result->id != "")
			$data['jobtracker_host_id'] = $result->id;
		else
			$data['jobtracker_host_id'] = "0";
		unset ($result);
		
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/mapred/';
		$config['total_rows'] = $this->nodes->CountTasktrackers();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->nodes->GetTasktrackersList($config['per_page'], $offset);
		
		$this->load->view('main/monitor/exa_main_monitor_mapred', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}

	public function MemStats($pId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		
		$res = $this->nodes->GetNode($pId);
		$ip = $res->ip;
		
		$this->load->model('exa_monitor_model', 'monitor');
		$json =  json_decode($this->monitor->MemStats($ip));
		$mem['mem_total'] = $json->MemTotal;
		$mem['mem_free'] = $json->MemFree;
		$mem['mem_buffers'] = $json->Buffers;
		$mem['mem_cached'] = $json->Cached;
		$mem['mem_used'] = $json->MemUsed;
		
		$mem['mem_free_percent'] = round(($mem['mem_free'] / $mem['mem_total']) * 100);
		$mem['mem_buffers_percent'] = round(($mem['mem_buffers'] / $mem['mem_total']) * 100);
		$mem['mem_cached_percent'] = round(($mem['mem_cached'] / $mem['mem_total']) * 100);
		$mem['mem_used_percent'] = 100 - $mem['mem_free_percent'] - $mem['mem_cached_percent'] - $mem['mem_buffers_percent'];
		
		$this->load->model('exa_aux_model','aux');
		
		$mem['mem_total_abbr'] = $this->aux->get_byte_abbr($mem['mem_total'], 2, True);
		$mem['mem_free_abbr'] = $this->aux->get_byte_abbr($mem['mem_free'], 2, True);
		$mem['mem_buffers_abbr'] = $this->aux->get_byte_abbr($mem['mem_buffers'], 2, True);
		$mem['mem_cached_abbr'] = $this->aux->get_byte_abbr($mem['mem_cached'], 2, True);
		$mem['mem_used_abbr'] = $this->aux->get_byte_abbr($mem['mem_used'], 2, True);
		
		$json = json_encode($mem);
		
		echo $json;
	}
	
	public function CpuDetail($pId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		
		$res = $this->nodes->GetNode($pId);
		$ip = $res->ip;
		
		$this->load->model('exa_monitor_model', 'monitor');
		$json = $this->monitor->CpuDetail($ip);
		
		echo $json;
	}
	
	public function NetStats($pId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		
		$res = $this->nodes->GetNode($pId);
		$ip = $res->ip;
		
		$this->load->model('exa_monitor_model', 'monitor');
		$json = $this->monitor->NetTrafficStats($ip);
		$net = json_decode($json, TRUE);
		$net['total'] = $net['bytes_sent'] + $net['bytes_recv'];
		$net['total'] = ($net['total'] == 0) ? 1 : $net['total'];
		
		$net['bytes_sent_percent'] = round(($net['bytes_sent'] / $net['total']) * 100);
		$net['bytes_recv_percent'] = round(($net['bytes_recv'] / $net['total']) * 100);
		
		$this->load->helper('number');
		$net['bytes_sent_abbr'] = byte_format($net['bytes_sent']);
		$net['bytes_recv_abbr'] = byte_format($net['bytes_recv']);
		
		echo json_encode($net);
	}
	
	public function CpuStats($pId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		
		$res = $this->nodes->GetNode($pId);
		$ip = $res->ip;
		
		$this->load->model('exa_monitor_model', 'monitor');
		$json = $this->monitor->CpuStats($ip);
		
		echo $json;
	}
	
	public function LoadStats($pId)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		
		$res = $this->nodes->GetNode($pId);
		$ip = $res->ip;
		
		$this->load->model('exa_monitor_model', 'monitor');
		$json = $this->monitor->LoadAverage($ip);
		
		echo $json;
	}
	
	public function NamenodeStats()
	{
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetNamenodeJmx($qry="Hadoop:service=NameNode,name=NameNodeInfo");
		$json = $this->aux->parse_jmx_json($json, 'namenode');
		
		echo $json;
	}
	
	public function SecondaryNamenodeStats()
	{
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetSecondaryNamenodeJmx($qry="Hadoop:service=NameNode,name=NameNodeInfo");
		$json = $this->aux->parse_jmx_json($json, 'secondarynamenode');
		
		echo $json;
	}
	
	public function JobtrackerStats()
	{
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetJobtrackerJmx($qry="Hadoop:service=JobTracker,name=JobTrackerMetrics");
		$json = $this->aux->parse_jmx_json($json, 'jobtracker');
		
		echo $json;
	}
	
	public function DatanodeStats($pId)
	{
		$host_id = $pId;
		$this->load->model('exa_nodes_model', 'nodes');
		$result = $this->nodes->GetNode($host_id);
		$ip = $result->ip;
		$role = "datanode";
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetDatanodeJmx($ip, $qry="");
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	
	public function TasktrackerStats($pId)
	{
		$host_id = $pId;
		$this->load->model('exa_nodes_model', 'nodes');
		$result = $this->nodes->GetNode($host_id);
		$ip = $result->ip;
		$role = "tasktracker";
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetTasktrackerJmx($ip, $qry="");
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	
	public function NamenodeSummary()
	{
		$this->load->model('exa_monitor_model', 'monitor');
		$this->load->model('exa_aux_model', 'aux');
		
		$json = $this->monitor->GetNamenodeJmx();
		echo $json;
	}
}

?>