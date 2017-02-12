<?php

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
// Get eventids and points of the user's completed events
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT members.first_name, members.last_name, old_points.service, old_points.fundraising FROM members, old_points WHERE members.uin = old_points.uin")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($first, $last, $service, $fundraising))
	echo "Select points result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	echo $first . " " . $last . ", service: " . $service . ", fundraising: " . $fundraising . "\r\n";	
}


$stmt->close();
$conn->close();
?>