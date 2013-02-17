<div id="add_node_settings" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/addsettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">添加配置</h3>
	</div>
	<div class="modal-body">
		<pre class="alert alert-danger">  <?php echo $common_node_setting_tips;?></pre>
		<label>文件名：</label><input type="text" placeholder="文件名称" name="filename" /><br />
		<label>内容：</label><textarea name=content></textarea><br />
		<label>IP：</label><select name="ip">
		<?php foreach($all_hosts as $host):?>
			<option value="<?php echo $host->ip;?>"><?php echo $host->ip;?> -> <?php echo $host->hostname;?> </option>
		<?php endforeach;?>
		</select>
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>