<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

	$page = 'tools';
	define('AT_INCLUDE_PATH', '../include/');
	require(AT_INCLUDE_PATH.'vitals.inc.php');

	$_section[0][0] = _AT('tools');
	
	require(AT_INCLUDE_PATH.'header.inc.php');

	$msg->printAll();

?>
<ul>
	<li><a href="search.php"><?php echo _AT('search'); ?></a><br /><?php echo _AT('search_text'); ?></li>
	<li><a href="tools/sitemap/index.php"><?php echo _AT('sitemap'); ?></a><br /><?php echo _AT('sitemap_text'); ?></li>
	<li><a href="glossary/index.php"><?php echo _AT('glossary'); ?></a><br /><?php echo _AT('glossary_text'); ?></li>
	<li><a href="tools/ims/index.php"><?php echo _AT('export_content'); ?></a><br /><?php echo _AT('export_content_text'); ?></li>
	<li><a href="tools/tracker.php"><?php echo _AT('my_tracker'); ?></a><br /><?php echo _AT('my_tracker_text'); ?></li>
	<li><a href="tools/my_tests.php"><?php echo _AT('my_tests'); ?></a><br /><?php echo _AT('my_tests_text'); ?></li>
</ul>

<hr />

<ul>
	<li><a href="tools/content/index.php"><?php echo _AT('content'); ?></a> (add, content packaging)</li>
	<li><a href="tools/news/index.php"><?php echo _AT('announcements'); ?></a></li>
	<li><a href="tools/forums/index.php"><?php echo _AT('forums'); ?></a></li>
	<li><a href="tools/course_properties.php"><?php echo _AT('properties'); ?></a></li>
	<li><a href="tools/backup/index.php"><?php echo _AT('backups'); ?></a></li>
	<li><a href="tools/enrollment/index.php"><?php echo _AT('enrolment'); ?></a> ( send email, enrollment manager, tracker)</li>
	<li><a href="discussions/polls.php"><?php echo _AT('polls'); ?></a></li>
	<li><a href="resources/tile/index.php"><?php echo _AT('tile_search'); ?></a></li>
</tr>
</ul>
<?php
	
if (defined('AC_PATH') && AC_PATH) {
	echo '<br /><h3>ACollab '._AT('tools').'</h3><br />';

?>
	<table border="0" cellspacing="0" cellpadding="3" summary="">
	<?php if (authenticate(AT_PRIV_AC_CREATE, AT_PRIV_RETURN)) { ?>
	<tr>
		<?php 
					if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
						echo '<td rowspan="2" valign="top"><img src="images/icons/default/ac_group_mng-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
					}
					echo '<td>';
					if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
						echo ' <a href="acollab/bounce.php?p='.urlencode('admin/groups_create.php').'">'._AT('ac_create').'</a>';
					}
					echo '</td></tr><tr><td>';
					echo _AT('ac_create_text');
		?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<?php 
					if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
						echo '<td rowspan="2" valign="top"><img src="images/icons/default/ac_group-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
					}
					echo '<td>';
					if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
						echo ' <a href="acollab/bounce.php">'._AT('ac_access_groups').'</a>';
					}
					echo '</td></tr><tr><td>';
					echo _AT('ac_access_text');
				?>
		</td>
	</tr>
	</table>
<?php
}
	if (!$_SESSION['privileges'] && !authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) {
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}

	if (show_tool_header()) {
		echo '<br /><a name="ins-tools"></a><h3>'._AT('instructor_tools').'</h3><br />';
	}
?>
<table border="0" cellspacing="0" cellpadding="3" summary="">
<?php if (authenticate(AT_PRIV_LINKS, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
			echo '<td rowspan="2" valign="top">*</td>';
			echo '<td>';
				echo ' <a href="tools/links/index.php">'._AT('links').'</a>';
			echo '</td></tr><tr><td>';
			echo _AT('links_text');
			?>
	</td>
</tr>
<?php } ?>

<?php if (authenticate(AT_PRIV_COURSE_EMAIL, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/course_mail-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/course_email.php">'._AT('course_email').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('send_to', '');
			?>
	</td>
</tr>
<?php } ?>

<?php if (authenticate(AT_PRIV_ENROLLMENT, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/enrol_mng-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/enrollment/index.php">'._AT('course_enrolment').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('course_enrollment_text');
			?>
	</td>
</tr>
<?php } ?>

<?php if (authenticate(AT_PRIV_FILES, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/file-manager-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/filemanager/index.php">'._AT('file_manager').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('file_manager_text');
			?>
	</td>
</tr>
<?php } ?>
<?php if (authenticate(AT_PRIV_TEST_CREATE, AT_PRIV_RETURN) || authenticate(AT_PRIV_TEST_MARK, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/test-manager-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/tests/">'._AT('test_manager').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('test_manager_text');
			?>
	</td>
</tr>
<?php } ?>
<?php if (authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) { ?>

<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/backup-small.gif" border="0" class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/backup/">'._AT('backup_manager').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('backup_course_text');
			?>
	</td>
</tr>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/course-tracker-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/course_tracker.php">'._AT('course_tracker').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('course_tracker_text');
			?>
	</td>
</tr>

<tr>
	<td rowspan="2" valign="top">*</td>
	<td><a href="tools/course_stats.php"><?php echo _AT('course_stats'); ?></a></td>
</tr>
<tr>
	<td><?php echo _AT('course_stats_text'); ?></td>
</tr>

<?php } ?>
<?php if (authenticate(AT_PRIV_STYLES, AT_PRIV_RETURN)) { ?>
<tr>
	<?php
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/edit-preferences-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/course_preferences.php">'._AT('course_default_prefs').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('course_default_prefs_text');
			?>
	</td>
</tr>
<tr>
	<?php 
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
					echo '<td rowspan="2" valign="top"><img src="images/icons/default/banner-small.gif" border="0"  class="menuimage" width="28" height="25" alt="*" /></td>';
				}
				echo '<td>';
				if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
					echo ' <a href="tools/banner.php">'._AT('course_banner').'</a>';
				}
				echo '</td></tr><tr><td>';
				echo _AT('banner_text');
			?>
	</td>
</tr>

<?php } ?>
</table>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>