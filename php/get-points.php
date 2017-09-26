<?php

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("utils.php");
require("connect.php");


// --------------------------------------------------------------------------------------------
// Get user's UIN
// --------------------------------------------------------------------------------------------

$uin = getUin();


// --------------------------------------------------------------------------------------------
// Get eventids and points of the user's completed events
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT eventid, points FROM points WHERE uin = ?")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "Select points parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($eventid, $points))
	echo "Select points result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	$events[$eventid] = $points;
}


// --------------------------------------------------------------------------------------------
// Get information about event from the archive and generate an array with the results
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT name, type, date FROM event_archive WHERE id = ?")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Select points parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($events as $id => $point_amount) {
	if (!$stmt->execute())
		echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->bind_result($name, $type, $date))
		echo "Select points result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	while ($stmt->fetch()) {
		$array[$id] = array(
			"points" => $point_amount,
			"name" => $name,
			"type" => $type,
			"date" => $date
		);
	}
}


// --------------------------------------------------------------------------------------------
// Encode json, return the result, and close any connections
// --------------------------------------------------------------------------------------------

// Encoding the array and returning
$json = json_encode($array);
echo $json;

$stmt->close();
$conn->close();
?>
