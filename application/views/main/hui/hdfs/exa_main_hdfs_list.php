

<script>
var ua = navigator.userAgent.toLowerCase();
if (ua.indexOf(" chrome/") >= 0 || ua.indexOf(" firefox/") >= 0 || ua.indexOf(' gecko/') >= 0) {
	var StringMaker = function () {
		this.str = "";
		this.length = 0;
		this.append = function (s) {
			this.str += s;
			this.length += s.length;
		}
		this.prepend = function (s) {
			this.str = s + this.str;
			this.length += s.length;
		}
		this.toString = function () {
			return this.str;
		}
	}
} else {
	var StringMaker = function () {
		this.parts = [];
		this.length = 0;
		this.append = function (s) {
			this.parts.push(s);
			this.length += s.length;
		}
		this.prepend = function (s) {
			this.parts.unshift(s);
			this.length += s.length;
		}
		this.toString = function () {
			return this.parts.join('');
		}
	}
}

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function base64encode(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	while (i < input.length) {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output.append(keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4));
   }
   
   return output.toString();
}

function base64decode(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

	while (i < input.length) {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output.append(String.fromCharCode(chr1));

		if (enc3 != 64) {
			output.append(String.fromCharCode(chr2));
		}
		if (enc4 != 64) {
			output.append(String.fromCharCode(chr3));
		}
	}

	return output.toString();
}


function ls(folder)
{
	$('#ls_current_dir').val(folder);//put current dir
	if(folder == '')
	{
		folder = '/'
	}
	folder = base64encode(folder);
	
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/ls/' + folder, function(json){
	    $("#loaddiv").hide();
		var html = '';
		
		html += '<table class="table table-bordered">';
		html += '<tr>';
			html += '<td>';
			html += '<?php echo $common_hdfs_priv;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_user;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_group?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_size;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_date;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_time;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_hdfs_filename;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_action;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_action;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_action;?>';
			html += '</td>';
			html += '<td>';
			html += '<?php echo $common_action;?>';
			html += '</td>';
		html += '</tr>';
		html += '<tr>';
			html += '<td>';
			html += '';
			html += '</td>';
			html += '<td>';
			html += ' ';
			html += '</td>';
			html += '<td>';
			html += ' ';
			html += '</td>';
			html += '<td>';
			html += ' ';
			html += '</td>';
			html += '<td>';
			html += ' ';
			html += '</td>';
			html += '<td>';
			html += ' ';
			html += '</td>';
			html += '<td>';
			current_dir = $('#ls_current_dir').val();//取当前路径
			if(current_dir == "/" || current_dir == "")
			{
				prev_folder = "/";
				current_dir = "/";
			}
			else
			{
				tmp = current_dir.split('/');//拆分路径
				tmp.shift();//弹出第一个 /
				tmp.pop();//弹出最后一个元素
				tmp = '/'+tmp.join('/');//拼上级路径
				prev_folder = tmp;
			}
			html += '<a href="javascript:ls(\''+ prev_folder +'\')"> ' + prev_folder + ' </a>';
			html += '</td>';
			$('#mkdir_sub_dir').val(current_dir);
			html += '<td><a href="#" data-target="#hdfs_mkdir" data-toggle="modal" class="btn btn-primary btn-xs"><?php echo $common_hdfs_mkdir;?></a></td>';
			html += '<td> </td>';
			html += '<td> </td>';
			html += '<td> </td>';
		html += '</tr>';
		for (var i = 0; i < json.length; i++){
			html += '<tr>';
			html += '<td>';
			html += json[i].priv;
			html += '</td>';
			html += '<td>';
			html += json[i].user;
			html += '</td>';
			html += '<td>';
			html += json[i].group;
			html += '</td>';
			html += '<td>';
			html += json[i].size;
			html += '</td>';
			html += '<td>';
			html += json[i].date;
			html += '</td>';
			html += '<td>';
			html += json[i].time;
			html += '</td>';
			html += '<td>';
			html += '<a href="javascript:ls(\''+ json[i].name +'\')">' + json[i].name + '</a>';
			html += '</td>';
			html += '<td><a href="javascript:rename(\''+ json[i].name +'\')" class="btn btn-default btn-xs"><?php echo $common_hdfs_rename;?></a></td>';
			html += '<td><a href="javascript:chmod(\''+ json[i].name +'\', \'' + json[i].priv + '\')" class="btn btn-warning btn-xs"><?php echo $common_hdfs_chmod;?></a></td>';
			html += '<td><a href="javascript:chown(\''+ json[i].name +'\', \'' + json[i].user + '\', \''+ json[i].group +'\')" class="btn btn-warning btn-xs"><?php echo $common_hdfs_chown;?></a></td>';
			html += '<td><a href="javascript:remove(\''+ json[i].name +'\')" class="btn btn-danger btn-xs"><?php echo $common_hdfs_remove;?></a></td>';
			html += '</tr>';
		}
		
		html += '</table>'
		$('#listHDFS').html(html);
	});
}
ls('/');

function rename(src_dir)
{
	$('#rename_dir_name').val(src_dir);
	$('#rename_src_dir_name').val(src_dir);
	$('#rename_dest_dir_name').val(src_dir);
	$('#hdfs_rename').modal('toggle');
}

function remove(dir_name)
{
	$('#remove_dir_name').val(dir_name);
	$('#remove_src_dir_name').val(dir_name);
	$('#hdfs_remove').modal('toggle');
}

function chmod(dir_name, priv)
{
	$('#chmod_dir_name').val(dir_name);
	$('#chmod_title_dirname').html(dir_name);
	$('#chmod_priv').val(priv);
	$('#hdfs_chmod').modal('toggle');
	setPriv();
}

function chown(dir_name, user, group)
{
	$('#chown_dir_name').val(dir_name);
	$('#chown_title_dirname').html(dir_name);
	$('#chown_user').val(user);
	$('#chown_group').val(group);
	$('#hdfs_chown').modal('toggle');
}

function report()
{
	$('#hdfs_report').modal('toggle');
	$('#hdfs_report_content').empty();
	$('#hdfs_report_content').html('<div id="hdfs_report_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/report/', function(json){
		var content = json.content;
		$('#hdfs_report_loaddiv').hide();
		$('#hdfs_report_content').html(content);
	});
}

function refresh()
{
	$('#hdfs_refresh').modal('toggle');
	$('#hdfs_refresh_content').empty();
	$('#hdfs_refresh_content').html('<div id="hdfs_refresh_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/refreshnodes/', function(json){
		var content = json.content;
		$('#hdfs_refresh_loaddiv').hide();
		$('#hdfs_refresh_content').html(content);
	});
}

function safemodestats()
{
	$('#hdfs_safemode_get').modal('toggle');
	$('#hdfs_safemode_get_content').empty();
	$('#hdfs_safemode_get_content').html('<div id="hdfs_safemode_get_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/safemodeget/', function(json){
		var content = json.content;
		$('#hdfs_safemode_get_loaddiv').hide();
		$('#hdfs_safemode_get_content').html(content);
	});
}

function safemodeenter()
{
	$('#hdfs_safemode_enter').modal('toggle');
	$('#hdfs_safemode_enter_content').empty();
	$('#hdfs_safemode_enter_content').html('<div id="hdfs_safemode_enter_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/safemodeenter/', function(json){
		var content = json.content;
		$('#hdfs_safemode_enter_loaddiv').hide();
		$('#hdfs_safemode_enter_content').html(content);
	});
}

function safemodeleave()
{
	$('#hdfs_safemode_leave').modal('toggle');
	$('#hdfs_safemode_leave_content').empty();
	$('#hdfs_safemode_leave_content').html('<div id="hdfs_safemode_leave_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/safemodeleave/', function(json){
		var content = json.content;
		$('#hdfs_safemode_leave_loaddiv').hide();
		$('#hdfs_safemode_leave_content').html(content);
	});
}

function startbalancer()
{
	$('#hdfs_start_balancer').modal('toggle');
	$('#hdfs_start_balancer_content').empty();
	$('#hdfs_start_balancer_content').html('<div id="hdfs_start_balancer_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/startbalancer/', function(json){
		var content = json.content;
		$('#hdfs_start_balancer_loaddiv').hide();
		$('#hdfs_start_balancer_content').html(content);
	});
}

function stopbalancer()
{
	$('#hdfs_stop_balancer').modal('toggle');
	$('#hdfs_stop_balancer_content').empty();
	$('#hdfs_stop_balancer_content').html('<div id="hdfs_stop_balancer_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/stopbalancer/', function(json){
		var content = json.content;
		$('#hdfs_stop_balancer_loaddiv').hide();
		$('#hdfs_stop_balancer_content').html(content);
	});
}

function balancerlog()
{
	$('#hdfs_balancer_log').modal('toggle');
	$('#hdfs_balancer_log_content').empty();
	$('#hdfs_balancer_log_content').html('<div id="hdfs_balancer_log_loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>');
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/balancerlog/', function(json){
		var content = json.content;
		$('#hdfs_balancer_log_loaddiv').hide();
		$('#hdfs_balancer_log_content').html(content);
	});
}

function balancerlog2()
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/balancerlog/', function(json){
		var content = json.content;
		$('#hdfs_balancer_log_loaddiv').hide();
		$('#hdfs_balancer_log_content').html(content);
	});
}
balancerlog2();
setInterval(function()
{
	balancerlog2()
}, 3000);
</script>

<div class="cols-md-12">
	<div class="btn-group">
		<button type="button" class="btn btn-default" onclick="report()"><?php echo $common_hdfs_report;?></button>
		<button type="button" class="btn btn-default" onclick="refresh()"><?php echo $common_hdfs_refreshnodes;?></button>
		<a href="http://<?php echo $nn_ip;?>:50070/" target="_blank" class="btn btn-default">Namenode Web</a>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<?php echo $common_hdfs_balancer;?>
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="javascript:startbalancer();"><?php echo $common_hdfs_start_balancer;?></a></li>
				<li><a href="javascript:stopbalancer();"><?php echo $common_hdfs_stop_balancer;?></a></li>
				<li><a href="javascript:balancerlog();"><?php echo $common_hdfs_balancer_log;?></a></li>
			</ul>
		</div>
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
	<input type="hidden" name="ls_current_dir" value="" id="ls_current_dir" />
	<div id="loaddiv" align="center"><img src="<?php echo $this->config->base_url();?>img/loading.gif" /></div>
	<div id="listHDFS"></div>
</div>