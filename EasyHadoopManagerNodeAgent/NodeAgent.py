#!/usr/bin/python
# -*- coding: utf8 -*-

#EasyHadoopManager remote control threading daemon
#Start written by Xianglei at 08/20/2012
#Support with CentOS and RedHat 5.x or 6.x
#under GPLv3 lisence
#modified by []
#Thank you for using easyhadoop

import atexit
import subprocess
import string
import platform
import os
import sys
import time
from optparse import OptionParser
from signal import SIGTERM

sys.path.append('./thrift')

from thrift.EasyHadoop.EasyHadoop import *
from thrift.EasyHadoop.EasyHadoop.ttypes import *

from thrift.transport import TSocket
from thrift.transport import TTransport
from thrift.protocol import TBinaryProtocol
from thrift.server import TServer

class EasyHadoopHandler:
	def RunCommand(self, command):
		try:
			a = subprocess.Popen( command, shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE )
			tmp_out = a.stdout.readlines()
			tmp_err = a.stderr.readlines()
			tmp = tmp_out + tmp_err
			tmp = "".join(tmp)
		except OSError,e:
			tmp = "Cannot run command, Exception:"+e+os.linesep
		return tmp

	def FileTransfer(self, filename, content):
		filename = filename
		content = content
		errstr = ''
		try:
			f = open(filename,"wb")
			try:
				f.write(content)
			except IOError,e:
				errstr = "Cannot write file:"+filename+". Exception:"+e+os.linesep
			f.close()
			errstr = "OK"
		except IOError,e:
			errstr = "Cannot open file:"+filename+". Exception:"+e+os.linesep
		return errstr

	def FileExists(self, filename):
		if os.path.isfile(filename):
			return True
		else:
			return False

	def GetSysVer(self):
		system_ver = platform.platform()
		if system_ver.find("el5") > 0:
			title = '5'
		elif system_ver.find("el6") > 0:
			title = '6'
		else:
			title = 'Not CentOS'
		return title
		
class Daemon:
	def __init__(self, pidfile, host, port, stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr'):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr
		self.pidfile = pidfile
		self.host = host
		self.port = port

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

		print os.linesep+"Start at process:"+pid+os.linesep

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
			#while 1:
				os.kill(pid, SIGTERM)
				time.sleep(0.1)

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
		handler = EasyHadoopHandler()
		processor = EasyHadoop.Processor(handler)
		transport = TSocket.TServerSocket(self.host,self.port)
		tfactory = TTransport.TBufferedTransportFactory()
		pfactory = TBinaryProtocol.TBinaryProtocolFactory()

		server = TServer.TThreadedServer(processor, transport, tfactory, pfactory)
		while True:
			print 'Starting server'+os.linesep
			server.serve()

if "__main__" == __name__:
	#server = Server()
	#server.run()

	#print "Terminated"
	usage = "usage: %prog -b 192.168.1.1 -s start"
	parser = OptionParser(usage=usage)

	parser.add_option("-b", "--bind", action="store", type="string", dest="bind", default="0.0.0.0", help="The IP address of this machine which is used to bind with, if not given, use 0.0.0.0")
	parser.add_option("-s", "--signal", action="store", type="string", dest="signal", help="valid signal is [ start | stop | restart ]")
	options, args = parser.parse_args()
	
	
	if len(sys.argv) == 1:
		print 'Type python %s -h or --help for options help.' % sys.argv[0]
	else:
		if options.signal == "":
			print 'Must give -s option\'s value'
		else:
			daemon = Daemon('/var/run/ehm.pid', options.bind, 30050)
			if 'start' == options.signal:
				daemon.start()
			elif 'stop' == options.signal:
				daemon.stop()
			elif 'restart' == options.signal:
				daemon.restart()
			else:   
				print 'Unknown command'
				sys.exit(2)
