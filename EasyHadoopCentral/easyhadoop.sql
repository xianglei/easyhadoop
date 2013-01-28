DROP TABLE IF EXISTS `ehm_hadoop_settings`;
CREATE TABLE IF NOT EXISTS `ehm_hadoop_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` TEXT NULL,
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ehm_hive_settings`
--

DROP TABLE IF EXISTS `ehm_hive_settings`;
CREATE TABLE IF NOT EXISTS `ehm_hive_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` TEXT NOT NULL,
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ehm_hosts`
--

DROP TABLE IF EXISTS `ehm_hosts`;
CREATE TABLE IF NOT EXISTS `ehm_hosts` (
  `host_id` int(11) unsigned NOT NULL auto_increment,
  `hostname` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `role` varchar(50) NOT NULL,
  `ssh_user` varchar(100) NULL,
  `ssh_pass` varchar(100) NULL,
  `create_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `rack` int(10) NOT NULL default '1',
  `mount_name` TEXT NULL,
  `mount_data` TEXT NULL,
  `mount_mrlocal` TEXT NULL,
  `mount_mrsystem` TEXT NULL,
  `mount_snn` TEXT NULL,
  PRIMARY KEY  (`host_id`),
  UNIQUE KEY `hostname_2` (`hostname`),
  KEY `hostname` (`hostname`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `ehm_host_settings`
--

DROP TABLE IF EXISTS `ehm_host_settings`;
CREATE TABLE IF NOT EXISTS `ehm_host_settings` (
  `set_id` int(10) unsigned NOT NULL auto_increment,
  `filename` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting files of each host' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `ehm_pig_settings`
--

DROP TABLE IF EXISTS `ehm_pig_settings`;
CREATE TABLE IF NOT EXISTS `ehm_pig_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` TEXT NULL,
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ehm_user`
--

DROP TABLE IF EXISTS `ehm_user`;
CREATE TABLE IF NOT EXISTS `ehm_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ehm_user`
--

INSERT INTO `ehm_user` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'superadmin');