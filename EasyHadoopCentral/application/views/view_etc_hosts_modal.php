<div id="view_etc_hosts" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">/etc/hosts</h3>
	</div>
	<div class="modal-body">
		<div id="hosts_content"></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
	</div>
</div>
<script>
function hosts_content()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/viewhosts/', {}, function(html){
		html = html;
		$('#hosts_content').html(html);
	});
}
hosts_content();
</script>