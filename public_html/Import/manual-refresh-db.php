<?php
error_reporting(E_ALL);

require_once('../inc.config.php');
require_once('../inc.bgcrypto.php');

$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
if(mysqli_connect_errno()){ 
	printf("Connect failed: %s\n", mysqli_connect_error());
	die();
}
unset($db);

// Update headlines table.  Need to call with multi_query due to prepared stmt in stored proc.
$callProcSql = 'CALL UpdateHeadlinesTable';
if(!$mySql->multi_query($callProcSql) ){
	printf("Query failed: %s\n", $callProcSql);
	printf("%s: %s\n", $mySql->errno, $mySql->error);
}

$mySql->close();

?>