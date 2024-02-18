<?php
require_once('inc.simplepie.php');
require_once('../inc.config.php');
require_once('../inc.bgcrypto.php');

class BGParser{

	function processFeed($feedId, $feedUrl){
		printf("Processing feedId %s with feedUrl %s\n", $feedId, $feedUrl);
		$feed = new SimplePie();
		$feed->set_feed_url($feedUrl);
		$feed->enable_cache(FALSE);
		$feed->force_feed(TRUE);
		$feed->strip_htmltags(array_merge($feed->strip_htmltags, array('h1', 'a', 'img', 'em', 'cite', 'nobr', 'br')));
//		$feed->set_output_encoding('ISO-8859-1');
		$feed->init();
		$feed->handle_content_type();
		
		$db = unserialize(BGCrypto::decryptString(DB_CONNECT));
		$mySql = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
		if(mysqli_connect_errno()){ 
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
		}
		unset($db);
		$stmt = $mySql->stmt_init();
		$insertSql = "INSERT INTO items (id, feedId, itemTitle, itemLink, importTime) VALUES ('', ?, ?, ?, NOW());";
		$stmt->prepare($insertSql);
		$stmt->bind_param('iss', $feedId, $itemTitle, $itemLink);

		foreach($feed->get_items() as $item){
			$itemTitle = $mySql->real_escape_string($item->get_title());
			$itemLink = $mySql->real_escape_string($item->get_link());

			if (stristr($itemTitle, '(podcast)') !== FALSE){ continue; }
			if (stristr($itemTitle, '(sponsor)') !== FALSE){ continue; }
			$stmt->execute();
		}

		$optimize = $mySql->query("OPTIMIZE TABLE items");

		$stmt->close();
		$optimize->close();
		$mySql->close();

		$feed->__destruct();
		unset($feed);
	}
}
?>