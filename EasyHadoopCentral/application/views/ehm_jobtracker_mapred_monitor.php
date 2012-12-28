<div class=span10>
<script>
function namenode_abbr()
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/jobtrackerstats/<?php echo $jobtracker_host_id;?>', function(json){
	var freeMapSlots = json.map_slots - json.running_maps;
	var freeReduceSlots = json.reduce_slots - json.running_reduces
	html = 'Total Map Slots: ' + json.map_slots + '<br />';
	html += 'Total Reduce Slots: ' + json.reduce_slots + '<br />';
	html += 'Running Map Slots: ' + json.running_maps + '<br />';
	html += 'Running Reduce Slots: ' + json.running_reduces + '<br />';
	$('#jobtracker_mapred').html(html);
	$('#mapred_jobtracker_map_free').attr('style', "width: "+json.percent_map_not_running+"%;");
	$('#mapred_jobtracker_map_free').html('Free: ' + freeMapSlots);
	$('#mapred_jobtracker_map_running').html('Running: ' + json.running_maps);
	$('#mapred_jobtracker_map_running').attr('style', "width: "+json.percent_map_running+"%;");
	$('#mapred_jobtracker_reduce_free').attr('style', "width: "+json.percent_reduce_not_running+"%;");
	$('#mapred_jobtracker_reduce_free').html('Free:' + freeReduceSlots);
	$('#mapred_jobtracker_reduce_running').attr('style', "width: "+json.percent_reduce_running+"%;");
	$('#mapred_jobtracker_reduce_running').html('Running:' + json.running_reduces);
	});
}
namenode_abbr();
setInterval(namenode_abbr, 2000);
</script>
<pre id="jobtracker_mapred">
	
</pre>
Map:
<div class="progress" id="jobtracker_map_progress">
	<div class="bar bar-success" style="" id="mapred_jobtracker_map_free">Free</div>
	<div class="bar bar-danger" style="" id="mapred_jobtracker_map_running">Running</div>
</div>

Reduce:
<div class="progress" id="jobtracker_reduce_progress">
	<div class="bar bar-success" style="" id="mapred_jobtracker_reduce_free">Free</div>
	<div class="bar bar-danger" style="" id="mapred_jobtracker_reduce_running">Running</div>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>Map状态</th>
			<th>Map状态</th>
			<th>Reduce状态</th>
			<th>Reduce状态</th>
		</tr>
	</thead>
	<tbody>
	<?php $i=1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?></td>
			<td><?php echo $item->ip;?></td>
			<td>
				<div class="progress">
					<div class="bar bar-success" style="" id="mapred_tasktracker_map_free_<?php echo $item->host_id;?>">Free</div>
					<div class="bar bar-danger" style="" id="mapred_tasktracker_map_used_<?php echo $item->host_id;?>">Used</div>
				<script>
				function tasktracker_use_<?php echo $item->host_id;?>()
				{
					$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/tasktrackerstats/<?php echo $item->host_id;?>', function(json){
					//alert(json.mem_free_percent);
					//alert(json.mem_used_percent);
						$('#mapred_tasktracker_map_free_<?php echo $item->host_id;?>').attr("style", "width: "+json.percent_map_remain+"%;");
						$('#mapred_tasktracker_map_used_<?php echo $item->host_id;?>').attr('style', "width: "+json.percent_map_running+"%;");
						$('#mapred_tasktracker_reduce_free_<?php echo $item->host_id;?>').attr("style", "width: "+json.percent_reduce_remain+"%;");
						$('#mapred_tasktracker_reduce_used_<?php echo $item->host_id;?>').attr('style', "width: "+json.percent_reduce_running+"%;");
						html = 'Used: ' + json.maps_running + ' / Total: ' + json.map_task_slots;
						$('#mapred_tasktracker_map_detail_<?php echo $item->host_id;?>').html(html);
						html = 'Used: ' + json.reduces_running + ' / Total: ' + json.reduce_task_slots;
						$('#mapred_tasktracker_reduce_detail_<?php echo $item->host_id;?>').html(html);
					});
				}
				tasktracker_use_<?php echo $item->host_id;?>();
				setInterval(tasktracker_use_<?php echo $item->host_id;?>, 2000);
				</script>
				</div>
			</td>
			<td>
				<!--Detail numeric usage-->
				<div id="mapred_tasktracker_map_detail_<?php echo $item->host_id;?>">
				</div>
			</td>
			<td>
				<div class="progress">
					<div class="bar bar-success" style="" id="mapred_tasktracker_reduce_free_<?php echo $item->host_id;?>">Free</div>
					<div class="bar bar-danger" style="" id="mapred_tasktracker_reduce_used_<?php echo $item->host_id;?>">Used</div>
				</div>
			</td>
			<td>
				<!--Detail numeric usage-->
				<div id="mapred_tasktracker_reduce_detail_<?php echo $item->host_id;?>">
				</div>
			</td>
		</tr>
	<?php $i++; endforeach;?>
	</tbody>
</table>
<div>
<h3><?php echo $pagination;?></h3>
</div>
</div>