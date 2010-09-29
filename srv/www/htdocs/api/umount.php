<?php
require_once("common.php");

$data = json_decode(file_get_contents('php://input'));

if ($data == null) {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "Request is not in JSON format.";
	die;
}

exec("sudo or-umount ".escSh($data->name), $results, $retval);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

if (count($results) != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

$data->stat = "offline";

sleep(2); // Wait until all devices settle down...

header('Content-type: application/json');
echo json_encode($data);
?>