<?php
include("header.html");

$action = "home";
if ( !empty($_GET['action']) ) {
	$tmp_action = basename($_GET['action']);

	if ( file_exists($_SERVER['DOCUMENT_ROOT']."/$tmp_action.html") )
		$action = $tmp_action;
}
include($_SERVER['DOCUMENT_ROOT']."/$action.html");
include("footer.html");
