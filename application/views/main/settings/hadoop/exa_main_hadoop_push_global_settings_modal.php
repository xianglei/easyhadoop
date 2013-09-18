<div id="push_global_settings" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_push_global_settings;?></h3>
	</div>
	<div class="modal-body">

			<div id="push_global_settings_status"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button class="btn btn-danger" onclick="push_global_settings()"><?php echo $common_push;?></button>
	</div>
</div>
</div>
</div>