#!/usr/bin/python
# -*- coding: utf8 -*-
'''
EasyHadoop Agent written by xianglei
Under GPLv3 License

  This agent will listen socket port 30050(Thrift RPC) for run shell command
and transfer hadoop file,
and port 30051(CherryPy embedded webserver) for get system info and status

Usage:
  python Agent.py -s start -b your_ip
Example:
  python Agent.py -s start -b 192.168.1.2
or
  python Agent.py -s start

when start work with EasyHadoopCantral, will create two static file in
/usr/local/exadoop/agent/authenticate.key	token file
/usr/local/exadoop/agent/agent.pid		self running process id

url /node/dist/token
url /node/GetCpuInfo/token
url /node/GetMemInfo/token
url /node/GetLoadAvg/token
url /node/GetNetTraffic/token
url /node/GetHddInfo/token
url /node/GetCpuDetail/token
url /node/GetRolePID/token
url /jmx/GetJmx/token/ip_addr/port/query_string

/var/cache/yum/base/packages (centos)
/var/cache/apt/archives (ubuntu,debian)
Must create local token authenticate.key file when first install with install.py
'''
import sys
import cherrypy
import platform
import os
try:
	import cPickle as pickle
except:
	import pickle
import urllib
import socket
import atexit
import subprocess
import string
import time
import base64
import psutil

import json

#sys.path.append('./thrift')
from Exadoop import *
from Exadoop.ttypes import *
from thrift.Thrift import *
from thrift.transport import TSocket
from thrift.transport import TTransport
from thrift.protocol import TBinaryProtocol
from thrift.server import TServer

from optparse import OptionParser
from signal import SIGTERM

socket.setdefaulttimeout(5.0)

'''

cherry py classes

'''
class Index(object):
	@cherrypy.expose
	def index(self):
		return "Smell to yellow elephant :)"

class Node(object):
	'''
	url /node/dist/token
	'''
	@cherrypy.expose
	def dist(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			dist_json = ''
			sysinstaller = ''
			installer = ''
			ostype = platform.dist()
			if(ostype[0] in ['Ubuntu','debian','ubuntu','Debian']):
				sysinstaller = 'apt-get'
				installer = 'dpkg'
			elif(ostype[0] in ['SuSE', 'suse']):
				sysinstaller = 'zypper'
				installer = 'rpm'
			elif(ostype[0] in ['CentOS', 'centos', 'redhat','RedHat']):
				sysinstaller = 'yum'
				installer = 'rpm'

			machine = platform.machine()
			hostname = platform.node()
			
			dist_json = {'os.system':ostype[0], 'os.version':ostype[1], 'os.release':ostype[2], 'os.sysinstall':sysinstaller, 'os.installer':installer, 'os.arch':machine, 'os.hostname':hostname}
			return json.dumps(dist_json, sort_keys=False, indent=4, separators=(',', ': ')) 
			#return dist_json
		else:
			return '{"Exception":"Invalid token"}'
	'''
	url /node/GetCpuInfo/token
	'''
	@cherrypy.expose
	def GetCpuInfo(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			cpu = []
			cpuinfo = {}
			f = open("/proc/cpuinfo")
			lines = f.readlines()
			f.close()
			for line in lines:
				if line == 'n':
					cpu.append(cpuinfo)
					cpuinfo = {}
				if len(line) < 2: continue
				name = line.split(':')[0].strip().replace(' ','_')
				var = line.split(':')[1].strip()
				cpuinfo[name] = var
			return json.dumps(cpuinfo, sort_keys=False, indent=4, separators=(',', ': '))
		else:
			return '{"Exception":"Invalid token"}'
	'''
	url /node/GetMemInfo/token
	'''
	@cherrypy.expose
	def GetMemInfo(self, token):
		tokenizer = Token()
                if tokenizer.AuthToken(token) == True:
			mem = {}
			f = open("/proc/meminfo")
			lines = f.readlines()
			f.close()
			for line in lines:
				if len(line) < 2:
				       continue
				name = line.split(':')[0]
				var = line.split(':')[1].split()[0]
				mem[name] = long(var) * 1024.0
			mem['MemUsed'] = mem['MemTotal'] - mem['MemFree'] - mem['Buffers'] - mem['Cached']
			return json.dumps(mem, sort_keys=False, indent=4, separators=(',', ': '))
		else:
			return '{"Exception":"Invalid token"}'
	'''
	url /node/GetLoadAvg/token
	'''
	@cherrypy.expose
	def GetLoadAvg(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			loadavg = {} 
			f = open("/proc/loadavg") 
			con = f.read().split() 
			f.close() 
			loadavg['lavg_1']=con[0] 
			loadavg['lavg_5']=con[1] 
			loadavg['lavg_15']=con[2] 
			loadavg['nr']=con[3] 
			loadavg['last_pid']=con[4] 
			return json.dumps(loadavg, sort_keys=False, indent=4, separators=(',', ': '))
		else:
			return '{"Exception":"Invalid token"}'

	@cherrypy.expose
	def GetNetTraffic(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			net_before = psutil.net_io_counters()
			time.sleep(0.1)
			net_after = psutil.net_io_counters()
			#print net_before
			#print net_after
			net = {}
			net['bytes_sent'] = (net_after.bytes_sent - net_before.bytes_sent)*10
			net['bytes_recv'] = (net_after.bytes_recv - net_before.bytes_recv)*10
			net['packets_sent'] = (net_after.packets_sent - net_before.packets_sent)*10
			net['packets_recv'] = (net_after.packets_recv - net_before.packets_recv) * 10
			return json.dumps(net)
		else:
			return '{"Exception":"Invalid token"}'

	@cherrypy.expose
	def GetHddInfo(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			hdds = []
			mount = {}
			file_system = []
			type = []
			size = []
			used = []
			avail = []
			used_percent = []
			mounted_on = []
			opts = []
			hdds = psutil.disk_partitions()
			for line in hdds:
				file_system.append(line[0].replace('\\n',''))
				type.append(line[2].replace('\\n',''))
				mounted_on.append(line[1].replace('\\n',''))
				opts.append(line[3].replace('\\n',''))
				
				size.append(psutil.disk_usage(line[1]).total)
				used.append(psutil.disk_usage(line[1]).used)
				avail.append(psutil.disk_usage(line[1]).free)
				used_percent.append(psutil.disk_usage(line[1]).percent)
				
			mount['file_system'] = file_system
			mount['type'] = type
			mount['size'] = size
			mount['used'] = used
			mount['avail'] = avail
			mount['used_percent'] = used_percent
			mount['mounted_on'] = mounted_on
			mount['opts'] = opts
			dist_json = json.dumps(mount)
			return dist_json
		else:
			return '{"Exception":"Invalid token"}'
	
	@cherrypy.expose
	def GetCpuDetail(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			cpu = {}
			user = psutil.cpu_times_percent().user
			system = psutil.cpu_times_percent().system
			iowait = psutil.cpu_times_percent().iowait
			idle = 100 - user - system - iowait

			cpu['user'] = user
			cpu['system'] = system
			cpu['iowait'] = iowait
			cpu['idle'] = idle
			cpu = json.dumps(cpu)
			return cpu
		else:
			return '{"Exception":"Invalid token"}'
	
	@cherrypy.expose
	def GetRolePID(self, token, role):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			cmd = 'ps aux | grep proc_' + role + ' | grep -v grep | awk \'{print $2}\''
			rolepid = os.popen(cmd).readline().strip()
			pid = {}
			if rolepid != '':
				pid['role'] = role
				pid['pid'] = rolepid
				pid['status'] = 'online'
			else:
				pid['role'] = role
				pid['pid'] = 0
				pid['status'] = 'offline'
			return json.dumps(pid)
		else:
			return '{"Exception":"Invalid token"}'
	
	@cherrypy.expose
	def CheckAlive(self, token):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			return '{"status":"alive"}'
		else:
			return '{"Exception":"Invalid token"}'

'''

Get hadoop node jmx for monitor

'''

class Jmx(object):
	'''
	url /jmx/GetJmx/token/ip_addr/port/query_string
	'''
	@cherrypy.expose
	def GetJmx(self, token , host, port, qry = ''):
		tokenizer = Token()
		if tokenizer.AuthToken(token) == True:
			url = 'http://'+host+':'+port+'/jmx?qry='+qry;
			jmx = urllib.urlopen(url)
			jmx_json = jmx.read().replace('\n','')
			jmx.close()
			return jmx_json
		else:
			return '{"Exception":"Invalid token"}'

'''
token is generated by central with a random sha1 hash when first install
and saved with pickle format
'''

class Token(object):
	def AuthToken(self, token):
		if(os.path.isfile('/usr/local/exadoop/agent/authenticate.key')) == False:
			return self.CreateToken(token)
		else:
			try:
				file = open('/usr/local/exadoop/agent/authenticate.key', 'rb')
				tokenizer = pickle.load(file)
				file.close()
				if token == tokenizer:
					return True
				else:
					return False
			except IOError,e:
				return '{"Exception":"' + e + '"}'
	@cherrypy.expose
	def CreateToken(self, token):
		if(os.path.isfile('/usr/local/exadoop/agent/authenticate.key')) == False:
			try:
				file = open('/usr/local/exadoop/agent/authenticate.key', 'wb')
				pickle.dump(token, file)
				file.close()
				return True
			except IOError, e:
				return '{"Exception":"' + e + '"}'
		else:
			return '{"Exception":"token exists"}'

'''

Thrift handler

'''
class ExadoopHandler:
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

'''

Daemon class

'''

class Daemon:
	def __init__(self, pidfile = '/usr/local/exadoop/agent/agent.pid', host = '0.0.0.0', stdin='/dev/stdin', stdout='/dev/stdout', stderr='/dev/stderr'):
		self.stdin = stdin
		self.stdout = stdout
		self.stderr = stderr
		self.pidfile = pidfile
		self.host = host
		self.port = 30050
		self.settings = { 
				'global': {
					'server.socket_port' : 30051,
					'server.socket_host': self.host,
					'server.socket_file': '',
					'server.socket_queue_size': 100,
					'server.protocol_version': 'HTTP/1.1',
					'server.log_to_screen': True,
					'server.log_file': '',
					'server.reverse_dns': False,
					'server.thread_pool': 200,
					'server.environment': 'production',
					'engine.timeout_monitor.on': False
				}
		}

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
			cherrypy.engine.stop()
			cherrypy.engine.exit()
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
		handler = ExadoopHandler()
		processor = Exadoop.Processor(handler)
		transport = TSocket.TServerSocket(self.host,self.port)
		tfactory = TTransport.TBufferedTransportFactory()
		pfactory = TBinaryProtocol.TBinaryProtocolFactory()

		server = TServer.TForkingServer(processor, transport, tfactory, pfactory)

		cherrypy.config.update(self.settings)
		cherrypy.tree.mount(Index(), '/')
		cherrypy.tree.mount(Node(), '/node')
		cherrypy.tree.mount(Jmx(), '/jmx')
		cherrypy.tree.mount(Token(), '/token')
		cherrypy.tree.mount(Sudo(), '/sudo')
		#cherrypy.tree.mount(Expect(), '/expect')
		cherrypy.engine.start()

		server.serve()

if "__main__" == __name__:
	cherrypy.engine.autoreload.stop()
	cherrypy.engine.autoreload.unsubscribe()
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
			daemon = Daemon('/usr/local/exadoop/agent/agent.pid', options.bind)
			if 'start' == options.signal:
				daemon.start()
			elif 'stop' == options.signal:
				daemon.stop()
			elif 'restart' == options.signal:
				daemon.restart()
			else:   
				print 'Unknown command'
				sys.exit(2)
