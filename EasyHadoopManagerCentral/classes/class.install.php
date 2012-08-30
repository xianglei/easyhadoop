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
		if($str = $this->SocketCommand())
		{
			return $str;
		}
		else
		{
			return $lang['notConnected'];
		}
	}
	
	###########################################
	public function InstallJava($pHost)
	{
		global $lang;
		
		$this->mHost = $pHost;
		$this->mFilename = "/home/hadoop/jdk-7u5-linux-x64.rpm";
		
		if($this->CheckFileExists())
		{
			$this->mCommand = $this->cAgentRunShell.":cd /home/hadoop/ && rpm -Uvh jdk-7u5-linux-x64.rpm";
		}
		else
		{
			$this->mCommand = $this->cAgentRunShell.":mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/jdk/jdk-7u5-linux-x64.rpm && rpm -Uvh jdk-7u5-linux-x64.rpm";
		}
		sleep(1);
		if($str = $this->SocketCommand())
		{
			return $str;
		}
		else
		{
			return $lang['notConnected'];
		}
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
		if($str = $this->SocketCommand())
		{
			return $str;
		}
		else
		{
			return $lang['notConnected'];
		}
	}
	
	###########################################
	public function InstallLzo($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$ver = $this->GetSystemVer();
		if($ver == "5")
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
			if($str = $this->SocketCommand())
			{
				$ret = $str;
			}
			else
			{
				return $lang['notConnected'];
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
			if($str = $this->SocketCommand())
			{
				$ret .= $str;
			}
			else
			{
				return $lang['notConnected'];
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
			if($str = $this->SocketCommand())
			{
				$ret .= $str;
			}
			else
			{
				return $lang['notConnected'];
			}
		}
		elseif($ver == "6")
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
			if($str = $this->SocketCommand())
			{
				$ret = $str;
			}
			else
			{
				return $lang['notConnected'];
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
			if($str = $this->SocketCommand())
			{
				$ret .= $str;
			}
			else
			{
				return $lang['notConnected'];
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
			if($str = $this->SocketCommand())
			{
				$ret .= $str;
			}
			else
			{
				return $lang['notConnected'];
			}
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
		if($str = $this->SocketCommand())
		{
			return $str;
		}
		else
		{
			return $lang['notConnected'];
		}
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
		if($str = $this->SocketCommand())
		{
			$ret = $str;
		}
		else
		{
			return $lang['notConnected'];
		}
		return $ret;
	}

	############################################
	public function UninstallJava($pHost)
	{
		global $lang;
		$this->mHost = $pHost;
		$this->mRpmName = "jdk-1.7.0_05-fcs";
		if(($str = $this->GetRpmStatus()))
		{
			$this->mCommand = $this->cAgentRunShell.":rpm -e ".$str;
			sleep(1);
			if($str = $this->SocketCommand())
			{
				return $str;
			}
			else
			{
				return $lang['notConnected'];
			}
		}
		else
		{
			return $lang['notConnected'];
		}
	}
	
	############################################
	public function UninstallHadoop($pHost)
	{
		$this->mHost = $pHost;
		$this->mRpmName = "hadoop-1.0.3-1";
		if(($str = $this->GetRpmStatus()))
		{
			$this->mCommand = $this->cAgentRunShell.":rpm -e ".$str;
			sleep(1);
			if($str = $this->SocketCommand())
			{
				return $str;
			}
			else
			{
				return $lang['notConnected'];
			}
		}
		else
		{
			return $lang['notConnected'];
		}
	}
	
	############################################
	public function UninstallHadoopgpl($pHost)
	{
		$this->mHost = $pHost;
		$this->mRpmName = "hadoop-gpl-packaging-0.5.3-1";
		if(($str = $this->GetRpmStatus()))
		{
			$this->mCommand = $this->cAgentRunShell.":rpm -e ".$str;
			sleep(1);
			if($str = $this->SocketCommand())
			{
				return $str;
			}
			else
			{
				return $lang['notConnected'];
			}
		}
		else
		{
			return $lang['notConnected'];
		}
	}

	###########################################
	private function CheckFileExists()
	{
		$this->mCommand = $this->cCheckFileStatus.":".$this->mFilename;
		
		$str = $this->SocketCommand();
		if(trim($str) == "TRUE")
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	##########################################
	private function GetSystemVer()
	{
		$this->mCommand = $this->cGetSystemVer;
		$str = $this->SocketCommand();
		return trim($str);
	}
	
	##########################################
	private function GetRpmStatus()
	{
		$this->mCommand = $this->cAgentRunShell.":rpm -qa | grep ".$this->mRpmName;
		$str = $this->SocketCommand();
		return trim($str);
	}
}

?>