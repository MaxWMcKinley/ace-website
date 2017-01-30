<?php

// --------------------------------------------------------------------------------------------
// Store input parameters 
// --------------------------------------------------------------------------------------------

$name = $_POST['name'];
$shifts = $_POST['shifts'];


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
// Get UIN of the member
// --------------------------------------------------------------------------------------------

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


// --------------------------------------------------------------------------------------------
// Check to make sure all shifts still have spots available
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT spots FROM shifts WHERE shiftid = ?")))
	echo "Select spots preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Select spots parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

foreach ($shifts as $id) {
	if (!$stmt->execute())
		echo "Select spots execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->bind_result($result))
		echo "Select spot result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
	
	// Storing result into $spots
	while ($stmt->fetch())
		$spots = $result;

	$no_spot = false;

	if ($spots <= 0)
		$no_spot = true;
}


// --------------------------------------------------------------------------------------------
// Sign up the member for his selected shifts
// --------------------------------------------------------------------------------------------

// Statement that will sign the member up by inserting uin into sign up table
if (!($stmt = $conn->prepare("INSERT INTO sign_ups (shiftid, uin) VALUES (?, ?)")))
	echo "Insert sign ups preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("ss", $id, $uin))
	echo "Insert sign ups parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Statement that decrements the number of spots
if (!($dec_stmt = $conn->prepare("UPDATE shifts SET spots = spots - 1 where shiftid = ?")))
	echo "Decrement spots preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$dec_stmt->bind_param("s", $id))
	echo "Decrement spots parameter binding failed with error number " . $dec_stmt->errno . " (" . $stmt->error . ")";

if ($no_spots) {
	echo "One of the shifts you tried to sign up for ran out of spots, please try again";
}
else {
	foreach ($shifts as $id){
		if (!$stmt->execute())
			echo "Insert sign ups execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		if (!$dec_stmt->execute())
			echo "Decrement spots execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
	}
}

echo "Sign Up Complete";

$stmt->close();
$conn->close();
?>