<div class="col-md-12">

	<div id="grid" style="height: 480px"></div>
	
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
                        //toolbar: kendo.template($("#createnode").html()),
						
                        columns: [
                            { field: "id", title: "ID", width: 30, hidden:true },
							{ field: "os", title: "os", width: 30 },
                            { field: "hostname", title: "Hostname", width: 60 },
                            { field: "ip", title: "IP", width: 60  },
							//{ field: "ssh_port", title: "ssh_port", width: 30, hidden:true },
							//{ field: "ssh_user", title: "ssh_user", width: 30, hidden:true },
							{ field: "ssh_pass", title: "ssh_pass", width: 30, hidden:true },
                            { field: "rack", title: "Rack", width: 40 },
							{ field: "is_sudo", title: "is_sudo", width: 30, hidden:true },
							//{ field: "namenode", title: "NN", width: 20,template:kendo.template($("#nn").html())},
							//{ field: "secondarynamenode", title: "SNN", width: 20,template:kendo.template($("#snn").html()) },
							//{ field: "datanode", title: "DN", width: 20,template:kendo.template($("#dn").html()) },
							//{ field: "jobtracker", title: "JT", width: 20,template:kendo.template($("#jt").html()) },
							//{ field: "tasktracker", title: "TT", width: 20,template:kendo.template($("#tt").html()) },
							//{ field: "create_time", width: 70 },
							
							{ command: { text:"<?php echo $common_install;?>", click: Install }, title: " ", width: "40px"}
							//{ command: { text:"HDD", click: HDD }, title: " ", width: "40px"},
							//{ command: ["destroy"], title: "&nbsp;", width: "40px"}
							//{ command: { text: "destroy"}, title: "&nbsp;", width: "50px" }
							
							], 
                        editable: "inline"
                    });
					function Install(e)
					{
						$("#install_hadoop_modal").modal('toggle');
						var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
						var id = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(0)").text();
						var os = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(1)").text();
						var hostname = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(2)").text();
						var ip = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(3)").text();
						var rack = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(5)").text();
						var is_sudo = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(6)").text();
						var ssh_pass = $("#grid").data("kendoGrid").tbody.find("tr[data-uid='" + dataItem.uid + "'] td:eq(4)").text();
						$("#node_id").val(id);
						$("#os").val(os);
						$('#inst_ip').html(ip)
						$('#inst_hostname').html(hostname)
						$("#node_ip").val(ip);
						$("#rack").val(rack);
						$("#ssh_pass").val(ssh_pass);
						$('#install_progress').attr("style", "width: 0%;");
						$('#install_hadoop_log').empty();
						$('#install_button').attr("disabled", false);
						if(is_sudo=="1")
						{
							$("#is_sudo").val("1");
						}
						if(is_sudo=="0")
						{
							$("#is_sudo").val("0");
						}
					
						/*if(namenode.indexOf("glyphicon-ok")>=0)
						{
						$("#namenode").prop("checked",true);
						}
						else
						{
						$("#namenode").prop("checked",false);
						}*/
					};
					
                });
            </script>

</div>