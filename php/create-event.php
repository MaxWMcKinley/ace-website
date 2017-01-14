<?php

if(_POST)
{
	// Setting up form variables
	$name = $_POST['name'];
	$event_name = $_POST['event_name'];
	$points = $_POST['points'];
	$points_type = $_POST['points_type'];
	$date = $_POST['date'];
	$freeze = $_POST['freeze'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$spots = $_POST['spots'];

	// Data validation
	if(!$name) { die("Name was not entered. You can just click back and add it in, then resubmit."); }
	if(!$event_name) { die("Event name was not entered. You can just click back and add it in, then resubmit."); }
	if(!$points) { die("Points per hour was not entered. You can just click back and add it in, then resubmit."); }
	if(!$date) { die("Date was not entered. You can just click back and add it in, then resubmit."); }
	if(!$freeze) { die("Freeze date was not entered. You can just click back and add it in, then resubmit."); }
	if(!$start) { die("Start time was not entered. You can just click back and add it in, then resubmit."); }
	if(!$end) { die("End time was not entered. You can just click back and add it in, then resubmit."); }
	if(!$spots) { die("Number of spots available was not entered. You can just click back and add it in, then resubmit."); }

	// Setting connection variables
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
		
	// Create a unique id for the event
	$event_id = uniqid();

	// Preparing SQL statement to create event
	if (!($stmt = $conn->prepare("INSERT INTO events (id, uin, name, points, type, date, freeze) VALUES (?, ?, ?, ?, ?, ?, ?)")))
		echo "Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	// Bind parameters to statement
	if (!$stmt->bind_param("sssisss", $event_id, $uin, $event_name, $points, $points_type, $date, $freeze))
		echo "Binding event parameters failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	// Execute statement
	if (!$stmt->execute())
		echo "Execute insert failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


	// Preparing SQL statement to create shifts
	if (!($stmt = $conn->prepare("INSERT INTO shifts (shiftid, eventid, shift_start, shift_end, spots) VALUES (?, ?, ?, ?, ?)")))
		echo "Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	// Bind parameters to statement
	if (!$stmt->bind_param("ssssi", $shift_id, $event_id, $shift_start, $shift_end, $shift_spots))
		echo "Binding event parameters failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


	foreach ($start as $key => $shift_start) {
		$shift_end = $end[$key];
		$shift_spots = $spots[$key];
		$shift_id = uniqid();

		if (!$stmt->execute())
			echo "Shift insert failed with error number " . $stmt->errno . " (" . $stmt->error . ")";
	}

	echo "Event created succesfully";

	$stmt->close();
	$conn->close();
}
?>
