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

$cat			= $_GET['cat'];
$course			= $_GET['course'];
$current_cats	= array();

$cats[0]  = _AT('all');
$cats[-1] = _AT('cats_uncategorized');

$sql = "SELECT * from ".TABLE_PREFIX."course_cats WHERE cat_parent=0 ORDER BY cat_name ";
$result = mysql_query($sql,$db);
while($row = mysql_fetch_array($result)) {
	$cats[$row['cat_id']] = $row['cat_name'];
}
 ?>
<div id="browse" >
	<div style="float: left; width: 30%"">
			<h3><?php echo _AT('cats_categories'); ?></h3>

			<?php 
			foreach ($cats as $cat_id => $cat_name) {
				if ($cat_id == $cat) {
					echo '<div class="browse-selected">';
				} else {
					echo '<div class="browse-unselected">';
				}

				echo '<a href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'">'.$cat_name.'</a>';
				echo '<br /></div>';
			}
			?>
			<br />
	</div>
	<div style="float: left; width: 20%"">
			<h3><?php echo $cats[$cat].' '._AT('courses'); ?></h3>
			<?php
			if ($cat > 0) {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND cat_id=".$_GET['cat']." ORDER BY title";
			} else if ($cat == "-1") {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND cat_id=0 ORDER BY title";
			} else {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 ORDER BY title";
			}
			$result = mysql_query($sql,$db);

			if ($row = mysql_fetch_assoc($result)) {
				do {
					if (!empty($course) && $course==$row['course_id']) {
						$course_row = $row;
						echo '<div class="browse-selected">';
					} else {
						echo '<div class="browse-unselected">';
					}
					//echo '<a href="bounce.php?course='.$row['course_id'].'">'.$system_courses[$row['course_id']]['title'].'</a><br />';
					echo '<a href="'.$_SERVER['PHP_SELF'].'?cat='.$cat.SEP.'course='.$row['course_id'].'">'.$system_courses[$row['course_id']]['title'].'</a><br />';
					echo '</div>';

				} while ($row = mysql_fetch_assoc($result));
			} else {
				echo _AT('no_courses');
			}
			?>
			<br />
	</div>

	<?php if (isset($course_row)) { ?>
	<div style="float: left; width: 50%">
			<h3><?php echo $course_row['title'].' '._AT('info'); ?></h3>

			<p><?php echo $course_row['description']; ?></p>

			<p>Taught by 
			<?php	
				$sql = "SELECT login, first_name, last_name FROM ".TABLE_PREFIX."members WHERE member_id=".$course_row['member_id'];
				$result = mysql_query($sql,$db);
				if ($row = mysql_fetch_array($result)) {
					echo $row['first_name'].' '.$row['last_name'];
				}						
			?>
			, Access is <?php echo $course_row['access']; ?></p>

			<p><a href="bounce.php?course=<?php echo $course_row['course_id']; ?>">Enter Course</a></p>
			<br />
	</div>
	<?php } ?>
</div>
<br />
<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>