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
// Retrieving all available events 
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT * FROM events")))
	echo "Select events preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select events execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$result = $stmt->get_result();
	
// Also getting the name of the officer for each event
if (!($name_stmt = $conn->prepare("SELECT first_name, last_name FROM members WHERE uin = ?")))
	echo "Select officer name preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

while ($row = $result->fetch_assoc()) {
	if (!$name_stmt->bind_param("s", $row['uin']))
		echo "Select officer name parameter binding failed with error number " . $name_stmt->errno . " (" . $stmt->error . ")";

	if (!$name_stmt->execute())
		echo "Select officer name execute failed with error number " . $name_stmt->errno . " (" . $stmt->error . ")";

	if (!$name_stmt->bind_result($first_name, $last_name))
		echo "Select officer name result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	// Stitch officer's name together
	while ($name_stmt->fetch())
		$officer_name = $first_name . " " . $last_name;

	// Generate 2 dimensional array containing event information
	$array[$row['name']] = array(
			"id" => $row['id'],
			"officer_name" => $officer_name,			
			"type" => $row['type'],
			"points" => $row['points'],			
			"date" => $row['date'],
			"freeze" => $row['freeze'],
		);
}


// --------------------------------------------------------------------------------------------
// JSON encode the array, return it and close all connections
// --------------------------------------------------------------------------------------------

$json = json_encode($array);
echo $json;

$stmt->close();
$name_stmt->close();
$conn->close();
?>
