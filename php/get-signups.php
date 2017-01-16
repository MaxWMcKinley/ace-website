<?php
$name = $_GET['name'];

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
if (!($stmt = $conn->prepare("SELECT shiftid FROM sign_ups WHERE uin = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("s", $uin))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Execute statement
if (!$stmt->execute())
	echo "UIN execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Binding query result
if (!$stmt->bind_result($shiftid))
	echo "Binding uin result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$array[$i] = $shiftid;
	$i++;
}

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("SELECT * FROM events, shifts WHERE shifts.shiftid = ? AND shifts.eventid = events.id")))
	echo "Statement preparation to get events failed with error number " . $conn->errno . " (" . $conn->error . ")";

foreach ($array as $value) {
	// Bind parameters to statement
	if (!$stmt->bind_param("s", $value))
		echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	// Execute statement
	if (!$stmt->execute())
		echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	// Storing query result
	$result = $stmt->get_result();
	
	// Preparing SQL statement to get all available events
	if (!($name_stmt = $conn->prepare("SELECT first_name, last_name FROM members WHERE uin = ?")))
		echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	// Creating array of arrays of the events
	while ($row = $result->fetch_assoc()) {

		// Bind parameters to statement
		if (!$name_stmt->bind_param("s", $row['uin']))
			echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		// Execute statement
		if (!$name_stmt->execute())
			echo "Name execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		// Binding query result
		if (!$name_stmt->bind_result($first_name, $last_name))
			echo "Binding name result failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

		while ($name_stmt->fetch())
			$officer_name = $first_name . " " . $last_name;

		$events[$row['name']] = array(
				"id" => $row['id'],
				"officer_name" => $officer_name,			
				"type" => $row['type'],
				"points" => $row['points'],			
				"date" => $row['date'],
				"freeze" => $row['freeze'],
			);
	}
}

// Encoding the array and returning
$json = json_encode($events);
echo $json;

$stmt->close();
$name_stmt->close();
$conn->close();
?>