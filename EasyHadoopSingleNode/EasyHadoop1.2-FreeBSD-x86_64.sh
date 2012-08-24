#!/bin/sh
################
#Use root to run this shell script, do not use sudo
################

##Download && install packages##
echo "#######################################"
echo "#Download and Install environment"
echo "#######################################"
setenv PACKAGEROOT ftp://ftp.cn.freebsd.org
pkg_add -r lrzsz ntp wget pcre openjdk6 lzop bash portupgrade
ntpdate cn.pool.ntp.org
chsh -s bash
chsh -s bash hadoop

if [ ! -d "/usr/ports/devel/hadoop/" ]; then
	portsnap fetch extract
else
	portsnap fetch update
fi

DIALOG='/usr/bin/env dialog'

TMP="/tmp/menu.$$"
$DIALOG --title "EasyHadoop 1.2" --menu "Installation mode" 0 0 0 S "Single Node" N "Namenode and Jobtracker" D "Datanode and Tasktracker" 2>$TMP

TYPE=$(cat $TMP)

rm -f "$TMP"

if [ $TYPE = "S" ]; then
	cd /usr/ports/devel/hadoop
	make install clean
	tar zxf hadoop-gpl-packaging-0.5.3-1.x86_64.tar.gz
	mv hadoopgpl /opt/
	
	cp -rf /opt/hadoopgpl/lib/* /usr/lib64
	cp /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64
	
	echo "<?xml version=\"1.0\"?>
<?xml-stylesheet type=\"text/xsl\" href=\"configuration.xsl\"?>
<!-- Put site-specific property overrides in this file. -->

<configuration>
	<property>
		<name>fs.default.name</name>
		<value>hdfs://localhost:9000</value>
	</property>
	<property>  
		<name>io.compression.codecs</name>
		<value>org.apache.hadoop.io.compress.GzipCodec,org.apache.hadoop.io.compress.DefaultCodec,com.hadoop.compression.lzo.LzoCodec,com.hadoop.compression.lzo.LzopCodec</value>  
	</property>  
	<property>  
		<name>io.compression.codec.lzo.class</name>  
		<value>com.hadoop.compression.lzo.LzoCodec</value>  
	</property>
</configuration>" > /usr/local/etc/hadoop/core-site.xml

	echo "<?xml version=\"1.0\"?>
<?xml-stylesheet type=\"text/xsl\" href=\"configuration.xsl\"?>
<!-- Put site-specific property overrides in this file. -->

<configuration>
	<property>
		<name>dfs.replication</name>
		<value>2</value>
	</property>
	<property>
		<name>dfs.permissions</name>
		<value>false</value>
	</property>
</configuration>" > /usr/local/etc/hadoop/hdfs-site.xml

	echo "<?xml version=\"1.0\"?>
<?xml-stylesheet type=\"text/xsl\" href=\"configuration.xsl\"?>
<!-- Put site-specific property overrides in this file. -->

<configuration>
     <property>
         <name>mapred.job.tracker</name>
         <value>localhost:9001</value>
     </property>
<property>
    <name>mapred.compress.map.output</name>
    <value>true</value>
  </property>
  <property>
    <name>mapred.map.output.compression.codec</name>
    <value>com.hadoop.compression.lzo.LzoCodec</value>
  </property>
  <property>
    <name>mapred.child.java.opts</name>
    <value>-Djava.library.path=/opt/hadoopgpl/lib</value>
  </property>
</configuration>" > /usr/local/etc/hadoop/mapred-site.xml

	echo "namenode_enable=YES" >> /etc/rc.conf;
	echo "datanode_enable=YES" >> /etc/rc.conf;
	echo "tasktracker_enable=YES" >> /etc/rc.conf;
	echo "jobtracker_enable=YES" >> /etc/rc.conf;

	rehash
	
	hadoop namenode -format
	/usr/local/etc/rc.d/namenode start
	/usr/local/etc/rc.d/secondarynamenode start
	/usr/local/etc/rc.d/datanode start
	/usr/local/etc/rc.d/jobtracker start
	/usr/local/etc/rc.d/tasktracker start
	hadoop dfsadmin -safemode leave
	
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> ~/.bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/local/openjdk6" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /home/hadoop/.bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/local/openjdk6" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /etc/bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/local/openjdk6" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /etc/profile
	echo "JAVA_HOME=$JAVA_HOME:/usr/local/openjdk6" >> ~/.bashrc
	
	source /etc/profile
	
elif [ $TYPE = "N" ]; then
        echo "Developing"
elif [ $TYPE = "D" ]; then
        echo "Developing"
else
        echo "Exit"
fi