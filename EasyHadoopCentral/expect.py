# -*- coding: utf-8 -*-
import pexpect
import sys
from optparse import OptionParser

def ssh(ip, user, passwd, cmd):
	ssh = pexpect.spawn('ssh %s@%s "%s"' % (user, ip, cmd))
	r = ''
	try:
		i = ssh.expect(['password:', 'continue connecting (yes/no)?'], timeout=5)
		#print i
		if i == 0 :
			ssh.sendline(passwd)
		elif i == 1:
			ssh.sendline('yes')
			ssh.expect('password:')
			ssh.sendline(passwd)
	except pexpect.EOF:
		ssh.close()
	else:
		r = ssh.read()
		ssh.expect(pexpect.EOF)
		ssh.close()
	return r

def scp(ip, user, passwd, file = "NodeAgent.py"):
	ssh = pexpect.spawn('scp %s %s@%s:~/ ' % (file, user, ip))
	r= ''
	try:
		i = ssh.expect(['password:', 'continue connecting (yes/no)?'], timeout=5)
		if i == 0:
			ssh.sendline(passwd)
		elif i == 1:
			ssh.senline('yes')
			ssh.expect('password:')
			ssh.sendline(passwd)
	except pexpect.EOF:
		ssh.close()
	else:
		r = ssh.read()
		ssh.expect(pexpect.EOF)
		ssh.close()
	return r

if "__main__" == __name__:
	usage = "usage: %prog -d dest_ip -c command -u user -p passwd -m [ ssh | scp ]"
	parser = OptionParser(usage=usage)
	
	parser.add_option("-m", "--mode", action="store", type="string", dest="mode", default="ssh", help="valid mode is [ ssh | scp ]")
	parser.add_option("-d", "--dest", action="store", type="string", dest="dest", default="127.0.0.1", help="IP address")
	parser.add_option("-c", "--cmd", action="store", type="string", dest="cmd", default="ls ~/", help="linux command")
	parser.add_option("-u", "--user", action="store", type="string", dest="username", default="root", help="Root user")
	parser.add_option("-p", "--pass", action="store", type="string", dest="password", help="ssh password")
	parser.add_option("-f", "--file", action="store", type="string", dest="file", default="NodeAgent.php", help="filename need to scp")
	options, args = parser.parse_args()
	
	
	if len(sys.argv) == 1:
		print 'Type python %s -h or --help for options help.' % sys.argv[0]
	else:
		if options.password == "":
			print 'Must give ssh root password!'
		else:
			if options.mode == 'ssh':
				ssh(options.dest, options.username, options.password, options.cmd)
			elif options.mode == "scp":
				scp(options.dest, options.username, options.password, options.file)
			else:
				print "Unknown mode"