<div class=span10>

<script>
function install_hadoop_action(host_id)
{
	push_install_files_action(host_id);
}

function push_install_files_action(host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/install/pushfiles/' + host_id,{}, function(data){
		var html = data;
		$('#install_name_'+host_id).html('<?php echo $common_push_hadoop_files;?>');
		$('#install_progress_'+host_id).attr("style", "width: 5%;");
		$('#install_hadoop_action_status_' + host_id).html(html);
		//$('#push_install_files_status_' + host_id).html(json);
		install_environment(host_id);
	});
}

function install_environment(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/environment/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_' + host_id).html('<?php echo $common_install_environment;?>');
			$('#install_progress_' + host_id).attr("style", "width: 10%;");
			$('#install_hadoop_action_status_' + host_id).html(data);
			install_lzorpm(host_id);
		}
	});
}

function install_lzorpm(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/lzorpm/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_'+host_id).html('<?php echo $common_install_lzo;?>');
			$('#install_progress_'+host_id).attr("style", "width: 25%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_lzo(host_id);
		}
	});
}

function install_lzo(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/lzo/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_'+host_id).html('<?php echo $common_install_lzo;?>');
			$('#install_progress_'+host_id).attr("style", "width: 40%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_lzop(host_id);
		}
	});
}

function install_lzop(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/lzop/' +host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_'+host_id).html('<?php echo $common_install_lzop;?>');
			$('#install_progress_'+host_id).attr("style", "width: 60%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_jdk(host_id);
		}
	});
}

function install_jdk(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/jdk/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_'+host_id).html('<?php echo $common_install_java;?>');
			$('#install_progress_'+host_id).attr("style", "width: 70%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_hadoop(host_id);
		}
	});
}

function install_hadoop(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/hadoop/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_' +host_id).html('<?php echo $common_install_hadoop?>');
			$('#install_progress_'+host_id).attr("style", "width: 85%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_gpl(host_id)
		}
	});
}

function install_gpl(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/gpl/'+host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_'+host_id).html('<?php echo $common_install_hadoopgpl;?>');
			$('#install_progress_'+host_id).attr("style", "width: 100%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_done(host_id);
		}
	});
}

function install_done(host_id)
{
	$('#install_hadoop_action_status_'+host_id).html("<?php echo $common_install_complete;?>");
}
</script>
	<!--<div class="alert alert-error"><?php //echo $common_add_node_tips?></div>-->
	<table class="table table-striped table_hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $common_hostname;?></th>
				<th><?php echo $common_ip_addr;?></th>
				<th><?php echo $common_node_role;?></th>
				<th><?php echo $common_create_time;?></th>
				<th><?php echo $common_action;?></th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1;foreach($results as $item):?>
			<tr>
				<td><?php echo $i?></td>
				<td><?php echo $item->hostname;?></td>
				<td><?php echo $item->ip;?></td>
				<td><?php
				if (preg_match("/namenode/i",$item->role) || preg_match("/secondarynamenode/i",$item->role) || preg_match("/jobtracker/i",$item->role)):
					echo $item->role;
				else:
					echo $item->role;
				endif;
				?>
				</td>
				<td><?php echo $item->create_time;?></td>
				<td>
				<div class="btn-group">
					<a class="btn btn-inverse" href="#install_hadoop_action_<?php echo $item->host_id;?>" data-toggle="modal"><i class="icon-cog icon-white"></i>  <?php echo $common_install_hadoop;?></a>
				</div>
				

<div id="install_hadoop_action_<?php echo $item->host_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="install_name_<?php echo $item->host_id;?>"><?php echo $common_install_hadoop;?> <?php echo $item->ip;?></h3>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_submit_to_install;?></pre>
			<div class="progress progress-info">
				<div class="bar" style=""  id="install_progress_<?php echo $item->host_id;?>"></div>
			</div>
			
			<div id="install_hadoop_action_status_<?php echo $item->host_id;?>"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<button class="btn btn-primary" onclick="install_hadoop_action(<?php echo $item->host_id;?>)"><?php echo $common_submit;?></button>
	</div>
</div>
				</td>
			</tr>
		<?php $i++; endforeach;?>
		</tbody>
	</table>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>
