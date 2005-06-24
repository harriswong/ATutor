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
// $Id$
$_user_location	= 'public';

define('AT_INCLUDE_PATH', '../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
	
$_GET['view'] = intval($_GET['view']);

if ($_GET['view']) {
	$result = mysql_query("UPDATE ".TABLE_PREFIX."messages SET new=0 WHERE to_member_id=$_SESSION[member_id] AND message_id=$_GET[view]",$db);
}

require(AT_INCLUDE_PATH.'header.inc.php');

if (!$_SESSION['valid_user']) {
	$msg->printInfos('INVALID_USER');
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

if ($_GET['delete']) {
	$_GET['delete'] = intval($_GET['delete']);

	if($result = mysql_query("DELETE FROM ".TABLE_PREFIX."messages WHERE to_member_id=$_SESSION[member_id] AND message_id=$_GET[delete]",$db)){
		$msg->addFeedback('MSG_DELETED');
	}

	$_GET['delete'] = '';
}

$msg->printFeedbacks();


if (isset($_GET['s'])) {
	$msg->printFeedbacks('MSG_SENT');
}

if (isset($_GET['view'])) {
	$sql	= "SELECT * FROM ".TABLE_PREFIX."messages WHERE message_id=$_GET[view] AND to_member_id=$_SESSION[member_id]";
	$result = mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {
?>
	<table align="center" border="0" cellpadding="2" cellspacing="1" width="98%" class="data static" summary="">
	<thead>
	<tr>
		<th><?php echo AT_print($row['subject'], 'messages.subject'); ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php
			$from = get_login($row['from_member_id']);

			echo '<span class="bigspacer">'._AT('from').' <strong>'.AT_print($from, 'members.logins').'</strong> '._AT('posted_on').' ';
			echo AT_date(_AT('inbox_date_format'), $row['date_sent'], AT_DATE_MYSQL_DATETIME);
			echo '</span>';
			echo '<p>';
			echo AT_print($row['body'], 'messages.body');
			echo '</p>';

		?></td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td>
			<form method="get" action="inbox/send_message.php">
			<input type="hidden" name="reply" value="<?php echo $_GET['view']; ?>" />
			<input type="submit" name="submit" value="<?php echo _AT('reply'); ?>" accesskey="r" />
		</form>
		<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="delete" value="<?php echo $_GET['view']; ?>" />
			<input type="submit" name="submit" value="<?php echo _AT('delete'); ?>" accesskey="x" />
		</form></td>
	</tr>
	</tfoot>
	</table>
	<br />	
	<?php
	}
}

$sql	= "SELECT * FROM ".TABLE_PREFIX."messages WHERE to_member_id=$_SESSION[member_id] ORDER BY date_sent DESC";
$result = mysql_query($sql,$db);
?>
<table class="data" summary="" rules="cols">
<thead>
<tr>
	<th>&nbsp;</th>
	<th width="100" class="cat"><?php echo _AT('from'); ?></th>
	<th width="327" class="cat"><?php echo _AT('subject'); ?></th>
	<th width="150" class="cat"><?php echo _AT('date'); ?></th>
<tr>
</thead>
<tbody>

<?php if ($row = mysql_fetch_assoc($result)): ?>
	<?php
	$count = 0;
	$total = mysql_num_rows($result);
	$view = $_GET['view'];
	do {
		$count ++;

		?>
		<?php if ($row['message_id'] == $view): ?>
			<tr onmousedown="document.location='<?php echo $_SERVER['PHP_SELF']; ?>?view=<?php echo $row['message_id']; ?>'" class="selected">
		<?php else: ?>
			<tr onmousedown="document.location='<?php echo $_SERVER['PHP_SELF']; ?>?view=<?php echo $row['message_id']; ?>'" title="<?php echo _AT('view_message'); ?>">
		<?php endif; ?>

		<?php
		echo '<td valign="middle" width="10" align="center">';
		if ($row['new'] == 1)	{
			echo _AT('new');
		} else if ($row['replied'] == 1) {
			echo _AT('replied');
		}
		echo '</td>';

		$name = AT_print(get_login($row['from_member_id']), 'members.logins');

		echo '<td align="left">';

		if ($view != $row['message_id']) {
			echo $name.'</td>';
		} else {
			echo '<strong>'.$name.'</strong></td>';
		}

		echo '<td valign="middle">';
		if ($view != $row['message_id']) {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?view='.$row['message_id'].'">'.AT_print($row['subject'], 'messages.subject').'</a></td>';
		} else {
			echo '<strong>'.AT_print($row['subject'], 'messages.subject').'</strong></td>';
		}
	
		echo '<td valign="middle" align="left" nowrap="nowrap">';
		echo AT_date(_AT('inbox_date_format'),
					 $row['date_sent'],
					 AT_DATE_MYSQL_DATETIME);
		echo '</td>';
		echo '</tr>';
	} while ($row = mysql_fetch_assoc($result)); ?>
<?php else: ?>
	<tr>
		<td colspan="4"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
</tbody>
</table>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>