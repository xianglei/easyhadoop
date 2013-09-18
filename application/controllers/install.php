<?php
class Install extends CI_Controller
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
		$data['common_close'] = $this->lang->line('common_close');

		$data['common_install'] = $this->lang->line('common_install');
		$data['common_install_hadoop'] = $this->lang->line('common_install_hadoop');
		$data['common_install_hadoop_complete'] = $this->lang->line('common_install_hadoop_complete');
		$this->load->view('main/install/exa_main_install_lists', $data);

		$data['common_install_push_hadoop_files'] = $this->lang->line('common_install_push_hadoop_files');
		$this->load->view('main/install/exa_main_install_hadoop_modal' , $data);

		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}

	public function InstallHadoop($pNodeId)
	{
		$this->load->model('exa_install_model', 'install');
		$html = $this->install->InstallHadoopBin($pNodeId);
		echo str_replace("\n","<br />",$html);
	}

	public function PushHadoopBin($pIp)
	{
		$this->load->model('exa_install_model', 'install');
		$file_list = $this->install->GetHadoopFileList();//array
		$json = "";
		sleep(1);
		foreach ($file_list as $v):
			$json .= $this->install->PushHadoopBin($pIp, $v);
		endforeach;
		echo "[".$json."]";
	}

}
?>