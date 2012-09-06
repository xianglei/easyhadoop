#!/usr/bin/python
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
import subprocess
import string
import platform

from optparse import OptionParser
from signal import SIGTERM

QUIT = False

class Install:
	def __init__( self, stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr' ):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr

	def RunShellScript(self, command):
		a = subprocess.Popen( command, shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE )
		tmp_out = a.stdout.readlines()
		tmp_err = a.stderr.readlines()
		tmp = tmp_out + tmp_err
		return tmp
	
	def CheckFileStatus(self, filename):
		if os.path.isfile( filename ) == False:
			title = True
		else:
			title = False
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
		

class ClientThread( threading.Thread ):
	def __init__( self, client_sock ):
		threading.Thread.__init__( self )
		self.client = client_sock
		
	def decrypt( self, cmd ):
		a = []
		for x in cmd:
			a.append(chr(ord(x)^0x88))
		return ''.join(a)

	def run( self ):
		global QUIT
		done = False
		cmd = self.readline()
		cmd = self.decrypt(cmd)
		install = Install()
		while not done:
			print cmd
			print time.time()
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
				done = True
				self.client.close()
			elif 'RunShellScript' == cmd[0:14]:
				'''
				RunShellScript
				'''
				tmp = install.RunShellScript(cmd[15:])
				for line in tmp:
					self.writeline( line + "\n" )
				#self.writeline("Ok\n")
				done = True
				self.client.close()
				print "client closed\n"
				
			elif 'CheckFileStatus' == cmd[0:15]:
				if install.CheckFileStatus(cmd[16:]) == False:
					self.writeline( "TRUE\n" )
				else:
					self.writeline("FALSE\n")
				done = True
				self.client.close()

			##########################
			elif 'FileTransport' == cmd[0:13]:
				'''
				Readdata from socket to update hadoop sets
				Format:  "FileTransport:/etc/hosts\n"
				sleep 1 second to send file content
				'''
				filename = cmd[14:].strip()
				print filename
				self.FileTransport( filename )
				self.writeline( filename + "Updated")
				done = True
				self.client.close()
			else:
				self.writeline( "Unknown Command: " + cmd )
				done = True
				self.client.close()

			#cmd = self.readline()
			#done = True
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
	def __init__(self, pidfile, validIP, stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr'):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr
		self.pidfile = pidfile
		self.validIP = validIP

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
				server = Server(self.validIP)
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
		time.sleep(1)
		self.start()
	def _run(self):
		while True:
			server = Server(self.validIP)
			server.run()

class Server:
	def __init__( self, validIP ):
		self.sock = None
		self.thread_list = []
		self.validIP = validIP

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
				self.sock.bind( ( self.validIP, 30050 ) )
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
					client,addr = self.sock.accept()
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
	usage = "usage: %prog -a 192.168.1.1 -s start"
	parser = OptionParser(usage=usage)

	parser.add_option("-a", "--address", action="store", type="string", dest="address", default="0.0.0.0", help="The IP address of this machine which is used to bind with, if not given, use 0.0.0.0")
	parser.add_option("-s", "--signal", action="store", type="string", dest="signal", help="valid signal is [ start | stop | reload ]")
	options, args = parser.parse_args()
	
	
	if len(sys.argv) == 1:
		print 'Type python %s -h or --help for options help.' % sys.argv[0]
	else:
		if options.signal == "":
			print 'Must give -s option\'s value'
		else:
			if 'start' == options.signal:
				daemon = Daemon('/var/run/ehm.pid', options.address)
				daemon.start()
			elif 'stop' == options.signal:
				daemon = Daemon('/var/run/ehm.pid', options.address)
				daemon.stop()
			elif 'restart' == options.signal:
				daemon = Daemon('/var/run/ehm.pid', options.address)
				daemon.restart()
			else:   
				print 'Unknown command'
				sys.exit(2)
	#else:
	#	print 'usage: %s bind_ip start|stop|restart' % sys.argv[0]
	#	sys.exit(2)