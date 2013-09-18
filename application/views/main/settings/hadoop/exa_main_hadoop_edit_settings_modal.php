<!--view settings area-->
<div id="edit_hadoop_settings" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/settings/saveeditsettings/">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Edit</h3>
			</div>
			<div class="modal-body">
				<label><?php echo $common_ip;?> : <font color="red"><span id="edit_ip"></span></font></label><br />
				<label><?php echo $common_filename;?> : <font color="red"><span id="edit_settings_filename"></span></font></label><br />
				<label><?php echo $common_content;?> : </label><textarea id="edit_settings_content" class="form-control" rows="20" name="content"></textarea><br />
				<input type="hidden" name="set_id" value="" id="edit_set_id" />
				<input type="hidden" name="ip" value="" id="edit_form_ip" />
				<input type="hidden" name="filename" value="" id="edit_form_filename" />
				<br />
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
				<button type="submit" class="btn btn-danger"><?php echo $common_save;?></button>
			</div>
		</div>
	</div>
	</form>
</div>
<!-- view settings area end-->