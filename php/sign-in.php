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
// Get last sign in date of the user
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT last FROM attendance WHERE uin = ?")))
	echo "Select attendance preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "Select attendance parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select attendance execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Select attendance result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$attendance = 0;
while ($stmt->fetch()) {
	$last = $result;
}


$permitted = true;

date_default_timezone_set('America/Chicago');
$today = date('Y-m-d');

if ($last != NULL) {
	$difference = time() - strtotime($last);

	if ($difference < 518400)
		$permitted = false;
}


if ($permitted) {
	// --------------------------------------------------------------------------------------------
	// Add attendance point to the user
	// --------------------------------------------------------------------------------------------

	if (!($stmt = $conn->prepare("INSERT INTO attendance (uin, attendance, last) VALUES (?, 1, ?) ON DUPLICATE KEY UPDATE attendance = attendance + 1, last = ?")))
		echo "Insert attendance preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	if (!$stmt->bind_param("sss", $uin, $today, $today))
		echo "Insert attendance parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->execute())
		echo "Insert attendance execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


	// --------------------------------------------------------------------------------------------
	// Get attendance of the user
	// --------------------------------------------------------------------------------------------

	if (!($stmt = $conn->prepare("SELECT attendance FROM attendance WHERE uin = ?")))
		echo "Select attendance preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	if (!$stmt->bind_param("s", $uin))
		echo "Select attendance parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->execute())
		echo "Select attendance execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->bind_result($result))
		echo "Select attendance result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	$attendance = 0;
	while ($stmt->fetch()) {
		$attendance = $result;
	}
}
else
	$attendance = 0; // 0 attendance means the user was not allowed to sign in


// --------------------------------------------------------------------------------------------
// Return attendance and close connections
// --------------------------------------------------------------------------------------------

echo $attendance;

$stmt->close();
$conn->close();
?>