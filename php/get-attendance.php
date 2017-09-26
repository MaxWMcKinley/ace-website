<?php

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
// Get eventids and points of the user's completed events
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT attendance FROM attendance WHERE uin = ?")))
	echo "Select attendance preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $uin))
	echo "Select attendance parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select attendance execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Select attendance result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$attendance = 0;
while ($stmt->fetch()) {
	$attendance = $result;
}


// --------------------------------------------------------------------------------------------
// Return attendance and close connections
// --------------------------------------------------------------------------------------------

echo $attendance;

$stmt->close();
$conn->close();
?>
