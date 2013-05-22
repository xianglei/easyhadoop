<?php

class Ehm_installation_model extends CI_Model
{
	public $ehm_host;
	public $ehm_port;
	public $socket;
	public $transport;
	public $protocol;
	public $ehm;

	public function __construct()
	{ 
		parent::__construct();
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/EasyHadoop/EasyHadoop.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TTransport.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
		
		$this->load->database();
	}

	#Push one installation file once, cause of need to return progress
	public function push_installation_file($host, $filename)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$content = read_file($this->config->item('src_folder') . $filename);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->FileTransfer($this->config->item('dest_folder') . $filename, $content);
			unset ($content);
			if (trim($str) == ""):
				$str = 'filename: '.$this->config->item('dest_folder') . $filename.' ==> status: success" ==> node: '.$host;
			else:
				$str = 'filename: '.$this->config->item('dest_folder') . $filename.' ==> status: '.$str.' ==> node: '.$host;
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function push_rackaware($host, $rack)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$content = $rack['content'];
		$filename = $rack['filename'];
		$chmod = $rack['chmod'];
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->FileTransfer($filename, $content);
			$command = "chmod " . $chmod . " ".$filename;
			$str .=$this->ehm->RunCommand($command);
			if (trim($str) == ""):
				$str = '{"filename":"'. $filename.'","status":"success","node":"'.$host.'"}';
			else:
				$str = '{"filename":"'. $filename.'","status":"'.$str.'","node":"'.$host.'"}';
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function push_setting_files($host, $filename, $content)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(100000);
		$this->socket->setRecvTimeout(100000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		if(preg_match("/\/etc\/hadoop/i",$filename))
		{
			$filename = $filename;
		}
		else
		{
			$filename = "/etc/hadoop/" . $filename;
		}
		try
		{
			$this->transport->open();
			$content = htmlspecialchars_decode($content);
			$str = $this->ehm->FileTransfer($filename, $content);
			if (trim($str) == ""):
				$str = '{"filename":"'.$filename.'","status":"success","node":"'.$host.'"}';
			else:
				$str = '{"filename":"'.$filename.'","status":"'.$str.'","node":"'.$host.'"}';
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function push_files($host, $filename, $content)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(100000);
		$this->socket->setRecvTimeout(100000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$content = htmlspecialchars_decode($content);
			$str = $this->ehm->FileTransfer($filename, $content);
			if (trim($str) == ""):
				$str = '{"filename":"'.$filename.'","status":"success","node":"'.$host.'"}';
			else:
				$str = '{"filename":"'.$filename.'","status":"'.$str.'","node":"'.$host.'"}';
			endif;
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function execute_shell_script($host, $commands)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$socket->setSendTimeout(300000);
		$socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($commands);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}

	public function user_add_group_add($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$command = 'groupadd '.$this->config->item('hadoop_group').'
						useradd '.$this->config->item('hadoop_user').' -g '.$this->config->item('hadoop_group').'
						mkdir -p ' . $this->config->item('dest_folder'). '
						chown -R '.$this->config->item('hadoop_user').':'.$this->config->item('hadoop_group').' '.$this->config->item('dest_folder')
						. '';
			$this->transport->open();
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}

	public function install_hadoop($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try{
			$this->transport->open();
			$command = "cd ".$this->config->item('dest_folder')."
						rpm -Uvh ".$this->config->item('hadoop_filename')."
						echo 'export HADOOP_HOME=/usr' >> /etc/profile
						source /etc/profile
						";
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_java($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$command = "cd ".$this->config->item('dest_folder')."
						rpm -Uvh ".$this->config->item('jdk_filename')."
						echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile
						";
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_lzop($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$command = "cd ".$this->config->item('dest_folder')."
						tar zxf ".$this->config->item('lzop_filename')."
						cd lzop-1.03
						./configure
						make
						make install clean
						";
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_hadoopgpl($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$command = "cd ".$this->config->item('dest_folder')."
						rpm -Uvh ".$this->config->item('gpl_filename')."
						cp -rf /opt/hadoopgpl/lib/* /usr/lib/
						cp -rf /opt/hadoopgpl/lib/* /usr/lib64/
						cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/
						cp -rf /opt/hadoopgpl/native /usr/share/hadoop/lib/
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64
						";
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_lzo_rpm($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$token = $this->config->item('token');

		$sys_json = $this->get_sys_version($host);
		$json = json_decode($sys_json,TRUE);
		
		$cmd_lib = "";
		$cmd_lib_dev = "";
		if($json['os.system'] == "centos" || $json['os.system'] == "redhat" || $json['os.system'] == "CentOS")
		{
			if(intval($json['os.version']) < 6)
			{
				$cmd_lib = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_filename')
								."";
				$cmd_lib_dev = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_devel_filename')
								."";
			}
			elseif(intval($json['os.version']) >= 6)
			{
				$cmd_lib = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ". $this->config->item('lzo_el6_rpm_filename')
								."";
				$cmd_lib_dev = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el6_rpm_devel_filename')
								."";
			}
			else
			{
				return "Unsupport system version";
			}
		}
		elseif($json['os.system'] == "ubuntu" || $json['os.system'] == 'debian' || $json['os.system'] == "Ubuntu")
		{
			$cmd_lib = "apt-get -y install liblzo2-2";
			$cmd_lib_dev = "apt-get -y install liblzo2-dev";
		}
		elseif($json['os.system'] == "suse" || $json['os.system'] == "SuSE")
		{
			$cmd_lib = "zypper -y install lzo";
			$cmd_lib_dev = "zypper -y install lzo-devel";
		}
		else
		{
			return "Unknown system";
		}
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($cmd_lib);
			$str .=$this->ehm->RunCommand($cmd_lib_dev);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_lzo($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$command = "cd ".$this->config->item('dest_folder')."
						tar zxf ".$this->config->item('lzo_filename')."
						cd lzo-2.06
						./configure
						make
						make install clean
						";
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function install_environment($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(3000000);
		$this->socket->setRecvTimeout(3000000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		$command = "yum -y install lrzsz gcc gcc-c++ libstdc++-devel make automake autoconf ntp wget pcre pcre-devel sudo pexpect zlib zlib-devel libxml2 libxml2-devel sysstat
					ntpdate cn.pool.ntp.org
					chmod 644 /etc/sudoers
					sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers
					chmod 440 /etc/sudoers
					";

		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($command);
			$this->transport->close();
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		sleep(1);
		return $str;
	}
	
	public function get_sys_version($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');

		try
		{
			$url = 'http://'.$this->ehm_host.':'.$this->ehm_port.'/node/dist/'.$token;
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function get_file_list()
	{
		$folder = $this->config->item('src_folder');
		$file_list_array = get_filenames($folder);
		return $file_list_array;
	}
	
	public function get_mount_point($host)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_http_port');
		$token = $this->config->item('token');
		
		$url = 'http://'.$this->ehm_host.':'.$this->ehm_port.'/node/GetHddInfo/'.$token;
		try
		{
			$str = @file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = 'Caught exception: '.  $e->getMessage(). "\n";
		}
		return $str;
	}
	
	public function set_mount_point($host_id, $host, $mount_list = array())
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('agent_thrift_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		if($mount_list[0] != "")
		{
			$cmd = "";
			$cmd_hdfs = "";
			$cmd_mapred = "";
			$mount_name = "";
			$mount_sname = "";
			$mount_data = "";
			$mount_mrlocal = "";
			$mount_mrsystem = "";
			for($i = 0; $i < count($mount_list); $i++)
			{
				if($mount_list[$i] == "/")
				{
					$mount_list[$i] = '';
				}
				$mount_name .= $mount_list[$i] . "/dfs/name,";
				$mount_sname .= $mount_list[$i] . "/dfs/snn,";
				$mount_data .= $mount_list[$i] . "/dfs/data,";
				$cmd_hdfs .= "mkdir -p ".$mount_list[$i]."/dfs; " . "chown -R hdfs:hadoop ".$mount_list[$i]. "/dfs;";
			}
			
			for($i = 0; $i < count($mount_list); $i++)
			{
				if($mount_list[$i] == "/")
				{
					$mount_list[$i] = '';
				}
				$mount_mrlocal .= $mount_list[$i] . "/mapred/local,";
				$mount_mrsystem .= $mount_list[$i] . "/mapred/system,";
				$cmd_mapred .= "mkdir -p ".$mount_list[$i]."/mapred; " . "chown -R mapred:hadoop ".$mount_list[$i]. "/mapred;";
			}
			
			$cmd = $cmd_hdfs . $cmd_mapred;
			
			$mount_name = substr($mount_name,0,-1);
			$mount_sname = substr($mount_sname,0,-1);
			$mount_data = substr($mount_data,0,-1);
			$mount_mrlocal = substr($mount_mrlocal,0,-1);
			$mount_mrsystem = substr($mount_mrsystem,0,-1);
			
			$sql = "update ehm_hosts set mount_name = '".$mount_name."', mount_data = '".$mount_data."', mount_local = '". $mount_mrlocal ."', mount_system = '". $mount_mrsystem ."', mount_snn = '". $mount_sname ."' where host_id='".$host_id."'";
			$this->db->simple_query($sql);
			
			try
			{
				$this->transport->open();
				echo $str = $this->ehm->RunCommand($cmd);
				$this->transport->close();
			}
			catch(Exception $e)
			{
				$str = 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		else
		{
			$str = "Empty mount list!";
		}
		return $str;
	}
	
	public function check_namenode_formatted()
	{
		$sql = "select ip, is_formatted from ehm_hosts where role like 'namenode%'";
		$query = $this->db->query($sql);
		$result = $query->result();
		$is_formatted = $result[0]->is_formatted;
		$ip = $result[0]->ip;
		if($is_formatted == 1)
		{
			return '{"status":"1","ip":"'.$ip.'"}';
		}
		else
		{
			return '{"status":"0","ip":"'.$ip.'"}';
		}
	}
	public function format_namenode()
	{
		$is_formatted = $this->check_namenode_formatted();
		$json = json_decode($is_formatted, TRUE);
		
		if($json['status'] == "0")
		{
			$this->ehm_host = $json['ip'];
			$this->ehm_port = $this->config->item('agent_thrift_port');
			$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
			$this->socket->setSendTimeout(30000);
			$this->socket->setRecvTimeout(30000);
			$this->transport = new TBufferedTransport($this->socket);
			$this->protocol = new TBinaryProtocol($this->transport);
			$this->ehm = new EasyHadoopClient($this->protocol);
		
			$cmd = "sudo -u hdfs hadoop namenode -format";
			try
			{
				$this->transport->open();
				$str = $this->ehm->RunCommand($cmd);
				$this->transport->close();
			}
			catch(Exception $e)
			{
				$str = 'Caught exception: '.  $e->getMessage(). "\n";
			}
			$sql = "update ehm_hosts set is_formatted = 1 where ip = '".$json['ip']."'";
			$this->db->simple_query($sql);
		}
		else
		{
			$str = "Already formatted";
		}
		return $str;
	}
}
