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
// $Id$

$page = 'tests';
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_TEST_CREATE);

$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/index.php';
$_section[1][0] = _AT('test_manager');
$_section[1][1] = 'tools/tests/index.php';
$_section[2][0] = _AT('questions');

$tid = intval($_REQUEST['tid']);

if (isset($_POST['done'])) {
	header('Location: index.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');

if (isset($_POST['submit'])) {
	// check if we own this tid:
	$sql    = "SELECT test_id FROM ".TABLE_PREFIX."tests WHERE test_id=$tid AND course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);
	if ($row = mysql_fetch_assoc($result)) {

		//update the weights
		$total_weight = 0;
		foreach ($_POST['weight'] as $qid => $weight) {
			$weight = $addslashes($weight);
			$sql	= "UPDATE ".TABLE_PREFIX."tests_questions_assoc SET weight=$weight WHERE question_id=$qid AND test_id=".$tid;
			$result	= mysql_query($sql, $db);
			$total_weight += $weight;
		}

		$sql	= "UPDATE ".TABLE_PREFIX."tests SET out_of='$total_weight' WHERE test_id=$tid";
		$result	= mysql_query($sql, $db);
	}
	$total_weight = 0;
	$msg->addFeedback('QUESTION_WEIGHT_UPDATED');
}

$sql	= "SELECT title FROM ".TABLE_PREFIX."tests WHERE test_id=$tid";
$result	= mysql_query($sql, $db);
$row	= mysql_fetch_array($result);
echo '<h3>'._AT('questions_for').' '.AT_print($row['title'], 'tests.title').'</h3>';

$sql	= "SELECT count(*) as cnt FROM ".TABLE_PREFIX."tests_questions_assoc QA, ".TABLE_PREFIX."tests_questions Q WHERE QA.test_id=$tid AND QA.weight=0 AND QA.question_id=Q.question_id AND Q.type<>".AT_TESTS_LIKERT;
$result	= mysql_query($sql, $db);
$row = mysql_fetch_array($result);
if ($row['cnt']) {
	$msg->printWarnings('QUESTION_WEIGHT');
}

$msg->printAll();

$sql	= "SELECT * FROM ".TABLE_PREFIX."tests_questions Q, ".TABLE_PREFIX."tests_questions_assoc TQ WHERE Q.course_id=$_SESSION[course_id] AND Q.question_id=TQ.question_id AND TQ.test_id=$tid";
$result	= mysql_query($sql, $db);

unset($editors);
$editors[] = array('priv' => AT_PRIV_TEST_CREATE, 'title' => _AT('add_questions'), 'url' => 'tools/tests/add_test_questions.php?tid=' . $tid);
$editors[] = array('priv' => AT_PRIV_TEST_CREATE, 'title' => _AT('preview'), 'url' => 'tools/tests/preview.php?tid=' . $tid);
echo '<div align="center">';
print_editor($editors , $large = false);
echo '</div>';


echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form">';
echo '<input type="hidden" name="tid" value="'.$tid.'" />';
echo '<table cellspacing="1" cellpadding="0" border="0" class="bodyline" summary="" align="center" width="90%">';
echo '<tr>';
echo '<th scope="col"><small>'._AT('num').'</small></th>';
echo '<th scope="col"><small>'._AT('weight').'</small></th>';
echo '<th scope="col"><small>'._AT('question').'</small></th>';
echo '<th scope="col"><small>'._AT('type').'</small></th>';
echo '<th scope="col"><small>'._AT('category').'</small></th>';
echo '<th scope="col"></th>';
echo '</tr>';

if ($row = mysql_fetch_assoc($result)) {
	do {
		$total_weight += $row['weight'];
		$count++;
		echo '<tr>';
		echo '<td class="row1" align="center"><small><b>'.$count.'</b></small></td>';
		echo '<td class="row1" align="center">';
		
		if ($row['type'] == 4) {
			echo '<small>'._AT('na').'</small>';
			echo '<input type="hidden" value="0" name="weight['.$row['question_id'].']" />';
		} else {
			echo '<input type="text" value="'.$row['weight'].'" name="weight['.$row['question_id'].']" size="2" class="formfieldR" />';
		}
		echo '</td>';
		echo '<td class="row1"><small>';
		if (strlen($row['question']) > 45) {
			echo AT_print(substr($row['question'], 0, 43), 'tests_questions.question') . '...';
		} else {
			echo AT_print(htmlspecialchars($row['question']), 'tests_questions.question');
		}
		echo '</small></td>';
		echo '<td class="row1" nowrap="nowrap"><small>';
		switch ($row['type']) {
			case 1:
				echo _AT('test_mc');
				break;
				
			case 2:
				echo _AT('test_tf');
				break;
	
			case 3:
				echo _AT('test_open');
				break;
			case 4:
				echo _AT('test_lk');
				break;
		}
				
		echo '</small></td>';
		
		$sql	= "SELECT title FROM ".TABLE_PREFIX."tests_questions_categories WHERE category_id=".$row['category_id']." AND course_id=".$_SESSION['course_id'];
		$cat_result	= mysql_query($sql, $db);

		if ($cat = mysql_fetch_array($cat_result)) {
			echo '<td class="row1" align="center"><small>'.$cat['title'].'</small></td>';
		} else {
			echo '<td class="row1" align="center"><small>'._AT('na').'</small></td>';
		}

		echo '<td class="row1" nowrap="nowrap"><small>';
		switch ($row['type']) {
			case 1:
				echo '<a href="tools/tests/edit_question_multi.php?tid='.$tid.SEP.'qid='.$row['question_id'].'">';
				break;
				
			case 2:
				echo '<a href="tools/tests/edit_question_tf.php?tid='.$tid.SEP.'qid='.$row['question_id'].'">';
				break;
			
			case 3:
				echo '<a href="tools/tests/edit_question_long.php?tid='.$tid.SEP.'qid='.$row['question_id'].'">';
				break;
			case 4:
				echo '<a href="tools/tests/edit_question_likert.php?tid='.$tid.SEP.'qid='.$row['question_id'].'">';
				break;
		}

		echo _AT('edit_shortcut').'</a> | ';
		echo '<a href="tools/tests/question_remove.php?tid=' . $tid . SEP . 'qid=' . $row['question_id'] . '">' . _AT('remove') . '</a>';
		//echo '<a href="tools/tests/preview_question.php?qid='.$row['question_id'].'">'._AT('preview').'</a>';
		echo '</small></td>';

		echo '</tr>';
		if($count != mysql_num_rows($result)) {
			echo '<tr><td height="1" class="row2" colspan="6"></td></tr>';
		}
	} while ($row = mysql_fetch_assoc($result));

	//total weight
	echo '<tr><td height="1" class="row2" colspan="7"></td></tr>';
	echo '<tr><td height="1" class="row2" colspan="7"></td></tr>';
	echo '<tr>';
	echo '<td class="row1" colspan="2" align="center" nowrap="nowrap"><small><strong>'._AT('total').':</strong></small> '.$total_weight.'</td>';

	echo '<td class="row1" colspan="4" align="left" nowrap="nowrap">';
	echo '<small><input type="submit" value="'._AT('update').'" name="submit" class="button" /> | <input type="submit"  value="'._AT('done').'" name="done" class="button" /></small></td>';
	echo '</tr>';
} else {
	echo '<tr><td colspan="6" class="row1"><small><i>'._AT('no_questions_avail').'</i></small></td></tr>';
}

echo '</table><br /></form>';

require(AT_INCLUDE_PATH.'footer.inc.php');
?>