	<div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
        </a>
          <a class="brand" href="<?php echo $this->config->base_url();?>"><?php echo $common_title;?></a>
          <div class="nav-collapse collapse navbar-responsive-collapse">
            <ul class="nav">
			<!--Index-->
              <li <?php if($this->router->class == "manage"){ echo "class=\"active\"";}?>>
                <a href="<?php echo $this->config->base_url();?>manage/index/"><?php echo $common_index_page;?></a>
              </li>
			<!--Index end-->
			
			<!--Install-->
			<li class="dropdown" <?php if($this->router->class == "install"){ echo "class=\"active\"";}?>>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_install?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
				<li>
					<a href="<?php echo $this->config->base_url();?>install/index/" ><?php echo $common_install_hadoop;?></a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="<?php echo $this->config->base_url();?>install/index/" ><?php echo $common_install_hbase;?></a>
				</li>
				</ul>
			</li>
			<!--Install end-->
			
			<!--Settings-->
			  <li class="dropdown" <?php if($this->router->class == "operate"){ echo "class=\"active\"";}?>>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_host_settings?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
				<li>
					<a href="<?php echo $this->config->base_url();?>settings/index/"><?php echo $common_hadoop_host_settings?></a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="<?php echo $this->config->base_url();?>settings/index/"><?php echo $common_hbase_host_settings?></a>
				</li>
				</ul>
			  </li>
			<!--Settings end-->
			
			<!--Operate-->
			  <li class="dropdown" <?php if($this->router->class == "operate"){ echo "class=\"active\"";}?>>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $common_node_operate;?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo $this->config->base_url();?>operate/index/"><?php echo $common_hadoop_node_operate;?></a>
						  <a  id="hdfs_nav_node" href="<?php echo $this->config->base_url();?>hdfs/index/"><?php echo "HDFS管理"?></a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="<?php echo $this->config->base_url();?>operate/index/"><?php echo $common_hbase_node_operate;?></a>
					</li>
				</ul>
			  </li>
			<!--Operate end-->
			
			<!--Monitor-->
              <li <?php if($this->router->class == "monitor"){ echo "class=\"active\"";}?>>
                <a href="<?php echo $this->config->base_url();?>monitor/index/"><?php echo $common_node_monitor;?></a>
              </li>
              <li <?php if($this->router->class == "user"){ echo "class=\"active\"";}?>>
                <a href="<?php echo $this->config->base_url();?>user/updatepassword/"><?php echo $common_user_admin?></a>
              </li>
			<!--Monitor end-->
              
              
              
			
			
			  <!--<li class="">
                <a href="<?php echo $this->config->base_url();?>about/index/">About</a>
              </li>-->
              
            </ul>
			<ul class="nav pull-right">
				<li class="">
                <a href="<?php echo $this->config->base_url();?>user/logout/"><?php echo $common_log_out;?></a>
				</li>
			</ul>
          </div>
        </div>
      </div>
    </div>
