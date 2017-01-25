<?php
$id = $_GET['id'];

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
if (!($stmt = $conn->prepare("SELECT DISTINCT sign_ups.uin FROM sign_ups, shifts WHERE sign_ups.shiftid = shifts.shiftid AND shifts.eventid = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("s", $id))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Binding query result
if (!$stmt->bind_result($result))
	echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$uins[$i] = $result;
	$i++;
}

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("SELECT first_name, last_name FROM members WHERE uin = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("s", $uin))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
foreach ($uins as $uin) {
	// Execute statement
	if (!$stmt->execute())
		echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	// Binding query result
	if (!$stmt->bind_result($first_name, $last_name))
		echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	while ($stmt->fetch()) {
		$names[$uin] = $first_name . " " . $last_name;
		$i++;
	}
}

$json = json_encode($names);
echo $json;

$stmt->close();
$conn->close();
?>