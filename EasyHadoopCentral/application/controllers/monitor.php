<?php

class Monitor extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
	}
	
	public function GetPid()
	{
		$host_id = $this->uri->segment(3,0);
		$role = $this->uri->segment(4,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_monitor_model', 'monitor');
		
		$json = $this->monitor->get_process_id($ip, $role);
		echo $json;
	}
	
	public function Index()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$this->load->view('ehm_hosts_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_action'] = $this->lang->line('common_action');
		
		$data['common_mem_free'] = $this->lang->line('common_mem_free');
		$data['common_mem_cached'] = $this->lang->line('common_mem_cached');
		$data['common_mem_buffers'] = $this->lang->line('common_mem_buffers');
		$data['common_mem_used'] = $this->lang->line('common_mem_used');
		$data['common_sample'] = $this->lang->line('common_sample');
		
		#generate pagination
		$this->load->model('ehm_hosts_model', 'hosts');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/index/';
		$config['total_rows'] = $this->hosts->count_hosts();
		$config['per_page'] = 10;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->hosts->get_hosts_list($config['per_page'], $offset);
		
		#get mem stat
		
		$this->load->view('ehm_hosts_monitor_list',$data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function MemStats()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = $result->role;
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		$json = json_decode($this->monitor->get_host_meminfo($ip));
		
		$mem['mem_total'] = $json->MemTotal;
		$mem['mem_free'] = $json->MemFree;
		$mem['mem_buffers'] = $json->Buffers;
		$mem['mem_cached'] = $json->Cached;
		$mem['mem_used'] = $json->MemUsed;
		
		$mem['mem_free_percent'] = round(($mem['mem_free'] / $mem['mem_total']) * 100);
		$mem['mem_buffers_percent'] = round(($mem['mem_buffers'] / $mem['mem_total']) * 100);
		$mem['mem_cached_percent'] = round(($mem['mem_cached'] / $mem['mem_total']) * 100);
		$mem['mem_used_percent'] = 100 - $mem['mem_free_percent'] - $mem['mem_cached_percent'] - $mem['mem_buffers_percent'];
		
		$mem['mem_total_abbr'] = $this->aux->get_byte_abbr($mem['mem_total'], 2, True);
		$mem['mem_free_abbr'] = $this->aux->get_byte_abbr($mem['mem_free'], 2, True);
		$mem['mem_buffers_abbr'] = $this->aux->get_byte_abbr($mem['mem_buffers'], 2, True);
		$mem['mem_cached_abbr'] = $this->aux->get_byte_abbr($mem['mem_cached'], 2, True);
		$mem['mem_used_abbr'] = $this->aux->get_byte_abbr($mem['mem_used'], 2, True);
		
		$json = json_encode($mem);
		
		echo $json;
	}
	
	public function DatanodeStats()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = "datanode";
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		
		$json = $this->monitor->get_datanode_jmx($ip);
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	public function GetAllStats()
	{
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_namenode_list();
		if(@$result[0]->host_id != "")
			$host_id  = $result[0]->host_id;
		else
			$host_id  = "0";		
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = "namenode";
		$this->load->model('ehm_monitor_model', 'monitor');
		
		
		$json = $this->monitor->get_namenode_jmx($ip);
		$jmx=json_decode($json,true);
		$array=array();
		//LiveNodes DeadNodes
        foreach($jmx['beans']  as $obj)
        {
				if($obj&&array_key_exists('DeadNodes',$obj))
                {
					$str=json_decode($obj["DeadNodes"],true );
					foreach($str as $k=>$v)
					{
                      $array[]=$k;
					}

                }

        }		
		
		//$array[]="slave";
		//$array[]="localhost.localdomain";
		echo json_encode($array);
	}
	public function NamenodeStats()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = "namenode";
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		
		$json = $this->monitor->get_namenode_jmx($ip);
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	
	public function JobtrackerStats()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = "jobtracker";
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		
		$json = $this->monitor->get_jobtracker_jmx($ip);
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	
	public function TasktrackerStats()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$role = "tasktracker";
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		
		$json = $this->monitor->get_tasktracker_jmx($ip);
		$json = $this->aux->parse_jmx_json($json, $role);
		
		echo $json;
	}
	
	public function HdfsStats()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$this->load->view('ehm_hosts_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_close'] = $this->lang->line('common_close');
		
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		$this->load->model('ehm_hosts_model', 'hosts');
		
		$result = $this->hosts->get_namenode_list();
		if(@$result[0]->host_id != "")
			$data['namenode_host_id'] = $result[0]->host_id;
		else
			$data['namenode_host_id'] = "0";
		unset ($result);
		
		#generate pagination
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/hdfsstats/';
		$config['total_rows'] = $this->hosts->count_hosts_by_role("datanode")->num_rows();
		$config['per_page'] = 10;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->hosts->get_datanode_list($config['per_page'], $offset);
		
		$this->load->view('ehm_namenode_hdfs_monitor', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function MapRedStats()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$this->load->view('ehm_hosts_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_map_status'] = $this->lang->line('common_map_status');
		$data['common_reduce_status'] = $this->lang->line('common_reduce_status');
		
		$this->load->model('ehm_monitor_model', 'monitor');
		$this->load->model('ehm_auxiliary_model', 'aux');
		$this->load->model('ehm_hosts_model', 'hosts');
		
		$result = $this->hosts->get_jobtracker_list();
		if(@$result[0]->host_id != "")
			$data['jobtracker_host_id'] = $result[0]->host_id;
		else
			$data['jobtracker_host_id'] = "0";
		unset ($result);
		
		#generate pagination
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/mapredstats/';
		$config['total_rows'] = $this->hosts->count_hosts_by_role("tasktracker")->num_rows();
		$config['per_page'] = 10;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->hosts->get_datanode_list($config['per_page'], $offset);
		
		$this->load->view('ehm_jobtracker_mapred_monitor', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function GetLoadAvg()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_monitor_model', 'monitor');
		
		$json = $this->monitor->get_host_loadavginfo($ip);
		echo $json;
	}
	
	public function LoadAvgStats()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$this->load->view('ehm_hosts_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_action'] = $this->lang->line('common_action');
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_active_pid'] = $this->lang->line('common_active_pid');
		$data['common_load_avg'] = $this->lang->line('common_load_avg');
		
		#generate pagination
		$this->load->model('ehm_hosts_model', 'hosts');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/loadavgstats/';
		$config['total_rows'] = $this->hosts->count_hosts();
		$config['per_page'] = 10;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->hosts->get_hosts_list($config['per_page'], $offset);
		
		#get mem stat
		
		$this->load->view('ehm_host_monitor_load_list',$data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function GetCpuInfo()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_monitor_model', 'monitor');
		
		$json = $this->monitor->get_host_cpuinfo($ip);
		echo $json;
	}
	
	public function CpuStats()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_storage_status'] = $this->lang->line('common_storage_status');
		$data['common_mem_status'] = $this->lang->line('common_mem_status');
		$data['common_cpu_status'] = $this->lang->line('common_cpu_status');
		$data['common_loadavg_status'] = $this->lang->line('common_loadavg_status');
		$data['common_mapred_status'] = $this->lang->line('common_mapred_status');
		$this->load->view('ehm_hosts_monitor_nav', $data);
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_action'] = $this->lang->line('common_action');
		
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_cpu_info'] = $this->lang->line('common_cpu_info');
		$data['common_cpu_usage'] = $this->lang->line('common_cpu_usage');
		
		#generate pagination
		$this->load->model('ehm_hosts_model', 'hosts');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/monitor/cpustats/';
		$config['total_rows'] = $this->hosts->count_hosts();
		$config['per_page'] = 10;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['results'] = $this->hosts->get_hosts_list($config['per_page'], $offset);
		
		#get mem stat
		
		$this->load->view('ehm_host_monitor_cpu_list',$data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function GetCpuUsage()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_monitor_model', 'monitor');
		
		$json = $this->monitor->get_host_cpuinfo_detail($ip);
		echo $json;
	}
	
	public function GetCpuUsageCore()
	{
		$host_id = $this->uri->segment(3,0);
		$cores = $this->uri->segment(4,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_monitor_model', 'monitor');
		
		$json = $this->monitor->get_host_cpuinfo_core_detail($ip, $cores);
		echo $json;
	}
}

?>