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
// Get attendance of each member
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT name, email, phone FROM nmembers ORDER BY name")))
	echo "Select probation preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select probation execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($name, $email, $number))
	echo "Select probation result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	echo $name . " --- " . " Email: " . $email . ", Phone Number: " . $number . "<br><br>";
}

$stmt->close();
$conn->close();
?>
