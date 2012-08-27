<?php

class Mysql
{
	private $mDbHost;
	private $mDbPort;
	private $mDbName;
	private $mDbUser;
	private $mDbPass;
	private $mDbConn;
	private $mResult;
	
	public function __construct()
	{
		$this->mDbHost = MYSQL_HOST;
		$this->mDbUser = MYSQL_USER;
		$this->mDbPass = MYSQL_PASS;
		$this->mDbName = MYSQL_NAME;
		$this->mDbPass = MYSQL_PORT;
		$this->connect();
    }
	
	public function Connect()
	{
		$this->mDbConn = mysql_connect($this->mDbHost.":".$this->mDbPort, $this->mDbUser, $this->mDbPass);
		try
		{
			mysql_select_db($this->mDbName, $this->mDbConn);
		}
		catch (exception $e)
		{
			echo $e;
		}
	}
	
	public function Query($pSql)
	{
		try
		{
			$this->mResult = mysql_query($pSql,$this->mDbConn);
			return $this->mResult;
		}
		catch(exception $e)
		{
			echo $e;
		}
	}
	
	public function FetchArray()
	{
		if($this->mResult)
		{
			retrun mysql_fetch_array($this->mResult);
		}
		else
		{
			return FALSE;
		}
	}
	
	public function Free()
	{
		@mysql_free_result($this->mResult);
	}
	
	public function __destruct()
	{
		if (!empty ($this->mResult))
		{
			$this->Free();
		}
		mysql_close($this->mDbConn);
    }
}

?>