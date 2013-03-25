<div class=span10>
	<div class="container">
		<h2>变更密码</h2>
		<form class="form-horizontal" method="post" action="<?php echo $this->config->base_url();?>index.php/user/updatepasswordaction/">
		<div class="control-group">
			<label class="control-label">当前密码</label>
			<div class="controls">
				<input type="password" name="old_password" placeholder="当前密码">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">输入新密码</label>
			<div class="controls">
				<input type="password" name="new_password1" placeholder="输入新密码">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">再次输入新密码</label>
			<div class="controls">
				<input type="password" name="new_password2" placeholder="再次输入新密码">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" class="btn" name="submit"  value="提交" />
			</div>
		</div>
		<input type=hidden name=action value=ChangePassword />
		</form>
	</div>
</div>