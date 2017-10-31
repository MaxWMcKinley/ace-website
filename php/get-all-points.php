<?php

// --------------------------------------------------------------------------------------------
// Check that user is exec
// --------------------------------------------------------------------------------------------

session_start();

if(!$_SESSION['loggedIn'] || !($_SESSION['position'] == "exec" || $_SESSION['position'] == "admin"))
	die("Either you need to log in or you don't know have the correct privelage to see this page.");


// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("utils.php");
require("connect.php");


// --------------------------------------------------------------------------------------------
// Get user's UIN
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT uin, name FROM nmembers WHERE position != 'alumni'")))
	echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($uin, $name))
	echo "Select points result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	$members[$uin] = $name;
}

// --------------------------------------------------------------------------------------------
// Get eventids and points of the user's completed events
// --------------------------------------------------------------------------------------------

foreach ($members as $uin => $name) {
	if (!($stmt = $conn->prepare("SELECT points.*, event_archive.type FROM points, event_archive WHERE points.uin = ? AND event_archive.id = points.eventid")))
		echo "Select points preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

	if (!$stmt->bind_param("s", $uin))
		echo "Select points parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	if (!$stmt->execute())
		echo "Select points execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		$array[$uin]["name"] = $name;
		// echo $array[$uin]["name"];		
		$array[$uin][$row["type"]] += $row["points"];
	}
}

foreach ($array as $uin => $pointArray) {
	echo $pointArray["name"];
	echo "<br>&emsp; Total: " . ($pointArray["flex"] + $pointArray["service"] + $pointArray["fundraising"]);
	echo "<br>&emsp; Flex: " . $pointArray["flex"];
	echo "<br>&emsp; Service: " . $pointArray["service"]; 
	echo "<br>&emsp; Fundraising: " . $pointArray["fundraising"];
	echo "<br><br>";
}


// --------------------------------------------------------------------------------------------
// Encode json, return the result, and close any connections
// --------------------------------------------------------------------------------------------

// Encoding the array and returning
// $json = json_encode($array);
// echo $json;

$stmt->close();
$conn->close();
?>
