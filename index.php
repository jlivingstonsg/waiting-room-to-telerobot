<?php

//Lite underhåll av kön

session_start(); //Använd sessions
header('Cache-Control: no-cache');
if(!isset($_COOKIE['queue'])) {$ingen_kökaka = true; setcookie('queue', md5(time()));}


if($_SERVER['QUERY_STRING'] == 'setcoockie') {
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv">
	<head>
	<title>GameReality</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	</head>
	<body style="background-color: yellow; color: black;"><p>
	<script type="text/JavaScript">/*<![CDATA[*/
		if (document.cookie.indexOf("queue=") == -1) {
			document.write('Du måste ändra inställningarna så kakor tillåts, om du vill se GameReality.');
		
		}
		else {
			window.opener.document.getElementById('kakfel').style.display = 'none';
			window.close();
		}
	  /*]]>*/</script>
	  </p><p style="text-align:center;"><button onclick="window.close()">Stäng</button></p>
	</body>
	</html>	
	<?php
	exit;
}




$queue = explode("\n", file_get_contents('status/queue')); //Läs in köfilen
$speltid = file_get_contents('status/speltid');
if(!$queue[1]) {//Om aktuell spelare inte aktiverat sig
 if(isset($queue[2]) && $queue[2]==$_COOKIE['queue']) {
	$queue[1]=true;
	file_put_contents('status/queue', implode("\n", $queue));
 }
 elseif($queue[0] < time()+$speltid-5) $queue[0] = 0; //Om spelaren som står på tur inte varit aktiv inom 5 sek så kastas hen ut i nedanstående
}

if($queue[0] <= time()) { //Uppdatera kön ifall tiden har förflutit
	unset($queue[2]);
	$queue = array_values($queue);
	$queue[1] = isset($queue[2]) && $queue[2]==$_COOKIE['queue']? 1: 0;
	$queue[0] = time()+$speltid;
	file_put_contents('status/queue', implode("\n", $queue));
}



$kötid = (count($queue)-3)*$speltid + $queue[0] - time();
$kötid = $kötid>5? ($kötid >= 120? round($kötid/60).' minuter': ($kötid-4).' sekunder'): 'Ingen';

header('Content-Type: text/html; charset=utf8');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="sv" lang="sv">


<head>
	<title>GameReality </title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
   <meta name="description" content="GameReality. Ett spel där ni fjärrstyr riktiga holonomiska/omni kamera-robotar, utrustade med laser-tag vapen, över internet." />
   <meta name="keywords" content="GameReality, M.Sc. Magnus Ivarsson" />
	<link href="style.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="top">
<h1><a href="http://www.gamereality.se" target="_top"><span>GameReality  2019</span></a></h1>
<p>Ett dataspel, där ni fjärrstyr riktiga holonomiska/omni kamera-robotar, utrustade med laser-tag vapen, över internet. 
</p>
</div>
 
  <?php if(isset($_GET['om_kakor'])): ?>
   <div style="max-width: 800px; margin: 0 auto 20px;">
   <h2>Om kakor</h2>
  
   <p>Enligt lagen om alltings tillkrånglande (lagen om elektronisk kommunikation 2003:389 18§), 
  som trädde i kraft den 25 juli 2003, ska alla som besöker en webbplats med kakor 
  få information om vad en kaka är. Så därför, laglydiga 
  som vi är, har vi samlat ihop lite info om saken.</p>
  
  <p> En kaka är en liten textfil som webbplatsen du besöker sparar på 
  din dator. Kakor används på många webbplatser för att 
  ge en besökare tillgång till olika funktioner. Informationen i kakan 
  är möjlig att använda för att följa en användares 
  surfande.</p>

  <p>Det finns två typer av kakor. Den ena typen sparar en fil under en längre 
  tid på din dator. Den används till exempel vid funktioner som talar 
  om vad som är nytt sedan användaren senast besökte den aktuella 
  webplatsen. Den andra typen av kakor kallas sessionskakor. Under tiden du 
  är inne och surfar på en sida, lagras den här kakan temporärt 
  i din dators minne exempelvis för att hålla reda på vilket 
  språk du har valt. Sessionskakor lagras inte under en längre tid 
  på din dator, utan försvinner när du stänger din webbläsare.</p> 

  <p>Denna GameReality använder sig av den senare typen av kakor för att hålla
  reda på vem som står på tur att spela. 
  Kakan som sparas är bara en vanlig textfil med lite bokstäver och 
  några siffror. Den kan på inget sätt ge oss info om din dator (sådant tar vi reda på annat sätt) eller användas för att spåra vilka politiker du valt.
  Skulle du mot förmodan vilja stoppa denna kaka så får du titta i din webbläsares 
  manual hur man gör. Men då får du tyvärr inte vara med och spela.</p>

  <p>Observera att det är själva GameRealityservern med adressen http://<?php echo $_SERVER["SERVER_NAME"];?>
  som sparar kakorna.</p>

  <p> För att få veta mer om vad en kaka är och hur lagen ser ut besök <a href="http://www.pts.se/sv/Regler/Lagar/Lag-om-elektronisk-kommunikation/Cookies-kakor/#Vad%20%C3%A4r%20en%20cookie" target="_blank">Post 
  &amp; Telestyrelsens hemsida om kakor</a><br/>
  Men innan du fördjupar dig i texterna där vill jag påpeka att de risker som 
  där uppges finnas med kakor i ännu högre grad gäller andra tekniker som inte 
  omfattas av lagen om alltings tillkrånglande.</p>


   </div>
 
  <?php endif; ?> 

 
 
 
 
<!-- Centrumbilden som visas på platsen för webbkameran --> 
 <div id="scenen">
  <div id="webcamcontainer">
   <img src="http://www.gamereality.se/images/Ol2gkzL3Tl.gif" alt="Den webbstyrda roboten"  height="442" width="552"/>
  </div>
 </div>





<div style="margin:0 auto;width:640px;padding-top:7px;/*height: 190px;overflow:hidden;border-bottom: solid #aaaaaa 1px;*/">
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style">
	<a class="addthis_button_facebook">&nbsp;Dela på facebook</a>
	<span class="addthis_separator">|</span>

	<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=gamereality" class="addthis_button_compact">Dela på fler sätt...</a>
	<!--
	<a class="addthis_button_myspace"></a>
	<a class="addthis_button_google"></a>
	<a class="addthis_button_twitter"></a>
	-->
	</div>
	<script type="text/javascript">
		addthis_url    = 'http://www.gamereality.se/';   
		addthis_title  = ' GameReality 2019';  
		addthis_pub    = 'gamereality';
		
		
		//Kolla ifall kakan blev satt:
		var errorbox;
		if (document.cookie.indexOf("queue=") == -1) {
			document.write('<p class="errorbox" id="kakfel">GameReality kunde inte sätta en nödvändig kaka.<br/><button onclick="trysetcoockie()">Gör ett nytt försök...</button></p>');
			

		}
		function trysetcoockie() {
			var w = 450; 
			var win = window.open('?setcoockie','','status=no,height=120,width='+w+',resizable=yes,left='+(window.screen.width-w)/2
			+ ',top=360,location=no,directories=no,menubar=no,toolbar=no,scrollbars=yes');
			
		}
		
		
    </script>
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=gamereality"></script>

	<!-- AddThis Button END -->
</div>












 <div style="text-align:center;margin: 20px 0 180px;">
  <?php if(strlen(file_get_contents('status/error'))): ?>
   <p class="errorbox">
    För närvarande är GameReality avstängd pga tekniskt fel eller underhåll.<br/>
    Välkommen åter om en liten stund
   </p>
  <?php elseif(isset($_GET['queue_full'])): ?>
   <p class="errorbox">
    För närvarande är det många som väntar på att få besöka GameReality.<br/>
    Välkommen åter om en liten stund
   </p>
  <?php else: ?>
   <?php if(isset($_GET['check_cookie']) && $ingen_kökaka) { ?>
 	<div class="errorbox"><b>ETT FEL UPPSTOD!</b>
	 För att kunna se GameReality här måste tredjepartskakor från GameRealitys server http://<?php echo $_SERVER["SERVER_NAME"];?> tillåtas i webbläsaren!<br/>Ändra inställningarna för kakor och försök igen.</div>
   <?php } ?>
	<button style="font-weight: bold; padding: 10px;" onclick="window.location.replace('queue.php?new')">Stand in queueu here to a Robot.</button>
	<br/>Calculated  queue time: <?php echo $kötid; ?>
	<!-- <div style="width:350px;border:solid black 1px;background-color:#ffffaa;margin:10px auto; text-align:left;padding:4px;">Denna webbsida har testats med gott resultat i alla vanliga nyare webbläsare.<br/>
	Men om du har en äldre webbläsare så får du gärna lämna en kommentar om hur webbsidan fungerat i den i kommentarsfältet till höger.	
	 </div> -->
  <?php endif; 
	preg_match('/Opera[\/\s](\d+\.\d+)/', $_SERVER['HTTP_USER_AGENT'], $d); $opera = (float) $d[1];
	if(!$opera) { preg_match('/MSIE\s(\d+\.\d+)/', $_SERVER['HTTP_USER_AGENT'], $d); $msie = (float) $d[1]; }
	if($msie): ?>
	<p class="errorbox">
Just nu verkar Internet Explorer inte vara helt kompatibel med GameReality.
Får du problem med Internet Explorer så pröva vilken annan webläsare som helst:<br/> 
	<a href="http://www.mozilla.com/en-US/firefox/all.html?lang-search=svenska">Mozilla Firefox</a> <br/> 
	<a href="http://www.google.com/chrome">Google Chrome</a><br/> 
	<a href="http://www.apple.com/safari/download/">Apple Safari</a><br/> 
	<a href="http://www.opera.com/browser/download/">Opera</a> 	
	</p>
  <?php endif; ?>
 </div>





<!-- Centrumbilden som visas på platsen för webbkameran 

<div style="white-space: nowrap; width:100%; overflow:hidden;"><img
src="http://www.gamereality.se/images/Ol2gkzL3Tl.gif" title="GameReality" alt="GameReality" height="200"/>
</div>

--> 


<div id="foot">
	<p>
	 GameReality och alla fysiska robotar gjordes av M.Sc. Magnus Ivarsson 
	<a href="http://www.gamereality.se"><br/>Kontakta GameReality</a>
	</p>


   <p><a href="?om_kakor">Denna webbsida använder kakor.</a></p>

</div>



</body>
</html>














