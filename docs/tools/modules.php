<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

$page = 'course_properties';

define('AT_INCLUDE_PATH', '../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_ADMIN);

// 'search.php',  removed
if (isset($_POST['submit'])) {
	if (isset($_POST['main'])) {
		$_POST['main'] = array_intersect($_POST['main'], $_modules);
		$main_links = implode('|', $_POST['main']);
	} else {
		$main_links = '';
	}

	if (isset($_POST['home'])) {
		$_POST['home'] = array_intersect($_POST['home'], $_modules);
		$home_links = implode('|', $_POST['home']);
	} else {
		$home_links = '';
	}

	if ((strlen($main_sections) < 256) && (strlen($home_sections) < 256)) {
		$sql    = "UPDATE ".TABLE_PREFIX."courses SET home_links='$home_links', main_links='$main_links' WHERE course_id=$_SESSION[course_id]";
		$result = mysql_query($sql, $db);
	}

	header('Location: '.$_SERVER['PHP_SELF']);
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="data static" rules="rows" summary="">
<thead>
<tr>
	<th scope="cols"><?php echo _AT('section'); ?></th>
	<th><?php echo _AT('location'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="2"><input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" /></td>
</tr>
</tfoot>
<tbody>
<?php foreach ($_modules as $module): ?>
<tr>
	<td><?php echo $_pages[$module]['title']; ?></td>
	<td>
		<?php if (in_array($module, $_pages[AT_NAV_COURSE])): ?>
			<input type="checkbox" name="main[]" value="<?php echo $module; ?>" id="m<?php echo $module; ?>" checked="checked" /><label for="m<?php echo $module; ?>">[Main Navigation]</label>
		<?php else: ?>
			<input type="checkbox" name="main[]" value="<?php echo $module; ?>" id="m<?php echo $module; ?>" /><label for="m<?php echo $module; ?>">[Main Navigation]</label>
		<?php endif; ?>

		<?php if (in_array($module, $_pages[AT_NAV_HOME])): ?>
			<input type="checkbox" name="home[]" value="<?php echo $module; ?>" id="h<?php echo $module; ?>" checked="checked" /><label for="h<?php echo $module; ?>"><?php echo _AT('home'); ?></label>
		<?php else: ?>
			<input type="checkbox" name="home[]" value="<?php echo $module; ?>" id="h<?php echo $module; ?>" /><label for="h<?php echo $module; ?>"><?php echo _AT('home'); ?></label>
			
		<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>