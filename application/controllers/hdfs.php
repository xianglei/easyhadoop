<?php

class Hdfs extends CI_Controller
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
		redirect($this->config->base_url() . 'index.php/hdfs/listhdfs/');
	}
	
	public function ListHDFS()
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
		
		$data['common_save'] = $this->lang->line('common_save');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_hdfs_refreshnodes'] = $this->lang->line('common_hdfs_refreshnodes');
		$data['common_hdfs_report'] = $this->lang->line('common_hdfs_report');
		$data['common_hdfs_priv'] = $this->lang->line('common_hdfs_priv');
		$data['common_hdfs_user'] = $this->lang->line('common_hdfs_user');
		$data['common_hdfs_group'] = $this->lang->line('common_hdfs_group');
		$data['common_hdfs_date'] = $this->lang->line('common_hdfs_date');
		$data['common_hdfs_time'] = $this->lang->line('common_hdfs_time');
		$data['common_hdfs_size'] = $this->lang->line('common_hdfs_size');
		$data['common_hdfs_filename'] = $this->lang->line('common_hdfs_filename');
		$data['common_hdfs_other'] = $this->lang->line('common_hdfs_other');
		$data['common_hdfs_balancer'] = $this->lang->line('common_hdfs_balancer');
		$data['common_hdfs_remove'] = $this->lang->line('common_hdfs_remove');
		$data['common_hdfs_rename'] = $this->lang->line('common_hdfs_rename');
		$data['common_hdfs_read'] = $this->lang->line('common_hdfs_read');
		$data['common_hdfs_write'] = $this->lang->line('common_hdfs_write');
		$data['common_hdfs_execute'] = $this->lang->line('common_hdfs_execute');
		$data['common_hdfs_recursive'] = $this->lang->line('common_hdfs_recursive');
		$data['common_hdfs_input_dir_name'] = $this->lang->line('common_hdfs_input_dir_name');
		$data['common_hdfs_target_name'] = $this->lang->line('common_hdfs_target_name');
		$data['common_hdfs_summary_report'] = $this->lang->line('common_hdfs_target_name');
		$data['common_hdfs_chmod'] = $this->lang->line('common_hdfs_chmod');
		$data['common_hdfs_chown'] = $this->lang->line('common_hdfs_chown');
		$data['common_hdfs_mkdir'] = $this->lang->line('common_hdfs_mkdir');
		$data['common_action'] = $this->lang->line('common_action');
		$data['common_hdfs_safemode'] = $this->lang->line('common_hdfs_safemode');
		$data['common_hdfs_safemode_status'] = $this->lang->line('common_hdfs_safemode_status');
		$data['common_hdfs_safemode_leave'] = $this->lang->line('common_hdfs_safemode_leave');
		$data['common_hdfs_safemode_enter'] = $this->lang->line('common_hdfs_safemode_enter');
		$data['common_hdfs_start_balancer'] = $this->lang->line('common_hdfs_start_balancer');
		$data['common_hdfs_stop_balancer'] = $this->lang->line('common_hdfs_stop_balancer');
		$data['common_hdfs_balancer_log'] = $this->lang->line('common_hdfs_balancer_log');

		$this->load->model('exa_nodes_model','nodes');
		$nn = $this->nodes->GetNamenode();
		$data['nn_ip'] = $nn->ip;
		
		$this->load->view('main/hui/hdfs/exa_main_hdfs_list', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_mkdir_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_rename_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_remove_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_chmod_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_chown_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_report_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_refresh_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_safemode_get_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_safemode_enter_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_safemode_leave_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_start_balancer_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_stop_balancer_modal', $data);
		$this->load->view('main/hui/hdfs/exa_main_hdfs_balancer_log_modal', $data);
		
		$this->load->view('main/exa_main_footer', $data);

		$this->load->view('copyright');
		$this->load->view('benchmark');
		$this->load->view('footer',$data);
	}
	
	public function LS($pFolder = "")
	{
		if($pFolder == "")
		{
			$pFolder = base64_encode('/');
		}
		
		$folder = base64_decode($pFolder);
		
		if(($folder == "//../"))
		{
			$folder = "/";
		}
		$this->load->model('exa_hdfs_model', 'hdfs');
		$json = $this->hdfs->LsJson($folder);
		echo $json;
	}
	
	public function Mkdir()
	{
		$dir_name = $this->input->post('mkdir_dir_name');
		$prev_dir = $this->input->post('mkdir_sub_dir');
		
		if(substr($dir_name,0,1) == "/")//去掉第一个/符号
		{
			$dir_name = substr($dir_name,1);
		}
		
		if($prev_dir == "/")//判断是否根目录
		{
			$prev_dir = '';
		}
		$submit_dir = $prev_dir . '/' . $dir_name;// ''/ab /aa/ab
		if($dir_name == "")
		{
			redirect($this->config->base_url() . 'index.php/hdfs/listhdfs/');
		}
		$this->load->model('exa_hdfs_model', 'hdfs');
		$this->hdfs->Mkdir($submit_dir);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function Rename()
	{
		$src_dir = $this->input->post('rename_src_dir_name');
		$dest_dir = $this->input->post('rename_dest_dir_name');
		if($dest_dir == "")
		{
			redirect($this->config->base_url() . 'index.php/hdfs/listhdfs/');
		}
		$this->load->model('exa_hdfs_model', 'hdfs');
		$this->hdfs->Mv($src_dir, $dest_dir);

		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function Remove()
	{
		$src_dir = $this->input->post('remove_src_dir_name');
		if($src_dir == "")
		{
			redirect($this->config->base_url() . 'index.php/hdfs/listhdfs/');
		}
		$this->load->model('exa_hdfs_model', 'hdfs');
		$this->hdfs->Rmr($src_dir);

		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function Chmod()
	{
		$u = ($this->input->post('u') == "") ? 0 : array_sum($this->input->post('u'));
		$g = ($this->input->post('g') == "") ? 0 : array_sum($this->input->post('g'));
		$o = ($this->input->post('o') == "") ? 0 : array_sum($this->input->post('o'));
		$r = ($this->input->post('recursive') == "") ? FALSE : TRUE;
		$dir = $this->input->post('chmod_dir_name');
		
		$mod = strval($u.$g.$o);
		$this->load->model('exa_hdfs_model', 'hdfs');
		$this->hdfs->Chmod($dir, $mod, $r);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function Getmod($priv_string)
	{
		$this->load->model('exa_aux_model', 'aux');
		$json = $this->aux->priv_string_to_oct($priv_string);
		echo $json;
	}
	
	public function Chown()
	{
		$user = $this->input->post('chown_user');
		$group = $this->input->post('chown_group');
		$dir_name = $this->input->post('chown_dir_name');
		$r = ($this->input->post('recursive') == "") ? FALSE : TRUE;
		
		$this->load->model('exa_hdfs_model', 'hdfs');
		$this->hdfs->Chown($dir_name, $user, $group, $r);
		
		$url = $this->input->server('HTTP_REFERER');
		redirect($url, 'refresh');
	}
	
	public function Report()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = str_replace("\n","<br />", $this->hdfs->DFSAdminReport());
		$json = '{"content":"'.$str.'"}';
		echo $json;
	}
	
	public function RefreshNodes()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->DFSAdminRefreshNodes();
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
	
	public function SafemodeGet()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->SafemodeGet();
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
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->SafemodeEnter();
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
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->SafemodeLeave();
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
	
	public function StartBalancer()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->StartBalancer();
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
	
	public function BalancerLog()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->TailBalancerLog();
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
	
	public function StopBalancer()
	{
		$this->load->model('exa_hdfs_model', 'hdfs');
		$str = $this->hdfs->StopBalancer();
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