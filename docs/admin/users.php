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

$page = 'users';
$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }

if (isset($_GET['delete'])) {
	header('Location: admin_delete.php?id='.$_GET['id']);
	exit;
} else if (isset($_GET['profile'])) {
	header('Location: profile.php?member_id='.$_GET['id']);
	exit;
}

$id = $_GET['id'];
$L = $_GET['L'];
require(AT_INCLUDE_PATH.'header.inc.php'); 

$msg->printAll();

if ($_GET['col']) {
	$col = addslashes($_GET['col']);
} else {
	$col = 'login';
}

if ($_GET['order']) {
	$order = addslashes($_GET['order']);
} else {
	$order = 'asc';
}


${'highlight_'.$col} = ' style="font-size: 1em;"';


$sql	= "SELECT COUNT(member_id) FROM ".TABLE_PREFIX."members";
$result = mysql_query($sql, $db);

if (($row = mysql_fetch_array($result))==0) {
	echo '<tr><td colspan="7" class="row1">'._AT('no_users_found_for').' <strong>'.$_GET['L'].'</strong></td></tr>';
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

	$num_results = $row[0];
	$results_per_page = 100;
	$num_pages = ceil($num_results / $results_per_page);
	$page = intval($_GET['p']);
	if (!$page) {
		$page = 1;
	}	
	$count = (($page-1) * $results_per_page) + 1;

	for ($i=1; $i<=$num_pages; $i++) {
		if ($i == 1) {
			echo _AT('page').': | ';
		}
		if ($i == $page) {
			echo '<strong>'.$i.'</strong>';
		} else {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?p='.$i.'#list">'.$i.'</a>';
		}
		echo ' | ';
	}


	echo '<br /><br /><a name="list"></a>';
?>

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table summary="" class="data" rules="cols" align="center">
<thead>
<tr>
	<th scope="col">&nbsp;</th>

	<th scope="col"><a name="list"></a><small<?php echo $highlight_member_id; ?>><?php echo _AT('id'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=member_id<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('id_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('id_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=member_id<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('id_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('id_descending'); ?>" border="0" height="7" width="11" /></a></small></th>

	<th scope="col"><small<?php echo $highlight_login; ?>><?php echo _AT('username'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=login<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('username_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('username_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=login<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('username_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('username_descending'); ?>" border="0" height="7" width="11" /></a></small></th>

	<th scope="col"><small<?php echo $highlight_first_name; ?>><?php echo _AT('first_name'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=first_name<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('first_name_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('first_name_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=first_name<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('first_name_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('first_name_descending'); ?>" border="0" height="7" width="11" /></a></small></th>

	<th scope="col"><small<?php echo $highlight_last_name; ?>><?php echo _AT('last_name'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=last_name<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('last_name_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('last_name_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=last_name<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('last_name_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('last_name_descending'); ?>" border="0" height="7" width="11" /></a></small></th>

	<th scope="col"><small<?php echo $highlight_email; ?>><?php echo _AT('email'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=email<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('email_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('email_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=email<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('email_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('email_descending'); ?>" border="0" height="7" width="11" /></a></small></th>

	<th scope="col"><small<?php echo $highlight_status; ?>><?php echo _AT('status'); ?> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=status<?php echo SEP; ?>order=desc#list" title="<?php echo _AT('status_ascending'); ?>"><img src="images/asc.gif" alt="<?php echo _AT('status_ascending'); ?>" border="0" height="7" width="11" /></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?col=status<?php echo SEP; ?>order=asc#list" title="<?php echo _AT('status_descending'); ?>"><img src="images/desc.gif" alt="<?php echo _AT('status_descending'); ?>" border="0" height="7" width="11" /></a></small></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="7"><input type="submit" name="delete" value="Delete" /> <input type="submit" name="profile" value="View Profile" /></td>
</tr>
</tfoot>
<tbody>
<?php
	$offset = ($page-1)*$results_per_page;

	$sql	= "SELECT * FROM ".TABLE_PREFIX."members ORDER BY $col $order LIMIT $offset, $results_per_page";
	$result = mysql_query($sql, $db);

	while ($row = mysql_fetch_assoc($result)) : ?>
		<tr onmousedown="document.form['m<?php echo $row['member_id']; ?>'].checked = true;">
			<td><input type="radio" name="id" value="<?php echo $row['member_id']; ?>" id="m<?php echo $row['member_id']; ?>"></td>
			<td><?php echo $row['member_id']; ?></td>
			<td><?php echo $row['login']; ?></td>
			<td><?php echo AT_print($row['first_name'], 'members.first_name'); ?></td>
			<td><?php echo AT_print($row['last_name'], 'members.last_name'); ?></td>
			<td><?php echo AT_print($row['email'], 'members.email'); ?></td>
			<td><?php if ($row['status']) {
					echo _AT('instructor');
				} else {
					echo _AT('student1');
				} ?></td>
		</tr>
<?php endwhile; ?>
</tbody>
</table>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>