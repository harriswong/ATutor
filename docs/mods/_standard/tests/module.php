<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

define('AT_PRIV_TESTS', $this->getPrivilege());

// if this module is to be made available to students on the Home or Main Navigation
$_student_tool = 'tools/my_tests.php';

$this->addCommand('calendar_source');

$this->_pages['tools/tests/index.php']['title_var'] = 'tests';
$this->_pages['tools/tests/index.php']['parent']    = 'tools/index.php';
$this->_pages['tools/tests/index.php']['guide']     = 'instructor/?p=tests_surveys.php';
$this->_pages['tools/tests/index.php']['children']  = array('tools/tests/create_test.php', 'tools/tests/question_db.php', 'tools/tests/question_cats.php');

$this->_pages['tools/tests/create_test.php']['title_var'] = 'create_test';
$this->_pages['tools/tests/create_test.php']['parent']    = 'tools/tests/index.php';
$this->_pages['tools/tests/create_test.php']['guide']     = 'instructor/?p=creating_tests_surveys.php';

$this->_pages['tools/tests/question_db.php']['title_var'] = 'question_database';
$this->_pages['tools/tests/question_db.php']['parent']    = 'tools/tests/index.php';
$this->_pages['tools/tests/question_db.php']['guide']     = 'instructor/?p=question_database.php';

	$this->_pages['tools/tests/create_question_multi.php']['title_var'] = 'create_question_multi';
	$this->_pages['tools/tests/create_question_multi.php']['parent']    = 'tools/tests/question_db.php';


$this->_pages['tools/tests/question_cats.php']['title_var'] = 'question_categories';
$this->_pages['tools/tests/question_cats.php']['parent']    = 'tools/tests/index.php';
$this->_pages['tools/tests/question_cats.php']['children']  = array('tools/tests/question_cats_manage.php');
$this->_pages['tools/tests/question_cats.php']['guide']     = 'instructor/?p=question_categories.php';

$this->_pages['tools/tests/question_cats_manage.php']['title_var'] = 'create_category';
$this->_pages['tools/tests/question_cats_manage.php']['parent']    = 'tools/tests/question_cats.php';

$this->_pages['tools/tests/question_cats_delete.php']['title_var'] = 'delete_category';
$this->_pages['tools/tests/question_cats_delete.php']['parent']    = 'tools/tests/question_cats.php';

$this->_pages['tools/tests/edit_test.php']['title_var'] = 'edit_test';
$this->_pages['tools/tests/edit_test.php']['parent']    = 'tools/tests/index.php';

$this->_pages['tools/tests/preview.php']['title_var'] = 'preview_questions';
$this->_pages['tools/tests/preview.php']['parent']    = 'tools/tests/index.php';
$this->_pages['tools/tests/preview.php']['guide']     = 'instructor/?p=preview.php';

$this->_pages['tools/tests/preview_question.php']['title_var'] = 'preview';
$this->_pages['tools/tests/preview_question.php']['parent']    = 'tools/tests/question_db.php';

$this->_pages['tools/tests/results.php']['title_var'] = 'submissions';
$this->_pages['tools/tests/results.php']['parent']    = 'tools/tests/index.php';

$this->_pages['tools/tests/results_all.php']['guide'] = 'instructor/?p=student_submissions.php';

//$this->_pages['tools/tests/results_all_quest.php']['title_var']  =  _AT('question')." "._AT('statistics');
//$this->_pages['tools/tests/results_all_quest.php']['parent'] = 'tools/tests/index.php';
$this->_pages['tools/tests/results_all_quest.php']['guide']     = 'instructor/?p=test_statistics.php';

$this->_pages['tools/tests/delete_test.php']['title_var'] = 'delete_test';
$this->_pages['tools/tests/delete_test.php']['parent']    = 'tools/tests/index.php';

$this->_pages['tools/view_results.php']['title_var'] = 'view_results';
$this->_pages['tools/view_results.php']['parent']    = 'tools/my_tests.php';
$this->_pages['tools/view_results.php']['children']  = array(); // to create the "back to tests" link

	// test questions
	$this->_pages['tools/tests/create_question_tf.php']['title_var'] = 'create_new_question';
	$this->_pages['tools/tests/create_question_tf.php']['parent']    = 'tools/tests/question_db.php';
	
	$this->_pages['tools/tests/create_question_multi.php']['title_var'] = 'create_new_question';
	$this->_pages['tools/tests/create_question_multi.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/create_question_long.php']['title_var'] = 'create_new_question';
	$this->_pages['tools/tests/create_question_long.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/create_question_likert.php']['title_var'] = 'create_new_question';
	$this->_pages['tools/tests/create_question_likert.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/edit_question_tf.php']['title_var'] = 'edit_question';
	$this->_pages['tools/tests/edit_question_tf.php']['parent']    = 'tools/tests/question_db.php';
	
	$this->_pages['tools/tests/edit_question_multi.php']['title_var'] = 'edit_question';
	$this->_pages['tools/tests/edit_question_multi.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/edit_question_long.php']['title_var'] = 'edit_question';
	$this->_pages['tools/tests/edit_question_long.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/edit_question_likert.php']['title_var'] = 'edit_question';
	$this->_pages['tools/tests/edit_question_likert.php']['parent']    = 'tools/tests/question_db.php';

	$this->_pages['tools/tests/delete_question.php']['title_var'] = 'delete';
	$this->_pages['tools/tests/delete_question.php']['parent'] = 'tools/tests/question_db.php';

$this->_pages['tools/take_test.php']['title_var'] = 'take_test';
$this->_pages['tools/take_test.php']['parent']    = 'tools/my_tests.php';

//student page
$this->_pages['tools/my_tests.php']['title_var'] = 'my_tests';
$this->_pages['tools/my_tests.php']['img']       = 'images/home-tests.gif';

function tests_calendar_source_get_sql($args) {
	// $args[2] = course id
	// $args[3] = start day
	// $args[4] = end day

	$sql = "SELECT title, TO_DAYS(start_date) AS start_days, TO_DAYS(end_date) AS end_days, YEAR(start_date) AS start_year, MONTH(start_date) AS start_month, DAYOFMONTH(start_date) AS start_day, YEAR(end_date) AS end_year, MONTH(end_date) AS end_month, DAYOFMONTH(end_date) AS end_day FROM ".TABLE_PREFIX."tests WHERE course_id=$args[2] AND ((TO_DAYS(start_date) >= TO_DAYS('$args[3]') AND TO_DAYS(end_date) <= TO_DAYS('$args[4]')) OR (TO_DAYS(start_date) < TO_DAYS('$args[3]') AND TO_DAYS(end_date) >= TO_DAYS('$args[3]')) OR (TO_DAYS(start_date) >= TO_DAYS('$args[3]') AND TO_DAYS(end_date) > TO_DAYS('$args[4]'))) ORDER BY start_date, title";
	return $sql;
}

?>