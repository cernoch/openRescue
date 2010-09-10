<?php
header('Cache-Control: no-cache, must-revalidate');

$data = json_decode(file_get_contents('php://input'));

if ($data == null) {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "Request is not in JSON format.";
	die;
}

$command = "sudo or-mount \"".escapeshellcmd($data->path)."\"";
$retval = exec($command, $results);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

if (count($results) != 1) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo "Mount output unreadable:\n";
	echo implode("\n", $results);
	die;
}

$data->name = $results[0];
$data->stat = "mounted";

header('Content-type: application/json');
echo json_encode($data);
?>