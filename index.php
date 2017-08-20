<?php
session_start();

require("php/utils.php");

include("header.html");

$action = "home";
if(!empty($_GET['action'])) {
	$tmp_action = basename($_GET['action']);

	if ( file_exists($_SERVER['DOCUMENT_ROOT']."/$tmp_action.html") )
		$action = $tmp_action;
}

if(!$_SESSION['loggedIn'] && in_array($action, $memberAccess)) {
	$action = "log-in";
}

include($_SERVER['DOCUMENT_ROOT']."/$action.html");
include("footer.html");
