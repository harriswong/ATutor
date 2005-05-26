<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_COURSES);

function quote_csv($line) {
	$line = str_replace('"', '""', $line);
	$line = str_replace("\n", '\n', $line);
	$line = str_replace("\r", '\r', $line);
	$line = str_replace("\x00", '\0', $line);

	return '"'.$line.'"';
}

//get course id
$cid = intval($_GET['course']);
//get course name 
$sql	= "SELECT title FROM ".TABLE_PREFIX."courses WHERE course_id=$cid";
$result = mysql_query($sql, $db);
if (!($row = mysql_fetch_array($result))){
	$msg->addError('COURSE_NOT_FOUND');
	header("Location:courses.php");
	exit;
}
$course_title = $row['title'];

//get challenge tests ------ add AND T.format=1
$sql	= "SELECT R.*, M.public_field FROM ".TABLE_PREFIX."tests_results R, ".TABLE_PREFIX."tests T, ".TABLE_PREFIX."master_list M WHERE R.test_id=T.test_id AND T.test_id=1 AND T.course_id=$cid AND R.final_score<>'' AND M.member_id=R.member_id ORDER BY R.date_taken";
$result	= mysql_query($sql, $db);
if (!($row = mysql_fetch_assoc($result))){
	$msg->addError('RESULT_NOT_FOUND');
	header("Location:courses.php");
	exit;
}  

header('Content-Type: application/x-excel');
header('Content-Disposition: inline; filename="'.$course_title.'_results.csv"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

/* employee #, course id, course title, result, date */
do {
	//get employee id
	echo quote_csv($row['public_field']).', ';
	echo $cid.', ';
	echo quote_csv($course_title).', ';
	echo $row['final_score'].', ';
	echo quote_csv(AT_date('%j/%n/%y %G:%i', $row['date_taken'], AT_DATE_MYSQL_DATETIME));
	echo "\n";
} while ($row = mysql_fetch_array($result));

?>
