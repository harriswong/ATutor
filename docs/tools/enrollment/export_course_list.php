<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/

define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/index.php';
$_section[1][0] = _AT('course_enrolment');
$_section[1][1] = 'tools/enrollment/index.php';
$_section[2][0] = _AT('list_export_course_list');
$_section[2][1] = 'tools/export_course_list.php';

$title = _AT('course_enrolment');
$completed = 0;

/*EXPORT LIST OF STUDENTS*/
if(isset($_POST['export'])) {
	//if not list was selected
	if (!$_POST['enrolled'] && !$_POST['unenrolled'] && !$_POST['alumni']) {
		$msg->addError('NO_STUDENT_SELECTED');
	}
	//retrieve info from database based on selection (make sure that instructor is not exported!)
	else {
		if ($_POST['enrolled'] && $_POST['unenrolled'] && $_POST['alumni']) {
			$condition = "";
		} else if ($_POST['enrolled'] && $_POST['unenrolled']) {
			$condition = "AND approved <> 'a'";
		} else if ($_POST['enrolled'] && $_POST['alumni']) {
			$condition = "AND approved <> 'n'";
		} else if ($_POST['unenrolled'] && $_POST['alumni']) {
			$condition = "AND approved <> 'y'";
		} else if ($_POST['unenrolled']) {
			$condition = "AND approved = 'n'";				
		} else if ($_POST['enrolled']) {
			$condition = "AND approved = 'y'";
		} else if ($_POST['alumni']) {
			$condition = "AND approved = 'a'";
		} 

		$sql = "SELECT m.first_name, m.last_name, m.email 
				FROM ".TABLE_PREFIX."course_enrollment cm JOIN ".TABLE_PREFIX."members m ON cm.member_id = m.member_id JOIN ".TABLE_PREFIX."courses c ON (cm.course_id = c.course_id AND cm.member_id <> c.member_id)	WHERE cm.course_id = $_SESSION[course_id] " . $condition . "ORDER BY m.last_name";

		$result =  mysql_query($sql,$db);
		while ($row = mysql_fetch_assoc($result)){
			$this_row .= quote_csv($row['first_name']).",";
			$this_row .= quote_csv($row['last_name']).",";
			$this_row .= quote_csv($row['email'])."\n";
		}

		
		header('Content-Type: text/csv');
		header('Content-transfer-encoding: binary');
		header('Content-Disposition: attachment; filename="course_list_'.$_SESSION['course_id'].'.csv"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		echo $this_row;
		exit;
	}
}
if(isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
}

if(isset($_POST['done'])) {
	$msg->addFeedback('COMPLETED');
	header('Location: index.php');
	exit;
}
require(AT_INCLUDE_PATH.'header.inc.php');


?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="selectform">
<div class="input-form">
	<div class="row">
		<label><input type="checkbox" name="enrolled" value="1" id="enrolled" /><?php echo _AT('enrolled_list_includes_assistants'); ?></label><br />
		<label><input type="checkbox" name="unenrolled" value="1" id="unenrolled" /><?php echo _AT('unenrolled_list'); ?></label><br />
		<label><input type="checkbox" name="alumni" value="1" id="alumni" /><?php echo _AT('alumni'); ?></label>
	</div>

	<div class="row buttons">
		<input type="submit" name="export" value="<?php echo _AT('export'); ?>" /> 
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>

<?php 

/**
* Creates csv file to be exported
* @access  private
* @param   string $line		The line ot be converted to csv
* @return  string			The line after conversion to csv
* @author  Shozub Qureshi
*/
function quote_csv($line) {
	$line = str_replace('"', '""', $line);

	$line = str_replace("\n", '\n', $line);
	$line = str_replace("\r", '\r', $line);
	$line = str_replace("\x00", '\0', $line);

	return '"'.$line.'"';
}

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>