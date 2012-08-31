<?php

class Install extends Socket
{
	
	private $mFilename;
	private $mRpmName;
	private $mDownloadUrl = "";
	
	private $cAgentRunShell = "RunShellScript";
	private $cGetSystemVer = "GetSystemVer";
	private $cCheckFileStatus = "CheckFileStatus";
	
	/*
	 * 继承socket类
	 * $mCommand 拼接出的命令内容，SocketCommand会调用
	 * $mHost 节点地址，通过各方法入口参数获取
	 * $mFilename 文件名称
	 * 
	 * Socket协议说明：
	 * $this->cAgentRunShell = "RunShellScript:xxxx" 发送Shell命令给Agent，xxxx为命令具体文本
	 * 		Agent接收到命令后，在节点机执行命令并返回stdout 
	 * 
	 * $this->cGetSystemVer = "GetSystemVer" 调用Agent platform.platform()获取操作系统版本号
	 * 
	 * $this->cGetFileStatus = "GetFileStatus" 调用Agent的os.path.isfile()获取文件存在状态，存在返回Socket TRUE，失败返回Socket FALSE
	 * 
	 * $this->cGetRpmStatus = "GetRpmStatus" 调用Agent的os.popen获取rpm是否安装，存在返回rpm名字，失败返回False
	 * 
	 */
	
	
	
	###########################################
	public function InstallEnvironment($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$this->mCommand = $this->cAgentRunShell.":yum -y install dialog lrzsz gcc gcc-c++ libstdc++-devel make automake autoconf ntp wget pcre pcre-devel sudo && ntpdate cn.pool.ntp.org";
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = $lang['notConnected'];
		}
		return $str;
	}
	
	###########################################
	public function InstallJava($pHost)
	{
		global $lang;
		
		$this->mHost = $pHost;
		$this->mFilename = "/home/hadoop/jdk-7u5-linux-x64.rpm";
		
		if($this->CheckFileExists())
		{
			$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && rpm -Uvh jdk-7u5-linux-x64.rpm && echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile";
		}
		else
		{
			$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/jdk/jdk-7u5-linux-x64.rpm && rpm -Uvh jdk-7u5-linux-x64.rpm && echo 'export JAVA_HOME=/usr/java/default' >> /etc/profile && source /etc/profile";
		}
		sleep(1);
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = $lang['notConnected'];
		}
		return $str;
	}
	
	#########################################
	public function InstallHadoop($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$this->mFilename = "/home/hadoop/hadoop-1.0.3-1.x86_64.rpm";
		if($this->CheckFileExists())
		{
			$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm && chmod 644 /etc/sudoers && sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers && chmod 440 /etc/sudoers && /usr/sbin/groupadd hadoop && /usr/sbin/useradd hadoop -g hadoop";
		}
		else
		{
			$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop/ && cd /home/hadoop/ && wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm && chmod 644 /etc/sudoers && sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers && chmod 440 /etc/sudoers && /usr/sbin/groupadd hadoop && /usr/sbin/useradd hadoop -g hadoop";
		}
		sleep(1);
		if($this->SocketCommand())
		{
			$ret = $this->mReturn;
		}
		else
		{
			$ret =  $lang['notConnected'];
		}
		return $ret;
	}
	
	###########################################
	public function InstallLzo($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$ver = $this->GetSystemVer();
		if(trim($ver) == "5")
		{
			$this->mFilename = "/home/hadoop/lzo-2.06-1.el5.rf.x86_64.rpm";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop && rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm && rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret = $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
			
			$this->mFilename = "/home/hadoop/lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop && rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm && rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret .= $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
			
			$this->mFilename = "/home/hadoop/lzo-2.06.tar.gz";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/lzo-2.06.tar.gz && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret .= $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
		}
		elseif(trim($ver) == "6")
		{
			$this->mFilename = "/home/hadoop/lzo-2.06-1.el6.rfx.x86_64.rpm";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm && rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret = $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
			
			$this->mFilename = "/home/hadoop/lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop && rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm && rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret .= $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
			
			$this->mFilename = "/home/hadoop/lzo-2.06.tar.gz";
			if($this->CheckFileExists())
			{
				$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install";
			}
			else
			{
				$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/lzo-2.06.tar.gz && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install";
			}
			sleep(1);
			if($this->SocketCommand())
			{
				$ret .= $this->mReturn;
			}
			else
			{
				$ret =  $lang['notConnected'];
			}
		}
		elseif($ver == $lang['notConnected'])
		{
			$ret = $lang['notConnected'];
		}
		else
		{
			$ret =  "Unknown Operation System";
		}
		return $ret;
	}

	###########################################
	public function InstallLzop($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$this->mFilename = "/home/hadoop/lzop-1.03.tar.gz";
		if($this->CheckFileExists())
		{
			$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && tar zxf lzop-1.03.tar.gz && cd lzop-1.03 && ./configure && make && make install";
		}
		else
		{
			$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/lzop-1.03.tar.gz && tar zxf lzop-1.03.tar.gz && cd lzop-1.03 && ./configure && make && make install";
		}
		sleep(1);
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = $lang['notConnected'];
		}
		return $str;
	}

	###########################################
	public function InstallHadoopgpl($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$this->mFilename = "/home/hadoop/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm";
		if($this->CheckFileExists())
		{
			$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && rpm -Uvh hadoop-gpl-packaging-0.5.3-1.x86_64.rpm && cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/ && cp -r /opt/hadoopgpl/native /usr/share/hadoop/lib/";
		}
		else
		{
			$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm && rpm -Uvh hadoop-gpl-packaging-0.5.3-1.x86_64.rpm && cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/ && cp -r /opt/hadoopgpl/native /usr/share/hadoop/lib/";
		}
		sleep(1);
		if($this->SocketCommand())
		{
			$ret = $this->mReturn;
		}
		else
		{
			$ret = $lang['notConnected'];
		}
		return $ret;
	}

	###########################################
	private function CheckFileExists()
	{
		global $lang;
		$this->mCommand = $this->cCheckFileStatus.":".$this->mFilename;
		
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
			if(trim($str) == "TRUE")
				return TRUE;
			else
				return FALSE;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	##########################################
	private function GetSystemVer()
	{
		global $lang;
		$this->mCommand = $this->cGetSystemVer;
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = $lang['notConnected'];
		}
		return $str;
	}
	
	##########################################
	private function GetRpmStatus()
	{
		global $lang;
		$this->mCommand = $this->cAgentRunShell.":rpm -qa | grep ".$this->mRpmName;
		if($this->SocketCommand())
		{
			$str = $this->mReturn;
		}
		else
		{
			$str = FALSE;
		}
		return $str;
	}
}

?>