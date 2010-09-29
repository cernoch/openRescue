<?php
require_once("common.php");

$path = $_SERVER["PATH_INFO"];
$part = explode("/", $path);
while (sizeof($part) > 0 && $part[0] == "") array_shift($part);
while (sizeof($part) > 0 && $part[sizeof($part)-1] == "") array_pop($part);

if (sizeof($part) < 2) {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "No path specified.\nUsage: api/pack.php/format/path";
	die;
}

$type = $part[0]; array_shift($part);
$file = $part[sizeof($part)-1];
$path = implode("/",$part); unset($part);

if ($type == "tgz") {
	header("Content-Type: application/x-gtar");
	header("Content-Disposition: attachment; filename=".escHttp($file.".tar.gz"));
} else if ($type == "zip") {
	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=".escHttp($file.".zip"));

} else {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "Unsupported archive format: '$type'.\nAllowed values are 'zip' and 'tgz'.";
	die;	
}
		
header("Content-Transfer-Encoding: binary");
passthru("sudo or-pack ".escSh($type)." ".escSh($path));
?>
