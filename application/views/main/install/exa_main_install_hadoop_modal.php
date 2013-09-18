<script>
function install_hadoop_action()
{
	push_install_files_action();
}

function push_install_files_action()
{
	var ip = $('#node_ip').val();
	$.get('<?php echo $this->config->base_url();?>index.php/install/pushhadoopbin/' + ip,{}, function(data){
		var html = data;
		$('#install_name').html('<?php echo $common_install_push_hadoop_files;?>');
		$('#install_progress').attr("style", "width: 30%;");
		$('#install_hadoop_log').html(html);
		//$('#push_install_files_status_' + host_id).html(json);
		install_bin();
	});
}
function install_bin()
{
	var node_id = $('#node_id').val();
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/installhadoop/'+node_id,
		async: true,
		success: function(data)
		{
			$('#install_name').html('<?php echo $common_install_hadoop;?>');
			$('#install_progress').attr("style", "width: 100%;");
			$('#install_hadoop_log').empty();
			$('#install_hadoop_log').html(data);
			install_done();
		}
	});
}

function install_done()
{
	$('#install_hadoop_log').append("<?php echo $common_install_hadoop_complete;?>");
	$('#install_button').attr("disabled", 'disabled');
}
</script>

<div id="install_hadoop_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo $common_install_hadoop;?></h4>
	</div>
	<div class="modal-body">
			<label><?php echo $common_hostname;?> : <span id="inst_hostname"></span></label><br />
			<label><?php echo $common_ip;?> : <span id="inst_ip"></span></label><br />
			<input type="hidden" name="node_id" id="node_id" value="" />
			<input type="hidden" name="ip" id="node_ip" value="" />
			<input type="hidden" name="is_sudo" id="is_sudo" value="" />
			<input type="hidden" name="ssh_pass" id="ssh_pass" value="" />
			
			<div class="progress progress-info progress-striped active">
				<div class="progress-bar" id="install_progress" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style=""></div>
			</div>
			
			<div id="install_hadoop_log"></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button type="button" id="install_button" class="btn btn-primary" onclick="install_hadoop_action()"><?php echo $common_install;?></button>
	</div>
	</div>
</div>
</div>