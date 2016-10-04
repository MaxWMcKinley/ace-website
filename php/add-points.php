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

	$query = "SELECT `$type` FROM `$usertable` WHERE `uin`='$name'";
	$result = mysql_query($query);

	if ($result)
	{
		$current_points = mysql_fetch_array($result);
		$points = $current_points[0] + $points;
	}


	$query = "UPDATE `$usertable` SET `$type`='$points' WHERE `uin`='$name'";
	$result = mysql_query($query);

	mysql_close($connection);	// Close database connection

	include '../points-manager.html';
}
?>
