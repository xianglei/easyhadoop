<?php
set_time_limit(0);
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_monitor_sidebar.html";

$mysql = new Mysql();
$monitor = new NodeMonitor;

if(!$_GET['action'])
{
	$sql = "select * from ehm_hosts where role like 'namenode%'";
	$mysql->Query($sql);
	$arr = $mysql->FetchArray();
	$ip = $arr['ip'];
	$hostname = $arr['hostname'];
	$json = $monitor->GetJson($ip, "namenode");
	#var_dump($json);
	
	foreach($json->{"beans"} as $k => $v)
	{
		$total = $v->{"Total"}/1024/1024/1024;
		break;
	}
	
	foreach($json->{"beans"} as $k => $v)
	{
		$free = $v->{"Free"}/1024/1024/1024;
		break;
	}
	
	foreach($json->{"beans"} as $k => $v)
	{
		$nondfs = $v->{"NonDfsUsedSpace"}/1024/1024/1024;
		break;
	}
	
	foreach($json->{"beans"} as $k => $v)
	{
		$dfs = $v->{"Used"}/1024/1024/1024;
		break;
	}

	$perc_free = ceil(($free/$total)*100);
	$perc_nondfs = ceil(($nondfs/$total)*100);
	$perc_dfs = 100 - ($perc_free + $perc_nondfs);

	echo '<div class=span10>';
	
	
	echo "<pre>";
	echo "Total DFS Space ".$total." GB";
	echo "</pre>";
	echo '
        <div class="progress">
                <div class="bar bar-success" style="width: '.$perc_free.'%;">Free</div>
                <div class="bar bar-warning" style="width: '.$perc_nondfs.'%;">NonDFS</div>
                <div class="bar bar-danger" style="width: '.$perc_dfs.'%;">DFS</div>
        </div>';
    ##################################
    $sql = "select * from ehm_hosts where role like 'datanode%' order by create_time desc";
	$mysql->Query($sql);
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
		echo '<tr>
                 	<td>'.$i.'</td>
                 	<td>'.$arr['hostname'].'</td>
                 	<td>'.$arr['ip'].'</td>';
		echo '<td>';
		$json = $monitor->GetJson($arr['ip'], "datanode");
		
		foreach($json->{"beans"} as $k => $v)
		{
			$total = $v->{"Capacity"}/1024/1024/1024;
			break;
		}
		
		foreach($json->{"beans"} as $k => $v)
		{
			$used = $v->{"DfsUsed"}/1024/1024/1024;
			break;
		}
		
		$perc_used = ceil(($used/$total)*100);
		$perc_remain = 100 - $perc_used;
		
        $bool = $monitor->CheckAgentAlive($arr['ip'], 30050);
		if($bool == FALSE)
		{
			echo '
        		<div class="progress">
                <div class="bar bar-danger" style="width: 100%;">No Agent Alive</div>
       			</div>';
		}
		else
		{
			echo '
        		<div class="progress">
                <div class="bar bar-success" style="width: '.$perc_remain.'%;">Free</div>
                <div class="bar bar-danger" style="width: '.$perc_used.'%;">DFS</div>
        		</div>';
		}
		echo '</td>';
		echo '<td>'.$total.'GB / '.$used.'GB</td>';
        echo '</tr>';
		#unset ($json);
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';
	
	echo '</div>';
}

elseif ($_GET['action'] == "CheckHadoopProcess")
{
	$sql = "select * from ehm_hosts order by create_time desc";
	$mysql->Query($sql);
	echo '<div class=span10>';

	echo '<h2>'.$lang['CheckHadoopProcess'].'</h2>';
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
        $transport = new TSocket($arr['ip'], 30050);
		$protocol = new TBinaryProtocol($transport);
		#$client = new EasyHadoopClient($protocol);
		foreach($arr_role as $key => $value)
		{
			try
			{
				$transport->open();
				$str = $monitor->CheckHadoopProcess($value, $protocol);
				$transport->close();
			}
			catch(exception $e)
			{
				echo "";
			}
			echo '<td>';
            
			if($str == "")
			{
				echo $value." <br /> <span class=\"label label-important\"><i class=\"icon-remove\"></i> ".$lang['notStarted']."</span>";
			}
			else
			{
				echo $value." <br /> <span class=\"label label-success\"><i class=\"icon-ok\"></i>".$lang['processId'].":".$str."</span>";
			}
			echo '</td>';
        }
			
           echo '</tr>';
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';
}


include_once "templates/footer.html";
?>