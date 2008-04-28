<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id: posts.inc.php 4812 2005-06-07 19:52:15Z joel $

if (!defined('AT_INCLUDE_PATH')) { exit; }
global $db;
global $_base_path;
global $savant;

//Number of posts to display
$post_limit = 5;
	
ob_start();

$forum_list = get_group_concat('forums_courses', 'forum_id', "course_id={$_SESSION['course_id']}");
if ($forum_list != 0) {
	$sql = "SELECT subject, post_id, forum_id, member_id FROM ".TABLE_PREFIX."forums_threads WHERE parent_id=0 AND forum_id IN ($forum_list) ORDER BY last_comment DESC LIMIT $post_limit";
	$result = mysql_query($sql, $db);

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			echo '&#176; <a href="' . $_base_path.url_rewrite('forum/view.php?fid=' . $row['forum_id'] . SEP . 'pid=' . $row['post_id']) . '" title="' . $row['subject'] . ': ' . htmlspecialchars(get_display_name($row['member_id'])) . '">' . AT_print($row['subject'], 'forums_threads.subject') . '</a><br />';
		}
	} else {
		echo '<em>'._AT('none_found').'</em>';
	}
} else {
	echo '<em>'._AT('none_found').'</em>';
}

$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();

$savant->assign('title', _AT('forum_posts'));
$savant->display('include/box.tmpl.php');
?>