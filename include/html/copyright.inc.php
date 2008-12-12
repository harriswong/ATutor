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

if (!defined('AT_INCLUDE_PATH')) { exit; }

global $_base_path;
global $system_courses;

?>
<div align="center"><?php

	if ((isset($_SESSION['course_id']) && $_SESSION['course_id'] > 0) && $system_courses[$_SESSION['course_id']]['copyright'] != '') {	
		echo '<small>' . AT_print($system_courses[$_SESSION['course_id']]['copyright'], 'courses.copyright') . '</small><br />';
	}

	/****************************************************************************************/
	/* VERY IMPORTANT
	   IN KEEPING WITH THE TERMS OF THE ATUTOR LICENCE AGREEMENT (GNU GPL), THE FOLLOWING
	   COPYRIGHT LINES MAY NOT BE ALTERED IN ANY WAY.
	*/
?>
	<small><?php echo _AT('copyright').'. '; echo '<a href="'.$_base_path.'about.php">'._AT('about_atutor').'</a>.'; ?><br />
	<span id="howto"><?php echo _AT('general_help', AT_GUIDES_PATH.'index_list.php?lang='.$_SESSION['lang']); ?></span></small>
</div>