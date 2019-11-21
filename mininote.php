<?php

 #
 # MiniNote - notes manager for website
 #
 # info: main folder copyright file
 #
 #


include("config/config.php");
include("config/$MN_LANGFILE");
include("$MN_HEADER");
include("$MN_JS_BEGIN");


function dirlist($dir) {
    global $MN_CONFIG_DIR;

    $result=array();
    $cdir=scandir($dir);
    foreach ($cdir as $key => $value){
	if (!in_array($value,array(".","..",$MN_CONFIG_DIR))){
	    $result[]=$value;
	}
    }
    return $result;
}


function vinput($d) {
    $d=trim($d);
    $d=stripslashes($d);
    $d=strip_tags($d);
    $d=htmlspecialchars($d);
    return $d;
}


function vinputtags($d) {
    $d=trim($d);
    $d=stripslashes($d);
    $d=htmlspecialchars($d);
    return $d;
}


function mess_error($m){
    echo("<div class='message' style='mmargin:20px;'>
	    <div onclick='this.parentElement.style.display=\"none\"' class='toprightclose'></div>
	    <p style='padding-left:40px;'>$m</p>
	</div>");
}


function mess_ok($m){
    echo("<div class='card'>
	    <div onclick='this.parentElement.style.display=\"none\"' class='toprightclose'></div>
	    <div class=card-header><br /></div>
	    <div class='cardbody' id='cardbody'>
		<p style='padding-left:40px;padding-bottom:20px;'>$m</p>
	    </div>
	</div>");
}




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

	$datat=array();
	$notefile="";
	$notedir="";
	$saved=FALSE;
	$printpage=false;
	
		
	# delete data
	
	if (isset($_POST["submitdel"])){
		$notedir=vinput($_POST["notedir"]);
		if ($_POST["newnote"]<>""){
		    $dn=$notedir.'/'.vinput($_POST["newnote"]);
		    if (file_exists($dn)){
			if (unlink($dn)){
			    mess_ok($L_DELNOTE_MESS);
			}else{
			    mess_error($L_DELNOTE_ERROR);
			}
		    }else{
			#mess_ok($L_DELNOTE_NOTFOUND);
		    }
		}
	}
		
	# save data
	
	if (isset($_POST["submitsave"])){
		$datat[0]=vinput($_POST["newnote"]);
		if ($MN_USE_3PARTY_EDITOR){
		    $datat[1]=vinputtags($_POST["newtext"]);
		}else{
		    $datat[1]=vinput($_POST["newtext"]);
		}
		$notedir=vinput($_POST["notedir"]);
		$notefile=$notedir.'/'.$datat[0];
		#echo($notefile);
		$encodedString=json_encode($datat);
 		file_put_contents($notefile, $encodedString);

	}
	
	# print page
	
	if (isset($_POST["submitprint"])){
		$notefile=vinput($_POST["newnote"]);
		$notedir=vinput($_POST["notedir"]);
		if ($notefile<>""){
		    $printpage=true;
		    echo("<form action=$MN_PRINTFILE ttarget=_blank id=3000 method=post enctype=multipart/form-data>");
		    echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		    echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");
		    echo("	<input type='hidden' name='notedir' id='notedir' value='$notedir'>");
		    echo("	<input type='hidden' name='notefile' id='notefile' value='$notefile'>");
		    echo("	<input class='inputsubmit40' style='display:none;' type='submit' id='submitprintx' name='submitprintx' value='$L_BUTTON_PRINT'>");
		    echo("</form>");
		    echo("	<script>var n=document.getElementById('submitprintx');n.click();</script>");
		
		}
		$notefile=$notedir.'/'.$notefile;
	}
	
	# new dir
	
	if (isset($_POST["submitdir"])){
		if (isset($_POST["newdir"])){
			$dn=$MN_DATA_ROOT.'/'.vinput($_POST["newdir"]);
			if (file_exists($dn)){
				mess_error($L_NEWDIR_EXISTS);
			}else{
				if (mkdir($dn)){
					mess_ok($L_NEWDIR_MESS);
				}else{
					mess_error($L_NEWDIR_ERROR);
				}
			}
		}
	}	
	
	# del dir
	
	if (isset($_POST["submitdeldir"])){
		if ($_POST["dirlist"]<>$L_SELECT_ITEM){
			$dirn=$MN_DATA_ROOT.'/'.vinput($_POST["dirlist"]);
			$files=dirlist($dirn);
		    foreach ($files as $file) {
			if (!is_dir("$dirn/$file")){
					unlink("$dirn/$file");
			}
		    }
			if (is_dir($dirn)) {
			    if (rmdir($dirn)){
					mess_ok($L_DELDIR_MESS);
			    }else{
					mess_error($L_DELDIR_ERROR);
			    }
			}else{
				mess_error($L_DELDIR_ERROR);
			}
		}
	}
	
	# change dir
	
	if (isset($_POST["submitloc"])){
		$notedir=$MN_DATA_ROOT.'/'.vinput($_POST["submitloc"]);
	}
	
	# prev dir
	
	if (isset($_POST["submitprev"])){
		$notedir="";
	}

	# files
	
	if (isset($_POST["submitfile"])){
		$notedir=vinput($_POST["notedir"]);
		$notefile=$notedir."/".vinput($_POST["submitfile"]);
	}
		

	if (!$printpage){
	    # generate dirlist
	
	    echo("<div class='row'>");
	    echo("<div class='column5'>");

	    if ($notedir==""){
		    echo("$L_MAPPANAME");
		    $dir_list=dirlist($MN_DATA_ROOT);
		    $db=count($dir_list);
		    for ($i=0;$i<$db;$i++){
			$fn=$MN_DATA_ROOT.'/'.$dir_list[$i].'/..';
			if (file_exists($fn)){
				echo("<form action=$MN_ADMINFILE id=$i method=post enctype=multipart/form-data>");
				echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
				echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");
				echo("  <input type='submit' name='submitloc' id='submitloc' value='$dir_list[$i]'>");
				echo("</form>");
			}
		    }
		    echo("<hr />");
		    echo("<form action=$MN_ADMINFILE id=100 method=post enctype=multipart/form-data>");
		    echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		    echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");	
		    echo("	<input type='text' name='newdir' id='newdir' placeholder='$L_NEWDIR'>");
		    echo("  <input type='submit' name='submitdir' id='submitdir' value='$L_BUTTON_ALL'>");
		    echo("</form>");
		    		
		    echo("<hr />");
		    echo("<form action=$MN_ADMINFILE id=101 method=post enctype=multipart/form-data>");
		    echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		    echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");	
		    
		    echo("	<select name='dirlist' id='dirlist'>");
		    echo("		<option value='$L_SELECT_ITEM'>$L_SELECT_ITEM</option>");
		    for ($i=0;$i<$db;$i++){
		    	echo("		<option value='$dir_list[$i]'>$dir_list[$i]</option>");
		    }
		    echo("	</select>");
		    
		    echo("  <input class='inputsubmitr' type='submit' name='submitdeldir' id='submitdeldir' value='$L_BUTTON_DELDIR'>");
		    echo("</form>");
	    }else{    
		$dn=substr($notedir,strlen($MN_DATA_ROOT)+1,strlen($notedir));
		
		# load file
		
 		if ($datat[0]==""){
 			#$datat[0]=$L_NEW_NOTE;
 		}
 		
		if ($notefile==""){
			$notefile=$notedir."/".$datat[0];
		}
		
		if (file_exists($notefile)){
			$fileContents=file_get_contents($notefile);
			$datat=json_decode($fileContents, true);
 		}
 		
 		# generate pagelist
 		
		echo("<form action=$MN_ADMINFILE id=101 method=post enctype=multipart/form-data>");
		echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");	
		echo("  <input class='inputsubmitg' type='submit' name='submitprev' id='submitprev' value='$dn $L_MAPPA_ACTUAL'>");
		echo("</form>");
		$dir_list=dirlist($notedir);
		$db=count($dir_list);
		for ($i=0;$i<$db;$i++){
			echo("<form action=$MN_ADMINFILE id=$i method=post enctype=multipart/form-data>");
			echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
			echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");	
			echo("	<input type='hidden' name='notedir' id='notedir' value='$notedir'>");
			if ($dir_list[$i]==$datat[0]){
			    $st="class='button_active'";
			}else{
			    $st="";
			}
			echo("  <input type='submit' name='submitfile' id='submitfile' $st value='$dir_list[$i]'>");
			echo("</form>");
		}
		echo("<form action=$MN_ADMINFILE id=101 method=post enctype=multipart/form-data>");
		echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");	
		echo("  <input class='inputsubmitr' type='submit' name='submitprev' id='submitprev' value='$L_BUTTON_PREV'>");
		echo("</form>");

		echo("</div>");
		
		echo("<div class='column54'>");
		echo("<div class='' style='padding-left:40px;'>");

		# generate content/editor
 		
		#echo($notefile);
		echo("<form action=$MN_ADMINFILE id=1000 method=post enctype=multipart/form-data>");
		echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");
		echo("	<input type='hidden' name='notedir' id='notedir' value='$notedir'>");
		echo("	<input type='hidden' name='notefile' id='notefile' value='$notefile'>");
		if ($datat[0]<>""){
			$da="value='$datat[0]'";
		}else{
			$da="placeholder='$L_NEW_NOTE'";
		}
		echo("	<input type='text' name='newnote' id='newnote' $da>");
		echo("	<textarea name='newtext' id='newtext'>$datat[1]</textarea>");
		
		if ($MN_USE_3PARTY_EDITOR){
		    $dbe=count($MN_EDITOR);
		    for ($ie=0;$ie<$dbe;$ie++){
			echo($MN_EDITOR[$ie]);
		    }
		}
		
		echo('	<br /><br />');
		echo('	<center>');
		echo("	<input class='inputsubmit40' type='submit' id='submitsave' name='submitsave' value='$L_BUTTON_SAVE'>");
		echo("	<input class='inputsubmit40r' type='submit' id='submitdel' name='submitdel' value='$L_BUTTON_DELETE'>");
		echo("	<input class='inputsubmit40' type='submit' id='submitprint' name='submitprint' value='$L_BUTTON_PRINT'>");
		
		echo("</form>");
		echo("</div>");
	    }
	    echo("</div>");
	    echo("</div>");

	}else{
	    # printpage
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
	    
	}
	
	
}else{

	# password
	
	echo("<h1>$L_SITENAME</h1>");
	echo("<div class=spaceline100></div>");
	echo("<form  method='post' enctype='multipart/form-data'>");
	echo("    $L_PASS:");
	echo("    <input type='password' name='password' id='password' autofocus>");
	echo("<div class=spaceline></div>");
	echo("    <input type='submit' value='$L_BUTTON_ALL' name='submit'>");
	echo("</form>");
	echo("<div class=spaceline></div>");
}



include("$MN_JS_END");
include("$MN_FOOTER");

?>
