<?php
$uin = $_POST['uins'];
$eventId = $_POST['eventId'];

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";

// Connecting to database
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_errno)
	echo "Failed to connect to database with error number " . $conn->connect_errno . " (" . $conn->connect_error . ")";

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("SELECT shiftid FROM shifts WHERE eventid = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("s", $eventId))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Binding query result
if (!$stmt->bind_result($result))
	echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$shiftids[$i] = $result;
	$i++;
}

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("DELETE FROM sign_ups WHERE uin = ? AND shiftid = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("ss", $uin, $shiftid))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
foreach ($shiftids as $shiftid) {
	// Execute statement
	if (!$stmt->execute())
		echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
}

echo "Succesfully removed from sign up list";

$stmt->close();
$conn->close();
?>