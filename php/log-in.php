<?php
session_start();

require("connect.php");
require("utils.php");

checkNull($_POST);

// Setting up form variables
$uin = $_POST['uin'];
$password = $_POST['password'];

if (!($stmt = $conn->prepare("SELECT password, name, position FROM nmembers WHERE uin = ?")))
	die("Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")");

if (!$stmt->bind_param("s", $uin))
	die("Insert parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

if (!$stmt->execute())
	die("Insert execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

if (!$stmt->bind_result($result, $name, $position))
	echo "UIN result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch())
	$hash = $result;

$stmt->close();
$conn->close();

if(password_verify($password, $hash)) {
	$_SESSION['loggedIn'] = true;
	$_SESSION['uin'] = $uin;
	$_SESSION['name'] = $name;
	$_SESSION['position'] = $position;	

	echo "success";
} else {
	echo "failure";
}
