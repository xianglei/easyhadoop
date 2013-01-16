<div class="span10">
	
	<pre>
	Sample:<br />
		<div class="progress">
			<div class="bar bar-info" style="width: 33%;">1 min LoadAvg</div>
			<div class="bar bar-warning" style="width: 33%;">5 min LoadAvg</div>
			<div class="bar bar-danger" style="width: 33%;">15 min LoadAvg</div>
		</div>
	</pre>
	
	<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>负载状态</th>
		</tr>
	</thead>
	<tbody>
		<?php $i =1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?></td>
			<td><?php echo $item->ip;?></td>
			<td>
			<div class="progress">
				<div class="bar bar-info" id="load1_<?php echo $item->host_id;?>" style="">1</div>
				<div class="bar bar-warning" id="load5_<?php echo $item->host_id;?>" style="">5</div>
				<div class="bar bar-danger" id="load15_<?php echo $item->host_id;?>" style="">15</div>
			</div>
			<script>
			function host_load_<?php echo $item->host_id;?>()
			{
				$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getloadavg/<?php echo $item->host_id;?>', function(json){
					load1 = Math.round(json.lavg_1);
					load5 = Math.round(json.lavg_5);
					load15 = Math.round(json.lavg_15);
					total = load1 + load5 + load15
					load1_per = Math.round(load1/total);
					load5_per = Math.round(load5/total);
					load15_per = 100 - load1_per - load15_per;
					$('#load1_<?php echo $item->host_id;?>').attr('style', 'width: ' + load1_per + '%;');
					$('#load5_<?php echo $item->host_id;?>').attr('style', 'width: ' + load5_per + '%;');
					$('#load15_<?php echo $item->host_id;?>').attr('style', 'width: ' + load15_per + '%;');
				});
			}
			host_load_<?php echo $item->host_id;?>();
			setInterval(host_load_<?php echo $item->host_id;?>, 2000);
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