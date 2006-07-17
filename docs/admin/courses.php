<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto				*/
/* http://atutor.ca															*/
/*																			*/
/* This program is free software. You can redistribute it and/or			*/
/* modify it under the terms of the GNU General Public License				*/
/* as published by the Free Software Foundation.							*/
/****************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_COURSES);

if (isset($_GET['view'], $_GET['id'])) {
	header('Location: instructor_login.php?course='.$_GET['id']);
	exit;
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: edit_course.php?course='.$_GET['id']);
	exit;
} else if (isset($_GET['backups'], $_GET['id'])) {
	header('Location: backup/index.php?course='.$_GET['id']);
	exit;
} else if (isset($_GET['delete'], $_GET['id'])) {
	header('Location: delete_course.php?course='.$_GET['id']);
	exit;
} else if (isset($_GET['report'], $_GET['id'])) {
	header('Location: course_tests.php?course='.$_GET['id']);
	exit;
}  else if (isset($_GET['delete']) || isset($_GET['backups']) || isset($_GET['edit']) || isset($_GET['view'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

$orders = array('asc' => 'desc', 'desc' => 'asc');

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = $_GET['asc'];
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = $_GET['desc'];
} else {
	// no order set
	$order = 'asc';
	$col   = 'title';
}

if ($_GET['id']) {
	$and .= ' AND C.member_id='.intval($_GET['id']);
}

${'highlight_'.$col} = ' style="background-color: #fff;"';


$sql	  = "SELECT COUNT(*)-1 AS cnt, course_id FROM ".TABLE_PREFIX."course_enrollment WHERE approved='y' OR approved='a' GROUP BY course_id";
$sql    = "SELECT COUNT(*) AS cnt, approved, course_id FROM ".TABLE_PREFIX."course_enrollment WHERE approved='y' OR approved='a' GROUP BY course_id, approved";
$result = mysql_query($sql, $db);
while ($row = mysql_fetch_assoc($result)) {
	if ($row['approved'] == 'y') {
		$row['cnt']--; // remove the instructor
	}
	$enrolled[$row['course_id']][$row['approved']] = $row['cnt'];
}

$sql	= "SELECT C.*, M.login, T.cat_name FROM ".TABLE_PREFIX."members M INNER JOIN ".TABLE_PREFIX."courses C USING (member_id) LEFT JOIN ".TABLE_PREFIX."course_cats T USING (cat_id) WHERE 1 $and ORDER BY $col $order";
$result = mysql_query($sql, $db);

if ($_GET['id']) {
	echo '<h3>'._AT('courses_for_login', AT_print($row['login'], 'members.login')).'</h3>';
}
		
$num_rows = mysql_num_rows($result);
?>
<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table class="data" summary="" rules="cols">
<colgroup>
	<?php if ($col == 'title'): ?>
		<col />
		<col class="sort" />
		<col span="6" />
	<?php elseif($col == 'login'): ?>
		<col span="2" />
		<col class="sort" />
		<col span="5" />
	<?php elseif($col == 'access'): ?>
		<col span="3" />
		<col class="sort" />
		<col span="4" />
	<?php elseif($col == 'created_date'): ?>
		<col span="4" />
		<col class="sort" />
		<col span="3" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><a href="admin/courses.php?<?php echo $orders[$order]; ?>=title<?php echo $page_string; ?>"><?php echo _AT('title');               ?></a></th>
	<th scope="col"><a href="admin/courses.php?<?php echo $orders[$order]; ?>=login<?php echo $page_string; ?>"><?php echo _AT('Instructor');          ?></a></th>
	<th scope="col"><a href="admin/courses.php?<?php echo $orders[$order]; ?>=access<?php echo $page_string; ?>"><?php echo _AT('access');             ?></a></th>
	<th scope="col"><a href="admin/courses.php?<?php echo $orders[$order]; ?>=created_date<?php echo $page_string; ?>"><?php echo _AT('created_date'); ?></a></th>
	<th scope="col"><a href="admin/courses.php?<?php echo $orders[$order]; ?>=cat_name<?php echo $page_string; ?>"><?php echo _AT('category'); ?></a></th>
	<th scope="col"><?php echo _AT('enrolled'); ?></th>
	<th scope="col"><?php echo _AT('alumni'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="7">
		<input type="submit" name="view" value="<?php echo _AT('view'); ?>" /> 
		<input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" /> 
		<input type="submit" name="backups" value="<?php echo _AT('backups'); ?>" /> 
		<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
		<input type="submit" name="report" value="Report" />
	</td>
</tr>
</tfoot>
<tbody>
<?php if ($num_rows): ?>
	<?php while ($row = mysql_fetch_assoc($result)): ?>
		<tr onmousedown="document.form['m<?php echo $row['course_id']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['course_id']; ?>">
			<td><input type="radio" name="id" value="<?php echo $row['course_id']; ?>" id="m<?php echo $row['course_id']; ?>" /></td>
			<td><label for="m<?php echo $row['course_id']; ?>"><?php echo AT_print($row['title'], 'courses.title'); ?></label></td>
			<td><?php echo AT_print($row['login'],'members.login'); ?></td>
			<td><?php echo _AT($row['access']); ?></td>
			<td><?php echo $row['created_date']; ?></td>
			<td><?php echo ($row['cat_name'] ? $row['cat_name'] : '-')?></td>
			<td><?php echo ($enrolled[$row['course_id']]['y'] ? $enrolled[$row['course_id']]['y'] : 0); ?></td>
			<td><?php echo ($enrolled[$row['course_id']]['a'] ? $enrolled[$row['course_id']]['a'] : 0); ?></td>
		</tr>
	<?php endwhile; ?>
<?php else: ?>
	<tr>
		<td colspan="8"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
</tbody>
</table>
</form>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>