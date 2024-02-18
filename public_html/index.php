<?php
require_once('inc.bgcom.php');
$BG = new BGCom();
include_once('inc.header.php');

$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
//echo('<!--' . base64_encode(serialize($db)) . '-->');

$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
if(mysqli_connect_errno()){ 
	printf("Connect failed: %s\n", mysqli_connect_error());
	die();
}
unset($db);
$homePageSiteSql = 'SELECT Id, SiteName, SiteUrl, DefaultOrder FROM HomePageSites';
$headlineSql = 'SELECT FeedId, ItemId, ItemTitle, ItemTime FROM Headlines';

if(!$homePageSiteResults = $mySql->query($homePageSiteSql)){
	printf("Error retreiving site list: %s\n", $mySql->error);
	exit();
} else {
	while($row = $homePageSiteResults->fetch_assoc()){
		$homePageSites[$row['DefaultOrder']] = array(
			'Id' => $row['Id'], 
			'SiteName' => $row['SiteName'], 
			'SiteUrl' => $row['SiteUrl']
		);
	}
	$homePageSiteResults->free();
}

if(!$headlineResults = $mySql->query($headlineSql)){
	printf("Error retreiving headlines: %s\n", $mySql->error);
	exit();
} else {
	while($row = $headlineResults->fetch_assoc()){
		$headlines[$row['FeedId']][] = array(
			'ItemId' => $row['ItemId'],
			'ItemTitle' => $row['ItemTitle'],
			'ItemTime' => $row['ItemTime']
		);
	}
	$headlineResults->free();
}

$mySql->close();

foreach($homePageSites as $site){
	if( (is_numeric($_COOKIE['n'])) && ($_COOKIE['n'] >= 3) && ($_COOKIE['n'] <= 15) ){
		$itemLimit = $_COOKIE['n'];
	} else {
		$itemLimit = 7;
	}
	
	if( $headlines[$site['Id']] === null ){
		echo '<div class="NewsBox"><ul class="NewsBoxItems"><li class="FeedSiteName"><a href="' . $site['SiteUrl'] . '" rel="nofollow">' . $site['SiteName'] . '</a></li><li class="ErrorItem">Zoinks! Can\'t find any data for this feed!</li></ul></div>';
	} else {
		$headlineSlice = array_slice($headlines[$site['Id']], 0, $itemLimit);
		echo $newsBox = $BG->makeNewNewsBox($site, $headlineSlice);
	}
}

include_once('inc.footer.php');
?>