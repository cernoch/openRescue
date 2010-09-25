<?php
header('Cache-Control: no-cache, must-revalidate');

exec("sudo or-ipaddr", $results, $retval);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

header('Content-type: application/json');
echo json_encode($results);
?>
