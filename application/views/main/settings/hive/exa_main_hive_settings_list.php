<div class="col-md-10">
<script>
function push_hive_settings()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushhivesettings/', function(html){
		$('#push_hive_settings_status').empty();
		$('#push_hive_settings_status').append(html);
	});
}

function remove_hive_settings(set_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/gethivesettings/' + set_id, function(json) {
		$('#remove_ip').html(json.ip);
		$('#remove_filename').html(json.filename);
		$('#remove_content').html(json.content);
		$('#remove_set_id').val(set_id);
		$('#remove_hive_settings').modal('toggle');
	});
}

function view_hive_settings(set_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/gethivesettings/' + set_id, function (json) {
		$('#view_ip').html(json.ip);
		$('#view_filename').html(json.filename);
		$('#view_content').html(json.content);
		$('#view_hive_settings').modal('toggle');
	});
}
</script>
	<ul class="nav nav-tabs" id="hive_settings">
		<li class="active"><a href="#hive"><?php echo $common_hive_settings;?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="hive">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					<?php echo $common_hive_settings;?>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#push_hive_settings" data-toggle="modal"><?php echo $common_push_hive_settings;?></a></li>
					</ul>
				</div>
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
					<?php $i = 1; foreach($result as $hive):?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $hive->ip;?></td>
							<td><?php echo $this->config->item('hive_conf_folder') . $hive->filename;?></td>
							<td><?php echo $hive->create_time;?></td>
							<td>
								<div class="btn-toolbar">
									<div class="btn-group">
										<a href="javascript:view_hive_settings('<?php echo $hive->id;?>')" class="btn btn-info btn-sm"><?php echo $common_view;?></a>
										<a href="javascript:remove_hive_settings('<?php echo $hive->id;?>')" class="btn btn-danger btn-sm"><?php echo $common_remove;?></a>
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