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

if (!($stmt = $conn->prepare("SELECT members.first_name, members.last_name, members.email, attendance.attendance FROM members, attendance WHERE members.uin = attendance.uin")))
	echo "Select attendance preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select attendance execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($first, $last, $email, $attendance))
	echo "Select attendance result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	echo $first . " " . $last . ", " . $email . " - Attendance: " . $attendance . "<br>";
}

$stmt->close();
$conn->close();
?>
