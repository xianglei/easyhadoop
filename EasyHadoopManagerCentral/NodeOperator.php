<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_operator_sidebar.html";

$mysql = new Mysql();
$node = new NodeOperator;
$monitor = new NodeMonitor;

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
			$transport = new TSocket($arr['ip'], 30050);
			$protocol = new TBinaryProtocol($transport);
			#$client = new EasyHadoopClient($protocol);
			$bool = $monitor->CheckAgentAlive($arr['ip'], 30050);
			
           	
			foreach($arr_role as $key => $value)
			{
				if($bool == TRUE)
				{
					$transport->open();
					$str = $monitor->CheckHadoopProcess($value, $protocol);
					$transport->close();
					if($str == "")
					{
						$button = '<button class="btn btn-warning">'.$value.'</button>
							<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
					}
					else
					{
						$button = '<button class="btn btn-success">'.$value.'</button>
							<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
					}
				}
				else
				{
					$button = '<button class="btn btn-danger">'.$value.'</button>
							<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
				}

				
				
				echo '<td>';
				
				echo '<div class="btn-group">';
				echo $button;
				echo	'<ul class="dropdown-menu">
                  		<li><a href="NodeOperator.php?action=Operate&do=Start&ip='.$arr['ip'].'&role='.$value.'"><i class="icon-play"></i>'.$lang['start'].$value.'</a></li>
                  		<li class="divider"></li>
                  		<li><a href="NodeOperator.php?action=Operate&do=Stop&ip='.$arr['ip'].'&role='.$value.'"><i class="icon-stop"></i>'.$lang['stop'].$value.'</a></li>
					 	<li><a href="NodeOperator.php?action=Operate&do=Restart&ip='.$arr['ip'].'&role='.$value.'"><i class="icon-refresh"></i>'.$lang['restart'].$value.'</a></li>
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
		$ip = $_GET['ip'];
		$role = $_GET['role'];
		echo '<div class=span10>';
		

		echo '<pre>';
		
		$transport = new TSocket($ip, 30050);
		$protocol = new TBinaryProtocol($transport);
		#$client = new EasyHadoopClient($protocol);
        $transport->open();
		
		switch ($_GET['do'])
		{
			case 'Start':
				$str = $node->HadoopStart($role,$protocol);
				break;
			case 'Stop':
				$str = $node->HadoopStop($role,$protocol);
				break;
			case 'Restart':
				$str = $node->HadoopRestart($role,$protocol);
				break;
			
			default:
				die("<pre>".$lang['unknownCommand']."</pre>");
				break;
		}
		echo $str;
		$transport->close();
		echo '</pre>';
		echo '</div>';
	}
}
elseif ($_GET['action'] == "FormatNamenode")
{
	echo '<div class=span10>';
	echo "<h3>".$lang['namenodeFormatWarn']."</h3>";
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
		
		echo '<div class=span10>';
		echo '<pre>';

		$ip = $_GET['ip'];
		
		$transport = new TSocket($ip, 30050);
		$protocol = new TBinaryProtocol($transport);
		//$client = new EasyHadoopClient($protocol);
		$transport->open();
		
		$str = $node->ViewLogs($ip, $role, $hostname, $protocol);
		echo $str;

		echo '</pre>';
		echo '</div>';
		$transport->close();
	}
}
elseif($_GET['action'] == "NodeHddSetup")
{
	if(!@$_GET['do'])
	{
		$sql = "select * from ehm_hosts order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		echo '<div class="alert alert-error">';
		echo $lang['nodeHddTipsDanger'];
		echo '</div>';
		echo '<h2>'.$lang['setupHdd'].'</h2>';
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
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['hostname'].'</td>
                  	<td>'.$arr['ip'].'</td>';
                  	
                  	
					 echo '<td>';
					 echo '<a class="btn" href="NodeOperator.php?action=NodeHddSetup&do=Hdd&ip='.$arr['ip'].'">'.$lang['setupHdd'].'</a>';
            		echo '</td>';
	        echo '</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action
	elseif($_GET['do'] == "Hdd")
	{
		$ip = $_GET['ip'];
		$transport = new TSocket($ip, 30050);
		$protocol = new TBinaryProtocol($transport);
		//$client = new EasyHadoopClient($protocol);
		$transport->open();
		if(!$_GET['mount'])
		{
			$list = $node->GetHddList($protocol);
			$list_line = explode("\n",$list);
			$list_first_line = explode(" ", $list_line[0]);
			echo '<div class=span10>';
			echo '<h2>'.$lang['setupHdd'].$ip.'</h2>';
			echo '<div class="alert alert-error">';
			echo $lang['nodeHddTipsDanger'];
			echo '</div>';
			echo '<table class="table table-striped">';
			echo '<thead>
                	<tr>
                  	<th>#</th>
                  	<th>'.trim($list_first_line[0]).'</th>
                  	<th>'.trim($list_first_line[1]).'</th>
                  	<th>'.trim($list_first_line[2]).'</th>
                  	<th>'.trim($list_first_line[3]).'</th>
                  	<th>'.trim($list_first_line[4]).'</th>
                  	<th>'.trim($list_first_line[5]).'</th>
                  	<th>'.$lang['action'].'</th>
                	</tr>
                	</thead>
                	<tbody>';
			$i = 1;
			while ($list_line[$i] != "")
			{
				$list_line_value = explode(" ",$list_line[$i]);
				echo "<tr>";
				echo "<td>".$i."</td>";
				foreach($list_line_value as $k => $v)
				{
					echo "<td>";
					echo trim($v);
					echo "</td>";
				}
				echo '<td><a class="btn" href="NodeOperator.php?action=NodeHddSetup&do=Hdd&ip='.$ip.'&mount='.$list_line_value[5].'">'.$lang['setupHddHadoop'].'</td>';
				echo "</tr>";
				$i++;
			}
			
			echo '</tbody></table>';
			echo '</div>';
			
		}
		else
		{
			$ip = $_GET['ip'];
			$mount = $_GET['mount'];
			$ret = $node->ChangeHddUser($mount,$protocol);
			echo "<script>alert('".$ret."');this.location='NodeOperator.php?action=NodeHddSetup'</script>";
		}
		$transport->close();
	}
	else
	{
		echo '<div class=span10>';
		echo "<h1>".$lang['unknownCommand']."</h1>";
		echo "</div>";
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