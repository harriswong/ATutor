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
$page = 'tests';
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
	
$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/';
$_section[1][0] = _AT('test_manager');
$_section[1][1] = 'tools/tests';
$_section[2][0] = _AT('results');

authenticate(AT_PRIV_TEST_MARK);

$tid = intval($_GET['tid']);
if ($tid == 0){
	$tid = intval($_POST['tid']);
}

require(AT_INCLUDE_PATH.'header.inc.php');

$sql	= "SELECT * FROM ".TABLE_PREFIX."tests WHERE test_id=$tid AND course_id=$_SESSION[course_id]";
$result	= mysql_query($sql, $db);
if (!($row = mysql_fetch_array($result))){
	$msg->printErrors('TEST_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}
$out_of = $row['out_of'];
$anonymous = $row['anonymous'];

echo '<h4>'._AT('submissions_for', AT_print($row['title'], 'tests.title')).'</h4><br />';

echo '<p><small>';
if ($_GET['m']) {
	echo '<a href="'.$_SERVER['PHP_SELF'].'?tid='.$tid.'">'._AT('show_marked_unmarked').'</a>';		
} else {
	echo _AT('show_marked_unmarked');
}

echo ' | ';
if ($_GET['m'] != 1) {
	echo '<a href="'.$_SERVER['PHP_SELF'].'?tid='.$tid.SEP.'m=1">'._AT('show_unmarked').'</a>';
} else {
	echo _AT('show_unmarked');
}
echo ' | ';
if ($_GET['m'] != 2){
	echo '<a href="'.$_SERVER['PHP_SELF'].'?tid='.$tid.SEP.'m=2">'._AT('show_marked').'</a>';
} else {
	echo _AT('show_marked');
}

echo '</small></p>';


if ($_GET['m'] == 1) {
	$show = ' AND R.final_score=\'\'';
} else if ($_GET['m'] == 2) {
	$show = ' AND R.final_score<>\'\'';
} else {
	$show = '';
}

$msg->printAll();

if ($anonymous == 1) {
	$sql	= "SELECT R.*, '<em>"._AT('anonymous')."</em>' AS login FROM ".TABLE_PREFIX."tests_results R WHERE R.test_id=$tid $show";
} else {
	$sql	= "SELECT R.*, M.login FROM ".TABLE_PREFIX."tests_results R, ".TABLE_PREFIX."members M WHERE R.test_id=$tid AND R.member_id=M.member_id $show";
}
$result	= mysql_query($sql, $db);
$num_results = mysql_num_rows($result);

if ($row = mysql_fetch_array($result)) {
	$count		 = 0;
	$total_score = 0;
	echo '<table cellspacing="1" cellpadding="0" border="0" class="bodyline" summary="" align="center" width="90%">';
	echo '<tr>';
	echo '<th scope="col"><small>'._AT('username').'</small></th>';
	echo '<th scope="col"><small>'._AT('date_taken').'</small></th>';
	echo '<th scope="col"><small>'._AT('mark').'</small></th>';
	if ($out_of) {
		echo '<th scope="col"><small>'._AT('view_mark_test').'</small></th>';
	} else {
		echo '<th scope="col"><small>'._AT('view').'</small></th>';
	}

	echo '<th scope="col"><small>'._AT('delete').'</small></th>';
	echo '</tr>';
	do {
		echo '<tr>';
		echo '<td class="row1"><small><strong>'.$row['login'].'</strong></small></td>';

		echo '<td class="row1"><small>'.AT_date('%j/%n/%y %G:%i', $row['date_taken'], AT_DATE_MYSQL_DATETIME).'</small></td>';

		echo '<td class="row1" align="center"><small>';
		if ($out_of) {
			if ($row['final_score'] != '') { 
				echo $row['final_score'];
			} else {
				echo _AT('unmarked');
			}
		} else {
			echo _AT('na');
		}
		echo '</small></td>';

		echo '<td class="row1" align="center"><small><a href="tools/tests/view_results.php?tid='.$tid.SEP.'rid='.$row['result_id'].SEP.'m='.$_GET['m'].'">';
		if ($out_of) {
			echo _AT('view_mark_test');
		} else {
			echo _AT('view');
		}
		echo '</a></small></td>';
		
		echo '<td class="row1" align="center"><small><a href="tools/tests/delete_result.php?tid='.$tid.SEP.'rid='.$row['result_id'].SEP.'tt='.$row['login'].SEP.'m='.$_GET['m'].'">'._AT('delete').'</a></small></td>';

		echo '</tr>';
		$count++;
		if ($count < $num_results) {
			echo '<tr><td height="1" class="row2" colspan="5"></td></tr>';
		}
	} while ($row = mysql_fetch_array($result));
	echo '</table>';
} else {
	echo '<em>'._AT('no_results_available').'</em>';
}

require(AT_INCLUDE_PATH.'footer.inc.php');
?>