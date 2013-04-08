<div class="span10">

<script>
function viewlogs(role, host_id)
{
	$.get('<?php echo $this->config->base_url();?>index.php/operate/viewlogs/'+host_id+'/'+role+'/', {}, function(html){
		html = html;
		$('#viewlogs_'+role+'_'+host_id).html('<small>'+html+'</small>');
	});
}
function check_online(kind)
{
	$.ajaxSettings.async = false;
	
	$.getJSON('<?php echo $this->config->base_url();?>index.php/monitor/getallstats/'+kind, function(json){
		
		var deads=eval(json);
	
		for(var i=0;i<deads.length;i++)
		{
			
			$('#show_node_'+deads[i].replace(".","_")).css("display","block");
			
			
		
		}
	});
}

function autoSubmit(val)
{
	if(val && (val.length>0))
	this.location="<?php echo $this->config->base_url();?>index.php/operate/index/?q="+val;

}
function refresh_fai_datanode()
{
	$("tr[id^='show_node_']").each(function() {
               $(this).css("display","none");    
              });
	check_online("d");		
   $('#data_list').tab('show'); 	
}
function refresh_fail_tasknode()
{
	$("tr[id^='show_node_']").each(function() {
               $(this).css("display","none");    
                });
	check_online("t");	
	$('#task_list').tab('show'); 	
}

</script>
<ul id="mytab" class="nav nav-tabs">
    <li id="data_list" class="active"><a href="#home" data-toggle="tab"  onclick ='refresh_fai_datanode();'>DataNode</a></li>
    <li id="task_list"><a href="#profile" data-toggle="tab" onclick ='refresh_fail_tasknode();'>TaskTracker</a></li>
    
</ul>
<table class="table table-striped">
	<!--<thead>
		
	
		<tr>
			<th>#</th>
			<th>主机名称</th>
			<th>IP地址</th>
			<th>操作</th>
		</tr>
	</thead>-->
	<tbody>
<?php $i = 1; foreach($results as $item):?>
		<tr  id="show_node_<?php echo str_replace(".","_",$item->hostname);?>" style="display:none">
			<td><?php echo $i;?></td>
			<td><?php echo $item->hostname;?>
			</td>
			<td><?php echo $item->ip;?>
			</td>
			<td>


			</td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<script>
			
				check_online("d");
</script>
<div>
		<h3></h3>
	</div>

</div>