<?php


header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

$retval = exec("sudo or-list", $results);

if ($retval != 0) {
	header("x", true, 500);
	echo implode("\n", $results);
	die;
}

$out = array();
foreach ($results as $i => $row)
	if (strlen($row) > 1) {
	$tmp = explode("\t", $row, 6);
	$out[$tmp[5]] = array(
		"name" => $tmp[0],
		"type" => $tmp[1],
		"fsys" => $tmp[2],
		"stat" => $tmp[3],
		"size" => $tmp[4]);
}




echo json_encode($out);

?>
