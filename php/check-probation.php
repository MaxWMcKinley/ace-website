<?php

// This file also fetchs the name of the user to show he is logged in. Not intuitive for it to be here.

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("utils.php");
require("connect.php");


// --------------------------------------------------------------------------------------------
// Get user's UIN
// --------------------------------------------------------------------------------------------

$uin = getUin();


// --------------------------------------------------------------------------------------------
// Check if user is on probation
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT probation, name FROM nmembers WHERE uin = ?")))
	echo "Select probation preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "Select probation parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select probation execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($probation, $name))
	echo "Select probation result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$stmt->fetch();

$array['probation'] = $probation;
$array['name'] = $name;

$json = json_encode($array);

// --------------------------------------------------------------------------------------------
// Return the result, and close any connections
// --------------------------------------------------------------------------------------------

echo $json;

$stmt->close();
$conn->close();
?>
