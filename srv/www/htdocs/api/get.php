<?php
require_once("common.php");

$path = $_SERVER["PATH_INFO"];

if ($path == "") {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "No path specified.\nUsage: api/get.php/path_to_your_file";
	die;
}

exec("sudo or-dir ".escSh($path), $results, $retval);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

if (sizeof($results) == 0) {
	header("x", true, 404);
	header('Content-type: text/plain');
	echo "File '$path' not found!";
	die;
}

if (sizeof($results) > 1) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo "More than 1 file matches the request!";
	die;
}


$tmp = explode("\t", $results[0], 6);
$file = array(
	"type" => $tmp[0],
	"flag" => $tmp[1],
	"size" => $tmp[2],
	"mime" => $tmp[3],
	"char" => $tmp[4],
	"name" => $tmp[5]);

header("Pragma: public");
header("Expires: 0");

header("Content-Transfer-Encoding: binary");
header("Content-Type: {$file["mime"]}"); 
header("Content-Length: {$file["size"]}");
header("Content-Disposition: inline; filename=".escHttp($file["name"]));

passthru("sudo cat -- ".escSh("/mnt".$path));

//header('Content-type: application/json');
//echo json_encode($data);
?>
