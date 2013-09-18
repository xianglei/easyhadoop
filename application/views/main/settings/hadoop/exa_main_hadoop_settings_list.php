<div class="col-md-10">
<script>
function push_node_settings()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushnodesettings/', function(html){
		$('#push_node_settings_status').empty();
		$('#push_node_settings_status').append(html);
	});
}
function push_etc_hosts()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushetchosts/', function(html){

		$('#push_etc_hosts_status').empty();
		$('#push_etc_hosts_status').append(html);
	});
}
function push_rackaware()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushrackaware/', function(html){
		$('#push_rackaware_status').empty();
		$('#push_rackaware_status').append(html);
	});
}
function push_global_settings()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushglobalsettings/', function(html){
		$('#push_global_settings_status').empty();
		$('#push_global_settings_status').append(html);
		});
}

function view_settings(set_id)
{
	$('#view_hadoop_settings').modal('toggle');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/gethadoopsettings/' + set_id, function (json){
		$('#settings_filename').html(json.filename);
		$('#settings_content').html(json.content);
		$('#settings_ip').html(json.ip);
	});
}

function remove_settings(set_id)
{
	$('#remove_hadoop_settings').modal('toggle');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/gethadoopsettings/' + set_id, function (json){
		$('#remove_filename').html(json.filename);
		$('#remove_content').html(json.content);
		$('#remove_ip').html(json.ip);
		$('#remove_set_id').val(set_id);
	});
}

function edit_settings(set_id)
{
	$('#edit_hadoop_settings').modal('toggle');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/gethadoopsettings/' + set_id, function (json){
		$('#edit_ip').html(json.ip);
		$('#edit_settings_filename').html(json.filename);
		var content = json.content;
		rcontent = content.replace(/&lt;/g, '<');
		rcontent = rcontent.replace(/&gt;/g, '>');
		$('#edit_settings_content').val(rcontent);
		$('#edit_set_id').val(set_id);
		$('#edit_form_ip').val(json.ip);
		$('#edit_form_filename').val(json.filename);
		$('#edit_form_content').val(rcontent);
	});
}
</script>
<ul id="settings_tab" class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab" data-placement="top" rel="tooltip" title="<?php //echo $common_gs_comment;?>"><?php echo $common_global_settings;?></a></li>
		<li><a href="#node" data-toggle="tab" data-placement="top" rel="tooltip" title="<?php //echo $common_ns_comment;?>"><?php echo $common_node_settings;?></a></li>
	</ul>
	<div id="settings_tab_content" class="tab-content">
		<!--独立配置Tab-->
		<div class="tab-pane" id="node">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					<?php echo $common_node_settings;?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#push_node_settings" data-toggle="modal"><?php echo $common_push_node_settings;?></a></li>
					</ul>
				</div>-
			</div>
			<br />
			<table class="table table-striped table_hover">
				<thead>
					<th>#</th>
					<th><?php echo $common_ip;?></th>
					<th><?php echo $common_filename;?></th>
					<th><?php echo $common_update_time;?></th>
					<th><?php echo $common_action;?></th>
				</thead>
				<tbody>
					<?php $i = 1; foreach($result_node as $node):?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $node->ip;?></td>
							<td><?php echo $this->config->item('hadoop_conf_folder') . $node->filename;?></td>
							<td><?php echo $node->create_time;?></td>
							<td>
								<div class="btn-toolbar">
									<div class="btn-group">
										<a href="javascript:edit_settings('<?php echo $node->id;?>')" class="btn btn-info btn-sm">Edit</a>
										<a href="javascript:view_settings('<?php echo $node->id;?>')" class="btn btn-info btn-sm"><?php echo $common_view;?></a>
										<a href="javascript:remove_settings('<?php echo $node->id;?>')" class="btn btn-danger btn-sm"><?php echo $common_remove;?></a>
									</div>
								</div>
							</td>
						</tr>
					<?php $i++; endforeach;?>
				</tbody>
			</table>
			<h3><?php echo $pagination;?></h3>
		</div>
		
		<!--全局配置Tab-->
		
		<div class="tab-pane fade in active" id="general">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					<?php echo $common_global_settings;?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#push_global_settings" data-toggle="modal"><?php echo $common_push_global_settings;?></a></li>
					</ul>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					/etc/hosts
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#view_etc_hosts" data-toggle="modal"><?php echo $common_view_hosts;?></a></li>
						<li class="divider"></li>
						<li><a href="#push_etc_hosts" data-toggle="modal"><?php echo $common_push_hosts;?></a></li>
					</ul>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					<?php echo $common_rack_aware;?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#view_rackaware" data-toggle="modal"><?php echo $common_view_rackaware;?></a></li>
						<li class="divider"></li>
						<li><a href="#push_rackaware" data-toggle="modal"><?php echo $common_push_rackaware;?></a></li>
					</ul>
				</div>
			</div>
			<br />
			<table class="table table-striped table_hover">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $common_filename;?></th>
						<th><?php echo $common_update_time;?></th>
						<th><?php echo $common_action;?></th>
					</tr>
				</thead>
				<tbody>
				<?php $i = 1;foreach($result_general as $global):?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $this->config->item('hadoop_conf_folder') . $global->filename;?></td>
						<td><?php echo $global->create_time;?></td>
						<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<!--<a href="#view_global_settings_<?php echo $global->id;?>" data-toggle="modal" class="btn btn-info btn-sm"><?php echo $common_view;?></a>-->
								<a href="javascript:edit_settings('<?php echo $global->id;?>')" class="btn btn-info btn-sm">Edit</a>
								<a href="javascript:view_settings('<?php echo $global->id;?>')" class="btn btn-info btn-sm"><?php echo $common_view;?></a>
								<a href="javascript:remove_settings('<?php echo $global->id;?>')" class="btn btn-danger btn-sm"><?php echo $common_remove;?></a>
							</div>
							
						</div>
						</td>
					</tr>
				<?php $i++; endforeach;?>
				</tbody>
			</table>
			
		</div>
		
	</div>

	

	
</div>