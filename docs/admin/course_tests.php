<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto				*/
/* http://atutor.ca															*/
/*																			*/
/* This program is free software. You can redistribute it and/or			*/
/* modify it under the terms of the GNU General Public License				*/
/* as published by the Free Software Foundation.							*/
/****************************************************************************/
// $Id: courses.php 4717 2005-05-26 16:26:43Z heidi $

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_COURSES);

//get course id
$course = intval($_GET['course']);

if (isset($_GET['report']) && !$_GET['id']) {
	$msg->addError('NO_ITEM_SELECTED');
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

$sql	= "SELECT * FROM ".TABLE_PREFIX."tests WHERE course_id=$course ORDER BY title";
$result = mysql_query($sql, $db);
$num_rows = mysql_num_rows($result);
?>

<form name="form" method="get" action="admin/course_report.php">
<input type="hidden" name="course" value="<?php echo $course; ?>" />

<table class="data" summary="" style="width:60%;">
<thead>
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col"><?php echo _AT('title'); ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="7">
			<input type="submit" name="report" value="Get Reports" />
		</td>
	</tr>
</tfoot>
<tbody>
	<?php if ($num_rows): ?>
		<?php while ($row = mysql_fetch_assoc($result)): ?>
			<tr onmousedown="document.form['t<?php echo $row['course_id']; ?>'].checked = true;">
				<td width="1"><input type="checkbox" name="id[]" value="<?php echo $row['test_id']; ?>" id="t<?php echo $row['test_id']; ?>" /></td>
				<td><label for="t<?php echo $row['test_id']; ?>"><?php echo AT_print($row['title'], 'tests.title'); ?>
					<?php if ($row['format'] == 1) { echo " - <strong>Challenge Test</strong>"; } ?>					
				</label></td>
			</tr>
		<?php endwhile; ?>
	<?php else: ?>
		<tr>
			<td colspan="7"><?php echo _AT('none_found'); ?></td>
		</tr>
	<?php endif; ?>
</tbody>
</table>

</form>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>