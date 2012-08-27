<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_manager_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class=span10>';
	
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
	$i = 0;
	while($arr = $mysql->FetchArray())
	{
		echo '<tr>
                  <td>'.$i.'</td>
                  <td>'.$arr['hostname'].'</td>
                  <td>'.$arr['ip'].'</td>
                  <td>'.$arr['role'].'</td>
                  <td>'.$arr['create_time'].'</td>
                </tr>';
		echo $arr["hostname"]."</br>";
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';
}


elseif ($_GET['action'] == "AddNode")
{
	if(!$_POST['ip'] && !$_POST['hostname'] && !$_POST['role'])
	{
		echo '<div class="span10">
            <h1>'.$lang['addNode'].'</h1>
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
		</div>';
	}
	else
	{
		echo '<div class=span10>';
		$hostname = $_POST['hostname'];
		$ipaddr = $_POST['ipaddr'];
		$role = $_POST['role'];
		$sql = "insert ehm_hosts set hostname = '".$hostname."', ip = '".$ipaddr."', role = '".$role."', create_time=current_timestamp()";
		echo $sql;
		$mysql->Query($sql);
		echo '</div>';
	}
}

include_once "templates/footer.html";
?>