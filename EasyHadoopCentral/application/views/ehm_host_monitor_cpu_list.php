<div class="span10">

<script>
function cpu_info(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getcpuinfo/' + host_id, function(json){
		model_name = json.model_name;
		processor = (Number(json.processor) + 1).toString();;

		$('#processors_'+host_id).html(processor);
		$('#model_name_'+host_id).html(model_name);
	});
}
function cpu_usage(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getcpuusage/' + host_id, function(json){
		user = Math.round(json.user);
		sys = Math.round(json.sys);
		io = Math.round( Number(json.nice) + Number(json.iowait) + Number(json.irq) + Number(json.soft) + Number(json.steal));
		idle = 100 - user - sys - io;

		$('#user_'+ host_id).attr('style', 'width: ' + user + '%;');
		$('#sys_'+ host_id).attr('style', 'width: ' + sys + '%;');
		$('#io_'+ host_id).attr('style', 'width: ' + io + '%;');
		$('#idle_'+ host_id).attr('style', 'width: ' + idle + '%;')
	});
}
</script>

Sample:<br />
	<div class="progress">
		<div class="bar bar-info" style="" id="sample_user">User</div>
		<div class="bar bar-warning" style="" id="sample_system">System</div>
		<div class="bar bar-danger" style="" id="sample_iowait">IOWait</div>
		<div class="bar bar-success" style="" id="sample_idle">Idle</div>
	</div>
	<script>
	function sample()
	{
		$('#sample_user').attr('style', 'width: 25%;');
		$('#sample_system').attr('style', 'width: 25%;');
		$('#sample_iowait').attr('style', 'width: 25%;');
		$('#sample_idle').attr('style', 'width: 25%;');
	}
	sample();
	</script>

	<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th><?php echo $common_hostname;?></th>
			<th><?php echo $common_ip_addr;?></th>
			<th><?php echo $common_cpu_info;?></th>
			<th><?php echo $common_cpu_usage;?></td>
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
				
				cpu_info(<?php echo $item->host_id;?>);
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
				<div class="bar bar-danger" id="io_<?php echo $item->host_id;?>" style="">IOWait</div>
				<div class="bar bar-success" id="idle_<?php echo $item->host_id;?>" style="">Idle</div>
			</div>
			<script>
			
			cpu_usage(<?php echo $item->host_id;?>);
			setInterval(function()
			{
				cpu_usage(<?php echo $item->host_id;?>)
			}, 2000
			);
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