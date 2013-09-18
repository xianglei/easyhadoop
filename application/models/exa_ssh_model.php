<?php
//This class do not need php ssh pecl support
/*
*/
class Exa_ssh_model extends CI_Model
{
	private $ssh_host;
	private $ssh_port;
	private $ssh_user;
	private $ssh_pass;
	private $ssh_conn = null;
	private $ssh_type = 'linux';
	private $ssh_shell = null;
	private $ssh_pub_key;
	private $ssh_priv_key;
	
	public function __construct()
	{
		parent::__construct();
		$GLOBALS['SSH_ROOT'] = __DIR__ . "/../../libs/ssh/";
		include_once $GLOBALS['SSH_ROOT'] . 'Net/SSH2.php';
		include_once $GLOBALS['SSH_ROOT'] . 'Net/SCP.php';
	}
	
	public function Connect($pHost, $pPort = 22)
	{
		$this->ssh_conn = new Net_SSH2($pHost, $pPort);
	}
	
	public function AuthPassword($pUser, $pPassword)
	{
		if (!$this->ssh_conn->login($pUser, $pPassword)) {
			exit('Login Failed');
		}
	}
	
	public function Execute()
	{
		$argc = func_num_args();
		$argv = func_get_args();
		$cmd = '';
		for($i = 0; $i < $argc; $i++)
		{
			if($i != ($argc - 1))
			{
				$cmd .= $argv[$i]." && ";
			}
			else
			{
				$cmd .= $argv[$i];
			}
		}
		
		$stdout = $this->ssh_conn->exec($cmd);
		return $stdout;
	}
	
	public function SCP($pDest, $pContent)// absolute path to file
	{
		$scp = new Net_SCP($this->ssh_conn);
		$scp->put($pDest, $pContent);
	}
}
?>