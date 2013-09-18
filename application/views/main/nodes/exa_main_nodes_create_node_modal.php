<script>
</script>
  <div class="modal fade" id="create_node_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog"><form method="POST" action="<?php echo $this->config->base_url();?>index.php/nodes/createnode/">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php echo $common_nodes_create_node;?></h4>
        </div>
        <div class="modal-body" id="create_node_modal_body">
          
			<table width="100%" class="table table-bordered">
			<tr>
				<td>
				<label><?php echo $common_os;?></label>
				</td>
				<td>
					<select name="os" class="form-control" >
						<option value="ubuntu"><?php echo $common_ubuntu;?></option>
						<option value="centos6"><?php echo $common_centos_6;?></option>
						<option value="centos5"><?php echo $common_centos_5;?></option>
						<option value="redhat5"><?php echo $common_redhat_5;?></option>
						<option value="redhat6"><?php echo $common_redhat_6;?></option>
						<option value="suse10" disabled><?php echo $common_suse_10;?></option>
						<option value="suse11" disabled><?php echo $common_suse_11;?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
				<label><?php echo $common_ip;?></label>
				</td>
				<td><input type="text" placeholder="<?php echo $common_ip;?>" name="ipaddr"  class="form-control" /></td>
			</tr>
			<tr>
				<td>
				<label><?php echo $common_ssh_port;?></label>
				</td>
				<td><input type="text" name="ssh_port" value="22" placeholder="22"  class="form-control" /></td>
			</tr>
			<tr>
				<td><label><?php echo $common_ssh_user;?></label>
				</td>
				<td>
					<input type="text" name="ssh_user" placeholder="root"  class="form-control" value="root" disabled />
					<input type="hidden" name="ssh_user" value="root" />
				</td>
			</tr>
			<tr>
				<td><label><?php echo $common_ssh_pass;?></label>
				</td>
				<td>
					<input type="text" name="ssh_pass" placeholder="Password"  class="form-control" />
				</td>
			</tr>
			<tr>
				<td><label><?php echo $common_sudo;?>?</label></td>
				<td><input type="checkbox" name="sudo" value="1"  class="form-control" disabled /></td>
			</tr>
			<tr>
				<td>
				<label><?php echo $common_role;?></label>
				</td>
				<td>
					<input type="checkbox"  name="namenode" value="1"  />Namenode<br />
					<input type="checkbox"  name="datanode" value="1"  />Datanode<br />
					<input type="checkbox"  name="secondarynamenode" value="1"  />SecondaryNamenode<br />
					<input type="checkbox"  name="jobtracker" value="1"  />Jobtracker<br />
					<input type="checkbox"  name="tasktracker" value="1"  />Tasktracker<br />
				</td>
			</tr>
			<tr>
				<td><label><?php echo $common_rack;?></label></td>
				<td><input type="text" name="rack" placeholder="/default"  class="form-control" /></td>
			</tr>
		</table>
		  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $common_close;?></button>
          <button type="submit" class="btn btn-primary btn-sm" id="create_node_submit"><?php echo $common_save;?></button>
        </div>
      </div><!-- /.modal-content --></form>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->