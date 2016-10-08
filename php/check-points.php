<?php
$name = $_POST['name'];

// Split name into first and last names
$names = explode(" ", $name);
$first_name = $names[0];
$last_name = $names[1];

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";
$usertable="points";

// Connect to the database
$connection = mysql_connect($hostname, $username, $password);
if (!$connection) { die('Could not connect: ' . mysql_error()); }
mysql_select_db($dbname, $connection);

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

$query = "SELECT * FROM `$usertable` where `uin`='$uin'";
$result = mysql_query($query);

if ($result) {
	$row = mysql_fetch_array($result);
}

// Output html table with point information
echo "<h2>Points You Have</h2>";
echo "<table>";
	echo "<tr>";
		echo "<th>Total</th>";
		echo "<th>Recruitment</th>";
		echo "<th>Service</th>";
		echo "<th>Fundraising</th>";
		echo "<th>Academic</th>";
		echo "<th>Athletics</th>";
		echo "<th>Social</th>";
		echo "<th>Tailgate</th>";
		echo "<th>Corporate Relations</th>";
		echo "<th>PR</th>";
		echo "<th>Songfest</th>";
		echo "<th>Special Events</th>";
		echo "<th>Attendance</th>";
		echo "<th>Family</th>";
		echo "<th>Sober Rides</th>";
		echo "<th>Flex</th>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>" . $row['required'] . "</td>";
		echo "<td>" . $row['recruitment'] . "</td>";
		echo "<td>" . $row['service'] . "</td>";
		echo "<td>" . $row['fundraising'] . "</td>";
		echo "<td>" . $row['academic'] . "</td>";
		echo "<td>" . $row['athletics'] . "</td>";
		echo "<td>" . $row['social'] . "</td>";
		echo "<td>" . $row['tailgate'] . "</td>";
		echo "<td>" . $row['corporate_relations'] . "</td>";
		echo "<td>" . $row['pr'] . "</td>";
		echo "<td>" . $row['songfest'] . "</td>";
		echo "<td>" . $row['special_events'] . "</td>";
		echo "<td>" . $row['attendance'] . "</td>";
		echo "<td>" . $row['family'] . "</td>";
		echo "<td>" . $row['sober_rides'] . "</td>";
		echo "<td>" . $row['flex'] . "</td>";
	echo "</tr>";
echo "</table>";
?>
