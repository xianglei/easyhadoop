	<div class="span2">
		<div class="btn-group btn-group-vertical">
			<a href="<?php echo $this->config->base_url();?>index.php/monitor/index/" class="btn btn-info"><i class="icon-random"></i> <?php echo $common_mem_status;?> </a>
			<a href="<?php echo $this->config->base_url();?>index.php/monitor/hdfsstats/" class="btn btn-info"><i class="icon-hdd"></i> <?php echo $common_storage_status;?> </a>
			<a href="/index.php/monitor/cpuinfo/" class="btn"><i class="icon-time"></i> <?php echo $common_cpu_status;?> </a>
			<a href="<?php echo $this->config->base_url();?>index.php/monitor/mapredstats/" class="btn btn-info"><i class="icon-tasks"></i> <?php echo $common_mapred_status;?> </a>
			<a href="<?php echo $this->config->base_url();?>index.php/monitor/loadavgstats/" class="btn btn-info"><i class="icon-signal"></i> <?php echo $common_loadavg_status;?> </a>
		</div>
	</div>