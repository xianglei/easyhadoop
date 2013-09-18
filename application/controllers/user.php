<?php

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->load->helper('url');
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
		redirect($this->config->base_url() . 'index.php/monitor/index/');
	}
	
	public function Login()
	{
		$this->lang->load('commons');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_username'] = $this->lang->line('common_username');
		$data['common_password'] = $this->lang->line('common_password');
		$this->load->view('header',$data);
		$this->load->view('main/user/exa_main_user_login_form', $data);
		$this->load->view('footer');
	}

	public function LoginAction()
	{
		$this->load->model('exa_user_model', 'user');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$this->user->login($username, $password);
		$this->load->helper('url');
		redirect($this->config->base_url() . 'index.php/monitor/index/');
	}
	
	public function Logout()
	{
		$this->load->model('exa_user_model', 'user');
		$this->user->Logout();
		$this->load->helper('url');
		redirect($this->config->base_url());
	}
	
	public function UpdatePassword()
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
		
		$data['common_user_admin'] = $this->lang->line('common_user_admin');
		
		$data['common_change_passwd'] = $this->lang->line('common_change_passwd');
		$data['common_current_passwd'] = $this->lang->line('common_current_passwd');
		$data['common_input_passwd'] = $this->lang->line('common_input_passwd');
		$data['common_reinput_passwd'] = $this->lang->line('common_reinput_passwd');
		$data['common_submit'] = $this->lang->line('common_submit');
		
		
		#generate settings lists
		
		$data['common_submit'] = $this->lang->line('common_submit');
		
		$this->load->view('main/user/exa_main_user_chpasswd', $data);
		
		
		#generaet footer
		$this->load->view('footer', $data);
	}
	
	public function UpdatePasswordAction()
	{
		$this->load->model('exa_user_model', 'user');
		$login = $this->session->userdata('login');
		echo $this->user->UpdatePassword($login);
		$this->Logout();
	}
}

?>