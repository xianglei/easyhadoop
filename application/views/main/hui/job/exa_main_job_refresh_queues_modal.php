<div class="modal fade" id="job_refresh_queues" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Refresh Queues</h4>
      </div>
      <div class="modal-body">
        <pre id="job_refresh_queues_content">
		<div id="job_refresh_queues_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
		</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $common_close;?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->