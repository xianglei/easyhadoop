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

os.system('apt-get install apache2 apache2-mpm-prefork libapache2-mod-php5 php5 php5-dev php5-cgi php5-cli php5-common php5-mysql mysql-client mysql-server mysql-common python-pexpect')
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
if(os.path.isfile('./hadoop/ubuntu.bin')) == False: #hadoop-1.0.4-1
	os.system('wget '+repo+'ubuntu.bin -P ./hadoop/')
if(os.path.isfile('./NodeAgent_1.2.0-2_amd64.deb')) == False:
	os.system('wget '+repo+'/agent/NodeAgent_1.2.0-2_amd64.deb')

os.system('dpkg -i ./NodeAgent_1.2.0-2_amd64.deb')
os.system('cp -R * /var/www')
os.system('rm -f /var/www/index.html')
os.system('echo "service httpd start" >> /etc/rc.local')
os.system('echo "service mysqld start" >> /etc/rc.local')
os.system('echo "python /usr/local/ehm_agent/NodeAgent.py -s restart" >> /etc/rc.local')
os.system('service iptables stop')
os.system("sed -i 's/display_errors = On/display_errors = Off/g' /etc/php5/apache2/php.ini")
print "/*************************************************************/"
print "Download Hadoop installation and runtime libaries complete."
print "Generate token key..."
token = Token()
t = token.CreateToken()
print "token key complete"
print "Generate /var/www/config.inc.php..."
config_php = ''
config_php += '<?php\n\n'
config_php += '#this is user definable area\n#这里是用户定义区域\n'
config_php += '$configure[\'language\'] = \'english\';\n\n'
config_php += '$configure[\'packages_source_address\'] = \'42.96.141.99\';\n\n'
config_php += '$configure[\'token\'] = \'%s\';\n\n' % t
config_php += '?>'
filename = '/var/www/config.inc.php'
f = open(filename, 'w')
f.write(config_php)
f.close()
print '/var/www/config.inc.php complete'
print "Access EasyHadoopCentral from your web browser."
print "/*************************************************************/"
