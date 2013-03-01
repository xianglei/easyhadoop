<div class=span10>
<script>
function jobtracker_abbr()
{
	$.getJSON('<?php echo $this->config->base_url();?>monitor/jobtrackerstats/<?php echo $jobtracker_host_id;?>', function(json){
		var freeMapSlots = json.map_slots - json.running_maps;
		var freeReduceSlots = json.reduce_slots - json.running_reduces
		
		html = "Map/Reduce real time monitoring. <br/><br/><br/>";
		html += 'Total Map Slots: ' + json.map_slots + '<br />';
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
jobtracker_abbr();
setInterval(jobtracker_abbr, 2000);

function tasktracker_use(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>monitor/tasktrackerstats/' + host_id, function(json){
		//alert(json.mem_free_percent);
		//alert(json.mem_used_percent);
		$('#mapred_tasktracker_map_free_' + host_id).attr("style", "width: "+json.percent_map_remain+"%;");
		$('#mapred_tasktracker_map_used_' + host_id).attr('style', "width: "+json.percent_map_running+"%;");
		$('#mapred_tasktracker_reduce_free_' + host_id).attr("style", "width: "+json.percent_reduce_remain+"%;");
		$('#mapred_tasktracker_reduce_used_' + host_id).attr('style', "width: "+json.percent_reduce_running+"%;");
		html = 'Used: ' + json.maps_running + ' / Total: ' + json.map_task_slots;
		$('#mapred_tasktracker_map_detail_' + host_id).html(html);
		html = 'Used: ' + json.reduces_running + ' / Total: ' + json.reduce_task_slots;
		$('#mapred_tasktracker_reduce_detail_' + host_id).html(html);
	});
}

function line_mapred()
{
var map=0;
var reduce=0;

$(function () {
	$(document).ready(function() {
		Highcharts.setOptions({
			global: {
				useUTC: false
			}
		});

		var chart;
		chart = new Highcharts.Chart({
			chart: {
				backgroundColor: "#FFFFFF",
				renderTo: 'mapred_slots_lines',
				type: 'spline',
				marginRight: 10,
				events: {
					load: function() {

						// set up the updating of the chart each second
						var maps = this.series[0];
						var reduces = this.series[1];
						setInterval(function() {
							var x1 = (new Date()).getTime(), // current time
								y1 = map;
							var x2 = (new Date()).getTime(),
								y2 = reduce;
								
							maps.addPoint([x1, y1], true, true);
							reduces.addPoint([x2, y2], true, true);
							$.getJSON("<?php echo $this->config->base_url();?>monitor/jobtrackerstats/<?php echo $jobtracker_host_id;?>", function(data){
								map=data.running_maps;
								reduce = data.running_reduces;
							});
						}, 2000);
					}
				}
			},
			title: {
				text: 'Map/Reduce'
			},
			xAxis: {
				type: 'datetime',
				tickPixelInterval: 120
			},
			yAxis: {
				title: {
					text: 'Map/Reduce'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}
				]
			},
			tooltip: {
				formatter: function() {
						return '<b>'+ this.series.name +'</b><br/>'+
						Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
						Highcharts.numberFormat(this.y, 2);
				}
			},
			legend: {
				enabled: true
			},
			exporting: {
				enabled: false
			},
			series: [{
				name: 'Map slots',
				data: (function() {
					// generate an array of random data
					var map = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						map.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return map;
				})()
			},
			{
				name: 'Reduce slots',
				data: (function() {
					// generate an array of random data
					var reduce = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						reduce.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return reduce;
				})()
			}
			]
		});
	});
	
});
}
line_mapred();
</script>
<div class="row">
	<div class="span4">
		<pre id="jobtracker_mapred">
	
		</pre>
	</div>
	<div class="span8">
		<div id="mapred_slots_lines" style="min-width: 200px; height: 200px; margin: 0 auto"></div>
	</div>
</div>
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

				tasktracker_use(<?php echo $item->host_id;?>);
				setInterval(function()
				{
					tasktracker_use(<?php echo $item->host_id;?>)
				}, 2000);
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