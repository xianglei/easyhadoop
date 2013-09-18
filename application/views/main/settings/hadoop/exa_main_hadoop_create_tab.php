<div class="col-md-10">
	<ul class="nav nav-tabs" id="Create">
		<li class="active"><a href="#core" data-toggle="tab"><?php echo $common_core_site;?></a></li>
		<li><a href="#hdfs" data-toggle="tab"><?php echo $common_hdfs_site;?></a></li>
		<li><a href="#mapred" data-toggle="tab"><?php echo $common_mapred_site;?></a></li>
		<li><a href="#env" data-toggle="tab"><?php echo $common_hadoop_env;?></a></li>
		<li><a href="#others" data-toggle="tab"><?php echo $common_hadoop_settings_others;?></a></li>
	</ul>
	<div id="CreateContent" class="tab-content">
	
<!--core-site.xml-->
		<div class="tab-pane fade in active" id="core">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/savesettings/">
	<table class="table table-hover table-bordered table-striped">
		<?php foreach($result_core as $core):?>
		<tr>
			<td>
			<a><?php echo $core->name?></a> : 
			<input type="hidden" name="name[]" value="<?php echo $core->name;?>">
			</td>
			<td>
			<input class="form-control" type="text" name="value[]" value="<?php echo $core->value;?>" <?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $core->value)) ? "disabled" : "";?>/>
			<?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $core->value)) ? '<input type="hidden" name="value[]" value="'. $core->value .'">' : '';?>
			</td>
			<td>
			<?php echo $core->description;?>
			<input type="hidden" name="desc[]" value="<?php echo $core->description;?>" />
			</td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td>
				<select name="ip" class="form-control">
					<option value="0"><?php echo $common_global_settings?></option>
					<option value="" disabled>----------------</option>
					<?php foreach($list as $node):?>
					<option value="<?php echo $node->ip;?>"><?php echo $node->ip;?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="hidden" name="filename" value="core-site.xml">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
		</div>
<!--core-site.xml-->


<!--hdfs-site.xml-->
		<div class="tab-pane fade" id="hdfs">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/savesettings/">
	<table class="table table-hover table-bordered table-striped">
		<?php foreach($result_hdfs as $hdfs):?>
		<tr>
			<td>
			<a><?php echo $hdfs->name?></a> : 
			<input type="hidden" name="name[]" value="<?php echo $hdfs->name;?>">
			</td>
			<td>
			<input type="text" name="value[]" class="form-control" value="<?php echo $hdfs->value;?>" <?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $hdfs->value)) ? "disabled" : "";?>/>
			<?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $hdfs->value)) ? '<input type="hidden" name="value[]" value="'. $hdfs->value .'">' : '';?>
			</td>
			<td>
			<?php echo $hdfs->description;?>
			<input type="hidden" name="desc[]" value="<?php echo $hdfs->description;?>" />
			</td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td>
				<select name="ip" class="form-control">
					<option value="0"><?php echo $common_global_settings?></option>
					<option value="" disabled>----------------</option>
					<?php foreach($list as $node):?>
					<option value="<?php echo $node->ip;?>"><?php echo $node->ip;?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="hidden" name="filename" value="hdfs-site.xml">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
		</div>
<!--hdfs-site.xml-->

<!--mapred-site.xml-->
		<div class="tab-pane fade" id="mapred">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/savesettings/">
	<table class="table table-hover table-bordered table-striped">
		<?php foreach($result_mapred as $mapred):?>
		<tr>
			<td>
			<a><?php echo $mapred->name?></a> : 
			<input type="hidden" name="name[]" value="<?php echo $mapred->name;?>">
			</td>
			<td>
			<input type="text" name="value[]" class="form-control" value="<?php echo $mapred->value;?>" <?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $mapred->value)) ? "disabled" : "";?>/>
			<?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $mapred->value)) ? '<input type="hidden" name="value[]" value="'. $mapred->value .'">' : '';?>
			</td>
			<td>
			<?php echo $mapred->description;?>
			<input type="hidden" name="desc[]" value="<?php echo $mapred->description;?>" />
			</td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td>
				<select name="ip" class="form-control">
					<option value="0"><?php echo $common_global_settings?></option>
					<option value="" disabled>----------------</option>
					<?php foreach($list as $node):?>
					<option value="<?php echo $node->ip;?>"><?php echo $node->ip;?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="hidden" name="filename" value="mapred-site.xml">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
		</div>
<!--mapred-site.xml-->

<!--hadoop-env.sh-->
	<div class="tab-pane fade" id="env">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/saveenv/">
	<table class="table table-hover table-bordered table-striped">
		<tr>
			<td>
			<input type="text" name="name" class="form-control" value="hadoop-env.sh" disabled>
			</td>
			<td>
			<textarea name="value" class="form-control" rows="20" cols="60"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<select name="ip" class="form-control">
					<option value="0"><?php echo $common_global_settings?></option>
					<option value="" disabled>----------------</option>
					<?php foreach($list as $node):?>
					<option value="<?php echo $node->ip;?>"><?php echo $node->ip;?></option>
					<?php endforeach;?>
				</select>
				<input type="hidden" name="filename" value="hadoop-env.sh">
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
	</div>
<!--hadoop-env.sh-->

<!--others-->
	<div class="tab-pane fade" id="others">
<form method="POST" action="<?php echo $this->config->base_url();?>index.php/settings/saveothers/">
	<table class="table table-hover table-bordered table-striped">
		<tr>
			<td>
			<select name="filename" class="form-control">
				<option value="mapred-queues.xml">Mapred Queues XML</option>
				<option value="capacity-scheduler.xml">Capacity Scheduler XML</option>
				<option value="fair-scheduler.xml">Fair Scheduler XML</option>
				<option value="hadoop-policy.xml">Hadoop Policy XML</option>
				<!--<option value="hadoop-metrics.properties">Hadoop Metric</option>-->
				<option value="hadoop-metrics2.properties">Hadoop Metric2 Properties</option>
				<option value="log4j.properties">Log4j Properties</option>
			</select>
			</td>
			<td>
			<textarea name="value" class="form-control" rows="20" cols="60"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<select name="ip" class="form-control">
					<option value="0"><?php echo $common_global_settings?></option>
					<option value="" disabled>----------------</option>
					<?php foreach($list as $node):?>
					<option value="<?php echo $node->ip;?>"><?php echo $node->ip;?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="submit" class="btn btn-primary">
			</td>
		</tr>
	</table>
</form>
	</div>
<!--other-->

	</div>
</div>