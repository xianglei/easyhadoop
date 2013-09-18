<div class="col-md-10">
<script>

function network_stat(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/netstats/' + host_id, function(json){
		//alert(eth.ReceiveBytes);
		var ReceiveBytes = Number(json.bytes_recv);
		var TransmitBytes = Number(json.bytes_sent);
		var InOut = ReceiveBytes + TransmitBytes;
		var ReceivePer = json.bytes_recv_percent;
		var TransmitPer = json.bytes_sent_percent;
		var RecvAbbr = json.bytes_recv_abbr;
		var TransAbbr = json.bytes_sent_abbr;
		
		$('#network_stats_'+host_id+'_receive').attr("style", "width: "+ReceivePer+"%");
		$('#network_stats_'+host_id+'_transmit').attr('style', "width: "+TransmitPer+"%");
		html = 'RX: ' + RecvAbbr + ' / TX: ' + TransAbbr;
		$('#network_stats_'+host_id+'_numeric').html(html);
	});
}

function bytesToSize (bytes, precision) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  if (bytes == 0) return 'n/a';
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return ((i == 0)? (bytes / Math.pow(1024, i)) : (bytes / Math.pow(1024, i)).toFixed(precision)) + ' ' + sizes[i];
}
</script>
<?php echo $common_sample;?> : <br />
	<div class="progress">
		<div class="progress-bar progress-bar-success" style="" id="sample_in">In</div>
		<div class="progress-bar progress-bar-info" style="" id="sample_out">Out</div>
	</div>
	<script>
	function sample()
	{
		$('#sample_in').attr('style', 'width: 50%;');
		$('#sample_out').attr('style', 'width: 50%;');
	}
	sample();
	</script>

	<table class="table table-striped table_hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $common_hostname;?></th>
				<th><?php echo $common_ip;?></th>
				<th>Network Loads</th>
				<th>Network Transfer</th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1;foreach($results as $item):?>
			<tr>
				<td><?php echo $i?></td>
				<td><?php echo $item->hostname;?></td>
				<td><?php echo $item->ip;?></td>
				<td>
					<div class="progress">
						<div class="progress-bar progress-bar-success" style="" id="network_stats_<?php echo $item->id;?>_receive">Incoming</div>
						<div class="progress-bar progress-bar-info" style="" id="network_stats_<?php echo $item->id;?>_transmit">Outgoing</div>
					</div>
					<script>
					network_stat(<?php echo $item->id;?>);
					setInterval(function()
					{
						network_stat(<?php echo $item->id;?>)
					}, 1000
					);
					</script>
				</td>
				<td id="network_stats_<?php echo $item->id;?>_numeric">

				</td>
			</tr>
		<?php $i++; endforeach;?>
		</tbody>
	</table>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>