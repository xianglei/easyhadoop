<div class="span10">
<script>
function check_online(role, host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getpid/'+host_id+'/'+role, function(json){
		if(json.status == 'online')
		{
			html = '<span class="label label-success"><i class="icon-ok"></i>' + json.role + '</span>';
		}
		else
		{
			html = '<span class="label label-important"><i class="icon-remove"></i>' + json.role + '</span>';
		}
		$('#check_online_'+role+'_'+host_id).html(html);
	});
}
function mem_stat(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/memstats/' + host_id, function(json){
		//alert(json.mem_free_percent);
		//alert(json.mem_used_percent);
		$('#mem_stats_'+host_id+'_free').attr("style", "width: "+json.mem_free_percent+"%");
		$('#mem_stats_'+host_id+'_buffers').attr('style', "width: "+json.mem_buffers_percent+"%");
		$('#mem_stats_'+host_id+'_cached').attr('style', "width: "+json.mem_cached_percent+"%");
		$('#mem_stats_'+host_id+'_used').attr('style', "width: "+json.mem_used_percent+"%");
	
		$('#mem_stats_'+host_id+'_free').html(json.mem_free_abbr);
		$('#mem_stats_'+host_id+'_buffers').html(json.mem_buffers_abbr);
		$('#mem_stats_'+host_id+'_cached').html(json.mem_cached_abbr);
		$('#mem_stats_'+host_id+'_used').html(json.mem_used_abbr);

		html = 'Total: ' + json.mem_total_abbr;
		$('#mem_stats_'+host_id+'_numeric').html(html);
	});
}
</script>
Sample:<br />
	<div class="progress">
		<div class="bar bar-success" style="" id="sample_free">Free</div>
		<div class="bar bar-info" style="" id="sample_buffers">Buffers</div>
		<div class="bar bar-warning" style="" id="sample_cached">Cached</div>
		<div class="bar bar-danger" style="" id="sample_used">Used</div>
	</div>
	<script>
	function sample()
	{
		$('#sample_free').attr('style', 'width: 25%;');
		$('#sample_buffers').attr('style', 'width: 25%;');
		$('#sample_cached').attr('style', 'width: 25%;');
		$('#sample_used').attr('style', 'width: 25%;');
	}
	sample();
	</script>

	<table class="table table-striped table_hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $common_hostname;?></th>
				<th><?php echo $common_ip_addr;?></th>
				<th><?php echo $common_node_role;?></th>
				<th><?php echo $common_mem_status;?></th>
				<th><?php echo $common_mem_status;?></th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1;foreach($results as $item):?>
			<tr>
				<td><?php echo $i?></td>
				<td><?php echo $item->hostname;?></td>
				<td><?php echo $item->ip;?></td>
				<td>
				<?php
				$tmp = explode(",",$item->role);
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
					<div id="check_online_<?php echo $v;?>_<?php echo $item->host_id;?>"></div>
				<?php
				endforeach;
				?>

				</td>
				<td>
					<div class="progress">
						<div class="bar bar-success" style="" id="mem_stats_<?php echo $item->host_id;?>_free">Free</div>
						<div class="bar bar-info" style="" id="mem_stats_<?php echo $item->host_id;?>_buffers">Buffers</div>
						<div class="bar bar-warning" style="" id="mem_stats_<?php echo $item->host_id;?>_cached">Cached</div>
						<div class="bar bar-danger" style="" id="mem_stats_<?php echo $item->host_id;?>_used">Used</div>
					</div>
					<script>
					mem_stat(<?php echo $item->host_id;?>);
					setInterval(function()
					{
						mem_stat(<?php echo $item->host_id;?>)
					}, 2000
					);
					</script>
				</td>
				<td id="mem_stats_<?php echo $item->host_id;?>_numeric">

				</td>
			</tr>
		<?php $i++; endforeach;?>
		</tbody>
	</table>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>