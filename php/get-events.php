<?php

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
if (!($stmt = $conn->prepare("SELECT * FROM events")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "Execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Storing query result
$result = $stmt->get_result();
	
// Creating array of arrays of the events
while ($row = $result->fetch_assoc()) {
	$array[$row['name']] = array(
			"id" => $row['id'],
			"uin" => $row['uin'],			
			"type" => $row['type'],
			"points" => $row['points'],			
			"shift_length" => $row['shift_length'],
			"shift_amount" => $row['shift_amount'],
			"date" => $row['date'],
			"freeze" => $row['freeze'],
			"start" => $row['start'],
			"end" => $row['end']
		);
}

// Encoding the array and returning
$json = json_encode($array);
echo $json;
?>
