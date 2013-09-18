<div class="row">
<div class="col-md-4">
</div>
<div class="col-md-4">
	<form class="form-horizontal" method="post" action="<?php echo $this->config->base_url();?>index.php/user/loginaction/"  role="form">
	<div class="form-group">
		<img src="<?php echo $this->config->base_url();?>img/exadoop.jpg" width="262" height="90" />
		<div class="control-group">
			<label class="control-label"><?php echo $common_username;?></label>
			<div class="controls">
				<input type="text" name="username" placeholder="<?php echo $common_username;?>" class="form-control" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo $common_password;?></label>
			<div class="controls">
				<input type="password" name="password" placeholder="<?php echo $common_password;?>" class="form-control" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-default" class="form-control">EXA</button>
			</div>
		</div>
		<input type="hidden" name="referer" value="<?php echo $this->agent->referrer();?>" />
	</div>
	</form>
</div>
<div class="col-md-4">
</div>
</div>
