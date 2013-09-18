<div class="modal fade" id="hdfs_chown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/hdfs/chown/">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_hdfs_chown;?> : <span id="chown_title_dirname"></span></h3> 
	</div>
	<div class="modal-body">

		<table class="table table-bordered">
			<tr>
				<td><?php echo $common_hdfs_user;?>:
				</td>
				<td>
				<input type="text" name="chown_user" id="chown_user" value="" class="form-control"  />
				</td>
			</tr>
			<tr>
				<td><?php echo $common_hdfs_group;?>:
				</td>
				<td>
				<input type="text" name="chown_group" id="chown_group" value="" class="form-control" />
				</td>
			</tr>
			<tr>
				<td>
				<?php echo $common_hdfs_recursive;?>:
				</td>
				<td>
					<input type="checkbox" name="recursive" value="1" class="form-control">
					<input  type="hidden" name="chown_dir_name" id="chown_dir_name" value="">
				</td>
			</tr>
		</table>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close?></button>
		<button type="submit" class="btn btn-primary"><?php echo $common_save;?></button>
	</div>
</div>
</div>
</form>
</div>