#!/bin/sh

yum -y install wget

mkdir -p ./hadoop
cd ./hadoop && rm -rf *
wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm
wget http://113.11.199.230/jdk/jdk-7u5-linux-x64.rpm
wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm
wget http://113.11.199.230/resources/lzo-2.06.tar.gz
wget http://113.11.199.230/resources/lzop-1.03.tar.gz
wget http://113.11.199.230/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm
wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm
wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm
wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm

cd ..

echo "Download Complete, You can use PushFiles now\n"
