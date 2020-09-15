<?php

 #
 # MiniNote - notes manager for website
 #
 # info: main folder copyright file
 #
 #


function searchpage(){
	echo("search page");
}

	
function privacypage(){
	global $L_PRIVACY_HEADER,$L_PRIVACY_TEXT, $L_BACKPAGE, $MA_NOPAGE;

	echo("<div class=\"content\">");
	echo("<h1>".$L_PRIVACY_HEADER."</h1>");
	echo("<div class=\"spaceline\"></div>");
	echo("<p>".$L_PRIVACY_TEXT."</p>");
	echo("</div>");
}

function printpage(){
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
	#echo("<a href=$MA_ADMINFILE onclick=\"window.history.back();\">");
	echo("<a onclick=\"window.history.back();\">");
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
	echo("</a></body></html>");
}





function main(){
	global $L_DELNOTE_MESS,$L_DELNOTE_ERROR,$L_DELNOTE_NOTFOUND,$MN_USE_3PARTY_EDITOR,$MA_PRINTFILE,
			$MN_DATA_ROOT,$L_NEWDIR_MESS,$L_NEWDIR_ERROR,$L_SELECT_ITEM,$L_DELDIR_MESS,$L_DELDIR_ERROR,
			$L_DELDIR_ERROR,$L_MAPPANAME,$MA_ADMINFILE,$L_NEWDIR,$L_BUTTON_ALL,$L_SELECT_ITEM,
		    $L_BUTTON_DELDIR,$L_MAPPA_ACTUAL,$L_BUTTON_PREV,$L_NEW_NOTE,$MN_EDITOR,$L_BUTTON_SAVE,
			$L_BUTTON_DELETE,$L_BUTTON_PRINT,$L_PRINT_NAME,$L_PRINT_ITEM,$L_DELDIR;

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
		    echo("<form action=$MA_PRINTFILE ttarget=_blank id=3000 method=post enctype=multipart/form-data>");
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
				echo("<form action=$MA_ADMINFILE id=$i method=post enctype=multipart/form-data>");
				echo("  <input type='submit' name='submitloc' id='submitloc' value='$dir_list[$i]'>");
				echo("</form>");
			}
		    }
		    echo("<div class=spaceline></div><hr /><div class=spaceline></div>$L_NEWDIR");
		    echo("<form action=$MA_ADMINFILE id=100 method=post enctype=multipart/form-data>");
		    echo("	<input type='text' name='newdir' id='newdir' placeholder='$L_NEWDIR'>");
		    echo("  <input type='submit' name='submitdir' id='submitdir' value='$L_BUTTON_ALL'>");
		    echo("</form>");
		    		
		    echo("<div class=spaceline></div><hr /><div class=spaceline></div>$L_DELDIR");
		    echo("<form action=$MA_ADMINFILE id=101 method=post enctype=multipart/form-data>");
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
 		
			echo("<form action=$MA_ADMINFILE id=101 method=post enctype=multipart/form-data>");
			echo("  <input class='inputsubmitg' type='submit' name='submitprev' id='submitprev' value='$dn $L_MAPPA_ACTUAL'>");
			echo("</form>");
			echo("<form action=$MA_ADMINFILE id='1-1' method=post enctype=multipart/form-data>");
			echo("	<input type='hidden' name='notedir' id='notedir' value='$notedir'>");
			echo("  <input type='submit' name='submitfile' id='submitfile' value='$L_NEW_NOTE'>");
			echo("</form>");
			$dir_list=dirlist($notedir);
			$db=count($dir_list);
			for ($i=0;$i<$db;$i++){
				echo("<form action=$MA_ADMINFILE id=$i method=post enctype=multipart/form-data>");
				echo("	<input type='hidden' name='notedir' id='notedir' value='$notedir'>");
				if ($dir_list[$i]==$datat[0]){
					$st="class='button_active'";
				}else{
					$st="";
				}
				echo("  <input type='submit' name='submitfile' id='submitfile' $st value='$dir_list[$i]'>");
				echo("</form>");
			}
			echo("<form action=$MA_ADMINFILE id=101 method=post enctype=multipart/form-data>");
			echo("  <input class='inputsubmitr' type='submit' name='submitprev' id='submitprev' value='$L_BUTTON_PREV'>");
			echo("</form>");

			echo("</div>");
		
			echo("<div class='column54'>");
			echo("<div class='' style='padding-left:40px;'>");

			# generate content/editor
 		
			#echo($notefile);
			echo("<form action=$MA_ADMINFILE id=1000 method=post enctype=multipart/form-data>");
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
		
			echo('	<div class=spaceline></div>');
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
	
}


?>
