#!/bin/sh
if [ $# -lt 3 ]; then
	echo "Usage: $0 map.tasks.maximum reduce.tasks.maximum memory child.java.opts"
else
	map=$1
	reduce=$2
	mem=$3
	yum -y install lrzsz gcc gcc-c++ libstdc++-devel
	/usr/sbin/groupadd hadoop
	/usr/sbin/useradd hadoop -g hadoop
	mkdir -p /opt/modules/hadoop/
	mkdir -p /opt/data/hadoop1/
	chown hadoop:hadoop /opt/data/hadoop1/
	
	echo "-------------config hosts----------------"
	wget http://221.238.27.164/hadoop/hosts
	cat hosts >> /etc/hosts

	echo "----------------env init finish and prepare su hadoop---------------"

	HADOOP=/home/hadoop

	cd $HADOOP
	mkdir .ssh
	ssh-keygen -q -t rsa -N "" -f $HADOOP/.ssh/id_rsa
	cd $HADOOP/.ssh/ && cat id_rsa.pub > $HADOOP/.ssh/authorized_keys
	chmod go-rwx $HADOOP/.ssh/authorized_keys

	wget http://221.238.27.164/hadoop/hadoop-0.20.203.0.tar.gz
	wget http://221.238.27.164/hadoop/hadoop-gpl-packaging-0.2.8-1.x86_64.rpm
	wget http://221.238.27.164/hadoop/jdk-6u21-linux-amd64.rpm
	wget http://221.238.27.164/hadoop/lrzsz-0.12.20-19.x86_64.rpm
	wget http://221.238.27.164/hadoop/lzo-2.04-1.el5.rf.x86_64.rpm
	wget http://221.238.27.164/hadoop/lzo-2.06.tar.gz
	wget http://221.238.27.164/hadoop/lzop-1.03.tar.gz

	mkdir $HADOOP/hadoop
	mv *.tar.gz $HADOOP/hadoop
	mv *.rpm $HADOOP/hadoop
	cd $HADOOP/hadoop

	rpm -ivh jdk-6u21-linux-amd64.rpm
	rpm -ivh lrzsz-0.12.20-19.x86_64.rpm
	rpm -ivh lzo-2.04-1.el5.rf.x86_64.rpm
	rpm -ivh hadoop-gpl-packaging-0.2.8-1.x86_64.rpm

	tar xzvf lzo-2.06.tar.gz
	cd lzo-2.06 && ./configure --enable-shared && make && make install
	cp /usr/local/lib/liblzo2.* /usr/lib/
	cd ..

	tar xzvf  lzop-1.03.tar.gz
	cd lzop-1.03
	./configure && make && make install && cd ..

	chown -R hadoop:hadoop  /opt/modules/hadoop/

	cp hadoop-0.20.203.0.tar.gz /opt/modules/hadoop/
	cd /opt/modules/hadoop/ && tar -xzvf hadoop-0.20.203.0.tar.gz
	sed -i "s/^<value>6<\/value>/<value>${map}<\/value>/g" /opt/modules/hadoop/hadoop-0.20.203.0/conf/mapred-site.xml
	sed -i "s/^<value>2<\/value>/<value>${reduce}<\/value>/g" /opt/modules/hadoop/hadoop-0.20.203.0/conf/mapred-site.xml
	sed -i "s/^<value>-Xmx1536M<\/value>/<value>-Xmx${mem}M<\/value>/g" /opt/modules/hadoop/hadoop-0.20.203.0/conf/mapred-site.xml

	chown -R hadoop:hadoop /opt/modules/hadoop/
	chown -R hadoop:hadoop /home/hadoop/

	#sudo -u hadoop /opt/modules/hadoop/hadoop-0.20.203.0/bin/hadoop namenode -format
	sudo -u hadoop /opt/modules/hadoop/hadoop-0.20.203.0/bin/hadoop-daemon.sh start namenode
	sudo -u hadoop /opt/modules/hadoop/hadoop-0.20.203.0/bin/hadoop-daemon.sh start jobtracker

	sudo -u hadoop /opt/modules/hadoop/hadoop-0.20.203.0/bin/hadoop-daemon.sh start datanode
	sudo -u hadoop /opt/modules/hadoop/hadoop-0.20.203.0/bin/hadoop-daemon.sh start tasktracker

fi
curl -# http://221.238.27.164/setup.html?type=setup
