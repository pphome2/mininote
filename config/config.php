<?php

 #
 # MiniNote - note manager for website
 #
 # info: main folder copyright file
 #
 #

# configuration - need change it


$COPYRIGHT="© 2019. <a href=https://github.com/pphome2/mininote>MiniNote</a>";

# need md5 passcode -- password
$MN_ADMIN_PASS="9F9858AFB7AD47216D2658E767E9E855";
$MN_PASS="5f4dcc3b5aa765d61d8327deb882cf99";

## auto logout - second
$LOGIN_TIMEOUT=600;

$ADMIN_USER=false;
$AUTO_DATE_TO_FIRST=true;

$MN_SITENAME="MiniNote - Test";
$MN_SITE_HOME="http://www.google.com";
$MN_DATA_ROOT="data";
$MN_CONFIG_DIR="config";

$MN_CSS="site.css";
$MN_CSS2="site2.css";
$MN_JS_BEGIN="";
$MN_JS_END="js_end.js";
$MN_HEADER="header.php";
$MN_FOOTER="footer.php";

$MN_ADMINFILE="mininote.php";
$MN_PRINTFILE="print.php";

$MN_USE_3PARTY_EDITOR=true;

# other editor for textarea

# TinyMCE
$MN_EDITOR[0]="	<script src='tinymce/tinymce.min.js' referrerpolicy='origin'></script>
		<script>
		    tinymce.init({
			selector: '#newtext',
			language: 'hu_HU',
			plugins: 'advlist,image,paste,cose,imagetools,link,table',
			browser_spellcheck: true
		    });
		</script>
		";

# CKEditor
#$MN_EDITOR[0]="	<script src='ckeditor/ckeditor.js'></script>
#		<script src='ckeditor/translations/hu.js'></script>
#		<script>
#		    ClassicEditor
#		    .create( document.querySelector( '#newtext' ), {
#			language: 'hu',
#			height: '70%'
#		    } )
#		    .catch( error => {
#			console.log( error );
#		    } );
#		</script>
#		";

# language
$MN_LANGFILE="hu.php";


?>
