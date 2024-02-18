<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 0);

require_once('inc.bgparser.php');
require_once('../inc.config.php');
require_once('../inc.bgcrypto.php');

$BGParser = new BGParser();

$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
if(mysqli_connect_errno()){ 
	printf("Connect failed: %s\n", mysqli_connect_error());
	die();
}
unset($db);

$selectSql = "SELECT f.feedId, f.feedRssUrl FROM feeds f WHERE f.activeFlag = 1;";

if(!$result = $mySql->query($selectSql)){
	printf("Query failed: %s\n", $selectSql);
	die();
} else {

	while($feed = $result->fetch_assoc()){
		$BGParser->processFeed($feed['feedId'], $feed['feedRssUrl']);
	}
}

$result->close();

// Update headlines table.  Need to call with multi_query due to prepared stmt in stored proc.
$callProcSql = 'CALL UpdateHeadlinesTable';
if(!$mySql->multi_query($callProcSql) ){
	printf("Query failed: %s\n", $callProcSql);
}

$mySql->close();

?>