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
// $Id$

require('../common/body_header.inc.php'); ?>

<h2>2.4.2 Managing Existing Themes</h2>
	<p>All available themes on an ATutor system are listed in the Administrator's Themes section.</p>

	<dl>
		<dt><code>Preview</code></dt>
		<dd>Use the Preview button to test the theme to make sure it doesn't break. If a previewed theme breaks, simply log-out and login again to restore the default theme. The Preview button can also be used to preview disabled themes. This feature is available in ATutor 1.5.1+.</dd>

		<dt><code>Enable/Disable</code></dt>
		<dd>Enabled themes are available to users in their Preferences section. Themes can be disabled which something you might want to do while a theme is being modified. If a student's preferred theme is disabled, the system default theme will be used in its place.</dd>

		<dt><code>Set as Default</code></dt>
		<dd>If a theme is set as the Default Theme, it will display for students who have not selected a prefered theme, and it will be displayed on public pages, such as the Login screen or Registration screen.</dd>

		<dt><code>Export</code></dt>
		<dd>Any theme can be exported from an ATutor installation to share with others.  It can also be imported back into an ATutor installation as a copy, available to be modified for creating a new theme.</dd>

		<dt><code>Delete</code></dt>
		<dd>A theme is removed from the system if the Delete button is used.</dd>
	</dl>

	<h3>Category Themes</h3>
	<p>If there are <a href="../admin/4.3.categories.php">Course Categories</a> defined and the <a href="../admin/2.2.system_preferences.php">System Preferences</a> <em>Theme Specific Categories</em>  has been enabled, themes can be assigned to categories so all courses under a particular category are displayed with the same look and feel. When defining <a href="../admin/4.3.categories.php">Course Categories</a> while the Category Themes system preference is enabled, a list of themes will be available to select from and assign to each category.</p>
	<p>Note that when Category Themes has been enabled, users will no longer be able to select themes from their personal preference settings.</p>

<?php require('../common/body_footer.inc.php'); ?>