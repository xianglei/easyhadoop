#!/usr/bin/python
#conding:utf-8
import socket,fcntl,struct
import os
def get_ip_address(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    return socket.inet_ntoa(fcntl.ioctl(
                s.fileno(),
                0x8915, # SIOCGIFADDR
                struct.pack('256s', ifname[:15])
                )[20:24])
name="node-"+get_ip_address('eth0').split(".",2)[2].replace(".","-")
os.system("/bin/hostname "+name)
oldname=socket.gethostname()
cmd="sed -i \"s/%s/%s/g\"  /etc/sysconfig/network"%(oldname,name)
os.system(cmd)
cmd="python /usr/local/ehm_agent/NodeAgent.py  -s restart"
os.system(cmd)