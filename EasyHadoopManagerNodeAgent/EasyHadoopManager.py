#!/bin/env python
# -*- coding: utf8 -*-

#EasyHadoopManager remote control threading daemon
#Start written by Xianglei at 08/20/2012
#Support with CentOS and RedHat 5.x or 6.x
#under GPLv3 lisence
#modified by []
#Thank you for using easyhadoop

import sys
import socket
import threading
import time
import atexit
import os
import string
import platform

from signal import SIGTERM

QUIT = False

class Install:
	def __init__( self, stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr' ):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr

	def VerifyPlatform( self ):
		tmp = platform.platform()
		if tmp.find("el5") > 0:
			return 5
		elif tmp.find("el6") > 0:
			return 6
		else:
			return False
	##########################
	#Install functions
	##########################
	def InstallEnvironment( self ):
		title = ['Installing Environment...\n']
		tmp = os.popen("yum -y install dialog lrzsz gcc gcc-c++ libstdc++-devel make automake autoconf ntp wget pcre pcre-devel sudo && ntpdate cn.pool.ntp.org").readlines()
		title.extend(tmp)
		return title

	def InstallJava( self ):
		title = ['Installing JDK...\n']
		if os.path.isfile("/home/hadoop/jdk-7u5-linux-x64.rpm") == False:
			tmp = os.popen("/usr/sbin/groupadd hadoop && /usr/sbin/useradd hadoop -g hadoop && mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/jdk/jdk-7u5-linux-x64.rpm && rpm -Uvh jdk-7u5-linux-x64.rpm").readlines()
		else:
			tmp = os.popen("cd /home/hadoop/ && rpm -Uvh jdk-7u5-linux-x64.rpm").readlines()

		os.popen('echo "JAVA_HOME=/usr/java/default" >> /etc/profile && echo "JAVA_HOME=/usr/java/default" >> /root/.bashrc && echo "JAVA_HOME=/usr/java/default" >> /home/hadoop/.bashrc')
		title.extend(tmp)
		return title

	def InstallHadoop( self ):
		title = ['Installing Hadoop...\n']
		if os.path.isfile("/home/hadoop/hadoop-1.0.3-1.x86_64.rpm") == False:
			tmp = os.popen("mkdir -p /home/hadoop/ && cd /home/hadoop/ && wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm").readlines()
		else:
			tmp = os.popen("cd /home/hadoop/ && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm").readlines()
		title.extend(tmp)
		return title
		
	def GetSystemVer( self ):
		system_ver = platform.platform()
		if system_ver.find("el5") > 0:
			title = '5'
		elif system_ver.find("el6") > 0:
			title = '6'
		else:
			title = 'Not CentOS'
		return title

	def InstallLzo( self ):
		title = ['Installing LZO libary...\n']
		if os.path.isfile("/home/hadoop/lzo-2.06.tar.gz") == False:
			tmp = os.popen("mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/lzo-2.06.tar.gz && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install").readlines()
		else:
			tmp = os.popen("cd /home/hadoop/ && tar zxf lzo-2.06.tar.gz && cd lzo-2.06 && ./configure && make && make install").readlines()
		title.extend(tmp)
		system_ver = platform.platform()
		if system_ver.find("el5") > 0:
			if os.path.isfile("/home/hadoop/lzo-2.06-1.el5.rf.x86_64.rpm") == False:
				lzo = os.popen("mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-2.06-1.el5.rf.x86_64.rpm && rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm").readlines()
			else:
				lzo = os.popen("cd /home/hadoop && rpm -Uvh lzo-2.06-1.el5.rf.x86_64.rpm").readlines()

			if os.path.isfile("/home/hadoop/lzo-devel-2.06-1.el5.rf.x86_64.rpm") == False:
				lzo_dev = os.popen("mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el5.rf.x86_64.rpm && rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm").readlines()
			else:
				lzo_dev = os.popen("cd /home/hadoop && rpm -Uvh lzo-devel-2.06-1.el5.rf.x86_64.rpm").readlines()

			title.extend(lzo)
			title.extend(lzo_dev)
		elif system_ver.find("el6") > 0:
			if os.path.isfile("/home/hadoop/lzo-2.06-1.el6.rfx.x86_64.rpm") == False:
				lzo = os.popen("mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/x64/lzo-2.06-1.el6.rfx.x86_64.rpm && rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm").readlines()
			else:
				lzo = os.popen("cd /home/hadoop/ && rpm -Uvh lzo-2.06-1.el6.rfx.x86_64.rpm").readlines()

			if os.path.isfile("/home/hadoop/lzo-devel-2.06-1.el6.rfx.x86_64.rpm") == False:
				lzo_dev = os.popen("mkdir -p /home/hadoop && cd /home/hadoop && wget http://113.11.199.230/resources/x64/lzo-devel-2.06-1.el6.rfx.x86_64.rpm && rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm").readlines()
			else:
				lzo_dev = os.popen("cd /home/hadoop && rpm -Uvh lzo-devel-2.06-1.el6.rfx.x86_64.rpm").readlines()

			title.extend(lzo)
			title.extend(lzo_dev)
		else:
			tmp = ['Unknown Operating System\n']
			title.extend(tmp)

		return title

	def InstallLzop( self ):
		title = ['Installing Lzop...\n']
		if os.path.isfile("/home/hadoop/lzop-1.03.tar.gz") == False:
			tmp = os.popen("mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/lzop-1.03.tar.gz && tar zxf lzop-1.03.tar.gz && cd lzop-1.03 && ./configure && make && make install").readlines()
		else:
			tmp = os.popen("cd /home/hadoop/ && tar zxf lzop-1.03.tar.gz && cd lzop-1.03 && ./configure && make && make install").readlines()
		title.extend(tmp)
		return title

	def InstallHadoopgpl( self ):
		title = ['Installing hadoopgpl...\n']
		if os.path.isfile("/home/hadoop/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm") == False:
			tmp = os.popen("mkdir -p /home/hadoop && cd /home/hadoop/ && wget http://113.11.199.230/resources/x64/hadoop-gpl-packaging-0.5.3-1.x86_64.rpm && rpm -Uvh hadoop-gpl-packaging-0.5.3-1.x86_64.rpm").readlines()
		else:
			tmp = os.popen("cd /home/hadoop/ && rpm -Uvh hadoop-gpl-packaging-0.5.3-1.x86_64.rpm").readlines()
		self.CopyHadoopgplFiles()
		title.extend(tmp)
		return title

	def CopyHadoopgplFiles(self):
		title = ['Installing hadoopgpl...\n']
		tmp = os.popen("cp -rf /opt/hadoopgpl/lib/* /usr/share/hadoop/lib/ && cp -r /opt/hadoopgpl/native /usr/share/hadoop/lib/").readlines()
		title.extend(tmp)
		return title

	def ChangeSudoer( self ):
		os.popen("chmod 644 /etc/sudoers && sed -i 's/Defaults    requiretty/#Defaults    requiretty/g' /etc/sudoers && chmod 440 /etc/sudoers")
		title = ['Make sudoers modify done']
		return title

	def UninstallHadoop( self ):
		title = ['Uninstalling Hadoop']
		rpm = os.popen("rpm -qa | grep hadoop-1.0.3-1").readline().strip()
		if (rpm[0:14] == "hadoop-1.0.3-1"):
			#On CentOS 5 is hadoop-1.0.3-1, On Centos 6 is hadoop-1.0.3-1.x86_64
			tmp = os.popen("rpm -e hadoop-1.0.3-1 && rm -rf /etc/hadoop /usr/share/hadoop").readlines()
		else:
			tmp = ['No Hadoop found']
		title.extend(tmp)
		return title

	def UninstallHadoopgpl( self ):
		title = ['Uninstalling Hadoopgpl']
		rpm = os.popen("rpm -qa | grep hadoop-gpl-packaging-0.5.3-1").readline().strip()
		if (rpm[0:28] == "hadoop-gpl-packaging-0.5.3-1"):
			#On CentOS 5 is hadoop-gpl-packaging-0.5.3-1, On Centos 6 is hadoop-gpl-packaging-0.5.3-1.x86_64
			tmp = os.popen("rpm -e hadoop-gpl-packaging-0.5.3-1 && rm -rf /usr/share/hadoop/lib/cdh4.0.1 /usr/share/hadoop/lib/guava-12.0.jar /usr/share/hadoop/lib/hadoop-lzo-0.4.17.jar /usr/share/hadoop/lib/hadoop-lzo.jar /usr/share/hadoop/lib/pig* /usr/share/hadoop/lib/native /usr/share/hadoop/lib/protobuf-java-2.4.1.jar /usr/share/hadoop/lib/slf4j-api-1.5.8.jar /usr/share/hadoop/lib/slf4j-log4j12-1.5.10.jar /usr/share/hadoop/lib/yamlbeans-0.9.3.jar").readlines()
		else:
			tmp = ['No Hadoopgpl found']
		title.extend(tmp)
		return title

	def UninstallJava( self ):
		title = ['Uninstalling Oracle JDK']
		rpm = os.popen("rpm -qa | grep jdk-1.7.0_05-fcs").readline().strip()
		if (rpm[0:16] == "jdk-1.7.0_05-fcs"):
			#On CentOS 5 is jdk-1.7.0_05-fcs, On Centos 6 is jdk-1.7.0_05-fcs.x86_64
			tmp = os.popen("rpm -e jdk-1.7.0_05-fcs && rm -rf /usr/java").readlines()
		else:
			tmp = ['No Jdk found']
		title.extend(tmp)
		return title
	##########################
	#Start Hadoop Functions
	##########################
	def StartHadoop ( self, type ):
		title = ['Starting Namenode...\n']
		t = type.strip()
		tmp = os.popen("sudo -u hadoop hadoop-daemon.sh start " + t).readlines()
		title.extend(tmp)
		return title
	##########################
	#Stop Hadoop Functions
	##########################
	def StopHadoop ( self, type ):
		title = ['Stopping Namenode...\n']
		t = type.strip()
		tmp = os.popen("sudo -u hadoop hadoop-daemon.sh stop " + t).readlines()
		title.extend(tmp)
		return title
	##########################
	#Format Namenode Function, do not use this
	##########################
	def FormatNamenode ( self ):
		title = ['Formatting Namenode...\n']
		tmp = os.popen("Y|sudo -u hadoop hadoop namenode -format").readlines()
		title.extend(tmp)   
		return title

class ClientThread( threading.Thread ):
	def __init__( self, client_sock ):
		threading.Thread.__init__( self )
		self.client = client_sock

	def run( self ):
		global QUIT
		done = False
		cmd = self.readline()
		install = Install()
		while not done:
			if 'finish' == cmd :
				self.writeline( 'Finish EasyHadoop Manager' )
				QUIT = True
				done = True
				self.client.close()
			elif 'bye' == cmd:
				self.writeline( 'Quit Install Process' )
				done = True
				self.client.close()
			##########################
			#Install Modules
			##########################
			elif 'InstallHadoop' == cmd:
				'''
				Installing Hadoop
				'''
				tmp = install.InstallHadoop()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'GetSystemVer' == cmd:
				'''
				Get system version
				'''
				tmp = install.GetSystemVer()
				self.writeline(tmp)
				self.client.close()
			elif 'InstallEnvironment' == cmd:
				'''
				Installing environment
				'''
				tmp = install.InstallEnvironment()
				for line in tmp:
					self.writeline( line + "\n" )
				tmp = install.ChangeSudoer()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'InstallJava' == cmd:
				'''
				Installing Java JDK
				'''
				tmp = install.InstallJava()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'InstallLzo' == cmd:
				'''
				Installing Lzo libary
				'''
				tmp = install.InstallLzo()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'InstallLzop' == cmd:
				'''
				Installing Lzop libary
				'''
				tmp = install.InstallLzop()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'InstallHadoopgpl' == cmd:
				'''
				Installing hadoopgpl libary
				'''
				tmp = install.InstallHadoopgpl()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			elif 'CopyHadoopgplFiles' == cmd:
				'''
				copy gpl files to /usr/share/hadoop/lib/
				'''
				tmp = install.CopyHadoopgplFiles()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline(cmd + " installed")
				self.client.close()
			##########################
			#Format namenode module, do not use this
			##########################
			elif 'FormatNamenode' == cmd:
				'''
				Format Namenode
				'''
				tmp = install.FormatNamenode()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Namenode formatted")
				self.client.close()
			##########################
			#Start Hadoop Modules
			##########################
			elif 'StartNamenode' == cmd:
				'''
				Starting Namenode
				'''
				tmp = install.StartHadoop("namenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Namenode started")
				self.client.close()
			elif 'StartSecondaryNamenode' == cmd:
				'''
				Starting SecondaryNamenode
				'''
				tmp = install.StartHadoop("secondarynamenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("SecondaryNamenode started")
				self.client.close()
			elif 'StartDatanode' == cmd:
				'''
				Starting Datanode
				'''
				tmp = install.StartHadoop("datanode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Datanode started")
				self.client.close()
			elif 'StartJobtracker' == cmd:
				'''
				Starting Jobtracker
				'''
				tmp = install.StartHadoop("jobtracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Jobtracker started")
				self.client.close()
			elif 'StartTasktracker' == cmd:
				'''
				Starting Tasktracker
				'''
				tmp = install.StartHadoop("tasktracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Tasktracker started")
				self.client.close()
			##########################
			#Stop Hadoop Modules
			##########################
			elif 'StopNamenode' == cmd:
				'''
				Stopping Namenode
				'''
				tmp = install.StopHadoop("namenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Namenode stopped")
				self.client.close()
			elif 'StopSecondaryNamenode' == cmd:
				'''
				Stopping SecondaryNamenode
				'''
				tmp = install.StopHadoop("secondarynamenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("SecondaryNamenode stopped")
				self.client.close()
			elif 'StopDatanode' == cmd:
				'''
				Stopping Datanode
				'''
				tmp = install.StopHadoop("datanode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Datanode stopped")
				self.client.close()
			elif 'StopJobtracker' == cmd:
				'''
				Stopping Jobtracker
				'''
				tmp = install.StopHadoop("jobtracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Jobtracker stopped")
				self.client.close()
			elif 'StopTasktracker' == cmd:
				'''
				Stopping Tasktracker
				'''
				tmp = install.StopHadoop("tasktracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Tasktracker stopped")
				self.client.close()
			##########################
			#Uninstall Hadoop and Java Modules
			##########################
			elif 'UninstallHadoopgpl' == cmd:
				'''
				Uninstalling Hadoopgpl
				'''
				tmp = install.UninstallHadoopgpl()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Uninstall Hadoopgpl finished")
				self.client.close()
			elif 'UninstallHadoop' == cmd:
				'''
				Uninstalling Hadoop
				'''
				tmp = install.UninstallHadoop()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Uninstall Hadoop finished")
				self.client.close()
			elif 'UninstallJava' == cmd:
				'''
				Uninstalling Java
				'''
				tmp = install.UninstallJava()
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Uninstall JDK finished")
				self.client.close()
			##########################
			elif 'FileTransport' == cmd[0:13]:
				'''
				Readdata from socket to update hadoop sets
				Format:  "FileTransport:/etc/hosts\n"
				sleep 1 second to send file content
				'''
				filename = cmd[15:].strip()
				self.FileTransport( filename )
				self.writeline( filename + "Updated")
				self.client.close()
			else:
				self.writeline( "Nop" )
				self.client.close()

			cmd = self.readline()
		self.client.close()
		return

	def FileTransport( self, filename ):
		res = ''
		f = open ( filename , "wb")
		while True:
			res = self.client.recv( 1024 )
			if not res:
				break
			f.write(res)
		f.flush()
		f.close()
		return

	def readline( self ):
		result = self.client.recv( 1024 )
		if( None != result ):
			result = result.strip()
		return result

	def writeline( self, text ):
		try:
			self.client.send( text.strip() + '\n' )
		except Exception, e:
			print e

class Daemon:
	def __init__(self, pidfile, stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr'):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr
		self.pidfile = pidfile

	def _daemonize(self):
		try:
			pid = os.fork()
			if pid > 0:
				sys.exit(0)
		except OSError, e:
			sys.stderr.write('fork #1 failed: %d (%s)\n' % (e.errno, e.strerror))
			sys.exit(1)

		os.chdir("/")
		os.setsid()
		os.umask(0)

		try:
			pid = os.fork()
			if pid > 0:
				sys.exit(0)
		except OSError, e:
			sys.stderr.write('fork #2 failed: %d (%s)\n' % (e.errno, e.strerror))
			sys.exit(1)

		sys.stdout.flush()
		sys.stderr.flush()
		si = file(self.stdin, 'r')
		so = file(self.stdout, 'a+')
		se = file(self.stderr, 'a+', 0)
		os.dup2(si.fileno(), sys.stdin.fileno())
		os.dup2(so.fileno(), sys.stdout.fileno())
		os.dup2(se.fileno(), sys.stderr.fileno())

		atexit.register(self.delpid)
		pid = str(os.getpid())
		file(self.pidfile,'w+').write('%s\n' % pid)
	def delpid(self):
		os.remove(self.pidfile)

	def start(self):
		try:
			pf = file(self.pidfile,'r')
			pid = int(pf.read().strip())
			pf.close()
		except IOError:
			pid = None

		if pid: 
			message = 'pidfile %s already exist. Daemon already running?\n'
			sys.stderr.write(message % self.pidfile)
			sys.exit(1)

		self._daemonize()
		self._run()

	def stop(self):
		try:	
			pf = file(self.pidfile,'r')
			pid = int(pf.read().strip())
			pf.close()
			if os.path.exists(self.pidfile):
				os.remove(self.pidfile)
			else:   
				print str(err)
		except IOError:
			pid = None

		if not pid:
			message = 'pidfile %s does not exist. Daemon not running?\n'
			sys.stderr.write(message % self.pidfile)
			return
		try:
			while 1:
				os.kill(pid, SIGTERM)
				time.sleep(0.1) 
				server = Server()
				server.close()

		except OSError, err:
			err = str(err)
			if err.find('No such process') > 0:
				if os.path.exists(self.pidfile):
					os.remove(self.pidfile)
				else:   
					print str(err)
					sys.exit(1)

	def restart(self):
		self.stop()
		self.start()
	def _run(self):
		while True:
			server = Server()
			server.run()

class Server:
	def __init__( self ):
		self.sock = None
		self.thread_list = []

	def close ( self ):
		del self.sock
		sys.exit(1)

	def run( self ):
		all_good = False
		try_count = 0
		while not all_good:
			if 0 < try_count:
				sys.exit( 1 )
			try:
				self.sock = socket.socket( socket.AF_INET, socket.SOCK_STREAM )
				self.sock.bind( ( '0.0.0.0', 30050 ) )
				self.sock.listen( 5 )
				all_good = True
				break
			except socket.error, err:
				print 'Socket was in used , please make sure the socket number is usable...'
				del self.sock
				time.sleep( 1 )
				try_count += 1

		print "Server is listening for incoming connections...."

		try:
			while not QUIT:
				try:
					self.sock.settimeout( 0.500 )
					client = self.sock.accept()[0]
				except socket.timeout:
					time.sleep( 1 )
					if QUIT:
						print "Received quit command. Shutting down..."
						break
					continue
				new_thread = ClientThread( client )
				print 'Incoming Connection. Started thread ',
				print new_thread.getName()
				self.thread_list.append( new_thread )
				new_thread.start()
				for thread in self.thread_list:
					if not thread.isAlive():
						self.thread_list.remove( thread )
						thread.join()

		except KeyboardInterrupt:
			print 'Ctrl+C pressed... Shutting Down'
		except Exception, err:
			print 'Exception caught: %s\nClosing...' % err
		for thread in self.thread_list:
			thread.join( 1.0 )
		self.sock.close()

if "__main__" == __name__:
	#server = Server()
	#server.run()

	#print "Terminated"
	daemon = Daemon('/var/run/ehm.pid')
	if len(sys.argv) == 2:
		if 'start' == sys.argv[1]:
			daemon.start()
		elif 'stop' == sys.argv[1]:
			daemon.stop()
		elif 'restart' == sys.argv[1]:
			daemon.restart()
		else:   
			print 'Unknown command'
			sys.exit(2)
	else:
		print 'usage: %s start|stop|restart' % sys.argv[0]
		sys.exit(2)