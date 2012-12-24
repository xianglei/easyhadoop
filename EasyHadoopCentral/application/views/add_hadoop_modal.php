<div id="add_hadoop_node" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/manage/addhadoopnode/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_add_node?></h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-error"><?php echo $common_add_node_tips?></div>
			<label><?php echo $common_hostname;?></label><input type="text" placeholder="<?php echo $common_hostname;?>" name="hostname" /><br />
			<label><?php echo $common_ip_addr;?></label><input type="text" placeholder="<?php echo $common_ip_addr;?>" name="ipaddr" /><br />
			<label><?php echo $common_role_name;?></label><br />
			<input type="checkbox"  name="role[]" value="namenode" />Namenode<br />
			<input type="checkbox"  name="role[]" value="datanode" />Datanode<br />
			<input type="checkbox"  name="role[]" value="secondarynamenode" />SecondaryNamenode<br />
			<input type="checkbox"  name="role[]" value="jobtracker" />Jobtracker<br />
			<input type="checkbox"  name="role[]" value="tasktracker" />Tasktracker<br />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>