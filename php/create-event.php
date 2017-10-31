<?php

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("connect.php");
require("utils.php");


// --------------------------------------------------------------------------------------------
// Check for null paramters and store their values in local variables
// --------------------------------------------------------------------------------------------

if ($_POST['description'] == NULL) {
	$_POST['description'] = "No description Available.";
}

checkNull($_POST);

// Setting up form variables
$event_name = $_POST['name'];
$points = $_POST['points'];
$type = $_POST['type'];
$date = $_POST['date'];
$freeze = $_POST['freeze'];
$starts = $_POST['start'];
$ends = $_POST['end'];
$spots = $_POST['spots'];
$description = $_POST['description'];

$uin = getUin();


// --------------------------------------------------------------------------------------------
// Create event
// --------------------------------------------------------------------------------------------

// Preparing SQL statement to create event
if (!($stmt = $conn->prepare("INSERT INTO events (id, uin, name, points, type, date, freeze, start, end, spots, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")))
	 die("Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")");

// Bind parameters to statement
if (!$stmt->bind_param("sssisssssis", $id, $uin, $name, $points, $type, $date, $freeze, $start, $end, $numSpots, $description))
	die("Binding event parameters failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

// Create a unique event for each shift
$count = 1;
foreach ($starts as $key => $start) {
	$name = $event_name;
	if (count($starts) > 1) {
		$name .= (" - Shift " . $count);
	}
	$end = $ends[$key];
	$numSpots = $spots[$key];
	if (!$numSpots) {
		$numSpots = 99;
	}
	$id = uniqid($count);
	$count++;

	if (!$stmt->execute())
		die("Event insert failed with error number " . $stmt->errno . " (" . $stmt->error . ")");
}


// --------------------------------------------------------------------------------------------
// Return the result, and close any connections
// --------------------------------------------------------------------------------------------

echo "Event created succesfully. Feel free to press back to submit another one.";

$stmt->close();
$conn->close();

?>
