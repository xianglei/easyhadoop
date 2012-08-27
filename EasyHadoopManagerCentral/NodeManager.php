<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_manager_sidebar.html";

if(!@$_GET['action'])
{
	
	
	
	$mysql = new Mysql();
	
	$sql = "select * from ehm_hosts order by create_time desc";
	$mysql->Query($sql);
	while($arr = $mysql->FetchArray())
	{
		echo $arr["hostname"]."</br>";
	}
}
elseif ($_GET['action'] == "AddNode")
{
	if(!$_GET['ip'])
	{
		echo '
		<div class="page-header">
            <h1>'.$lang['addNode'].'</h1>
          </div>
            <label>'.$lang['addNode'].'</label>
		<form>
				<input type="text" placeholder="'.$lang['hostname'].'" name="hostname" />
				<input type="text" placeholder="'.$lang['ipAddr'].'" name="ipaddr" />
				<input type="text" placeholder="'.$lang['roleName'].'" name="role" />
				<input type="hidden" name="action" value="'.$_GET['action'].'" />
				<button type="submit" class="btn">'.$lang['submit'].'</button>
			</form>
			</div>
    		</div>
  			</div>';
	}
}
?>