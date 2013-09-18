<div class="modal fade" id="hdfs_safemode_get" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $common_hdfs_safemode_status;?></h4>
      </div>
      <div class="modal-body">
        <pre id="hdfs_safemode_get_content">
		<div id="hdfs_safemode_get_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
		</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $common_close;?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->