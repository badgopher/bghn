<?php
require_once('inc.config.php');
require_once('inc.bgcrypto.php');

$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
if(mysqli_connect_errno()){ 
	printf("Connect failed: %s\n", mysqli_connect_error());
	die();
}
unset($db);

$mySql->query('DELETE FROM items WHERE importTime < DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND feedId IN ( SELECT feedId FROM feeds WHERE purgeFlag = 1 );');
printf("Items removed: %d", $mySql->affected_rows);

$mySql->query('OPTIMIZE TABLE items;');

$mySql->close();

//DELETE FROM items WHERE importTime < DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND feedId IN ( SELECT feedId FROM feeds WHERE purgeFlag = 1 );
//OPTIMIZE TABLE items;

?>