<?php
session_start();

if(!$_SESSION['loggedIn'])
	die("Please log in and then try again.");

require("access.php");
echo generateAccessCode();
