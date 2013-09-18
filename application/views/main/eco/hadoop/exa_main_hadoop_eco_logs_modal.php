<script>
function view_hadoop_logs(role, host_id)
{
	//var role = $('#hadoop_role').val();
	//var host_id = $('#hadoop_host_id').val();
	$('view_hadoop_log_modal').modal('toggle');
	$.get('<?php echo $this->config->base_url();?>index.php/eco/viewlogs/'+host_id+'/'+role+'/', {}, function(html){
		html = html;
		$('#viewlogs_hadoop').html('<small>'+html+'</small>');
	});
}

function get_hadoop_logs(role, host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/eco/viewlogs/'+host_id+'/'+role+'/', {}, function(html){
		html = html;
		$('#viewlogs_hadoop').html('<small>'+html+'</small>');
	});
}
/*setInterval(function()
{
	viewlogs('<?php echo $v?>', <?php echo $item->id;?>)
}, 3000);*/
</script>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="view_hadoop_log_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4><?php echo $item->hostname;?> : <?php echo $item->ip;?> : <?php echo $v;?></h4>
			</div>
			<div class="modal-body" id="viewlogs_hadoop">
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $common_close;?></button>
				<input type="hidden" name="hadoop_role" id="hadoop_role" value="">
				<input type="hidden" name="hadoop_host_id" id="hadoop_host_id" value="">
			</div>
		</div>
	</div>
</div>