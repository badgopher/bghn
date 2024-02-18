<?php
if($_COOKIE['retro']=='no'){ require_once('index.php'); die(); }
require_once('inc.bgcom.php');
error_reporting(0);
$BG = new BGCom();
?>
<HTML>
<!--This file created 1:44 PM  11/15/2008 by Claris Home Page version 3.0 30 Day Trial-->
<HEAD>
   <TITLE>Welcome to BadGopher.com</TITLE>
   <META NAME=GENERATOR CONTENT="Claris Home Page 3.0 30 Day Trial">
   <X-CLARIS-WINDOW TOP=62 BOTTOM=871 LEFT=8 RIGHT=538>
   <X-CLARIS-TAGVIEW MODE=full>
<SCRIPT LANGUAGE="JavaScript">
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
	window.location.reload();
}
</SCRIPT>
</HEAD>
<BODY TEXT="#FFFFFF" BGCOLOR="#003399" LINK="#3399FF" ALINK="#FF3300" VLINK="#9933FF" BACKGROUND="images/2_chip.gif">
<CENTER><TABLE BORDER=10 CELLSPACING=0 CELLPADDING=0 WIDTH=100 HEIGHT=100 a bad gopher>
   <TR>
      <TD>
         <P><IMG SRC="images/Mascot.gif" WIDTH=80 HEIGHT=80 X-CLARIS-USEIMAGEWIDTH X-CLARIS-USEIMAGEHEIGHT ALIGN=bottom></P>
      </TD>
   </TR>
</TABLE>
 <FONT SIZE="+4"> Welcome to BadGopher.com</FONT></CENTER>

<PRE><CENTER><FONT SIZE="+1"><B>Your source for upto date news headlines on the Internet.</B></FONT></CENTER></PRE>

<CENTER><MARQUEE><FONT SIZE="+2" COLOR="#00FF00"><B><I>News Headlines
Are&nbsp;Updated <BLINK>Every 30 Minutes</BLINK>!</I></B></FONT></MARQUEE></CENTER>

<P><FONT SIZE="+2" COLOR="#00FF00"><B>

<HR>

</B></FONT></P>

<CENTER><TABLE BORDER=2 CELLPADDING=2 WIDTH="95%">
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(1); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(2); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(3); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(4); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(5); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(6); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(7); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(8); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(9); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(10); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(11); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(12); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(15); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(17); ?></P>
      </TD>
   </TR>
   <TR>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(14); ?></P>
      </TD>
      <TD WIDTH="50%">
         <P><?php $BG->makeRetroNewsBox(13); ?></P>
      </TD>
   </TR>
</TABLE>
</CENTER>

<P>

<HR>

<I>Last updated:&nbsp;</I><B><I>November 15 1998</I></B></P>
<P><A HREF="mailto:retro@badgopher.com">E-Mail Me!</A></P>

<P>&nbsp;</P>
<P><A HREF="#" ONCLICK="createCookie('retro','no','180')">Go modern!</A></P>
</BODY>
</HTML>
