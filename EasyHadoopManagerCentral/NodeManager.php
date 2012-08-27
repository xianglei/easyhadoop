<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_manager_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	$sql = "select * from ehm_hosts order by create_time desc";
	$mysql->Query($sql);
	while($arr = $mysql->FetchArray())
	{
		echo $arr["hostname"]."</br>";
	}
	echo '</div>
	</div>';
}
elseif ($_GET['action'] == "AddNode")
{
	if(!$_POST['ip'] && !$_POST['hostname'] && !$_POST['role'])
	{
		echo '<div class="page-header">
            <h1>'.$lang['addNode'].'</h1>
        </div>
        
		<form method=POST>
				<label>'.$lang['hostname'].'</label><br />
				<input type="text" placeholder="'.$lang['hostname'].'" name="hostname" /><br />
				<label>'.$lang['ipAddr'].'</label><br />
				<input type="text" placeholder="'.$lang['ipAddr'].'" name="ipaddr" /><br />
				<label>'.$lang['roleName'].'</label><br />
				<input type="text" placeholder="'.$lang['roleName'].'" name="role" /><br />
				<input type="hidden" name="action" value="'.$_GET['action'].'" />
				<button type="submit" class="btn">'.$lang['submit'].'</button>
		</form>
		</div>
		</div>';
	}
	else
	{
		$hostname = $_POST['hostname'];
		$ipaddr = $_POST['ipaddr'];
		$role = $_POST['role'];
		$sql = "insert ehm_hosts set hostname = '".$hostname."', ip = '".$ipaddr."', role = '".$role."', create_time=current_timestamp()";
		echo $sql;
		$mysql->Query($sql);
	}
}

include_once "templates/footer.html";
?>