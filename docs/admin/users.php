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

$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
admin_authenticate(AT_ADMIN_PRIV_USERS);

if (isset($_GET['delete'], $_GET['id'])) {
	header('Location: admin_delete.php?id='.$_GET['id']);
	exit;
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: edit_user.php?id='.$_GET['id']);
	exit;
} else if (isset($_GET['confirm'], $_GET['id'])) {
	$id  = intval($_GET['id']);
	$sql = "UPDATE ".TABLE_PREFIX."members SET status=".AT_STATUS_STUDENT." WHERE status=".AT_STATUS_UNCONFIRMED." AND member_id=$id";
	$result = mysql_query($sql, $db);

	$msg->addFeedback('ACCOUNT_CONFIRMED');
} else if (isset($_GET['confirm']) || isset($_GET['edit']) || isset($_GET['delete'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

require(AT_INCLUDE_PATH.'header.inc.php');

if ($_GET['reset_filter']) {
	unset($_GET);
}

$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = $addslashes($_GET['asc']);
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = $addslashes($_GET['desc']);
} else {
	// no order set
	$order = 'asc';
	$col   = 'login';
}

if (isset($_GET['status']) && ($_GET['status'] != '')) {
	$status = '=' . intval($_GET['status']);
	$page_string .= SEP.'status='.$_GET['status'];
} else {
	$status = '<>-1';
}

if ($_GET['search']) {
	$page_string .= SEP.'search='.urlencode($_GET['search']);
	$search = $addslashes($_GET['search']);
	$search = str_replace(array('%','_'), array('\%', '\_'), $search);
	$search = '%'.$search.'%';

	if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST) {
		$sql = "SELECT COUNT(M.member_id) AS cnt FROM ".TABLE_PREFIX."members AS M LEFT JOIN ".TABLE_PREFIX."master_list AS ML USING (member_id) WHERE status $status AND ((M.first_name LIKE '$search') OR (M.last_name LIKE '$search') OR (M.email LIKE '$search') OR (M.login LIKE '$search') OR (ML.public_field LIKE '$search'))";
	} else {
		$sql = "SELECT COUNT(member_id) AS cnt FROM ".TABLE_PREFIX."members WHERE status $status AND ((first_name LIKE '$search') OR (last_name LIKE '$search') OR (email LIKE '$search') OR (login LIKE '$search'))";
	}
} else {
	unset ($search);
	$sql = "SELECT COUNT(member_id) AS cnt FROM ".TABLE_PREFIX."members WHERE status $status";
}

$result = mysql_query($sql, $db);

//debug($sql);
$row = mysql_fetch_assoc($result);
$num_results = $row['cnt'];

$results_per_page = 100;
$num_pages = max(ceil($num_results / $results_per_page), 1);
$page = intval($_GET['p']);
if (!$page) {
	$page = 1;
}	
$count  = (($page-1) * $results_per_page) + 1;
$offset = ($page-1)*$results_per_page;

if (isset($search) && admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST) {
	$sql = "SELECT M.member_id, M.login, M.first_name, M.last_name, M.email, M.status  FROM ".TABLE_PREFIX."members AS M LEFT JOIN ".TABLE_PREFIX."master_list AS ML USING (member_id) WHERE M.status $status AND ((M.first_name LIKE '$search') OR (M.last_name LIKE '$search') OR (M.email LIKE '$search') OR (M.login LIKE '$search') OR (ML.public_field LIKE '$search')) ORDER BY $col $order LIMIT $offset, $results_per_page";
} else if(isset ($search)) {
	$sql = "SELECT member_id, login, first_name, last_name, email, status FROM ".TABLE_PREFIX."members WHERE status $status AND ((first_name LIKE '$search') OR (last_name LIKE '$search') OR (email LIKE '$search') OR (login LIKE '$search')) ORDER BY $col $order LIMIT $offset, $results_per_page";
}	else {
	$sql	= "SELECT member_id, login, first_name, last_name, email, status FROM ".TABLE_PREFIX."members WHERE status $status ORDER BY $col $order LIMIT $offset, $results_per_page";
}

$result = mysql_query($sql, $db);


?>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div class="input-form">
		<div class="row">
			<h3><?php echo _AT('results_found', $num_results); ?></h3>
		</div>

		<div class="row">
			<?php echo _AT('account_status'); ?><br />
			<input type="radio" name="status" value="0" id="s0" <?php if ($_GET['status'] == 0) { echo 'checked="checked"'; } ?> /><label for="s0"><?php echo _AT('disabled'); ?></label> 

			<input type="radio" name="status" value="1" id="s1" <?php if ($_GET['status'] == 1) { echo 'checked="checked"'; } ?> /><label for="s1"><?php echo _AT('unconfirmed'); ?></label> 

			<input type="radio" name="status" value="2" id="s2" <?php if ($_GET['status'] == 2) { echo 'checked="checked"'; } ?> /><label for="s2"><?php echo _AT('student'); ?></label>

			<input type="radio" name="status" value="3" id="s3" <?php if ($_GET['status'] == 3) { echo 'checked="checked"'; } ?> /><label for="s3"><?php echo _AT('instructor'); ?></label>

			<input type="radio" name="status" value="" id="s" <?php if ($_GET['status'] == '') { echo 'checked="checked"'; } ?> /><label for="s"><?php echo _AT('all'); ?></label>
		</div>

		<div class="row">
			<label for="search"><?php echo _AT('search'); ?> (<?php echo _AT('login_name').', '._AT('first_name').', '._AT('last_name') .', '._AT('email');
			if (admin_authenticate(AT_ADMIN_PRIV_USERS, TRUE) && defined('AT_MASTER_LIST') && AT_MASTER_LIST) {
				echo ', '._AT('student_id');
			}
			
			?>)</label><br />
			<input type="text" name="search" id="search" size="20" value="<?php echo htmlspecialchars($_GET['search']); ?>" />
		</div>

		<div class="row buttons">
			<input type="submit" name="filter" value="<?php echo _AT('filter'); ?>" />
			<input type="submit" name="reset_filter" value="<?php echo _AT('reset_filter'); ?>" />
		</div>
	</div>
</form>

<div class="paging">
	<ul>
	<?php for ($i=1; $i<=$num_pages; $i++): ?>
		<li>
			<?php if ($i == $page) : ?>
				<a class="current" href="<?php echo $_SERVER['PHP_SELF']; ?>?p=<?php echo $i.$page_string.SEP.$order.'='.$col; ?>"><em><?php echo $i; ?></em></a>
			<?php else: ?>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?p=<?php echo $i.$page_string.SEP.$order.'='.$col; ?>"><?php echo $i; ?></a>
			<?php endif; ?>
		</li>
	<?php endfor; ?>
	</ul>
</div>

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="status" value="<?php echo $_GET['status']; ?>" />

<table summary="" class="data" rules="cols">
<colgroup>
	<?php if ($col == 'login'): ?>
		<col />
		<col class="sort" />
		<col span="4" />
	<?php elseif($col == 'first_name'): ?>
		<col span="2" />
		<col class="sort" />
		<col span="3" />
	<?php elseif($col == 'last_name'): ?>
		<col span="3" />
		<col class="sort" />
		<col span="2" />
	<?php elseif($col == 'email'): ?>
		<col span="4" />
		<col class="sort" />
		<col />
	<?php elseif($col == 'status'): ?>
		<col span="5" />
		<col class="sort" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><a href="admin/users.php?<?php echo $orders[$order]; ?>=login<?php echo $page_string; ?>"><?php echo _AT('login_name');      ?></a></th>
	<th scope="col"><a href="admin/users.php?<?php echo $orders[$order]; ?>=first_name<?php echo $page_string; ?>"><?php echo _AT('first_name'); ?></a></th>
	<th scope="col"><a href="admin/users.php?<?php echo $orders[$order]; ?>=last_name<?php echo $page_string; ?>"><?php echo _AT('last_name');   ?></a></th>
	<th scope="col"><a href="admin/users.php?<?php echo $orders[$order]; ?>=email<?php echo $page_string; ?>"><?php echo _AT('email');           ?></a></th>
	<th scope="col"><a href="admin/users.php?<?php echo $orders[$order]; ?>=status<?php echo $page_string; ?>"><?php echo _AT('account_status'); ?></a></th>
</tr>
</thead>
<?php if ($num_results > 0): ?>
	<tfoot>
	<tr>
		<td colspan="6"><input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" /> <input type="submit" name="confirm" value="<?php echo _AT('confirm'); ?>" /> <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" /></td>
	</tr>
	</tfoot>
	<tbody>
		<?php while($row = mysql_fetch_assoc($result)): ?>
			<tr onmousedown="document.form['m<?php echo $row['member_id']; ?>'].checked = true;">
				<td><input type="radio" name="id" value="<?php echo $row['member_id']; ?>" id="m<?php echo $row['member_id']; ?>" /></td>
				<td><label for="m<?php echo $row['member_id']; ?>"><?php echo $row['login']; ?></label></td>
				<td><?php echo AT_print($row['first_name'], 'members.first_name'); ?></td>
				<td><?php echo AT_print($row['last_name'], 'members.last_name'); ?></td>
				<td><?php echo AT_print($row['email'], 'members.email'); ?></td>
				<td><?php 
					switch ($row['status']) {
							case AT_STATUS_DISABLED:
									echo _AT('disabled');
								break;
							case AT_STATUS_UNCONFIRMED:
									echo _AT('unconfirmed');
								break;
							case AT_STATUS_STUDENT:
									echo _AT('student');
								break;
							case AT_STATUS_INSTRUCTOR:
									echo _AT('instructor');
								break;
					} ?></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
<?php else: ?>
	<tr>
		<td colspan="6"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
</table>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
