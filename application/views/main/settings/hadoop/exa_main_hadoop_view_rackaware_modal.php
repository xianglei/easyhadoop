<div id="view_rackaware" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">/etc/hadoop/RackAware.py</h3>
	</div>
	<div class="modal-body">
		<div id="rackaware_content"></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
	</div>
</div>
</div>
</div>
<script>
function rackaware_content()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/viewrackaware/', {}, function(html){
		html = html;
		$('#rackaware_content').html(html);
	});
}
rackaware_content();
</script>