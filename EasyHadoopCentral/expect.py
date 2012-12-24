# -*- coding: utf-8 -*-
import pexpect
from optparse import OptionParser

def ssh_cmd(ip, user, passwd, cmd):
	ssh = pexpect.spawn('ssh %s@%s "%s"' % (user, ip, cmd))
	r = ''
	try:
		i = ssh.expect(['password:', 'continue connecting (yes/no)?'], timeout=5)
		#print i
		if i == 0 :
			ssh.sendline(passwd)
		elif i == 1:
			ssh.sendline('yes\n')
			ssh.expect('password:')
			ssh.sendline(passwd)
	except pexpect.EOF:
		ssh.close()
	else:
		r = ssh.read()
		ssh.expect(pexpect.EOF)
		ssh.close()
	return r

hosts = '''
192.168.1.50:xianglei:v*\ZmpnrjuR'*a5lj0ea:df -h,uptime
192.168.1.51:xianglei:v*\ZmpnrjuR'*a5lj0ea:df -h,uptime
'''

for host in hosts.split("\n"):
	if host:
		ip, user, passwd, cmds = host.split(":")
		for cmd in cmds.split(","):
			print "-- %s run:%s --" % (ip, cmd)
			print ssh_cmd(ip, user, passwd, cmd)