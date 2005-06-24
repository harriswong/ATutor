<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: new_thread.inc.php 4630 2005-05-12 14:42:46Z jhung $

if (!defined('AT_INCLUDE_PATH')) { exit; }

if (!$_SESSION['valid_user']) {
	$msg->printInfos('LOGIN_TO_POST');
	return;
}

$msg->printErrors();

if ($_POST['submit']) {
	$subject	= $_POST['subject'];
	$body		= $_POST['body'];
	$parent_id	= $_POST['parent_id'];
	$parent_name	= $_POST['parent_name'];
} else if ($_GET['reply'] != '') {
	$subject = $saved_post['subject'];

	if (substr($subject, 0, 3) != 'Re:') {
		$subject = 'Re: '.$subject;
	}
}

?>
<a name="post"></a>
<form action="forum/new_thread.php" method="post" name="form">
<input name="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
<input name="fid" type="hidden" value="<?php echo $fid; ?>" />
<input name="reply" type="hidden" value="<?php echo $_GET['reply']; ?>" />
<input name="page" type="hidden" value="<?php echo $_GET['page']; ?>" />
<input name="parent_name" type="hidden" value="<?php echo urlencode($parent_name); ?>" />

<a name="post"></a>

<div class="input-form">
	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="subject"><?php echo _AT('subject'); ?></label><br />
		<input type="text" maxlength="80" name="subject" size="36" value="<?php echo stripslashes(htmlspecialchars($subject)); ?>" id="subject" />
	</div>

	<div class="row">
		<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="body"><?php echo _AT('body'); ?></label><br />
		<textarea cols="45" name="body" rows="10" id="body"><?php echo $body; ?></textarea>

		<small class="spacer">&middot; <?php echo _AT('forum_links'); ?><br />
		&middot; <?php echo _AT('forum_email_links'); ?><br />
		&middot; <?php echo _AT('forum_html_disabled'); ?></small>
	</div>

	<?php if ($_GET['reply']): ?>
		<div class="row">
			<label for="body"><?php echo _AT('forum_reply_to'); ?></label><br />
			<textarea cols="45" name="replytext" rows="5"><?php echo $saved_post['body']; ?></textarea>
		</div>

	<?php endif; ?>

	<div class="row">	
		<a href="<?php echo $_SERVER['REQUEST_URI']; ?>#jumpcodes" title="<?php echo _AT('jump_codes'); ?>"><img src="images/clr.gif" height="1" width="1" alt="<?php echo _AT('jump_codes'); ?>" border="0" /></a><?php require(AT_INCLUDE_PATH.'html/code_picker.inc.php'); ?>

		<a name="jumpcodes"></a>

	</div>

	<?php if (!$subscribed): ?>
		<div class="row">
			<input type="checkbox" name="subscribe" value="1" id="sub" />
			<label for="sub"><?php echo _AT('thread_subscribe'); ?></label>
		</div>
	<?php else: ?>
		<div class="row">
			<?php echo _AT('thread_already_subscribed'); ?>
		</div>
	<?php endif; ?>

	<div class="row buttons">
		<input name="submit" accesskey="s" type="submit" value=" <?php echo _AT('post'); ?>" />
		<?php if ($new_thread == TRUE) : ?>
			<input name="cancel" type="submit" value="<?php echo _AT('cancel'); ?>" />
		<?php endif; ?>
	</div>
</div>
</form>