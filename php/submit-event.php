<?php

// --------------------------------------------------------------------------------------------
// Store input parameters 
// --------------------------------------------------------------------------------------------

// $uins = $_POST['uins'];
$eventid = $_GET['eventid'];


// --------------------------------------------------------------------------------------------
// Connect to database 
// --------------------------------------------------------------------------------------------

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";

// Connecting
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_errno)
	echo "Failed to connect to database with error number " . $conn->connect_errno . " (" . $conn->connect_error . ")";


// --------------------------------------------------------------------------------------------
// Handle people who didnt show up 
// --------------------------------------------------------------------------------------------



// --------------------------------------------------------------------------------------------
// Archive the event 
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("INSERT INTO event_archive(id, uin, name, type, points, date, freeze) SELECT id, uin, name, type, points, date, freeze FROM events WHERE id = ?")))
	echo "Event archive preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $eventid))
	echo "Event archive parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Event archive execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


// --------------------------------------------------------------------------------------------
// Get the number of points each shift is worth
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT points FROM events WHERE id = ?")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $eventid))
	echo "Select points parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "select points result binding result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$points = $result;
}


// --------------------------------------------------------------------------------------------
// Get all shiftids for the event 
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT shiftid FROM shifts WHERE eventid = ?")))
	echo "Select shiftids preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $eventid))
	echo "Select shiftids parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select shiftids execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Select shiftids result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$shiftids[$i] = $result;
	$i++;
}


// --------------------------------------------------------------------------------------------
// Get the uins of people who went to the event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT uin FROM sign_ups WHERE shiftid = ?")))
	echo "Select uin preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $shift))
	echo "Select uin parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
foreach ($shiftids as $shift) {
	if (!$stmt->execute())
		echo "Select uin execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->bind_result($result))
		echo "Select uin result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	while ($stmt->fetch()) {
		if(isset($attendees[$result]))
			$attendees[$result] += $points;
		else
			$attendees[$result] = $points;

		$i++;
	}
}


// --------------------------------------------------------------------------------------------
// Add attendees to the points table
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("INSERT INTO points (eventid, uin, points) VALUES (?, ?, ?)")))
	echo "Insert points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("ssi", $eventid, $uin, $point_amount))
	echo "Insert points parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($attendees as $uin => $point_amount) {
	if (!$stmt->execute())
		echo "Insert points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
}


// --------------------------------------------------------------------------------------------
// Delete sign ups related to this event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("DELETE FROM sign_ups WHERE shiftid = ?")))
	echo "Delete sign ups preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $shift))
	echo "Delete sign ups parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($shiftids as $shift) {
	if (!$stmt->execute())
		echo "Delete sign ups execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
}


// --------------------------------------------------------------------------------------------
// Delete shifts related to this event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("DELETE FROM shifts WHERE shiftid = ?")))
	echo "Delete shifts preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $shift))
	echo "Delete shifts parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($shiftids as $shift) {
	if (!$stmt->execute())
		echo "Delete shifts execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
}


// --------------------------------------------------------------------------------------------
// Delete this event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("DELETE FROM events WHERE id = ?")))
	echo "Delete event preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $eventid))
	echo "Delete event parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Delete event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


// --------------------------------------------------------------------------------------------
// Close connections
// --------------------------------------------------------------------------------------------

$stmt->close();
$conn->close();

?>