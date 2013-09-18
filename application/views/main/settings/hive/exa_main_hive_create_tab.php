<div class="col-md-10">
	<ul class="nav nav-tabs" id="Create">
		<li class="active"><a href="#hive" data-toggle="tab"><?php echo $common_hive_site;?></a></li>
		<li><a href="#env" data-toggle="tab"><?php echo $common_hive_env;?></a></li>
	</ul>
	<div id="CreateContent" class="tab-content">
	
<!--hive-site.xml-->
		<div class="tab-pane fade in active" id="hive">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/savehivesettings/">
	<table class="table table-hover table-bordered table-striped">
		<?php foreach($result_core as $core):?>
		<tr>
			<td>
			<a><?php echo $core->name?></a> : 
			<input type="hidden" name="name[]" value="<?php echo $core->name;?>">
			</td>
			<td>
			<input class="form-control" type="text" name="value[]" value="<?php echo $core->value;?>" />
			</td>
			<td>
			<!--<?php echo $core->description;?>-->
			<!--<input type="hidden" name="desc[]" value="<?php echo $core->description;?>" />-->
			</td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td>
				<?php echo $ip;?><input type="hidden" name="ip" value="<?php echo $ip;?>">
			</td>
			<td>
				<input type="hidden" name="filename" value="hive-site.xml">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
		</div>
<!--core-site.xml-->


<!--hive-env.sh-->
	<div class="tab-pane fade" id="env">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/savehiveenv/">
	<table class="table table-hover table-bordered table-striped">
		<tr>
			<td>
			<input type="text" name="name" class="form-control" value="hive-env.sh" disabled>
			</td>
			<td>
			<textarea name="value" class="form-control" rows="20" cols="60"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $ip;?><input type="hidden" name="ip" value="<?php echo $ip;?>">
				<input type="hidden" name="filename" value="hive-env.sh">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
	</div>
<!--hadoop-env.sh-->

	</div>
</div>