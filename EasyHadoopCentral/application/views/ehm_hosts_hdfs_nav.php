
<div class="span2">
	<div class="">
			<!--<a  id="add_folder"  href="#" class="btn btn-info"><i class="icon-thumbs-up"></i>建立目录</a><br>
			<a id="rename" href="#" class="btn btn-info"><i class="icon-thumbs-up"></i>重命名</a><br>
			<a id="remove"  href="#" class="btn btn-info"><i class="icon-thumbs-up"></i> 删除文件 </a><br>-->
			<a href="#"  onclick="$('#demo').jstree('refresh',-1);" class="btn btn-info"><i class="icon-thumbs-up"></i>刷新目录</a><br>
			<a href="#"   class="btn btn-info" onclick="remove_all();"><i class="icon-thumbs-up"></i>批量删除</a><br>
	</div>
</div>
<script type="text/javascript">

var per=0;
var w=0;

function remove_all() 
{
	$('#remove_progress_show').attr("style","display:block");
	var nodes=$('#demo').jstree("get_checked");
	var c=nodes.size();
	if(c<1)
	{
		alter("请选择删除文件");
		return;
	}
	var msg='';
	$("#myModal").modal();
	$("#modal-title").html("批量删除中...");
	$.each(nodes, function(i, n) {  
	    per=i/c*100;	
		$.ajax({
				async : false,
				type: 'POST',
				url: "/hdfs/remove/",
				data : { 
					"operation" : "remove_node", 
					"id" : $("#demo").jstree("get_text",$(n))
				}, 
				success : function (r) {
					$('#remove_progress').attr("style", "width: "+per+"%;");
					var r=eval("("+r+")");
					msg=msg + r.msg;
					w++;
				}
			});
			if(w==c)
			{
				$('#remove_progress').attr("style", "width: 100%;");
				$('#modalshow').html(msg);
				$("#modal-title").html("返回信息");
				$('#remove_progress_show').attr("style","display:none");
				per=0;
				msg='';			
				w=0;
				$('#demo').jstree('refresh',-1);
			}
           //alert($("#demo").jstree("get_text",$(n)) );
		   
		   
 });
}

</script>