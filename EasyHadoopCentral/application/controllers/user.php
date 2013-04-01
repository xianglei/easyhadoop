<?php

class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
		redirect($this->config->base_url() . 'index.php/manage/index/');
	}
	
	public function Login()
	{
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_username'] = $this->lang->line('common_username');
		$data['common_password'] = $this->lang->line('common_password');
		$this->load->view('header',$data);
		$this->load->view('login_form');
		$this->load->view('footer');
	}

	public function LoginAction()
	{
		$this->load->model('ehm_user_model', 'user');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$this->user->login($username, $password);
		redirect($this->config->base_url() . 'index.php/manage/index/');
	}
	
	public function Logout()
	{
		$this->load->model('ehm_user_model', 'user');
		$this->user->log_out();
		redirect($this->config->base_url());
	}
	
	public function UpdatePassword()
	{
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
		
		$data['common_user_admin'] = $this->lang->line('common_user_admin');
		$this->load->view('ehm_hosts_user_nav', $data);
		
		$data['common_change_passwd'] = $this->lang->line('common_change_passwd');
		$data['common_current_passwd'] = $this->lang->line('common_current_passwd');
		$data['common_input_passwd'] = $this->lang->line('common_input_passwd');
		$data['common_reinput_passwd'] = $this->lang->line('common_reinput_passwd');
		$data['common_submit'] = $this->lang->line('common_submit');
		
		
		#generate settings lists
		
		$data['common_submit'] = $this->lang->line('common_submit');
		
		$this->load->view('ehm_hosts_user_list', $data);
		
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function UpdatePasswordAction()
	{
		$this->load->model('ehm_user_model', 'user');
		$login = $this->session->userdata('login');
		echo $this->user->update_password($login);
	}
}

?>