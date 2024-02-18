function setNumberOfColumns(){
	if(navigator.appName=="Netscape"){
		var WindowWidth = window.innerWidth;
	} else {
		var WindowWidth = document.body.offsetWidth;
	}
	var MaxCols = Math.floor(WindowWidth / 490);
	var ContentWidth = MaxCols * 490;
	try{
		document.getElementById('PageContentContainer').style.width = ContentWidth + 'px';
	} catch(err) {
		//alert(err);
	}
}

function setNumberOfNewColumns(){
	if(navigator.appName=="Netscape"){
		var WindowWidth = window.innerWidth;
	} else {
		var WindowWidth = document.body.offsetWidth;
	}
	var MaxCols = Math.floor(WindowWidth / 515);
	var ContentWidth = MaxCols * 515;
	try{
		document.getElementById('PageContentContainer').style.width = ContentWidth + 'px';
	} catch(err) {
		//alert(err);
	}
}

function setNumberOfHeadlines(NumObj){
	var Num = NumObj.options[NumObj.selectedIndex].value;
	console.log('selectedIndex: ' + NumObj.selectedIndex);
	console.log('num: ' + Num);
	NumObj.SelectedIndex = 0;
	if((Num < 3) || (Num > 15)){
		alert("TO INFINITY AND BEYOND!\n\nWhat do you think you're trying to do??\nSetting the number of items to "+ Num +" like that.\n\nGeeez...\n\nHere, take 7 and be happy with it.");
		createCookie('n', 7, 90);
	} else {
		createCookie('n', Num, 90);
	}
	window.location.reload();
}

function setLastVisit(){
	var d = new Date();
	var ds = d.toUTCString();
	createCookie('v', ds, 90);
}

function hideLoading(){
	document.getElementById('Loading').style.display = 'none';
}

function addEllipsis(NumToSkip){
	var itemSpans = document.getElementsByTagName("span");
	var spanCount = itemSpans.length;
	var maxWidth = 480;
	
	for(var i = NumToSkip; i <= spanCount; i ++){
		try{
			if(itemSpans[i].offsetWidth > maxWidth){
				var longSpanText = itemSpans[i].innerHTML;

				// Since we're looping through the string 1 character at a time
				// let's start at 40 characters to reduce the number of iterations...
				// We should be able to get at least 40 characters into 455 pixels, right?
				var s = 40;

				itemSpans[i].innerHTML = '';

				while((itemSpans[i].offsetWidth < maxWidth) && (s < longSpanText.length)){
					itemSpans[i].innerHTML = longSpanText.substr(0, s) + '...';
					s ++;
				}
			} 
		} catch(err) {
			//This is not the proper use of a catch statement, but fuck it.
			//alert(err);
			return;
		}
	}
}

function setTweetStatus(status){
	document.getElementById('TweetStatus').innerHTML = status;
}

function track(url){
	var d = false; // Debug

	try{
		pageTracker._trackPageview(url);
	} catch(err) {
		if(d){alert('Unable to track outbound link.\n\n' + err);}
		return false;
	}
}

function ajaxPost(url, query){
	var d = true; // Debug
	var r = false; // XMLHttpRequest

	if(window.XMLHttpRequest()){
		try{ r = new XMLHttpRequest(); } catch(err) { if(d){alert(err);} }
	} else if (window.ActiveXObject){
		try{ r = new ActiveXObject("Microsoft.XMLHTTP"); } catch(err) { if(d){alert(err);} }
	}

	if(r){
		r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		r.onreadystatechange = function(){
			if(r.readyState == 4){
				if(r.status == 200){
					if(d){alert(r.responseText);}
					return r.responseText;
				} else {r
					if(d){alert(r.statusText);}
					return false;
				}
			}
		}
		r.open('POST', url, true);
		r.send(query);
	} else {
		return false;
	}
}

function replaceWord(oldWord, newWord) {
	var itemSpans = document.getElementsByTagName("span");
	var spanCount = itemSpans.length;

	for (var i = 0; i <= spanCount; i++) {
		try {
			var spanText = itemSpans[i].innerHTML;
			itemSpans[i].innerHTML = spanText.replace(oldWord, newWord);
		} catch (err) {
			//console.log(err);
		}
	}
}

function setVillain(NameObj){
    var name = NameObj.options[NameObj.selectedIndex].value;
    if(NameObj.selectedIndex == 1){
        eraseCookie('e');
        window.location.reload();
    } else {
        createCookie('e', name, 180);
    }
    vilify(null, name);
}

function vilify(thing = null, villain = null){
    if (villain == null){ 
        try{ 
            villain = readCookie("e");
        } catch(err) {
            //console.log(err);
        }
    }
    if (villain == null){ return; }
    if (thing == null){ thing = "Trump"; }
    replaceWord(thing, villain);
}


// Javascript Cookie Functions
// From: http://www.quirksmode.org/js/cookies.html
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
