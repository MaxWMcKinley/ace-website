<?php
// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";
$usertable="members";

// Connect to the database
$connection = mysql_connect($hostname, $username, $password);
if (!$connection) { die('Could not connect: ' . mysql_error()); }
mysql_select_db($dbname, $connection);

// Get all of the first and last names of ACE members
$query = "SELECT `first_name`, `last_name` FROM `$usertable`";
$result = mysql_query($query);

if ($result) {		// Received a result from database
	// Iterate through each result and add names to an array
	$names = array();
	$i = 0;
	while ($row = mysql_fetch_array($result))
	{
		$string = $row['first_name'] . " " . $row['last_name'];	// Concatenate first and last name
		$names[$i] = $string;
		$i++;
	}
}
else {	// No result from database
		echo json_encode("Nothing in database result");
}

echo json_encode($names);	// Return array of names

mysql_close($connection);	// Close database connection
?>
