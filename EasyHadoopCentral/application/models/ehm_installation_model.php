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
	}

	#Push one installation file once, cause of need to return progress
	public function push_installation_file($host, $filename)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
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
	
	public function push_setting_files($host, $filename, $content)
	{
		$this->ehm_host = $host;
		$this->ehm_port = $this->config->item('ehm_port');
		$this->socket = new TSocket($this->ehm_host, $this->ehm_port);
		$this->socket->setSendTimeout(10000);
		$this->socket->setRecvTimeout(10000);
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
}