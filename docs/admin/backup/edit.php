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

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }

$page = 'backups';
$_user_location = 'admin';

require(AT_INCLUDE_PATH.'classes/Backup/Backup.class.php');

$_SESSION['done'] = 0;
session_write_close();

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 

$Backup =& new Backup($db, $_REQUEST['course_id']);
$backup_row = $Backup->getRow($_REQUEST['backup_id']);

if (isset($_POST['edit'])) {
	$Backup->edit($_POST['backup_id'], $_POST['new_description']);
	$msg->addFeedback('BACKUP_EDIT');
	header('Location: index.php');
	exit;
} 

//check for errors

require(AT_INCLUDE_PATH.'header.inc.php');

?>

<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onsubmit="">
<input type="hidden" name="backup_id" value="<?php echo $_GET['backup_id']; ?>" />
<input type="hidden" name="course_id" value="<?php echo $_GET['course_id']; ?>" />

<div class="input-form">
	<div class="row">
		<?php echo _AT('file_name'); ?><br />
		<?php echo _AT('edit_backup', $backup_row['file_name']); ?>
	</div>

	<div class="row">
		<label for="desc">Description</label><br />
		<textarea cols="30" rows="2" name="new_description"><?php echo $backup_row['description']; ?></textarea>
	</div>

	<div class="row buttons">
		<input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" /> <input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>
<?php require (AT_INCLUDE_PATH.'footer.inc.php');  ?>