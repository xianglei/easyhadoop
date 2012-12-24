<div class="span10">

<ul id="job_tab" class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab" data-placement="top" rel="tooltip" title="为全部节点所使用">Hadoop配置</a></li>
		<!--<li><a href="#divide" data-toggle="tab" data-placement="top" rel="tooltip" title="为单独节点所使用">节点配置</a></li>-->
	</ul>
	<div id="job_tab_content" class="tab-content">
		<div class="tab-pane fade in active" id="general">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#add_general_settings" data-toggle="modal" class="btn btn-primary">添加配置</a>
					<a href="#view_etc_hosts" data-toggle="modal" class="btn btn-primary">查看hosts</a>
				</div>
				<div class="btn-group">
					<a href="#push_etc_hosts" data-toggle="modal" class="btn btn-danger">推送hosts</a>
				</div>
				<div class="btn-group">
					<a href="#push_general_settings" data-toggle="modal" class="btn btn-danger">推送配置</a>
				</div>
			</div>
			<script>
						<?php foreach($all_hosts as $v):?>
						function push_etc_hosts_status_<?php echo $v->host_id;?>()
						{
							$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/pushetchost/<?php echo $v->host_id;?>', function(json){
								var node = json.node;
								var status = json.status;
								var filename = json.filename;
								var html = json.node + ' : ' + json.filename + ' -> ' + json.status + ' <br />';

								$('#push_etc_hosts_status').append(html);
							});
						}
						<?php endforeach;?>
						</script>
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
						function push_general_settings_status_<?php echo $v->host_id;?>_<?php echo $item->set_id;?>()
						{
							$.getJSON('<?php echo $this->config->base_url();?>index.php/settings/pushgeneralsettings/<?php echo $v->host_id;?>/<?php echo $item->set_id;?>/', function(json){
								var node = json.node;
								var status = json.status;
								var filename = json.filename;
								var html = json.node + ' : ' + json.filename + ' -> ' + json.status + ' <br />';

								$('#push_general_settings_status').append(html);
							});
						}
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