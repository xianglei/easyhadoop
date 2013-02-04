<div class="span10">

<div class="accordion" id="accordion_hadoop_settings">
<?php $i = 0; foreach($category as $cate):?>
	
	<div class="accordion-group">
	<form method="post" action="<?php echo $this->config->base_url();?>index.php/settings/generatesettings/">
		<div class="accordion-heading">
			<a class="accordion-toggle  alert alert-error" data-toggle="collapse" data-parent="#accordion_hadoop_settings" href="#collapse<?php echo $i;?>">
				<?php echo $cate->filename;?>
			</a>
		</div>
		<div id="collapse<?php echo $i;?>" class="accordion-body collapse <?php echo ($i == 0) ? "in" : "";?>">
			<div class="accordion-inner">
				<table class="table table-hover table-bordered table-striped">
					<?php
					$this->load->model('ehm_auxiliary_model', 'aux');
					$this->load->model('ehm_settings_model', 'sets');
					$results = $this->sets->get_hadoop_settings($cate->filename);
				
					foreach($results as $options):
					?>
					<tr>
						<td>
							<a><?php echo $options->name?></a> : 
							<input type="hidden" name="name[]" value="<?php echo $options->name;?>">
						</td>
						<td>
							<input type="text" name="value[]" value="<?php echo $options->value;?>" <?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $options->value)) ? "disabled" : "";?>/>
							
							<?php echo (preg_match('/(?<=\{)([^\}]*?)(?=\})/', $options->value)) ? '<input type="hidden" name="value[]" value="'. $options->value .'">' : '';?>
						</td>
						<td>
							<?php echo $options->description;?>
							<input type="hidden" name="desc" value="<?php echo $options->description;?>" />
						</td>
					</tr>
					<?php endforeach;?>
					<tr>
						<td>
						</td>
						<td>
							<input type="hidden" name="filename" value="<?php echo $cate->filename;?>">
						</td>
						<td>
							<input type="submit" class="btn btn-primary">
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
	</div>
	
<?php $i++; endforeach;?>

</div>
</div>