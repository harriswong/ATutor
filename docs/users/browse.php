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

$cat	= intval($_GET['cat']);
$course	= intval($_GET['course']);
$cats	= array();

$cats[0]  = _AT('all');
$cats[-1] = _AT('cats_uncategorized');

$sql = "SELECT * from ".TABLE_PREFIX."course_cats WHERE cat_parent=0 ORDER BY cat_name ";
$result = mysql_query($sql,$db);
while($row = mysql_fetch_array($result)) {
	$cats[$row['cat_id']] = $row['cat_name'];
}
 ?>
<div id="browse" >
	<div style="float: left; white-space:nowrap; padding-right:30px;">
			<h3><?php echo _AT('cats_categories'); ?></h3>

			<ul class="browse-list">
			<?php 
			foreach ($cats as $cat_id => $cat_name) {
				if ($cat_id == $cat) {
					echo '<div class="browse-selected">';
				} else {
					echo '<div class="browse-unselected">';
				}

				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'#courses">'.$cat_name.'</a></li>';
				echo '</div>';
			}
			?>
			</ul>
	</div>
	<a name="courses"></a>
	<div style="float: left; white-space:nowrap; padding-right:30px;">
			<?php
			if ($cat > 0) {				
				//get its subcats
				$sql_sub	= "SELECT * FROM ".TABLE_PREFIX."course_cats WHERE cat_parent=".$cat." ORDER BY cat_name";
				$result_sub = mysql_query($sql_sub,$db);

				if ($row = mysql_fetch_assoc($result_sub)) {
					$sql = "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND (cat_id=".$cat;
					do {
						$sql .= " OR cat_id=".$row['cat_id'];
						$sub_cats[$row['cat_id']] = $row['cat_name'];
					} while ($row = mysql_fetch_assoc($result_sub));
					$sql .= ") ORDER BY cat_id, title";
				} else {
					$sql = "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND cat_id=".$cat." ORDER BY title";
				}				
			} else if ($cat == -1) {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND cat_id=0 ORDER BY title";
			} else {
				$sql	= "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 ORDER BY title";
				$cat=0;
			}
			$result = mysql_query($sql,$db);

			echo '<h3>'.$cats[$cat].' '._AT('courses').'</h3>';

			if ($row = mysql_fetch_assoc($result)) {
				$cur_sub_cat = '';
				echo '<ul class="browse-list">';
				do {
					if (isset($sub_cats) && array_key_exists($row['cat_id'], $sub_cats) && ($cur_sub_cat != $sub_cats[$row['cat_id']])) {
						$cur_sub_cat = $sub_cats[$row['cat_id']];
						echo '</ul><br /><h4>'.$cur_sub_cat.'</h4><ul class="browse-list">';
					}
					if (!empty($course) && $course==$row['course_id']) {
						$course_row = $row;
						echo '<div class="browse-selected">';
					} else {
						echo '<div class="browse-unselected">';
					}
					echo '<li><a href="'.$_SERVER['PHP_SELF'].'?cat='.$cat.SEP.'course='.$row['course_id'].'#info">'.$system_courses[$row['course_id']]['title'].'</a></li>';
					echo '</div>';

				} while ($row = mysql_fetch_assoc($result));
				echo '</ul>';
			} else {
				echo _AT('no_courses');
			}
			?>
			<br />
	</div>

	<?php if (isset($course_row)) { ?>
	<a name="info"></a>
	<div style="float: left; width: 50%;">
			<h3><?php echo $course_row['title'].' '._AT('info'); ?></h3>

			<p><?php echo $course_row['description']; ?></p>

			<p> 
			<?php
				echo _AT('instructor').': ';
				$sql = "SELECT login FROM ".TABLE_PREFIX."members WHERE member_id=".$course_row['member_id'];
				$result = mysql_query($sql,$db);
				if ($row = mysql_fetch_array($result)) {
					echo $row['login'];
				}				
				echo '</p><p>';
				echo _AT('access').': '.$course_row['access']; ?></p>

			<p><a href="bounce.php?course=<?php echo $course_row['course_id']; ?>"><?php echo _AT('enter_course'); ?></a></p>
			<br />
	</div>
	<?php } ?>
</div>
<br />
<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>