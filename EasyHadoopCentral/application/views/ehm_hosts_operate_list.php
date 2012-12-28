<div class="span10">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>操作</th>
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
			<table>
				<tr>
					
<?php
$tmp = explode(",", $item->role);
foreach($tmp as $k => $v):
?>
					<td>
				<div class="btn-group">
					<button class="btn"><?php echo $v;?> </button>
					<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/start/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-play"></i>启动 <?php echo $v;?></a></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/stop/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-stop"></i>停止 <?php echo $v;?></a></li>
						<li><a href="<?php echo $this->config->base_url();?>index.php/operate/restart/<?php echo $item->host_id?>/<?php echo $v?>"><i class="icon-refresh"></i>重启 <?php echo $v;?></a></li>
						<li class="divider"></li>
						<li><a data-toggle="modal" href="#view_<?php echo $v?>_<?php echo $item->host_id?>_modal"><i class="icon-search"></i>查看日志</a></li>
					</ul>
				</div>
				</td>
				<!--modaled detail node hdd usage-->
					<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="view_<?php echo $v?>_<?php echo $item->host_id?>_modal">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4><?php echo $item->hostname;?> : <?php echo $item->ip;?> : <?php echo $v;?></h4>
						</div>
						<div class="modal-body" id="viewlogs_<?php echo $v?>_<?php echo $item->host_id?>">
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						</div>
					</div>
				<!--modal end-->

				<script>
				function viewlogs_<?php echo $v?>_<?php echo $item->host_id?>()
				{
					$.get('<?php echo $this->config->base_url();?>index.php/operate/viewlogs/<?php echo $item->host_id;?>/<?php echo $v;?>/', {}, function(html){
						html = html;
						$('#viewlogs_<?php echo $v?>_<?php echo $item->host_id?>').html(html);
					});
				}
				viewlogs_<?php echo $v?>_<?php echo $item->host_id?>();
				setInterval(viewlogs_<?php echo $v?>_<?php echo $item->host_id?>, 3000);
				</script>
			
<?php
endforeach;
?>
				</tr>
<tr>
<?php
				$tmp = explode(",",$item->role);
				foreach ($tmp as $k => $v):
				?>
					<script>
					function check_online_<?php echo $v;?>_<?php echo $item->host_id;?>()
					{
						$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getpid/<?php echo $item->host_id;?>/<?php echo $v;?>', function(json){
							if(json.status == 'online')
							{
								html = '<span class="label label-success"><i class="icon-ok"></i>' + json.role + '</span>';
							}
							else
							{
								html = '<span class="label label-important"><i class="icon-remove"></i>' + json.role + '</span>';
							}
							$('#check_online_<?php echo $v;?>_<?php echo $item->host_id;?>').html(html);
						});
					}
					check_online_<?php echo $v;?>_<?php echo $item->host_id;?>();
					setInterval(check_online_<?php echo $v;?>_<?php echo $item->host_id;?>, 5000);
					</script>
					<td><div id="check_online_<?php echo $v;?>_<?php echo $item->host_id;?>"></div></td>
				<?php
				endforeach;
				?>

</tr>
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