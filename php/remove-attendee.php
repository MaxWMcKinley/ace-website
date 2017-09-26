<?php

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("utils.php");
require("connect.php");


// --------------------------------------------------------------------------------------------
// Check for null paramters and store their values in local variables
// --------------------------------------------------------------------------------------------

checkNull($_POST);

$id = $_POST['eventId'];
$uin = getUin();

// Preparing SQL statement to get all available events
if (!($stmt = $conn->prepare("DELETE FROM sign_ups WHERE uin = ? AND id = ?")))
	echo "Statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

// Bind parameters to statement
if (!$stmt->bind_param("ss", $uin, $id))
	echo "UIN parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Event execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


echo "Succesfully removed from sign up list";

$stmt->close();
$conn->close();
?>
