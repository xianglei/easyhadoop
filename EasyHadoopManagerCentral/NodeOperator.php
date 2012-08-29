<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/node_operator_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	Choose left sidebar for next step.
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
                  	<td>'.$arr['ip'].'</td>
                  	<td>';
                  	echo '<div class="btn-group">';
			foreach($arr_role as $key => $value)
			{
            			
   					 echo '<a class="btn" href="NodeOperator.php?action=Operate&do=Start&ip='.$arr['ip'].'&role='.$value.'">'.$lang['start'].$value.'</a>
   					 <a class="btn btn-danger" href="NodeOperator.php?action=Operate&do=Stop&ip='.$arr['ip'].'&role='.$value.'">'.$lang['stop'].$value.'</a>
					 <a class="btn btn-danger" href="NodeOperator.php?action=Operate&do=Restart&ip='.$arr['ip'].'&role='.$value.'">'.$lang['restart'].$value.'</a>';
	        	
	        }
			echo '</div>';
            echo '</td>
               			</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action
}
?>