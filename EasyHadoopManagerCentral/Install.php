<!DOCTYPE html>
<html lang="cn">
  <head>
    <meta charset="utf-8">
    <title>EasyHadoop 管理中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="xianglei">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/google-code-prettify/prettify.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  </head>

 <a class="btn btn-secondary" href=Install.php?action=InstallEnvironment&ip=127.0.0.1>安装环境依赖</a>
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


<br/> 

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
?>
