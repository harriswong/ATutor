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

$page = 'tests';
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_TEST_CREATE);

if (isset($_GET['submit_create'])) {
	header('Location: create_question_'.$_GET['question_type'].'.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');

$msg->printAll();
?>

<?php $tid = intval($_GET['tid']); ?>

<?php require(AT_INCLUDE_PATH.'html/tests_questions.inc.php'); ?>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>