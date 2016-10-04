<?php

if(_POST)
{
	// Setting up form variables
	$type = $_POST['type'];
	$points = $_POST['points'];
	$name = $_POST['name'];

	// Data validation
	if(!$points) { die("UIN was not entered. You can just click back and add it in, then resubmit."); }
	if(!$name) { die("First name was not entered. You can just click back and add it in, then resubmit."); }

	// Setting connection variables
	$hostname="localhost";
	$username="acesan7_max";
	$password="dbpw2669";
	$dbname="acesan7_db";
	$usertable="points";

	// Connect to the database
	$connection = mysql_connect($hostname, $username, $password);
	if (!$connection) { die('Could not connect: ' . mysql_error()); }
	mysql_select_db($dbname, $connection);

	// Split name into first and last names
	$names = explode(" ", $name);
	$first_name = $names[0];
	$last_name = $names[1];

	// Get uin of ACE member for use with points table
	$query = "SELECT `uin` FROM `members` WHERE `first_name`='$first_name' AND `last_name`='$last_name'";
	$result = mysql_query($query);

	if ($result) {
		$row = mysql_fetch_array($result);
		$uin = $row[0];
	}
	else {	// No result from database
			die("Nothing in database result when trying to get UIN");
	}

	// Get the current points for the ACE member in this category
	$query = "SELECT `$type` FROM `$usertable` WHERE `uin`='$uin'";
	$result = mysql_query($query);

	// Calculate how many total points the member should now have
	if ($result) {
		$current_points = mysql_fetch_array($result);
		$points = $current_points[0] + $points;
	}
	else {	// No result from database
			die("Nothing in database result when trying to get points");
	}
	// Update database with new point total
	$query = "UPDATE `$usertable` SET `$type`='$points' WHERE `uin`='$uin'";
	$result = mysql_query($query);

	if(!$result) echo "Update was unsuccesful";

	mysql_close($connection);	// Close database connection

	include '../points-manager.html';
}
?>
