<div class="span10">

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
			<div class="progress">
				<div class="bar bar-info" id="load1_<?php echo $item->host_id;?>" style="">1</div>
				<div class="bar bar-warning" id="load5_<?php echo $item->host_id;?>" style="">5</div>
				<div class="bar bar-danger" id="load15_<?php echo $item->host_id;?>" style="">15</div>
				<div class="bar bar-success" id="free_<?php echo $item->host_id;?>" style="">Free</div>
			</div>
			<script>
			function host_load_<?php echo $item->host_id;?>()
			{
				$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getloadavg/<?php echo $item->host_id;?>', function(json){
					load1 = Math.round(json.lavg_1);
					load5 = Math.round(json.lavg_5);
					load15 = Math.round(json.lavg_15);
					free = 100 - load1 - load5 - load15;
					$('#load1_<?php echo $item->host_id;?>').attr('style', 'width: ' + load1 + '%;');
					$('#load5_<?php echo $item->host_id;?>').attr('style', 'width: ' + load5 + '%;');
					$('#load15_<?php echo $item->host_id;?>').attr('style', 'width: ' + load15 + '%;');
					$('#free_<?php echo $item->host_id;?>').attr('style', 'width: ' + free + '%;')
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