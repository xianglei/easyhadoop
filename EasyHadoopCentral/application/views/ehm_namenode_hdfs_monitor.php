<div class="span10">
<script>
function namenode_abbr()
{
	$.getJSON('<?php echo $this->config->base_url();?>monitor/namenodestats/<?php echo @$namenode_host_id;?>', function(json){
	html = 'Total DFS Space: ' + json.total_abbr + '<br />';
	html += 'Free DFS Space: ' + json.free_abbr + '<br />';
	html += 'NonDFS Space: ' + json.nondfs_abbr + '<br />';
	html += 'DFS Space: ' + json.dfs_abbr + '<br />';
	html += 'Free precent: ' + json.percent_free + ' %<br />';
	html += 'NonDFS precent: ' + json.percent_nondfs + ' %<br />';
	html += 'DFS precent: ' + json.percent_dfs + ' %<br />';
	$('#namenode_abbr').html(html);
	$('#namenode_progress_free').attr('style', "width: "+json.percent_free+"%;");
	$('#namenode_progress_nondfs').attr('style', "width: "+json.percent_nondfs+"%;");
	$('#namenode_progress_dfs').attr('style', "width: "+json.percent_dfs+"%;");
	});
}
namenode_abbr();
setInterval(namenode_abbr, 30000);

function datanode_use(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>monitor/datanodestats/' + host_id, function(json){
		//alert(json.mem_free_percent);
		//alert(json.mem_used_percent);
		$('#datanode_free_'+host_id).attr("style", "width: "+json.percent_remain+"%;");
		$('#datanode_dfs_'+host_id).attr('style', "width: "+json.percent_used+"%;");
		html = 'Used: ' + json.used_abbr + ' / Total: ' + json.total_abbr;
		$('#datanode_dfs_use_detail_'+host_id).html(html);

		var freeSpace = "";
		html = '<table width=100%>';
		for(var key in json.volume_info){
			freeSpace = Math.round(json.volume_info[key].freeSpace/1024/1024/1024,2);
			usedSpace = Math.round(json.volume_info[key].usedSpace/1024/1024/1024,2);
			reservedSpace = Math.round(json.volume_info[key].reservedSpace/1024/1024/1024,2);
			totalSpace = Math.round((json.volume_info[key].freeSpace + json.volume_info[key].usedSpace + json.volume_info[key].reservedSpace) / 1024/1024/1024, 2);

			var usedPer = Math.round(usedSpace * 100 / totalSpace);
			var reservedPer = Math.round(reservedSpace * 100 / totalSpace);
			var totalPer = 100;
			var freePer = totalPer - usedPer - reservedPer;

			html += '<tr><td><small>挂载点; </small></td><td><small>Free: </small></td><td><small>Used: </small></td><td><small>Reserved: </small></td><td><?php echo $common_storage_status?></td></tr>';
			html += '<tr><td><small>' + key + '</small></td><td><small>' + freeSpace + ' GB</small></td><td><small>' + usedSpace + ' GB</small></td><td><small>' + reservedSpace + ' GB</small></td>';
			html += '<td>';
			html += '<div class="progress">';
			html += '<div class="bar bar-success" style="width: ' + freePer + '%;">Free</div>';
			html += '<div class="bar bar-warning" style="width: ' + reservedPer + '%;">Reserved</div>';
			html += '<div class="bar bar-danger" style="width: ' + usedPer + '%;">DFS</div>';
			html += '</div>'
			html += '</td>';
			html += '</tr>';
		}
		html += '</table>';
		$('#datanode_modal_mountpoint_'+host_id).html(html);
		});
}
</script>
<pre id="namenode_abbr">
	
</pre>
<div class="progress" id="namenode_progress">
	<div class="bar bar-success" style="" id="namenode_progress_free">Free</div>
	<div class="bar bar-warning" style="" id="namenode_progress_nondfs">NonDFS</div>
	<div class="bar bar-danger" style="" id="namenode_progress_dfs">DFS</div>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>存储状态</th>
		</tr>
	</thead>
	<tbody>
	<?php $i=1; foreach($results as $item):?>
		<tr>
			<td><?php echo $i;?></td>
			<td><a data-toggle="modal" href="#datanode_modal_<?php echo $item->host_id;?>"><?php echo $item->hostname;?></a></td>
			<td><?php echo $item->ip;?></td>
			<td>
			<div class="progress">
				<div class="bar bar-success" style="" id="datanode_free_<?php echo $item->host_id;?>">Free</div>
				<div class="bar bar-danger" style="" id="datanode_dfs_<?php echo $item->host_id;?>">DFS</div>
			<script>
			
			datanode_use(<?php echo $item->host_id;?>);
			setInterval(function()
			{
				datanode_use(<?php echo $item->host_id;?>)
			}, 30000);
			</script>
			</div>
			<!--Detail numeric usage-->
			<div id="datanode_dfs_use_detail_<?php echo $item->host_id;?>">
			</div>
			<!--modaled detail node hdd usage-->
			<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="datanode_modal_<?php echo $item->host_id?>">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3><?php echo $item->hostname;?> : <?php echo $item->ip;?></h3>
				</div>
				<div class="modal-body" id="datanode_modal_mountpoint_<?php echo $item->host_id;?>">
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
			</div>
			<!--modal end-->
			</td>
		</tr>
	<?php $i++; endforeach;?>
	</tbody>
</table>
<div>
<h3><?php echo $pagination;?></h3>
</div>
</div>