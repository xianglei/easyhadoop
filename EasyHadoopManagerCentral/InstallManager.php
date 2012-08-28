<?php
set_time_limit(0);
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/install_manager_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	Choose left sidebar for next step.
	</div>';
}

elseif($_GET['action'] == "Install")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['chooseInstallHost'].'</h2>';
		$sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['nodeRole'].'</th>
                  <th>'.$lang['createTime'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td><a href=InstallManager.php?action=Install&ip='.$arr['ip'].'>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>
                  	<td>'.$arr['role'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}
	else
	{
		$ip = $_GET['ip'];
		echo '<div class="span10">
		<div class="btn-toolbar">
		<div class="btn-group">
	
		<a href="InstallManager.php?action=Install&which=Lzo&ip='.$ip.'" class="btn">'.$lang['installLzo'].'</a>
		<a href="InstallManager.php?action=Install&which=Lzop&ip='.$ip.'" class="btn">'.$lang['installLzop'].'</a>
		<a href="InstallManager.php?action=Install&which=Hadoopgpl&ip='.$ip.'" class="btn">'.$lang['installHadoopgpl'].'</a>
		<a href="InstallManager.php?action=Install&which=Java&ip='.$ip.'" class="btn">'.$lang['installJava'].'</a>
		<a href="InstallManager.php?action=Install&which=Hadoop&ip='.$ip.'" class="btn">'.$lang['installHadoop'].'</a>';
		
		
		echo '</div>
		</div>';//btn-toolbar
		
		if(@$_GET['which'])
		{
			echo '<pre>';
			
			$action = $_GET['action'].$_GET['which'];
			$ip = $_GET['ip'];
			switch ($action)
			{
				case 'InstallJava':
					$command = "FileTransport:/home/hadoop/jdk-7u5-linux-x64.rpm";
					$filename = "./hadoop/jdk-7u5-linux-x64.rpm";
					break;
				case 'InstallHadoop':
					$command = "FileTransport:/home/hadoop/hadoop-1.0.3-1.x86_64.rpm";
					$filename = "./hadoop/hadoop-1.0.3-1.x86_64.rpm";
					break;
				case 'InstallLzop':
					$command = "FileTransport:/home/hadoop/lzop-1.03.tar.gz\n";
					$filename = "./hadoop/lzop-1.03.tar.gz";
					break;
				default:
					echo "Invalid Socket Command";
					break;
			}
			
			if($fp = @fsockopen($ip, 30050, $errno, $errstr, 60))
			{
				if($action == "InstallJava" || $action == "InstallHadoop" ||$action == "InstallLzop")
				{echo $action;
					fwrite($fp, $command."\n");
					sleep(1);
					$fd = fopen($filename,"rb");
					while (!feof($fd))
					{
						$a = fread($fd,1024);
						fwrite($fp,$a);
					}
					fclose($fd);
					fclose($fp);
			echo "socket sended";
					$fp = @fsockopen($ip, 30050, $errno, $errstr, 60);
					fwrite($fp, $command);
					while(!feof($fp))
					{
						$str .= fread($fp,1024);
					}
					echo str_replace("\n","<br/>",$str);
					fclose($fp);
				}
				else
				{
					fwrite($fp,$action."\n");
					while(!feof($fp))
					{
						$str .= fread($fp,1024);
					}
					echo str_replace("\n","<br/>",$str);
					fclose($fp);
				}
				
			}
			else
			{
				echo $lang['notConnected'];
			}

			echo '</pre>';
		}
		echo "<br />";
		echo "<pre>";
		echo 'The Chosen host is '.$ip;
		echo "</pre>";
		echo '</div>';// span10
	}	
}

elseif($_GET['action'] == "Uninstall")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['chooseUninstallHost'].'</h2>';
		$sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['nodeRole'].'</th>
                  <th>'.$lang['createTime'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td><a href=InstallManager.php?action=Uninstall&ip='.$arr['ip'].'>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>
                  	<td>'.$arr['role'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                	</tr>';
			$i++;
		}
		echo '</tbody>
			</table>';
		echo '</div>';
	}
	else
	{
		$ip = $_GET['ip'];
		echo '<div class="span10">
		<div class="btn-toolbar">
		<div class="btn-group">
	
		<a href="InstallManager.php?action=Uninstall&which=Java&ip='.$ip.'" class="btn">'.$lang['uninstallJava'].'</a>
		<a href="InstallManager.php?action=Uninstall&which=Hadoop&ip='.$ip.'" class="btn">'.$lang['uninstallHadoop'].'</a>
		<a href="InstallManager.php?action=Uninstall&which=Hadoopgpl&ip='.$ip.'" class="btn">'.$lang['uninstallHadoopgpl'].'</a>';
		
		echo '</div>
		</div>';//btn-toolbar
		
		
		if(@$_GET['which'])
		{
			echo '<pre>';
			
			$action = $_GET['action'].$_GET['which'];
			$ip = $_GET['ip'];
			/*$sock = new Socket;
			$sock->Connect($ip, 30050 , 60);
			$str = $sock->SendCommand($action);
			$str = str_replace("\n","<br />",$str);
			$sock->DisConnect();*/
			
			if($fp = @fsockopen($ip, 30050, $errno, $errstr, 60))
			{
				fwrite($fp, $action."\n");
				while(!feof($fp))
				{
					$str .= fread($fp,1024);
				}
				echo str_replace("\n","<br/>",$str);
				fclose($fp);
			}
			else
			{
				echo $lang['notConnected'];
			}
			
			echo '</pre>';
		}
		echo "<br />";
		echo "<pre>";
		echo 'The Chosen host is '.$ip;
		echo "</pre>";
		echo '</div>';// span10
	}
}
?>

<!--<a class="btn btn-secondary" href=Install.php?action=InstallEnvironment&ip=127.0.0.1>安装环境依赖</a>
 <a class="btn" href=Install.php?action=InstallJava&ip=127.0.0.1>安装JDK</a>
 <a class="btn" href=Install.php?action=InstallHadoop&ip=127.0.0.1>安装Hadoop</a>
 <a class="btn" href=Install.php?action=InstallLzo&ip=127.0.0.1>安装Lzo</a>
 <a class="btn" href=Install.php?action=InstallLzop&ip=127.0.0.1>安装Lzop</a>
 <a class="btn" href=Install.php?action=InstallHadoopgpl&ip=127.0.0.1>安装HadoopGpl库(LZO依赖)</a>
<br><br>

<a class="btn" href=Install.php?action=FormatNamenode&ip=127.0.0.1>格式化Namenode</a>
<a class="btn" href=Install.php?action=StartNamenode&ip=127.0.0.1>启动Namenode</a>
<a class="btn" href=Install.php?action=StartDatanode&ip=127.0.0.1>启动Datanode</a>
<a class="btn" href=Install.php?action=StartJobtracker&ip=127.0.0.1>启动Jobtracker</a>
<a class="btn" href=Install.php?action=StartTasktracker&ip=127.0.0.1>启动Tasktracker</a>


<br><br>

<a class="btn" href=Install.php?action=UninstallJava&ip=127.0.0.1>卸载Java</a>
<a class="btn" href=Install.php?action=UninstallHadoop&ip=127.0.0.1>卸载Hadoop</a>
<a class="btn" href=Install.php?action=UninstallHadoopgpl&ip=127.0.0.1>卸载Hadoopgpl</a>


<br/>--> 

<?php
/*if(@$_GET['which'] && @$_GET['ip'])
{
	$action = @$_GET['action'].$_GET['which'];
	$node_ip = @$_GET['ip'];
	if($fp = fsockopen($node_ip, 30050, $errno, $errstr, 5))
	{
		fwrite($fp, $action."\n");
		while(!feof($fp))
		{
			$str .= fread($fp,1024);
		}
		echo str_replace("\n","<br/>",$str);
		fclose($fp);
	}
	else
	{
		echo "无法连接到节点，请确认Agent已开启。";
	}
}*/

include_once "templates/footer.html";
?>
