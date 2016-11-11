<?php

if(_POST)
{
	// Setting up form variables
	$name = $_POST['name'];
	$event_name = $_POST['event_name'];
	$points = $_POST['points'];
	$points_type = $_POST['points_type'];
	$date = $_POST['date'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$freeze = $_POST['freeze'];
	$shift = $_POST['shift'];


	// Data validation
	if(!$name) { die("Name was not entered. You can just click back and add it in, then resubmit."); }
	if(!$event_name) { die("Event name was not entered. You can just click back and add it in, then resubmit."); }
	if(!$points) { die("Points per hour was not entered. You can just click back and add it in, then resubmit."); }
	if(!$date) { die("Date was not entered. You can just click back and add it in, then resubmit."); }
	if(!$start) { die("Start time was not entered. You can just click back and add it in, then resubmit."); }
	if(!$end) { die("End time was not entered. You can just click back and add it in, then resubmit."); }
	if(!$freeze) { die("Freeze date was not entered. You can just click back and add it in, then resubmit."); }
	if(!$shift) { die("Shift length was not entered. You can just click back and add it in, then resubmit."); }

	// Setting connection variables
	$hostname="localhost";
	$username="acesan7_max";
	$password="dbpw2669";
	$dbname="acesan7_db";
	$usertable="events";

	// Connect to the database
	$connection = mysql_connect($hostname, $username, $password);
	if (!$connection) { die('Could not connect: ' . mysql_error()); }
	mysql_select_db($dbname, $connection);

	// Split name into first and last names
	$names = explode(" ", $name);
	$first_name = $names[0];
	$last_name = $names[1];

	// Get uin of person creating the event
	$query = "SELECT `uin` FROM `members` WHERE `first_name`='$first_name' AND `last_name`='$last_name'";
	$result = mysql_query($query);

	if ($result) {
		$row = mysql_fetch_array($result);
		$uin = $row[0];
	}
	else {	// No result from database
			die("Nothing in database result when trying to get UIN");
	}

	$id = uniqid();

	// Update database with new point total
	$query = "INSERT INTO `$usertable` (`id`, `uin`, `name`, `points`, `type`, `date`, `start`, `end`, `shift`, `freeze`) VALUES ('$id', '$uin', '$event_name', '$points', '$points_type', '$date', '$start', '$end', '$shift', '$freeze')";
	$result = mysql_query($query);

	if(!$result) echo "<h2>Update was unsuccesful</h2>";
	else echo "<h2>Event Created</h2>";

	mysql_close($connection);	// Close database connection
}
?>
