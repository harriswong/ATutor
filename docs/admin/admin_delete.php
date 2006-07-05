<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
/* linked from admin/users.php                                  */
/* deletes a user from the system.                              */
/****************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_USERS);

$id = intval($_GET['id']);

if (isset($_POST['submit_yes'])) {
	$id = intval($_POST['id']);

	$sql	= "DELETE FROM ".TABLE_PREFIX."course_enrollment WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'course_enrollment', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."forums_accessed WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'forums_accessed', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."forums_subscriptions WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'forums_subscriptions', mysql_affected_rows($db), $sql);

	$sql	= "UPDATE ".TABLE_PREFIX."master_list SET member_id=0 WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'master_list', mysql_affected_rows($db), $sql);


	/****/
	/* delete forum threads block: */
		/* delete the thread replies: */
		$sql	= "SELECT COUNT(*) AS cnt, parent_id, forum_id FROM ".TABLE_PREFIX."forums_threads WHERE member_id=$id AND parent_id<>0 GROUP BY parent_id";
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_assoc($result)) {
			/* update the forum posts counter */
			$sql = "UPDATE ".TABLE_PREFIX."forums SET num_posts=num_posts - $row[cnt] WHERE forum_id=$row[forum_id]";
			mysql_query($sql, $db);
			write_to_log(AT_ADMIN_LOG_UPDATE, 'forums', mysql_affected_rows($db), $sql);
			
			/* update the topics reply counter */
			$sql = "UPDATE ".TABLE_PREFIX."forums_threads SET num_comments=num_comments-$row[cnt] WHERE post_id=$row[parent_id]";
			mysql_query($sql, $db);
			write_to_log(AT_ADMIN_LOG_UPDATE, 'forums_threads', mysql_affected_rows($db), $sql);
		}

		/* delete threads this member started: */
		$sql	= "SELECT post_id, forum_id, num_comments FROM ".TABLE_PREFIX."forums_threads WHERE member_id=$id AND parent_id=0";
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_assoc($result)) {
			/* update the forum posts and topics counters */
			$num_posts = $row['num_comments'] + 1;
			$sql = "UPDATE ".TABLE_PREFIX."forums SET num_topics=num_topics-1, num_posts=num_posts - $num_posts WHERE forum_id=$row[forum_id]";
			mysql_query($sql, $db);
			write_to_log(AT_ADMIN_LOG_UPDATE, 'forums', mysql_affected_rows($db), $sql);

			/* delete the replies */
			$sql = "DELETE FROM ".TABLE_PREFIX."forums_threads WHERE parent_id=$row[post_id]";
			mysql_query($sql, $db);
			write_to_log(AT_ADMIN_LOG_DELETE, 'forums_threads', mysql_affected_rows($db), $sql);
		}
		/* delete the actual threads */
		$sql	= "DELETE FROM ".TABLE_PREFIX."forums_threads WHERE member_id=$id";
		mysql_query($sql, $db);
		write_to_log(AT_ADMIN_LOG_DELETE, 'forums_threads', mysql_affected_rows($db), $sql);

	/* end delete forum threads block. */
	/****/

	$sql	= "DELETE FROM ".TABLE_PREFIX."instructor_approvals WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'instructor_approvals', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."messages WHERE from_member_id=$id OR to_member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'messages', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."polls_members WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'polls_members', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."tests_answers WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'tests_answers', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."tests_results WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'tests_results', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."users_online WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'users_online', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."members WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'members', mysql_affected_rows($db), $sql);

	$sql	= "DELETE FROM ".TABLE_PREFIX."member_track WHERE member_id=$id";
	mysql_query($sql, $db);
	write_to_log(AT_ADMIN_LOG_DELETE, 'member_track', mysql_affected_rows($db), $sql);

	$msg->addFeedback('USER_DELETED');
	if (isset($_POST['ml']) && $_REQUEST['ml']) {
		header('Location: '.$_base_href.'admin/master_list.php');
	} else {
		header('Location: '.$_base_href.'admin/users.php');
	}
	exit;
} else if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	if (isset($_POST['ml']) && $_REQUEST['ml']) {
		header('Location: '.$_base_href.'admin/master_list.php');
	} else {
		header('Location: '.$_base_href.'admin/users.php');
	}
	exit;
}


$sql	= "SELECT member_id, login FROM ".TABLE_PREFIX."members WHERE member_id=$id";
$result = mysql_query($sql, $db);
if (!($row_log = mysql_fetch_assoc($result))) {
	require(AT_INCLUDE_PATH.'header.inc.php'); 
	echo _AT('no_user_found');
} else {
	$sql	= "SELECT course_id FROM ".TABLE_PREFIX."courses WHERE member_id=$id";
	$result = mysql_query($sql, $db);
	if (($row2 = mysql_fetch_assoc($result))) {
		$msg->addError('NODELETE_USER');

		header('Location: '.$_base_href.'admin/users.php');
		exit;

	} else {
		require(AT_INCLUDE_PATH.'header.inc.php'); 
		$hidden_vars['id'] = $id;
		$hidden_vars['ml'] = intval($_REQUEST['ml']);
		$confirm = array('DELETE_USER', AT_print($row_log['login'], 'members.login'));
		$msg->addConfirm($confirm, $hidden_vars);
		$msg->printConfirm();
	}
}

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>