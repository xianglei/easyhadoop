<div class="modal fade" id="hdfs_rename" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/hdfs/rename/">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_hdfs_rename;?></h3>
	</div>
	<div class="modal-body">

		<p>
		<input type="text" name="rename_dir_name" value="" class="form-control" disabled id="rename_dir_name" /><br>
		<input type="text" name="rename_dest_dir_name" placeholder="<?php echo $common_hdfs_target_name;?>" class="form-control" id="rename_dest_dir_name" value="" />
		<input type="hidden" name="rename_src_dir_name" value="" id="rename_src_dir_name">
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