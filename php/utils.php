<?php
session_start();

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
