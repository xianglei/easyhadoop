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

os.system('apt-get -y install apache2 apache2-mpm-prefork libapache2-mod-php5 php5 php5-dev php5-cgi php5-cli php5-common php5-mysql mysql-client mysql-server mysql-common python-pexpect automake autoconf make gcc cpp libstdc++6 libstdc++6-4.6-dev libssh2-php')
os.system('mysql -hlocalhost -uroot -e"create database if not exists exadoop"')
os.system('mysql -hlocalhost -uroot --default-character-set=utf8 exadoop < xadoop.sql')

print "/*************************************************************/"
print "Install basic environment complete, starting download from"
print "Exadoop repositry......"
print "/*************************************************************/"

work_dir = os.popen('pwd').readline()[:-1]
tmp_dir = '/tmp'
hadoop_bin_file = 'ubuntu.bin'
agent_file = 'NodeAgent-1.2.1_1-amd64.deb'

os.system('mkdir -p ' + work_dir + '/hadoop')
#os.system('rm -f ./hadoop/*')
if(os.path.isfile(work_dir + '/hadoop/' + hadoop_bin_file)) == False: #hadoop-1.1.2-1
	os.system('wget '+ repo + hadoop_bin_file+' -P '+ work_dir +'/hadoop/')
if(os.path.isfile(work_dir + '/' + agent_file)) == False:
	os.system('wget '+repo+'/agent/stable/' + agent_file)

os.system('cd '+work_dir)
os.system('dpkg -i ' + agent_file)
os.system('cp -R * /var/www')
os.system('rm -f /var/www/index.html')
os.system('echo "service apache2 start" >> /etc/rc.local')
os.system('echo "service mysql start" >> /etc/rc.local')
os.system('chkconfig --add apache2')
os.system('chkconfig --add mysql')
os.system('echo "nohup /usr/local/exadoop/bin/python /usr/local/exadoop/agent/NodeAgent.py -s restart 2>/dev/null &" >> /etc/rc.local')
os.system('service iptables stop')
os.system("sed -i 's/display_errors = On/display_errors = Off/g' /etc/php5/apache2/php.ini")
os.system('sed -i "s/^;date.timezone =/date.timezone = Asia\/Shanghai/g" /etc/php5/apache2/php.ini')
os.system('service apache2 restart')
os.system('service mysql restart')
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
print "Access ExadoopCentral from your web browser."
print "/*************************************************************/"
