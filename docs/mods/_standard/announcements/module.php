<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

define('AT_PRIV_ANNOUNCEMENTS', $this->getPrivilege());

$this->addCommand('calendar_source');

$this->_pages['tools/news/index.php']['title_var'] = 'announcements';
$this->_pages['tools/news/index.php']['guide']     = 'instructor/?p=announcements.php';
$this->_pages['tools/news/index.php']['parent']    = 'tools/index.php';
$this->_pages['tools/news/index.php']['children']  = array('editor/add_news.php');

	$this->_pages['editor/add_news.php']['title_var']  = 'add_announcement';
	$this->_pages['editor/add_news.php']['parent'] = 'tools/news/index.php';

	$this->_pages['editor/edit_news.php']['title_var']  = 'edit_announcement';
	$this->_pages['editor/edit_news.php']['parent'] = 'tools/news/index.php';

	$this->_pages['editor/delete_news.php']['title_var']  = 'delete_announcement';
	$this->_pages['editor/delete_news.php']['parent'] = 'tools/news/index.php';

function announcements_calendar_source_get_sql($args) {
	// $args[2] = course id
	// $args[3] = start day
	// $args[4] = end day
	$sql = "SELECT title, MONTH(date) AS `start_month`, DAYOFMONTH(date) AS `start_day` FROM ".TABLE_PREFIX."news WHERE course_id=$args[2] AND TO_DAYS(date) >= TO_DAYS('$args[3]') AND TO_DAYS(date) <=  TO_DAYS('$args[4]') ORDER BY date, title";

	return $sql;
}
?>