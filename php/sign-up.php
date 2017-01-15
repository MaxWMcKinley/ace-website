<?php
$name = $_POST['name'];
$shifts = $_POST['shifts'];

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
if (!($stmt = $conn->prepare("INSERT INTO sign_ups (shiftid, uin) VALUES (?, ?)")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("ss", $id))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$stmt->close();
$conn->close();
?>