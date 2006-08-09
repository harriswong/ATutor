<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_ASSIGNMENTS', $this->getPrivilege());

$this->addCommand('calendar_source');

/*******
 * instructor Manage section:
 */
$this->_pages['assignments/index_instructor.php']['title_var'] = 'assignments';
$this->_pages['assignments/index_instructor.php']['parent']   = 'tools/index.php';
$this->_pages['assignments/index_instructor.php']['children'] = array('assignments/add_assignment.php');
$this->_pages['assignments/index_instructor.php']['guide']     = 'instructor/?p=assignments.php';

	$this->_pages['assignments/add_assignment.php']['title_var'] = 'add_assignment';
	$this->_pages['assignments/add_assignment.php']['parent']    = 'assignments/index_instructor.php';

	$this->_pages['assignments/edit_assignment.php']['title_var'] = 'edit';
	$this->_pages['assignments/edit_assignment.php']['parent']    = 'assignments/index_instructor.php';

	$this->_pages['assignments/delete_assignment.php']['title_var'] = 'delete';
	$this->_pages['assignments/delete_assignment.php']['parent']    = 'assignments/index_instructor.php';


function assignments_calendar_source_get_sql($args) {
	// $args[2] = course id
	// $args[3] = start day
	// $args[4] = end day
	$sql = "SELECT title, YEAR(date_due) AS start_year, MONTH(date_due) AS start_month, DAYOFMONTH(date_due) AS start_day FROM ".TABLE_PREFIX."assignments WHERE course_id=$args[2] AND TO_DAYS(date_due) >= TO_DAYS('$args[3]') AND TO_DAYS(date_due) <= TO_DAYS('$args[4]')";

	return $sql;
}
?>