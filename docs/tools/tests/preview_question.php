<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2007 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'classes/testQuestions.class.php');
authenticate(AT_PRIV_TESTS);

function ordering_seed($question_id) {
	// by controlling the seed before calling array_rand() we insure that
	// we can un-randomize the order for marking.
	// used with ordering type questions only.
	srand($question_id + ord(DB_PASSWORD) + $_SESSION['member_id']);
}


if (isset($_GET['submit'])) {
	header('Location: '.$_base_href.'tools/tests/question_db.php');
	exit;
}

if (defined('AT_FORCE_GET_FILE') && AT_FORCE_GET_FILE) {
	$content_base_href = 'get.php/';
} else {
	$content_base_href = 'content/' . $_SESSION['course_id'] . '/';
}

require(AT_INCLUDE_PATH.'header.inc.php');

$qid = intval($_GET['qid']);
$sql = "SELECT * FROM ".TABLE_PREFIX."tests_questions WHERE course_id=$_SESSION[course_id] AND question_id=$qid";

$result	= mysql_query($sql, $db);
$row = mysql_fetch_assoc($result);

if ($row['properties'] == AT_TESTS_QPROP_ALIGN_VERT) {
	$spacer = '<br />';
} else {
	$spacer = ', ';
}
echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">';
echo '<div class="input-form">';

$_letters = array(_AT('A'), _AT('B'), _AT('C'), _AT('D'), _AT('E'), _AT('F'), _AT('G'), _AT('H'), _AT('I'), _AT('J'));

$obj = test_question_factory($row['type']);
$obj->display($row);

echo '<div class="row buttons"><input type="submit" name="submit" value="'._AT('back').'" /></div>';
echo '</div>';
echo '</form>';
?>
<script type="text/javascript">
//<!--
function iframeSetHeight(id, height) {
	document.getElementById("qframe" + id).style.height = (height + 20) + "px";
}
//-->
</script>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>