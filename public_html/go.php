<?php
require_once('inc.bgcom.php');

$BGCom = new BGCom();

if( ( (strtolower(substr($_GET['id'], 0, 2)) == 'bg') || (strtolower(substr($_GET['id'], 0, 2)) == 'bh')) && (strlen($_GET['id']) == 6) ){
	$id = $BGCom->base36ToInt($_GET['id']);
	$itemInfo = $BGCom->getItemInfo($id);
} else { // Someone's feeding garbage
	$BGCom->httpRedirect('/?__Invalid_ID');
	die();
}

if($itemInfo['itemLink'] == ''){
	$BGCom->httpRedirect('/?__Invalid_Link');
	die();
} else {
	$BGCom->httpRedirect($itemInfo['itemLink']);
	die();
}

?>