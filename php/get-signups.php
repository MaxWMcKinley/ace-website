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
// Retrieve events the user has signed up for
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT id FROM sign_ups WHERE uin = ?")))
	echo "Select events preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "Select events parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select events execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($id))
	echo "Select events result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Storing events in array
$i = 0;
while ($stmt->fetch()) {
	$array[$i] = $id;
	$i++;
}

if ($array == NULL)
	return("There are no signups");

// --------------------------------------------------------------------------------------------
// Retreieve event information
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT * FROM events WHERE id = ?")))
	echo "Select event info preparation to get events failed with error number " . $conn->errno . " (" . $conn->error . ")";

foreach ($array as $value) {
	if (!$stmt->bind_param("s", $value))
		echo "Select event info parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->execute())
		echo "Select event info execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	$result = $stmt->get_result();

	// Additional query to get the name of the officer for each event
	if (!($name_stmt = $conn->prepare("SELECT name FROM nmembers WHERE uin = ?")))
		echo "Select officer name preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	while ($row = $result->fetch_assoc()) {

		if (!$name_stmt->bind_param("s", $row['uin']))
			echo "Select officer name parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		if (!$name_stmt->execute())
			echo "Select officer name execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		if (!$name_stmt->bind_result($name))
			echo "Select officer name result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		// Stitching the officer's name together
		while ($name_stmt->fetch()) {
			$officer_name = $name;
		}

		// Constructing 2 dimensional array to store all the events and their info
		$events[$row['name']] = array(
				"id" => $row['id'],
				"officer_name" => $officer_name,
				"uin" => $uin,
				"type" => $row['type'],
				"points" => $row['points'],
				"date" => $row['date'],
				"freeze" => $row['freeze'],
			);
	}
}


// --------------------------------------------------------------------------------------------
// JSON encode array, return it and close all connections
// --------------------------------------------------------------------------------------------

$json = json_encode($events);
echo $json;

$stmt->close();
$name_stmt->close();
$conn->close();
?>
