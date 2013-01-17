<div class="span10">

<script>
function host_load(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getloadavg/' + host_id, function(json){
		load1 = parseFloat(json.lavg_1);
		load5 = parseFloat(json.lavg_5);
		load15 = parseFloat(json.lavg_15);
		total = load1 + load5 + load15;
		load1_per = Math.round((load1/total)*100);
		load5_per = Math.round((load5/total)*100);
		load15_per = 100 - load1_per - load5_per;
		$('#load1_' + host_id).attr('style', 'width: ' + load1_per + '%;');
		$('#load5_' + host_id).attr('style', 'width: ' + load5_per + '%;');
		$('#load15_' + host_id).attr('style', 'width: ' + load15_per + '%;');
		$('#last_pid_' + host_id).html(json.last_pid);

		$('#load1_' + host_id).html(json.lavg_1);
		$('#load5_' + host_id).html(json.lavg_5);
		$('#load15_' + host_id).html(json.lavg_15);
	});
}
</script>
	Sample:
	<br />
	<div class="progress">
		<div class="bar bar-info" style="" id="sample_1load">1 min LoadAvg</div>
		<div class="bar bar-warning" style="" id="sample_5load">5 min LoadAvg</div>
		<div class="bar bar-danger" style="" id="sample_15load">15 min LoadAvg</div>
	</div>
	<script>
	function sample()
	{
		$('#sample_1load').attr('style', 'width: 33%;');
		$('#sample_5load').attr('style', 'width: 33%;');
		$('#sample_15load').attr('style', 'width: 34%;');
	}
	sample();
	</script>
	<br />
	
	<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>活跃PID</th>
			<th>负载状态</th>
		</tr>
	</thead>
	<tbody>
		<?php $i =1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?></td>
			<td><?php echo $item->ip;?></td>
			<td id="last_pid_<?php echo $item->host_id;?>"></td>
			<td>
			<div class="progress">
				<div class="bar bar-info" id="load1_<?php echo $item->host_id;?>" style=""></div>
				<div class="bar bar-warning" id="load5_<?php echo $item->host_id;?>" style=""></div>
				<div class="bar bar-danger" id="load15_<?php echo $item->host_id;?>" style=""></div>
			</div>
			<script>
			
			host_load(<?php echo $item->host_id;?>);
			setInterval(function()
			{
				host_load(<?php echo $item->host_id;?>)
			}, 2000);
			</script>
			</td>
		</tr>
		<?php $i++; endforeach;?>
	</tbody>
	</table>
<div>
<h3><?php echo $pagination;?></h3>
</div>
	
</div>