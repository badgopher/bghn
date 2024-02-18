<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('log_errors', '1');
ini_set('error_log', '/home/bghnadlv/logs/phperr.log');
require_once('inc.config.php');
require_once('inc.bgcrypto.php');

class BGCom {

	function __construct(){
		date_default_timezone_set('GMT');
	}

	function batchNewsBoxes($feedIdArray){
		foreach($feedIdArray as $feedId){
			if(is_numeric($feedId)){
				$newsBoxes .= $this->makeNewsBox($feedId);
			}
		}
		return $newsBoxes;
	}

	function makeCategoryBox($categoryId, $getRandom = TRUE){
		$categoryItems = $this->getCategoryItems($categoryId, $getRandom);
		
		$categoryBox	= '<div class="CategoryBox" id="cb' . $categoryId . '">' . "\n"
						. '  <ul class="CategoryBoxItems" id="cbi' . $categoryId . '">' . "\n"
						. '    <li class="FeedSiteName">Grab Bag</li>' . "\n"; // TODO: Make dynamic
		
		foreach($categoryItems as $categoryItem){
			$categoryBox .= '    <li><em><a href="' . $categoryItem['webUrl'] . '" rel="nofollow">' . $categoryItem['siteName'] . '</a></em><nobr>: <a href="/' . $categoryItem['id'] . '" rel="nofollow">' . $categoryItem['title'] . '</a></nobr></li>' . "\n";
		}

		$categoryBox	.= '  </ul>' . "\n"
						.  '</div>' . "\n";

		return $categoryBox;
	}


	function makeNewNewsBox($site, $headlines){
		/*
		===== NewsBox Structure =====
		<div class="NewsBox">
		  <ul class="NewsBoxItems">
		    <li class="FeedSiteName"><a href="#">Site Name</a></li>
		    <li class="NewItem"><a href="#" title="Donec faucibus urna."><span>Donec fauc</span></a></li>
		    <li class="OldItem"><a href="#" title="Morbi suscipit quam."><span>Morbi susc</span></a></li>
		  </ul>
		</div>
		*/
		
		$newsBox	= '<div class="NewsBox">' . "\n"
					. '  <ul class="NewsBoxItems">' . "\n"
					. '    <li class="FeedSiteName"><a href="' . $site['SiteUrl'] . '" rel="nofollow">' . $site['SiteName'] . '</a></li>' . "\n";

		foreach($headlines as $headline){
			$cssClass = $this->isItemNew($headline['ItemTime']) ? 'NewItem' : 'OldItem';
			$howOld = $this->howOldIsItem($headline['ItemTime']);
			$headline['ItemTitle'] = strip_tags(stripslashes($headline['ItemTitle']));
			
			//Apparently there are keywords that cause _some_ hosting providers to block access to the site.
			$badWords = array('hacked by ');
			$goodWords = array('hacked-by ');
			$headline['ItemTitle'] = str_replace($badWords, $goodWords, $headline['ItemTitle']);

			//The Idea here is to eliminate wasted characters that would otherwise be auto-ellipsis'd
			if(strlen($headline['ItemTitle']) >= 80){
				$headline['shortTitle'] = substr($headline['ItemTitle'], 0, 80);
			} else {
				$headline['shortTitle'] = $headline['ItemTitle'];
			}

			$newsBox .= '    <li class="' . $cssClass . '"><a href="/' . $this->intToBase36($headline['ItemId']) . '" title="' . $howOld . ': ' . $headline['ItemTitle'] . '" rel="nofollow"><span>' . $headline['shortTitle'] . '</span></a></li>' . "\n";
		}

		$newsBox .= '  </ul>' . "\n"
		          . '</div>' . "\n\n";
		
		return $newsBox;
	}

	function makeNewsBox($feedId, $getRandom = FALSE){
		// 2008.12.07 -- Added getRandom argument (for Damn Interesting)

		/*
		===== NewsBox Structure =====
		<div class="NewsBox">
		  <ul class="NewsBoxItems">
		    <li class="FeedSiteName"><a href="#">Site Name</a></li>
		    <li class="NewItem"><a href="#" title="Donec faucibus urna."><span>Donec fauc</span></a></li>
		    <li class="OldItem"><a href="#" title="Morbi suscipit quam."><span>Morbi susc</span></a></li>
		  </ul>
		</div>
		*/
		
		$feedInfo = $this->getFeedInfo($feedId);
		$feedItems = $this->getFeedItems($feedId, $getRandom);

		$newsBox	= '<div class="NewsBox">' . "\n"
					. '  <ul class="NewsBoxItems">' . "\n"
					. '    <li class="FeedSiteName"><a href="' . $feedInfo['webUrl'] . '" rel="nofollow">' . $feedInfo['siteName'] . '</a></li>' . "\n";

		foreach($feedItems as $feedItem){
			$cssClass = ($feedItem['isNew'] === TRUE) ? 'NewItem' : 'OldItem';

			//The idea here is to eliminate wasted characters that would otherwise be auto-ellipsis'd
			if(strlen($feedItem['title']) >= 80){
				$feedItem['shortTitle'] = substr($feedItem['title'], 0, 80);
			} else {
				$feedItem['shortTitle'] = $feedItem['title'];
			}

			$newsBox .= '    <li class="' . $cssClass . '"><a href="/' . $feedItem['id'] . '" title="' . $feedItem['howOld'] . ': ' . $feedItem['title'] . '" rel="nofollow" onClick="recordOutboundLink(this, \'Clicks\', \'' . $feedInfo['siteName'] . '\');return false;"><span>' . $feedItem['shortTitle'] . '</span></a></li>' . "\n";
		}

		$newsBox .= '  </ul>' . "\n"
		          . '</div>' . "\n\n";
		
		return $newsBox;
	}


	function makeRetroNewsBox($feedId){
		/*
		===== RetroNewsBox Structure =====
         <H3><CENTER><A HREF="MyCool%A0Site.com"><B>Site
         Name</B></A></CENTER></H3>
         
         <UL>
            <LI><A HREF="http://www.google.com">Item 1</A></LI>
            
            <LI><A HREF="http://www.internic.net">Item 2</A></LI>
         </UL>
		*/
		
		$feedInfo = $this->getFeedInfo($feedId);
		$feedItems = $this->getFeedItems($feedId);

		$retroNewsBox	= '<H3><CENTER><A HREF="' . $feedInfo['webUrl'] . '"><B>' . $feedInfo['siteName'] . '</B></A></CENTER></H3>' . "\n\n<UL>\n";

		foreach($feedItems as $feedItem){
			$retroNewsBox .= '   <LI><A HREF="/' . $feedItem['id'] . '">' . $feedItem['title'] . '</A></LI>' . "\n\n";
		}

		$retroNewsBox .= '</UL>' . "\n\n";
		
		echo $retroNewsBox;
	}

	function getFeedInfo($feedId){
		$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
		$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
		if(mysqli_connect_errno()){ 
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
		}
		unset($db);

		$selectSql = "SELECT f.feedWebUrl, f.feedSiteName FROM feeds f WHERE f.feedId = ? LIMIT 1;";

		$stmt = $mySql->stmt_init();
		$stmt->prepare($selectSql);
		$stmt->bind_param(
			'i', 
			$feedId
		);
		$stmt->execute();
		$stmt->bind_result(
			$feedInfo['webUrl'], 
			$feedInfo['siteName']
		);
		$stmt->fetch();

		$stmt->close();
		$mySql->close();

		return $feedInfo;
	}

	function getFeedItems($feedId, $getRandom){
		if( (is_numeric($_COOKIE['n'])) && ($_COOKIE['n'] >= 3) && ($_COOKIE['n'] <= 15) ){
			$itemLimit = $_COOKIE['n'];
		} else {
			$itemLimit = 7;
		}

		$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
		$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
		if(mysqli_connect_errno()){ 
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
		}
		unset($db);

		// Assume we always want the newest stuff
		$orderBy = 'i.importTime';
		
		// Unless getRandom is TRUE
		if($getRandom === TRUE){
			$orderBy = 'RAND()';
		}

		$selectSql = "SELECT i.id, i.itemTitle, i.importTime FROM items i WHERE i.feedId = ? ORDER BY " . $orderBy . " DESC LIMIT ?;";

		$stmt = $mySql->stmt_init();
		$stmt->prepare($selectSql);
		$stmt->bind_param(
			'ii', 
			$feedId, 
			$itemLimit
		);
		$stmt->execute();
		$stmt->bind_result(
			$id, 
			$itemTitle, 
			$importTime
		);

		$i = 0;
		while($stmt->fetch()){
			$feedItems[$i]['id'] = $this->intToBase36($id);
			$feedItems[$i]['title'] = stripslashes($itemTitle);
			$feedItems[$i]['isNew'] = $this->isItemNew($importTime);
			$feedItems[$i]['howOld'] = $this->howOldIsItem($importTime);
			$i ++;
		}

		$stmt->close();
		$mySql->close();
		
		return $feedItems;
	}

	function getItemInfo($itemId){
		$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
		$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
		if(mysqli_connect_errno()){ 
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
		}
		unset($db);

		$selectSql = "SELECT i.itemTitle, i.itemLink, i.importTime, i.feedId FROM items i WHERE i.id = ? LIMIT 1;";

		$stmt = $mySql->stmt_init();
		$stmt->prepare($selectSql);
		$stmt->bind_param(
			'i', 
			$itemId
		);
		$stmt->execute();
		$stmt->bind_result(
			$itemInfo['itemTitle'], 
			$itemInfo['itemLink'], 
			$itemInfo['importTime'],
			$itemInfo['feedId']
		);
		$stmt->fetch();

		$stmt->close();
		$mySql->close();
		
		return $itemInfo;
	}

	function getCategoryItems($categoryId, $getRandom = TRUE){
//		if( (is_numeric($_COOKIE['n'])) && ($_COOKIE['n'] >= 3) && ($_COOKIE['n'] <= 15) ){
//			$itemLimit = $_COOKIE['n'];
//		} else {
			$itemLimit = 3;
//		}

		$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
		$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
		if(mysqli_connect_errno()){ 
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
		}
		unset($db);

		// Assume we want the newest stuff
		$orderBy = 'i.importTime';
		
		// Unless getRandom is TRUE
		if($getRandom === TRUE){
			$orderBy = 'RAND()';
		}

		$selectSql = "SELECT i.id, i.itemTitle, f.feedSiteName, f.feedWebUrl FROM items i LEFT JOIN feeds f ON i.feedId = f.feedId WHERE f.feedCategoryId = ? ORDER BY " . $orderBy. " LIMIT ?";

		$stmt = $mySql->stmt_init();
		$stmt->prepare($selectSql);
		$stmt->bind_param(
			'ii', 
			$categoryId, 
			$itemLimit
		);
		$stmt->execute();
		$stmt->bind_result(
			$id, 
			$itemTitle, 
			$feedSiteName,
			$feedWebUrl
		);

		$i = 0;
		while($stmt->fetch()){
			$categoryItems[$i]['id'] = $this->intToBase36($id);
			$categoryItems[$i]['title'] = stripslashes($itemTitle);
			$categoryItems[$i]['siteName'] = stripslashes($feedSiteName);
			$categoryItems[$i]['webUrl'] = stripslashes($feedWebUrl);
			$i ++;
		}

		$stmt->close();
		$mySql->close();
		
		return $categoryItems;
	}


	function intToBase36($int){
		$int += MAGIC_NUMBER;
		return base_convert($int, 10, 36);
	}

	function base36ToInt($str){
		return base_convert($str, 36, 10) - MAGIC_NUMBER;
	}

	function isItemNew($itemTime){
		if($_COOKIE['v'] == ''){
			// No last visit cookie means the item must be new, right?
			return TRUE;
		} else {
			$cookieTime = strtotime($_COOKIE['v']);
			$itemTime = strtotime($itemTime);
			
			if($itemTime > $cookieTime){
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	function howOldIsItem($itemTime){
		$itemTime = strtotime($itemTime);
		$timeDiff = time() - $itemTime;

		switch($timeDiff){
			// Less than 1 minute; show seconds as 's'.
			case ($timeDiff > 0) && ($timeDiff < 60):
				$factor = 1;
				$suffix = 's';
				break;
			
			// 60 seconds or more, but less than 1 hour; show as 'm'.
			case ($timeDiff >= 60) && ($timeDiff < 3600):
				$factor = 60;
				$suffix = 'm';
				break;
			
			// 1 hour or more, but less than 1 day; show as 'h'.
			case ($timeDiff >= 3600) && ($timeDiff < 86400):
				$factor = 3600;
				$suffix = 'h';
				break;
			
			// 1 day or more, but less than 30 days; show as 'd'.
			case ($timeDiff >= 86400) && ($timeDiff < 2592000):
				$factor = 86400;
				$suffix = 'd';
				break;

			// 30 days or more; show as 'Mo'
			case ($timeDiff >= 2592000):
				$factor = 2592000;
				$suffix = 'Mo';
				break;
			
			// Other; show as '0? Oh my!'.
			default:
				$factor = .0000000001;
				$suffix = '? Oh my!';
				break;
		}

		$timeDiff = floor($timeDiff / $factor) . $suffix;
		return $timeDiff;

	}

	function httpRedirect($location){
		if($location != ''){
			header("HTTP/1.1 301 Moved Permanently\r\n");
			header("Location: " . $location . "\r\n");
		}
	}


}

?>