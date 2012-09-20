<?php
set_time_limit(0);
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/install_manager_sidebar.html";

$mysql = new Mysql();
$install = new Install;
$socket = new Socket;

if(!@$_GET['action'])
{
	echo '<div class="span10">
	'.$lang['chooseLeftSidebar'].'
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
                  	<td><a class="btn" href="InstallManager.php?action=Install&ip='.$arr['ip'].'">'.$lang['installButton'].'</a></td>
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
		<a href="InstallManager.php?action=Install&which=Environment&ip='.$ip.'" class="btn">'.$lang['installEnvironment'].'</a>	
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
			
			$str = $install->$action($ip);
			
			echo $str;
			
			echo '</pre>';
		}
		echo "<br />";
		echo "<pre>";
		echo 'The Chosen host is '.$ip;
		echo "</pre>";
		echo '</div>';// span10
	}	
}

elseif($_GET['action'] == "PushSettingFiles")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['pushFiles'].'</h2>';
		
		echo '<div class="alert alert-error">';
		echo $lang['pushSettingFileTips'];
		echo '</div>';
		
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
   						 <a class="btn btn-warning" onclick="javascript:realconfirm(\''.$lang['pushSettingsConfirm'].'\', \'InstallManager.php?action=PushSettingFiles&do=Global&ip='.$arr['ip'].'\');return false;" href="#">'.$lang['pushGlobalSettings'].'</a>
   						 <a class="btn btn-danger" onclick="javascript:realconfirm(\''.$lang['pushSettingsConfirm'].'\',\'InstallManager.php?action=PushSettingFiles&do=Node&ip='.$arr['ip'].'\');return false;" href="#">'.$lang['pushHadoopSettings'].'</a>
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
			$sql = "select * from ehm_host_settings where ip='0'";
			$mysql->Query($sql);
			while ($arr = $mysql->FetchArray())
			{
				if($fp = @fsockopen($ip, 30050, $errstr, $errno, 60))
				{
					$command = "FileTransport:".$arr['filename']."\n";
					$command = $socket->EncryptCommand($command);
					$content = $arr['content'];
					fwrite($fp, $command);
					sleep(1);
					fwrite($fp, $content);
					unset($content);
					fclose($fp);
					echo "<script>alert('".$arr['filename']." pushed');</script>";
				}
				else
				{
					echo $lang['notConnected'];
				}
			}
			echo "<script>this.location='InstallManager.php?action=PushSettingFiles';</script>";
		}
		elseif ($_GET['do'] == 'Node')
		{
			$ip = $_GET['ip'];
			$sql = "select set_id,filename,CONVERT(content USING latin1) from ehm_host_settings where ip='".$ip."'";
			$mysql->Query($sql);
			while ($arr = $mysql->FetchArray())
			{
				if($fp = @fsockopen($ip, 30050, $errstr, $errno, 60))
				{
					$command = "FileTransport:".$arr['filename']."\n";
					$command = $socket->EncryptCommand($command);
					$content = $arr['content'];
					fwrite($fp, $command);
					sleep(1);
					fwrite($fp, $content);
					unset($content);
					fclose($fp);
					echo "<script>alert('".$arr['filename']." pushed');</script>";
				}
				else
				{
					echo $lang['notConnected'];
				}
			}
			echo "<script>this.location='InstallManager.php?action=PushSettingFiles';</script>";
		}
		else
		{
			echo $lang['unknownCommand'];
		}
	}
	
}
elseif($_GET['action'] == "PushHadoopFiles")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['pushHadoopFiles'].'</h2>';
		echo '<div class="alert alert-error">';
		echo $lang['pushTipsDanger'];
		echo '</div>';
		echo '<div class="alert">';
		echo $lang['pushTipsWarn'];
		echo '</div>';
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
		$ip = $_GET['ip'];
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
		$install->MakeDir($ip);
		foreach ($arr as $key => $value)
		{
			if($fp = @fsockopen($ip, 30050, $errstr, $errno, 60))
			{
				$command = "FileTransport:/home/hadoop/".$value."\n";
				$command = $socket->EncryptCommand($command);
				fwrite($fp,$command);
				sleep(1);
				$fd = fopen("./hadoop/".$value, "rb");
				while(!feof($fd))
				{
					$str .= fread($fd,1024);
				}
				fwrite($fp,$str);
				unset($str);
				fclose($fp);
			}
			else
			{
				die ("<script>alert('".$lang['notConnected']."');this.location='InstallManager.php?action=PushHadoopFiles';</script>");
			}
		}
		echo "<script>alert('".$lang['pushComplete']."'); this.location='InstallManager.php?action=Install';</script>";
	}
}
else
{
	die($lang['unknownCommand']);
}

include_once "templates/footer.html";
?>
