<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id: footer.inc.php 6772 2007-02-16 19:04:52Z joel $
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $_base_path, $_my_uri;
global $_stacks, $db;
global $system_courses;
global $savant;

$side_menu = array();
$stack_files = array();

if ($_SESSION['course_id'] > 0) {
	//$savant->assign('my_uri', $_my_uri);

	$savant->assign('right_menu_open', TRUE);
	$savant->assign('menu_url', '<a name="menu"></a>');
	$savant->assign('close_menu_url', htmlspecialchars($_my_uri).'disable='.PREF_MAIN_MENU);
	$savant->assign('close_menus', _AT('close_menus'));

	$side_menu = explode('|', $system_courses[$_SESSION['course_id']]['side_menu']);

	foreach ($side_menu as $side) {
		if (isset($_stacks[$side])) {
			$stack_files[] = $_stacks[$side]['file'];
		}
	}
}

$theme_img  = $_base_path . 'themes/'. $_SESSION['prefs']['PREF_THEME'] . '/images/';
$savant->assign('img', $theme_img);


$savant->assign('side_menu', $stack_files);

$savant->display('include/side_menu.tmpl.php');
?>