<?php

$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";
$usertable="members";

// Connect to the database
$connection = mysql_connect($hostname, $username, $password);
if (!$connection) { die('Could not connect: ' . mysql_error()); }
mysql_select_db($dbname, $connection);

$query = "SELECT `first_name`, `last_name` FROM `$usertable`";
$result = mysql_query($query);

if ($result)
{
	echo json_encode($result);
	// while ($row = mysql_fetch_array($result))
	// {
	// 	echo $row;
	// }
}
else {
		echo json_encode("Nothing in database result");
}

mysql_close($connection);	// Close database connection
?>
