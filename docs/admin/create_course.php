<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/

$page = 'courses';
$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }
	
require(AT_INCLUDE_PATH.'classes/Backup/Backup.class.php');
require(AT_INCLUDE_PATH.'lib/course.inc.php');

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	if ($_REQUEST['show_courses'] != "") {
		header('Location: '.$_base_href.'users/admin/course_categories.php?course='.$_REQUEST['course_id'].SEP.'this_course='.$_REQUEST['course_id'].SEP.'show_courses='.$_REQUEST['show_courses'].SEP.'current_cat='.$_REQUEST['current_cat']);
	} else {		
		header('Location: '.$_base_href.'admin/courses.php');
	}
	exit;
} else if (isset($_POST['form_course'])) {
	$errors = add_update_course($_POST, TRUE);

	if ($errors !== FALSE) {
		$msg->addFeedback('COURSE_CREATED');
		header('Location: '.$_base_href.'admin/courses.php');
		exit;	
	}
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

echo '<br />';

$msg->printAll();

$course_id = 0;
$isadmin   = TRUE;

require(AT_INCLUDE_PATH.'html/course_properties.inc.php');

require(AT_INCLUDE_PATH.'footer.inc.php');

?>