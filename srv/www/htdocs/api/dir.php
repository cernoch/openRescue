<?php
header('Cache-Control: no-cache, must-revalidate');

$data = json_decode(file_get_contents('php://input'));

if ($data == null) {
	header("x", true, 415);
	header('Content-type: text/plain');
	echo "Request is not in JSON format.";
	die;
}

$command = "sudo or-dir \"".($data->path)."\"";
exec($command, $results, $retval);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

function cmp($a, $b)
{
	$x = strcmp($a["type"], $b["type"]);
    if ($x != 0) return $x;
    
	$x = strcmp(mb_strtoupper($a["name"]), mb_strtoupper($b["name"]));
    if ($x != 0) return $x;

    return 0;
}


$data->entries = array();
foreach ($results as $i => $row) if (strlen($row) > 1) {
	$tmp = explode("\t", $row, 6);
	$entry = array(
		"type" => $tmp[0],
		"flag" => $tmp[1],
		"size" => $tmp[2],
		"mime" => $tmp[3],
		"char" => $tmp[4],
		"name" => $tmp[5]);
	
	array_push($data->entries, $entry);
}
usort($data->entries, "cmp"); 

header('Content-type: application/json');
echo json_encode($data);
?>
