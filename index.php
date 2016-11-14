<?php
include("header.html");

$action = "home";
if ( !empty($_GET['action']) ) {
	$tmp_action = basename($_GET['action']);

	if ( file_exists($_SERVER['DOCUMENT_ROOT']."/content/$tmp_action.html") )
		$action = $tmp_action;
}
include($_SERVER['DOCUMENT_ROOT']."/content/$action.html");
include("footer.html");
