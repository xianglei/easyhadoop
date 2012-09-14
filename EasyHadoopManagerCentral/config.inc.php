<?php
session_id();
session_start();

define("MYSQL_HOST","localhost");
define("MYSQL_PORT","3306");
define('MYSQL_USER', 'root');
define("MYSQL_PASS","");
define("MYSQL_NAME", "easyhadoop");

include_once "langs/lang_cn.php";
include_once "classes/class.mysql.php";
include_once "classes/class.user.php";
//include_once "classes/class.socket.php";

$auth =new User;

if($_POST['username'] && $_POST['password'])
{
	$user = $_POST['username'];
	$pass = $_POST['password'];
}
else
{
	$user = $_SESSION['username'];
	$pass = $_SESSION['password'];
}
if(($user == "") || ($pass == ""))
{
	include_once "templates/login.html";
	die('');
}
else
{

	if(!$auth->AuthUser($user,$pass))
	{
		include_once "templates/login.html";
		die('');
	}
	else
	{
		$_SESSION['username'] = $user;
		$_SESSION['password'] = $pass;
		$_SESSION['role'] = $auth->mRole;
	}
}
include_once "classes/class.socket.php";
include_once "classes/class.install.php";
include_once "classes/class.nodeoperate.php";
include_once "classes/class.nodemonitor.php";



?>