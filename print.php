<?php

 #
 # MinNote - notes manager for website
 #
 # info: main folder copyright file
 #
 #

# configuration - need change it


include("config/config.php");
include("config/$MN_LANGFILE");


function vinput($d) {
    $d=trim($d);
    $d=stripslashes($d);
    $d=strip_tags($d);
    $d=htmlspecialchars($d);
    return $d;
}


echo("<!DOCTYPE HTML>");
echo("<html><head>");
echo("<title>$MN_SITENAME</title>");
echo("<meta charset=\"utf-8\" />");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">");
echo("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />");
echo("<link rel=\"icon\" href=\"favicon.png\">");
echo("<link rel=\"shortcut icon\" type=\"image/png\" href=\"favicon.png\" />");
echo("</head>");
echo("<style>");
include("$MN_CSS2");
echo("</style>");
echo("<body>");

#echo("<a href=$MN_ADMINFILE onclick=\"window.history.back();\">");
echo("<a onclick=\"window.history.back();\">");



$utime=time();
$loggedin=FALSE;
$passw="";

if (isset($_POST["password"])){
	$passw=md5($_POST["password"]);
	$passw=vinput($passw);
	if ($passw==$MN_ADMIN_PASS){
		$loggedin=TRUE;
		$ADMIN_USER=true;
	}else{
		if ($passw==$MN_PASS){
			$loggedin=TRUE;
			$ADMIN_USER=false;
		}
	}
}
if (isset($_POST["passwordh"])){
	$passw=$_POST["passwordh"];
	$passw=vinput($passw);
	if ($passw==$MN_PASS){
		if (isset($_POST["utime"])){
			$outime=$_POST["utime"];
			$outime=vinput($outime);
			$utime2=$utime-$outime;
			if ($utime2<$LOGIN_TIMEOUT){
				$loggedin=TRUE;
			}
		}else{
			$loggedin=TRUE;
		}
	}
}




if ($loggedin){
	echo("<div class='content'>");
	if (isset($_POST["submitprintx"])){
	    $notefile=vinput($_POST["notefile"]);
	    $notedir=vinput($_POST["notedir"]);
	    $nt=explode("/",$notedir);
	    echo("<center><h1>$L_PRINT_NAME: $nt[1], $L_PRINT_ITEM: $notefile</h1></center><br /><br />");
	    echo("<div class='content2'>");
	    $notefile=$notedir.'/'.$notefile;
	    if (file_exists($notefile)){
		$fileContents=file_get_contents($notefile);
		$datat=json_decode($fileContents, true);
		$db=count($datat);
		for($i=0;$i<$db;$i++){
		    $dout=htmlspecialchars_decode($datat[$i]);
		    echo($dout);
		}
	    }
	    echo("</div>");
	}else{
	}
	echo("</div>");

}else{
	echo("<section id=message>$L_NORIGHTS</section>");
}

echo("</a></body></html>");

?>
