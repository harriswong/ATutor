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

function make_csv($test_id) {
	global $msg, $db, $system_courses;

	//get course name, course id
	$sql	= "SELECT course_id, title FROM ".TABLE_PREFIX."tests WHERE test_id=$test_id";
	$result = mysql_query($sql, $db);
	$row = mysql_fetch_array($result);

	$course_id = $row['course_id'];
	$test_title = $row['title'];
	$course_title = $system_courses[$course_id]['title'];

	//get test
	$sql	= "SELECT R.*, M.public_field FROM ".TABLE_PREFIX."tests_results R, ".TABLE_PREFIX."master_list M WHERE R.final_score<>'' AND M.member_id=R.member_id AND R.test_id=$test_id ORDER BY M.public_field, R.date_taken";
	$result	= mysql_query($sql, $db);
	$row = mysql_fetch_assoc($result);  

	/* employee #, course id, course title, result, date */
	$csv = array();
	$csv_data = "";
	do {
		$csv_data .= quote_csv($row['public_field']).', ';
		$csv_data .= $course_id.', ';
		$csv_data .= quote_csv($course_title).', ';
		$csv_data .= $row['final_score'].', ';
		$csv_data .= quote_csv($row['date_taken']);
		$csv_data .= "\n";
	} while ($row = mysql_fetch_array($result));	

	$csv['name'] = $course_title.'_'.$test_title.'_results.csv';
	$csv['name'] = str_replace("\\", '_', $csv['name']);
	$csv['name'] = str_replace(" ", '_', $csv['name']);
	$csv['data'] = $csv_data;

	return $csv;
}

if (count($_GET['id']) > 1) {
	require(AT_INCLUDE_PATH.'classes/zipfile.class.php');

	$course_title = $system_courses[$_GET['course']]['title'];
	$course_title = str_replace("//", '_', $course_title);
	$course_title = str_replace(" ", '_', $course_title);

	$zipfile = new zipfile();

	foreach($_GET['id'] as $test_id) {
		$csv = make_csv($test_id);
		if ($csv['data']) {
			$zipfile->add_file($csv['data'], $csv['name']);
		}
	}

	if ($zipfile->num_entries > 0) {
		$zipfile->close();
		$zipfile->send_file($course_title.'_test_results');
	} else {
		$msg->addError('RESULT_NOT_FOUND');
		header("Location:course_tests.php?course=".$_GET['course']);
		exit;
	}

} else if (count($_GET['id']) == 1) {
	$csv = make_csv($_GET['id'][0]);

	if ($csv['data']) {
		header('Content-Type: application/x-excel');
		header('Content-Disposition: inline; filename="'.$csv['name'].'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		echo $csv['data'];
	} else {
		$msg->addError('RESULT_NOT_FOUND');
		header("Location:course_tests.php?course=".$_GET['course']);
		exit;
	}
} 

exit;

?>