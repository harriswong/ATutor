<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay, Joel Kronenberg, Heidi Hazelton	*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }
error_reporting(E_ALL ^ E_NOTICE);

if ($step < 4) {
	error_reporting(0);
	include('../include/config.inc.php');
	error_reporting(E_ALL ^ E_NOTICE);
	if (defined('AT_INSTALL')) {
		echo 'ATutor appear to have been installed already.';
		exit;
	}
}

$install_steps[0] = array(	'name' => 'Introduction',
							'file' => 'index.php');

$install_steps[1] = array(	'name' => 'Terms of Use',
							'file' => 'step1.php');

$install_steps[2] = array(	'name' => 'Database',
							'file' => 'step2.php');

$install_steps[3] = array(	'name' => 'Administrator Account &amp; System Preferences');

$install_steps[4] = array(	'name' => 'Personal Account &amp; Defaults',
							'file' => 'step4.php');

$install_steps[5] = array(	'name' => 'Content Directory');

$install_steps[6] = array(	'name' => 'Save Configuration');

//$install_steps[6] = array(	'name' => 'Languages');

$install_steps[7] = array(	'name' => 'Done!',
							'file' => 'step5.php');


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<title>ATutor Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>
<table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr height="50" bgcolor="#354A81">
	<td width="90%"><h2 class="header">ATutor <?php echo $new_version; ?> Installation</h2></td>
	<td align="right" valign="middle"><img src="../images/logo.gif" height="29" width="84" alt="ATutor Logo" id="logo" /></td>
</tr>
<tr>
	<td colspan="2" valign="top" class="content">