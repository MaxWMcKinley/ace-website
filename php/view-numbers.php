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
// Get attendance of each member
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number FROM members ORDER BY first_name")))
	echo "Select probation preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select probation execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($first, $last, $email, $number))
	echo "Select probation result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	echo $first . " " . $last . " --- " . " Email: " . $email . ", Phone Number: " . $number . "<br><br>";
}

$stmt->close();
$conn->close();
?>
