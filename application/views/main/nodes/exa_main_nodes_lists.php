<div class="col-md-12">

	<div id="grid" style="height: 480px"></div>
	
	<script type="text/x-kendo-template" id="nn">
	#if(namenode=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
	</script>
		
	<script type="text/x-kendo-template" id="snn">
	#if(secondarynamenode=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
    </script>
	
	<script type="text/x-kendo-template" id="dn">
	#if(datanode=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
	</script>
	
	<script type="text/x-kendo-template" id="jt">
	#if(jobtracker=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
	</script>
	
	<script type="text/x-kendo-template" id="tt">
	#if(tasktracker=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
    </script>
	
	<script type="text/x-kendo-template" id="agent">
	#if(agent=="1"){#
		<span class="btn btn-xs btn-info glyphicon glyphicon-ok disabled"></span>
	#}else{#
		<span class="btn btn-xs glyphicon glyphicon-remove disabled"></span>
	#}#
    </script>
	
<!--	<script type="text/x-kendo-template" id="edittem">
	 
	<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle btn-primary btn-sm" data-toggle="dropdown">
		<?php echo $common_nodes;?>
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" style="position:relative;z-index:29999;">
			<li><a data-toggle="modal" href="\#add_node_modal"><?php echo $common_nodes_add_node;?></a></li>
			<li><a data-toggle="modal" href="\#add_nodes_modal"><?php echo $common_nodes_add_nodes;?></a></li>
		</ul>
	</div>

	</script>
-->
	<script type="text/x-kendo-template" id="createnode">
	<a data-toggle="modal" href="\#create_node_modal" class="btn btn-sm btn-primary"><?php echo $common_nodes_create_node;?></a>
	<a data-toggle="modal" href="\#create_nodes_modal" class="btn btn-sm btn-primary"><?php echo $common_nodes_create_nodes;?></a>
	</script>
	
	<script>	
		$(document).ready(function () {
                    var crudServiceBaseUrl = "<?php echo $this->config->base_url();?>index.php/nodes",
                        dataSource = new kendo.data.DataSource({
                            transport: {
                                read:  {
                                    url: crudServiceBaseUrl + "/listnodes/",
                                    dataType: "json"
                                },
                                destroy: {
                                    url: crudServiceBaseUrl + "/removenode/",
                                    dataType: "json",
									data:"GET"
                                },
                                parameterMap: function(options, operation) {
                                    if (operation !== "read" && options.models) {
                                        return {models: kendo.stringify(options.models)};
                                    }
                                }
                            },							
                            batch: true,
                            pageSize: 20,
							serverPaging: true,
                            schema: {
                                model: {
                                    id: "id",
                                    fields: {
                                        id: {  editable: true, nullable: true,validation: { required: true}},
										os: {  editable: true, nullable: true},
                                        hostname: { editable: false, validation: { required: true } },
                                        ip: { editable: false, type: "string", validation: { required: true, min: 1} },
										ssh_port: { editable: false, validation: { required: true } },
										ssh_user: { editable: false, validation: { required: true } },
										ssh_pass: { editable: false, validation: { required: true } },
                                        rack: { editable: false, type: "string", validation: { required: true, min: 1} },
										is_sudo: { editable: false, validation: { required: true } },
										namenode: { editable: false, type: "string" },
										secondarynamenode: { editable: false, type: "string" },
										datanode: { editable: false, type: "string" },
										jobtracker: { editable: false, type: "string" },
										tasktracker: { editable: false, type: "string" },
                                        //create_time: { editable: false, type: "string", validation: { min: 0, required: true } }
                                    }
                                }
                            },
                        });

                    $("#grid").kendoGrid({
                        dataSource: dataSource,						
                        navigatable: true,
                        pageable: true,
						resizable: true,
						sortable: true,
						//selectable: "multiple cell",
						scrollable: true,
                        height: 630,
                        toolbar: kendo.template($("#createnode").html()),
						
                        columns: [
                            { field: "id", title: "ID", width: 30, hidden:true },
							{ field: "os", title: "os", width: 30, hidden:true },
                            { field: "hostname", title: "Hostname", width: 60 },
                            { field: "ip", title: "IP", width: 60  },
							{ field: "ssh_port", title: "ssh_port", width: 30, hidden:true },
							{ field: "ssh_user", title: "ssh_user", width: 30, hidden:true },
							{ field: "ssh_pass", title: "ssh_pass", width: 30, hidden:true },
                            { field: "rack", title: "Rack", width: 40 },
							{ field: "is_sudo", title: "is_sudo", width: 30, hidden:true },
							{ field: "namenode", title: "NN", width: 20,template:kendo.template($("#nn").html())},
							{ field: "secondarynamenode", title: "SNN", width: 20,template:kendo.template($("#snn").html()) },
							{ field: "datanode", title: "DN", width: 20,template:kendo.template($("#dn").html()) },
							{ field: "jobtracker", title: "JT", width: 20,template:kendo.template($("#jt").html()) },
							{ field: "tasktracker", title: "TT", width: 20,template:kendo.template($("#tt").html()) },
							//{ field: "create_time", width: 70 },
							
							{ command: { text:"Edit", click: Edit }, title: " ", width: "40px"},
							{ command: { text:"Storage", click: HDD }, title: " ", width: "40px"},
							{ command: ["destroy"], title: "&nbsp;", width: "40px"}
							//{ command: { text: "destroy"}, title: "&nbsp;", width: "50px" }
							
							], 
                        editable: "inline"
                    });
					function Edit(e)
					{
						$("#edit_node_modal").modal('toggle');
						var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
						var id = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(0)").text();
						var os = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(1)").text();					  
						var ip = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(3)").text();
						var ssh_port = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(4)").text();
						var ssh_user = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(5)").text();
						var ssh_pass = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(6)").text();
						var rack = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(7)").text();
						var is_sudo = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(8)").text();
						var namenode = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(9)").html();					  
						var secondarynamenode = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(10)").html();
						var datanode = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(11)").html();
						var jobtracker = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(12)").html();
						var tasktracker = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(13)").html();
						$("#id").val(id);
						$("#os").val(os);
						$("#ipaddr").val(ip);
						$("#ssh_port").val(ssh_port);
						$("#ssh_user").val(ssh_user);
						$("#ssh_pass").val(ssh_pass);
						$("#rack").val(rack);					   
						if(is_sudo=="1")
						{
							$("#is_sudo").prop("checked",true);
						}
						if(is_sudo=="0")
						{
							$("#is_sudo").prop("checked",false);
						}
					
						if(namenode.indexOf("glyphicon-ok")>=0)
						{
						$("#namenode").prop("checked",true);
						}
						else
						{
						$("#namenode").prop("checked",false);
						}
					
						if(secondarynamenode.indexOf("glyphicon-ok")>=0)
						{
						$("#secondarynamenode").prop("checked",true);
						}
						else
						{
						$("#secondarynamenode").prop("checked",false);
						}
					
						if(datanode.indexOf("glyphicon-ok")>=0)
						{
						$("#datanode").prop("checked",true);
						}
						else 
						{
							$("#datanode").prop("checked",false);
						}
					
						if(jobtracker.indexOf("glyphicon-ok")>=0)
						{
							$("#jobtracker").prop("checked",true);
						}
						else
						{
							$("#jobtracker").prop("checked",false);
						}
					
						if(tasktracker.indexOf("glyphicon-ok")>=0)
						{
							$("#tasktracker").prop("checked",true);
						}
						else
						{
							$("#tasktracker").prop("checked",false);
						}
					};
					
					function HDD(e)
					{
						$("#setup_hdd").modal('toggle');
						var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
						var id = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(0)").text();
						var hostname = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(2)").text();
						var ip = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(3)").text();
						$("#hdd_host_id").val(id);
						$("#hdd_hostname").html(hostname);
						$("#hdd_ip").html(ip);
						$("#hdd_hidden_ip").val(ip);
						//$('#nodes_hdd_loaddiv').empty();
						$('#nodes_hdd_loaddiv').html('<img src="<?php echo $this->config->base_url();?>img/loading.gif" />');
						$.getJSON('<?php echo $this->config->base_url();?>index.php/nodes/getmountpoint/' + ip, function(json){
							$.getJSON('<?php echo $this->config->base_url();?>index.php/nodes/getsavedmountpoint/'+ip, function(json2){
								$('#nodes_hdd_loaddiv').hide();
								var i = json.file_system.length;
								html = '<table class="table table-bordered table-hover">';
								html += '<tr>';
								html += '	<td>FileSystem:</td><td>Size</td><td>Used</td><td>Mounted:</td><td>Selection:</td>';
								html += '</tr>';

								for(var i = 0; i < json.file_system.length; i++)
								{
									if(json.mounted_on[i] == json2.mounted_at[i])
									{
										checked = 'checked';
									}
									else
									{
										checked = '';
									}
									html += '<tr>';
									html += '<td>'+ json.file_system[i] +'</td>';
									html += '<td>'+ json.size[i] +'</td>';
									html += '<td>'+ json.used_percent[i] +'</td>';
									html += '<td>'+ json.mounted_on[i] +'</td>';
									html += '<td><input type=checkbox name="mount_point[]" value="' + json.mounted_on[i] + '" '+checked+'/> </td>';
									html += '</tr>';
								}
								html += '</table>';
								$('#hdd_status').html(html);
							});
						});
					};
					
                });
            </script>

</div>