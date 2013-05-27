<div class=span10>
<script>

function check_online(role, host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getpid/'+host_id+'/'+role, function(json){
		if(json.status == 'online')
		{
			$('#btn_'+host_id+'_'+role).removeClass("btn").addClass("btn btn-success");
		}
		else
		{
			$('#btn_'+host_id+'_'+role).removeClass("btn").addClass("btn btn-success");
		}
	});
}


function line_jvm_namenode()
{
var memNonHeapUsedM=0;
var memHeapUsedM=0;
var memNonHeapCommittedM = 0;
var memHeapCommittedM=0;

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
				type: 'area',
				marginRight: 10,
				events: {
					load: function() {

						// set up the updating of the chart each second
						var memNonHeapUsedMs = this.series[0];
						var memHeapUsedMs = this.series[1];
						var memNonHeapCommittedMs = this.series[2];
						var memHeapCommittedMs = this.series[3];
						setInterval(function() {
							var x1 = (new Date()).getTime(), // current time
								y1 = memNonHeapUsedM;
							var x2 = (new Date()).getTime(),
								y2 = memHeapUsedM;
							var x3 = (new Date()).getTime(), // current time
								y3 = memNonHeapCommittedM;
							var x4 = (new Date()).getTime(),
								y4 = memHeapCommittedM;
								
							memNonHeapUsedMs.addPoint([x1, y1], true, true);
							memHeapUsedMs.addPoint([x2, y2], true, true);
							memNonHeapCommittedMs.addPoint([x3, y3], true, true);
							memHeapCommittedMs.addPoint([x4, y4], true, true);
							$.getJSON("<?php echo $this->config->base_url();?>index.php/monitor/getrolejvm/<?php echo $jobtracker_host_id;?>/tasktracker", function(data){
								memNonHeapUsedM=data.memNonHeapUsedM;
								memHeapUsedM = data.memHeapUsedM;
								memNonHeapCommittedM=data.memNonHeapCommittedM;
								memHeapCommittedM = data.memHeapCommittedM;
							});
						}, 1000);
					}
				}
			},
			title: {
				text: 'Heap/NonHeap/HeapCommitted/NonHeapCommitted'
			},
			xAxis: {
				type: 'datetime',
				tickPixelInterval: 120
			},
			yAxis: {
				title: {
					text: 'Java Heap'
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
			plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
			series: [{
				name: 'JVM Non Heap Used',
				data: (function() {
					// generate an array of random data
					var memNonHeapUsedM = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						memNonHeapUsedM.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return memNonHeapUsedM;
				})()
			},
			{
				name: 'JVM Heap Used',
				data: (function() {
					// generate an array of random data
					var memHeapUsedM = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						memHeapUsedM.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return memHeapUsedM;
				})()
			},
			{
				name: 'JVM Non Heap Committed',
				data: (function() {
					// generate an array of random data
					var memNonHeapCommittedM = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						memNonHeapCommittedM.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return memNonHeapCommittedM;
				})()
			},
			{
				name: 'JVM Heap Committed',
				data: (function() {
					// generate an array of random data
					var memHeapCommittedM = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						memHeapCommittedM.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return memHeapCommittedM;
				})()
			}
			]
		});
	});
	
});
}
line_jvm_namenode();
</script>
<div class="row">
	<div class="span4">
		<pre id="jobtracker_mapred">
	
		</pre>
	</div>
	<div class="span8">
		<div id="mapred_slots_lines" style="min-width: 100px; height: 300px; margin: 0 auto"></div>
	</div>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th><?php echo $common_hostname;?></th>
			<th><?php echo $common_ip_addr;?></th>
			<th><?php echo $common_node_role;?></th>
		</tr>
	</thead>
	<tbody>
	<?php $i=1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?></td>
			<td><?php echo $item->ip;?></td>
			<td>
			<?php
				$tmp = explode(",",$item->role);
			?>
				<div class="btn-group">
					<button class="btn btn-small">Click <i class="icon-play"></i></button>
				<?php
				foreach ($tmp as $k => $v):
				?>
					<script>
					check_online('<?php echo $v;?>', <?php echo $item->host_id;?>);
					setInterval(function()
					{
						check_online('<?php echo $v;?>', <?php echo $item->host_id;?>)
					}, 10000
					);
					</script>
					<button id="btn_<?php echo $item->host_id;?>_<?php echo $v;?>" class="btn btn-small"><?php echo $v;?></button>
				<?php
				endforeach;
				?>
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