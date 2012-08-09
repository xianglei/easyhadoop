#!/bin/env bash

################
#Use root to run this shell script, do not use sudo
#请使用root执行脚本，不要使用sudo方式
################

##Download && install packages##
echo "#######################################"
echo "Download and Install environment"
echo "#######################################"
yum -y install dialog lrzsz gcc gcc-c++ libstdc++-devel make automake autoconf ntp wget pcre pcre-devel sudo > /dev/null
ntpdate cn.pool.ntp.org
cd ~/
/usr/sbin/groupadd hadoop
/usr/sbin/useradd hadoop -g hadoop

mkdir hadoop
cd hadoop/
if [ ! -f "hadoop-1.0.3-1.x86_64.rpm" ]; then
	wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm
fi
if [ ! -f "lzop-1.03.tar.gz" ]; then
	wget http://113.11.199.230/resources/lzop-1.03.tar.gz
fi
if [ ! -f "hadoop-gpl-packaging-0.5.3-1.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm
fi
if [ ! -f "lzo-2.06.tar.gz" ]; then
	wget http://113.11.199.230/resources/lzo-2.06.tar.gz
fi
if [ ! -f "jdk-7u5-linux-x64.rpm" ]; then
	wget http://113.11.199.230/jdk/jdk-7u5-linux-x64.rpm
fi
if [ ! -f "lzo-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm
fi
if [ ! -f "lzo-devel-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm
fi

DIALOG='/bin/env dialog'

TMP="/tmp/menu.$$"
$DIALOG --title "EasyHadoop 1.2" --menu "Installation mode" 0 0 0 S "Single Node" N "Namenode and Jobtracker" D "Datanode and Tasktracker" 2>$TMP

TYPE=$(cat $TMP)

rm -f "$TMP"

if [ $TYPE = "S" ]; then
	rpm -ivh jdk-7u5-linux-x64.rpm lzo-2.06-1.el6.rfx.x86_64.rpm lzo-devel-2.06-1.el6.rfx.x86_64.rpm hadoop-gpl-packaging-0.5.3-1.x86_64.rpm hadoop-1.0.3-1.x86_64.rpm > /dev/null
	
	cp -rf /opt/hadoopgpl/lib/* /usr/lib64
	cp /opt/hadoopgpl/native/Linux-amd64-64/* /usr/lib64
	cp /opt/hadoopgpl/native/Linux-i386-32/* /usr/lib

	tar zxf lzo-2.06.tar.gz
	cd lzo-2.06
	./configure && make && make install
	cd ../

	tar zxf lzop-1.03.tar.gz
	cd lzop-1.03
	./configure && make && make install
	cd ../
	
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
</configuration>" > /etc/hadoop/core-site.xml

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
</configuration>" > /etc/hadoop/hdfs-site.xml

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
</configuration>" > /etc/hadoop/mapred-site.xml
	sudo -u hadoop hadoop namenode -format
	sudo -u hadoop hadoop-daemon.sh start namenode
	sudo -u hadoop hadoop-daemon.sh start secondarynamenode
	sudo -u hadoop hadoop-daemon.sh start datanode
	sudo -u hadoop hadoop-daemon.sh start jobtracker
	sudo -u hadoop hadoop-daemon.sh start tasktracker
	sudo -u hadoop hadoop dfsadmin -safemode leave
	
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> ~/.bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/java/default" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /home/hadoop/.bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/java/default" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /etc/bashrc
	echo "JAVA_HOME=$JAVA_HOME:/usr/java/default" >> ~/.bashrc
	echo "HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar" >> /etc/profile
	echo "JAVA_HOME=$JAVA_HOME:/usr/java/default" >> ~/.bashrc
	
	$DIALOG --title "EasyHadoop 1.2" --menu "Install utilities" 0 0 0 H "Hive data warehouse" 2>$TMP
	UTIL_TYPE=$(cat $TMP)
	rm -f "$TMP"
	if [ $UTIL_TYPE = "H" ]; then
		cd ~/
		cd hadoop/
		if [ ! -f "hive-0.9.0-bin.tar.gz" ]; then
			wget http://113.11.199.230/utils/hive-0.9.0-bin.tar.gz
		fi
		tar zxf hive-0.9.0-bin.tar.gz
		mv hive-0.9.0-bin /opt/hive
		cp /opt/hive/conf/hive-default.xml.template /opt/hive/conf/hive-site.xml
		/opt/hive/bin/daemon.py start
		echo "#################################################"
		echo "Hive install complete, Have been started Thrift on port 10000"
		echo "Derby metastore by default, but included mysql connector in lib/"
		echo "Already include Hive status watching tool Usage: /opt/hive/bin/daemon.py [start|stop|restart]"
		echo "Run /opt/hive/bin/hive to start"
		echo "If there is no HADOOP_CLASSPATH set, then run: \"export HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar\" manually"
		echo "#################################################"
		export HADOOP_CLASSPATH=$HADOOP_CLASSPATH:/usr/lib64/hadoop-lzo.jar
		export JAVA_HOME=$JAVA_HOME:/usr/java/default
		source /etc/profile
	else
		echo "Exit"
	fi
	
elif [ $TYPE = "N" ]; then
        echo "Developing"
elif [ $TYPE = "D" ]; then
        echo "Developing"
else
        echo "Exit"
fi