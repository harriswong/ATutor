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
// $Id$

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'lib/forums.inc.php');

$fid = intval($_REQUEST['fid']);
$_POST['parent_id'] = intval($_REQUEST['parent_id']);

$_pages['forum/index.php?fid='.$fid]['title']    = get_forum_name($fid);
$_pages['forum/index.php?fid='.$fid]['parent']   = 'forum/list.php';
$_pages['forum/index.php?fid='.$fid]['children'] = array('forum/new_thread.php');

$_pages['forum/new_thread.php']['title_var'] = 'new_thread';
$_pages['forum/new_thread.php']['parent']    = 'forum/index.php?fid='.$fid;

if (!valid_forum_user($fid) || !$_SESSION['enroll']) {
	$msg->addError('FORUM_DENIED');
	require(AT_INCLUDE_PATH.'header.inc.php');
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}


if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index.php?fid='.$fid);
	exit;
} else if (isset($_POST['submit'])) {
	if ($_POST['subject'] == '')  {
		$msg->addError('MSG_SUBJECT_EMPTY');
	}

	if ($_POST['body'] == '') {
		$msg->addError('MSG_BODY_EMPTY');
	}

	if (!$msg->containsErrors()) {
		if ($_POST['replytext'] != '') {
			$_POST['body'] .= "\n\n".'[reply][b]'._AT('in_reply_to').': [/b]'."\n";
			if (strlen($_POST['replytext']) > 200) {
				$_POST['body'] .= substr($_POST['replytext'], 0, 200).'...';
			} else {
				$_POST['body'] .= $_POST['replytext'];
			}
			$num_open_replies = substr_count($_POST['body'], '[reply]');
			$num_close_replies = substr_count($_POST['body'], '[/reply]');
			$num_replies_add = $num_open_replies - $num_close_replies - 1;
			for ($i=0; $i < $num_replies_add; $i++) {
				$_POST['body'] .= '[/reply]';
			}

			$_POST['body'] .= "\n".'[op]forum/view.php?fid='.$_POST['fid'].SEP.'pid='.$_POST['parent_id'].SEP.'page='.$_POST['page'].'#'.$_POST['reply'];
			$_POST['body'] .= '[/op][/reply]';
		}

		/* use this value instead of NOW(), because we want the parent post to have the exact */
		/* same date. and not a second off if that may happen */
		$now = date('Y-m-d H:i:s');

		$sql_subject = $addslashes($_POST['subject']);
		$sql_body    = $addslashes($_POST['body']);

		$sql = "INSERT INTO ".TABLE_PREFIX."forums_threads VALUES(0, $_POST[parent_id], $_SESSION[member_id], $_POST[fid], '".addslashes(get_login($_SESSION['member_id']))."', '$now', 0, '$sql_subject', '$sql_body', '$now', 0, 0)";
		$result = mysql_query($sql, $db);
		$this_id = mysql_insert_id($db);

		/* Increment count for posts in forums table in database */
		$sql = "UPDATE ".TABLE_PREFIX."forums SET num_posts=num_posts+1, last_post='$now' WHERE forum_id=$_POST[fid]";
		$result	 = mysql_query($sql, $db);

		// If there are subscribers to this forum, send them an email notification
		$subscriber_email_list = array(); // list of subscribers array('email', 'full_name')
		$subscriber_list       = '';

		//get a list of users subscribed to this forum
		$sql = "SELECT member_id FROM ".TABLE_PREFIX."forums_subscriptions WHERE forum_id=$fid";
		$result = mysql_query($sql, $db);
		while($row = mysql_fetch_assoc($result)){
			$subscriber_list .= $row['member_id'] . ',';
		}
		if ($_POST['parent_id']) {
			$sql = "SELECT member_id FROM ".TABLE_PREFIX."forums_accessed WHERE post_id=$_POST[parent_id] AND subscribe=1";
			$result = mysql_query($sql, $db);
			while($row = mysql_fetch_assoc($result)){
				$subscriber_list .= $row['member_id'] . ',';
			}
		}
		$subscriber_list = substr($subscriber_list, 0, -1);

		if ($subscriber_list != '') {
			$sql = "SELECT first_name, second_name, last_name, email FROM ".TABLE_PREFIX."members WHERE member_id IN ($subscriber_list) AND member_id <> $_SESSION[member_id]";
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_assoc($result)) {
				$subscriber_email_list[] = array('email'=> $row['email'], 'full_name' => $row['first_name'] . ' '. $row['second_name'] . ' ' . $row['last_name']);
			}
		}

		$sql = "UPDATE ".TABLE_PREFIX."forums_threads SET num_comments=num_comments+1, last_comment='$now' WHERE post_id=$_POST[parent_id]";
		$result = mysql_query($sql, $db);

		if ($subscriber_email_list) {
			require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');

			if ($_POST['parent_name'] == ''){
				$_POST['parent_name'] = $_POST['subject'];
			}
			$_POST['parent_name'] = urldecode($_POST['parent_name']);

			foreach ($subscriber_email_list as $subscriber){
				$mail = new ATutorMailer;
				$mail->AddAddress($subscriber['email'], $subscriber['full_name']);

				$body = _AT('forum_new_submsg', $_SESSION['course_title'],  get_forum_name($_POST['fid']), $_POST['parent_name'],  $_base_href.'bounce.php?course='.$_SESSION['course_id']);
				$body .= "\n----------------------------------------------\n";
				$body .= _AT('posted_by').": ".$_SESSION[login]."\n";
				$body .= $_POST['body']."\n";
				$mail->FromName = $_config['site_name'];
				$mail->From     = $_config['contact_email'];
				$mail->Subject = _AT('thread_notify1');
				$mail->Body    = $body;

				if(!$mail->Send()) {
					$msg->addError('SENDING_ERROR');
				}

				unset($mail);
			}
		}
		if ($_REQUEST['subscribe']) {
			if($_POST['parent_id'] != 0){
				$this_id = $_POST['parent_id'];
				$subject = $_POST['parent_name'];
			} else {
				$subject = $_POST['subject'];
			}
			$sql	= "REPLACE INTO ".TABLE_PREFIX."forums_accessed VALUES ($this_id, $_SESSION[member_id], NOW(), 1)";
			$result = mysql_query($sql, $db);

			$msg->addFeedback(array('THREAD_SUBSCRIBED', $subject));
		} else if ($_POST['parent_id'] == 0) {
			// not subscribe and it's a new thread, mark read:

			$sql	= "REPLACE INTO ".TABLE_PREFIX."forums_accessed VALUES ($this_id, $_SESSION[member_id], NOW(), 0)";
			$result = mysql_query($sql, $db);
		}

		if ($_POST['parent_id'] == 0) {
			$sql = "UPDATE ".TABLE_PREFIX."forums SET num_topics=num_topics+1, last_post='$now' WHERE forum_id=$_POST[fid]";
			$result	 = mysql_query($sql, $db);
			$msg->addFeedback('THREAD_STARTED');
			header('Location: ./index.php?fid='.$fid);
			exit;
		}

		$msg->addFeedback('THREAD_REPLY');
		header('Location: view.php?fid='.$fid.SEP.'pid='.$_POST['parent_id']);
		exit;
	}
}

$onload = 'document.form.subject.focus();';

require(AT_INCLUDE_PATH.'header.inc.php');
	
$parent_id = 0;
$new_thread = TRUE;
require(AT_INCLUDE_PATH.'html/new_thread.inc.php');
require(AT_INCLUDE_PATH.'footer.inc.php');

?>