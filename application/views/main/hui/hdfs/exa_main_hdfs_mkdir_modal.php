<div class="modal fade" id="hdfs_mkdir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/hdfs/mkdir/">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $common_hdfs_mkdir?></h4>
      </div>
      <div class="modal-body">
        <p>
		<input type="text" name="mkdir_dir_name" placeholder="<?php echo $common_hdfs_input_dir_name;?>" class="form-control" />
		<input type="hidden" name="mkdir_sub_dir" value="" id="mkdir_sub_dir">
		</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $common_close;?></button>
        <button type="submit" class="btn btn-primary"><?php echo $common_save;?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</form>
</div><!-- /.modal -->
