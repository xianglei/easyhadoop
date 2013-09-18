-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 09 月 02 日 07:12
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `exadoop`
--

-- --------------------------------------------------------

--
-- 表的结构 `exa_hadoop_settings`
--

DROP TABLE IF EXISTS `exa_hadoop_settings`;
CREATE TABLE IF NOT EXISTS `exa_hadoop_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text,
  `description` text NOT NULL,
  `filename` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- 转存表中的数据 `exa_hadoop_settings`
--

INSERT INTO `exa_hadoop_settings` (`id`, `name`, `value`, `description`, `filename`) VALUES
(1, 'fs.default.name', 'hdfs://{namenode}:9000', 'HDFS主节点访问地址和端口', 'core-site.xml'),
(2, 'fs.checkpoint.dir', '{mount_snn}', 'HDFS检查点路径设置(SNN)', 'core-site.xml'),
(4, 'fs.checkpoint.period', '1800', 'HDFS元数据备份间隔时间(SNN)/秒', 'core-site.xml'),
(5, 'fs.checkpoint.size', '33554432', 'HDFS元数据备份文件滚动大小(SNN)/字节', 'core-site.xml'),
(6, 'io.compression.codecs', 'org.apache.hadoop.io.compress.DefaultCodec,com.hadoop.compression.lzo.LzoCodec,com.hadoop.compression.lzo.LzopCodec,org.apache.hadoop.io.compress.GzipCodec,org.apache.hadoop.io.compress.BZip2Codec,org.apache.hadoop.io.compress.SnappyCodec', 'HDFS压缩编解码器', 'core-site.xml'),
(7, 'io.compression.codec.lzo.class', 'com.hadoop.compression.lzo.LzoCodec', 'LZO编解码器', 'core-site.xml'),
(8, 'topology.script.file.name', '/etc/hadoop/RackAware.py', '机架感知脚本位置', 'core-site.xml'),
(9, 'topology.script.number.args', '100', '机架感知参数最大值', 'core-site.xml'),
(10, 'fs.trash.interval', '4320', 'HDFS回收站自动清空间隔/分钟', 'core-site.xml'),
(11, 'io.file.buffer.size', '131072', '序列化文件处理时读写buffer大小/字节', 'core-site.xml'),
(12, 'webinterface.private.actions', 'false', 'NN网页是否可以删除目录文件', 'core-site.xml'),
(13, 'dfs.name.dir', '{mount_name}', 'Namenode元数据存储位置', 'hdfs-site.xml'),
(14, 'dfs.data.dir', '{mount_data}', 'HDFS数据存储路径', 'hdfs-site.xml'),
(15, 'dfs.replication', '3', '文件块复制份数', 'hdfs-site.xml'),
(16, 'dfs.datanode.du.reserved', '1073741824', '每硬盘保留空间/字节', 'hdfs-site.xml'),
(17, 'dfs.block.size', '67108864', '文件块大小/字节', 'hdfs-site.xml'),
(18, 'dfs.permissions', 'true', '是否启用Hadoop文件系统 true/false', 'hdfs-site.xml'),
(19, 'dfs.balance.bandwidthPerSec', '10485760', 'Balancer使用最大带宽/字节', 'hdfs-site.xml'),
(20, 'dfs.support.append', 'true', '是否允许对文件APPEND', 'hdfs-site.xml'),
(22, 'dfs.datanode.failed.volumes.tolerated', '0', '硬盘故障数量异常阀值', 'hdfs-site.xml'),
(23, 'mapred.job.tracker', '{jobtracker}:9001', 'Jobtracker地址', 'mapred-site.xml'),
(24, 'mapred.local.dir', '{mount_mrlocal}', 'MR本地使用路径', 'mapred-site.xml'),
(25, 'mapred.system.dir', '/mapred/system', 'MR系统使用路径', 'mapred-site.xml'),
(26, 'mapred.tasktracker.map.tasks.maximum', '2', '服务器最大map数', 'mapred-site.xml'),
(27, 'mapred.tasktracker.reduce.tasks.maximum', '1', '服务器最大reduce数', 'mapred-site.xml'),
(28, 'mapred.map.child.java.opts', '-Xmx256M', '每map线程使用内存大小', 'mapred-site.xml'),
(29, 'mapred.reduce.child.java.opts', '-Xmx256M', '每reduce线程使用内存大小', 'mapred-site.xml'),
(30, 'mapred.reduce.parallel.copies', '6', 'reduce阶段并行复制线程数', 'mapred-site.xml'),
(31, 'io.sort.factor', '100', '处理流合并时的文件排序数', 'core-site.xml'),
(32, 'io.sort.mb', '200', '排序使用内存大小/MB', 'core-site.xml'),
(33, 'mapred.compress.map.output', 'true', '是否对map进行压缩输出', 'mapred-site.xml'),
(34, 'mapred.map.output.compression.code', 'org.apache.hadoop.io.compress.SnappyCodec', 'map压缩输出使用的编码器', 'mapred-site.xml'),
(35, 'mapred.child.java.opts', '-Xmx1024M -Xms1024M', 'map/red虚拟机子进程设置', 'mapred-site.xml'),
(36, 'mapred.jobtracker.taskScheduler', 'org.apache.hadoop.mapred.JobQueueTaskScheduler', 'hadoop使用队列', 'mapred-site.xml'),
(37, 'mapred.output.compress', 'false', '是否开启任务结果压缩输出', 'mapred-site.xml'),
(38, 'mapred.output.compression.codec', 'org.apache.hadoop.io.compress.DefaultCodec', '任务结果压缩输出编解码器', 'mapred-site.xml'),
(39, 'map.sort.class', 'org.apache.hadoop.util.QuickSort', 'map排序算法', 'mapred-site.xml'),
(40, 'mapred.tasktracker.expiry.interval', '60000', 'TaskTracker存活检测时间，超出该时间则认为tasktracker死亡/毫秒', 'mapred-site.xml'),
(41, 'mapred.local.dir.minspacestart', '1073741824', '硬盘空间小于该大小则不在本地做计算/字节', 'mapred-site.xml'),
(42, 'mapred.local.dir.minspacekill', '1073741824', '硬盘空间小于该大小则不再申请新任务/字节', 'mapred-site.xml'),
(43, 'security.client.protocol.acl', '*', '', 'hadoop-policy.xml'),
(44, 'security.client.datanode.protocol.acl', '*', '', 'hadoop-policy.xml'),
(45, 'security.datanode.protocol.acl', '*', '', 'hadoop-policy.xml'),
(46, 'security.inter.datanode.protocol.acl', '*', '', 'hadoop-policy.xml'),
(47, 'security.namenode.protocol.acl', '*', '', 'hadoop-policy.xml'),
(48, 'security.inter.tracker.protocol.acl', '*', '', 'hadoop-policy.xml'),
(49, 'security.job.submission.protocol.acl', '*', '', 'hadoop-policy.xml'),
(50, 'security.task.umbilical.protocol.acl', '*', '', 'hadoop-policy.xml'),
(51, 'security.refresh.policy.protocol.acl', '*', '', 'hadoop-policy.xml'),
(52, 'security.admin.operations.protocol.acl', '*', '', 'hadoop-policy.xml'),
(53, 'mapred.queue.default.acl-submit-job', '', '', 'mapred-queue-acls.xml'),
(54, 'mapred.queue.default.acl-administer-jobs', '', '', 'mapred-queue-acls.xml'),
(55, 'hadoop.security.authorization', 'false', 'Hadoop服务层级验证安全验证，需配合hadoop-policy.xml使用', 'core-site.xml'),
(56, 'mapred.reduce.slowstart.completed.maps', '0.05', 'Reduce启动所需map进度。', 'mapred-site.xml'),
(57, 'dfs.datanode.max.xcievers', '8192', 'Datanode打开文件句柄数', 'hdfs-site.xml'),
(58, 'fs.inmemory.size.mb', '300', '文件系统内存映射大小', 'core-site.xml'),
(59, 'dfs.datanode.handler.count', '20', 'Datanode处理RPC线程数', 'hdfs-site.xml'),
(60, 'dfs.namenode.handler.count', '60', 'Namenode处理RPC线程数', 'hdfs-site.xml'),
(61, 'mapred.jobtracker.restart.recover', 'true', 'Jobtracker重启后恢复任务', 'mapred-site.xml'),
(62, 'mapred.tasktracker.indexcache.mb', '30', 'Tasktracker缓存索引大小', 'mapred-site,xml'),
(63, 'dfs.safemode.extension', '5', 'Namenode退出Safemode的延迟时间', 'hdfs-site.xml'),
(64, 'mapred.job.reuse.jvm.num.tasks', '-1', 'Map/Reduce进程使用JVM重用', 'mapred-site.xml'),
(65, 'mapred.local.dir.minspacekill', '1073741824', '磁盘小于该空间则不申请新任务', 'mapred-site.xml');

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes`
--

DROP TABLE IF EXISTS `exa_nodes`;
CREATE TABLE IF NOT EXISTS `exa_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostname` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `rack` varchar(10) NOT NULL,
  `ssh_port` int(5) NOT NULL,
  `ssh_user` varchar(20) NOT NULL,
  `ssh_pass` varchar(100) NOT NULL,
  `os` varchar(20) NOT NULL,
  `is_sudo` tinyint(1) NOT NULL,
  `namenode` tinyint(1) NOT NULL,
  `datanode` tinyint(1) NOT NULL,
  `secondarynamenode` tinyint(1) NOT NULL,
  `jobtracker` tinyint(1) NOT NULL,
  `tasktracker` tinyint(1) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_formatted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`hostname`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_role`
--

DROP TABLE IF EXISTS `exa_nodes_role`;
CREATE TABLE IF NOT EXISTS `exa_nodes_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `is_hbase` tinyint(1) NOT NULL COMMENT '0=false,1=true',
  `is_hive` tinyint(1) NOT NULL COMMENT '0=false,1=true',
  `is_pig` tinyint(1) NOT NULL COMMENT '0=false,1=true',
  `is_zk` tinyint(1) NOT NULL COMMENT '0=false,1=true',
  `is_sqoop` tinyint(1) NOT NULL,
  `is_mahout` tinyint(1) NOT NULL,
  `is_oozie` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_settings`
--

DROP TABLE IF EXISTS `exa_nodes_settings`;
CREATE TABLE IF NOT EXISTS `exa_nodes_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting files of each host' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_storage`
--

DROP TABLE IF EXISTS `exa_nodes_storage`;
CREATE TABLE IF NOT EXISTS `exa_nodes_storage` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `is_formatted` tinyint(1) NOT NULL,
  `nn_storage` text NOT NULL,
  `dn_storage` text NOT NULL,
  `snn_storage` text NOT NULL,
  `local_storage` text NOT NULL,
  `system_storage` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_hbase_role`
--

DROP TABLE IF EXISTS `exa_nodes_hbase_role`;
CREATE TABLE IF NOT EXISTS `exa_nodes_hbase_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `is_master` tinyint(1) NOT NULL,
  `is_regionserver` tinyint(1) NOT NULL,
  `is_zookeeper` tinyint(1) NOT NULL,
  `is_thrift` tinyint(1) NOT NULL,
  `is_thrift2` tinyint(1) NOT NULL,
  `is_avro` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_hive_role`
--

DROP TABLE IF EXISTS `exa_nodes_hive_role`;
CREATE TABLE IF NOT EXISTS `exa_nodes_hive_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `is_thrift` tinyint(1) NOT NULL,
  `is_thrift2` tinyint(1) NOT NULL,
  `is_hwi` tinyint(1) NOT NULL,
  `is_hcatalog` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_user`
--

DROP TABLE IF EXISTS `exa_user`;
CREATE TABLE IF NOT EXISTS `exa_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `exa_user`
--

INSERT INTO `exa_user` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'superadmin');

-- --------------------------------------------------------

--
-- 表的结构 `exa_hive_settings`
--

DROP TABLE IF EXISTS `exa_hive_settings`;
CREATE TABLE IF NOT EXISTS `exa_hive_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `filename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=231 ;

-- --------------------------------------------------------

--
-- 表的结构 `exa_hbase_settings`
--

DROP TABLE IF EXISTS `exa_hbase_settings`;
CREATE TABLE IF NOT EXISTS `exa_hbase_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `filename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

--
-- 转存表中的数据 `exa_hbase_settings`
--

INSERT INTO `exa_hbase_settings` (`id`, `name`, `value`, `description`, `filename`) VALUES
(1, 'hbase.rootdir', 'hdfs://{namenode}:9000/hbase', 'Hbase on HDFS storage folder', 'hbase_site.xml'),
(2, 'hbase.cluster.distributed', 'true', 'Whether hbase runs with single or cluster, true is cluster', 'hbase-site.xml'),
(3, 'hbase.master', 'your_hostname', 'Hbase masters hostname', 'hbase-site.xml'),
(4, 'hbase.zookeeper.quorum', 'hostname1, hostname2, hostname3...', 'zookeeper ensemble, 3 nodes at least', 'hbase-site.xml'),
(5, 'hbase.tmp.dir', '/tmp/hbase', 'Hbase tmp dir', 'hbase-site.xml'),
(6, 'dfs.support.append', 'true', 'Whether hdfs support file append', 'hbase-site.xml'),
(7, 'hbase.master.port', '60000', 'hbase master bind to port', 'hbase-site.xml'),
(8, 'hbase.master.info.port', '60010', 'hbase master http port', 'hbase-site.xml'),
(9, 'hbase.regionserver.lease.period', '600000', 'hbase region server lease in milliseconds', 'hbase-site.xml'),
(10, 'hbase.regionserver.port', '60020', 'hbase region server bind to port', 'hbase-site.xml'),
(11, 'hbase.regionserver.info.port', '60030', 'hbase region server http port', 'hbase-site.xml'),
(12, 'hbase.regionserver.handler.count', '80', 'hbase region server RPC handlers', 'hbase-site.xml'),
(13, 'hfile.block.cache.size', '0.4', 'Percentage of maximum heap (-Xmx setting) to allocate to block cache used by HFile', 'hbase-site.xml'),
(14, 'hbase.regionserver.global.memstore.upperLimit', '0.4', 'Maximum size of all memstores in a region server before new updates are blocked and flushes are forced. Defaults to 40% of heap', 'hbase-site.xml'),
(15, 'hbase.regionserver.global.memstore.lowerLimit', '0.3', 'Defaults to 35% of heap', 'hbase-site.xml'),
(16, 'hbase.regionserver.optionalcacheflushinterval', '3600000', 'Maximum amount of time an edit lives in memory before being automatically flushed, 1hour by default', 'hbase-site.xml'),
(17, 'zookeeper.session.timeout', '90000', 'ZooKeeper session timeout in milliseconds.', 'hbase-site.xml'),
(18, 'zookeeper.znode.parent', '/hbase', 'Path to ZNode holding root region location.', 'hbase-site.xml'),
(19, 'hbase.zookeeper.peerport', '2888', 'Port used by ZooKeeper peers to talk to each other.', 'hbase-site.xml'),
(20, 'hbase.zookeeper.leaderport', '3888', 'Port used by ZooKeeper for leader election', 'hbase-site.xml'),
(21, 'hbase.zookeeper.property.clientPort', '2181', 'The port at which the clients will connect.', 'hbase-site.xml'),
(22, 'hbase.client.write.buffer', '2097152', 'Default size of the HTable client write buffer in bytes', 'hbase-site.xml'),
(23, 'hbase.client.retries.number', '35', 'Maximum retries.', 'hbase-site.xml'),
(24, 'hbase.client.scanner.caching', '100', 'Number of rows that will be fetched when calling next on a scanner if it is not served from (local, client) memory.', 'hbase-site.xml'),
(25, 'hbase.client.keyvalue.maxsize', '10485760', 'Specifies the combined maximum allowed size of a KeyValue instance.', 'hbase-site.xml'),
(26, 'hbase.client.scanner.timeout.period', '60000', 'Client scanner lease period in milliseconds.', 'hbase-site.xml'),
(27, 'hbase.client.localityCheck.threadPoolSize', '2', '', 'hbase-site.xml'),
(28, 'hbase.bulkload.retries.number', '0', 'This is maximum number of iterations to atomic bulk loads are attempted in the face of splitting operations 0 means never give up.', 'hbase-site.xml'),
(29, 'hbase.balancer.period', '300000', 'Period at which the region balancer runs in the Master.', 'hbase-site.xml'),
(30, 'hbase.regions.slop', '0.2', 'Rebalance if any regionserver has average + (average * slop) regions.', 'hbase-site.xml'),
(31, 'hbase.hregion.memstore.flush.size', '134217728', 'Memstore will be flushed to disk if size of the memstore exceeds this number of bytes.', 'hbase-site.xml'),
(32, 'hbase.hregion.preclose.flush.size', '5242880', 'If the memstores in a region are this size or larger when we go to close', 'hbase-site.xml'),
(33, 'hbase.hregion.max.filesize', '10737418240', 'Maximum HStoreFile size.If any one of a column families HStoreFiles has grown to exceed this value, the hosting HRegion is split in two.', 'hbase-site.xml'),
(34, 'hbase.hregion.majorcompaction', '604800000', 'The time (in miliseconds) between major compactions of all HStoreFiles in a region. 7days by default', 'hbase-site.xml'),
(35, 'hbase.storescanner.parallel.seek.enable', 'false', 'Enables StoreFileScanner parallel-seeking in StoreScanner, a feature which can reduce response latency under special conditions.', 'hbase-site.xml'),
(36, 'hbase.storescanner.parallel.seek.threads', '20', 'The default thread pool size if parallel-seeking feature enabled.', 'hbase-site.xml'),
(37, 'hfile.block.cache.size', '0.4', 'Percentage of maximum heap (-Xmx setting) to allocate to block cache used by HFile/StoreFile.', 'hbase-site.xml'),
(38, 'hfile.index.block.max.size', '131072', 'When the size of a leaf-level', 'hbase-site.xml'),
(39, 'hfile.format.version', '2', 'The HFile format version to use for new files.', 'hbase-site.xml'),
(40, 'io.storefile.bloom.block.size', '131072', 'The size in bytes of a single block ("chunk") of a compound Bloom filter.', 'hbase-site.xml'),
(41, 'hbase.rpc.server.engine', 'org.apache.hadoop.hbase.ipc.ProtobufRpcServerEngine', 'Implementation of org.apache.hadoop.hbase.ipc.RpcServerEngine to be used for server RPC call marshalling.', 'hbase-site.xml'),
(42, 'hbase.rpc.timeout', '60000', 'This is for the RPC layer to define how long HBase client applications take for a remote call to time out.', 'hbase-site.xml'),
(43, 'hbase.ipc.client.tcpnodelay', 'true', 'Set no delay on rpc socket connections.', 'hbase-site.xml'),
(44, 'hbase.master.keytab.file', '', 'Full path to the kerberos keytab file to use for logging in the configured HMaster server principal.', 'hbase-site.xml'),
(45, 'hbase.master.kerberos.principal', '', 'Ex. "hbase/_HOST@EXAMPLE.COM". The kerberos principal name that should be used to run the HMaster process.', 'hbase-site.xml'),
(46, 'hbase.regionserver.keytab.file', '', 'Full path to the kerberos keytab file to use for logging in the configured HRegionServer server principal.', 'hbase-site.xml'),
(47, 'hbase.regionserver.kerberos.principal', '', 'Ex. "hbase/_HOST@EXAMPLE.COM". The kerberos principal name that should be used to run the HRegionServer process.', 'hbase-site.xml'),
(48, 'hadoop.policy.file', 'hbase-policy.xml', 'The policy configuration file used by RPC servers to make authorization decisions on client requests.Only used when HBase security is enabled.', 'hbase-site.xml'),
(49, 'hbase.superuser', 'hbase', 'List of users or groups (comma-separated), who are allowed full privileges, regardless of stored ACLs, across the cluster.', 'hbase-site.xml'),
(50, 'hbase.auth.key.update.interval', '86400000', 'The update interval for master key for authentication tokens in servers in milliseconds. Only used when HBase security is enabled.', 'hbase-site.xml'),
(51, 'hbase.auth.token.max.lifetime', '604800000', 'The maximum lifetime in milliseconds after which an authentication token expires.', 'hbase-site.xml'),
(52, 'hbase.coprocessor.region.classes', '', 'A comma-separated list of Coprocessors that are loaded by default on all tables.', 'hbase-site.xml'),
(53, 'hbase.rest.port', '8080', 'The port for the HBase REST server.', 'hbase-site.xml'),
(54, 'hbase.rest.readonly', 'false', 'Possible values are: false: All HTTP methods are permitted - GET/PUT/POST/DELETE. true: Only the GET method is permitted.', 'hbase-site.xml'),
(55, 'hbase.rest.threads.max', '150', 'The maximum number of threads of the REST server thread pool.', 'hbase-site.xml'),
(56, 'hbase.rest.threads.min', '5', 'The minimum number of threads of the REST server thread pool.', 'hbase-site.xml'),
(57, 'hbase.online.schema.update.enable', 'true', 'Set true to enable online schema changes.', 'hbase-sie.xml'),
(58, 'hbase.table.lock.enable', 'true', 'Set to true to enable locking the table in zookeeper for schema change operations.', 'hbase-site.xml'),
(59, 'hbase.thrift.minWorkerThreads', '16', 'The "core size" of the thread pool.', 'hbase-site.xml'),
(60, 'hbase.thrift.maxWorkerThreads', '1000', 'The maximum size of the thread pool.', 'hbase-site.xml'),
(61, 'hbase.thrift.maxQueuedRequests', '1000', 'The maximum number of pending Thrift connections waiting in the queue.', 'hbase-site.xml'),
(62, 'hbase.thrift.htablepool.size.max','1000','The upper bound for the table pool used in the Thrift gateways server.','hbase-site.xml'),
(63, 'hbase.data.umask.enable', 'false', 'Enable, if true, that file permissions should be assigned to the files written by the regionserver', 'hbase-site.xml'),
(64, 'hbase.data.umask','000','File permissions that should be used to write data files when hbase.data.umask.enable is true','hbase-site.xml'),
(65, 'hbase.metrics.showTableName', 'true', 'Whether to include the prefix "tbl.tablename" in per-column family metrics.', 'hbase-site.xml'),
(66, 'hbase.snapshot.enabled', 'true', 'Set to true to allow snapshots to be taken / restored / cloned.', 'hbase-site.xml'),
(67, 'hbase.lease.recovery.timeout', '900000', 'How long we wait on dfs lease recovery in total before giving up.', 'hbase-site.xml'),
(68, 'hbase.lease.recovery.dfs.timeout', '64000', 'How long between dfs recover lease invocations.', 'hbase-site.xml'),
(69, 'hbase.regionserver.checksum.verify', 'true', 'If set to true, HBase will read data and then verify checksums for hfile blocks.', 'hbase-site.xml'),
(70, 'hbase.hstore.bytes.per.checksum', '16384', 'Number of bytes in a newly created checksum chunk for HBase-level checksums in hfile blocks.', 'hbase-site.xml'),
(71, 'hbase.hstore.checksum.algorithm', 'CRC32', 'NULL/CRC32/CRC32C', 'hbase-site.xml'),
(72, 'hbase.status.published', 'false', 'This setting activates the publication by the master of the status of the region server.', 'hbase-site.xml'),
(73, 'hbase.status.publisher.class', 'org.apache.hadoop.hbase.master.ClusterStatusPublisher$MulticastPublisher', 'Implementation of the status publication with a multicast message.', 'hbase-site.xml'),
(74, 'hbase.status.listener.class', 'org.apache.hadoop.hbase.client.ClusterStatusListener$MulticastListener', 'Implementation of the status listener with a multicast message.', 'hbase-site.xml'),
(75, 'hbase.status.multicast.address.ip', '226.1.1.3', 'Multicast address to use for the status publication by multicast.', 'hbase-site.xml'),
(76, 'hbase.status.multicast.address.port', '6100', 'Multicast port to use for the status publication by multicast.', 'hbase-site.xml'),
(77, 'hbase.dynamic.jars.dir', '/hbase/lib', 'The directory from which the custom filter/co-processor jars can be loaded dynamically by the region server without the need to restart.', 'hbase-site.xml');

-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_hbase_settings`
--

DROP TABLE IF EXISTS `exa_nodes_hbase_settings`;
CREATE TABLE IF NOT EXISTS `exa_nodes_hbase_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting files of each host' AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

--
-- 表的结构 `exa_nodes_hive_settings`
--

DROP TABLE IF EXISTS `exa_nodes_hive_settings`;
CREATE TABLE IF NOT EXISTS `exa_nodes_hive_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`,`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Setting files of each host' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `exa_hive_settings`
--

INSERT INTO `exa_hive_settings` (`id`, `name`, `value`, `description`, `filename`) VALUES
(1, 'mapred.reduce.tasks', '-1', 'The default number of reduce tasks per job.  Typically set\n  to a prime close to the number of available hosts.  Ignored when\n  mapred.job.tracker is "local". Hadoop set this to 1 by default, whereas hive uses -1 as its default value.\n  By setting this property to -1, Hive will automatically figure out what should be the number of reducers.\n  ', 'hive-site.xml'),
(2, 'hive.exec.reducers.bytes.per.reducer', '1000000000', 'size per reducer.The default is 1G, i.e if the input size is 10G, it will use 10 reducers.', 'hive-site.xml'),
(3, 'hive.exec.reducers.max', '999', 'max number of reducers will be used. If the one\n	specified in the configuration parameter mapred.reduce.tasks is\nnegative, hive will use this one as the max number of reducers when\n	automatically determine number of reducers.', 'hive-site.xml'),
(4, 'hive.cli.print.header', 'false', 'Whether to print the names of the columns in query output.', 'hive-site.xml'),
(5, 'hive.cli.print.current.db', 'false', 'Whether to include the current database in the hive prompt.', 'hive-site.xml'),
(6, 'hive.cli.prompt', 'hive', 'Command line prompt configuration value. Other hiveconf can be used in\n        this configuration value. Variable substitution will only be invoked at the hive\n        cli startup.', 'hive-site.xml'),
(7, 'hive.exec.scratchdir', '/tmp/hive-${user.name}', 'Scratch space for Hive jobs', 'hive-site.xml'),
(8, 'hive.exec.local.scratchdir', '/tmp/${user.name}', 'Local scratch space for Hive jobs', 'hive-site.xml'),
(9, 'hive.test.mode', 'false', 'whether hive is running in test mode. If yes, it turns on sampling and prefixes the output tablename', 'hive-site.xml'),
(10, 'hive.test.mode.prefix', 'test_', 'if hive is running in test mode, prefixes the output table by this string', 'hive-site.xml'),
(11, 'hive.test.mode.samplefreq', '32', 'if hive is running in test mode and table is not bucketed, sampling frequency', 'hive-site.xml'),
(12, 'hive.test.mode.nosamplelist', '', 'if hive is running in test mode, dont sample the above comma seperated list of tables', 'hive-site.xml'),
(13, 'hive.metastore.uris', '', 'Thrift uri for the remote metastore. Used by metastore client to connect to remote metastore.', 'hive-site.xml'),
(14, 'javax.jdo.option.ConnectionURL', 'jdbc:derby:;databaseName=metastore_db;create=true', 'JDBC connect string for a JDBC metastore', 'hive-site.xml'),
(15, 'javax.jdo.option.ConnectionDriverName', 'org.apache.derby.jdbc.EmbeddedDriver', 'Driver class name for a JDBC metastore', 'hive-site.xml'),
(16, 'javax.jdo.PersistenceManagerFactoryClass', 'org.datanucleus.jdo.JDOPersistenceManagerFactory', 'class implementing the jdo persistence', 'hive-site.xml'),
(17, 'javax.jdo.option.DetachAllOnCommit', 'true', 'detaches all objects from session so that they can be used after transaction is committed', 'hive-site.xml'),
(18, 'javax.jdo.option.NonTransactionalRead', 'true', 'reads outside of transactions', 'hive-site.xml'),
(19, 'javax.jdo.option.ConnectionUserName', 'APP', 'username to use against metastore database', 'hive-site.xml'),
(20, 'javax.jdo.option.ConnectionPassword', 'mine', 'password to use against metastore database', 'hive-site.xml'),
(21, 'javax.jdo.option.Multithreaded', 'true', 'Set this to true if multiple threads access metastore through JDO concurrently.', 'hive-site.xml'),
(22, 'datanucleus.connectionPoolingType', 'DBCP', 'Uses a DBCP connection pool for JDBC metastore', 'hive-site.xml'),
(23, 'datanucleus.validateTables', 'false', 'validates existing schema against code. turn this on if you want to verify existing schema ', 'hive-site.xml'),
(24, 'datanucleus.validateColumns', 'false', 'validates existing schema against code. turn this on if you want to verify existing schema ', 'hive-site.xml'),
(25, 'datanucleus.validateConstraints', 'false', 'validates existing schema against code. turn this on if you want to verify existing schema ', 'hive-site.xml'),
(26, 'datanucleus.storeManagerType', 'rdbms', 'metadata store type', 'hive-site.xml'),
(27, 'datanucleus.autoCreateSchema', 'true', 'creates necessary schema on a startup if one doesn"t exist. set this to false, after creating it once', 'hive-site.xml'),
(28, 'datanucleus.autoStartMechanismMode', 'checked', 'throw exception if metadata tables are incorrect', 'hive-site.xml'),
(29, 'datanucleus.transactionIsolation', 'read-committed', 'Default transaction isolation level for identity generation. ', 'hive-site.xml'),
(30, 'datanucleus.cache.level2', 'false', 'Use a level 2 cache. Turn this off if metadata is changed independently of hive metastore server', 'hive-site.xml'),
(31, 'datanucleus.cache.level2.type', 'SOFT', 'SOFT=soft reference based cache, WEAK=weak reference based cache.', 'hive-site.xml'),
(32, 'datanucleus.identifierFactory', 'datanucleus', 'Name of the identifier factory to use when generating table/column names etc. "datanucleus" is used for backward compatibility', 'hive-site.xml'),
(33, 'datanucleus.plugin.pluginRegistryBundleCheck', 'LOG', 'Defines what happens when plugin bundles are found and are duplicated [EXCEPTION|LOG|NONE]', 'hive-site.xml'),
(34, 'hive.metastore.warehouse.dir', '/user/hive/warehouse', 'location of default database for the warehouse', 'hive-site.xml'),
(35, 'hive.metastore.execute.setugi', 'false', 'In unsecure mode, setting this property to true will cause the metastore to execute DFS operations using the client"s reported user and group permissions. Note that this property must be set on both the client and server sides. Further note that its best effort. If client sets its to true and server sets it to false, client setting will be ignored.', 'hive-site.xml'),
(36, 'hive.metastore.event.listeners', '', 'list of comma seperated listeners for metastore events.', 'hive-site.xml'),
(37, 'hive.metastore.partition.inherit.table.properties', '', 'list of comma seperated keys occurring in table properties which will get inherited to newly created partitions. * implies all the keys will get inherited.', 'hive-site.xml'),
(38, 'hive.metadata.export.location', '', 'When used in conjunction with the org.apache.hadoop.hive.ql.parse.MetaDataExportListener pre event listener, it is the location to which the metadata will be exported. The default is an empty string, which results in the metadata being exported to the current user"s home directory on HDFS.', 'hive-site.xml'),
(39, 'hive.metadata.move.exported.metadata.to.trash', '', 'When used in conjunction with the org.apache.hadoop.hive.ql.parse.MetaDataExportListener pre event listener, this setting determines if the metadata that is exported will subsequently be moved to the user"s trash directory alongside the dropped table data. This ensures that the metadata will be cleaned up along with the dropped table data.', 'hive-site.xml'),
(40, 'hive.metastore.partition.name.whitelist.pattern', '', 'Partition names will be checked against this regex pattern and rejected if not matched.  To use, enable hive.metastore.pre.event.listeners=org.apache.hadoop.hive.metastore.PartitionNameWhitelistPreEventListener  Listener will not register if this property value is empty.', 'hive-site.xml'),
(41, 'hive.metastore.end.function.listeners', '', 'list of comma separated listeners for the end of metastore functions.', 'hive-site.xml'),
(42, 'hive.metastore.event.expiry.duration', '0', 'Duration after which events expire from events table (in seconds)', 'hive-site.xml'),
(43, 'hive.metastore.event.clean.freq', '0', 'Frequency at which timer task runs to purge expired events in metastore(in seconds).', 'hive-site.xml'),
(44, 'hive.metastore.connect.retries', '5', 'Number of retries while opening a connection to metastore', 'hive-site.xml'),
(45, 'hive.metastore.failure.retries', '3', 'Number of retries upon failure of Thrift metastore calls', 'hive-site.xml'),
(46, 'hive.metastore.client.connect.retry.delay', '1', 'Number of seconds for the client to wait between consecutive connection attempts', 'hive-site.xml'),
(47, 'hive.metastore.client.socket.timeout', '20', 'MetaStore Client socket timeout in seconds', 'hive-site.xml'),
(48, 'hive.metastore.rawstore.impl', 'org.apache.hadoop.hive.metastore.ObjectStore', 'Name of the class that implements org.apache.hadoop.hive.metastore.rawstore interface. This class is used to store and retrieval of raw metadata objects such as table, database', 'hive-site.xml'),
(49, 'hive.metastore.batch.retrieve.max', '300', 'Maximum number of objects (tables/partitions) can be retrieved from metastore in one batch. The higher the number, the less the number of round trips is needed to the Hive metastore server, but it may also cause higher memory requirement at the client side.', 'hive-site.xml'),
(50, 'hive.metastore.batch.retrieve.table.partition.max', '1000', 'Maximum number of table partitions that metastore internally retrieves in one batch.', 'hive-site.xml'),
(51, 'hive.default.fileformat', 'TextFile', 'Default file format for CREATE TABLE statement. Options are TextFile and SequenceFile. Users can explicitly say CREATE TABLE ... STORED AS <TEXTFILE|SEQUENCEFILE> to override', 'hive-site.xml'),
(52, 'hive.fileformat.check', 'true', 'Whether to check file format or not when loading data files', 'hive-site.xml'),
(53, 'hive.map.aggr', 'true', 'Whether to use map-side aggregation in Hive Group By queries', 'hive-site.xml'),
(54, 'hive.groupby.skewindata', 'false', 'Whether there is skew in data to optimize group by queries', 'hive-site.xml'),
(55, 'hive.groupby.mapaggr.checkinterval', '100000', 'Number of rows after which size of the grouping keys/aggregation classes is performed', 'hive-site.xml'),
(56, 'hive.mapred.local.mem', '0', 'For local mode, memory of the mappers/reducers', 'hive-site.xml'),
(57, 'hive.mapjoin.followby.map.aggr.hash.percentmemory', '0.3', 'Portion of total memory to be used by map-side grup aggregation hash table, when this group by is followed by map join', 'hive-site.xml'),
(58, 'hive.map.aggr.hash.force.flush.memory.threshold', '0.9', 'The max memory to be used by map-side grup aggregation hash table, if the memory usage is higher than this number, force to flush data', 'hive-site.xml'),
(59, 'hive.map.aggr.hash.percentmemory', '0.5', 'Portion of total memory to be used by map-side grup aggregation hash table', 'hive-site.xml'),
(60, 'hive.map.aggr.hash.min.reduction', '0.5', 'Hash aggregation will be turned off if the ratio between hash\n  table size and input rows is bigger than this number. Set to 1 to make sure\n  hash aggregation is never turned off.', 'hive-site.xml'),
(61, 'hive.optimize.cp', 'true', 'Whether to enable column pruner', 'hive-site.xml'),
(62, 'hive.optimize.index.filter', 'false', 'Whether to enable automatic use of indexes', 'hive-site.xml'),
(63, 'hive.optimize.index.groupby', 'false', 'Whether to enable optimization of group-by queries using Aggregate indexes.', 'hive-site.xml'),
(64, 'hive.optimize.ppd', 'true', 'Whether to enable predicate pushdown', 'hive-site.xml'),
(65, 'hive.optimize.ppd.storage', 'true', 'Whether to push predicates down into storage handlers.  Ignored when hive.optimize.ppd is false.', 'hive-site.xml'),
(66, 'hive.ppd.recognizetransivity', 'true', 'Whether to transitively replicate predicate filters over equijoin conditions.', 'hive-site.xml'),
(67, 'hive.optimize.groupby', 'true', 'Whether to enable the bucketed group by from bucketed partitions/tables.', 'hive-site.xml'),
(68, 'hive.optimize.skewjoin.compiletime', 'false', 'Whether to create a separate plan for skewed keys for the tables in the join.\n    This is based on the skewed keys stored in the metadata. At compile time, the plan is broken\n    into different joins: one for the skewed keys, and the other for the remaining keys. And then,\n    a union is performed for the 2 joins generated above. So unless the same skewed key is present\n    in both the joined tables, the join for the skewed key will be performed as a map-side join.\n\n    The main difference between this paramater and hive.optimize.skewjoin is that this parameter\n    uses the skew information stored in the metastore to optimize the plan at compile time itself.\n    If there is no skew information in the metadata, this parameter will not have any affect.\n    Both hive.optimize.skewjoin.compiletime and hive.optimize.skewjoin should be set to true.\n    Ideally, hive.optimize.skewjoin should be renamed as hive.optimize.skewjoin.runtime, but not doing\n    so for backward compatibility.\n\n    If the skew information is correctly stored in the metadata, hive.optimize.skewjoin.compiletime\n    would change the query plan to take care of it, and hive.optimize.skewjoin will be a no-op.\n  ', 'hive-site.xml'),
(69, 'hive.optimize.union.remove', 'false', '\n    Whether to remove the union and push the operators between union and the filesink above\n    union. This avoids an extra scan of the output by union. This is independently useful for union\n    queries, and specially useful when hive.optimize.skewjoin.compiletime is set to true, since an\n    extra union is inserted.\n\n    The merge is triggered if either of hive.merge.mapfiles or hive.merge.mapredfiles is set to true.\n    If the user has set hive.merge.mapfiles to true and hive.merge.mapredfiles to false, the idea was the\n    number of reducers are few, so the number of files anyway are small. However, with this optimization,\n    we are increasing the number of files possibly by a big margin. So, we merge aggresively.\n  ', 'hive-site.xml'),
(70, 'hive.mapred.supports.subdirectories', 'false', 'Whether the version of hadoop which is running supports sub-directories for tables/partitions.\n    Many hive optimizations can be applied if the hadoop version supports sub-directories for\n    tables/partitions. It was added by MAPREDUCE-1501\n  ', 'hive-site.xml'),
(71, 'hive.multigroupby.singlemr', 'false', 'Whether to optimize multi group by query to generate single M/R\n  job plan. If the multi group by query has common group by keys, it will be\n  optimized to generate single M/R job.', 'hive-site.xml'),
(72, 'hive.map.groupby.sorted', 'false', 'If the bucketing/sorting properties of the table exactly match the grouping key, whether to\n    perform the group by in the mapper by using BucketizedHiveInputFormat. The only downside to this\n    is that it limits the number of mappers to the number of files.\n  ', 'hive-site.xml'),
(73, 'hive.join.emit.interval', '1000', 'How many rows in the right-most join operand Hive should buffer before emitting the join result. ', 'hive-site.xml'),
(74, 'hive.join.cache.size', '25000', 'How many rows in the joining tables (except the streaming table) should be cached in memory. ', 'hive-site.xml'),
(75, 'hive.mapjoin.bucket.cache.size', '100', 'How many values in each keys in the map-joined table should be cached in memory. ', 'hive-site.xml'),
(76, 'hive.mapjoin.cache.numrows', '25000', 'How many rows should be cached by jdbm for map join. ', 'hive-site.xml'),
(77, 'hive.optimize.skewjoin', 'false', 'Whether to enable skew join optimization.\n    The algorithm is as follows: At runtime, detect the keys with a large skew. Instead of\n    processing those keys, store them temporarily in a hdfs directory. In a follow-up map-reduce\n    job, process those skewed keys. The same key need not be skewed for all the tables, and so,\n    the follow-up map-reduce job (for the skewed keys) would be much faster, since it would be a\n    map-join.\n  ', 'hive-site.xml'),
(78, 'hive.exec.list.bucketing.default.dir', 'HIVE_DEFAULT_LIST_BUCKETING_DIR_NAME', 'Default directory name used in list bucketing.\n    List bucketing feature will create sub-directory for each skewed-value and a default directory\n    for non-skewed value. This config specifies the default name for the default directory.\n    Sub-directory is created by list bucketing DML and under partition directory. User doesn"t need \n    to know how to construct the canonical path. It just gives user choice if they want to change \n    the default directory name.\n    For example, there are 2 skewed column c1 and c2. 2 skewed value: (1,a) and (2,b). subdirectory:\n    partition-dir/c1=1/c2=a/\n    partition-dir/c1=2/c2=b/\n    partition-dir/HIVE_DEFAULT_LIST_BUCKETING_DIR_NAME/HIVE_DEFAULT_LIST_BUCKETING_DIR_NAME/\n    Note: This config won"t impact users if they don"t list bucketing.\n  ', 'hive-site.xml'),
(79, 'hive.skewjoin.key', '100000', 'Determine if we get a skew key in join. If we see more\n	than the specified number of rows with the same key in join operator,\n	we think the key as a skew join key. ', 'hive-site.xml'),
(80, 'hive.skewjoin.mapjoin.map.tasks', '10000', ' Determine the number of map task used in the follow up map join job\n	for a skew join. It should be used together with hive.skewjoin.mapjoin.min.split\n	to perform a fine grained control.', 'hive-site.xml'),
(81, 'hive.skewjoin.mapjoin.min.split', '33554432', ' Determine the number of map task at most used in the follow up map join job\n	for a skew join by specifying the minimum split size. It should be used together with\n	hive.skewjoin.mapjoin.map.tasks to perform a fine grained control.', 'hive-site.xml'),
(82, 'hive.mapred.mode', 'nonstrict', 'The mode in which the hive operations are being performed.\n     In strict mode, some risky queries are not allowed to run. They include:\n       Cartesian Product.\n       No partition being picked up for a query.\n       Comparing bigints and strings.\n       Comparing bigints and doubles.\n       Orderby without limit.\n  ', 'hive-site.xml'),
(83, 'hive.enforce.bucketmapjoin', 'false', 'If the user asked for bucketed map-side join, and it cannot be performed,\n    should the query fail or not ? For eg, if the buckets in the tables being joined are\n    not a multiple of each other, bucketed map-side join cannot be performed, and the\n    query will fail if hive.enforce.bucketmapjoin is set to true.\n  ', 'hive-site.xml'),
(84, 'hive.exec.script.maxerrsize', '100000', 'Maximum number of bytes a script is allowed to emit to standard error (per map-reduce task). This prevents runaway scripts from filling logs partitions to capacity ', 'hive-site.xml'),
(85, 'hive.exec.script.allow.partial.consumption', 'false', ' When enabled, this option allows a user script to exit successfully without consuming all the data from the standard input.\n  ', 'hive-site.xml'),
(86, 'hive.script.operator.id.env.var', 'HIVE_SCRIPT_OPERATOR_ID', ' Name of the environment variable that holds the unique script operator ID in the user"s transform function (the custom mapper/reducer that the user has specified in the query)\n  ', 'hive-site.xml'),
(87, 'hive.exec.compress.output', 'false', ' This controls whether the final outputs of a query (to a local/hdfs file or a hive table) is compressed. The compression codec and other options are determined from hadoop config variables mapred.output.compress* ', 'hive-site.xml'),
(88, 'hive.exec.compress.intermediate', 'false', ' This controls whether intermediate files produced by hive between multiple map-reduce jobs are compressed. The compression codec and other options are determined from hadoop config variables mapred.output.compress* ', 'hive-site.xml'),
(89, 'hive.exec.parallel', 'true', 'Whether to execute jobs in parallel', 'hive-site.xml'),
(90, 'hive.exec.parallel.thread.number', '8', 'How many jobs at most can be executed in parallel', 'hive-site.xml'),
(91, 'hive.exec.rowoffset', 'false', 'Whether to provide the row offset virtual column', 'hive-site.xml'),
(92, 'hive.task.progress', 'false', 'Whether Hive should periodically update task progress counters during execution.  Enabling this allows task progress to be monitored more closely in the job tracker, but may impose a performance penalty.  This flag is automatically set to true for jobs with hive.exec.dynamic.partition set to true.', 'hive-site.xml'),
(93, 'hive.hwi.war.file', 'lib/hive-hwi-0.10.0.war', 'This sets the path to the HWI war file, relative to ${HIVE_HOME}. ', 'hive-site.xml'),
(94, 'hive.hwi.listen.host', '0.0.0.0', 'This is the host address the Hive Web Interface will listen on', 'hive-site.xml'),
(95, 'hive.hwi.listen.port', '9999', 'This is the port the Hive Web Interface will listen on', 'hive-site.xml'),
(96, 'hive.exec.pre.hooks', '', 'Comma-separated list of pre-execution hooks to be invoked for each statement.  A pre-execution hook is specified as the name of a Java class which implements the org.apache.hadoop.hive.ql.hooks.ExecuteWithHookContext interface.', 'hive-site.xml'),
(97, 'hive.exec.post.hooks', '', 'Comma-separated list of post-execution hooks to be invoked for each statement.  A post-execution hook is specified as the name of a Java class which implements the org.apache.hadoop.hive.ql.hooks.ExecuteWithHookContext interface.', 'hive-site.xml'),
(98, 'hive.exec.failure.hooks', '', 'Comma-separated list of on-failure hooks to be invoked for each statement.  An on-failure hook is specified as the name of Java class which implements the org.apache.hadoop.hive.ql.hooks.ExecuteWithHookContext interface.', 'hive-site.xml'),
(99, 'hive.client.stats.publishers', '', 'Comma-separated list of statistics publishers to be invoked on counters on each job.  A client stats publisher is specified as the name of a Java class which implements the org.apache.hadoop.hive.ql.stats.ClientStatsPublisher interface.', 'hive-site.xml'),
(100, 'hive.client.stats.counters', '', 'Subset of counters that should be of interest for hive.client.stats.publishers (when one wants to limit their publishing). Non-display names should be used', 'hive-site.xml'),
(101, 'hive.merge.mapfiles', 'true', 'Merge small files at the end of a map-only job', 'hive-site.xml'),
(102, 'hive.merge.mapredfiles', 'false', 'Merge small files at the end of a map-reduce job', 'hive-site.xml'),
(103, 'hive.mergejob.maponly', 'true', 'Try to generate a map-only job for merging files if CombineHiveInputFormat is supported.', 'hive-site.xml'),
(104, 'hive.heartbeat.interval', '1000', 'Send a heartbeat after this interval - used by mapjoin and filter operators', 'hive-site.xml'),
(105, 'hive.merge.size.per.task', '256000000', 'Size of merged files at the end of the job', 'hive-site.xml'),
(106, 'hive.merge.smallfiles.avgsize', '16000000', 'When the average output file size of a job is less than this number, Hive will start an additional map-reduce job to merge the output files into bigger files.  This is only done for map-only jobs if hive.merge.mapfiles is true, and for map-reduce jobs if hive.merge.mapredfiles is true.', 'hive-site.xml'),
(107, 'hive.mapjoin.smalltable.filesize', '25000000', 'The threshold for the input file size of the small tables; if the file size is smaller than this threshold, it will try to convert the common join into map join', 'hive-site.xml'),
(108, 'hive.mapjoin.localtask.max.memory.usage', '0.90', 'This number means how much memory the local task can take to hold the key/value into in-memory hash table; If the local task"s memory usage is more than this number, the local task will be abort by themself. It means the data of small table is too large to be hold in the memory.', 'hive-site.xml'),
(109, 'hive.mapjoin.followby.gby.localtask.max.memory.usage', '0.55', 'This number means how much memory the local task can take to hold the key/value into in-memory hash table when this map join followed by a group by; If the local task"s memory usage is more than this number, the local task will be abort by themself. It means the data of small table is too large to be hold in the memory.', 'hive-site.xml'),
(110, 'hive.mapjoin.check.memory.rows', '100000', 'The number means after how many rows processed it needs to check the memory usage', 'hive-site.xml'),
(111, 'hive.auto.convert.join', 'false', 'Whether Hive enable the optimization about converting common join into mapjoin based on the input file size', 'hive-site.xml'),
(112, 'hive.script.auto.progress', 'false', 'Whether Hive Tranform/Map/Reduce Clause should automatically send progress information to TaskTracker to avoid the task getting killed because of inactivity.  Hive sends progress information when the script is outputting to stderr.  This option removes the need of periodically producing stderr messages, but users should be cautious because this may prevent infinite loops in the scripts to be killed by TaskTracker.  ', 'hive-site.xml'),
(113, 'hive.script.serde', 'org.apache.hadoop.hive.serde2.lazy.LazySimpleSerDe', 'The default serde for trasmitting input data to and reading output data from the user scripts. ', 'hive-site.xml'),
(114, 'hive.binary.record.max.length', '1000', 'Read from a binary stream and treat each hive.binary.record.max.length bytes as a record.\n  The last record before the end of stream can have less than hive.binary.record.max.length bytes', 'hive-site.xml'),
(115, 'hive.script.recordreader', 'org.apache.hadoop.hive.ql.exec.TextRecordReader', 'The default record reader for reading data from the user scripts. ', 'hive-site.xml'),
(116, 'hive.script.recordwriter', 'org.apache.hadoop.hive.ql.exec.TextRecordWriter', 'The default record writer for writing data to the user scripts. ', 'hive-site.xml'),
(117, 'hive.input.format', 'org.apache.hadoop.hive.ql.io.CombineHiveInputFormat', 'The default input format. Set this to HiveInputFormat if you encounter problems with CombineHiveInputFormat.', 'hive-site.xml'),
(118, 'hive.udtf.auto.progress', 'false', 'Whether Hive should automatically send progress information to TaskTracker when using UDTF"s to prevent the task getting killed because of inactivity.  Users should be cautious because this may prevent TaskTracker from killing tasks with infinte loops.  ', 'hive-site.xml'),
(119, 'hive.mapred.reduce.tasks.speculative.execution', 'true', 'Whether speculative execution for reducers should be turned on. ', 'hive-site.xml'),
(120, 'hive.exec.counters.pull.interval', '1000', 'The interval with which to poll the JobTracker for the counters the running job. The smaller it is the more load there will be on the jobtracker, the higher it is the less granular the caught will be.', 'hive-site.xml'),
(121, 'hive.querylog.location', '/tmp/${user.name}', '\n    Location of Hive run time structured log file\n  ', 'hive-site.xml'),
(122, 'hive.querylog.enable.plan.progress', 'true', '\n    Whether to log the plan"s progress every time a job"s progress is checked.\n    These logs are written to the location specified by hive.querylog.location\n  ', 'hive-site.xml'),
(123, 'hive.querylog.plan.progress.interval', '60000', '\n    The interval to wait between logging the plan"s progress in milliseconds.\n    If there is a whole number percentage change in the progress of the mappers or the reducers,\n    the progress is logged regardless of this value.\n    The actual interval will be the ceiling of (this value divided by the value of\n    hive.exec.counters.pull.interval) multiplied by the value of hive.exec.counters.pull.interval\n    I.e. if it is not divide evenly by the value of hive.exec.counters.pull.interval it will be\n    logged less frequently than specified.\n    This only has an effect if hive.querylog.enable.plan.progress is set to true.\n  ', 'hive-site.xml'),
(124, 'hive.enforce.bucketing', 'false', 'Whether bucketing is enforced. If true, while inserting into the table, bucketing is enforced. ', 'hive-site.xml'),
(125, 'hive.enforce.sorting', 'false', 'Whether sorting is enforced. If true, while inserting into the table, sorting is enforced. ', 'hive-site.xml'),
(126, 'hive.enforce.sortmergebucketmapjoin', 'false', 'If the user asked for sort-merge bucketed map-side join, and it cannot be performed,\n    should the query fail or not ?\n  ', 'hive-site.xml'),
(127, 'hive.metastore.ds.connection.url.hook', '', 'Name of the hook to use for retriving the JDO connection URL. If empty, the value in javax.jdo.option.ConnectionURL is used ', 'hive-site.xml'),
(128, 'hive.metastore.ds.retry.attempts', '1', 'The number of times to retry a metastore call if there were a connection error', 'hive-site.xml'),
(129, 'hive.metastore.ds.retry.interval', '1000', 'The number of miliseconds between metastore retry attempts', 'hive-site.xml'),
(130, 'hive.metastore.server.min.threads', '200', 'Minimum number of worker threads in the Thrift server"s pool.', 'hive-site.xml'),
(131, 'hive.metastore.server.max.threads', '100000', 'Maximum number of worker threads in the Thrift server"s pool.', 'hive-site.xml'),
(132, 'hive.metastore.server.tcp.keepalive', 'true', 'Whether to enable TCP keepalive for the metastore server. Keepalive will prevent accumulation of half-open connections.', 'hive-site.xml'),
(133, 'hive.metastore.sasl.enabled', 'false', 'If true, the metastore thrift interface will be secured with SASL. Clients must authenticate with Kerberos.', 'hive-site.xml'),
(134, 'hive.metastore.thrift.framed.transport.enabled', 'false', 'If true, the metastore thrift interface will use TFramedTransport. When false (default) a standard TTransport is used.', 'hive-site.xml'),
(135, 'hive.metastore.kerberos.keytab.file', '', 'The path to the Kerberos Keytab file containing the metastore thrift server"s service principal.', 'hive-site.xml'),
(136, 'hive.metastore.kerberos.principal', 'hive-metastore/_HOST@EXAMPLE.COM', 'The service principal for the metastore thrift server. The special string _HOST will be replaced automatically with the correct host name.', 'hive-site.xml'),
(137, 'hive.cluster.delegation.token.store.class', 'org.apache.hadoop.hive.thrift.MemoryTokenStore', 'The delegation token store implementation. Set to org.apache.hadoop.hive.thrift.ZooKeeperTokenStore for load-balanced cluster.', 'hive-site.xml'),
(138, 'hive.cluster.delegation.token.store.zookeeper.connectString', 'localhost:2181', 'The ZooKeeper token store connect string.', 'hive-site.xml'),
(139, 'hive.cluster.delegation.token.store.zookeeper.znode', '/hive/cluster/delegation', 'The root path for token store data.', 'hive-site.xml'),
(140, 'hive.cluster.delegation.token.store.zookeeper.acl', 'sasl:hive/host1@EXAMPLE.COM:cdrwa,sasl:hive/host2@EXAMPLE.COM:cdrwa', 'ACL for token store entries. List comma separated all server principals for the cluster.', 'hive-site.xml'),
(141, 'hive.metastore.cache.pinobjtypes', 'Table,StorageDescriptor,SerDeInfo,Partition,Database,Type,FieldSchema,Order', 'List of comma separated metastore object types that should be pinned in the cache', 'hive-site.xml'),
(142, 'hive.optimize.reducededuplication', 'true', 'Remove extra map-reduce jobs if the data is already clustered by the same key which needs to be used again. This should always be set to true. Since it is a new feature, it has been made configurable.', 'hive-site.xml'),
(143, 'hive.exec.dynamic.partition', 'true', 'Whether or not to allow dynamic partitions in DML/DDL.', 'hive-site.xml'),
(144, 'hive.exec.dynamic.partition.mode', 'strict', 'In strict mode, the user must specify at least one static partition in case the user accidentally overwrites all partitions.', 'hive-site.xml'),
(145, 'hive.exec.max.dynamic.partitions', '1000', 'Maximum number of dynamic partitions allowed to be created in total.', 'hive-site.xml'),
(146, 'hive.exec.max.dynamic.partitions.pernode', '100', 'Maximum number of dynamic partitions allowed to be created in each mapper/reducer node.', 'hive-site.xml'),
(147, 'hive.exec.max.created.files', '100000', 'Maximum number of HDFS files created by all mappers/reducers in a MapReduce job.', 'hive-site.xml'),
(148, 'hive.exec.default.partition.name', '__HIVE_DEFAULT_PARTITION__', 'The default partition name in case the dynamic partition column value is null/empty string or anyother values that cannot be escaped. This value must not contain any special character used in HDFS URI (e.g., ":", "%", "/" etc). The user has to be aware that the dynamic partition value should not contain this value to avoid confusions.', 'hive-site.xml'),
(149, 'hive.stats.dbclass', 'jdbc:derby', 'The default database that stores temporary hive statistics.', 'hive-site.xml'),
(150, 'hive.stats.autogather', 'true', 'A flag to gather statistics automatically during the INSERT OVERWRITE command.', 'hive-site.xml'),
(151, 'hive.stats.jdbcdriver', 'org.apache.derby.jdbc.EmbeddedDriver', 'The JDBC driver for the database that stores temporary hive statistics.', 'hive-site.xml'),
(152, 'hive.stats.dbconnectionstring', 'jdbc:derby:;databaseName=TempStatsStore;create=true', 'The default connection string for the database that stores temporary hive statistics.', 'hive-site.xml'),
(153, 'hive.stats.default.publisher', '', 'The Java class (implementing the StatsPublisher interface) that is used by default if hive.stats.dbclass is not JDBC or HBase.', 'hive-site.xml'),
(154, 'hive.stats.default.aggregator', '', 'The Java class (implementing the StatsAggregator interface) that is used by default if hive.stats.dbclass is not JDBC or HBase.', 'hive-site.xml'),
(155, 'hive.stats.jdbc.timeout', '30', 'Timeout value (number of seconds) used by JDBC connection and statements.', 'hive-site.xml'),
(156, 'hive.stats.retries.max', '0', 'Maximum number of retries when stats publisher/aggregator got an exception updating intermediate database. Default is no tries on failures.', 'hive-site.xml'),
(157, 'hive.stats.retries.wait', '3000', 'The base waiting window (in milliseconds) before the next retry. The actual wait time is calculated by baseWindow * failues + baseWindow * (failure + 1) * (random number between [0.0,1.0]).', 'hive-site.xml'),
(158, 'hive.stats.reliable', 'false', 'Whether queries will fail because stats cannot be collected completely accurately.\n    If this is set to true, reading/writing from/into a partition may fail becuase the stats\n    could not be computed accurately.\n  ', 'hive-site.xml'),
(159, 'hive.stats.collect.tablekeys', 'false', 'Whether join and group by keys on tables are derived and maintained in the QueryPlan.\n    This is useful to identify how tables are accessed and to determine if they should be bucketed.\n  ', 'hive-site.xml'),
(160, 'hive.stats.ndv.error', '20.0', 'Standard error expressed in percentage. Provides a tradeoff between accuracy and compute cost.A lower value for error indicates higher accuracy and a higher compute cost.\n  ', 'hive-site.xml'),
(161, 'hive.support.concurrency', 'false', 'Whether hive supports concurrency or not. A zookeeper instance must be up and running for the default hive lock manager to support read-write locks.', 'hive-site.xml'),
(162, 'hive.lock.numretries', '100', 'The number of times you want to try to get all the locks', 'hive-site.xml'),
(163, 'hive.unlock.numretries', '10', 'The number of times you want to retry to do one unlock', 'hive-site.xml'),
(164, 'hive.lock.sleep.between.retries', '60', 'The sleep time (in seconds) between various retries', 'hive-site.xml'),
(165, 'hive.zookeeper.quorum', '', 'The list of zookeeper servers to talk to. This is only needed for read/write locks.', 'hive-site.xml'),
(166, 'hive.zookeeper.client.port', '2181', 'The port of zookeeper servers to talk to. This is only needed for read/write locks.', 'hive-site.xml'),
(167, 'hive.zookeeper.session.timeout', '600000', 'Zookeeper client"s session timeout. The client is disconnected, and as a result, all locks released, if a heartbeat is not sent in the timeout.', 'hive-site.xml'),
(168, 'hive.zookeeper.namespace', 'hive_zookeeper_namespace', 'The parent node under which all zookeeper nodes are created.', 'hive-site.xml'),
(169, 'hive.zookeeper.clean.extra.nodes', 'false', 'Clean extra nodes at the end of the session.', 'hive-site.xml'),
(170, 'fs.har.impl', 'org.apache.hadoop.hive.shims.HiveHarFileSystem', 'The implementation for accessing Hadoop Archives. Note that this won"t be applicable to Hadoop vers less than 0.20', 'hive-site.xml'),
(171, 'hive.archive.enabled', 'false', 'Whether archiving operations are permitted', 'hive-site.xml'),
(172, 'hive.fetch.output.serde', 'org.apache.hadoop.hive.serde2.DelimitedJSONSerDe', 'The serde used by FetchTask to serialize the fetch output.', 'hive-site.xml'),
(173, 'hive.exec.mode.local.auto', 'false', ' Let hive determine whether to run in local mode automatically ', 'hive-site.xml'),
(174, 'hive.exec.drop.ignorenonexistent', 'true', '\n    Do not report an error if DROP TABLE/VIEW specifies a non-existent table/view\n  ', 'hive-site.xml'),
(175, 'hive.exec.show.job.failure.debug.info', 'true', '\n  	If a job fails, whether to provide a link in the CLI to the task with the\n  	most failures, along with debugging hints if applicable.\n  ', 'hive-site.xml'),
(176, 'hive.auto.progress.timeout', '0', '\n    How long to run autoprogressor for the script/UDTF operators (in seconds).\n    Set to 0 for forever.\n  ', 'hive-site.xml'),
(177, 'hive.hbase.wal.enabled', 'true', 'Whether writes to HBase should be forced to the write-ahead log.  Disabling this improves HBase write performance at the risk of lost writes in case of a crash.', 'hive-site.xml'),
(178, 'hive.table.parameters.default', '', 'Default property values for newly created tables', 'hive-site.xml'),
(179, 'hive.entity.separator', '@', 'Separator used to construct names of tables and partitions. For example, dbname@tablename@partitionname', 'hive-site.xml'),
(180, 'hive.ddl.createtablelike.properties.whitelist', '', 'Table Properties to copy over when executing a Create Table Like.', 'hive-site.xml'),
(181, 'hive.variable.substitute', 'true', 'This enables substitution using syntax like ${var} ${system:var} and ${env:var}.', 'hive-site.xml'),
(182, 'hive.variable.substitute.depth', '40', 'The maximum replacements the substitution engine will do.', 'hive-site.xml'),
(183, 'hive.conf.validation', 'true', 'Eables type checking for registered hive configurations', 'hive-site.xml'),
(184, 'hive.security.authorization.enabled', 'false', 'enable or disable the hive client authorization', 'hive-site.xml'),
(185, 'hive.security.authorization.manager', 'org.apache.hadoop.hive.ql.security.authorization.DefaultHiveAuthorizationProvider', 'the hive client authorization manager class name.\n  The user defined authorization class should implement interface org.apache.hadoop.hive.ql.security.authorization.HiveAuthorizationProvider.\n  ', 'hive-site.xml'),
(186, 'hive.security.metastore.authorization.manager', 'org.apache.hadoop.hive.ql.security.authorization.DefaultHiveMetastoreAuthorizationProvider', 'authorization manager class name to be used in the metastore for authorization.\n  The user defined authorization class should implement interface org.apache.hadoop.hive.ql.security.authorization.HiveMetastoreAuthorizationProvider. \n  ', 'hive-site.xml'),
(187, 'hive.security.metastore.authorization.manager', 'org.apache.hadoop.hive.ql.security.authorization.DefaultHiveMetastoreAuthorizationProvider', 'authorization manager class name to be used in the metastore for authorization.\n  The user defined authorization class should implement interface org.apache.hadoop.hive.ql.security.authorization.HiveMetastoreAuthorizationProvider. \n  ', 'hive-site.xml'),
(188, 'hive.security.authenticator.manager', 'org.apache.hadoop.hive.ql.security.HadoopDefaultAuthenticator', 'hive client authenticator manager class name.\n  The user defined authenticator should implement interface org.apache.hadoop.hive.ql.security.HiveAuthenticationProvider.', 'hive-site.xml'),
(189, 'hive.security.metastore.authenticator.manager', 'org.apache.hadoop.hive.ql.security.HadoopDefaultMetastoreAuthenticator', 'authenticator manager class name to be used in the metastore for authentication. \n  The user defined authenticator should implement interface org.apache.hadoop.hive.ql.security.HiveAuthenticationProvider.', 'hive-site.xml'),
(190, 'hive.security.metastore.authenticator.manager', 'org.apache.hadoop.hive.ql.security.HadoopDefaultMetastoreAuthenticator', 'authenticator manager class name to be used in the metastore for authentication. \n  The user defined authenticator should implement interface org.apache.hadoop.hive.ql.security.HiveAuthenticationProvider.', 'hive-site.xml'),
(191, 'hive.security.authorization.createtable.user.grants', '', 'the privileges automatically granted to some users whenever a table gets created.\n   An example like "userX,userY:select;userZ:create" will grant select privilege to userX and userY,\n   and grant create privilege to userZ whenever a new table created.', 'hive-site.xml'),
(192, 'hive.security.authorization.createtable.group.grants', '', 'the privileges automatically granted to some groups whenever a table gets created.\n   An example like "groupX,groupY:select;groupZ:create" will grant select privilege to groupX and groupY,\n   and grant create privilege to groupZ whenever a new table created.', 'hive-site.xml'),
(193, 'hive.security.authorization.createtable.role.grants', '', 'the privileges automatically granted to some roles whenever a table gets created.\n   An example like "roleX,roleY:select;roleZ:create" will grant select privilege to roleX and roleY,\n   and grant create privilege to roleZ whenever a new table created.', 'hive-site.xml'),
(194, 'hive.security.authorization.createtable.owner.grants', '', 'the privileges automatically granted to the owner whenever a table gets created.\n   An example like "select,drop" will grant select and drop privilege to the owner of the table', 'hive-site.xml'),
(195, 'hive.metastore.authorization.storage.checks', 'false', 'Should the metastore do authorization checks against the underlying storage\n  for operations like drop-partition (disallow the drop-partition if the user in\n  question doesn"t have permissions to delete the corresponding directory\n  on the storage).', 'hive-site.xml'),
(196, 'hive.error.on.empty.partition', 'false', 'Whether to throw an excpetion if dynamic partition insert generates empty results.', 'hive-site.xml'),
(197, 'hive.index.compact.file.ignore.hdfs', 'false', 'True the hdfs location stored in the index file will be igbored at runtime.\n  If the data got moved or the name of the cluster got changed, the index data should still be usable.', 'hive-site.xml'),
(198, 'hive.optimize.index.filter.compact.minsize', '5368709120', 'Minimum size (in bytes) of the inputs on which a compact index is automatically used.', 'hive-site.xml'),
(199, 'hive.optimize.index.filter.compact.maxsize', '-1', 'Maximum size (in bytes) of the inputs on which a compact index is automatically used.\n  A negative number is equivalent to infinity.', 'hive-site.xml'),
(200, 'hive.index.compact.query.max.size', '10737418240', 'The maximum number of bytes that a query using the compact index can read. Negative value is equivalent to infinity.', 'hive-site.xml'),
(201, 'hive.index.compact.query.max.entries', '10000000', 'The maximum number of index entries to read during a query that uses the compact index. Negative value is equivalent to infinity.', 'hive-site.xml'),
(202, 'hive.index.compact.binary.search', 'true', 'Whether or not to use a binary search to find the entries in an index table that match the filter, where possible', 'hive-site.xml'),
(203, 'hive.exim.uri.scheme.whitelist', 'hdfs,pfile', 'A comma separated list of acceptable URI schemes for import and export.', 'hive-site.xml'),
(204, 'hive.lock.mapred.only.operation', 'false', 'This param is to control whether or not only do lock on queries\n  that need to execute at least one mapred job.', 'hive-site.xml'),
(205, 'hive.limit.row.max.size', '100000', 'When trying a smaller subset of data for simple LIMIT, how much size we need to guarantee\n   each row to have at least.', 'hive-site.xml'),
(206, 'hive.limit.optimize.limit.file', '10', 'When trying a smaller subset of data for simple LIMIT, maximum number of files we can\n   sample.', 'hive-site.xml'),
(207, 'hive.limit.optimize.enable', 'false', 'Whether to enable to optimization to trying a smaller subset of data for simple LIMIT first.', 'hive-site.xml'),
(208, 'hive.limit.optimize.fetch.max', '50000', 'Maximum number of rows allowed for a smaller subset of data for simple LIMIT, if it is a fetch query.\n   Insert queries are not restricted by this limit.', 'hive-site.xml'),
(209, 'hive.rework.mapredwork', 'false', 'should rework the mapred work or not.\n  This is first introduced by SymlinkTextInputFormat to replace symlink files with real paths at compile time.', 'hive-site.xml'),
(210, 'hive.exec.concatenate.check.index', 'true', 'If this sets to true, hive will throw error when doing\n   "alter table tbl_name [partSpec] concatenate" on a table/partition\n    that has indexes on it. The reason the user want to set this to true\n    is because it can help user to avoid handling all index drop, recreation,\n    rebuild work. This is very helpful for tables with thousands of partitions.', 'hive-site.xml'),
(211, 'hive.sample.seednumber', '0', 'A number used to percentage sampling. By changing this number, user will change the subsets\n   of data sampled.', 'hive-site.xml'),
(212, 'hive.io.exception.handlers', '', 'A list of io exception handler class names. This is used\n		to construct a list exception handlers to handle exceptions thrown\n		by record readers', 'hive-site.xml'),
(213, 'hive.autogen.columnalias.prefix.label', '_c', 'String used as a prefix when auto generating column alias.\n  By default the prefix label will be appended with a column position number to form the column alias. Auto generation would happen if an aggregate function is used in a select clause without an explicit alias.', 'hive-site.xml'),
(214, 'hive.autogen.columnalias.prefix.includefuncname', 'false', 'Whether to include function name in the column alias auto generated by hive.', 'hive-site.xml'),
(215, 'hive.exec.perf.logger', 'org.apache.hadoop.hive.ql.log.PerfLogger', 'The class responsible logging client side performance metrics.  Must be a subclass of org.apache.hadoop.hive.ql.log.PerfLogger', 'hive-site.xml'),
(216, 'hive.start.cleanup.scratchdir', 'false', 'To cleanup the hive scratchdir while starting the hive server', 'hive-site.xml'),
(217, 'hive.output.file.extension', '', 'String used as a file extension for output files. If not set, defaults to the codec extension for text files (e.g. ".gz"), or no extension otherwise.', 'hive-site.xml'),
(218, 'hive.insert.into.multilevel.dirs', 'false', 'Where to insert into multilevel directories like\n  "insert directory "/HIVEFT25686/chinna/" from table"', 'hive-site.xml'),
(219, 'hive.warehouse.subdir.inherit.perms', 'false', 'Set this to true if the the table directories should inherit the\n    permission of the warehouse or database directory instead of being created\n    with the permissions derived from dfs umask', 'hive-site.xml'),
(220, 'hive.exec.job.debug.capture.stacktraces', 'true', 'Whether or not stack traces parsed from the task logs of a sampled failed task for\n  		    each failed job should be stored in the SessionState\n  ', 'hive-site.xml'),
(221, 'hive.exec.driver.run.hooks', '', 'A comma separated list of hooks which implement HiveDriverRunHook and will be run at the\n  			   beginning and end of Driver.run, these will be run in the order specified\n  ', 'hive-site.xml'),
(222, 'hive.ddl.output.format', 'text', '\n    The data format to use for DDL output.  One of "text" (for human\n    readable text) or "json" (for a json object).\n  ', 'hive-site.xml'),
(223, 'hive.transform.escape.input', 'false', '\n    This adds an option to escape special chars (newlines, carriage returns and\n    tabs) when they are passed to the user script. This is useful if the hive tables\n    can contain data that contains special characters.\n  ', 'hive-site.xml'),
(224, 'hive.exec.rcfile.use.explicit.header', 'true', '\n    If this is set the header for RC Files will simply be RCF.  If this is not\n    set the header will be that borrowed from sequence files, e.g. SEQ- followed\n    by the input and output RC File formats.\n  ', 'hive-site.xml'),
(225, 'hive.multi.insert.move.tasks.share.dependencies', 'false', '\n    If this is set all move tasks for tables/partitions (not directories) at the end of a\n    multi-insert query will only begin once the dependencies for all these move tasks have been\n    met.\n    Advantages: If concurrency is enabled, the locks will only be released once the query has\n                finished, so with this config enabled, the time when the table/partition is\n                generated will be much closer to when the lock on it is released.\n    Disadvantages: If concurrency is not enabled, with this disabled, the tables/partitions which\n                   are produced by this query and finish earlier will be available for querying\n                   much earlier.  Since the locks are only released once the query finishes, this\n                   does not apply if concurrency is enabled.\n  ', 'hive-site.xml'),
(226, 'hive.fetch.task.conversion', 'more', '\n    Some select queries can be converted to single FETCH task minimizing latency.\n    Currently the query should be single sourced not having any subquery and should not have\n    any aggregations or distincts (which incurrs RS), lateral views and joins.\n    1. minimal : SELECT STAR, FILTER on partition columns, LIMIT only\n    2. more    : SELECT, FILTER, LIMIT only (+TABLESAMPLE, virtual columns)\n  ', 'hive-site.xml'),
(227, 'hive.hmshandler.retry.attempts', '1', 'The number of times to retry a HMSHandler call if there were a connection error', 'hive-site.xml'),
(228, 'hive.hmshandler.retry.interval', '1000', 'The number of miliseconds between HMSHandler retry attempts', 'hive-site.xml'),
(229, 'hive.server.read.socket.timeout', '10', 'Timeout for the HiveServer to close the connection if no response from the client in N seconds, defaults to 10 seconds.', 'hive-site.xml'),
(230, 'hive.server.tcp.keepalive', 'true', 'Whether to enable TCP keepalive for the Hive server. Keepalive will prevent accumulation of half-open connections.', 'hive-site.xml');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
