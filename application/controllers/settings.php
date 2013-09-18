<?php

class Settings extends CI_Controller
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
		redirect($this->config->base_url() . 'index.php/settings/hadoop/');
	}
	
	public function Hadoop()
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
		
		$data['common_hadoop_settings'] = $this->lang->line('common_hadoop_settings');
		$data['common_core_site'] = $this->lang->line('common_core_site');
		$data['common_hdfs_site'] = $this->lang->line('common_hdfs_site');
		$data['common_mapred_site'] = $this->lang->line('common_mapred_site');
		$data['common_hadoop_env'] = $this->lang->line('common_hadoop_env');
		$data['common_settings_templates'] = $this->lang->line('common_settings_templates');
		
		$this->load->view('main/settings/hadoop/exa_main_hadoop_settings_nav', $data);
		
		$data['common_global_settings'] = $this->lang->line('common_global_settings');
		$data['common_node_settings'] = $this->lang->line('common_node_settings');
		$data['common_rack_aware'] = $this->lang->line('common_rack_aware');
		$data['common_view_rackaware'] = $this->lang->line('common_view_rackaware');
		$data['common_push_rackaware'] = $this->lang->line('common_push_rackaware');
		$data['common_view_hosts'] = $this->lang->line('common_view_hosts');
		$data['common_push_hosts'] = $this->lang->line('common_push_hosts');
		$data['common_push_global_settings'] = $this->lang->line('common_push_global_settings');
		$data['common_push_node_settings'] = $this->lang->line('common_push_node_settings');
		$data['common_filename'] = $this->lang->line('common_filename');
		$data['common_update_time'] = $this->lang->line('common_update_time');
		$data['common_action'] = $this->lang->line('common_action');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_remove'] = $this->lang->line('common_remove');
		$data['common_save'] = $this->lang->line('common_save');
		$data['common_view'] = $this->lang->line('common_view');
		$data['common_remove_settings'] = $this->lang->line('common_remove_settings');
		$data['common_content'] = $this->lang->line('common_content');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_push'] = $this->lang->line('common_push');
		$data['common_view_settings'] = $this->lang->line('common_view_settings');
		$data['common_hadoop_settings_others'] = $this->lang->line('common_hadoop_settings_others');
		
		$this->load->model('exa_settings_model', 'sets');
		$data['result_general'] = $this->sets->ListSettings("0");
		$this->load->model('exa_nodes_model', 'nodes');
		
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/settings/hadoop/';
		$config['total_rows'] = $this->sets->CountNodeSettings();
		$config['per_page'] = 20;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['result_node'] = $this->sets->ListNodeSettings($config['per_page'], $offset);
		
		$this->load->view('main/settings/hadoop/exa_main_hadoop_settings_list', $data);
		
		$this->load->view('main/settings/hadoop/exa_main_hadoop_push_etc_hosts_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_push_rackaware_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_view_etc_hosts_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_view_rackaware_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_push_global_settings_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_push_node_settings_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_view_settings_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_remove_settings_modal', $data);
		$this->load->view('main/settings/hadoop/exa_main_hadoop_edit_settings_modal', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Create()
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
		
		$data['common_hadoop_settings'] = $this->lang->line('common_hadoop_settings');
		$data['common_core_site'] = $this->lang->line('common_core_site');
		$data['common_hdfs_site'] = $this->lang->line('common_hdfs_site');
		$data['common_mapred_site'] = $this->lang->line('common_mapred_site');
		$data['common_hadoop_env'] = $this->lang->line('common_hadoop_env');
		$data['common_settings_templates'] = $this->lang->line('common_settings_templates');
		$data['common_hadoop_settings_others'] = $this->lang->line('common_hadoop_settings_others');
		
		$this->load->view('main/settings/hadoop/exa_main_hadoop_settings_nav', $data);
		
		$this->load->model('exa_settings_model', 'sets');
		$this->load->model('exa_nodes_model', 'nodes');
		$list = $this->nodes->ListNodes();
		$data['list'] = $list;
		
		$result = $this->sets->ListHadoopSettings('core-site.xml');
		$data['result_core'] = $result;
		
		$result = $this->sets->ListHadoopSettings('hdfs-site.xml');
		$data['result_hdfs'] = $result;
		
		$result = $this->sets->ListHadoopSettings('mapred-site.xml');
		$data['result_mapred'] = $result;
		
		$data['common_global_settings'] = $this->lang->line('common_global_settings');
		
		$this->load->view('main/settings/hadoop/exa_main_hadoop_create_tab', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function HBase()// Hbase settings
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
		
		$data['common_coming_soon'] = $this->lang->line('common_coming_soon');
		$this->load->view('main/settings/hbase/exa_main_settings_hbase_soon', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function CreateHive()// Hbase settings
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
		
		$data['common_settings_templates'] = $this->lang->line('common_settings_templates');
		$data['common_global_settings'] = $this->lang->line('common_global_settings');
		$data['common_hive_env'] = $this->lang->line('common_hive_env');
		$data['common_hive_site'] = $this->lang->line('common_hive_site');
		$this->load->model('exa_nodes_model', 'nodes');
		$list = $this->nodes->ListNodes();
		$data['list'] = $list;
		
		$this->load->model('hive/exa_hive_settings_model', 'hs');
		$data['result_core'] = $this->hs->ListHiveSettings();
		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$data['ip'] = $jt->ip;
		
		$this->load->view('main/settings/hive/exa_main_hive_settings_nav', $data);
		$this->load->view('main/settings/hive/exa_main_hive_create_tab', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Hive()// Hive settings
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
		
		$data['common_settings_templates'] = $this->lang->line('common_settings_templates');
		$data['common_hive_env'] = $this->lang->line('common_hive_env');
		$data['common_hive_site'] = $this->lang->line('common_hive_site');
		
		$data['common_hive_settings'] = $this->lang->line('common_hive_settings');
		$data['common_push_hive_settings'] = $this->lang->line('common_push_hive_settings');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_filename'] = $this->lang->line('common_filename');
		$data['common_update_time'] = $this->lang->line('common_update_time');
		$data['common_action'] = $this->lang->line('common_action');
		$data['common_content'] = $this->lang->line('common_content');
		$data['common_view_settings'] = $this->lang->line('common_view_settings');
		$data['common_remove_settings'] = $this->lang->line('common_remove_settings');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_remove'] = $this->lang->line('common_remove');
		$data['common_view'] = $this->lang->line('common_view');
		$data['common_push'] = $this->lang->line('common_push');
		
		$this->load->model('hive/exa_hive_settings_model', 'hives');
		$data['result'] = $this->hives->ListNodesHiveSettings();
		
		$this->load->view('main/settings/hive/exa_main_hive_settings_nav', $data);
		$this->load->view('main/settings/hive/exa_main_hive_settings_list', $data);
		$this->load->view('main/settings/hive/exa_main_hive_push_hive_settings_modal', $data);
		$this->load->view('main/settings/hive/exa_main_hive_remove_settings_modal', $data);
		$this->load->view('main/settings/hive/exa_main_hive_view_settings_modal', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Mahout()// Hbase settings
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
		
		$data['common_non_free'] = $this->lang->line('common_non_free');
		$this->load->view('main/settings/mahout/exa_main_settings_mahout_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Pig()// Hbase settings
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
		
		$data['common_non_free'] = $this->lang->line('common_non_free');
		$this->load->view('main/settings/pig/exa_main_settings_pig_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function ZooKeeper()// Hbase settings
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
		
		$data['common_non_free'] = $this->lang->line('common_non_free');
		$this->load->view('main/settings/zookeeper/exa_main_settings_zookeeper_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function GetHadoopSettings($pId)
	{
		$this->load->model('exa_settings_model', 'sets');
		$obj = $this->sets->GetSettingsById($pId);
		$obj->content = str_replace("<", "&lt;", str_replace(">", "&gt;", $obj->content));
		echo json_encode($obj);
	}
	
	public function SaveSettings()//Save Hadoop Setting Action
	{
		$filename = $this->input->post('filename');
		$name = $this->input->post('name');
		$value = $this->input->post('value');
		$desc = $this->input->post('desc');
		$ip = $this->input->post('ip');
		
		$this->load->model('exa_settings_model', 'sets');
		
		$setting = $this->sets->MakeHadoopSettings($name, $value, $desc, $ip);
		$this->sets->CreateNodeSettings($filename, $setting, $ip);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function SaveEditSettings()//save hadoop edit modal settings
	{
		$filename = $this->input->post('filename');
		$set_id = $this->input->post('set_id');
		$content = $this->input->post('content');
		$ip = $this->input->post('ip');
		
		$this->load->model('exa_settings_model', 'sets');
		$this->sets->SaveEditSettings($set_id, $filename, $content, $ip);
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function RemoveSettings()
	{
		$set_id = $this->input->post('set_id');
		$this->load->model('exa_settings_model', 'sets');
		$this->sets->RemoveSettings($set_id);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function ViewRackAware()
	{
		$this->load->model('exa_settings_model','sets');
		$rack = $this->sets->MakeRackAware();
		echo str_replace("\n", "<br/>", str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $rack['content']));
	}
	
	public function ViewEtcHosts()
	{
		$this->load->model('exa_settings_model', 'sets');
		$hosts = $this->sets->MakeEtcHosts();
		echo str_replace("\n", "<br />", $hosts);
	}
	
	public function PushRackAware()
	{
		$this->load->model('exa_settings_model', 'sets');
		$html = $this->sets->PushRackAware();
		echo $html;
	}
	
	public function PushEtcHosts()
	{
		$this->load->model('exa_settings_model', 'sets');
		$html = $this->sets->PushEtcHosts();
		echo $html;
	}
	
	public function PushGlobalSettings()
	{
		$this->load->model('exa_settings_model', 'sets');
		$html = $this->sets->PushGlobalSettings();
		$this->sets->PushNodeSettings();
		echo $html;
	}
	
	public function PushNodeSettings()
	{
		$this->load->model('exa_settings_model', 'sets');
		$html = $this->sets->PushNodeSettings();
		echo $html;
	}
	
	public function SaveEnv()
	{
		$filename = $this->input->post('filename');
		$content = $this->input->post('value');
		$ip = $this->input->post('ip');
		$this->load->model('exa_settings_model', 'sets');
		$this->sets->CreateNodeSettings($filename, $content, $ip);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function SaveOthers()
	{
		$filename = $this->input->post('filename');
		$content = $this->input->post('value');
		$ip = $this->input->post('ip');
		$this->load->model('exa_settings_model', 'sets');
		$this->sets->CreateNodeSettings($filename, $content, $ip);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function GetHiveSettings($pId)
	{
		$this->load->model('hive/exa_hive_settings_model', 'sets');
		$obj = $this->sets->GetHiveSettingById($pId);
		echo str_replace(">","&gt;",str_replace("<","&lt;",json_encode($obj)));
	}
	
	public function SaveHiveEnv()
	{
		$filename = $this->input->post('filename');
		$content = $this->input->post('value');
		$ip = $this->input->post('ip');
		$this->load->model('hive/exa_hive_settings_model', 'hives');
		$this->hives->CreateHiveSettings($filename, $content, $ip);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function SaveHiveSettings()
	{
		$filename = $this->input->post('filename');
		$name = $this->input->post('name');
		$value = $this->input->post('value');
		$desc = $this->input->post('desc');
		$ip = $this->input->post('ip');
		
		$this->load->model('hive/exa_hive_settings_model', 'hives');
		
		
		$setting = $this->hives->MakeHiveSettings($name, $value, $desc);
		var_dump($this->hives->CreateHiveSettings($filename, $setting, $ip));
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function RemoveHiveSettings()
	{
		$set_id = $this->input->post('set_id');
		$this->load->model('hive/exa_hive_settings_model', 'hives');
		$this->hives->RemoveHiveSettings($set_id);
		$this->load->helper('url');
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function PushHiveSettings()
	{
		$this->load->model('hive/exa_hive_settings_model', 'hives');
		$html = $this->hives->PushHiveSettings();
		echo $html;
	}
}

?>