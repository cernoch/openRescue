<?php
header("Expires: 0");
header("Pragma: public");
header("Cache-Control: no-cache, must-revalidate");

function escSh($arg) {
	$arg = str_replace("'","'\''",$arg);
	return "'$arg'";
}

function escHttp($arg) {
	// FIXME: This is not very nice, but otherwise the browsers complain... 
	$arg = str_replace('"',"''",$arg);
	return "\"$arg\"";
}
?>
