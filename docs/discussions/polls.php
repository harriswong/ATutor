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

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('discussions');
$_section[0][1] = 'discussions/index.php';
$_section[1][0] = _AT('polls');

if (isset($_POST['view'])) {
	if ($_POST['poll'] == '') {
		$msg->addError('NO_POLL_SELECTED');
	} else {
		header('Location: ./poll.php?id=' . $_POST['poll']);
	}
} 

else if (isset($_POST['edit'])) {
	if ($_POST['poll'] == '') {
		$msg->addError('NO_POLL_SELECTED');
	} else {
		header('Location: ../editor/edit_poll.php?poll_id=' . $_POST['poll']);
	}
}

else if (isset($_POST['delete'])) { 
	if ($_POST['poll'] == '') {
		$msg->addError('NO_POLL_SELECTED');
	} else {
		header('Location: ../editor/delete_poll.php?pid=' . $_POST['poll'] );
	}
}


require(AT_INCLUDE_PATH.'header.inc.php'); 

if ($_GET['col']) {
	$col = addslashes($_GET['col']);
} else {
	$col = 'created_date';
}

if ($_GET['order']) {
	$order = addslashes($_GET['order']);
} else {
	$order = 'DESC';
}

${'highlight_'.$col} = ' style="font-size: 1em;"';

$sql	= "SELECT * FROM ".TABLE_PREFIX."polls WHERE course_id=$_SESSION[course_id] ORDER BY $col $order";
$result = mysql_query($sql, $db);


/* admin editing options: */
/* this session thing is a hack to temporarily prevent the en/dis editor link from affecting 'add poll' */
if (authenticate(AT_PRIV_POLLS, AT_PRIV_RETURN)) {
	unset($editors);
	$editors[] = array('priv' => AT_PRIV_POLLS, 'title' => _AT('add_poll'), 'url' => $_base_path.'editor/add_poll.php');
	print_editor($editors , $large = true);

	if (!$_SESSION['prefs'][PREF_EDIT]) {
		
		$help = array('ENABLE_EDITOR', $_my_uri);
		$msg->printHelps($help);
	}
}



if (!($row = mysql_fetch_assoc($result))) {
	echo '<p>'._AT('no_polls_found').'</p>';
} else {
	$msg->printAll();

	$num_rows = mysql_num_rows($result);
?>


<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<table class="data" summary="" rules="cols">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col">
		<?php echo _AT('question'); ?>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=question<?php echo SEP; ?>order=asc" title="<?php echo _AT('question_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('question_ascending'); ?>" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=question<?php echo SEP; ?>order=desc" title="<?php echo _AT('question_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('question_descending'); ?>" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>
	</th>
	<th scope="col">
		<?php echo _AT('created'); ?>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=created_date<?php echo SEP; ?>order=asc" title="<?php echo _AT('created_date_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('created_date_ascending'); ?>" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a> 
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=created_date<?php echo SEP; ?>order=desc" title="<?php echo _AT('created_date_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('created_date_descending'); ?>" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>
	</th>
</tr>
</thead>
<?php 
	if ($_SESSION['prefs'][PREF_EDIT] && authenticate(AT_PRIV_POLLS,AT_PRIV_RETURN)) { 
?>
		<tfoot>
		<tr>
			<td colspan="6">
				<input type="submit" name="view"   value="<?php echo _AT('view'); ?>" />
				<input type="submit" name="edit"   value="<?php echo _AT('edit'); ?>" />
				<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
			</td>
		</tr>
		</tfoot>
<?php
	} // end if
?>

<tbody>
<?php
	do {
		echo '<tr onmousedown="document.form[\'p_' . $row['poll_id'] . '\'].checked = true;">';
		echo '<td>';
		echo '<input type="radio" id="p_' . $row['poll_id'] . '" name="poll" value="' . $row['poll_id'] . '" /></td>';
		echo '<td><label for="p_' . $row['poll_id'] . '">' . AT_print($row['question'], 'polls.question') . '</label>';
		echo '</td>';
		echo '<td>' . $row['created_date'] . '</td>';

	} while ($row = mysql_fetch_assoc($result));

	echo '</tbody>';
	echo '</table>';
	echo '</form>';
}

require(AT_INCLUDE_PATH.'footer.inc.php'); 
?>