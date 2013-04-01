<?php

class About extends CI_Controller
{
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
		$data['common_hdfs_manage'] = $this->lang->line('common_hdfs_manage');
		$this->load->view('nav_bar', $data);
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$this->load->view('about',$data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#generaet footer
		$this->load->view('footer', $data);
	}
}

?>