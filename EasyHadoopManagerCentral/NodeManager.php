<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_manager_sidebar.html";

$mysql = new Mysql();

##默认页面
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
	$i = 1;
	while($arr = $mysql->FetchArray())
	{
		echo '<tr>
                  <td>'.$i.'</td>
                  <td>'.$arr['hostname'].'</td>
                  <td>'.$arr['ip'].'</td>
                  <td>'.$arr['role'].'</td>
                  <td>'.$arr['create_time'].'</td>
                </tr>';
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';
}

##添加节点到数据库
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
		$role = strtolower($_POST['role']);
		$sql = "insert ehm_hosts set hostname = '".$hostname."', ip = '".$ipaddr."', role = '".$role."', create_time=current_timestamp()";
		$mysql->Query($sql);
		echo '</div>';
		echo "<script>alert('".$lang['nodeAdded']."'); this.location='NodeManager.php';</script>";
	}
}
#从数据库中删除节点
elseif ($_GET['action'] == "RemoveNode")
{
	if(!$_GET['nodeid'])
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
                  	<th>'.$lang['removeNode'].'</th>
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
                  	<td><i class=icon-remove></i><a class="btn btn-danger" href=NodeManager.php?action=RemoveNode&nodeid='.$arr['host_id'].'>'.$lang['removeNode'].'</a></td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}
	else
	{
		$sql = "delete from ehm_hosts where host_id='".$_GET['nodeid']."'";
		$mysql->Query($sql);
		echo "<script>alert('".$lang['nodeRemoved']."'); this.location='NodeManager.php?action=RemoveNode';</script>";
	}
}
##连通性测试
elseif($_GET['action'] == "PingNode")
{
	if(!$_GET['nodeid'])
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
                  	<th>'.$lang['removeNode'].'</th>
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
                  	<td><i class=icon-play></i><a class="btn btn-info" href=NodeManager.php?action=PingNode&nodeid='.$arr['host_id'].'>'.$lang['pingNode'].'</a></td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}
	else
	{
		$sql = "select ip from ehm_hosts where host_id='".$_GET['nodeid']."'";
		$mysql->Query($sql);
		$arr = $mysql->FetchArray();
		if($fp = @fsockopen($arr['ip'], 30050, $errstr, $errno, 30))
		{
			@fclose($fp);
			echo "<script>alert('".$lang['connected']."'); this.location='NodeManager.php?action=PingNode';</script>";
		}
		else
		{
			echo "<script>alert('".$lang['notConnected']."'); this.location='NodeManager.php?action=PingNode';</script>";
		}
	}
}

include_once "templates/footer.html";
?>