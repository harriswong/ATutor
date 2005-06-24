<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $next_prev_links, $langEditor;
global $_base_path, $_my_uri;
global $_stacks, $db;

$side_menu = array();

if ($_SESSION['course_id'] > 0) {
	$savant->assign('my_uri', $_my_uri);

	if (($_SESSION['prefs'][PREF_MAIN_MENU] == 1) && $_SESSION['prefs'][PREF_MAIN_MENU_SIDE] != MENU_LEFT) {
		$savant->assign('right_menu_open', TRUE);
		$savant->assign('popup_help', 'MAIN_MENU');
		$savant->assign('menu_url', '<a name="menu"></a>');
		$savant->assign('close_menu_url', $_my_uri.'disable='.PREF_MAIN_MENU);
		$savant->assign('close_menus', _AT('close_menus'));
	}	
	$sql = "SELECT copyright FROM ".TABLE_PREFIX."courses WHERE course_id=".$_SESSION['course_id'];
	if($result = mysql_query($sql, $db)) {
		while($row=mysql_fetch_row($result)) {
			if(strlen($row[0])>0) {
				$custom_copyright= $row[0];
				$custom_copyright = str_replace('CONTENT_DIR/', '', $custom_copyright);
			}
		}
		$savant->assign('custom_copyright', $custom_copyright);
	} else {
		$savant->assign('custom_copyright', '');
	}

	//side menu array
	$side_menu = explode('|', $system_courses[$_SESSION['course_id']]['side_menu']);
	$side_menu = array_intersect($side_menu, $_stacks);
}

$theme_img  = $_base_path . 'themes/'. $_SESSION['prefs']['PREF_THEME'] . '/images/';
$savant->assign('img', $theme_img);

if (isset($err)) {
	$err->showErrors(); // print all the errors caught on this page
}

$file_info = pathinfo($_SERVER['PHP_SELF']);
if ($file_info['basename'] != 'take_test.php') {
	$savant->assign('side_menu', $side_menu);
}


if ($framed || $popup) {
	$savant->display('include/fm_footer.tmpl.php');
} else {
	$savant->display('include/footer.tmpl.php');
}

debug($_SESSION);

?>