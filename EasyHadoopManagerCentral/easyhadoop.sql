--
-- 数据库: `easyhadoop`
--

-- --------------------------------------------------------

--
-- 表的结构 `ehm_hadoop_settings`
--

DROP TABLE IF EXISTS `ehm_hadoop_settings`;
CREATE TABLE IF NOT EXISTS `ehm_hadoop_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
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
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
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
  `hostname` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `create_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`host_id`),
  UNIQUE KEY `hostname_2` (`hostname`),
  KEY `hostname` (`hostname`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `ehm_host_settings`
--

DROP TABLE IF EXISTS `ehm_host_settings`;
CREATE TABLE IF NOT EXISTS `ehm_host_settings` (
  `set_id` int(10) unsigned NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY  (`set_id`),
  UNIQUE KEY `filename_2` (`filename`),
  KEY `filename` (`filename`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting files of each host' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `ehm_pig_settings`
--

DROP TABLE IF EXISTS `ehm_pig_settings`;
CREATE TABLE IF NOT EXISTS `ehm_pig_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;