<!-- /////     BEGIN FOOTER FILE     ///// -->
</div> <!--PageContentContainer-->

<script type="text/javascript">
	setLastVisit(); 
	setNumberOfColumns();
	addEllipsis(0);
	vilify("Trump");
</script>

<div id="PageFooter" class="FooterText">


<!-- <div id="Announcement" class="Announcement" align="center"><p></p></div> -->


  <div id="PageTopLinks" class="PageTopLinks"><p> Items per Feed:
    <select class="NumForm" name="Num" onchange="setNumberOfHeadlines(this)">
      <option value="7" selected="selected">#</option>
      <option value="Infinity">∞</option>
      <option value="15">15</option>
      <option value="14">14</option>
      <option value="13">13</option>
      <option value="12">12</option>
      <option value="11">11</option>
      <option value="10">10</option>
      <option value="9">9</option>
      <option value="8">8</option>
      <option value="7">7</option>
      <option value="6">6</option>
      <option value="5">5</option>
      <option value="4">4</option>
      <option value="3">3</option>
    </select></p>
    <p> Villain:
    <select class="NumForm" name="Name" onchange="setVillain(this)">
      <option value="">&mdash;</option>
      <option value="">Donald</option>
      <option value="Voldemort">Lord Voldemort</option>
    </select></p>
</div>   <!--PageTopLinks-->

<p>
BadGopher.com aggregates headlines from around the Internet for your enjoyment.  Headline topics range from tech news to celeb gossip, with a smattering of real world news to, you know, keep it real.  Now for the obvious stuff: this site is not affiliated or endorsed by any entity or trademark that might show up from time to time.  All trademarks and copyrights are the property of their respective owners.  Your lucky numbers are <?php echo mt_rand(1,15) . ' ' . mt_rand(16,30) . ' ' . mt_rand(45,60) . ' ' . mt_rand(31,39) . ' ' . mt_rand(61,75); ?> 42.
</p>
<div align="center"><a href="privacy.php" rel="nofollow">Privacy</a> :: <a href="http://badgopher.tumblr.com">Tumblr</a><!-- - <a href="http://twitter.com/BadGopher">Twitter</a>--> - <a href="http://flickr.com/photos/badgopher">Flickr</a> - <a href="mailto:bgcom21f57@badgopher.com">Email</a> :: <a href="source.htm" rel="nofollow">Source</a>
</div>
</div>


<!-- Twitter stuff -->
<?php
/*
require_once('inc.bgtwttr.php');

$twitter = new bgTwttr();
if( $twitter->apiLimits['remaining-hits'] > 0 ){
	$status = addslashes($twitter->getStatus());
} else {
	$status = 'Click <a href="http://twitter.com/BadGopher" rel="nofollow">here</a> to read my current status on Twitter.';
}

echo "<script type=\"text/javascript\">\n" . 
	 "    var status = '" . $status . "';\n" . 
	 "    setTweetStatus(status);\n" . 
	 "</script>\n";
*/
?>

</body>
</html>