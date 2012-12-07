<?php
set_time_limit(0);
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_monitor_sidebar.html";

$mysql = new Mysql();
$monitor = new NodeMonitor;


if(!$_GET['action'])
{
	
}

elseif($_GET['action'] == 'MrUsed')
{
	
	
	$sql = "select * from ehm_hosts where role like '%jobtracker%'";
	$mysql->Query($sql);
	$arr = $mysql->FetchArray();
	$ip = $arr['ip'];
	$hostname = $arr['hostname'];
	
	$transport = new TSocket($arr['ip'], 30050);
	$protocol = new TBinaryProtocol($transport);
	try
	{
		$transport->open();
		$json = $monitor->GetJmx($protocol, 'jobtracker');
		$transport->close();
	}
	catch(exception $e)
	{
		echo "";
	}
	
	
	$json = $monitor->ParseJson($json); 
	$map_slots = intval($monitor->GetJsonObject($json->{'beans'}, 'map_slots'));
	$reduce_slots = intval($monitor->GetJsonObject($json->{'beans'}, 'reduce_slots'));
	$running_maps = intval($monitor->GetJsonObject($json->{'beans'}, 'running_maps'));
	$running_reduces = intval($monitor->GetJsonObject($json->{'beans'}, 'running_reduces'));
	
	$perc_map_running = round(($running_maps/$map_slots)*100);
	$perc_map_not_running = 100 - $perc_map_running;
	$perc_reduce_running = round(($running_reduces/$reduce_slots)*100);
	$perc_reduce_not_running = 100 - $perc_reduce_running;
	
	echo "<div class=span10>";
	
	echo "<pre>";
	echo "Total Map Slots: ".$map_slots." <br />";
	echo "Total Reduce Slots: ".$reduce_slots." <br />";
	echo "Running Map Slots: ".$running_maps." <br />";
	echo "Running Reduce Slots: ".$running_reduces." <br />";
	echo "</pre>";

	echo '
        <div class="progress">
                <div class="bar bar-warning" style="width: '.$perc_map_running.'%;">Running MapSlots</div>
                <div class="bar bar-success" style="width: '.$perc_map_not_running.'%;">Free MapSlots</div>
        </div>';
		
	echo '
        <div class="progress">
                <div class="bar bar-warning" style="width: '.$perc_reduce_running.'%;">Running ReduceSlots</div>
                <div class="bar bar-success" style="width: '.$perc_reduce_not_running.'%;">Free ReduceSlots</div>
        </div>';
	##################################
    $sql = "select * from ehm_hosts where role like '%tasktracker%' order by create_time desc";
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
                 	<td><a href=NodeMonitor.php?action=NodeMrUsed&ip='.$arr['ip'].'>'.$arr['hostname'].'</a></td>
                 	<td>'.$arr['ip'].'</td>';
		echo '<td>';
		
		$transport = new TSocket($arr['ip'], 30050);
		$protocol = new TBinaryProtocol($transport);
		try
		{
			$transport->open();
			$json = $monitor->GetJmx($protocol, 'tasktracker');
			$transport->close();
		}
		catch(exception $e)
		{
			echo "";
		}
		
		$json = $monitor->ParseJson($json);
		
		$map_task_slots = intval($monitor->GetJsonObject($json->{"beans"},"mapTaskSlots"));
		$maps_running = intval($monitor->GetJsonObject($json->{"beans"},"maps_running"));

		$reduce_task_slots = intval($monitor->GetJsonObject($json->{"beans"},"reduceTaskSlots"));
		$reduces_running = intval($monitor->GetJsonObject($json->{"beans"},"reduces_running"));

		$perc_map_running = round(($maps_running/$map_task_slots)*100);
		$perc_map_remain = 100 - $perc_map_running;
		$perc_reduce_running = round(($reduces_running/$reduce_task_slots)*100);
		$perc_reduce_remain = 100 - $perc_reduce_running;
		
        $bool = $monitor->CheckAgentAlive($arr['ip'], 30050);
		if($bool == FALSE)
		{
			echo '
        		<div class="progress">
                <div class="bar bar-danger" style="width: 100%;">No Agent Alive</div>
       			</div>';
			echo '
        		<div class="progress">
                <div class="bar bar-danger" style="width: 100%;">No Agent Alive</div>
       			</div>';
		}
		else
		{
			echo '
        		<div class="progress">
                <div class="bar bar-success" style="width: '.$perc_map_remain.'%;">Free Map Slots</div>
                <div class="bar bar-danger" style="width: '.$perc_map_running.'%;">Running Map Slots</div>
        		</div>';
			echo '
        		<div class="progress">
                <div class="bar bar-success" style="width: '.$perc_reduce_remain.'%;">Free Reduce Slots</div>
                <div class="bar bar-danger" style="width: '.$perc_reduce_running.'%;">Running Reduce Slots</div>
        		</div>';
		}
		echo '</td>';
		echo '<td>Map:'.$maps_running.' /  '.$map_task_slots.'<br /> Reduce:'.$reduces_running.' / '.$reduce_task_slots.'</td>';
        echo '</tr>';
		#unset ($json);
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';

	echo "</div>";
}

elseif($_GET['action'] == "NodeMrUsed")
{
	
}

elseif($_GET['action'] == "HddUsed")
{
	$sql = "select * from ehm_hosts where role like 'namenode%'";
	$mysql->Query($sql);
	$arr = $mysql->FetchArray();
	$ip = $arr['ip'];
	$hostname = $arr['hostname'];
	
	$transport = new TSocket($arr['ip'], 30050);
	$protocol = new TBinaryProtocol($transport);
	try
	{
		$transport->open();
		$json = $monitor->GetJmx($protocol, 'namenode');
		$transport->close();
	}
	catch(exception $e)
	{
		echo "";
	}
	
	$json = $monitor->ParseJson($json);
	#var_dump($json);
	$total = $monitor->GetJsonObject($json->{"beans"}, "Total");
	$free = $monitor->GetJsonObject($json->{"beans"},"Free");
	$nondfs = $monitor->GetJsonObject($json->{"beans"},"NonDfsUsedSpace");
	$dfs = $monitor->GetJsonObject($json->{"beans"},"Used");

	$perc_free = round(($free/$total)*100);
	$perc_nondfs = round(($nondfs/$total)*100);
	$perc_dfs = 100 - ($perc_free + $perc_nondfs);

	echo '<div class=span10>';
	
	
	echo "<pre>";
	echo "Total DFS Space ".$monitor->GetRealSize($total)." <br />";
	echo "Free DFS Space ".$monitor->GetRealSize($free)." / ".$perc_free."% Percentage <br />";
	echo "NonDFS Space ".$monitor->GetRealSize($nondfs)." / ".$perc_nondfs."% Percentage <br />";
	echo "DFS Space ".$monitor->GetRealSize($dfs)." / ".$perc_dfs."% Percentage <br />";
	echo "</pre>";
	echo '
        <div class="progress">
                <div class="bar bar-success" style="width: '.$perc_free.'%;">Free</div>
                <div class="bar bar-warning" style="width: '.$perc_nondfs.'%;">NonDFS</div>
                <div class="bar bar-danger" style="width: '.$perc_dfs.'%;">DFS</div>
        </div>';
    ##################################
    $sql = "select * from ehm_hosts where role like '%datanode%' order by create_time desc";
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
                 	<td><a href=NodeMonitor.php?action=NodeHddUsed&ip='.$arr['ip'].'>'.$arr['hostname'].'</a></td>
                 	<td>'.$arr['ip'].'</td>';
		echo '<td>';
		
		$ip = $arr['ip'];
		$transport = new TSocket($arr['ip'], 30050);
		$protocol = new TBinaryProtocol($transport);
		try
		{
			$transport->open();
			$json = $monitor->GetJmx($protocol, 'datanode');
			$transport->close();
		}
		catch(exception $e)
		{
			echo "";
		}
		
		$json = $monitor->ParseJson($json);
		
		$total = $monitor->GetJsonObject($json->{"beans"},"Capacity");
		$used = $monitor->GetJsonObject($json->{"beans"},"DfsUsed");
		
		$perc_used = round(($used/$total)*100);
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
		echo '<td>'.$monitor->GetRealSize($total).' /  '.$monitor->GetRealSize($used).'</td>';
        echo '</tr>';
		#unset ($json);
		$i++;
	}
	echo '</tbody></table>';
	echo '</div>';
	
	echo '</div>';
}

elseif($_GET['action'] == "NodeHddUsed")
{
	if(!$_GET['ip'])
	{
		echo '<div class=span10>';
		echo "Invalid Entry";
		echo "</div>";
	}
	else
	{
		$ip = $_GET['ip'];
		echo '<div class=span10>';
		
		$transport = new TSocket($ip, 30050);
		$protocol = new TBinaryProtocol($transport);
		try
		{
			$transport->open();
			$json = $monitor->GetJmx($protocol, 'datanode');
			$transport->close();
		}
		catch(exception $e)
		{
			echo "";
		}

		$json = $monitor->ParseJson($json);
		foreach($json->{"beans"} as $k => $v)
		{
			$volumeinfo = $v->{"VolumeInfo"};
			if($volumeinfo != "")
				break;
		}
		$json = json_decode($volumeinfo,true);
		#var_dump($json);
		
		echo '<table class="table table-striped">';
		echo '<thead>
               <tr>
                 <th>#</th>
                 <th>'.$lang['hostname'].$ip.'</th>
                 <th>'.$lang['action'].'</th>
                 <th>'.$lang['action'].'</th>
               </tr>
               </thead>
               <tbody>';
		$i = 1;
		foreach($json as $k => $v)
		{
			echo "<tr>";
			echo "<td>";
			echo $i;
			echo "</td>";
			echo "<td>";
			echo $k;
			echo "</td>";
			echo "<td>";
			$free = $v["freeSpace"];
			$used = $v["usedSpace"];
			$reserved = $v["reservedSpace"];
			$total = $free+$used+$reserved;
			$perc_free = round(($free/$total)*100);
			$perc_used = round(($used/$total)*100);
			$perc_reserved = 100 - $perc_free - $perc_used;
			
			echo '
        		<div class="progress">
                <div class="bar bar-success" style="width: '.$perc_free.'%;">Free</div>
                <div class="bar bar-warning" style="width: '.$perc_reserved.'%;">NonDFS</div>
                <div class="bar bar-danger" style="width: '.$perc_used.'%;">DFS</div>
        		</div>';
			
			echo "</td>";
			echo '<td>Free:'.$perc_free.'% / NonDFS:'.$perc_reserved.'% / Used:'.$perc_used.'%</td>';
			echo "</tr>";
			$i++;
		}
		echo '</tbody></table>';
		echo "</div>";
	}
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