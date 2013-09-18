<div id="setup_hdd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<form action="<?php echo $this->config->base_url();?>index.php/nodes/setmountpoint/" method="POST">
	<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo $common_setup_storage;?></h4>
	</div>
	<div class="modal-body">
		<pre><?php echo $common_nodes_storage_tips;?></pre>
		<label><?php echo $common_hostname;?> : <span id="hdd_hostname"></span></label><br />
		<label><?php echo $common_ip;?> : <span id="hdd_ip"></span></label><br />
		<input type="hidden" name="node_id" id="hdd_host_id" value="" />
		<input type="hidden" name="ip" id="hdd_hidden_ip" value="" />
		<div id="hdd_status"></div>
		<div id="nodes_hdd_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_save;?></button>
	</div>
	</div>
</form>
</div>
</div>