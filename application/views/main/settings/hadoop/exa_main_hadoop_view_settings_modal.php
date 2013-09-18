<!--view settings area-->
<div id="view_hadoop_settings" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel"><?php echo $common_view_settings?></h3>
			</div>
			<div class="modal-body">
				<label><?php echo $common_ip;?> : <font color="red"><span id="settings_ip"></span></font></label><br />
				<label><?php echo $common_filename;?> : <font color="red"><span id="settings_filename"></span></font></label><br />
				<label><?php echo $common_content;?> : </label><pre id="settings_content"></pre><br />
				<br />
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
			</div>
		</div>
	</div>
</div>
<!-- view settings area end-->