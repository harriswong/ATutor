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
require (AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_FAQ);


if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index_instructor.php');
	exit;
} 

if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
} else {
	$id = intval($_POST['id']);
}

if (isset($_POST['submit'])) {
	if (trim($_POST['question']) == '') {
		$msg->addError('QUESTION_EMPTY');
	}

	if (trim($_POST['answer']) == '') {
		$msg->addError('ANSWER_EMPTY');
	}

	if (!$msg->containsErrors()) {
		$_POST['question'] = $addslashes($_POST['question']);
		$_POST['answer'] = $addslashes($_POST['answer']);
		$_POST['topic_id'] = intval($_POST['topic_id']);

		$sql = "UPDATE ".TABLE_PREFIX."faq_entries SET question='$_POST[question]', answer='$_POST[answer]', topic_id=$_POST[topic_id] WHERE entry_id=$id";
		$result = mysql_query($sql,$db);

		$msg->addFeedback('QUESTION_UPDATED');
		header('Location: index_instructor.php');
		exit;
	}
}
$onload = 'document.form.topic.focus();';

require(AT_INCLUDE_PATH.'header.inc.php');

if ($id == 0) {
	$msg->printErrors('QUESTION_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

$sql = "SELECT * FROM ".TABLE_PREFIX."faq_entries WHERE entry_id=$id";
$result = mysql_query($sql,$db);
if (!($row = mysql_fetch_assoc($result))) {
	$msg->printErrors('QUESTION_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}


$sql	= "SELECT name, topic_id FROM ".TABLE_PREFIX."faq_topics WHERE course_id=$_SESSION[course_id] ORDER BY name";
$result = mysql_query($sql, $db);
$num_topics = mysql_num_rows($result);
if (!$num_topics) {
	$msg->printErrorS('NO_FAQ_TOPICS');
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<input type="hidden" name="id" value="<?php echo $row['entry_id']; ?>" />

<div class="input-form">
	<div class="row">
		<?php
			$sql	= "SELECT name, topic_id FROM ".TABLE_PREFIX."faq_topics WHERE course_id=$_SESSION[course_id] ORDER BY name";
			$result = mysql_query($sql, $db);
		?>

		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="topic"><?php  echo _AT('topic'); ?></label><br />
		<select name="topic_id" id="topic">
			<?php while ($topic_row = mysql_fetch_assoc($result)): ?>
				<option value="<?php echo $topic_row['topic_id']; ?>"<?php if ($topic_row['topic_id'] == $row['topic_id']) { echo ' selected="selected"'; } ?>><?php echo htmlspecialchars($topic_row['name']); ?></option>
			<?php endwhile; ?>
		</select>
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="question"><?php echo _AT('question'); ?>:</label><br />
		<input type="text" name="question" size="50" id="question" value="<?php if (isset ($_POST['question'])) { echo $stripslashes($_POST['question']); } else { echo $row['question']; } ?>" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="answer"><?php  echo _AT('answer'); ?></label><br />
		<textarea name="answer" cols="45" rows="3" id="answer" style="width:90%;"><?php if (isset ($_POST['answer'])) { echo $stripslashes($_POST['answer']); } else { echo $row['answer']; } ?></textarea>
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" />
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?> " />
	</div>

</div>
</form>
<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>