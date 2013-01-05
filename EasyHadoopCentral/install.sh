#!/bin/sh
yum install -y php53 php53-cli php53-devel php53-common httpd httpd-devel php53-mbstring php53-mysql php53-pdo php53-process mysql mysql-devel mysql-server wget lrzsz dos2unix pexpect
service mysqld start
mysql -hlocalhost -uroot -e"create database easyhadoop"
mysql easyhadoop < easyhadoop.sql

echo "/*************************************************************/"
echo "Install basic environment complete, starting download from"
echo "EasyHadoop repositry......"
echo "/*************************************************************/"

mkdir -p ./hadoop

cd ./hadoop && rm -rf *

if [ ! -f "hadoop-1.1.1-1.x86_64.rpm" ]; then
	wget http://113.11.199.230/hadoop/hadoop-1.1.1-1.x86_64.rpm
fi
if [ ! -f "jdk-6u37-linux-x64.rpm" ]; then
	wget http://113.11.199.230/jdk/jdk-6u37-linux-amd64.rpm
fi
if [ ! -f "hadoop-gpl-packaging-0.5.4-1.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.4-1.x86_64.rpm
fi
if [ ! -f "lzo-2.06.tar.gz" ]; then
	wget http://113.11.199.230/resources/lzo-2.06.tar.gz
fi
if [ ! -f "lzop-1.03.tar.gz" ]; then
	wget http://113.11.199.230/resources/lzop-1.03.tar.gz
fi
if [ ! -f "lzo-2.06-1.el5.rf.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm
fi
if [ ! -f "lzo-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm
fi
if [ ! -f "lzo-devel-2.06-1.el5.rf.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm
fi
if [ ! -f "lzo-devel-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm
fi

cd ..

echo "/*************************************************************/"
echo "Download Hadoop runtime libaries install complete."
echo "Do not forget to start your NodeAgent.php"
echo "And access EasyHadoopCentral from your web browser."
echo "/*************************************************************/"