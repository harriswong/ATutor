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

if (!defined('AT_INCLUDE_PATH')) { exit; }

global $system_courses, $_base_path, $_pages, $_my_uri;

define('AT_NAV_PUBLIC', 1);
define('AT_NAV_START',  2);
define('AT_NAV_COURSE', 3);
define('AT_NAV_ADMIN',  4);

/*
	4 sections: public, my_start_page, course, admin

*/

$_pages[AT_NAV_PUBLIC] = array('registration.php', 'browse.php',        'login.php',             'password_reminder.php');
$_pages[AT_NAV_START]  = array('users/index.php',  'users/profile.php', 'users/preferences.php', 'users/inbox.php');
$_pages[AT_NAV_COURSE] = array('index.php',        'tools/index.php',   'forum/list.php',        'resources/links/index.php');
$_pages[AT_NAV_ADMIN]  = array('admin/index.php',  'admin/users.php',   'admin/courses.php',     'admin/config_info.php');

/* admin pages */
$_pages['admin/index.php']['title']    = _AT('home');
$_pages['admin/index.php']['parent']   = AT_NAV_ADMIN;

$_pages['admin/users.php']['title']    = _AT('users');
$_pages['admin/users.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/users.php']['children'] = array('admin/admin_email.php');

	$_pages['admin/admin_email.php']['title']    = _AT('admin_email');
	$_pages['admin/admin_email.php']['parent']   = 'admin/users.php';

	$_pages['admin/profile.php']['title']    = _AT('profile');
	$_pages['admin/profile.php']['parent']   = 'admin/users.php';

	$_pages['admin/admin_delete.php']['title']    = _AT('delete_user');
	$_pages['admin/admin_delete.php']['parent']   = 'admin/users.php';

$_pages['admin/courses.php']['title']    = _AT('courses');
$_pages['admin/courses.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/courses.php']['children']   = array('admin/create_course.php', 'admin/backup/index.php', 'admin/forums.php', 'admin/course_categories.php');

	$_pages['admin/instructor_login.php']['title']    = _AT('view');
	$_pages['admin/instructor_login.php']['parent']   = 'admin/courses.php';

	$_pages['admin/edit_course.php']['title']    = _AT('course_properties');
	$_pages['admin/edit_course.php']['parent']   = 'admin/courses.php';

	$_pages['admin/create_course.php']['title']    = _AT('create_course');
	$_pages['admin/create_course.php']['parent']   = 'admin/courses.php';

	$_pages['admin/backup/index.php']['title']    = _AT('backups');
	$_pages['admin/backup/index.php']['parent']   = 'admin/courses.php';
	$_pages['admin/backup/index.php']['children'] = array('admin/backup/create.php');

		$_pages['admin/backup/create.php']['title']    = _AT('create_backup');
		$_pages['admin/backup/create.php']['parent']   = 'admin/backup/index.php';
	
		// this item is a bit iffy:
		$_pages['admin/backup/restore.php']['title']    = _AT('restore');
		$_pages['admin/backup/restore.php']['parent']   = 'admin/backup/index.php';

		$_pages['admin/backup/delete.php']['title']    = _AT('delete');
		$_pages['admin/backup/delete.php']['parent']   = 'admin/backup/index.php';

		$_pages['admin/backup/edit.php']['title']    = _AT('edit');
		$_pages['admin/backup/edit.php']['parent']   = 'admin/backup/index.php';


	$_pages['admin/forums.php']['title']    = _AT('forums');
	$_pages['admin/forums.php']['parent']   = 'admin/courses.php';
	$_pages['admin/forums.php']['children'] = array('admin/forum_add.php');

		$_pages['admin/forum_add.php']['title']    = _AT('create_forum');
		$_pages['admin/forum_add.php']['parent']   = 'admin/forums.php';

		$_pages['admin/forum_edit.php']['title']    = _AT('edit_forum');
		$_pages['admin/forum_edit.php']['parent']   = 'admin/forums.php';

		$_pages['admin/forum_delete.php']['title']    = _AT('delete_forum');
		$_pages['admin/forum_delete.php']['parent']   = 'admin/forums.php';

	$_pages['admin/course_categories.php']['title']    = _AT('cats_categories');
	$_pages['admin/course_categories.php']['parent']   = 'admin/courses.php';
	$_pages['admin/course_categories.php']['children'] = array('admin/create_category.php');

		$_pages['admin/create_category.php']['title']    = _AT('create_category');
		$_pages['admin/create_category.php']['parent']   = 'admin/course_categories.php';

$_pages['admin/config_info.php']['title']    = _AT('configuration');
$_pages['admin/config_info.php']['parent']   = AT_NAV_ADMIN;
$_pages['admin/config_info.php']['children'] = array('admin/language.php', 'admin/themes/index.php', 'admin/error_logging.php');

	$_pages['admin/language.php']['title']    = _AT('language');
	$_pages['admin/language.php']['parent']   = 'admin/config_info.php';

	$_pages['admin/themes/index.php']['title']    = _AT('themes');
	$_pages['admin/themes/index.php']['parent']   = 'admin/config_info.php';
	//$_pages['admin/themes/index.php']['children'] = array('admin/themes/delete.php');

	$_pages['admin/themes/delete.php']['title']    = _AT('delete');
	$_pages['admin/themes/delete.php']['parent']   = 'admin/themes/index.php';

	$_pages['admin/error_logging.php']['title']    = _AT('error_logging');
	$_pages['admin/error_logging.php']['parent']   = 'admin/config_info.php';


/* public pages */
$_pages['registration.php']['title']    = _AT('register');
$_pages['registration.php']['parent']   = AT_NAV_PUBLIC;

$_pages['browse.php']['title']    = _AT('browse_courses');
$_pages['browse.php']['parent']   = AT_NAV_PUBLIC;

$_pages['login.php']['title']    = _AT('login');
$_pages['login.php']['parent']   = AT_NAV_PUBLIC;

$_pages['password_reminder.php']['title']    = _AT('password_reminder');
$_pages['password_reminder.php']['parent']   = AT_NAV_PUBLIC;

$_pages['logout.php']['title']    = _AT('logout');
$_pages['logout.php']['parent']   = AT_NAV_PUBLIC;

/* my start page pages */
$_pages['users/index.php']['title']    = _AT('my_courses');
$_pages['users/index.php']['parent']   = AT_NAV_START;
$_pages['users/index.php']['children'] = array('users/browse.php', 'users/create_course.php');
	
	$_pages['users/browse.php']['title']  = _AT('browse_courses');
	$_pages['users/browse.php']['parent'] = 'users/index.php';
	
	$_pages['users/create_course.php']['title']  = _AT('create_course');
	$_pages['users/create_course.php']['parent'] = 'users/index.php';

$_pages['users/profile.php']['title']    = _AT('profile');
$_pages['users/profile.php']['parent']   = AT_NAV_START;
	
$_pages['users/preferences.php']['title']  = _AT('preferences');
$_pages['users/preferences.php']['parent'] = AT_NAV_START;

$_pages['users/inbox.php']['title']    = _AT('inbox');
$_pages['users/inbox.php']['parent']   = AT_NAV_START;
$_pages['users/inbox.php']['children'] = array('users/send_message.php');

	$_pages['users/send_message.php']['title']  = _AT('send_message');
	$_pages['users/send_message.php']['parent'] = 'users/inbox.php';

/* course pages */
$_pages['index.php']['title']  = _AT('announcements');
$_pages['index.php']['parent'] = AT_NAV_COURSE;

$_pages['tools/index.php']['title']    = _AT('tools');
$_pages['tools/index.php']['parent']   = AT_NAV_COURSE;
//$_pages['tools/index.php']['children'] = array('forum/list.php');

	$_pages['tools/filemanager/index.php']['title']  = _AT('file_manager');
	$_pages['tools/filemanager/index.php']['parent'] = 'tools/index.php';

	$_pages['tools/course_stats.php']['title']  = _AT('statistics');
	$_pages['tools/course_stats.php']['parent'] = 'tools/index.php';

	$_pages['tools/course_properties.php']['title']  = _AT('properties');
	$_pages['tools/course_properties.php']['parent'] = 'tools/index.php';

	$_pages['tools/sitemap/index.php']['title']  = _AT('sitemap');
	$_pages['tools/sitemap/index.php']['parent'] = 'tools/index.php';

	$_pages['tools/course_email.php']['title']  = _AT('course_email');
	$_pages['tools/course_email.php']['parent'] = 'tools/index.php';

	$_pages['tools/edit_header.php']['title']  = _AT('course_copyright2');
	$_pages['tools/edit_header.php']['parent'] = 'tools/index.php';

	$_pages['tools/backup/index.php']['title']  = _AT('backups');
	$_pages['tools/backup/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/backup/index.php']['children'] = array('tools/backup/create.php', 'tools/backup/upload.php');

		$_pages['tools/backup/create.php']['title']  = _AT('create');
		$_pages['tools/backup/create.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/upload.php']['title']  = _AT('upload');
		$_pages['tools/backup/upload.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/restore.php']['title']  = _AT('restore');
		$_pages['tools/backup/restore.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/edit.php']['title']  = _AT('edit');
		$_pages['tools/backup/edit.php']['parent'] = 'tools/backup/index.php';

		$_pages['tools/backup/delete.php']['title']  = _AT('delete');
		$_pages['tools/backup/delete.php']['parent'] = 'tools/backup/index.php';

	$_pages['tools/news/index.php']['title']  = _AT('announcements');
	$_pages['tools/news/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/news/index.php']['children'] = array('editor/add_news.php');

	$_pages['editor/add_news.php']['title']  = _AT('add_announcement');
	$_pages['editor/add_news.php']['parent'] = 'tools/news/index.php';

	$_pages['editor/edit_news.php']['title']  = _AT('edit_announcement');
	$_pages['editor/edit_news.php']['parent'] = 'tools/news/index.php';

	$_pages['editor/delete_news.php']['title']  = _AT('delete_announcement');
	$_pages['editor/delete_news.php']['parent'] = 'tools/news/index.php';

	$_pages['tools/forums/index.php']['title']  = _AT('forums');
	$_pages['tools/forums/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/forums/index.php']['children'] = array('editor/add_forum.php');

	$_pages['editor/add_forum.php']['title']  = _AT('create_forum');
	$_pages['editor/add_forum.php']['parent'] = 'tools/forums/index.php';

	$_pages['editor/delete_forum.php']['title']  = _AT('delete_forum');
	$_pages['editor/delete_forum.php']['parent'] = 'tools/forums/index.php';

	$_pages['editor/edit_forum.php']['title']  = _AT('edit_forum');
	$_pages['editor/edit_forum.php']['parent'] = 'tools/forums/index.php';

	// tests
	$_pages['tools/tests/index.php']['title']  = _AT('tests');
	$_pages['tools/tests/index.php']['parent'] = 'tools/index.php';
	$_pages['tools/tests/index.php']['children'] = array('tools/tests/create_test.php', 'tools/tests/question_db.php', 'tools/tests/question_cats.php');

	$_pages['tools/tests/create_test.php']['title']  = _AT('create_test');
	$_pages['tools/tests/create_test.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/question_db.php']['title']  = _AT('question_database');
	$_pages['tools/tests/question_db.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/question_cats.php']['title']  = _AT('question_categories');
	$_pages['tools/tests/question_cats.php']['parent'] = 'tools/tests/index.php';
	$_pages['tools/tests/question_cats.php']['children'] = array('tools/tests/question_cats_manage.php');

	$_pages['tools/tests/question_cats_manage.php']['title']  = _AT('create_category');
	$_pages['tools/tests/question_cats_manage.php']['parent'] = 'tools/tests/question_cats.php';

	$_pages['tools/tests/edit_test.php']['title']  = _AT('edit_test');
	$_pages['tools/tests/edit_test.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/preview.php']['title']  = _AT('preview');
	$_pages['tools/tests/preview.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/questions.php']['title']  = _AT('questions');
	$_pages['tools/tests/questions.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/results.php']['title']  = _AT('submissions');
	$_pages['tools/tests/results.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/results_all_quest.php']['title']  = _AT('statistics');
	$_pages['tools/tests/results_all_quest.php']['parent'] = 'tools/tests/index.php';

	$_pages['tools/tests/delete_test.php']['title']  = _AT('delete_test');
	$_pages['tools/tests/delete_test.php']['parent'] = 'tools/tests/index.php';


$_pages['forum/list.php']['title']  = _AT('forums');
$_pages['forum/list.php']['parent'] = AT_NAV_COURSE;

	$_pages['forum/index.php']['title']  = 'forum_name';
	$_pages['forum/index.php']['parent'] = 'forum/list.php';

	$_pages['forum/view.php']['title']  = 'thread_name';
	$_pages['forum/view.php']['parent'] = 'forum/index.php';


$_pages['resources/links/index.php']['title']  = _AT('links');
$_pages['resources/links/index.php']['parent'] = AT_NAV_COURSE;

$_pages['editor/edit_content.php']['title']  = _AT('edit_content');
//$_pages['editor/edit_content.php']['parent'] = 'index.php';

/* global pages */
$_pages['about.php']['title']  = _AT('about_atutor');

$_pages['help/index.php']['title']  = _AT('help');
$_pages['help/index.php']['children'] = array('help/accessibility.php', 'help/about_help.php', 'help/contact_admin.php');

	$_pages['help/accessibility.php']['title']  = _AT('accessibility');
	$_pages['help/accessibility.php']['parent'] = 'help/index.php';

	$_pages['help/about_help.php']['title']  = _AT('about_atutor_help');
	$_pages['help/about_help.php']['parent'] = 'help/index.php';

	$_pages['help/contact_admin.php']['title']  = _AT('contact_admin');
	$_pages['help/contact_admin.php']['parent'] = 'help/index.php';

$_pages['search.php']['title']      = _AT('search');

$current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));

function get_main_navigation($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];
	$_top_level_pages = array();

	if (isset($parent_page) && is_numeric($parent_page)) {
		foreach($_pages[$parent_page] as $page) {
			$_top_level_pages[] = array('url' => $_base_path . $page, 'title' => $_pages[$page]['title']);
		}
	} else if (isset($parent_page)) {
		return get_main_navigation($parent_page);
	}

	return $_top_level_pages;
}

function get_current_main_page($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		return $_base_path . $current_page;
	} else if (isset($parent_page)) {
		return get_current_main_page($parent_page);
	}
}

function get_sub_navigation($current_page) {
	global $_pages, $_base_path;

	if (isset($current_page) && is_numeric($current_page)) {
		// reached the top
		return array();
	} else if (isset($_pages[$current_page]['children'])) {
		$_sub_level_pages[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		foreach ($_pages[$current_page]['children'] as $child) {
			$_sub_level_pages[] = array('url' => $_base_path . $child, 'title' => $_pages[$child]['title']);
		}
	} else if (isset($_pages[$current_page]['parent'])) {
		// no children

		$parent_page = $_pages[$current_page]['parent'];
		return get_sub_navigation($parent_page);
	}

	return $_sub_level_pages;
}

function get_current_sub_navigation_page($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		return $_base_path . $current_page;
	} else {
		return $_base_path . $current_page;
	}
}

function get_path($current_page) {
	global $_pages, $_base_path;

	$parent_page = $_pages[$current_page]['parent'];

	if (isset($parent_page) && is_numeric($parent_page)) {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		return $path;
	} else if (isset($parent_page)) {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
		$path = array_merge($path, get_path($parent_page));
	} else {
		$path[] = array('url' => $_base_path . $current_page, 'title' => $_pages[$current_page]['title']);
	}
	
	return $path;
}

$_top_level_pages        = get_main_navigation($current_page);
$_current_top_level_page = get_current_main_page($current_page);
if (empty($_top_level_pages)) {
	if (!$_SESSION['valid_user']) {
		$_top_level_pages = get_main_navigation($_pages[AT_NAV_PUBLIC][0]);
	} else if ($_SESSION['course_id'] < 0) {
		//$_section_title = 'Administration';
		$_top_level_pages = get_main_navigation($_pages[AT_NAV_ADMIN][0]);
	} else if (!$_SESSION['course_id']) {
		//$_section_title = _AT('my_start_page');
		$_top_level_pages = get_main_navigation($_pages[AT_NAV_START][0]);
	} else {
		//$_section_title = $_SESSION['course_title'];
		$_top_level_pages = get_main_navigation($_pages[AT_NAV_COURSE][0]);
	}
}

$_sub_level_pages        = get_sub_navigation($current_page);
$_current_sub_level_page = get_current_sub_navigation_page($current_page);

$_path = get_path($current_page);
unset($_path[0]);
if ($_path[1]['url'] == $_sub_level_pages[0]['url']) {
	$back_to_page = $_path[2];
	//debug('back to : '.$_path[2]['title']);
} else {
	$back_to_page = $_path[1];
	//debug('back to : '.$_path[1]['title']);
}
$_path = array_reverse($_path);


$_page_title = $_pages[$current_page]['title'];

if ($_SESSION['course_id'] > 0) {
	$_section_title = $_SESSION['course_title'];
} else if (!$_SESSION['valid_user']) {
	$_section_title = SITE_NAME;
	if (defined('HOME_URL') && HOME_URL) {
		$_top_level_pages[] = array('url' => HOME_URL, 'title' => _AT('home'));
	}
} else if ($_SESSION['course_id'] < 0) {
	$_section_title = _AT('administration');
} else if (!$_SESSION['course_id']) {
	$_section_title = _AT('my_start_page');
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?php echo $this->tmpl_lang; ?>">
<head>
	<title><?php echo SITE_NAME; ?> : <?php echo $_page_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->tmpl_charset; ?>" />
	<meta name="Generator" content="ATutor - Copyright 2005 by http://atutor.ca" />
	<base href="<?php echo $this->tmpl_content_base_href; ?>" />
	<link rel="shortcut icon" href="<?php echo $this->tmpl_base_path; ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path; ?>print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path.'themes/'.$this->tmpl_theme; ?>/styles.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->tmpl_base_path.'themes/'.$this->tmpl_theme; ?>/forms.css" type="text/css" />
	<?php echo $this->tmpl_rtl_css; ?>
	<style type="text/css"><?php echo $this->tmpl_banner_style; ?></style>
	<?php if ($system_courses[$_SESSION['course_id']]['rss']): ?>
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 2.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-2" />
	<link rel="alternate" type="application/rss+xml" title="ATutor course - RSS 1.0" href="<?php echo $this->tmpl_base_href; ?>get_rss.php?<?php echo $_SESSION['course_id']; ?>-1" />
	<?php endif; ?>
</head>
<body <?php echo $this->tmpl_onload; ?>><div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="<?php echo $this->tmpl_base_path; ?>overlib.js" type="text/javascript"><!-- overLIB (c) Erik Bosrup --></script>
<script language="JavaScript" src="<?php echo $this->tmpl_base_path; ?>jscripts/help.js" type="text/javascript"></script><div >

<!-- section title -->
	<h1 id="section-title"><?php echo $_section_title; ?></h1>

<!-- top help/search/login links -->
<div align="right" id="top-links">
	<a href="<?php echo $this->tmpl_base_path; ?>search.php"><?php echo _AT('search'); ?></a> | <a href="<?php echo $this->tmpl_base_path; ?>help/index.php"><?php echo _AT('help'); ?></a>
<?php if ($_SESSION['valid_user'] && ($_SESSION['course_id'] >= 0)): ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>logout.php"><?php echo _AT('logout'); ?></a><br />
	<form method="post" action="<?php echo $this->tmpl_base_path; ?>bounce.php?p=<?php echo urlencode($this->tmpl_rel_url); ?>" target="_top">
		<label for="jumpmenu" accesskey="j"></label>
			<select name="course" id="jumpmenu" title="<?php echo _AT('jump'); ?>:  ALT-j">							
				<option value="0"><?php echo _AT('my_start_page'); ?></option>
				<optgroup label="<?php echo _AT('courses_below'); ?>">
					<?php foreach ($this->tmpl_nav_courses as $this_course_id => $this_course_title): ?>
						<?php if ($this_course_id == $_SESSION['course_id']): ?>
							<option value="<?php echo $this_course_id; ?>" selected="selected"><?php echo $this_course_title; ?></option>
						<?php else: ?>
							<option value="<?php echo $this_course_id; ?>"><?php echo $this_course_title; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</optgroup>
			</select> <input type="submit" name="jump" value="<?php echo _AT('jump'); ?>" id="jump-button" /><input type="hidden" name="g" value="22" /></form>
<?php elseif ($_SESSION['valid_user']): ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>logout.php"><?php echo _AT('logout'); ?></a><br />
<?php else: ?>
	 | <a href="<?php echo $this->tmpl_base_path; ?>login.php?course=<?php echo $_SESSION['course_id']; ?>"><?php echo _AT('login'); ?></a><br /><br />
<?php endif; ?>
</div>


<!-- back to the current section -->
	<?php if ($_SESSION['valid_user'] && ($_SESSION['course_id'] > 0)): ?>
		<a href="<?php echo $_base_path; ?>bounce.php?course=0" id="my-start-page">Back to My Start Page</a>
	<?php endif; ?>

<!-- the bread crumbs -->
	<div id="breadcrumbs">
		<?php echo $_section_title; ?> : 
		<?php foreach ($_path as $page): ?>
			<a href="<?php echo $page['url']; ?>"><?php echo $page['title']; ?></a> » 
		<?php endforeach; ?> <?php echo $_page_title; ?>
	</div>

<!-- the main navigation. in our case, tabs -->
<table class="tabbed-table" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th id="left-empty-tab">&nbsp;</th>
	<?php foreach ($_top_level_pages as $page): ?>
		<?php if ($page['url'] == $_current_top_level_page): ?>
			<th class="selected"><a href="<?php echo $page['url']; ?>"><div><?php echo $page['title']; ?></div></a></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php else: ?>
			<th class="tab"><a href="<?php echo $page['url']; ?>"><div><?php echo $page['title']; ?></div></a></th>
			<th class="tab-spacer">&nbsp;</th>
		<?php endif; ?>
	<?php endforeach; ?>
	<th id="right-empty-tab">
		<?php if (FALSE && ($_SESSION['course_id'] > 0) && show_pen() && (!$_SESSION['prefs']['PREF_EDIT'])): ?>
			<a href="<?php echo $_my_uri; ?>enable=PREF_EDIT" id="editor-link" class="off"><?php echo _AT('enable_editor'); ?></a>
		<?php elseif (FALSE && ($_SESSION['course_id'] > 0) && show_pen() && ($_SESSION['prefs']['PREF_EDIT'])): ?>
			<a href="<?php echo $_my_uri; ?>disable=PREF_EDIT" id="editor-link" class="on"><?php echo _AT('disable_editor'); ?></a>
		<?php else: ?>
			&nbsp;
		<?php endif; ?>
	</th>
	</tr>
	</table>
</div>
<!-- the sub navigation -->

<?php if ($_sub_level_pages): ?>
	<div id="sub-navigation">
		<?php if (($_SESSION['course_id'] > 0) && show_pen()): ?>
			<div style="float: right; color: black;">
				Instructor tools: <a href="">Add Content</a> | <a href="">Add Test</a> | <a href="">File Manager</a> | <a href="">Properties</a>
			</div>
		<?php endif; ?>

		<?php if (isset($back_to_page)): ?>
			<a href="<?php echo $back_to_page['url']; ?>" id="back-to">Back to <?php echo $back_to_page['title']; ?></a> | 
		<?php endif; ?>

		<?php $num_pages = count($_sub_level_pages); ?>
		<?php for($i=0; $i<$num_pages; $i++): ?>
			<?php if ($_sub_level_pages[$i]['url'] == $_current_sub_level_page): ?>
				<strong><?php echo $_sub_level_pages[$i]['title']; ?></strong>
			<?php else: ?>
				<a href="<?php echo $_sub_level_pages[$i]['url']; ?>"><?php echo $_sub_level_pages[$i]['title']; ?></a>
			<?php endif; ?>
			<?php if ($i < $num_pages-1): ?>
				|
			<?php endif; ?>
		<?php endfor; ?>
	</div>
<?php else: ?>
	<div id="sub-navigation">
		<?php if (($_SESSION['course_id'] > 0) && show_pen()): ?>
			<div style="float: right; color: black;">
				Instructor tools: <a href="">Add Content</a> | <a href="">Add Test</a> | <a href="">File Manager</a> | <a href="">Properties</a>
			</div>
		<?php endif; ?>
		&nbsp;
	</div>
<?php endif; ?>

<!-- the page title -->
	<h2 id="page-title"><?php echo $_page_title; ?></h2>
	<!-- div style="float: right">
	<a href="/svn/atutor/redesign/docs/?cid=123;g=7" accesskey="8" title="Previous: 5.7 Accessibility Features Alt-8"><img src="/svn/atutor/redesign/docs/images/previous.gif" class="menuimage" alt="Previous: 5.7 Accessibility Features" border="0" height="25" width="28"></a>  <a href="/svn/atutor/redesign/docs/?cid=117;g=7" accesskey="9" title="Next: 5.1 Register Alt-9"><img src="/svn/atutor/redesign/docs/images/next.gif" class="menuimage" alt="Next: 5.1 Register" border="0" height="25" width="28"></a>&nbsp;&nbsp;
	</div-->
	<!--
	<script type="text/javascript">
	if (document.getElementById) {
		document.writeln('<div id=\'toctoggle\'>[<a href="javascript:toggleToc()" class="internal">' +
		'<span id="showlink" style="display:none;">show</span>' +
		'<span id="hidelink">hide</span>'
		+ '</a>]</div>');
	}
	</script></div>

	<h3 id="help-title">Help</h3>
	<div id="help">
		<p>this is a help message.</p>
		<p>More help goes here..</p>
		<p>And here</p>
	</div>
	-->
<a name="content"></a>
<?php global $msg; $msg->printAll(); ?>