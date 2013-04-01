<div id="add_general_settings" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/addsettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_add_settings;?></h3>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_global_setting_tips;?>
		</pre>
		<label><?php echo $common_filename;?></label><input type="text" placeholder="<?php echo $common_filename;?>" name="filename" /><br />
		<label><?php echo $common_content;?></label><textarea name=content></textarea><br />
		<input type="hidden" name="ip" value="0" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>