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
$id = $_POST['id'];

// --------------------------------------------------------------------------------------------
// Get user's UIN
// --------------------------------------------------------------------------------------------

$uin = getUin();


// --------------------------------------------------------------------------------------------
// Check to make sure all shifts still have spots available
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT spots FROM events WHERE id = ?")))
	echo "Select spots preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Select spots parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select spots execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($result))
	echo "Select spot result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

// Storing result into $spots
while ($stmt->fetch())
	$spots = $result;

if ($spots <= 0)
	die("nospots");


// --------------------------------------------------------------------------------------------
// Sign up the member for his selected shifts
// --------------------------------------------------------------------------------------------

// Statement that will sign the member up by inserting uin into sign up table
if (!($stmt = $conn->prepare("INSERT INTO sign_ups (id, uin) VALUES (?, ?)")))
	echo "Insert sign ups preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("ss", $id, $uin))
	echo "Insert sign ups parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Insert sign ups execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


// --------------------------------------------------------------------------------------------
// Decrement number of spots available
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("UPDATE events SET spots = spots - 1 where id = ?")))
	echo "Decrement spots preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $id))
	echo "Decrement spots parameter binding failed with error number " . $dec_stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Decrement spots execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";


echo "success";

$stmt->close();
$conn->close();
?>
