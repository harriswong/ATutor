<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
/* Adaptive Technology Resource Centre / University of Toronto				*/
/* http://atutor.ca															*/
/*																			*/
/* This program is free software. You can redistribute it and/or			*/
/* modify it under the terms of the GNU General Public License				*/
/* as published by the Free Software Foundation.							*/
/****************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_FORUMS);

require (AT_INCLUDE_PATH.'lib/forums.inc.php');

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.$_base_href.'tools/forums/index.php');
	exit;
} else if (isset($_POST['edit_forum'])) {
	$_POST['fid'] = intval($_POST['fid']);

	// check if this forum is shared:
	// (if this forum is shared, then we do not want to edit it.)

	if ($_POST['title'] == '') {
		$msg->addError('TITLE_EMPTY');
	}

	if (!$msg->containsErrors()) {
		if (!is_shared_forum($_POST['fid'])) {
			edit_forum($_POST);
			$msg->addFeedback('FORUM_UPDATED');
		} else {
			$msg->addError('FORUM_NO_EDIT_SHARE');
		}
		
		header('Location: '.$_base_href.'tools/forums/index.php');
		exit;
	}
}
$_section[0][0] = _AT('discussions');
$_section[0][1] = 'discussions/';
$_section[1][0] = _AT('forums');
$_section[1][1] = 'forum/list.php';
$_section[2][0] = _AT('edit_forum');

$onload = 'onLoad="document.form.title.focus()"';
require(AT_INCLUDE_PATH.'header.inc.php');

$fid = intval($_REQUEST['fid']);

if (!isset($_POST['submit'])) {
	$row = get_forum($fid, $_SESSION['course_id']);
	if (!is_array($row)) {
		$msg->addError('FORUM_NOT_FOUND');
		$msg->printALL();
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}
} else {
	$row['description'] = $_POST['body'];
}

$msg->printErrors();

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<input type="hidden" name="edit_forum" value="true">
<input type="hidden" name="fid" value="<?php echo $fid; ?>">

<div class="input-form">
	<div class="row">
		<label for="title"><?php echo _AT('title'); ?></label><br />
		<input type="text" name="title" size="50" id="title" value="<?php echo htmlspecialchars(stripslashes($row['title'])); ?>">
	</div>
	<div class="row">
		<label for="body"><?php echo _AT('description'); ?></label><br />
		<textarea name="body" cols="45" rows="10" id="body" wrap="wrap"><?php echo $row['description']; ?></textarea>
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" /> 
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>