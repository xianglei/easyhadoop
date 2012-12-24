<div class="span10">
	<ul id="job_tab" class="nav nav-tabs">
		<li class="active"><a href="#active_job" data-toggle="tab">活跃任务</a></li>
		<li><a href="#kill_job" data-toggle="tab">杀任务</a></li>
	</ul>
	<div id="job_tab_content" class="tab-content">
		<div class="tab-pane fade in active" id="active_job">
			<pre>
			<?php
			if(@$job_list[0] != ""):
				echo $job_list[0];
				@array_shift($job_list);
			else:
				echo "未找到合法的Jobtracker";
			endif;
			?>
			</pre>
			<table class="table table-striped">
				<tr>
					<?php
					$tmp = explode("\t", $job_list[0]);
					foreach($tmp as $k=>$v):
						echo "<td>";
						echo trim($v);
						echo "</td>";
					endforeach;
					@array_shift($job_list);
					?>
				</tr>
				
				<?php
				if(is_array($job_list))
				{
					foreach(@$job_list as $k=>$v):
						echo "<tr>";
						$tmp = explode("\t", $v);
						if(count($tmp) > 1)
						{
							foreach(@$v as $kk => $vv):
								echo "<td>";
								if($kk == '2'):
									echo date("Y-m-d H:i:s", substr(trim($vv),0,10));
								else:
									echo trim($vv);
								endif;
								echo "</td>";
							endforeach;
						}
						else
						{
							echo "";
						}
						echo "</tr>";
					endforeach;
				}
				else
				{
					echo "未找到合法的Jobtracker";
				}
				?>
			</table>
		</div>
		<div class="tab-pane fade" id="kill_job">
			<form method=post action="<?php echo $this->config->base_url();?>index.php/operate/killjobbyid/">
				<fieldset>
					<legend>杀任务</legend>
					<label>任务ID</label>
					<input type="text" placeholder="Job ID here" name="job_id">
					<span class="help-block">Copy & Paste activating JobID</span>
					<button type="submit" class="btn btn-danger"><?php echo $common_submit;?></button>
				</fieldset>
			</form>
		</div>
	</div>
</div>