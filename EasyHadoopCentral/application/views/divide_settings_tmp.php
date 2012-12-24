<div class="tab-pane fade" id="divide">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#add_divide_settings" data-toggle="modal" class="btn btn-primary">添加配置</a>
				</div>
			</div>
			
			<table class="table table-striped table_hover">
				<thead>
					<tr>
						<th>#</th>
						<th>文件名称</th>
						<th>编辑时间</th>
						<th>IP</th>
						<th><?php echo $common_action;?></th>
					</tr>
				</thead>
				<tbody>
				<?php $i = 1;foreach($result_divide as $item):?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $item->filename;?></td>
						<td><?php echo $item->create_time;?></td>
						<td><?php echo $item->ip;?></td>
						<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="#edit_divide_settings_<?php echo $item->set_id;?>" data-toggle="modal" class="btn">编辑</a>
								<a href="#delete_divide_settings_<?php echo $item->set_id;?>" data-toggle="modal" class="btn btn-danger">删除</a>
							</div>
							<div class="btn-group">
								<a href="#push_divide_settings_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>" data-toggle="modal" class="btn btn-danger" disabled>推送配置</a>
							</div>
						</div>
						
						<!--edit divide settings area-->
							<div id="edit_divide_settings_<?php echo $item->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/updatedividesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">修改配置</h3>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_node_setting_tips;?></pre>
		<label>文件名：</label><input type="text" placeholder="文件名称" name="filename" value="<?php echo $item->filename;?>" /><br />
		<label>内容：</label><textarea name=content><?php echo $item->content;?></textarea><br />
		<label>IP：</label>
		<select name="ip">
		<?php foreach($all_hosts as $host):?>
			<option value="<?php echo $host->ip;?>" <?php echo ($item->ip == $host->ip) ? "selected" : ""; ?>><?php echo $host->ip;?> -> <?php echo $host->hostname;?> </option>
		<?php endforeach;?>
		</select>
		<input type="hidden" name="set_id" value="<?php echo $item->set_id;?>" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
						</div>
						<!-- edit divide settings area end-->
						
						<!--delete general settings area-->
						<div id="delete_divide_settings_<?php echo $item->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/deletesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">删除配置</h3>
	</div>
	<div class="modal-body">
		<label>文件名：</label><?php echo $item->filename;?><br />
		<label>内容：</label><textarea name=content disabled><?php echo $item->content;?></textarea><br />
		<input type="hidden" name="set_id" value="<?php echo $item->set_id;?>" /><br />
		<label>IP：</label><?php echo $item->ip;?>
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-danger"><?php echo $common_submit;?></button>
	</div>
</form>
						</div>
						<!--delete general settings area end-->
						
						
						
						</td>
					</tr>
				<?php $i++; endforeach;?>
				</tbody>
			</thead>
			</table>
		</div>

<script>
<?php foreach($hosts_divide as $v):?>
function push_divide_settings_status_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>()
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/pushdividesettings/<?php echo $v->host_id;?>/<?php echo $item->set_id;?>/', function(json){
		var node = json.node;
		var status = json.status;
		var filename = json.filename;
		var html = json.node + ' : ' + json.filename + ' -> ' + json.status + ' <br />';

		$('#push_divide_settings_status_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>').append(html);
	});
}
<?php endforeach;?>
</script>
<?php foreach($hosts_divide as $v):?>
<div id="push_divide_settings_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">推送独立配置</h3>
	</div>
	<div class="modal-body">

			<div id="push_divide_settings_status_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button class="btn btn-danger" onclick="push_divide_settings_status_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>()"><?php echo $common_submit;?></button>
	</div>
</div>
<?php endforeach;?>