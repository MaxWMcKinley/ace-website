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

// Get all the points of the ACE member
$query = "SELECT * FROM `$usertable` where `uin`='$uin'";
$result = mysql_query($query);

if ($result) {
	$row = mysql_fetch_array($result);
}

// Calculate total points of ACE member
$total = $row['recruitment'] + $row['service'] + $row['fundraising'] + $row['academic'] + $row['athletics'] + $row['social'] + $row['tailgate'] + $row['corporate_relations'] + $row['pr'] + $row['songfest'] + $row['special_events'] + $row['attendance'] + $row['family'] + $row['sober_rides'] + $row['flex'];

// Calculate the points still needed for each category
$needed = $row['required'] - $total;
if ($needed < 0) $needed = 0;
$recruitment = "N/A";
$service = 25 - $row['service'];
if ($service < 0) $service = 0;
$fundraising = 20 - $row['fundraising'];
if ($fundraising < 0) $fundraising = 0;
$academic = "N/A";
$athletics = "N/A";
$social = 5 - $row['social'];
if ($social < 0) $social = 0;
$tailgate = "N/A";
$corporate_relations = "N/A";
$pr = 5 - $row['pr'];
if ($pr < 0) $pr = 0;
$songfest = "N/A";
$special_events = "N/A";
$attendance = "N/A";
$family = (9 - $row['family']);
if ($family < 0) $family = 0;
$sober_rides = "N/A";
$flex = $needed - ($service + $fundraising + $social + $pr + $family);
if ($flex < 0) $flex = 0;

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
		echo "<td>" . $total . "</td>";
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
echo "<br><br>";
echo "<h2>Points You Still Need</h2>";
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
		echo "<td>" . $needed . "</td>";
		echo "<td>" . $recruitment . "</td>";
		echo "<td>" . $service . "</td>";
		echo "<td>" . $fundraising . "</td>";
		echo "<td>" . $academic . "</td>";
		echo "<td>" . $athletics . "</td>";
		echo "<td>" . $social . "</td>";
		echo "<td>" . $tailgate . "</td>";
		echo "<td>" . $corporate_relations . "</td>";
		echo "<td>" . $pr . "</td>";
		echo "<td>" . $songfest . "</td>";
		echo "<td>" . $special_events . "</td>";
		echo "<td>" . $attendance . "</td>";
		echo "<td>" . $family . "</td>";
		echo "<td>" . $sober_rides . "</td>";
		echo "<td>" . $flex . "</td>";
	echo "</tr>";
echo "</table>";
?>
