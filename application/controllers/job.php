<?php

class Job extends CI_Controller
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
		redirect($this->config->base_url() . 'index.php/job/joblist/');
	}
	
	public function JobList()
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
		
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_save'] = $this->lang->line('common_save');
		$data['common_hdfs_safemode'] = $this->lang->line('common_hdfs_safemode');
		$data['common_hdfs_safemode_status'] = $this->lang->line('common_hdfs_safemode_status');
		$data['common_hdfs_safemode_leave'] = $this->lang->line('common_hdfs_safemode_leave');
		$data['common_hdfs_safemode_enter'] = $this->lang->line('common_hdfs_safemode_enter');

		$this->load->model('exa_nodes_model', 'nodes');
		$jt = $this->nodes->GetJobtracker();
		$data['jt_ip'] = $jt->ip;

		$this->load->view('main/hui/job/exa_main_job_list', $data);
		$this->load->view('main/hui/job/exa_main_kill_job_modal', $data);
		$this->load->view('main/hui/job/exa_main_job_safemode_enter_modal', $data);
		$this->load->view('main/hui/job/exa_main_job_safemode_get_modal', $data);
		$this->load->view('main/hui/job/exa_main_job_safemode_leave_modal', $data);
		$this->load->view('main/hui/job/exa_main_job_refresh_nodes_modal', $data);
		$this->load->view('main/hui/job/exa_main_job_refresh_queues_modal', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function ListJob()
	{
		$this->load->model('exa_job_model', 'job');
		$json = $this->job->ListJobJson();
		echo $json;
	}
	
	public function JobStatus($pJobId)
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->JobStatus($pJobId);
		$json['content'] = str_replace("\n", "<br />", $str);
		echo json_encode($json);
	}
	
	public function KillJob()
	{
		$job_id = $this->input->post('job_kill_job_id');
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->KillJob($job_id);
		echo $str;
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function SafemodeGet()
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->SafemodeGet();
		if(trim($str) == "")
		{
			$str = "Done";
		}
		else
		{
			$str = str_replace("\n","<br />",$str);
		}
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
	
	public function SafemodeLeave()
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->SafemodeLeave();
		if(trim($str) == "")
		{
			$str = "Done";
		}
		else
		{
			$str = str_replace("\n","<br />",$str);
		}
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
	
	public function SafemodeEnter()
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->SafemodeEnter();
		if(trim($str) == "")
		{
			$str = "Done";
		}
		else
		{
			$str = str_replace("\n","<br />",$str);
		}
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
	
	public function RefreshNodes()
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->MRAdminRefreshNodes();
		if(trim($str) == "")
		{
			$str = "Done";
		}
		else
		{
			$str = str_replace("\n","<br />",$str);
		}
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
	
	public function RefreshQueues()
	{
		$this->load->model('exa_job_model', 'job');
		$str = $this->job->MRAdminRefreshQueues();
		if(trim($str) == "")
		{
			$str = "Done";
		}
		else
		{
			$str = str_replace("\n","<br />",$str);
		}
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
}

?>