#!/bin/sh
yum install -y php php-cli php-devel php-common httpd httpd-devel php-mbstring php-mysql php-pdo php-process mysql mysql-devel mysql-server mysql-libs wget lrzsz dos2unix pexpect libxml2 libxml2-devel MySQL-python
service mysqld start
mysql -hlocalhost -uroot -e"create database if not exists easyhadoop"
mysql easyhadoop < easyhadoop.sql
mysql easyhadoop < patch-0001.sql
mysql easyhadoop < patch-0002.sql
chmod 777 /var/www/html/expect.py

echo "/*************************************************************/"
echo "Install basic environment complete, starting download from"
echo "EasyHadoop repositry......"
echo "/*************************************************************/"

mkdir -p ./hadoop

cd ./hadoop && rm -rf *

if [ ! -f "hadoop-1.1.1-1.x86_64.rpm" ]; then
	wget http://42.96.141.99/hadoop/hadoop-1.1.1-1.x86_64.rpm
fi
if [ ! -f "jdk-6u39-linux-x64.rpm" ]; then
	wget http://42.96.141.99/jdk/jdk-6u39-linux-amd64.rpm
fi
if [ ! -f "hadoop-gpl-packaging-0.5.4-1.x86_64.rpm" ]; then
	wget http://42.96.141.99/resources/x64/hadoop-gpl-packaging-0.5.4-1.x86_64.rpm
fi
if [ ! -f "lzo-2.06.tar.gz" ]; then
	wget http://42.96.141.99/resources/lzo-2.06.tar.gz
fi
if [ ! -f "lzop-1.03.tar.gz" ]; then
	wget http://42.96.141.99/resources/lzop-1.03.tar.gz
fi
if [ ! -f "lzo-2.06-1.el5.rf.x86_64.rpm" ]; then
	wget http://42.96.141.99/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm
fi
if [ ! -f "lzo-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://42.96.141.99/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm
fi
if [ ! -f "lzo-devel-2.06-1.el5.rf.x86_64.rpm" ]; then
	wget http://42.96.141.99/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm
fi
if [ ! -f "lzo-devel-2.06-1.el6.rfx.x86_64.rpm" ]; then
	wget http://42.96.141.99/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm
fi

cd ..
cp -R * /var/www/html/
cd /var/www/html
python NodeAgent -s start
service httpd start
echo "/*************************************************************/"
echo "Download Hadoop installation and runtime libaries complete."
echo "Do not forget to start your NodeAgent.py"
echo "And access EasyHadoopCentral from your web browser."
echo "/*************************************************************/"