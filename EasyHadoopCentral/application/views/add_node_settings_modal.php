<div id="add_node_settings" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/addsettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_add_settings;?></h3>
	</div>
	<div class="modal-body">
		<pre class="alert alert-danger">  <?php echo $common_node_setting_tips;?></pre>
		<label><?php echo $common_filename;?></label><input type="text" placeholder="<?php echo $common_filename;?>" name="filename" /><br />
		<label><?php echo $common_content;?></label><textarea name=content></textarea><br />
		<label>IP: </label><select name="ip">
		<?php foreach($all_hosts as $host):?>
			<option value="<?php echo $host->ip;?>"><?php echo $host->ip;?> -> <?php echo $host->hostname;?> </option>
		<?php endforeach;?>
		</select>
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>