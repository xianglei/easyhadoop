<div class="span10">

<script>
function viewlogs(role, host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/operate/viewlogs/'+host_id+'/'+role+'/', {}, function(html){
		html = html;
		$('#viewlogs_'+role+'_'+host_id).html('<small>'+html+'</small>');
	});
}
function check_online(role, host_id)
{
	$.ajaxSettings.async = false;
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getpid/'+host_id+'/' + role, function(json){
		if(json.status == 'online')
		{
			$('#status_'+ role +'_'+host_id).removeClass("btn");
			$('#status_'+ role +'_'+host_id).addClass("btn btn-success");
			//$('#show_node_'+host_id).css("display","none");
		}
		else
		{
			$('#status_'+role+'_'+host_id).removeClass("btn");
			$('#status_'+role+'_'+host_id).addClass("btn btn-danger");
			$('#show_node_'+host_id).css("display","block");
		}
	});
}
function autoSubmit(val)
{
	if(val && (val.length>0))
	this.location="<?php echo $this->config->base_url();?>index.php/operate/index/?q="+val;

}
</script>

<table class="table table-striped">
	<!--<thead>
		
	
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>操作</th>
		</tr>
	</thead>-->
	<tbody>
<?php $i = 1; foreach($results as $item):?>
		<tr  id="show_node_<?php echo $item->host_id;?>" style="display:none">
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?>
			</td>
			<td><?php echo $item->ip;?>
			</td>
			<td>
			<table border="0" class="table-condensed" >
			<thead>
				<tr>
					
<?php
$tmp = explode(",", $item->role);
foreach($tmp as $k => $v):
?>
					<th>
				<div class="btn-group"  >
					<a class="btn" id="status_<?php echo $v;?>_<?php echo $item->host_id;?>" data-toggle="modal"  href="#view_<?php echo $v?>_<?php echo $item->host_id?>_modal"><?php echo $v;?> </a>
					<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a data-toggle="modal" href="#view_<?php echo $v?>_<?php echo $item->host_id?>_modal"><i class="icon-search"></i><?php echo $common_view_logs;?></a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/start/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-play"></i><?php echo $common_start;?> <?php echo $v;?></a></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/stop/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-stop"></i><?php echo $common_stop;?> <?php echo $v;?></a></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/restart/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-refresh"></i><?php echo $common_restart;?> <?php echo $v;?></a></li>
					</ul>
				</div>
				</th>
				<!--modaled view logs hdd usage-->
					<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="view_<?php echo $v?>_<?php echo $item->host_id?>_modal">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4><?php echo $item->hostname;?> : <?php echo $item->ip;?> : <?php echo $v;?></h4>
						</div>
						<div class="modal-body" id="viewlogs_<?php echo $v?>_<?php echo $item->host_id?>">
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $common_close;?></button>
						</div>
					</div>
				<!--modal end-->

				<script>
			
				check_online('<?php echo $v?>', <?php echo $item->host_id?>);
				
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
		<h3></h3>
	</div>

</div>