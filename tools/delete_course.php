<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg GayJoel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_ADMIN);

$course = isset($_REQUEST['course']) ? intval($_REQUEST['course']) : 0;

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.AT_BASE_HREF.'tools/course_properties.php');
	exit;
} else if (isset($_POST['step']) && ($_POST['step'] == 2) && isset($_POST['submit_yes'])) {
	require(AT_INCLUDE_PATH.'lib/filemanager.inc.php');
	require(AT_INCLUDE_PATH.'lib/delete_course.inc.php');

	delete_course($_SESSION['course_id'], $entire_course = true); // delete the course
	cache_purge('system_courses','system_courses'); // purge the system_courses cache (if successful)
	
	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: '.AT_BASE_HREF.'bounce.php?course=0');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

if (!isset($_POST['step'])) {
	$hidden_vars['step']   = 1;
	$msg->addConfirm(array('DELETE_COURSE_1', $system_courses[$_SESSION['course_id']]['title']), $hidden_vars);
	$msg->printConfirm();
} else if ($_POST['step'] == 1) {
	$hidden_vars['step']   = 2;
	$msg->addConfirm(array('DELETE_COURSE_2', $system_courses[$_SESSION['course_id']]['title']), $hidden_vars);
	$msg->printConfirm();
}

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>