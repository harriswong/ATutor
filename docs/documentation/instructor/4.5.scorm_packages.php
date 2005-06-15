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

require('../common/body_header.inc.php'); ?>

<h2>4.5 SCORM Packages</h2>
	<p>The Packages tool when enabled, allows instructors to include SCORM 1.2 Sharable Content Objects (SCOs) as part of their courses. SCOs remain separated from the course content as complete learning units. SCOs should not be confused with content packages which are loaded into ATutor using the Import/Export tool in the Content Manager.</p>
	<p><strong>Note:</strong> The ATutor SCORM Run-Time Environment  (RTE) that plays SCOs requires users to have Java 1.5 (i.e. JRE 1.5) installed on their computer.</p>
	<dl>
		<dt>Import Package</dt>
		<dd><p>Upload a SCO from your computer, or enter the URL to a SCO located on the Web to import it into your course.</p></dd>

		<dt>Delete Package</dt>
		<dd><p>Removes a SCO from a course, and deletes all associated files.</p></dd>

		<dt>Package Setting</dt>
		<dd>
   			<p><code>Credit Mode</code> sets the package to credit or no credit.</p>

			<p><code>Lesson Mode</code> set to <code>browse</code> mode if the package is to be available for evaluation, or set to <code>normal </code> as a lesson..</p>


		</dd>


<?php require('../common/body_footer.inc.php'); ?>
