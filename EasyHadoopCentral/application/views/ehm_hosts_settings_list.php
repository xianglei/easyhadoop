<div class="span10">
<script>
function push_node_settings_status(set_id)
{
	var set_id = set_id;
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushnodesettings/'+set_id, function(html){
		$('#push_node_settings_status').append(html + '<br />');
	});
}
function push_etc_hosts_status(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/pushetchost/' + host_id, function(json){
		var node = json.node;
		var status = json.status;
		var filename = json.filename;
		var html = json.node + ' : ' + json.filename + ' -> ' + json.status + ' <br />';

		$('#push_etc_hosts_status').append(html);
	});
}
function push_rackaware_status()
{
	$.get('<?php echo $this->config->base_url();?>index.php/settings/pushrackaware/', function(html){
		$('#push_rackaware_status').empty();
		$('#push_rackaware_status').append(html);
	});
}
function push_general_settings_status(host_id, set_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/pushgeneralsettings/'+host_id+'/'+set_id+'/', function(json){
		var node = json.node;
		var status = json.status;
		var filename = json.filename;
		var html = json.node + ' : ' + json.filename + ' -> ' + json.status + ' <br />';

		$('#push_general_settings_status').append(html);
		});
}
</script>
<ul id="settings_tab" class="nav nav-tabs">
		<li><a href="#general" data-toggle="tab" data-placement="top" rel="tooltip" title="为全部节点所使用">全局配置</a></li>
		<li class="active" ><a href="#node" data-toggle="tab" data-placement="top" rel="tooltip" title="为单独节点所使用">节点配置</a></li>
	</ul>
	<div id="settings_tab_content" class="tab-content">
		<!--独立配置Tab-->
		<div class="tab-pane fade in active" id="node">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					节点配置管理
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#add_node_settings" data-toggle="modal">添加节点配置</a></li>
						<li class="divider"></li>
						<li><a href="#push_node_settings" data-toggle="modal">推送节点配置</a></li>
					</ul>
				</div>-
			</div>
			<br />
			<table class="table table-striped table_hover">
				<thead>
					<th>#</th>
					<th>IP</th>
					<th>Filename</th>
					<th>编辑时间</th>
					<th>操作</th>
				</thead>
				<tbody>
					<?php $i = 1; foreach($result_node as $node):?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $node->ip;?></td>
							<td><?php echo $node->filename;?></td>
							<td><?php echo $node->create_time;?></td>
							<td>
								<div class="btn-toolbar">
									<div class="btn-group">
										<a href="#edit_node_settings_<?php echo $node->set_id;?>" data-toggle="modal" class="btn">编辑</a>
										<a href="#delete_node_settings_<?php echo $node->set_id;?>" data-toggle="modal" class="btn btn-danger">删除</a>
									</div>
								</div>
								<!--edit node settings area-->
							<div id="edit_node_settings_<?php echo $node->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/updatenodesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 id="myModalLabel">修改配置 > <?php echo $node->ip;?> > <?php echo $node->filename;?></h4>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_node_setting_tips;?>
		</pre>
		<label>文件名：</label><input type="text" placeholder="文件名称" name="filename" value="<?php echo $node->filename;?>" /><br />
		<label>内容：</label><textarea name=content><?php echo $node->content;?></textarea><br />
		<label>IP：</label>
		<select name="ip">
		<?php foreach($all_hosts as $host):?>
			<option value="<?php echo $host->ip;?>" <?php echo ($node->ip == $host->ip) ? "selected" : ""; ?>><?php echo $host->ip;?> -> <?php echo $host->hostname;?> </option>
		<?php endforeach;?>
		</select>
		<input type="hidden" name="set_id" value="<?php echo $node->set_id;?>" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
						</div>
						<!-- edit node settings area end-->
						<!--delete node settings area-->
						<div id="delete_node_settings_<?php echo $node->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/deletesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">删除配置</h3>
	</div>
	<div class="modal-body">
		<label>文件名：</label><?php echo $node->filename;?><br />
		<label>内容：</label><textarea name=content disabled><?php echo $node->content;?></textarea><br />
		<input type="hidden" name="set_id" value="<?php echo $node->set_id;?>" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-danger"><?php echo $common_submit;?></button>
	</div>
</form>
						</div>
						<!--delete node settings area end-->
							
							</td>
						</tr>
					<?php $i++; endforeach;?>
				</tbody>
			</table>
			<h3><?php echo $pagination;?></h3>
		</div>
		
		<!--全局配置Tab-->
		
		<div class="tab-pane fade in" id="general">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					全局配置管理
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#add_general_settings" data-toggle="modal">添加全局配置</a></li>
						<li class="divider"></li>
						<li><a href="#push_general_settings" data-toggle="modal">推送全局配置</a></li>
					</ul>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					/etc/hosts
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#view_etc_hosts" data-toggle="modal">查看hosts</a></li>
						<li class="divider"></li>
						<li><a href="#push_etc_hosts" data-toggle="modal">推送hosts</a></li>
					</ul>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
					机架感知
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#view_rackaware" data-toggle="modal">查看机架感知</a></li>
						<li class="divider"></li>
						<li><a href="#push_rackaware" data-toggle="modal">推送机架感知</a></li>
					</ul>
				</div>
			</div>
			<br />
			<table class="table table-striped table_hover">
				<thead>
					<tr>
						<th>#</th>
						<th>文件名称</th>
						<th>编辑时间</th>
						<th><?php echo $common_action;?></th>
					</tr>
				</thead>
				<tbody>
				<?php $i = 1;foreach($result_general as $item):?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $item->filename;?></td>
						<td><?php echo $item->create_time;?></td>
						<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="#edit_general_settings_<?php echo $item->set_id;?>" data-toggle="modal" class="btn">编辑</a>
								<a href="#delete_general_settings_<?php echo $item->set_id;?>" data-toggle="modal" class="btn btn-danger">删除</a>
							</div>
							
						</div>
						<script>
						<?php foreach($all_hosts as $v):?>
						
						<?php endforeach;?>
						</script>
							<!--edit general settings area-->
							<div id="edit_general_settings_<?php echo $item->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/updategeneralsettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">修改配置</h3>
	</div>
	<div class="modal-body">
		<pre>  <?php echo $common_global_setting_tips;?>
		</pre>
		<label>文件名：</label><input type="text" placeholder="文件名称" name="filename" value="<?php echo $item->filename;?>" /><br />
		<label>内容：</label><textarea name=content><?php echo $item->content;?></textarea><br />
		<input type="hidden" name="ip" value="0" />
		<input type="hidden" name="set_id" value="<?php echo $item->set_id;?>" />
		<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
						</div>
						<!-- edit general settings area end-->
						
						<!--delete general settings area-->
						<div id="delete_general_settings_<?php echo $item->set_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/settings/deletesettings/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">删除配置</h3>
	</div>
	<div class="modal-body">
		<label>文件名：</label><?php echo $item->filename;?><br />
		<label>内容：</label><textarea name=content disabled><?php echo $item->content;?></textarea><br />
		<input type="hidden" name="set_id" value="<?php echo $item->set_id;?>" />
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
			</table>
			
		</div>
		
	</div>

	

	
</div>