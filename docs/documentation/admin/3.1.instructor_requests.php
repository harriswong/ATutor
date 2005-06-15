<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg 		*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id: 10.1.link_categories.php 4824 2005-06-08 19:27:33Z joel $

require('../common/body_header.inc.php'); ?>

<h2>3.1 Instructor Requests</h2>
	<p>If the <a href="2.2.system_preferences.php">System Preferences</a> <em>Allow Instructor Requests</em> option is enabled and the <em>Auto Approve Instructor Requests</em> option is disabled, then pending instructor account requests will be listed on this page.</p>

	<p>Using the <code>Deny</code> or <code>Approve</code> buttons after selecting an entry will remove it from the list and take the appropriate action. An email message will be sent to the account holder notifying them of the change.</p>

	<p>Tip: The number of pending instructor account requests is always listed on the main <a href="2.0.configuration.php">Configuration</a> page.</p>

<?php require('../common/body_footer.inc.php'); ?>
