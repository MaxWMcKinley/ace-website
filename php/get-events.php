<?php

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";
$usertable="events";

// Connect to the database
$connection = mysql_connect($hostname, $username, $password);
if (!$connection) { die('Could not connect: ' . mysql_error()); }
mysql_select_db($dbname, $connection);

// Get all the events
$query = "SELECT * FROM `$usertable`";
$result = mysql_query($query);

if (!$result) echo "Query failed";


// Output html table with point information
echo "<h2>Events</h2>";
echo "<table>";
	echo "<tr>";
		echo "<th>Name</th>";
		echo "<th>Officer in Charge</th>";
		echo "<th>Points per Shift</th>";
		echo "<th>Points Type</th>";
		echo "<th>Date</th>";
		echo "<th>Start Time</th>";
		echo "<th>End Time</th>";
		echo "<th>Shift Length (in min)</th>";
	echo "</tr>";
while ($row = mysql_fetch_array($result)) {
	echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['uin'] . "</td>";
		echo "<td>" . $row['points'] . "</td>";
		echo "<td>" . $row['type'] . "</td>";
		echo "<td>" . $row['date'] . "</td>";
		echo "<td>" . $row['start'] . "</td>";
		echo "<td>" . $row['end'] . "</td>";
		echo "<td>" . $row['shift'] . "</td>";
	echo "</tr>";
}
echo "</table>";

?>
