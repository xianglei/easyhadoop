<?php
include_once "config.inc.php";

if(!@$_GET['action'])
{
	include_once "templates/header.html";
	include_once "templates/node_manager_sidebar.html";
	
	$mysql = new Mysql();
	
	$sql = "select * from ehm_hosts order by create_time desc";
	$mysql->Query($sql);
	while($arr = $mysql->FetchArray())
	{
		echo $arr["hostname"]."</br>";
	}
}
?>