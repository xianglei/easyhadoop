<div class="col-md-10">
<script>
function viewlogs(role, host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/eco/viewlogs/'+host_id+'/'+role+'/', {}, function(html){
		html = html;
		$('#viewlogs_'+role+'_'+host_id).html('<small>'+html+'</small>');
	});
}
function check_online(role, host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/eco/getpid/'+host_id+'/' + role+'/', function(json){
		if(json.status == 'online')
		{
			$('#status_'+ role +'_'+host_id).removeClass("btn");
			$('#status_'+ role +'_'+host_id).addClass("btn btn-success");
		}
		else
		{
			$('#status_'+role+'_'+host_id).removeClass("btn");
			$('#status_'+role+'_'+host_id).addClass("btn btn-danger");
		}
	});
}
</script>

		<button type="button" class="btn btn-danger btn-sm" title="Tip" data-toggle="popover" data-placement="bottom" data-content="<?php echo $common_eco_hadoop_format_tip;?>" disabled><?php echo $common_eco_hadoop_format?></button>

	<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th><?php echo $common_hostname;?></th>
			<th><?php echo $common_ip;?></th>
			<th><?php echo $common_action;?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?>
			</td>
			<td><?php echo $item->ip;?>
			</td>
			<td>
			<table border="0" class="table-condensed">
			<thead>
				<tr>
<?php
$namenode = ($item->namenode == 1) ? "namenode" : "";
$secondarynamenode = ($item->secondarynamenode == 1) ? "secondarynamenode" : "";
$datanode = ($item->datanode == 1) ? "datanode" : "";
$jobtracker = ($item->jobtracker == 1) ? "jobtracker" : "";
$tasktracker = ($item->tasktracker == 1) ? "tasktracker" : "";

$tmp = array($namenode, $secondarynamenode, $datanode, $jobtracker, $tasktracker);
$tmp = array_filter($tmp);
foreach($tmp as $k => $v):
?>
					<th>
				<div class="btn-group">
					<a class="btn btn-default btn-sm" id="status_<?php echo $v;?>_<?php echo $item->id;?>" data-toggle="modal"  href="#view_<?php echo $v?>_<?php echo $item->id?>_modal"><?php echo $v;?> </a>
					<button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a data-toggle="modal" href="#view_<?php echo $v?>_<?php echo $item->id?>_modal"><i class="icon-search"></i><?php echo $common_view_logs;?></a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/eco/starthadoop/<?php echo $item->id?>/<?php echo $v?>"><i class="icon-play"></i><?php echo $common_start;?> <?php echo $v;?></a></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/eco/stophadoop/<?php echo $item->id?>/<?php echo $v?>"><i class="icon-stop"></i><?php echo $common_stop;?> <?php echo $v;?></a></li>
					</ul>
				</div>
				</th>
				<!--modaled view logs hdd usage-->
					<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="view_<?php echo $v?>_<?php echo $item->id?>_modal">
					<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4><?php echo $item->hostname;?> : <?php echo $item->ip;?> : <?php echo $v;?></h4>
						</div>
						<div class="modal-body" id="viewlogs_<?php echo $v?>_<?php echo $item->id?>">
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $common_close;?></button>
						</div>
					</div>
					</div>
					</div>
				<!--modal end-->

				<script>
				
				viewlogs('<?php echo $v?>',<?php echo $item->id;?>);
				setInterval(function()
				{
					viewlogs('<?php echo $v?>', <?php echo $item->id;?>)
				}, 3000);
				
				check_online('<?php echo $v?>', <?php echo $item->id;?>);
				setInterval(function()
				{
					check_online('<?php echo $v?>', <?php echo $item->id;?>)
				}, 3000);
				</script>
			
<?php
endforeach;
?>
					</tr>
				</thead>
				</table>
			</td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>

<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>