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

$sql_sub	= "SELECT * FROM ".TABLE_PREFIX."course_cats WHERE cat_parent=".$cat." ORDER BY cat_name";
$result_sub = mysql_query($sql_sub,$db);

if ($cat > 0) {
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

$courses	= array();
while ($row = mysql_fetch_assoc($result)) {
	if (!empty($course) && $course==$row['course_id']) {
		$course_row = $row;
		$course_row['login'] = get_login($row['member_id']);
	} 
	$course_cats[$row['course_id']] = $row['cat_id'];
}
 
$savant->assign('cat',	$cat);
$savant->assign('course', $course);
$savant->assign('cats', $cats);
$savant->assign('sub_cats', $sub_cats);

$savant->assign('course_row', $course_row);
$savant->assign('course_cats', $course_cats);


$savant->display('users/browse.tmpl.php');

 ?>