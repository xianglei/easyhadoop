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

	def RunShellScript(self, command):
		print command
		tmp_in,tmp_out,tmp_err = os.popen3( command )
		tmp_out = tmp_out.readlines()
		tmp_err = tmp_err.readlines()
		tmp_out = tmp_out.extend(tmp_err)
		tmp = tmp_out
		return tmp
	
	def CheckFileStatus(self, filename):
		if os.path.isfile( filename ) == False:
			title = ["FALSE"]
		else:
			title = ["TRUE"];
			return title

	#def InstallHadoop( self ):
	#	title = ['Installing Hadoop...\n']
	#	if os.path.isfile("/home/hadoop/hadoop-1.0.3-1.x86_64.rpm") == False:
	#		tmp = os.popen("mkdir -p /home/hadoop/ && cd /home/hadoop/ && wget http://113.11.199.230/hadoop/hadoop-1.0.3-1.x86_64.rpm && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm").readlines()
	#	else:
	#		tmp = os.popen("cd /home/hadoop/ && rpm -Uvh hadoop-1.0.3-1.x86_64.rpm").readlines()
	#	title.extend(tmp)
	#	return title
		
	def GetSystemVer( self ):
		system_ver = platform.platform()
		if system_ver.find("el5") > 0:
			title = '5'
		elif system_ver.find("el6") > 0:
			title = '6'
		else:
			title = 'Not CentOS'
		return title

	##########################
	#Start Hadoop Functions
	##########################
	def StartHadoop ( self, type ):
		t = type.strip()
		title = ['Starting '+ t +'...\n']
		tmp = os.popen("sudo -u hadoop hadoop-daemon.sh start " + t).readlines()
		title.extend(tmp)
		return title
	##########################
	#Restart Hadoop Functions
	##########################
	def RestartHadoop ( self, type ):
		t = type.strip()
		title = ['Starting '+ t +'...\n']
		tmp = os.popen("sudo -u hadoop hadoop-daemon.sh stop " + t).readlines()
		time.sleep(1)
		tmp = os.popen("sudo -u hadoop hadoop-daemon.sh start " + t).readlines()
		title.extend(tmp)
		return title
	##########################
	#Stop Hadoop Functions
	##########################
	def StopHadoop ( self, type ):
		t = type.strip()
		title = ['Starting '+ t +'...\n']
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
	##########################
	#Tail Logs
	##########################
	def TailLogs (self, type, hostname):
		title = ['Cat hadoop logs...\n']
		tmp = os.popen("tail -n 1000 /var/log/hadoop/hadoop/hadoop-hadoop-"+type+"-"+hostname+".log")
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
			elif 'GetSystemVer' == cmd:
				'''
				Get system version
				'''
				tmp = install.GetSystemVer()
				self.writeline(tmp)
				self.client.close()
			elif 'RunShellScript' == cmd[0:14]:
				'''
				RunShellScript
				'''
				tmp = install.RunShellScript(cmd[15:])
				for line in tmp:
					self.writeline( line + "\n" )
				self.client.close()
				
			elif 'CheckFileStatus' == cmd[0:15]:
				tmp = install.CheckFileStatus(cmd[16:])
				for line in tmp:
					self.writeline( line + "\n"	)
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
			#Cat Hadoop Logs
			##########################
			elif 'ViewLogs' == cmd[0:8]:
				'''
				Cat hadoop Logs
				Command is TailLogs:datanode:hostname
				'''
				str = cmd.split(":")
				type = str[1]
				hostname = str[2]
				tmp = install.TailLogs(type, hostname)
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Namenode stopped")
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
			#Restart Hadoop Modules
			##########################
			elif 'RestartNamenode' == cmd:
				'''
				Restarting Namenode
				'''
				tmp = install.RestartHadoop("namenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Namenode stopped")
				self.client.close()
			elif 'RestartSecondaryNamenode' == cmd:
				'''
				Restarting SecondaryNamenode
				'''
				tmp = install.RestartHadoop("secondarynamenode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("SecondaryNamenode stopped")
				self.client.close()
			elif 'RestartDatanode' == cmd:
				'''
				Restarting Datanode
				'''
				tmp = install.RestartHadoop("datanode")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Datanode stopped")
				self.client.close()
			elif 'RestartJobtracker' == cmd:
				'''
				Restarting Jobtracker
				'''
				tmp = install.RestartHadoop("jobtracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Jobtracker stopped")
				self.client.close()
			elif 'RestartTasktracker' == cmd:
				'''
				Restarting Tasktracker
				'''
				tmp = install.RestartHadoop("tasktracker")
				for line in tmp:
					self.writeline( line + "\n" )
				self.writeline("Tasktracker stopped")
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
				self.writeline( "Unknown Command: " + cmd )
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