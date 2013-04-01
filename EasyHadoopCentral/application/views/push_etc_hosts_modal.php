<div id="push_etc_hosts" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_push_hosts;?></h3>
	</div>
	<div class="modal-body">

			<div id="push_etc_hosts_status"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close?></button>
		<button class="btn btn-danger" onclick="push_etc_host_action()"><?php echo $common_submit;?></button>
	</div>
</div>
<script>
function push_etc_host_action()
{
	$(document).ready(function(){
		$('#push_etc_hosts_status').empty();
	});
	<?php foreach($all_hosts as $v):?>
	push_etc_hosts_status(<?php echo $v->host_id;?>);
	<?php endforeach;?>
}

</script>