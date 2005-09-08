<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg, Heidi Hazelton	*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }

print_progress($step);

if (isset($_POST['submit']) && (trim($_POST['old_path']) != '')) {
	if ((strpos($_POST['old_path'], '/') === false) && is_dir('../../'.$_POST['old_path'])) {
		if ( file_exists('../../'.$_POST['old_path'] . '/include/config.inc.php') ) {
			
			require('../../'.$_POST['old_path'] . '/include/lib/constants.inc.php');
			if (!defined('VERSION')) {
				$errors[] = 'Cannot detect version number. Only ATutor versions greater than 1.0 can be upgraded. Upgrade to 1.1 manually then try upgrading to the latest version again.';
			} else {
				$progress[] = 'Found ATutor version <kbd><b>'.VERSION . '</b></kbd> in path <kbd><b>'.$_POST['old_path'].'</b></kbd>.';
			}
			if (!version_compare(VERSION, $new_version, '<')) {
				$errors[] = 'The version upgrading (<kbd><b>'.VERSION.'</b></kbd>) is not older than the new version (<kbd><b>'.$new_version.'</b></kbd>).';
			}

			if (!$errors) {
				$progress[] = 'Will be upgrading from version <kbd><b>'.VERSION.'</b></kbd> to version <kbd><b>'.$new_version.'</b></kbd>.';
				print_feedback($progress);

				require('../../'.$_POST['old_path'] . '/include/config.inc.php');

				if (is_array($IllegalExtentions)) {
					$IllegalExtentions = implode(',', $IllegalExtentions);
				}

				echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form">';
				echo '<input type="hidden" name="step" value="2" />';
				echo '<input type="hidden" name="old_path" value="'.$_POST['old_path'].'" />';

				echo '<input type="hidden" name="db_login" value="'.urlencode(DB_USER).'" />';
				echo '<input type="hidden" name="db_password" value="'.urlencode(DB_PASSWORD).'" />';
				echo '<input type="hidden" name="db_host" value="'.DB_HOST.'" />';
				if (defined('DB_PORT')) {
					echo '<input type="hidden" name="db_port" value="'.DB_PORT.'" />';
				} else {
					echo '<input type="hidden" name="db_port" value="3306" />';
				}
				echo '<input type="hidden" name="db_name" value="'.DB_NAME.'" />';

				if (defined('TABLE_PREFIX')) {
					echo '<input type="hidden" name="tb_prefix" value="'.TABLE_PREFIX.'" />';
				} else {
					echo '<input type="hidden" name="tb_prefix" value="" />';
				}
				if (defined('SITE_NAME')) {
					echo '<input type="hidden" name="site_name" value="'.SITE_NAME.'" />';
				} else {
					echo '<input type="hidden" name="site_name" value="'.$_defaults['site_name'].'" />';
				}
				if (defined('HEADER_IMAGE')) {
					echo '<input type="hidden" name="header_img" value="'.HEADER_IMAGE.'" />';
				} else {
					echo '<input type="hidden" name="header_img" value="'.$_defaults['header_img'].'" />';
				}
				if (defined('HEADER_LOGO')) {
					echo '<input type="hidden" name="header_logo" value="'.HEADER_LOGO.'" />';
				} else {
					echo '<input type="hidden" name="header_logo" value="'.$_defaults['header_logo'].'" />';
				}
				if (defined('HOME_URL')) {
					echo '<input type="hidden" name="home_url" value="'.HOME_URL.'" />';
				} else {
					echo '<input type="hidden" name="home_url" value="'.$_defaults['home_url'].'" />';
				}

				echo '<input type="hidden" name="admin_password" value="'.urlencode(ADMIN_PASSWORD).'" />';

				if (defined('ADMIN_USERNAME')) {
					echo '<input type="hidden" name="admin_username" value="'.ADMIN_USERNAME.'" />';
				} else {
					echo '<input type="hidden" name="admin_username" value="'.$_defaults['admin_username'].'" />';
				}

				if (defined('ADMIN_EMAIL')) {
					echo '<input type="hidden" name="admin_email" value="'.ADMIN_EMAIL.'" />';
				} else {
					echo '<input type="hidden" name="admin_email" value="'.$_defaults['admin_email'].'" />';
				}
				if (defined('EMAIL_NOTIFY')) {
					echo '<input type="hidden" name="email_notification" value="'.(EMAIL_NOTIFY ? 'TRUE' : 'FALSE').'" />';
				} else {
					echo '<input type="hidden" name="email_notification" value="'.$_defaults['email_notification'].'" />';
				}
				if (defined('ALLOW_INSTRUCTOR_REQUESTS')) {
					echo '<input type="hidden" name="allow_instructor_requests" value="'.(ALLOW_INSTRUCTOR_REQUESTS ? 'TRUE' : 'FALSE').'" />';
				} else {
					echo '<input type="hidden" name="allow_instructor_requests" value="'.$_defaults['allow_instructor_requests'].'" />';
				}

				if (defined('AUTO_APPROVE_INSTRUCTORS')) {
					echo '<input type="hidden" name="auto_approve" value="'.(AUTO_APPROVE_INSTRUCTORS ? 'TRUE' : 'FALSE').'" />';
				} else {
					echo '<input type="hidden" name="auto_approve" value="'.$_defaults['auto_approve'].'" />';
				}

				if (isset($MaxFileSize)) {
					echo '<input type="hidden" name="max_file_size" value="'.$MaxFileSize.'" />';
				} else {
					echo '<input type="hidden" name="max_file_size" value="'.$_defaults['max_file_size'].'" />';
				}
				if (isset($MaxCourseSize)) {
					echo '<input type="hidden" name="max_course_size" value="'.$MaxCourseSize.'" />';
				} else {
					echo '<input type="hidden" name="max_course_size" value="'.$_defaults['max_course_size'].'" />';
				}
				if (isset($MaxCourseFloat)) {
					echo '<input type="hidden" name="max_course_float" value="'.$MaxCourseFloat.'" />';
				} else {
					echo '<input type="hidden" name="max_course_float" value="' . $_defaults['max_course_float'] . '" />';
				}
				
				if (isset($IllegalExtentions)) {
					echo '<input type="hidden" name="ill_ext" value="' . $IllegalExtentions . '" />';
				} else {
					echo '<input type="hidden" name="ill_ext" value="' . $_defaults['ill_ext'] . '" />';
				}
				if (defined('CACHE_DIR')) {
					echo '<input type="hidden" name="cache_dir" value="' . CACHE_DIR . '" />';
				} else {
					echo '<input type="hidden" name="cache_dir" value="' . $_defaults['cache_dir'] . '" />';
				}

				if (defined('AT_ENABLE_CATEGORY_THEMES')) {
					echo '<input type="hidden" name="theme_categories" value="' . (AT_ENABLE_CATEGORY_THEMES ? 'TRUE' : 'FALSE') . '" />';
				} else {
					echo '<input type="hidden" name="theme_categories" value="' . $_defaults['theme_categories'] . '" />';
				}

				if (defined('AT_COURSE_BACKUPS')) {
					echo '<input type="hidden" name="course_backups" value="' . AT_COURSE_BACKUPS . '" />';
				} else {
					echo '<input type="hidden" name="course_backups" value="' . $_defaults['course_backups'] . '" />';
				}

				if (defined('AT_CONTENT_DIR')) {
					echo '<input type="hidden" name="content_dir" value="'.AT_CONTENT_DIR.'" />';
				} else {
					echo '<input type="hidden" name="content_dir" value="'.$_defaults['content_dir'].'" />';
				}
				echo '<input type="hidden" name="new_version" value="'.$new_version.'" />';
				echo '<input type="hidden" name="old_version" value="'.VERSION.'" />';
				echo '<p align="center"><input type="submit" class="button" value=" Next &raquo; " name="submit" /></p></form>';


				$db = @mysql_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASSWORD);
				@mysql_select_db(DB_NAME, $db);

				$sql    = "SELECT content_id, content_parent_id, ordering, course_id FROM ".TABLE_PREFIX."content ORDER BY course_id, content_parent_id, ordering";
				$result = mysql_query($sql, $db);
				while ($row = mysql_fetch_assoc($result)) {
					if ($current_course_id != $row['course_id']) {
						$current_course_id = $row['course_id'];
						unset($current_parent_id);
						unset($ordering);
					}
					if ($current_parent_id != $row['content_parent_id']) {
						$current_parent_id = $row['content_parent_id'];
						$ordering = 1;
					}

					if ($row['ordering'] != $ordering) {
						mysql_query("UPDATE ".TABLE_PREFIX."content SET ordering=$ordering WHERE content_id=$row[content_id]", $db);
					}
					$ordering++;
				}
				return;
			}
		} else {
			$errors[] = 'Directory was found, but the old configuration file cannot be found.';
		}
	} else {
		$errors[] = 'Directory does not exist relative to the new installation.';
	}
}

if (isset($progress)) {
	print_feedback($progress);
}

if (isset($errors)) {
	print_errors($errors);
}

?>
<p>Please specify where the old installation of ATutor is and review the notes at the bottom carefully.</p>

<p>If the old ATutor installation directory was renamed to <kbd>ATutor_old</kbd> then enter that name below. The old version must be at the same directory level as the new version.</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<input type="hidden" name="new_version" value="<?php echo $new_version; ?>" />
<input type="hidden" name="step" value="1" />

<table width="50%" class="tableborder" cellspacing="0" cellpadding="1" border="0" align="center">
<tr>
	<td class="row1"><div class="required" title="Required Field">*</div><b><label for="dir">Old Directory Name:</label></b><br />
		The old directory must be at the same level as the current directory.</td>
		<td class="row1" valign="middle"><input type="text" id="dir" name="old_path" value="<?php if (!empty($_POST['old_path'])) { echo stripslashes(htmlspecialchars($_POST['old_path'])); } ?>" class="formfield" /></td>
</tr>
</table>

<br />
<p><strong>Note 1:</strong> Release Candidate (RC) installations cannot be upgraded.</p>

<p><strong>Note 2:</strong> Depending on the size of the existing courses, some steps of the upgrade may require considerable time to complete (in particular steps 2 and 6).</p>

<p><strong>Note 3:</strong> All installed language packs and changes made to the default English language will be deleted.</p>

<p><strong>Note 4:</strong> Some installed themes may not be supported by this version of ATutor.</p>

<br /><p align="center"><input type="submit" class="button" value="Next &raquo; " name="submit" /></p>

</form>