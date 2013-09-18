#!/usr/bin/php -q
<?
$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/libs/";
include_once $GLOBALS['THRIFT_ROOT'] . 'packages/EasyHadoop/EasyHadoop.php';
include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TTransport.php';
include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';

if($argc < 3)
{
	die("Usage: \nTester.php \"remote_ip:port\" \"command\"\n");
}
else
{
	$ip = $argv[1];
	$command = $argv[2];
	$sock = explode(":", $ip);
	$socket = new TSocket($sock[0], $sock[1]);
	$socket->setSendTimeout(30000);
	$socket->setRecvTimeout(30000);
	$transport = new TBufferedTransport($socket);
	$protocol = new TBinaryProtocol($transport);
	$ehm = new EasyHadoopClient($protocol);
	try
	{
		$transport->open();
		$str = $ehm->RunCommand($command);
		$transport->close();
	}
	catch(Exception $e)
	{
		$str = 'Caught exception: '.  $e->getMessage(). "\n";
	}
}
?>