<div id="add_general_settings" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>settings/addsettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">添加配置</h3>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_global_setting_tips;?>
		</pre>
		<label>文件名：</label><input type="text" placeholder="文件名称" name="filename" /><br />
		<label>内容：</label><textarea name=content></textarea><br />
		<input type="hidden" name="ip" value="0" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>