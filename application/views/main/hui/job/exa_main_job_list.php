<script>
function list_job()
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/listjob/', function(json){
		$("#loaddiv").hide();
		var html = '';
		html += '<table class="table table-bordered">';
		html += '<tr>';
		html += '<td>JobId';
		html += '</td>';
		html += '<td>State';
		html += '</td>';
		html += '<td>StartTime';
		html += '</td>';
		html += '<td>UserName';
		html += '</td>';
		html += '<td>Priority';
		html += '</td>';
		html += '<td>SchedulingInfo';
		html += '</td>';
		html += '<td>Action';
		html += '</td>';
		html += '</tr>';
		for (var i=0; i<json.length; i++)
		{
			html += '<tr>';
			html += '<td>'+ json[i].job_id +'</td>';
			html += '<td>'+ json[i].state +'</td>';
			html += '<td>'+ json[i].start_time_datetime +'</td>';
			html += '<td>'+ json[i].username +'</td>';
			html += '<td>'+ json[i].priority +'</td>';
			html += '<td>'+ json[i].scheduling_info +'</td>';
			html += '<td><a href="javascript:kill_job(\''+ json[i].job_id +'\')" class="btn btn-danger btn-xs">Kill</a></td>';
			html += '</tr>';
		}
		html += '</table>';
		$('#job_list_table').html(html);
	});
	
	
}

list_job();

function kill_job(job_id)
{
	$('#job_kill_job_id').val(job_id);
	$('#kill_job_modal').modal('toggle');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/jobstatus/' + job_id, function (json){
		$("#kill_job_loaddiv").hide();
		var content = json.content;
		$('#kill_job_status').html(content);
	});
}

function safemodestats()
{
	$('#job_safemode_get').modal('toggle');
	$('#job_safemode_get_content').empty();
	$('#job_safemode_get_content').html('<div id="job_safemode_get_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/safemodeget/', function(json){
		var content = json.content;
		$('#job_safemode_get_loaddiv').hide();
		$('#job_safemode_get_content').html(content);
	});
}

function safemodeenter()
{
	$('#job_safemode_enter').modal('toggle');
	$('#job_safemode_enter_content').empty();
	$('#job_safemode_enter_content').html('<div id="job_safemode_enter_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/safemodeenter/', function(json){
		var content = json.content;
		$('#job_safemode_enter_loaddiv').hide();
		$('#job_safemode_enter_content').html(content);
	});
}

function safemodeleave()
{
	$('#job_safemode_leave').modal('toggle');
	$('#job_safemode_leave_content').empty();
	$('#job_safemode_leave_content').html('<div id="job_safemode_leave_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/safemodeleave/', function(json){
		var content = json.content;
		$('#job_safemode_leave_loaddiv').hide();
		$('#job_safemode_leave_content').html(content);
	});
}

function refreshnodes()
{
	$('#job_refresh_nodes').modal('toggle');
	$('#job_refresh_nodes_content').empty();
	$('#job_refresh_nodes_content').html('<div id="job_refresh_nodes_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/refreshnodes/', function(json){
		var content = json.content;
		$('#job_refresh_nodes_loaddiv').hide();
		$('#job_refresh_nodes_content').html(content);
	});
}

function refreshqueues()
{
	$('#job_refresh_queues').modal('toggle');
	$('#job_refresh_queues_content').empty();
	$('#job_refresh_queues_content').html('<div id="job_refresh_queues_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/job/refreshqueues/', function(json){
		var content = json.content;
		$('#job_refresh_queues_loaddiv').hide();
		$('#job_refresh_queues_content').html(content);
	});
}

</script>

<div class="col-md-12">
	<div class="btn-group">
		<a href="http://<?php echo $jt_ip;?>:50030/" target="_blank" class="btn btn-default">Jobtracker Web</a>
		<a href="javascript:refreshnodes();" class="btn btn-default">RefreshNodes</a>
		<a href="javascript:refreshqueues();" class="btn btn-default">RefreshQueues</a>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<?php echo $common_hdfs_safemode;?>
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="javascript:safemodestats();"><?php echo $common_hdfs_safemode_status;?></a></li>
				<li><a href="javascript:safemodeleave();"><?php echo $common_hdfs_safemode_leave;?></a></li>
				<li><a href="javascript:safemodeenter();"><?php echo $common_hdfs_safemode_enter;?></a></li>
			</ul>
		</div>
	</div>
	<div id="loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
	<div id="job_list_table"></div>
</div>