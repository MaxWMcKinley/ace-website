<?php

// --------------------------------------------------------------------------------------------
// Store input parameters 
// --------------------------------------------------------------------------------------------

$id = $_GET['id'];


// --------------------------------------------------------------------------------------------
// Connect to database
// --------------------------------------------------------------------------------------------

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";

// Connecting to database
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_errno)
	echo "Failed to connect to database with error number " . $conn->connect_errno . " (" . $conn->connect_error . ")";


// --------------------------------------------------------------------------------------------
// Gets shift info for the event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT shiftid, shift_start, shift_end, spots FROM shifts WHERE eventid = ?")))
	echo "Select shift preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Select shift parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select shift execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($id, $start, $end, $spots))
	echo "Select shift result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Generate array to store shift information
$i = 0;
while ($stmt->fetch()) {
	$array[$i] = array(
		"id" => $id,
		"start" => $start,
		"end" => $end,
		"spots" => $spots
	);
	$i++;
}


// --------------------------------------------------------------------------------------------
// JSON encode array, return it and close all connections
// --------------------------------------------------------------------------------------------

$json = json_encode($array);
echo $json;

$stmt->close();
$conn->close();
?>