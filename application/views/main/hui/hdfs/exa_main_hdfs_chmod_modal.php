<script>
function setPriv()
{
	var priv = $('#chmod_priv').val();
	$.getJSON('<?php echo $this->config->base_url();?>index.php/hdfs/getmod/' + priv, function (json){
		var ur = json.ur;
		var uw = json.uw;
		var ux = json.ux;
		var gr = json.gr;
		var gw = json.gw;
		var gx = json.gx;
		var or = json.or;
		var ow = json.ow;
		var ox = json.ox;
		if(ur != 0)
		{
			$('#ur').prop('checked',true);
		}
		if(uw != 0)
		{
			$('#uw').prop('checked',true);
		}
		if(ux != 0)
		{
			$('#ux').prop('checked',true);
		}
		if(gr != 0)
		{
			$('#gr').prop('checked',true);
		}
		if(gw != 0)
		{
			$('#gw').prop('checked',true);
		}
		if(gx != 0)
		{
			$('#gx').prop('checked',true);
		}
		if(or != 0)
		{
			$('#or').prop('checked',true);
		}
		if(ow != 0)
		{
			$('#ow').prop('checked',true);
		}
		if(ox != 0)
		{
			$('#ox').prop('checked',true);
		}
	});
}
</script>

<div class="modal fade" id="hdfs_chmod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post" action="<?php echo $this->config->base_url();?>index.php/hdfs/chmod/">
<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_hdfs_chmod;?> : <span id="chmod_title_dirname"></span></h3>
	</div>
	<div class="modal-body">

		<table class="table table-bordered">
			<tr>
				<td>
				<?php echo $common_hdfs_user;?>
				</td>
				<td>
				<?php echo $common_hdfs_read;?><input type="checkbox" name="u[]" value="4" class="form-control" id="ur">
				</td>
				<td>
				<?php echo $common_hdfs_write;?><input type="checkbox" name="u[]" value="2" class="form-control" id="uw">
				</td>
				<td>
				<?php echo $common_hdfs_execute;?><input type="checkbox" name="u[]" value="1" class="form-control" id="ux">
				</td>
			</tr>
			<tr>
				<td>
				<?php echo $common_hdfs_group;?>
				</td>
				<td>
					<?php echo $common_hdfs_read;?><input type="checkbox" name="g[]" value="4" class="form-control" id="gr">
				</td>
				<td>
					<?php echo $common_hdfs_write;?><input type="checkbox" name="g[]" value="2" class="form-control" id="gw">
				</td>
				<td>
					<?php echo $common_hdfs_execute;?><input type="checkbox" name="g[]" value="1" class="form-control" id="gx">
				</td>
			</tr>
			<tr>
				<td>
				<?php echo $common_hdfs_other;?>
				</td>
				<td>
					<?php echo $common_hdfs_read;?><input type="checkbox" name="o[]" value="4" class="form-control" id="or">
				</td>
				<td>
					<?php echo $common_hdfs_write;?><input type="checkbox" name="o[]" value="2" class="form-control" id="ow">
				</td>
				<td>
					<?php echo $common_hdfs_execute;?><input type="checkbox" name="o[]" value="1" class="form-control" id="ox">
				</td>
			</tr>
			<tr>
				<td>
				<?php echo $common_hdfs_recursive;?>
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					<input type="checkbox" name="recursive" value="1" class="form-control">
					<input  type="hidden" name="chmod_priv" id="chmod_priv" value="">
					<input  type="hidden" name="chmod_dir_name" id="chmod_dir_name" value="">
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