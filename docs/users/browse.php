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

$page = 'browse_courses';
$_user_location	= 'users';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('browse_courses');

require(AT_INCLUDE_PATH.'header.inc.php');
$msg->printAll();

$cat = $_GET['cat'];

$sql = "SELECT * from ".TABLE_PREFIX."course_cats ORDER BY cat_name ";
$result = mysql_query($sql,$db);
if (mysql_num_rows($result) == 0) {
	$empty = true;
} 
?>

<table align="center" width="40%" border="1">
	<tr>
		<th>Category</th>
		<th>Courses</th>
	</tr>

	<tr>
	<td>

			<?php if (empty($cat)) { ?>
				<img src="images/side_arrow.gif" /><a href="<?php echo $_SERVER['PHP_SELF']; ?>">All Courses</a><br />
			<?php } else { ?>
				<div style="margin-left: 9px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">All Courses</a><br /></div>
			<?php }	?>
			
			<?php 
			while($row = mysql_fetch_array($result)){
				if ($row['cat_id'] == $cat) {
					echo '<img src="images/side_arrow.gif" />';
					echo '<a href="'.$_SERVER['PHP_SELF'].'?cat='.$row['cat_id'].'">'.$row['cat_name'].'</a><br />';
				} else {
					echo '<div style="margin-left: 9px;">';
					echo '<a href="'.$_SERVER['PHP_SELF'].'?cat='.$row['cat_id'].'">'.$row['cat_name'].'</a><br /></div>';
				}
				$current_cats[$row['cat_id']] = $row['cat_name'];
			}
			?>
			</div>
	</td>
	<td align="left" valign="top">
			<?php
			if (!empty($cat)) {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND cat_id=".$_GET['cat']." ORDER BY title";
			} else {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 ORDER BY title";
			}
			$result = mysql_query($sql,$db);

			while($row = mysql_fetch_assoc($result)){
				echo '<a href="bounce.php?course='.$row['course_id'].'">'.$system_courses[$row['course_id']]['title'].'</a><br />';
			}
			?>
	</td>
</tr>
</table>

<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>