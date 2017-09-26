<?php
session_start();

include("header.html");

$memberAccess = array('nexus', 'events', 'create-event', 'calendar', 'links');
$officerAccess = array('create-event');
$execAccess = array();

$execClearance = array('exec', 'admin');
$officerClearance = array_merge($execClearance, array('godfather', 'officer'));

$position = $_SESSION['position'];

$action = "home";
if(!empty($_GET['action'])) {
	$tmp_action = basename($_GET['action']);

	if ( file_exists($_SERVER['DOCUMENT_ROOT']."/$tmp_action.html") )
		$action = $tmp_action;
}

if (!$_SESSION['loggedIn'] && in_array($action, $memberAccess)) {
	$action = "log-in";
} else if (!in_array($position, $officerClearance) && in_array($action, $officerAccess)) {
	$action = "restricted";
} else if (!in_array($position, $execClearance) && in_array($action, $execAccess)) {
	$action = "restricted";
}

include($_SERVER['DOCUMENT_ROOT']."/$action.html");
include("footer.html");
