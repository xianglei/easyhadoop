<div id="push_general_settings" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">推送通用配置</h3>
	</div>
	<div class="modal-body">

			<div id="push_general_settings_status"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button class="btn btn-danger" onclick="push_general_settings_action()"><?php echo $common_submit;?></button>
	</div>
</div>
<script>
function push_general_settings_action()
{
	$(document).ready(function(){
		$('#push_general_settings_status').empty();
	});
	<?php foreach($all_hosts as $v):?>
	push_general_settings_status_<?php echo $v->host_id;?>();
	<?php endforeach;?>
}
</script>