<?php
session_start();

require("connect.php");
require("utils.php");
require("checkAccess.php");

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

if(!checkAccess($accessCode))
	die("wrongAccess");

$hash = password_hash($password, PASSWORD_DEFAULT);

if (!($stmt = $conn->prepare("INSERT INTO nmembers (uin, name, major, family, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)")))
	die("Insert statement preparation failed with error number " . $conn->errno . " (" . $conn->error . ")");

if (!$stmt->bind_param("sssssss", $uin, $name, $major, $family, $phone, $email, $hash))
	die("Insert parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

if (!$stmt->execute())
	die("Insert execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")");

$_SESSION['loggedIn'] = true;
$_SESSION['uin'] = $uin;

$stmt->close();
$conn->close();

echo "success";
