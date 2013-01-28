ALTER TABLE ehm_hosts ADD COLUMN `rack` INT(10) NOT NULL DEFAULT '1';
ALTER TABLE ehm_hosts ADD COLUMN `mount_name` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_data` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_mrlocal` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_mrsystem` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_snn` TEXT NULL;

ALTER TABLE ehm_hadoop_settings MODIFY COLUMN `value` TEXT NULL;
ALTER TABLE ehm_hive_settings MODIFY COLUMN `value` TEXT NOT NULL;
ALTER TABLE ehm_pig_settings MODIFY COLUMN `value` TEXT NOT NULL;

ALTER TABLE ehm_hadoop_settings MODIFY COLUMN `filename` VARCHAR(100) NULL;
ALTER TABLE ehm_hive_settings MODIFY COLUMN `filename` VARCHAR(100) NULL;
ALTER TABLE ehm_pig_settings MODIFY COLUMN `filename` VARCHAR(100) NULL;

ALTER TABLE ehm_hadoop_settings MODIFY COLUMN `name` VARCHAR(100) NOT NULL;
ALTER TABLE ehm_hive_settings MODIFY COLUMN `name` VARCHAR(100) NOT NULL;
ALTER TABLE ehm_hbase_settings MODIFY COLUMN `name` VARCHAR(100) NOT NULL;

CREATE TABLE IF NOT EXISTS `ehm_hbase_settings` (
  `set_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` TEXT NULL,
  `description` text NOT NULL,
  `filename` varchar(100) NOT NULL,
  PRIMARY KEY  (`set_id`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

insert ehm_hadoop_settings set name='fs.default.name', value='hdfs://{namenode}:9000', description='HDFS主节点访问地址和端口', filename='core-site.xml';
insert ehm_hadoop_settings set name='fs.checkpoint.dir', value='{mount_snn}', description='HDFS元数据备份点路径设置(SNN)', filename='core-site.xml';
insert ehm_hadoop_settings set name='fs.checkpoint.period', value='1800', description='HDFS元数据备份间隔时间(SNN)/秒', filename='core-site.xml';
insert ehm_hadoop_settings set name='fs.checkpoint.size', value='33554432', description='HDFS元数据备份文件滚动大小(SNN)/字节', filename='core-site.xml';
insert ehm_hadoop_settings set name='io.compression.codecs', value='org.apache.hadoop.io.compress.DefaultCodec,com.hadoop.compression.lzo.LzoCodec,com.hadoop.compression.lzo.LzopCodec,org.apache.hadoop.io.compress.GzipCodec,org.apache.hadoop.io.compress.BZip2Codec', description='HDFS压缩编解码器', filename='core-site.xml';
insert ehm_hadoop_settings set name='io.compression.codec.lzo.class', value='com.hadoop.compression.lzo.LzoCodec', description='LZO编解码器', filename='core-site.xml';
insert ehm_hadoop_settings set name='topology.script.file.name', value='/etc/hadoop/RackAware.py', description='机架感知脚本位置', filename='core-site.xml';
insert ehm_hadoop_settings set name='topology.script.number.args', value='4000', description='机架感知主机最大值', filename='core-site.xml';
insert ehm_hadoop_settings set name='fs.trash.interval', value='4320', description='HDFS回收站自动清空间隔/分钟', filename='core-site.xml';
insert ehm_hadoop_settings set name='io.file.buffer.size', value='131072', description='序列化文件处理时读写buffer大小/字节', filename='core-site.xml';
insert ehm_hadoop_settings set name='webinterface.private.actions', value='false', description='NN网页是否可以删除目录文件', filename='core-site.xml';

insert ehm_hadoop_settings set name='dfs.name.dir', value='{mount_name}', description='Namenode元数据存储位置', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.data.dir', value='{mount_data}', description='HDFS数据存储路径', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.replication', value='3', description='文件块复制份数', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.datanode.du.reserved', value='1073741824', description='每硬盘保留空间/字节', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.block.size', value='67108864', description='文件块大小/字节', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.permissions', value='true', description='是否启用Hadoop文件系统 true/false', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.balance.bandwidthPerSec', value='10485760', description='Balancer使用最大带宽/字节', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.support.append', value='false', description='是否允许对文件APPEND', filename='hdfs-site.xml';
insert ehm_hadoop_settings set name='dfs.datanode.failed.volumes.tolerated', value='0', description='硬盘故障数量异常阀值', filename='hdfs-site.xml';

insert ehm_hadoop_settings set name='mapred.job.tracker', value='{jobtracker}:9001', description='Jobtracker地址', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.local.dir', value='{mount_mrlocal}', description='MR本地使用路径', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.system.dir', value='{mount_mrsystem}', description='MR系统使用路径', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.tasktracker.map.tasks.maximum', value='2', description='服务器最大map数', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.tasktracker.reduce.tasks.maximum', value='1', description='服务器最大reduce数', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.map.child.java.opts', value='-Xmx128M', description='每map线程使用内存大小', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.reduce.child.java.opts', value='-Xmx128M', description='每reduce线程使用内存大小', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.reduce.parallel.copies', value='3', description='reduce阶段并行复制线程数', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='io.sort.factor', value='100', description='处理流合并时的文件排序数', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='io.sort.mb', value='100', description='排序使用内存大小/MB', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.compress.map.output', value='true', description='是否对map进行压缩输出', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.map.output.compression.code', value='com.hadoop.compression.lzo.LzoCodec', description='map压缩输出使用的编码器', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.child.java.opts', value='-Xmx128M java.library.path=/opt/hadoopgpl/native/Linux-amd64-64', description='map/red虚拟机子进程设置', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.jobtracker.taskScheduler', value='org.apache.hadoop.mapred.JobQueueTaskScheduler', description='hadoop使用队列', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.output.compress', value='false', description='是否开启任务结果压缩输出', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.output.compression.codec', value='org.apache.hadoop.io.compress.DefaultCodec', description='任务结果压缩输出编解码器', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='map.sort.class', value='org.apache.hadoop.util.QuickSort', description='map排序算法', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.tasktracker.expiry.interval', value='60000', description='TaskTracker存活检测时间，超出该时间则认为tasktracker死亡/毫秒', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.local.dir.minspacestart', value='1073741824', description='硬盘空间小于该大小则不在本地做计算/字节', filename='mapred-site.xml';
insert ehm_hadoop_settings set name='mapred.local.dir.minspacekill', value='1073741824', description='硬盘空间小于该大小则不再申请新任务/字节', filename='mapred-site.xml';

















