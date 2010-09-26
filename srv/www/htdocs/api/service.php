<?php
header('Cache-Control: no-cache, must-revalidate');

$data = json_decode(file_get_contents('php://input'));

// For all given services, execute the given command

if ($data != null)
	foreach ($data as $service => $params) {
		if (isset($params->command)) {
			$command = "sudo or-service ".escapeshellcmd($service)." ".escapeshellcmd($params->command);
			exec($command, $results, $retval);
			// If the command failed, stop the script and show the result
			if ($retval != 0) {
				header("x", true, 500);
				header('Content-type: text/plain');
				echo implode("\n", $results);
				die;
			} else {
				$data->{$service}->stdout = implode("\n", $results);
			}
			unset($results);
			unset($retval);
		}
	}


exec("sudo or-service", $results, $retval);

if ($retval != 0) {
	header("x", true, 500);
	header('Content-type: text/plain');
	echo implode("\n", $results);
	die;
}

foreach ($results as $i => $row) if (strlen($row) > 1) {
	$tmp = explode("\t", $row, 2);
	$data->{$tmp[0]}->status = $tmp[1];
}

header('Content-type: application/json');
echo json_encode($data);
?>
