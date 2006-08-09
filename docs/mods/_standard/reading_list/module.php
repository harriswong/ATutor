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
define('AT_PRIV_READING_LIST',       $this->getPrivilege());

$this->addCommand('calendar_source');

/*******
 * if this module is to be made available to students on the Home or Main Navigation.
 */
$_student_tool = 'reading_list/index.php';


/*******
 * instructor Manage section:
 */
$this->_pages['reading_list/index_instructor.php']['title_var'] = 'rl_reading_list';
$this->_pages['reading_list/index_instructor.php']['parent']   = 'tools/index.php';
$this->_pages['reading_list/index_instructor.php']['children'] = array('reading_list/display_resources.php');
$this->_pages['reading_list/index_instructor.php']['guide'] = 'instructor/?p=reading_list.php';


	$this->_pages['reading_list/add_resource_url.php']['title_var'] = 'rl_add_resource_url';
	$this->_pages['reading_list/add_resource_url.php']['parent']    = 'reading_list/display_resources.php';

	$this->_pages['reading_list/add_resource_book.php']['title_var'] = 'rl_add_resource_book';
	$this->_pages['reading_list/add_resource_book.php']['parent']    = 'reading_list/display_resources.php';

	$this->_pages['reading_list/add_resource_handout.php']['title_var'] = 'rl_add_resource_handout';
	$this->_pages['reading_list/add_resource_handout.php']['parent']    = 'reading_list/display_resources.php';

	$this->_pages['reading_list/add_resource_av.php']['title_var'] = 'rl_add_resource_av';
	$this->_pages['reading_list/add_resource_av.php']['parent']    = 'reading_list/display_resources.php';

	$this->_pages['reading_list/add_resource_file.php']['title_var'] = 'rl_add_resource_file';
	$this->_pages['reading_list/add_resource_file.php']['parent']    = 'reading_list/display_resources.php';

	$this->_pages['reading_list/edit_reading_book.php']['title_var'] = 'rl_edit_reading_book';
	$this->_pages['reading_list/edit_reading_book.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/edit_reading_url.php']['title_var'] = 'rl_edit_reading_url';
	$this->_pages['reading_list/edit_reading_url.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/edit_reading_handout.php']['title_var'] = 'rl_edit_reading_handout';
	$this->_pages['reading_list/edit_reading_handout.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/edit_reading_file.php']['title_var'] = 'rl_edit_reading_file';
	$this->_pages['reading_list/edit_reading_file.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/edit_reading_av.php']['title_var'] = 'rl_edit_reading_av';
	$this->_pages['reading_list/edit_reading_av.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/delete_reading.php']['title_var'] = 'rl_delete_reading';
	$this->_pages['reading_list/delete_reading.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/new_reading_book.php']['title_var'] = 'rl_new_reading_book';
	$this->_pages['reading_list/new_reading_book.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/new_reading_url.php']['title_var'] = 'rl_new_reading_url';
	$this->_pages['reading_list/new_reading_url.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/new_reading_av.php']['title_var'] = 'rl_new_reading_av';
	$this->_pages['reading_list/new_reading_av.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/new_reading_handout.php']['title_var'] = 'rl_new_reading_handout';
	$this->_pages['reading_list/new_reading_handout.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/new_reading_file.php']['title_var'] = 'rl_new_reading_file';
	$this->_pages['reading_list/new_reading_file.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/display_resources.php']['title_var'] = 'rl_display_resources';
	$this->_pages['reading_list/display_resources.php']['parent']    = 'reading_list/index_instructor.php';

	$this->_pages['reading_list/display_resource.php']['title_var'] = 'rl_display_resource';
	$this->_pages['reading_list/display_resource.php']['parent']    = 'reading_list/index.php';

	$this->_pages['reading_list/delete_resource.php']['title_var'] = 'rl_delete_resource';
	$this->_pages['reading_list/delete_resource.php']['parent']    = 'reading_list/index_instructor.php';


/*******
 * student page.
 */
$this->_pages['reading_list/index.php']['title_var'] = 'rl_reading_list';
$this->_pages['reading_list/index.php']['img']       = 'images/home-reading_list.gif';

$this->_pages['reading_list/index.php']['children'] = array('reading_list/reading_details.php');

	$this->_pages['reading_list/reading_details.php']['title_var'] = 'rl_display_resources';
	$this->_pages['reading_list/reading_details.php']['parent']    = 'reading_list/index.php';

function reading_list_calendar_source_get_sql($args) {
	// $args[2] = course id
	// $args[3] = start day
	// $args[4] = end day

	$sql = "SELECT R.title, TO_DAYS(L.date_start) AS start_days, TO_DAYS(L.date_end) AS end_days, YEAR(L.date_start) AS start_year, MONTH(L.date_start) AS start_month, DAYOFMONTH(L.date_start) AS start_day, YEAR(L.date_end) AS end_year, MONTH(L.date_end) AS end_month, DAYOFMONTH(L.date_end) AS end_day FROM ".TABLE_PREFIX."reading_list L INNER JOIN ".TABLE_PREFIX."external_resources R USING (resource_id) WHERE L.course_id=$args[2] AND ((TO_DAYS(L.date_start) >= TO_DAYS('$args[3]') AND TO_DAYS(L.date_end) <= TO_DAYS('$args[4]')) OR (TO_DAYS(L.date_start) < TO_DAYS('$args[3]') AND TO_DAYS(L.date_end) >= TO_DAYS('$args[3]')) OR (TO_DAYS(L.date_start) >= TO_DAYS('$args[3]') AND TO_DAYS(L.date_end) > TO_DAYS('$args[4]'))) ORDER BY L.date_start, R.title";

	return $sql;
}
?>