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
// $Id: preview_question.php 2661 2004-12-03 19:53:53Z shozubq $

$page = 'tests';
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
	
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

echo '<h4><a href="'.$_base_path.'tools/tests/question_db.php">'._AT('question_database').'</a>: '._AT('preview').'</h4>';
echo '<br />';

if ($row['properties'] == AT_TESTS_QPROP_ALIGN_VERT) {
	$spacer = '<br />';
} else {
	$spacer = ', ';
}

switch ($row['type']) {
	
	case AT_TESTS_MC:
		/* multiple choice question */
		echo AT_print($row['question'], 'tests_questions.question').'<br /><p>';
		for ($i=0; $i < 10; $i++) {
			if ($row['choice_'.$i] != '') {
				if ($i > 0) {
					echo $spacer;
				}
					 
				echo '<input type="radio" name="question_'.$row['question_id'].'" value="'.$i.'" id="choice_'.$row['question_id'].'_'.$i.'" /><label for="choice_'.$row['question_id'].'_'.$i.'">'.AT_print($row['choice_'.$i], 'tests_answers.answer').'</label>';
			}
		}

		echo $spacer;
		echo '<input type="radio" name="question_'.$row['question_id'].'" value="-1" id="choice_'.$row['question_id'].'_x" checked="checked" /><label for="choice_'.$row['question_id'].'_x"><i>'._AT('leave_blank').'</i></label>';
		echo '</p>';
		break;
				
	case AT_TESTS_TF:
		/* true or false quastion */
		echo AT_print($row['question'], 'tests_questions.question').'<br />';

		echo '<input type="radio" name="question_'.$row['question_id'].'" value="1" id="choice_'.$row['question_id'].'_1" /><label for="choice_'.$row['question_id'].'_1">'._AT('true').'</label>';

		echo $spacer;
		echo '<input type="radio" name="question_'.$row['question_id'].'" value="0" id="choice_'.$row['question_id'].'_0" /><label for="choice_'.$row['question_id'].'_0">'._AT('false').'</label>';

		echo $spacer;
		echo '<input type="radio" name="question_'.$row['question_id'].'" value="-1" id="choice_'.$row['question_id'].'_x" checked="checked" /><label for="choice_'.$row['question_id'].'_x"><i>'._AT('leave_blank').'</i></label>';

		echo $spacer;
		break;

	case AT_TESTS_LONG:
		/* long answer question */
		echo AT_print($row['question'], 'tests_questions.question').'<br /><p>';
		switch ($row['properties']) {
			case AT_TESTS_QPROP_WORD:
				/* one word */
				echo '<input type="text" name="question_'.$row['question_id'].'" class="formfield" size="15" />';
				break;

			case AT_TESTS_QPROP_SENTENCE:
				/* sentence */
				echo '<input type="text" name="question_'.$row['question_id'].'" class="formfield" size="45" />';
				break;
				
			case AT_TESTS_QPROP_PARAGRAPH:
				/* paragraph */
				echo '<textarea cols="55" rows="5" name="question_'.$row['question_id'].'" class="formfield"></textarea>';
				break;

			case AT_TESTS_QPROP_PAGE:
				/* page */
				echo '<textarea cols="55" rows="25" name="question_'.$row['question_id'].'" class="formfield"></textarea>';
				break;
		}

		echo '</p><br />';
		break;

	case AT_TESTS_LIKERT:
		/* Likert question */
		echo AT_print($row['question'], 'tests_questions.question').'<br /><p>';
 
		for ($i=0; $i < 10; $i++) {
			if ($row['choice_'.$i] != '') {
				if ($i > 0) {
					echo $spacer;
				}
					 
				echo '<input type="radio" name="question_'.$row['question_id'].'" value="'.$i.'" id="choice_'.$row['question_id'].'_'.$i.'" /><label for="choice_'.$row['question_id'].'_'.$i.'">'.AT_print($row['choice_'.$i], 'tests_answers.answer').'</label>';
			}
		}

		echo $spacer;
		echo '<input type="radio" name="question_'.$row['question_id'].'" value="-1" id="choice_'.$row['question_id'].'_x" checked="checked" /><label for="choice_'.$row['question_id'].'_x"><i>'._AT('leave_blank').'</i></label>';
		
		echo '</p>';
		break;
}
echo '<hr />';

require(AT_INCLUDE_PATH.'footer.inc.php');
?>