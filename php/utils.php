<?php
session_start();

$memberAccess = array('nexus', 'events', 'create-event', 'calendar', 'links');

function checkNull ($array) {
	$nullKeys = array_keys($array, NULL);
	$missing = "";

	$i = 0;
	if ($nullKeys) {
		foreach($nullKeys as $key) {
			if ($i == 0)
				$missing .= $key;
			else
				$missing .= ", " . $key;

			$i++;
	 	}
		die("Missing " . $missing . ".  Please resubmit");
	}
}

function getUin() {
	return $_SESSION['uin'];
}
