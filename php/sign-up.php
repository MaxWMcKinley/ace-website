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

// Split name into first and last names
$names = explode(" ", $name);
$first_name = $names[0];
$last_name = $names[1];

// Preparing SQL statement to get users UIN
if (!($stmt = $conn->prepare("SELECT uin FROM members WHERE first_name = ? AND last_name = ?")))
	echo "UIN statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("ss", $first_name, $last_name))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "UIN execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Binding query result
if (!$stmt->bind_result($result))
	echo "Binding uin result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
	
// Storing result into $uin
while ($stmt->fetch())
	$uin = $result;

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("INSERT INTO sign_ups (shiftid, uin) VALUES (?, ?)")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("ss", $id, $uin))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($shifts as $id){
	// Execute statement
	if (!$stmt->execute())
		echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
}

echo "Sign Up Complete";

$stmt->close();
$conn->close();
?>