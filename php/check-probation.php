<?php

// --------------------------------------------------------------------------------------------
// Store input parameters
// --------------------------------------------------------------------------------------------

$name = $_GET['name'];


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
// Get UIN of user
// --------------------------------------------------------------------------------------------

// Split name into first and last names
$names = explode(" ", $name);
$first_name = $names[0];
$last_name = $names[1];

if (!($stmt = $conn->prepare("SELECT uin FROM members WHERE first_name = ? AND last_name = ?")))
	echo "UIN statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("ss", $first_name, $last_name))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "UIN execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "UIN result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Storing result into $uin
while ($stmt->fetch())
	$uin = $result;

// --------------------------------------------------------------------------------------------
// Get eventids of the probation event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT id FROM event_archive WHERE name = 'Probation'")))
	echo "Select probation id preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select probation id execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($id))
	echo "Select probation id result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	$probationid = $id;
}


// --------------------------------------------------------------------------------------------
// Check to see if the member is signed up for the "Probation Event"
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT 1 FROM points WHERE eventid = ? AND uin = ?")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("ss", $probationid, $uin))
	echo "Check probation select parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Check probation select execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Check probation select result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$probation = 0;
if ($stmt->fetch()) {
	$probation = 1;
}

// --------------------------------------------------------------------------------------------
// Return the result, and close any connections
// --------------------------------------------------------------------------------------------

echo $probation;

$stmt->close();
$conn->close();
?>
