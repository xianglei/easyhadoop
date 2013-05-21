<div id="format_namenode" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">
			<font color="red"><b>Danger Zone</b></font>
		</h3>
	</div>
	<div class="modal-body">
		<p>
			<div id="format_status">Are you sure?</div>
		</p>
	</div>
	<div class="modal-footer">
		 <button class="btn" data-dismiss="modal" aria-hidden="true">No</button> <button class="btn btn-primary" onclick="javascript:format_namenode_status();">Sure</button>
	</div>
</div>
<script>
function format_namenode_status()
{
	$.get('<?php echo $this->config->base_url();?>index.php/operate/formatnamenode/', {}, function(data){
		var html = data;
		$('#format_status').html(html);
	});
}
</script>