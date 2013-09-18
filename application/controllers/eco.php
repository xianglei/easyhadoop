<?php

class Eco extends CI_Controller
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
		redirect($this->config->base_url() . 'index.php/eco/hadoop/');
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
		
		$data['common_eco_hadoop_operate'] = $this->lang->line('common_eco_hadoop_operate');
		$this->load->view('main/eco/hadoop/exa_main_hadoop_eco_nav', $data);
		
		$data['common_eco_hadoop_format'] = $this->lang->line('common_eco_hadoop_format');
		$data['common_eco_hadoop_format_tip'] = $this->lang->line('common_eco_hadoop_format_tip');
		$data['common_operates'] = $this->lang->line('common_operates');
		$data['common_hostname'] = $this->lang->line('common_hostname');
		$data['common_ip'] = $this->lang->line('common_ip');
		$data['common_action'] = $this->lang->line('common_action');
		$data['common_view_logs'] = $this->lang->line('common_view_logs');
		$data['common_start'] = $this->lang->line('common_start');
		$data['common_stop'] = $this->lang->line('common_stop');
		$data['common_restart'] = $this->lang->line('common_restart');
		$data['common_close'] = $this->lang->line('common_close');
		
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/eco/hadoop/';
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
		$this->load->view('main/eco/hadoop/exa_main_hadoop_eco_list', $data);
		$this->load->view('main/eco/hadoop/exa_main_hadoop_eco_logs_modal', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function HBase()
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
		$this->load->view('main/eco/hbase/exa_main_eco_hbase_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Hive()
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
		$this->load->view('main/eco/hive/exa_main_eco_hive_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Mahout()
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
		$this->load->view('main/eco/mahout/exa_main_eco_mahout_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function Pig()
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
		$this->load->view('main/eco/pig/exa_main_eco_pig_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function ZooKeeper()
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
		$this->load->view('main/eco/zookeeper/exa_main_eco_zookeeper_nonfree', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function GetPid($pId, $pRole)
	{
		$this->load->model('exa_hadoop_model', 'hadoop');
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetNode($pId);
		$ip = $node->ip;
		$hostname = $node->hostname;
		$ssh_user = $node->ssh_user;
		$ssh_pass = $node->ssh_pass;
		$sudo = $node->is_sudo;
		
		$json = $this->hadoop->GetPID($ip, $pRole);
		echo $json;
	}
	
	public function ViewLogs($pId, $pRole)
	{
		$this->load->model('exa_hadoop_model', 'hadoop');
		$this->load->model('exa_nodes_model', 'nodes');
		$node = $this->nodes->GetNode($pId);
		$ip = $node->ip;
		$hostname = $node->hostname;
		$ssh_user = $node->ssh_user;
		$ssh_pass = $node->ssh_pass;
		$sudo = $node->is_sudo;
		$role = $pRole;
		if($sudo == 0)
		{
			$sudo = FALSE;
		}
		else
		{
			$sudo = TRUE;
		}
		$html = $this->hadoop->ViewHadoopLogs($ip, $hostname, $role, $sudo, $ssh_user, $ssh_pass);
		echo str_replace("\n","<br/>",$html);
	}
	
	public function StartHadoop($pId, $pRole)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->model('exa_hadoop_model', 'hadoop');
		$this->load->model('exa_hdfs_model', 'hdfs');
		$node = $this->nodes->GetNode($pId);
		$ip = $node->ip;
		$hostname = $node->hostname;
		$ssh_user = $node->ssh_user;
		$ssh_pass = $node->ssh_pass;
		$sudo = $node->is_sudo;
		$role = $pRole;
		if($sudo == 0)
		{
			$sudo = FALSE;
		}
		else
		{
			$sudo = TRUE;
		}
		
		$html = $this->hadoop->HadoopControl($ip, $role, 'start', $sudo, $ssh_user, $ssh_pass);
		if($pRole == 'namenode')
		{
			$html .= $this->hdfs->Chmod('/', '777', TRUE);
		}
		echo $html;
		$this->load->helper('url');
		redirect($this->config->base_url() . 'index.php/eco/hadoop/');
	}
	
	public function StopHadoop($pId, $pRole)
	{
		$this->load->model('exa_nodes_model', 'nodes');
		$this->load->model('exa_hadoop_model', 'hadoop');
		$node = $this->nodes->GetNode($pId);
		$ip = $node->ip;
		$hostname = $node->hostname;
		$ssh_user = $node->ssh_user;
		$ssh_pass = $node->ssh_pass;
		$sudo = $node->is_sudo;
		$role = $pRole;
		if($sudo == 0)
		{
			$sudo = FALSE;
		}
		else
		{
			$sudo = TRUE;
		}
		
		$html = $this->hadoop->HadoopControl($ip, $pRole, 'stop', $sudo, $ssh_user, $ssh_pass);
		echo $html;
		$this->load->helper('url');
		redirect($this->config->base_url() . 'index.php/eco/hadoop/');
	}
}

?>