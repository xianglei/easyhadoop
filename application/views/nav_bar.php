<header class="navbar navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
	<div class="navbar-header">
		<a class="navbar-brand" href="<?php echo $this->config->base_url();?>">
		<?php echo $common_title;?>
		</a>
	</div>
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<!--monitor link-->
			<li <?php if($this->router->class == "monitor"){ echo "class=\"active\"";}?>>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_nav_monitor;?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/memory/" ><?php echo $common_nav_mem;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/cpu/" ><?php echo $common_nav_cpu;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/hdfs/" ><?php echo $common_nav_storage;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/mapred/"><?php echo $common_nav_mapred;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/loadavg/"><?php echo $common_nav_load;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/monitor/network/"><?php echo $common_nav_nettraffic;?></a></li>
				</ul>
			</li>
			<!--monitor link-->
			
			<!--nodes link-->
			<li <?php if($this->router->class == "nodes"){ echo "class=\"active\"";}?>><a href="<?php echo $this->config->base_url();?>index.php/nodes/index/"><?php echo $common_nav_nodes;?></a></li>
			<!--nodes link-->
			
			<!--Install link-->
			<li <?php if($this->router->class == "install"){ echo "class=\"active\"";}?>><a href="<?php echo $this->config->base_url();?>index.php/install/index/" ><?php echo $common_nav_install;?></a></li>
			<!--Install link-->
			
			<!--Sets link-->
			<li <?php if($this->router->class == "settings"){ echo "class=\"active\"";}?>>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_nav_settings;?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/hadoop/" >Hadoop</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/hbase/" >HBase</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/hive/" >Hive</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/pig/">Pig</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/mahout/">Mahout</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/settings/zookeeper/">Zookeeper</a></li>
				</ul>
			</li>
			<!--Sets link-->
			
			<!--Eco link-->
			<li <?php if($this->router->class == "eco"){ echo "class=\"active\"";}?>>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_nav_eco;?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/hadoop/" >Hadoop</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/hbase/" >HBase</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/hive/" >Hive</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/pig/">Pig</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/mahout/">Mahout</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/eco/zookeeper/">Zookeeper</a></li>
				</ul>
			</li>
			<!--Eco link-->
			
			<!--HUI link-->
			<li <?php if($this->router->class == "hui"){ echo "class=\"active\"";}?>>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_nav_hui;?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo $this->config->base_url();?>index.php/hdfs/index/"><?php echo $common_nav_hdfs;?></a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/job/index/"><?php echo $common_nav_job;?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/hui/hbase/" >Infrasonic(HBase)</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/hui/hive/" >Buzz(Hive)</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/hui/pig/">Grunted(Pig)</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/hui/mahout/">Neigh(Mahout)</a></li>
					<li><a href="<?php echo $this->config->base_url();?>index.php/hui/zookeeper/">Holler(Zookeeper)</a></li>
				</ul>
			</li>
			<!--HUI link-->

			<!--User link-->
			<li <?php if($this->router->class == "user"){ echo "class=\"active\"";}?>><a href="<?php echo $this->config->base_url();?>index.php/user/updatepassword/"><?php echo $common_nav_user;?></a></li>
			<!--User link-->
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
				<li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/user/logout/"><?php echo $common_nav_logout;?></a>
				</li>
			</ul>
	</div>
	</div>
</header>