<?php

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("utils.php");
require("connect.php");


// --------------------------------------------------------------------------------------------
// Check for null paramters and store their values in local variables
// --------------------------------------------------------------------------------------------

checkNull($_GET);

$id = $_GET['id'];


// --------------------------------------------------------------------------------------------
// Get UINs of all members signed up for the event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT uin FROM sign_ups WHERE id = ?")))
	echo "Select uins preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Select uins parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select uins execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Binding uins result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$uins[$i] = $result;
	$i++;
}


// --------------------------------------------------------------------------------------------
// Get the names of each member who is signed up for the event
// --------------------------------------------------------------------------------------------

$json = 0;
if ($uins != 0) {
	if (!($stmt = $conn->prepare("SELECT name FROM nmembers WHERE uin = ?")))
		echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	if (!$stmt->bind_param("s", $uin))
		echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	$i = 0;
	foreach ($uins as $uin) {
		if (!$stmt->execute())
			echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		if (!$stmt->bind_result($name))
			echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		while ($stmt->fetch()) {
			$names[$uin] = $name;
			$i++;
		}
	}
	$json = json_encode($names);
}


// --------------------------------------------------------------------------------------------
// Return result and close connections
// --------------------------------------------------------------------------------------------

echo $json;

$stmt->close();
$conn->close();
?>
