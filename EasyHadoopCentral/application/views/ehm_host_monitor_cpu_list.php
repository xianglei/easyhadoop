<div class="span10">

	<pre>
	Sample:<br />
		<div class="progress">
			<div class="bar bar-info" style="width: 25%;">User</div>
			<div class="bar bar-warning" style="width: 25%;">System</div>
			<div class="bar bar-danger" style="width: 25%;">IOWait</div>
			<div class="bar bar-success" style="width: 25%;">Idle</div>
		</div>
	</pre>

	<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>CPU信息</th>
			<th>CPU使用率</td>
		</tr>
	</thead>
	<tbody>
		<?php $i =1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?></td>
			<td><?php echo $item->ip;?></td>
			<td>
			<script>
				function cpu_info_<?php echo $item->host_id;?>()
				{
					$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getcpuinfo/<?php echo $item->host_id;?>', function(json){
						model_name = json.model_name;
						processor = (Number(json.processor) + 1).toString();;
						
						$('#processors_<?php echo $item->host_id;?>').html(processor);
						$('#model_name_<?php echo $item->host_id;?>').html(model_name);
					});
				}
				cpu_info_<?php echo $item->host_id;?>();
			</script>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>Processors</td>
						<td><div id="processors_<?php echo $item->host_id;?>"></div></td>
					</tr>
					<tr>
						<td>Model</td>
						<td><div id="model_name_<?php echo $item->host_id;?>"></div></td>
					</tr>
				</table>
			</td>
			<td>
			<div class="progress">
				<div class="bar bar-info" id="user_<?php echo $item->host_id;?>" style="">User</div>
				<div class="bar bar-warning" id="sys_<?php echo $item->host_id;?>" style="">System</div>
				<div class="bar bar-danger" id="other_<?php echo $item->host_id;?>" style="">IOWait</div>
				<div class="bar bar-success" id="idle_<?php echo $item->host_id;?>" style="">Idle</div>
			</div>
			<script>
			function cpu_usage_<?php echo $item->host_id;?>()
			{
				$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getcpuusage/<?php echo $item->host_id;?>', function(json){
					user = Math.round(json.user);
					sys = Math.round(json.sys);
					etc = Math.round( Number(json.nice) + Number(json.iowait) + Number(json.irq) + Number(json.soft) + Number(json.steal));
					idle = 100 - user - sys - etc;
					
					$('#user_<?php echo $item->host_id;?>').attr('style', 'width: ' + user + '%;');
					$('#sys_<?php echo $item->host_id;?>').attr('style', 'width: ' + sys + '%;');
					$('#other_<?php echo $item->host_id;?>').attr('style', 'width: ' + etc + '%;');
					$('#idle_<?php echo $item->host_id;?>').attr('style', 'width: ' + idle + '%;')
				});
			}
			cpu_usage_<?php echo $item->host_id;?>();
			setInterval(cpu_usage_<?php echo $item->host_id;?>, 2000);
			</script>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
	</table>
<div>
<h3><?php echo $pagination;?></h3>
</div>
	
</div>