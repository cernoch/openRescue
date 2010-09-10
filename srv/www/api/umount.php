<?php
header('Cache-Control: no-cache, must-revalidate');

$data = json_decode(file_get_contents('php://input'));

if ($data == null) {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "Request is not in JSON format.";
	die;
}

$command = "sudo or-umount \"".escapeshellcmd($data->name)."\"";
$retval = exec($command, $results);

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

header('Content-type: application/json');
echo json_encode($data);
?>