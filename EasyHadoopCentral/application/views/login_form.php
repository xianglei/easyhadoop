<div class="container">
	<h2>EasyHadoopManager 1.03</h2>
	<form class="form-horizontal" method="post" action="<?php echo $this->config->base_url();?>index.php/user/loginaction/">
		<div class="control-group">
			<label class="control-label">用户名</label>
			<div class="controls">
				<input type="text" name="username" placeholder="用户名">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">密码</label>
			<div class="controls">
				<input type="password" name="password" placeholder="密码">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn">Easy</button>
			</div>
		</div>
		<input type="hidden" name="referer" value="<?php echo $this->agent->referrer();?>" />
	</form>
</div>