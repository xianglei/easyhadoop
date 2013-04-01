<?php

class Manage extends CI_Controller
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
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_local_root_user'] = $this->lang->line('common_local_root_user');
		$data['common_local_root_pass'] = $this->lang->line('common_local_root_pass');
		$data['common_start_admin_server'] = $this->lang->line('common_start_admin_server');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		#getnarate host manager left bar
		$data['common_ping_node'] = $this->lang->line('common_ping_node');
		$data['common_add_node'] = $this->lang->line('common_add_node');
		$data['common_start_admin_server'] = $this->lang->line('common_start_admin_server');
		
		$data['common_remove_node'] = $this->lang->line('common_remove_node');
		$data['common_modify_node'] = $this->lang->line('common_modify_node');
		$data['common_add_node_tips'] = $this->lang->line('common_add_node_tips');
		$this->load->view('ehm_hosts_manage_nav', $data);
		
		#generate host manager right list
		$this->load->model('ehm_hosts_model', 'hosts');
		$data['common_add_node'] = $this->lang->line('common_add_node');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_node_role'] = $this->lang->line('common_node_role');
		$data['common_create_time'] = $this->lang->line('common_create_time');
		$data['common_action'] = $this->lang->line('common_action');
		$data['common_online'] = $this->lang->line('common_online');
		$data['common_offline'] = $this->lang->line('common_offline');
		
		$data['common_modify_node'] = $this->lang->line('common_modify_node');
		$data['common_remove_node'] = $this->lang->line('common_remove_node');
		$data['common_node_operating'] = $this->lang->line('common_node_operating');
		$data['common_set_hdfs_storage'] = $this->lang->line('common_set_hdfs_storage');
		$data['common_storage_setting'] = $this->lang->line('common_storage_setting');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip_addr'] = $this->lang->line('common_ip_addr');
		$data['common_role_name'] = $this->lang->line('common_role_name');
		$data['common_root_user'] = $this->lang->line('common_root_user');
		$data['common_root_pass'] = $this->lang->line('common_root_pass');
		$data['common_rackaware_position'] = $this->lang->line('common_rackaware_position');
		
		#genarate pagination
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/manage/index/';
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
		
		$this->load->view('ehm_hosts_manage_list',$data);
		
		$data['common_submit'] = $this->lang->line('common_submit');
		$this->load->view('add_hadoop_modal',$data);
		$this->load->view('start_admin_server_modal');
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	public function  StartAdminServer()
	{
		$this->load->model('ehm_hosts_model','hosts');
		$status = $this->hosts->start_admin_server($this->input->post('ssh_user'),$this->input->post('ssh_pass'));	
		redirect($this->config->base_url() . 'index.php/manage/index/');
	
	}
	public function PingAdminNode()
	{
		$this->load->model('ehm_hosts_model','hosts');
		echo $this->hosts->ping_admin_host();
	}
	public function PingNode()
	{
		$host_id = $this->uri->segment(3,0);
		$this->load->model('ehm_hosts_model','hosts');
		if($host_id == "" || $host_id == "0")
		{
			$status = '{"status":"Invalid Host ID","ip":""}';
		}
		else
		{
			$status = $this->hosts->ping_host($host_id);
		}
		echo $status;
	}
	
	public function AddHadoopNode()
	{
		$this->load->model('ehm_hosts_model', 'hosts');
		$hostname = $this->input->post('hostname');
		$ip = $this->input->post('ipaddr');
		$role = join(',', $this->input->post('role'));
		$ssh_user = $this->input->post('ssh_user');
		$ssh_pass = $this->input->post('ssh_pass');
		$rack = $this->input->post('rack');
		$this->hosts->insert_host($hostname, $ip, $role, $ssh_user, $ssh_pass, $rack);

		redirect($this->config->base_url() . 'index.php/manage/index/');
	}
	
	public function DeleteHadoopNode()
	{
		$host_id = $this->input->post('host_id');
		$this->load->model('ehm_hosts_model', 'hosts');
		$this->load->model('ehm_management_model', 'manage');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$role = $result->role;
		$ip = $result->ip;
		$tmp = explode(",", $role);
		foreach($tmp as $k => $v)
		{
			$this->manage->control_hadoop($ip, $v , 'stop');
		}
		$this->hosts->delete_host($host_id);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function UpdateHadoopNode()
	{
		$host_id = $this->input->post('host_id');
		$this->load->model('ehm_hosts_model', 'hosts');
		$hostname = $this->input->post('hostname');
		$ip = $this->input->post('ipaddr');
		$role = join(',', $this->input->post('role'));
		$ssh_user = $this->input->post('ssh_user');
		$ssh_pass = $this->input->post('ssh_pass');
		$rack = $this->input->post('rack');
		
		$this->hosts->update_host($host_id, $hostname, $ip, $role, $ssh_user = '', $ssh_pass = '', $rack);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function GetMountPoint($host_id)
	{
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		$this->load->model('ehm_installation_model', 'install');
		$arr = $this->install->get_mount_point($ip);
		echo json_encode($arr);
	}
	
	public function AddMountPoint()
	{
		$host_id = $this->input->post('host_id');
		$mount_point = $this->input->post('mount_point');
		
		$this->load->model('ehm_hosts_model', 'hosts');
		$result = $this->hosts->get_host_by_host_id($host_id);
		$ip = $result->ip;
		
		$this->load->model('ehm_installation_model', 'install');
		$str = $this->install->set_mount_point($host_id, $ip, $mount_point);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url);
	}
	
	public function __destruct()
	{
		
	}
}

?>