<?php
session_start();

// --------------------------------------------------------------------------------------------
// Import files
// --------------------------------------------------------------------------------------------

require("connect.php");
require("utils.php");
require("access.php");


// --------------------------------------------------------------------------------------------
// Check for null paramters and store their values in local variables
// --------------------------------------------------------------------------------------------

checkNull($_POST);

// Setting up form variables
$uin = $_POST['uin'];
$name = $_POST['name'];
$major = $_POST['major'];
$family = $_POST['family'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];
$accessCode = $_POST['access'];

// Check to see if the access code the user entered is correct
// Purpose of the access code is to prevent anyone for signing up for an account and gaining access to the website
if(!checkAccess($accessCode))
	die("wrongAccess");


// --------------------------------------------------------------------------------------------
// Create user account
// --------------------------------------------------------------------------------------------

$hash = password_hash($password, PASSWORD_DEFAULT);

if (!($stmt = $conn->prepare("INSERT INTO nmembers (uin, name, major, family, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)")))
	die("Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")");

if (!$stmt->bind_param("sssssss", $uin, $name, $major, $family, $phone, $email, $hash))
	die("Insert parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

if (!$stmt->execute())
	die("Insert execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")");


// --------------------------------------------------------------------------------------------
// Log user in
// --------------------------------------------------------------------------------------------

$_SESSION['loggedIn'] = true;
$_SESSION['uin'] = $uin;
$_SESSION['name'] = $name;


// --------------------------------------------------------------------------------------------
// Return the result, and close any connections
// --------------------------------------------------------------------------------------------

echo "success";

$stmt->close();
$conn->close();
