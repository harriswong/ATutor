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

$tid = intval($_GET['id']);

//get course name, course id
$sql	= "SELECT C.course_id, C.title FROM ".TABLE_PREFIX."courses C, ".TABLE_PREFIX."tests T WHERE test_id=$tid";
$result = mysql_query($sql, $db);
if (!($row = mysql_fetch_array($result))){
	$msg->addError('TEST_NOT_FOUND');
	header("Location:course_tests.php?course=".$_GET['course']);
	exit;
}
$course_title = $row['title'];
$course_id = $row['course_id'];

//get challenge tests ------ add AND T.format=1
$sql	= "SELECT R.*, M.public_field FROM ".TABLE_PREFIX."tests_results R, ".TABLE_PREFIX."master_list M WHERE R.final_score<>'' AND M.member_id=R.member_id AND R.test_id=$tid ORDER BY M.public_field, R.date_taken";
$result	= mysql_query($sql, $db);
if (!($row = mysql_fetch_assoc($result))){
	$msg->addError('RESULT_NOT_FOUND');
	header("Location:course_tests.php?course=".$_GET['course']);
	exit;
}  

header('Content-Type: application/x-excel');
header('Content-Disposition: inline; filename="'.$course_title.'_results.csv"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

/* employee #, course id, course title, result, date */
do {
	echo quote_csv($row['public_field']).', ';
	echo $course_id.', ';
	echo quote_csv($course_title).', ';
	echo $row['final_score'].', ';
	echo quote_csv($row['date_taken']);
	echo "\n";
} while ($row = mysql_fetch_array($result));

exit;

?>
