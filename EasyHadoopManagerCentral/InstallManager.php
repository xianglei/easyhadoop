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
                  <th>'.$lang['action'].'</th>
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
                  	<td><a class="btn" href="InstallManager.php?action=Install&ip='.$arr['ip'].'">'.$lang['install'].'</td>
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
			
			if($fp = @fsockopen($ip, 30050, $errno, $errstr, 60))
			{
					fwrite($fp, $action);
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
elseif($_GET['action'] == "PushFiles")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['pushFiles'].'</h2>';
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
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>
                  	<td>'.$arr['role'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                  	<td>
					<div class="btn-group">
   						 <a class="btn btn-warning" href="InstallManager.php?action=PushFiles&do=Global&ip='.$arr['ip'].'">'.$lang['pushGlobalSettings'].'</a>
   						 <a class="btn btn-danger" href="InstallManager.php?action=PushFiles&do=Node&ip='.$arr['ip'].'">'.$lang['pushHadoopSettings'].'</a>
                  	</div>
                  	</td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}
	else
	{
		if($_GET['do'] == "Global")
		{
			$ip = $_GET['ip'];
			$sql = 'select * from ehm_host_settings where host_id=0';
			$mysql->Query($sql);
			while ($arr = $mysql->FetchArray())
			{
				if($fp = @fsockopen($ip, 30050, $errstr, $errno, 60))
				{
					$command = "FileTransport:".$arr['filename']."\n";
					$content = $arr['content'];
					fwrite($fp, $command);
					sleep(1);
					fwrite($fp, $content);
					fclose($fp);
					echo "<script>alert('".$arr['filename']." pushed');</script>";
				}
				else
				{
					echo $lang['notConnected'];
				}
			}
			echo "<script>this.location='InstallManager.php?action=PushFiles';</script>";
		}
		elseif ($_GET['do'] == 'Node')
		{
			$ip = $_GET['ip'];
			$sql = "select host_id from ehm_hosts where ip='".$ip."'";
			$mysql->Query($sql);
			$arr = $mysql->FetchArray();
			$sql = "select * from ehm_host_settings where host_id=".$arr['host_id'];
			$mysql->Query($sql);
			while ($arr = $mysql->FetchArray())
			{
				if($fp = @fsockopen($ip, 30050, $errstr, $errno, 60))
				{
					$command = "FileTransport:".$arr['filename']."\n";
					$content = $arr['content'];
					fwrite($fp, $command);
					sleep(1);
					fwrite($fp, $content);
					fclose($fp);
					echo "<script>alert('".$arr['filename']." pushed');</script>";
				}
				else
				{
					echo $lang['notConnected'];
				}
			}
			echo "<script>this.location='InstallManager.php?action=PushFiles';</script>";
		}
		else
		{
			echo "Unknown Command";
		}
	}
	
}
elseif($_GET['action'] == "PushHadoopFiles")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['pushHadoopFiles'].'</h2>';
		echo "<pre>";
		echo $lang['pushTips'];
		echo "</pre>";
		echo $sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['nodeRole'].'</th>
                  <th>'.$lang['createTime'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>
                  	<td>'.$arr['role'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                  	<td>
                  	<a class="btn btn-success" href="InstallManager.php?action=PushHadoopFiles&ip='.$arr['ip'].'">'.$lang['push'].'</a>
                  	</td>
                	</tr>';
			$i++;
		}
		echo '</tbody>
			</table>';
		echo '</div>';
	}
	else
	{
		if ($handle = opendir('./hadoop'))
		{
			$i = 0;
			while (FALSE !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..")
				{
					$arr[$i] = $file;
					$i++;
				}
			}
			closedir($handle);
		}
		echo $arr;
	}
}
else
{
	die("Unknown Command");
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
