<?php

class Install
{
	
	/*
	 * 
	 */
	
	public function MakeDir($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "mkdir -p /home/hadoop && groupadd hadoop && useradd hadoop -g hadoop";
		$str = $client->RunCommand($command);
		return $str;
	}
	
	public function PushFile($pFilename, $pContent, $pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$filename = $pFilename;
		$content = $pContent;
		$str = $client->FileTransfer($filename, $content);
		return $str;
	}
	
	###########################################
	public function InstallEnvironment($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$command = "yum -y install dialog lrzsz gcc gcc-c++ libstdc++-devel make automake autoconf ntp wget pcre pcre-devel sudo 
					ntpdate cn.pool.ntp.org";
		$str = $client->RunCommand($command);
		return $str;
	}
	
	###########################################
	public function InstallJava($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$filename = "/home/hadoop/jdk-6u35-linux-amd64.rpm";
		
		if($client->FileExists($filename))
		{
			$command = "cd /home/hadoop/ 
						rpm -Uvh jdk-6u35-linux-amd64.rpm 
						echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile";
		}
		else
		{
			$command = "mkdir -p /home/hadoop
						cd /home/hadoop/ 
						wget http://113.11.199.230/jdk/jdk-6u35-linux-amd64.rpm 
						rpm -Uvh jdk-6u35-linux-amd64.rpm 
						echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile";
		}
		$str = $client->RunCommand($command);
		return $str;
	}
	
	#########################################
	public function InstallHadoop($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$filename = "/home/hadoop/hadoop-1.0.3-1.x86_64.rpm";
		if($client->FileExists($filename))
		{
			$command = "cd /home/hadoop/ 
						rpm -Uvh hadoop-1.0.3-1.x86_64.rpm 
						chmod 644 /etc/sudoers 
						sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers 
						chmod 440 /etc/sudoers 
						echo 'export HADOOP_HOME=/usr' >> /etc/profile 
						source /etc/profile 
						/usr/sbin/groupadd hadoop 
						/usr/sbin/useradd hadoop -g hadoop";
		}
		else
		{
			$command = "mkdir -p /home/hadoop/ 
						cd /home/hadoop/ 
						wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm 
						rpm -Uvh hadoop-1.0.3-1.x86_64.rpm 
						chmod 644 /etc/sudoers 
						sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers 
						chmod 440 /etc/sudoers 
						echo 'export HADOOP_HOME=/usr' >> /etc/profile 
						source /etc/profile 
						/usr/sbin/groupadd hadoop 
						/usr/sbin/useradd hadoop -g hadoop";
		}
		$str = $client->RunCommand($command); 
		return $str;
	}
	
	###########################################
	public function InstallLzo($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$ver = $client->GetSysVer();
		if(trim($ver) == "5")
		{
			$filename = "/home/hadoop/lzo-2.06-1.el5.rf.x86_64.rpm";
			if($client->FileExists($filename))
			{
				$command = "cd /home/hadoop 
							rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm";
			}
			else
			{
				$command = "mkdir -p /home/hadoop 
							cd /home/hadoop 
							wget http://113.11.199.230/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm 
							rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm";
			}
			$ret = $client->RunCommand($command);
			$filename = "/home/hadoop/lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			if($client->FileExists($filename))
			{
				$command = "cd /home/hadoop 
							rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			}
			else
			{
				$command = "mkdir -p /home/hadoop 
							cd /home/hadoop 
							wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm 
							rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			}
			$ret .=$client->RunCommand($command);
		}
		elseif(trim($ver) == "6")
		{
			$filename = "/home/hadoop/lzo-2.06-1.el6.rfx.x86_64.rpm";
			if($client->FileExists($filename))
			{
				$command = "cd /home/hadoop/ 
							rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm";
			}
			else
			{
				$command = "mkdir -p /home/hadoop 
							cd /home/hadoop/ 
							wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm 
							rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm";
			}
			$ret = $client->RunCommand($command);
			
			$filename = "/home/hadoop/lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			if($client->FileExists($filename))
			{
				$command = "cd /home/hadoop 
							rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			}
			else
			{
				$command = "mkdir -p /home/hadoop 
							cd /home/hadoop 
							wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm 
							rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			}
			$ret .= $client->RunCommand($command);
		}
		else
		{
			$ret =  "Unknown Operation System";
		}
		$filename = "/home/hadoop/lzo-2.06.tar.gz";
		if($client->FileExists($filename))
		{
			$command = "cd /home/hadoop/ 
						tar zxf lzo-2.06.tar.gz 
						cd lzo-2.06 
						./configure 
						make 
						make install";
		}
		else
		{
			$command = "mkdir -p /home/hadoop 
						cd /home/hadoop/ 
						wget http://113.11.199.230/resources/lzo-2.06.tar.gz 
						tar zxf lzo-2.06.tar.gz 
						cd lzo-2.06 
						./configure 
						make 
						make install";
		}
		$ret .= $client->RunCommand($command);
		
		return $ret;
	}

	###########################################
	public function InstallLzop($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$filename = "/home/hadoop/lzop-1.03.tar.gz";
		if($client->FileExists($filename))
		{
			$command = "cd /home/hadoop/ 
						tar zxf lzop-1.03.tar.gz 
						cd lzop-1.03 
						./configure 
						make 
						make install";
		}
		else
		{
			$command = "mkdir -p /home/hadoop 
						cd /home/hadoop/ 
						wget http://113.11.199.230/resources/lzop-1.03.tar.gz 
						tar zxf lzop-1.03.tar.gz 
						cd lzop-1.03 
						./configure 
						make 
						make install";
		}
		$str = $client->RunCommand($command);
		return $str;
	}

	###########################################
	public function InstallHadoopgpl($pProtocol)
	{
		$client = new EasyHadoopClient($pProtocol);
		$filename = "/home/hadoop/hadoop-gpl-packaging-0.5.4-1.x86_64.rpm";
		if($client->FileExists($filename))
		{
			$command = "cd /home/hadoop/ 
						rpm -Uvh hadoop-gpl-packaging-0.5.4-1.x86_64.rpm 
						cp -rf /opt/hadoopgpl/lib/* /usr/lib/ 
						cp -rf /opt/hadoopgpl/lib/* /usr/lib64/
						cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/ 
						cp -rf /opt/hadoopgpl/native /usr/share/hadoop/lib/
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64";
		}
		else
		{
			$command = "mkdir -p /home/hadoop 
						cd /home/hadoop/ 
						wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.4-1.x86_64.rpm 
						rpm -Uvh hadoop-gpl-packaging-0.5.4-1.x86_64.rpm 
						cp -rf /opt/hadoopgpl/lib/* /usr/lib/ 
						cp -rf /opt/hadoopgpl/lib/* /usr/lib64/
						cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/ 
						cp -rf /opt/hadoopgpl/native /usr/share/hadoop/lib/
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib
						cp -f /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64";
		}
		$ret = $client->RunCommand($command);
		
		return $ret;
	}

	###########################################
}

?>