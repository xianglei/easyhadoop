<!--delete settings area-->
<div id="remove_hadoop_settings" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<form action="<?php echo $this->config->base_url();?>index.php/settings/removesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_remove_settings?></h3>
	</div>
	<div class="modal-body">
		<label><?php echo $common_ip;?> : <font color="red"><span id="remove_ip"></span></font></label><br />
		<label><?php echo $common_filename;?> : <font color="red"><span id="remove_filename"></span></font></label><br />
		<label><?php echo $common_content;?> : </label><pre id="remove_content"></pre><br />
		<input type="hidden" name="set_id" value="" id="remove_set_id" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button type="submit" class="btn btn-danger"><?php echo $common_remove;?></button>
	</div>
</form>
</div>
</div>
</div>
<!--delete settings area end-->