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
// $Id$

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/';
$_section[1][0] = _AT('backup_manager');
$_section[1][1] = 'tools/';

authenticate(AT_PRIV_ADMIN);

require(AT_INCLUDE_PATH.'classes/Backup/Backup.class.php');
require(AT_INCLUDE_PATH.'lib/filemanager.inc.php');

if (isset($_POST['restore'])) {
	if (!isset($_POST['backup_id'])) {
		$msg->addError('DID_NOT_SELECT_A_BACKUP');
	}
	else {
		header('Location: restore.php?backup_id=' . $_POST['backup_id']);
		exit;
	}

} else if (isset($_POST['download'])) {
	if (!isset($_POST['backup_id'])) {
		$msg->addError('DID_NOT_SELECT_A_BACKUP');
	}
	else {
		$Backup =& new Backup($db, $_SESSION['course_id']);
		$Backup->download($_POST['backup_id']);
		exit; // never reached
	}

} else if (isset($_POST['delete'])) {
	if (!isset($_POST['backup_id'])) {
		$msg->addError('DID_NOT_SELECT_A_BACKUP');
	}
	else {
		header('Location: delete.php?backup_id=' . $_POST['backup_id']);
		exit;
	}

} else if (isset($_POST['edit'])) {
	if (!isset($_POST['backup_id'])) {
		$msg->addError('DID_NOT_SELECT_A_BACKUP');
	}
	else {
		header('Location: edit.php?backup_id=' . $_POST['backup_id']);
		exit;
	}

} 

require(AT_INCLUDE_PATH.'header.inc.php');


$Backup =& new Backup($db, $_SESSION['course_id']);
$list = $Backup->getAvailableList();

?>

<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table class="data" summary="" rules="cols">
<thead>
<tr>
	<th><?php echo _AT('file_name');    ?></th>
	<th><?php echo _AT('date_created'); ?></th>
	<th><?php echo _AT('file_size');    ?></th>
	<th><?php echo _AT('description');  ?></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="6"><input type="submit" name="restore" value="<?php echo _AT('restore'); ?>" /> 
				  <input type="submit" name="download" value="<?php echo _AT('download'); ?>" /> 
				  <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" /> 
				  <input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" /></td>
</tr>
</tfoot>
<tbody>
<?php

	if (!$list) {
		?>
	<tr>
		<td class="row1" align="center" colspan="4"><small><?php echo _AT('none_found'); ?></small></td>
	</tr>
	<?php
	} else {
		foreach ($list as $row) {
			echo '<tr onmousedown="document.form[\'b'.$row['backup_id'].'\'].checked = true;">';
			echo '<td class="row1"><label><input type="radio" value="'.$row['backup_id'].'" name="backup_id" id="b'.$row['backup_id'].'" />';
			echo '<small>'.$row['file_name'].'</small></label></td>';
			echo '<td class="row1"><small>'.AT_date(_AT('filemanager_date_format'), $row['date_timestamp'], AT_DATE_UNIX_TIMESTAMP).'</small></td>';
			echo '<td class="row1" align="right"><small>'.get_human_size($row['file_size']).'</small></td>';
			echo '<td class="row1"><small>'.$row['description'].'</small></td>';
			echo '</tr>';
		}
?>
	<?php } ?>
</tbody>
</table>
</form>

<?php require (AT_INCLUDE_PATH.'footer.inc.php');  ?>
