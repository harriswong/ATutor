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

$page = 'tests';
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

if (!authenticate(AT_PRIV_TEST_CREATE, true)) {
	$msg->addError('ACCESS_DENIED');
	header('Location: index.php');
	exit;
}

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: question_cats.php');
	exit;
} else if (isset($_POST['submit'])) {

	$_POST['title'] = trim($_POST['title']);

	if (!empty($_POST['title']) && !isset($_POST['catid'])) {
		$_POST['title'] = $addslashes($_POST['title']);
		$sql	= "INSERT INTO ".TABLE_PREFIX."tests_questions_categories VALUES (0, $_SESSION[course_id], '$_POST[title]')";
		$result = mysql_query($sql, $db);
		$msg->addFeedback('CAT_ADDED');
		header('Location: question_cats.php');
		exit;
	} else if (!empty($_POST['title']) && isset($_POST['catid']))  {
		$_POST['title'] = $addslashes($_POST['title']);
		$sql	= "REPLACE INTO ".TABLE_PREFIX."tests_questions_categories VALUES ($_POST[catid], $_SESSION[course_id], '$_POST[title]')";
		$result = mysql_query($sql, $db);
		$msg->addFeedback('CAT_UPDATE_SUCCESSFUL');
		header('Location: question_cats.php');
		exit;
	} else {
		$msg->addError('CAT_NO_NAME');
	}
}

if (isset($_GET['catid'])) {
	$sql = "SELECT title FROM ".TABLE_PREFIX."tests_questions_categories WHERE category_id=$_GET[catid]";
	$result = mysql_query($sql, $db);
	$row = mysql_fetch_assoc($result);

	$_POST['title'] = $row['title'];
}

require(AT_INCLUDE_PATH.'header.inc.php');

$msg->printErrors();

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<?php 
if (isset($_REQUEST['catid'])) {
	echo '<input type="hidden" value="'.$_REQUEST['catid'].'" name="catid" />';
}
?>
<div class="input-form">
	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="cat"><?php echo _AT('title'); ?></label><br />
		<input type="text" name="title" id="cat" value="<?php echo htmlspecialchars($_POST['title']); ?>" />
	</div>

	<div class="row buttons">
		<input type="submit" value="<?php echo _AT('save'); ?>"   name="submit" accesskey="s" />
		<input type="submit" value="<?php echo _AT('cancel'); ?>" name="cancel" />
	</div>

</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>