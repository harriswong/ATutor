<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto				*/
/* http://atutor.ca															*/
/*																			*/
/* This program is free software. You can redistribute it and/or			*/
/* modify it under the terms of the GNU General Public License				*/
/* as published by the Free Software Foundation.							*/
/****************************************************************************/

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require_once(AT_INCLUDE_PATH.'classes/Message/Message.class.php');

global $savant;
$msg =& new Message($savant);

authenticate(AT_PRIV_FORUMS);

if ($_POST['cancel']) {
	$msg->addFeedback('CANCELLED');
	Header('Location: ../forum/list.php');
	exit;

}
if ($_POST['delete_forum']) {
	$_POST['fid'] = intval($_POST['fid']);

	$sql	= "SELECT post_id FROM ".TABLE_PREFIX."forums_threads WHERE forum_id=$_POST[fid] AND course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);
	while ($row = mysql_fetch_array($result)) {
		$sql	 = "DELETE FROM ".TABLE_PREFIX."forums_accessed WHERE post_id=$row[post_id]";
		$result2 = mysql_query($sql, $db);

		$sql	 = "DELETE FROM ".TABLE_PREFIX."forums_subscriptions WHERE post_id=$row[post_id]";
		$result2 = mysql_query($sql, $db);
	}

	$sql = "DELETE FROM ".TABLE_PREFIX."forums_threads WHERE forum_id=$_POST[fid] AND course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);

	$sql = "DELETE FROM ".TABLE_PREFIX."forums WHERE forum_id=$_POST[fid] AND course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);

	$sql = "OPTIMIZE TABLE ".TABLE_PREFIX."forums_threads";
	$result = mysql_query($sql, $db);

	$msg->addFeedback('FORUM_DELETED');
	Header('Location: ../forum/list.php');
	exit;
}

$_section[0][0] = _AT('discussions');
$_section[0][1] = 'discussions/';
$_section[1][0] = _AT('forums');
$_section[1][1] = 'forum/list.php';
$_section[2][0] = _AT('delete_forum');

require(AT_INCLUDE_PATH.'header.inc.php');

	echo '<h2>';
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
		echo '<img src="images/icons/default/square-large-discussions.gif" width="42" height="38" border="0" alt="" class="menuimage" /> ';
	}
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
		echo '<a href="discussions/">'._AT('discussions').'</a>';
	}
	echo '</h2>';


echo'<h3>';
if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
	echo '<img src="images/icons/default/forum-large.gif" width="42" height="38" border="0" alt="" class="menuimageh3" />';
}
echo _AT('delete_forum').'</h3>';

	$_GET['fid'] = intval($_GET['fid']); 

	$sql = "SELECT * FROM ".TABLE_PREFIX."forums WHERE forum_id=$_GET[fid] AND course_id=$_SESSION[course_id]";

	$result = mysql_query($sql,$db);
	if (mysql_num_rows($result) == 0) {
		$msg->addError('FORUM_NOT_ADDED');
	} else {
		$row = mysql_fetch_assoc($result);
?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="delete_forum" value="true">
		<input type="hidden" name="fid" value="<?php echo $_GET['fid']; ?>">
		<?php
			
		$warnings = array('DELETE_FORUM', AT_print($row['title'], 'forums.title'));
		$msg->printWarnings($warnings);

		?>

		<br />
		<input type="submit" name="submit" value="<?php echo _AT('yes_delete'); ?>" class="button"> -
		<input type="submit" name="cancel" value="<?php echo _AT('no_cancel'); ?>" class="button">
		</form>
		<?php
	}
require(AT_INCLUDE_PATH.'footer.inc.php');

?>