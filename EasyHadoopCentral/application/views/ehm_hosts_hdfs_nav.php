
<div class="span2">
	<div class="">
			<!--<a  id="add_folder"  href="#" class="btn btn-info"><i class="icon-thumbs-up"></i>建立目录</a><br>
			<a id="rename" href="#" class="btn btn-info"><i class="icon-thumbs-up"></i>重命名</a><br>
			<a id="remove"  href="#" class="btn btn-info"><i class="icon-thumbs-up"></i> 删除文件 </a><br>-->
			<a href="#"  onclick="$('#demo').jstree('refresh',-1);" class="btn btn-info"><i class="icon-thumbs-up"></i><?php echo $common_refresh_dir;?></a><br>
			<a href="#"   class="btn btn-info" onclick="remove_all();"><i class="icon-thumbs-up"></i><?php echo $common_batch_rm;?></a><br>
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
	var w =0;
	if(c<1)
	{
		alter("<?php echo $common_choose_file_to_rm;?>");
		return;
	}
	
	$('#confirmDiv').confirmModal({
								heading:'<?php echo $common_rm_confirm;?>',
								body:'<?php echo $common_rm_confirmed;?>?',
								callback: function() {
										 $('#confirmDiv').hide();
	                                     var msg='';
	                                     $("#myModal").modal();
	                                     $("#modalshow").html("<?php echo $common_removing;?>...");
	                                     $.each(nodes, function(i, n) {  
	                                         per=i/c*100;	
	                                     	$.ajax({
	                                     			async : true,
	                                     			type: 'POST',
	                                     			url: "<?php echo $this->config->base_url();?>index.php/hdfs/remove/",
	                                     			data : { 
	                                     				"operation" : "remove_node", 
	                                     				"id" : $("#demo").jstree("get_text",$(n))
	                                     			}, 
	                                     			success : function (r) {
	                                     				$('#remove_progress').attr("style", "width: "+per+"%;");
	                                     				var r=eval("("+r+")");
	                                     				msg=msg + r.msg;
	                                     				w++;
														if(w==c)
														{
															$('#remove_progress').attr("style", "width: 100%;");
															$('#modalshow').html(msg);
															//$("#modalshow").html("返回信息");
															$('#remove_progress_show').attr("style","display:none");
															$('#remove_progress').attr("style", "width: 0%;");
															per=0;
															msg='';			
															w=0;
															$('#demo').jstree('refresh',-1);
														}														
	                                     			}
	                                     		});
	                                     		
                                                //alert($("#demo").jstree("get_text",$(n)) );
	                                     	   
		   
										});

								}
							});		
	
}

</script>