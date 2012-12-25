	<div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="<?php echo $this->config->base_url();?>"><?php echo $common_title;?></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li>
                <a href="<?php echo $this->config->base_url();?>"><?php echo $common_index_page;?></a>
              </li>
              <li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/settings/index/"><?php echo $common_host_settings?></a>
              </li>
              <li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/install/index/" ><?php echo $common_install;?></a>
              </li>
              <li>
                <a href="<?php echo $this->config->base_url();?>index.php/operate/index/"><?php echo $common_node_operate;?></a>
              </li>
              <li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/monitor/index/"><?php echo $common_node_monitor;?></a>
              </li>
              <li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/user/updatepassword/"><?php echo $common_user_admin?></a>
              </li>
			  <!--<li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/about/index/">About</a>
              </li>-->
              <li class="">
                <a href="<?php echo $this->config->base_url();?>index.php/user/logout/"><?php echo $common_log_out;?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>