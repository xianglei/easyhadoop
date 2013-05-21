#!/usr/bin/python
# -*- coding: utf8 -*-
import os
import pickle
import random

class Token:
	def __init__(self):
		self.token = ''
	
	def CreateToken(self):
		self.token = str(random.randint(2000000000, 9000000000));
		if(os.path.isfile('/usr/local/ehm_agent/authenticate.key')) == False:
			try:
				file = open('/usr/local/ehm_agent/authenticate.key', 'wb')
				pickle.dump(self.token, file)
				print "The token is %s , please remember it and write it down." % self.token
				file.close()
				return self.token
			except IOError, e:
				return '{"Exception":"' + e + '"}'
		else:
			try:
				file = open('/usr/local/ehm_agent/authenticate.key', 'rb')
				self.token = pickle.load(file)
				print "The token is " + self.token + " , please remember it and write it down."
				file.close()
				return self.token
			except IOError, e:
				return '{"Exception":"' + e + '"}'

repo = 'http://42.96.141.99/'

os.system('yum install -y php php-cli php-devel php-common httpd httpd-devel php-mbstring php-mysql php-pdo php-process mysql mysql-devel mysql-server mysql-libs wget lrzsz dos2unix pexpect libxml2 libxml2-devel MySQL-python curl curl-devel')
os.system('service mysqld start')
os.system('mysql -hlocalhost -uroot -e"create database if not exists easyhadoop"')
os.system('mysql -hlocalhost -uroot easyhadoop < easyhadoop.sql')
os.system('mysql -hlocalhost -uroot easyhadoop < patch-0001.sql')
os.system('mysql -hlocalhost -uroot easyhadoop < patch-0002.sql')
os.system('mysql -hlocalhost -uroot easyhadoop < patch-0003.sql')

print "/*************************************************************/"
print "Install basic environment complete, starting download from"
print "EasyHadoop repositry......"
print "/*************************************************************/"

os.system('mkdir -p ./hadoop')
#os.system('rm -f ./hadoop/*')
if(os.path.isfile('./hadoop/hadoop-1.1.2-1.x86_64.rpm')) == False: #hadoop-1.0.4-1
	os.system('wget '+repo+'hadoop/hadoop-1.1.2-1.x86_64.rpm -P ./hadoop/')
	
if(os.path.isfile('./hadoop/jdk-6u45-linux-amd64.rpm')) == False: #jdk-6u39
	os.system('wget '+repo+'jdk/jdk-6u45-linux-amd64.rpm -P ./hadoop/')
	
if(os.path.isfile('./hadoop/hadoop-gpl-packaging-0.6.1-1.x86_64.rpm')) == False:
	os.system('wget '+repo+'resources/x64/hadoop-gpl-packaging-0.6.1-1.x86_64.rpm -P ./hadoop/')
	
if(os.path.isfile('./hadoop/lzo-2.06.tar.gz')) == False:
	os.system('wget '+repo+'resources/lzo-2.06.tar.gz -P ./hadoop/')
	
if(os.path.isfile('./hadoop/lzop-1.03.tar.gz')) == False:
	os.system('wget '+repo+'resources/lzop-1.03.tar.gz -P ./hadoop/')
	
if(os.path.isfile('./hadoop/lzo-2.06-1.el6.rfx.x86_64.rpm')) == False:
	os.system('wget '+repo+'resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm -P ./hadoop/')
	
if(os.path.isfile('./hadoop/lzo-devel-2.06-1.el6.rfx.x86_64.rpm')) == False:
	os.system('wget '+repo+'resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm -P ./hadoop/')
	
os.system('rpm -Uvh ./NodeAgent-1.2.0-1.x86_64.rpm')
os.system('cp -R * /var/www/html')
os.system('service httpd start')
print "/*************************************************************/"
print "Download Hadoop installation and runtime libaries complete."
print "Generate token key..."
token = Token()
t = token.CreateToken()
print "token key complete"
print "Generate /var/www/html/config.inc.php..."
config_php = ''
config_php += '<?php\n\n'
config_php += '#this is user definable area\n#这里是用户定义区域\n'
config_php += '$configure[\'language\'] = \'english\';\n\n'
config_php += '$configure[\'packages_source_address\'] = \'42.96.141.99\';\n\n'
config_php += '$configure[\'token\'] = \'%s\';\n\n' % t
config_php += '?>'
filename = '/var/www/html/config.inc.php'
f = open(filename, 'w')
f.write(config_php)
f.close()
print '/var/www/html/config.inc.php complete'
print 'Starting agent...'
os.system('python /usr/local/ehm_agent/NodeAgent.py -s start')
print "Access EasyHadoopCentral from your web browser."
print "/*************************************************************/"
