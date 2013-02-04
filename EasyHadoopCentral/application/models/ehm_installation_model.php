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
		$this->ehm_port = $this->config->item('ehm_port');
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
				$str = '{"filename":"'.$this->config->item('dest_folder') . $filename.'","status":"success","node":"'.$host.'"}';
			else:
				$str = '{"filename":"'.$this->config->item('dest_folder') . $filename.'","status":"'.$str.'","node":"'.$host.'"}';
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
		$this->ehm_port = $this->config->item('ehm_port');
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(100000);
		$this->socket->setRecvTimeout(100000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		try
		{
			$this->transport->open();
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
		$this->ehm_port = $this->config->item('ehm_port');
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
		$this->ehm_port = $this->config->item('ehm_port');
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try{
			$this->transport->open();
			if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('hadoop_filename')))
			{
				$command = "cd ".$this->config->item('dest_folder')."
							rpm -Uvh ".$this->config->item('hadoop_filename')."
							echo 'export HADOOP_HOME=/usr' >> /etc/profile
							source /etc/profile
							";
			}
			else
			{
				$command = "mkdir -p ".$this->config->item('dest_folder')."
							cd ".$this->config->item('dest_folder')."
							wget http://".$this->config->item('packages_source_address')."/hadoop/".$this->config->item('hadoop_filename')."
							rpm -Uvh ". $this->config->item('hadoop_filename') ."
							echo 'export HADOOP_HOME=/usr' >> /etc/profile
							source /etc/profile
							";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('jdk_filename')))
			{
				$command = "cd ".$this->config->item('dest_folder')."
							rpm -Uvh ".$this->config->item('jdk_filename')."
							echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile
							";
			}
			else
			{
				$command = "mkdir -p ".$this->config->item('dest_folder')."
							cd ".$this->config->item('dest_folder')."
							wget http://".$this->config->item('packages_source_address')."/jdk/".$this->config->item('jdk_filename')."
							rpm -Uvh ".$this->config->item('jdk_filename')."
							echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile
							";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzop_filename')))
			{
				$command = "cd ".$this->config->item('dest_folder')."
							tar zxf ".$this->config->item('lzop_filename')."
							cd lzop-1.03
							./configure
							make
							make install clean
							";
			}
			else
			{
				$command = "mkdir -p ".$this->config->item('dest_folder')."
							cd ".$this->config->item('dest_folder')."
							wget http://".$this->config->item('packages_source_address')."/resources/".$this->config->item('lzop_filename')."
							tar zxf ".$this->config->item('lzop_filename')."
							cd lzop-1.03 
							./configure 
							make 
							make install clean
							";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('gpl_filename')))
			{
				$command = "cd ".$this->config->item('dest_folder')."
							rpm -Uvh ".$this->config->item('gpl_filename')."
							cp -rf /opt/hadoopgpl/lib/* /usr/lib/
							cp -rf /opt/hadoopgpl/lib/* /usr/lib64/
							cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/
							cp -rf /opt/hadoopgpl/native /usr/share/hadoop/lib/
							cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib
							cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64
							";
			}
			else
			{
				$command = "mkdir -p ".$this->config->item('dest_folder')."
							cd ".$this->config->item('dest_folder')."
							wget http://".$this->config->item('packages_source_address')."/resources/x64/".$this->config->item('gpl_filename')."
							rpm -Uvh ".$this->config->item('gpl_filename')."
							cp -rf /opt/hadoopgpl/lib/* /usr/lib/
							cp -rf /opt/hadoopgpl/lib/* /usr/lib64/
							cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/
							cp -rf /opt/hadoopgpl/native /usr/share/hadoop/lib/
							cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib
							cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64
							";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$ver = $this->ehm->GetSysVer();
			if(trim($ver) == "5")
			{
				if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzo_el5_rpm_filename')))
				{
					$command = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_filename')
								."";
				}
				else
				{
					$command = "mkdir -p ".$this->config->item('dest_folder')."
								cd ".$this->config->item('dest_folder')."
								wget http://".$this->config->item('packages_source_address')."/resources/x64/".$this->config->item('lzo_el5_rpm_filename')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_filename')
								."";
				}
				$str = $this->ehm->RunCommand($command);

				if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzo_el5_rpm_devel_filename')))
				{
					$command = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_devel_filename')
								."";
				}
				else
				{
					$command = "mkdir -p ".$this->config->item('dest_folder')."
								cd ".$this->config->item('dest_folder')."
								wget http://".$this->config->item('packages_source_address')."/resources/x64/".$this->config->item('lzo_el5_rpm_devel_filename')."
								rpm -Uvh ".$this->config->item('lzo_el5_rpm_devel_filename')
								."";
				}
				$str .=$this->ehm->RunCommand($command);
			}
			elseif(trim($ver) == "6")
			{
				if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzo_el6_rpm_filename')))
				{
					$command = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ". $this->config->item('lzo_el6_rpm_filename')
								."";
				}
				else
				{
					$command = "mkdir -p ".$this->config->item('dest_folder')."
								cd ".$this->config->item('dest_folder')."
								wget http://".$this->config->item('packages_source_address')."/resources/x64/".$this->config->item('lzo_el6_rpm_filename')."
								rpm -Uvh ".$this->config->item('lzo_el6_rpm_filename')
								."";
				}
				$str = $this->ehm->RunCommand($command);

				if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzo_el6_rpm_devel_filename')))
				{
					$command = "cd ".$this->config->item('dest_folder')."
								rpm -Uvh ".$this->config->item('lzo_el6_rpm_devel_filename')
								."";
				}
				else
				{
					$command = "mkdir -p ".$this->config->item('dest_folder')."
								cd ".$this->config->item('dest_folder')."
								wget http://".$this->config->item('packages_source_address')."/resources/x64/".$this->config->item('lzo_el6_rpm_devel_filename')."
								rpm -Uvh ".$this->config->item('lzo_el6_rpm_devel_filename')
								."";
				}
				$str .= $this->ehm->RunCommand($command);
			}
			else
			{
				$str =  "Unknown Operation System";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			if($this->ehm->FileExists($this->config->item('dest_folder') . $this->config->item('lzo_filename')))
			{
				$command = "cd ".$this->config->item('dest_folder')."
							tar zxf ".$this->config->item('lzo_filename')."
							cd lzo-2.06
							./configure
							make
							make install clean
							";
			}
			else
			{
				$command = "mkdir -p ".$this->config->item('dest_folder')."
							cd ".$this->config->item('dest_folder')."
							wget http://".$this->config->item('packages_source_address')."/resources/".$this->config->item('lzo_filename')."
							tar zxf ".$this->config->item('lzo_filename')."
							cd lzo-2.06
							./configure
							make
							make install clean
							";
			}
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);

		try
		{
			$this->transport->open();
			$str = $this->ehm->GetSysVer();// 5 or 6
			$this->transport->close();
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		$cmd = ' df -lhT  | grep -v tmpfs | grep -v boot | grep -v usr | grep -v tmp | sed \'1d;/ /!N;s/\n//;s/[ ]*[ ]/\t/g;\' ';
		try
		{
			$this->transport->open();
			$str = $this->ehm->RunCommand($cmd);
			$this->transport->close();
			
			$tmp1 = explode("\n", $str);
			$arr = array();
			for ($i = 0; $i < count($tmp1); $i++)
			{
				if($tmp1[$i] != "")
				{
					$tmp2 = explode("\t", $tmp1[$i]);
					$arr['file_system'][$i] = $tmp2[0];
					$arr['type'][$i] = $tmp2[1];
					$arr['size'][$i] = $tmp2[2];
					$arr['used'][$i] = $tmp2[3];
					$arr['avail'][$i] = $tmp2[4];
					$arr['used_percent'][$i] = $tmp2[5];
					$arr['mounted_on'][$i] = $tmp2[6];
				}
			}
			$str = $arr;
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
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(300000);
		$this->socket->setRecvTimeout(300000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->ehm = new EasyHadoopClient($this->protocol);
		
		if($mount_list[0] != "")
		{
			$cmd = "";
			$mount_name = "";
			$mount_sname = "";
			$mount_data = "";
			$mount_mrlocal = "";
			$mount_mrsystem = "";
			for($i = 0; $i < count($mount_list); $i++)
			{
				$mount_name .= $mount_list[$i] . "/hdfs/name,";
				$mount_sname .= $mount_list[$i] . "/hdfs/snn,";
				$mount_data .= $mount_list[$i] . "/hdfs/data,";
				$mount_mrlocal .= $mount_list[$i] . "/hdfs/mrlocal,";
				$mount_mrsystem .= $mount_list[$i] . "/hdfs/mrsystem,";
				$cmd .= "mkdir -p ".$mount_list[$i]."/hdfs;" . "chown -R hadoop:hadoop ".$mount_list[$i]. "/hdfs;";
			}
			
			$mount_name = substr($mount_name,0,-1);
			$mount_sname = substr($mount_sname,0,-1);
			$mount_data = substr($mount_data,0,-1);
			$mount_mrlocal = substr($mount_mrlocal,0,-1);
			$mount_mrsystem = substr($mount_mrsystem,0,-1);
			
			$sql = "update ehm_hosts set mount_name = '".$mount_name."', mount_data = '".$mount_data."', mount_mrlocal = '". $mount_mrlocal ."', mount_mrsystem = '". $mount_mrsystem ."', mount_snn = '". $mount_sname ."' where host_id='".$host_id."'";
			$this->db->simple_query($sql);
			
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
		}
		else
		{
			$str = "Empty mount list!";
		}
		return $str;
	}
	
}