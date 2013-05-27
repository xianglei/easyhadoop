<?php
/*
install_environment($ip) --> user_add_group_add($ip) -->  install_lzo_rpm($ip) --> install_lzo($ip) --> install_lzop($ip) 
--> install_jdk($ip) --> install_hadoop($ip) --> install_hadoopgpl --> end
*/
class Install extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
	}
	
	public function Index()
	{
		#Generate header
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_role_name'] = $this->lang->line('common_role_name');
		$data['common_remove_node_tips'] = $this->lang->line('common_remove_node_tips');
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
		
		#getnarate host manager left bar
		$this->load->view('ehm_hosts_install_nav', $data);
		
		$data['common_install_environment'] = $this->lang->line('common_install_environment');
		$data['common_install_java'] = $this->lang->line('common_install_java');
		$data['common_install_hadoop'] = $this->lang->line('common_install_hadoop');
		$data['common_install_lzo'] = $this->lang->line('common_install_lzo');
		$data['common_install_lzop'] = $this->lang->line('common_install_lzop');
		$data['common_install_hadoopgpl'] = $this->lang->line('common_install_hadoopgpl');
		$data['common_install_complete'] = $this->lang->line('common_install_complete');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_push_hadoop_files'] = $this->lang->line('common_push_hadoop_files');
		$data['common_submit_to_install'] = $this->lang->line('common_submit_to_install');
		
		#generate host manager right list
		$this->load->model('ehm_hosts_model', 'hosts');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_action'] = $this->lang->line('common_action');
		#genarate pagination
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/install/index/';
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
		
		$this->load->view('ehm_hosts_install_list',$data);
		
		
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function PushFiles()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$filename = $this->uri->segment(4,0);
		$this->load->model('ehm_installation_model', 'install');
		
		$file_list_array = $this->install->get_file_list();
		$json = "";
		sleep(1);
		foreach ($file_list_array as $v):
			$json .= $this->install->push_installation_file($ip, $v)."<br />";
		endforeach;
		echo $json;
	}
	
	/*public function Environment()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_environment($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function LzoRpm()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_lzo_rpm($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function Lzo()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_lzo($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function Jdk()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_java($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function Lzop()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_lzop($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function Hadoop()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_hadoop($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	/*public function Gpl()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_hadoopgpl($ip);
		echo str_replace("\n","<br />",$html);
	}*/
	
	public function Bin()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$html = $this->install->install_bin($ip);
		echo str_replace("\n","<br />",$html);
	}
}

?>