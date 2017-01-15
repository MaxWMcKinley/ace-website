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
if (!($stmt = $conn->prepare("SELECT shift_start, shift_end, spots FROM shifts WHERE eventid = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("s", $id))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Binding query result
if (!$stmt->bind_result($start, $end, $spots))
	echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$array[$i] = array(
		"start" => $start,
		"end" => $end,
		"spots" => $spots
	);
	$i++;
}

$json = json_encode($array);
echo $json;

$stmt->close();
$conn->close();
?>