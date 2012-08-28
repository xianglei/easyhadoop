<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/host_settings_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	Choose left sidebar for next step.
	</div>';
}
elseif($_GET['action'] == "GlobalSettings")
{
	if(!@$_GET['do'])
	{
		$sql = "select set_id, filename, create_time from ehm_host_settings where host_id = 0 order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		echo '<h2>'.$lang['globalSettings'].'</h2>';
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['filename'].'</th>
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
                  	<td>'.$arr['filename'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                  	<td>
                  	
					<div class="btn-group">
  						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
    					'.$lang['action'].'
    						<span class="caret"></span>
 						</a>
  						<ul class="dropdown-menu">
   						 <li><a href="HostSettings.php?action=GlobalSettings&do=Edit&setid='.$arr['set_id'].'">编辑</a></li>
   						 <li class="divider"></li>
   						 <li><a href="HostSettings.php?action=GlobalSettings&do=Remove&setid='.$arr['set_id'].'">删除</a></li>
  						</ul>
					</div>
                  	
                  	</td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action

	elseif ($_GET['do'] == "Edit")
	{
		
	}
}

?>