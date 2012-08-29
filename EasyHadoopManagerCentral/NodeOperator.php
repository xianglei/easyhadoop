<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_operator_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	'.$lang['chooseLeftSidebar'].'
	</div>';
}
elseif($_GET['action'] == "Operate")
{
	if(!@$_GET['do'])
	{
		$sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		
		echo '<h2>'.$lang['operateNode'].'</h2>';
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['action'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			$role = $arr['role'];
			$arr_role = explode(",",$role);
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>';
                  	
                  	
			foreach($arr_role as $key => $value)
			{
					 echo '<td>';
					 
					 echo '<div class="btn-group">
                		<button class="btn">'.$value.'</button>
                		<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                		<ul class="dropdown-menu">
                  		<li><a href="NodeOperator.php?action=Operate&do=Start&ip='.$arr['ip'].'&role='.$value.'">'.$lang['start'].$value.'</a></li>
                  		<li class="divider"></li>
                  		<li><a href="NodeOperator.php?action=Operate&do=Stop&ip='.$arr['ip'].'&role='.$value.'">'.$lang['stop'].$value.'</a></li>
					 	<li><a href="NodeOperator.php?action=Operate&do=Restart&ip='.$arr['ip'].'&role='.$value.'">'.$lang['restart'].$value.'</a></li>
                		</ul>
              				</div>';
            		echo '</td>';
	        }
			
            echo '</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action
	else
	{
		echo '<div class=span10>';
		switch ($_GET['do'])
		{
			case 'Start':
				$command_1 = "Start";
				break;
			case 'Stop':
				$command_1 = "Stop";
				break;
			case 'Restart':
				$command_1 = "Restart";
				break;
			
			default:
				die("<pre>".$lang['unknownCommand']."</pre>");
				break;
		}
		
		switch ($_GET['role'])
		{
			case 'namenode':
				$command_2 = "Namenode";
				break;
			case 'jobtracker':
				$command = "Jobtracker";
				break;
			case 'secondarynamenode':
				$command = "SecondaryNamenode";
				break;
			case 'tasktracker':
				$command = "Tasktracker";
				break;
			case 'datanode':
				$command = "Datanode";
				break;
			
			default:
				die("<pre>".$lang['unknownCommand']."</pre>");
				break;
		}

		echo '<pre>';
		$action = $command_1.$command_2;
		$ip = $_GET['ip'];
		if($fp = @fsockopen($ip, 30050, $errno, $errstr, 60))
		{
			fwrite($fp, $action);
			while(!feof($fp))
			{
				$str .= fread($fp,1024);
			}
			echo str_replace("\n","<br/>",$str);
			fclose($fp);
		}
		else
		{
			echo $lang['notConnected'];
		}

		echo '</pre>';
		echo '</div>';
	}
}
elseif ($_GET['action'] == "FormatNamenode")
{
	echo '<div class=span10>';
	echo "<h1>".$lang['namenodeFormatWarn']."</h1>";
	echo "</div>";
}
elseif ($_GET['action'] == "ViewLogs")
{
	if(!$_GET['ip'])
	{
		$sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		
		echo '<h2>'.$lang['operateNode'].'</h2>';
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			$role = $arr['role'];
			$arr_role = explode(",",$role);
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>';
                  	
                  	
			foreach($arr_role as $key => $value)
			{
					 echo '<td>';
					 
					 echo '
					 <div class="btn-group">
                  		<a class="btn" href="NodeOperator.php?action=ViewLogs&ip='.$arr['ip'].'&role='.$value.'&hostname='.$arr['hostname'].'">'.$value.$lang['logs'].'</a>
              				</div>';
            		echo '</td>';
	        }
			
            echo '</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action
	else
	{
		$hostname = $_GET['hostname'];
		$ip = $_GET['ip'];
		$action = $_GET['action'];
		$role = $_GET['role'];
		
		$command = "TailLogs:".$role.":".$hostname;
		echo '<div class=span10>';
		echo '<pre>';
			
		$action = $_GET['action'].$_GET['which'];
		$ip = $_GET['ip'];
			
		if($fp = @fsockopen($ip, 30050, $errno, $errstr, 60))
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
			echo $lang['notConnected'];
		}

		echo '</pre>';
		echo '</div>';
	}
}
else
{
	echo '<div class=span10>';
	echo "<h1>".$lang['unknownCommand']."</h1>";
	echo "</div>";
}

include_once "templates/footer.html";
?>