<div class="modal hide fade" id="myModal">
    <div class="modal-header" id="modal-title">
        <h3></h3>
	
	</div>
    <div class="modal-body" id="modalshow">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>	
		<div class="progress progress-info" style="display:none"id="remove_progress_show">
				<div class="bar" style="" id="remove_progress" ></div>
			</div>	

			</div>
		
    <div class="modal-footer">
    </div>
 </div>


			
<div class=span10>
			<div id="demo" class="controls" style="height:800px;width:480px;background-color:#FFFFFF;"></div>
</div>





<script type="text/javascript" class="source below">
$(function () {
$("#myModal").hide(); 
$("#demo")
	.bind("before.jstree", function (e, data) {
		$("#alog").append(data.func + "<br />");
	})
	.jstree({ 
		// List of active plugins
		"plugins" : [ 
			"themes","json_data","ui","crrm","cookies","dnd","search","types","hotkeys","contextmenu","checkbox" 
		],

		// I usually configure the plugin that handles the data first
		// This example uses JSON as it is most common
		"json_data" : { 
			// This tree is ajax enabled - as this is most common, and maybe a bit more complex
			// All the options are almost the same as jQuery's AJAX (read the docs)
			"ajax" : {
				// the URL to fetch the data
				"url" : "/hdfs/get_children/",
				// the `data` function is executed in the instance's scope
				// the parameter is the node being loaded 
				// (may be -1, 0, or undefined when loading the root nodes)
				"data" : function (n) { 
					// the result is fed to the AJAX request `data` option
					return { 
						"operation" : "get_children", 
						"id" : n.attr ? n.attr("id").replace("node_","") : 1 
					}; 
				}
			}
		},
		// Configuring the search plugin
		"search" : {
			// As this has been a common question - async search
			// Same as above - the `ajax` config option is actually jQuery's AJAX object
			"ajax" : {
				"url" : "/hdfs/search/",
				// You get the search string as a parameter
				"data" : function (str) {
					return { 
						"operation" : "search", 
						"search_str" : str 
					}; 
				}
			}
		},
		// Using types - most of the time this is an overkill
		// read the docs carefully to decide whether you need types
		"types" : {
			// I set both options to -2, as I do not need depth and children count checking
			// Those two checks may slow jstree a lot, so use only when needed
			"max_depth" : -2,
			"max_children" : -2,
			// I want only `drive` nodes to be root nodes 
			// This will prevent moving or creating any other type as a root node
			"valid_children" : [ "drive" ],
			"types" : {
				// The default type
				"default" : {
					// I want this type to have no children (so only leaf nodes)
					// In my case - those are files
					"valid_children" : "none",
					// If we specify an icon for the default type it WILL OVERRIDE the theme icons
					"icon" : {
						"image" : "/img/file.png"
					}
				},
				// The `folder` type
				"folder" : {
					// can have files and other folders inside of it, but NOT `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "/img/folder.png"
					}
				},
				// The `drive` nodes 
				"drive" : {
					// can have files and folders inside, but NOT other `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "/img/root.png"
					},
					// those prevent the functions with the same name to be used on `drive` nodes
					// internally the `before` event is used
					"start_drag" : false,
					"move_node" : false,
					"delete_node" : false,
					"remove" : false
				}
			}
		},
		// UI & core - the nodes to initially select and open will be overwritten by the cookie plugin

		// the UI plugin - it handles selecting/deselecting/hovering nodes
		"ui" : {
			// this makes the node with ID node_4 selected onload
			"initially_select" : [ "node_4" ]
		},
		// the core plugin - not many options here
		"core" : { 
			// just open those two nodes up
			// as this is an AJAX enabled tree, both will be downloaded from the server
			"initially_open" : [ "node_2" , "node_3" ] 
		}
	})
	.bind("create.jstree", function (e, data) {
		
		$.post(
			"/hdfs/create/", 
			{ 
				"operation" : "create_node", 
				"id" : data.rslt.parent.attr("id").replace("node_",""), 
				"position" : data.rslt.position,
				"title" : data.rslt.name,
				"type" : data.rslt.obj.attr("rel")
				
			}, 
			function (r,status) {
				var r=eval("("+r+")");
				if(r.msg.length >0)
				{
				$('#modalshow').html(r.msg);
				$("#myModal").modal();
				}
				//$("#myModal").show(); 
				//$(this).modal('show'); 
				if(r.status) {
					$(data.rslt.obj).attr("id", "node_" + r.id);
				}
				else {
					$.jstree.rollback(data.rlbk);
				}
				
			}
		);
		$('#demo').jstree('refresh',-1);
	})
	.bind("remove.jstree", function (e, data) {
		data.rslt.obj.each(function () {
			$.ajax({
				async : false,
				type: 'POST',
				url: "/hdfs/remove/",
				data : { 
					"operation" : "remove_node", 
					"id" : this.id.replace("node_","")
				}, 
				success : function (r) {
				
					var r=eval("("+r+")");
					if(r.msg.length >0)
					{
					$('#modalshow').html(r.msg);
					$("#myModal").modal();
					}
					
					if(!r.status) {
						data.inst.refresh();
					}
				}
			});
		});
		$('#demo').jstree('refresh',-1);
	})
	.bind("rename.jstree", function (e, data) {
		$.post(
			"/hdfs/rename/", 
			{ 
				"operation" : "rename_node", 
				"id" : data.rslt.obj.attr("id").replace("node_",""),
				"title" : data.rslt.new_name
			}, 
			function (r) {

					var r=eval("("+r+")");
					if(r.msg.length >0)
					{
						$('#modalshow').html(r.msg);
						$("#myModal").modal();
					}
					if(!r.status) {
					$.jstree.rollback(data.rlbk);
				}
			}
		);
		$('#demo').jstree('refresh',-1);
	})
	.bind("move_node.jstree", function (e, data) {
		data.rslt.o.each(function (i) {
			$.ajax({
				async : false,
				type: 'POST',
				url: "./server.php",
				data : { 
					"operation" : "move_node", 
					"id" : $(this).attr("id").replace("node_",""), 
					"ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""), 
					"position" : data.rslt.cp + i,
					"title" : data.rslt.name,
					"copy" : data.rslt.cy ? 1 : 0
				},
				success : function (r) {
					if(!r.status) {
						$.jstree.rollback(data.rlbk);
					}
					else {
						$(data.rslt.oc).attr("id", "node_" + r.id);
						if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
							data.inst.refresh(data.inst._get_parent(data.rslt.oc));
						}
					}
					$("#analyze").click();
				}
			});
		});
	});

});
</script>
<script type="text/javascript" class="source below">
// Code for the menu buttons
$(function () { 
	$("#mmenu input").click(function () {
		switch(this.id) {
			case "add_default":
			case "add_folder":
				$("#demo").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
				break;
			case "search":
				$("#demo").jstree("search", document.getElementById("text").value);
				break;
			case "text": break;
			default:
				$("#demo").jstree(this.id);
				break;
		}
	});
});
</script>