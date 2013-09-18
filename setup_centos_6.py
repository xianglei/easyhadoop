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
		if(os.path.isfile('/usr/local/exadoop/agent/authenticate.key')) == False:
			try:
				file = open('/usr/local/exadoop/agent/authenticate.key', 'wb')
				pickle.dump(self.token, file)
				print "The token is %s , please remember it and write it down." % self.token
				file.close()
				return self.token
			except IOError, e:
				return '{"Exception":"' + e + '"}'
		else:
			try:
				file = open('/usr/local/exadoop/agent/authenticate.key', 'rb')
				self.token = pickle.load(file)
				print "The token is " + self.token + " , please remember it and write it down."
				file.close()
				return self.token
			except IOError, e:
				return '{"Exception":"' + e + '"}'

repo = 'http://42.96.141.99/'

os.system('yum install -y sudo php php-cli php-devel php-common httpd httpd-devel php-mbstring php-mysql php-pdo php-process mysql mysql-devel mysql-server mysql-libs wget lrzsz dos2unix pexpect libxml2 libxml2-devel MySQL-python curl curl-devel libssh2 libssh2-devel automake autoconf make gcc gcc-c++ libstdc++ libstdc++-devel')
os.system('/sbin/service mysqld start')
os.system('mysql -hlocalhost -uroot -e"create database if not exists exadoop"')
os.system('mysql -hlocalhost -uroot --default-character-set=utf8 exadoop < xadoop.sql')

print "/*************************************************************/"
print "Install basic environment complete, starting download from"
print "Exadoop repositry......"
print "/*************************************************************/"

work_dir = os.popen('pwd').readline()[:-1]
tmp_dir = '/tmp'
hadoop_bin_file = 'centos_6.bin'
agent_file = 'NodeAgent-1.2.1-1.el6.x86_64.rpm'

os.system('mkdir -p ' + work_dir + '/hadoop')
#os.system('rm -f ./hadoop/*')
if(os.path.isfile(work_dir + '/hadoop/' + hadoop_bin_file)) == False: #hadoop-1.1.2-1
	os.system('wget '+ repo + hadoop_bin_file+' -P '+ work_dir +'/hadoop/')
if(os.path.isfile(work_dir + '/' + agent_file)) == False:
	os.system('wget '+repo+'/agent/stable/' + agent_file)

os.system('cd '+work_dir)
os.system('rpm -Uvh ' + agent_file)
os.system('cp -R * /var/www/html')
os.system('sed -i "s/^;date.timezone =/date.timezone = Asia\/Shanghai/g" /etc/php.ini')
os.system('/sbin/service httpd restart')
os.system('echo "service httpd restart" >> /etc/rc.local')
os.system('echo "service mysqld restart" >> /etc/rc.local')
os.system('echo "nohup /usr/local/exadoop/bin/python /usr/local/exadoop/agent/NodeAgent.py -s restart 2>/dev/null &" >> /etc/rc.local')
os.system('sed -i "s/^SELINUX=enforcing/SELINUX=disabled/g" /etc/selinux/config')
os.system('/sbin/service iptables stop')
os.system('/sbin/chkconfig --del iptables')
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
print "Access ExadoopCentral from your web browser."
print "/*************************************************************/"
