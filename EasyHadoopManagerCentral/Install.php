<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/install_sidebar.html";

if(!@$_GET['action'])
{
	echo '<div class="span10">
	Choose left sidebar for next step.
	</div>';
}

elseif($_GET['action'] == "Install")
{
	echo 'div class="span10">
	<div class="btn-toolbar">
	<div class="btn-group">
	
	<a href="Install.php?action=Install&which=Evironment class="btn btn-secondary">'.$lang['installEvironment'].'</a>
	<a href="Install.php?action=Install&which=Java class="btn">'.$lang['installJava'].'</a>
	<a href="Install.php?action=Install&which=Hadoop class="btn">'.$lang['installHadoop'].'</a>
	<a href="Install.php?action=Install&which=Lzo class="btn">'.$lang['installLzo'].'</a>
	
	</div>
	</div>
	choose button above for next step.
	</div>';
}

elseif($_GET['action'] == "Uninstall")
{
	echo 'div class="span9">
	<div class="btn-toolbar">
	<div class="btn-group">
	
	<a href="Install.php?action=Uninstall&which=Evironment class="btn">'.$lang['uninstallEvironment'].'</a>
	<a href="Install.php?action=Uninstall&which=Java class="btn">'.$lang['uninstallJava'].'</a>
	<a href="Install.php?action=Uninstall&which=Hadoop class="btn">'.$lang['uninstallHadoop'].'</a>
	<a href="Install.php?action=Uninstall&which=Lzo class="btn">'.$lang['uninstallLzo'].'</a>
	
	</div>
	</div>
	choose button above for next step.
	</div>';
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
if(@$_GET['ip'])
{
	$action = @$_GET['action'];
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
}

include_once "templates/footer.html";
?>
