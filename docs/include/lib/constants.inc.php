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
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }

/***************
 * constants
 ******/


define('AT_DEFAULT_PREFS', 'a:4:{s:14:"PREF_NUMBERING";i:1;s:10:"PREF_THEME";s:7:"default";s:18:"PREF_JUMP_REDIRECT";i:1;s:15:"PREF_FORM_FOCUS";i:1;}');

 $_modules = array('sitemap.php', 'export.php', 'chat/index.php', 'links/index.php', 'tile.php', 'glossary/index.php', 'my_stats.php', 'tools/my_tests.php', 'forum/list.php' ,'polls/index.php','acollab/bounce.php', 'directory.php');

if (defined('AT_ENABLE_SCO') && AT_ENABLE_SCO) {
	$_modules[] = 'packages/index.php';
}

/* how many related topics can be listed */
define('NUM_RELATED_TOPICS', 5);

/* taking a test an unlimited # of times */
define('AT_TESTS_TAKE_UNLIMITED', 0);

/* how many announcements listed */
define('NUM_ANNOUNCEMENTS', 10);

/* how long cache objects can persist	*/
/* in seconds. should be low initially, but doesn't really matter. */
/* in practice should be 0 (ie. INF)    */
define('CACHE_TIME_OUT',	60);

/* member status field options */
define('AT_STATUS_DISABLED',    0);
define('AT_STATUS_UNCONFIRMED', 1);
define('AT_STATUS_STUDENT',     2);
define('AT_STATUS_INSTRUCTOR',  3);

/* user permissions */

/* $_privs[priv number] = array(String name, Boolean pen, Boolean tools) */
define('AT_PRIV_RETURN',		true);
define('AT_PRIV_NONE',			0);
define('AT_PRIV_ADMIN',			1);

$_privs[2]		= array('name' => 'AT_PRIV_CONTENT',		'pen' => true,	'tools' => false);
$_privs[4]		= array('name' => 'AT_PRIV_GLOSSARY',		'pen' => true,	'tools' => false);
$_privs[8]		= array('name' => 'AT_PRIV_TEST_CREATE',	'pen' => true,	'tools' => true);
$_privs[16]		= array('name' => 'AT_PRIV_TEST_MARK',		'pen' => false, 'tools' => true);
$_privs[32]		= array('name' => 'AT_PRIV_FILES',			'pen' => false, 'tools' => true);
$_privs[64]		= array('name' => 'AT_PRIV_LINKS',			'pen' => true,	'tools' => false);
$_privs[128]	= array('name' => 'AT_PRIV_FORUMS',			'pen' => true,	'tools' => false);
$_privs[256]	= array('name' => 'AT_PRIV_STYLES',			'pen' => false, 'tools' => true);
$_privs[512]	= array('name' => 'AT_PRIV_ENROLLMENT',		'pen' => false,	'tools' => false);
$_privs[1024]	= array('name' => 'AT_PRIV_COURSE_EMAIL',	'pen' => false,	'tools' => false);
$_privs[2048]	= array('name' => 'AT_PRIV_ANNOUNCEMENTS',	'pen' => true,	'tools' => false);
$_privs[16384]	= array('name' => 'AT_PRIV_POLLS',	        'pen' => false,	'tools' => false);

if (defined('AC_PATH') && AC_PATH) {
	$_privs[4096]= array('name' => 'AT_PRIV_AC_CREATE',		'pen' => false,	'tools' => true);
	$_privs[8192]= array('name' => 'AT_PRIV_AC_ACCESS_ALL',	'pen' => false,	'tools' => true);
}

/* admin privs: */
define('AT_ADMIN_PRIV_NONE',        0);
define('AT_ADMIN_PRIV_ADMIN',       1);
define('AT_ADMIN_PRIV_USERS',       2);
define('AT_ADMIN_PRIV_COURSES',     4);
define('AT_ADMIN_PRIV_BACKUPS',     8);
define('AT_ADMIN_PRIV_FORUMS',     16);
define('AT_ADMIN_PRIV_CATEGORIES', 32);
define('AT_ADMIN_PRIV_LANGUAGES',  64);
define('AT_ADMIN_PRIV_THEMES',    128);

/* admin log (type of operations) */
define('AT_ADMIN_LOG_UPDATE',  1);
define('AT_ADMIN_LOG_DELETE',  2);
define('AT_ADMIN_LOG_INSERT',  3);
define('AT_ADMIN_LOG_REPLACE', 4);
define('AT_ADMIN_LOG_OTHER',   5); //for non-db operations

if (strpos(@ini_get('arg_separator.input'), ';') !== false) {
	define('SEP', ';');
} else {
	define('SEP', '&');
}

/* the URL to the AChecker server of choice. must include trailing slash. */
define('AT_ACHECKER_URL', 'http://checker.atrc.utoronto.ca/servlet/');

/* the URL to the WSDL of the TILE repository of choice. */
define('AT_TILE_WSDL', 'http://tile.atutor.ca/tile/services/search?wsdl');

/* the URL to the content package export servlet of the TILE repository of choice. */
define('AT_TILE_EXPORT', 'http://tile.atutor.ca/tile/servlet/export');

/* the URL to the content importing servlet of the TILE repository. */
define('AT_TILE_IMPORT', 'http://tile.atutor.ca/tile/servlet/put');

define('AT_TILE_PREVIEW', 'http://tile.atutor.ca/tile/servlet/view?view=item&');

if (!isset($_SERVER['REQUEST_URI'])) {
	$REQUEST_URI = $_SERVER['SCRIPT_NAME'];
	if ($_SERVER['QUERY_STRING'] != '') {
		$REQUEST_URI .= '?'.$_SERVER['QUERY_STRING'];
	}
	$_SERVER['REQUEST_URI'] = $REQUEST_URI;
}

/* get the base url	*/
if (isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') {
	$server_protocol = 'https://';
} else {
	$server_protocol = 'http://';
}

$dir_deep	 = substr_count(AT_INCLUDE_PATH, '..');
$url_parts	 = explode('/', $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$_base_href	 = array_slice($url_parts, 0, count($url_parts) - $dir_deep-1);
$_base_href	 = $server_protocol . implode('/', $_base_href).'/';
$_base_path  = substr($_base_href, strlen($server_protocol . $_SERVER['HTTP_HOST']));

/* relative uri */
$_rel_url = '/'.implode('/', array_slice($url_parts, count($url_parts) - $dir_deep-1));


/* for the SCORM 1.2 RTE: */
define('AT_PACKAGE_TYPES', 'scorm-1.2');
if (defined('AT_ENABLE_SCO') && AT_ENABLE_SCO) {
	define('AT_PACKAGE_URL_BASE', $_base_href . 'sco/'); 
}

/* where the gudes are (could be a full URL if needed): */
define('AT_GUIDES_PATH', $_base_path . 'documentation/');

define('AT_BACKUP_DIR', AT_CONTENT_DIR . 'backups/'); // where the backups get stored

define('HELP',			0);
define('VERSION',		'1.5');
define('ONLINE_UPDATE', 3); /* update the user expiry every 3 min */

/* valid date format_types:						*/
/* @see ./include/lib/date_functions.inc.php	*/
define('AT_DATE_MYSQL_DATETIME',		1); /* YYYY-MM-DD HH:MM:SS	*/
define('AT_DATE_MYSQL_TIMESTAMP_14',	2); /* YYYYMMDDHHMMSS		*/
define('AT_DATE_UNIX_TIMESTAMP',		3); /* seconds since epoch	*/
define('AT_DATE_INDEX_VALUE',			4); /* index to the date arrays */

define('AT_ROLE_STUDENT',				0);
define('AT_ROLE_INSTRUCTOR',			1);

define('AT_KBYTE_SIZE',		         1024);

define('AT_COURSESIZE_UNLIMITED',	   -1); 
define('AT_COURSESIZE_DEFAULT',		   -2);  /* can be changed in config.inc.php */
define('AT_FILESIZE_DEFAULT',		   -3);  /* this too */
define('AT_FILESIZE_SYSTEM_MAX',	   -4);
$editable_file_types = array('txt', 'html', 'htm', 'xml', 'css', 'asc', 'csv');

/* how many poll choices are available: */
define('AT_NUM_POLL_CHOICES',   7);

/* ways of releasing a test */
define('AT_RELEASE_NEVER',		   0); // do not release 
define('AT_RELEASE_IMMEDIATE',	   1); // release after submitted
define('AT_RELEASE_MARKED',		   2); // release after all q's marked

/* types of test questions */
define('AT_TESTS_MC',				1); // multiple choice
define('AT_TESTS_TF',				2); // true/false
define('AT_TESTS_LONG',				3); // long answer
define('AT_TESTS_LIKERT',			4); // likert

/* QPROP = question property: */
define('AT_TESTS_QPROP_WORD',       1);
define('AT_TESTS_QPROP_SENTENCE',   2);
define('AT_TESTS_QPROP_PARAGRAPH',  3);
define('AT_TESTS_QPROP_PAGE',       4);
define('AT_TESTS_QPROP_ALIGN_VERT',	5); // align question options vertically
define('AT_TESTS_QPROP_ALIGN_HORT',	6); // align question options horizontally

/* enrollment types for $_SESSION['enroll'] */
define('AT_ENROLL_NO',			0);
define('AT_ENROLL_YES',			1);
define('AT_ENROLL_ALUMNUS',		2);

/* names of the include files for the side menu drop downs */
$_stacks = array('menu_menu', 'related_topics', 'users_online', 'glossary', 'search', 'poll', 'posts');

/* control how user inputs get formatted on output: */
/* note: v131 not all formatting options are available on each section. */

define('AT_FORMAT_NONE',	      0); /* LEQ to ~AT_FORMAT_ALL */
define('AT_FORMAT_EMOTICONS',     1);
define('AT_FORMAT_LINKS',         2);
define('AT_FORMAT_IMAGES',        4);
define('AT_FORMAT_HTML',          8);
define('AT_FORMAT_GLOSSARY',     16);
define('AT_FORMAT_ATCODES',      32);
define('AT_FORMAT_CONTENT_DIR', 64); /* remove CONTENT_DIR */
define('AT_FORMAT_QUOTES',      128); /* remove double quotes (does this get used?) */
define('AT_FORMAT_ALL',       AT_FORMAT_EMOTICONS 
							   + AT_FORMAT_LINKS 
						       + AT_FORMAT_IMAGES 
						       + AT_FORMAT_HTML 
						       + AT_FORMAT_GLOSSARY 
							   + AT_FORMAT_ATCODES
							   + AT_FORMAT_CONTENT_DIR);

$_field_formatting = array();

$_field_formatting['content.keywords']			= AT_FORMAT_NONE;
$_field_formatting['content.title']				= AT_FORMAT_ALL & ~AT_FORMAT_HTML | AT_FORMAT_QUOTES;
$_field_formatting['content.text']				= AT_FORMAT_ALL;

$_field_formatting['course_cats.cat_name']		= AT_FORMAT_NONE;

$_field_formatting['courses.*']				    = AT_FORMAT_ALL & ~AT_FORMAT_EMOTICONS & ~AT_FORMAT_ATCODES & ~AT_FORMAT_LINKS & ~AT_FORMAT_IMAGES;

$_field_formatting['forums.title']				= AT_FORMAT_NONE;
$_field_formatting['forums.description']		= AT_FORMAT_ALL;

$_field_formatting['forums_threads.subject']	= AT_FORMAT_ALL & ~AT_FORMAT_HTML;
$_field_formatting['forums_threads.body']		= AT_FORMAT_ALL & ~AT_FORMAT_HTML;

$_field_formatting['glossary.word']				= AT_FORMAT_NONE;
$_field_formatting['glossary.definition']		= AT_FORMAT_ALL & ~AT_FORMAT_HTML;

$_field_formatting['instructor_approvals.notes']= AT_FORMAT_NONE;

$_field_formatting['members.*']                 = AT_FORMAT_NONE; /* wildcards are okay */

$_field_formatting['messages.subject']			= AT_FORMAT_EMOTICONS + AT_FORMAT_LINKS + AT_FORMAT_IMAGES;
$_field_formatting['messages.body']				= AT_FORMAT_EMOTICONS + AT_FORMAT_LINKS + AT_FORMAT_IMAGES + AT_FORMAT_ATCODES;

$_field_formatting['news.title']				= AT_FORMAT_EMOTICONS | AT_FORMAT_LINKS & ~AT_FORMAT_HTML;
$_field_formatting['news.body']					= AT_FORMAT_ALL;

$_field_formatting['resource_categories.CatName']= AT_FORMAT_NONE;
$_field_formatting['resource_categories.Url']	= AT_FORMAT_NONE;
$_field_formatting['resource_links.LinkName']	= AT_FORMAT_NONE;
$_field_formatting['resource_links.Description']= AT_FORMAT_NONE;
$_field_formatting['resource_links.SubmitName']= AT_FORMAT_NONE;

$_field_formatting['tests.title']				= AT_FORMAT_ALL;
$_field_formatting['tests.instructions']		= AT_FORMAT_ALL;

$_field_formatting['themes.title']				= AT_FORMAT_NONE;

$_field_formatting['tests_answers.answer']		= AT_FORMAT_ALL;
$_field_formatting['tests_answers.notes']		= AT_FORMAT_ALL;
$_field_formatting['tests_questions.question']	= AT_FORMAT_ALL;

$_field_formatting['polls.*']            = AT_FORMAT_ALL;


	if (isset($_GET['cid'])) {
		$cid = intval($_GET['cid']);
	} else if (isset($_POST['cid'])) {
		$cid = intval($_POST['cid']);
	}

?>
