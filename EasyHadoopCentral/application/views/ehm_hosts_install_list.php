<div class=span10>

<script>
function push_install_files_action(host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/install/pushfiles/' + host_id,{}, function(data){
		var html = data;
		$('#push_install_files_status_' + host_id).html(html);
	});
}

function install_hadoop_action(host_id)
{
	install_environment(host_id);
}

function install_environment(host_id)
{
	$.ajax({
		url: '<?php echo $this->config->base_url();?>index.php/install/environment/' + host_id,
		async: true,
		success: function(data)
		{
			$('#install_name_' + host_id).html('安装hadoop所需环境');
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
			$('#install_name_'+host_id).html('安装Lzo依赖');
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
			$('#install_name_'+host_id).html('安装lzo依赖');
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
			$('#install_name_'+host_id).html('安装lzop');
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
			$('#install_name_'+host_id).html('安装Sun JDK');
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
			$('#install_name_' +host_id).html('安装Hadoop');
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
			$('#install_name_'+host_id).html('安装LZO解码器');
			$('#install_progress_'+host_id).attr("style", "width: 100%;");
			$('#install_hadoop_action_status_'+host_id).empty();
			$('#install_hadoop_action_status_'+host_id).html(data);
			install_done(host_id);
		}
	});
}

function install_done(host_id)
{
	$('#install_hadoop_action_status_'+host_id).html("感谢您的耐心等待，本节点安装完毕。");
}
</script>
	<!--<div class="alert alert-error"><?php echo $common_add_node_tips?></div>-->
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
					<a class="btn" href="#push_install_files_<?php echo $item->host_id;?>" data-toggle="modal"><i class="icon-hand-right"></i> 推送安装文件 </a>
					<a class="btn btn-inverse" href="#install_hadoop_action_<?php echo $item->host_id;?>" data-toggle="modal"><i class="icon-cog icon-white"></i>  安装 Hadoop</a>
				</div>
				
				<!--push file entry-->
				
<div id="push_install_files_<?php echo $item->host_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">推送安装文件 <?php echo $item->ip;?></h3>
	</div>
	<div class="modal-body">

			<div id="push_install_files_status_<?php echo $item->host_id;?>"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button class="btn btn-primary" onclick="push_install_files_action(<?php echo $item->host_id;?>)"><?php echo $common_submit;?></button>
	</div>
</div>
				<!--end push file entry-->
				
				<!--push file entry-->
				
<div id="install_hadoop_action_<?php echo $item->host_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="install_name_<?php echo $item->host_id;?>">安装 Hadoop <?php echo $item->ip;?></h3>
	</div>
	<div class="modal-body">
		<pre>  点击提交开始安装，请耐心等待</pre>
			<div class="progress progress-info">
				<div class="bar" style=""  id="install_progress_<?php echo $item->host_id;?>"></div>
			</div>

			<div id="install_hadoop_action_status_<?php echo $item->host_id;?>"></div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button class="btn btn-primary" onclick="install_hadoop_action(<?php echo $item->host_id;?>)"><?php echo $common_submit;?></button>
	</div>
</div>
				<!--end push file entry-->
<script>



</script>
				
				
				
				
				</td>
			</tr>
		<?php $i++; endforeach;?>
		</tbody>
	</table>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>
