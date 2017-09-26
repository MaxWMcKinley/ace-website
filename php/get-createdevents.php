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
// Get events made by the members and format them in a usable way for the front end
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT * FROM events WHERE uin = ?")))
	echo "Statement preparation to get events failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$result = $stmt->get_result();

if (!($name_stmt = $conn->prepare("SELECT name FROM nmembers WHERE uin = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

while ($row = $result->fetch_assoc()) {

	if (!$name_stmt->bind_param("s", $row['uin']))
		echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$name_stmt->execute())
		echo "Name execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$name_stmt->bind_result($name))
		echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	while ($name_stmt->fetch())
		$officer_name = $name;

	$events[$row['name']] = array(
			"id" => $row['id'],
			"officer_name" => $officer_name,
			"type" => $row['type'],
			"points" => $row['points'],
			"date" => $row['date'],
			"freeze" => $row['freeze'],
		);
}


// --------------------------------------------------------------------------------------------
// Return result and close connections
// --------------------------------------------------------------------------------------------

$json = json_encode($events);
echo $json;

$stmt->close();
$name_stmt->close();
$conn->close();
?>
