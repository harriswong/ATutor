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

if (!defined('AT_INCLUDE_PATH')) { exit; }

require(AT_INCLUDE_PATH.'lib/admin_categories.inc.php');

$cat	= intval($_GET['cat']);
$course = intval($_GET['course']);

$cats	= array();
$cats[0]  = _AT('all');
$cats[-1] = _AT('cats_uncategorized');

$sql = "SELECT * from ".TABLE_PREFIX."course_cats WHERE cat_parent=0 ORDER BY cat_name ";
$result = mysql_query($sql,$db);
while($row = mysql_fetch_array($result)) {
	$cats[$row['cat_id']] = $row['cat_name'];
}

//gets all subcats
$subs = recursive_get_subcategories($cat);

//gets 2nd level subcats only
$sql_sub	= "SELECT cat_id, cat_name FROM ".TABLE_PREFIX."course_cats WHERE cat_parent=".$cat." ORDER BY cat_name";
$result_sub = mysql_query($sql_sub,$db);
do {
	$sub_cats[$row['cat_id']] = $row['cat_name'];
} while ($row = mysql_fetch_assoc($result_sub));

if ($cat > 0) {
	if ($row = mysql_fetch_assoc($result_sub)) {
		$sql = "SELECT * FROM ".TABLE_PREFIX."courses WHERE hide=0 AND (cat_id=".$cat;
		foreach ($subs as $sub) {
			$sql .= " OR cat_id=".$sub;
		}
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

while ($row = mysql_fetch_assoc($result)) {
	if (!empty($course) && $course==$row['course_id']) {
		$course_row = $row;
		$course_row['login'] = get_login($row['member_id']);
		$courses[$row['course_id']]['selected'] = TRUE;
	} else {
		$courses[$row['course_id']]['selected'] = FALSE;
	}

	$courses[$row['course_id']]['title'] = $row['title'];
	$courses[$row['course_id']]['cat_id'] = $row['cat_id'];
	$courses[$row['course_id']]['url'] = $_SERVER['PHP_SELF'].'?cat='.$cat.SEP.'course='.$row['course_id'].'#info';
}

$savant->assign('cat',	$cat);
$savant->assign('course', $course);
$savant->assign('cats', $cats);
$savant->assign('sub_cats', $sub_cats);

$savant->assign('course_row', $course_row);
$savant->assign('courses', $courses);

$savant->display('users/browse.tmpl.php');

?>