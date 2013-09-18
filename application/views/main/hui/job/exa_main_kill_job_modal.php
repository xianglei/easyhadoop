<div class="modal fade" id="kill_job_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/job/killjob/">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">Kill Job</h3>
	</div>
	<div class="modal-body">

		<p>
		<input type="hidden" name="job_kill_job_id" value="" id="job_kill_job_id">
		</p>
		
		<pre id="kill_job_status">
		<div id="kill_job_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
		</pre>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_save;?></button>
	</div>
</div>
</div>
</form>
</div>