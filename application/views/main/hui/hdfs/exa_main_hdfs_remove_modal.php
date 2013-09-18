<div class="modal fade" id="hdfs_remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/hdfs/remove/">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_hdfs_remove?></h3>
	</div>
	<div class="modal-body">

		<p>
		<input type="text" name="remove_dir_name" value="" class="form-control" disabled id="remove_dir_name" /><br>

		<input type="hidden" name="remove_src_dir_name" value="" id="remove_src_dir_name">
		</p>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_save;?></button>
	</div>
</div>
</div>
</form>
</div>