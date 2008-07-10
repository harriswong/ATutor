<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

$_user_location	= 'users';
define('AT_INCLUDE_PATH', '../include/');

require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'lib/themes.inc.php');
require(AT_INCLUDE_PATH.'lib/pref_tab_functions.inc.php');
/* whether or not, any settings are being changed when this page loads. */
/* ie. is ANY action being performed right now?							*/

$action = false;

if (!$_SESSION['valid_user']) {				
	/* we're not logged in */
	$msg->addFeedback('PREFS_LOGIN');
}

if (isset($_POST['submit'])) {
	/* custom prefs */

	// atutor settings (tab 0)
	$temp_prefs['PREF_NUMBERING']	   = intval($_POST['numbering']);
	$temp_prefs['PREF_THEME']	       = $addslashes($_POST['theme']);
	$temp_prefs['PREF_JUMP_REDIRECT']  = intval($_POST['use_jump_redirect']);
	$temp_prefs['PREF_FORM_FOCUS']     = intval($_POST['form_focus']);
	$temp_prefs['PREF_CONTENT_EDITOR'] = intval($_POST['content_editor']);
	
	// content settings (tab 2)
	$temp_prefs['PREF_USE_ALTERNATE_TEXT'] = intval($_POST['use_alternate_text']);
	$temp_prefs['PREF_ALT_TEXT_LANG']	     = $addslashes($_POST['alt_text_lang']);
	$temp_prefs['PREF_LONG_DESC_LANG']	   = $addslashes($_POST['long_desc_lang']);
	$temp_prefs['PREF_USE_GRAPHIC_ALTERNATIVE'] = intval($_POST['use_graphic_alternative']);
	$temp_prefs['PREF_USE_SIGN_LANG'] = intval($_POST['use_sign_lang']);
	$temp_prefs['PREF_SIGN_LANG']	   = $addslashes($_POST['sign_lang']);
	$temp_prefs['PREF_USE_VIDEO'] = intval($_POST['use_video']);
	$temp_prefs['PREF_PREFER_LANG']	   = $addslashes($_POST['prefer_lang']);
	$temp_prefs['PREF_DESC_TYPE']	   = $addslashes($_POST['description_type']);
	$temp_prefs['PREF_ENABLE_CAPTIONS'] = intval($_POST['enable_captions']);
	$temp_prefs['PREF_CAPTION_TYPE']	   = $addslashes($_POST['caption_type']);
	$temp_prefs['PREF_CAPTION_LANG']	   = $addslashes($_POST['caption_lang']);
	$temp_prefs['PREF_ENHANCED_CAPTIONS'] = intval($_POST['enhanced_captions']);
	$temp_prefs['PREF_REQUEST_CAPTION_RATE'] = intval($_POST['request_caption_rate']);
	$temp_prefs['PREF_CAPTION_RATE']	   = $addslashes($_POST['caption_rate']);

	/* we do this instead of assigning to the $_SESSION directly, b/c	*/
	/* assign_session_prefs functionality might change slightly.		*/
	assign_session_prefs($temp_prefs);

	/* save as pref for ALL courses */
	save_prefs();

	//update auto-login settings
	if (isset($_POST['auto']) && ($_POST['auto'] == 'disable')) {
		$parts = parse_url(AT_BASE_HREF);
		setcookie('ATLogin', '', time()-172800, $parts['path'], $parts['host'], 0);
		setcookie('ATPass',  '', time()-172800, $parts['path'], $parts['host'], 0);		
	} else if (isset($_POST['auto']) && ($_POST['auto'] == 'enable')) {
		$parts = parse_url(AT_BASE_HREF);
		$sql	= "SELECT password FROM ".TABLE_PREFIX."members WHERE member_id=$_SESSION[member_id]";
		$result = mysql_query($sql, $db);
		$row	= mysql_fetch_assoc($result);
		$password = sha1($row['password'] . '-' . DB_PASSWORD);
		setcookie('ATLogin', $_SESSION['login'], time()+172800, $parts['path'], $parts['host'], 0);
		setcookie('ATPass',  $password, time()+172800, $parts['path'], $parts['host'], 0);
	}

	/* also update message notification pref */
	$_POST['mnot'] = intval($_POST['mnot']);
	$sql = "UPDATE ".TABLE_PREFIX."members SET inbox_notify = $_POST[mnot], creation_date=creation_date, last_login=last_login WHERE member_id = $_SESSION[member_id]";
	$result = mysql_query($sql, $db);

	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'users/preferences.php');
	exit;
}

$sql	= "SELECT inbox_notify FROM ".TABLE_PREFIX."members WHERE member_id=$_SESSION[member_id]";
$result = mysql_query($sql, $db);
$row_notify = mysql_fetch_assoc($result);

/* page contents starts here */
$savant->assign('notify', $row_notify['inbox_notify']);

$savant->display('users/preferences.tmpl.php');

?>

