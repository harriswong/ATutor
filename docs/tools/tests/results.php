<?php
/****************************************************************************/
/* ATutor																	*/
/****************************************************************************/
/* Copyright (c) 2002-2007 by Greg Gay, Joel Kronenberg & Heidi Hazelton	*/
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
authenticate(AT_PRIV_TESTS);

$tid = intval($_REQUEST['tid']);


if (isset($_GET['delete'], $_GET['id'])) {
	header('Location:delete_result.php?tid='.$tid.SEP.'rid='.$_GET['id']);
	exit;
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location:view_results.php?tid='.$tid.SEP.'rid='.$_GET['id']);
	exit;
} else if (isset($_GET['edit']) && !$_GET['id'] && !$_GET['asc'] && !$_GET['desc'] && !$_GET['filter'] && !$_GET['reset_filter']) {
	$msg->addError('NO_ITEM_SELECTED');
}

require(AT_INCLUDE_PATH.'lib/test_result_functions.inc.php');

if ($_GET['reset_filter']) {
	unset($_GET);
}

$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('login' => 1, 'full_name' => 1, 'date_taken' => 1, 'fs' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 'login';
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 'login';
} else {
	// no order set
	$order = 'asc';
	$col   = 'login';
}

require(AT_INCLUDE_PATH.'header.inc.php');

if (isset($_GET['status']) && ($_GET['status'] != '') && ($_GET['status'] != 2)) {
	if ($_GET['status'] == 0) {
		$status = " AND R.final_score=''";
	} else {
		$status = " AND R.final_score<>''";
	}
	$page_string .= SEP.'status='.$_GET['status'];
} else {
	$status = '';
}

//get test info
$sql	= "SELECT out_of, anonymous, random, title FROM ".TABLE_PREFIX."tests WHERE test_id=$tid AND course_id=$_SESSION[course_id]";
$result	= mysql_query($sql, $db);
if (!($row = mysql_fetch_array($result))){
	$msg->printErrors('ITEM_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}
$out_of = $row['out_of'];
$anonymous = $row['anonymous'];
$random = $row['random'];

//count total
$sql	= "SELECT count(*) as cnt FROM ".TABLE_PREFIX."tests_results R LEFT JOIN ".TABLE_PREFIX."members M USING (member_id) WHERE R.test_id=$tid";
$result	= mysql_query($sql, $db);
$row	= mysql_fetch_array($result);
$num_sub = $row['cnt'];

//get results based on filtre and sorting
if ($anonymous == 1) {
	$sql	= "SELECT R.*, '<em>"._AT('anonymous')."</em>' AS login FROM ".TABLE_PREFIX."tests_results R WHERE R.test_id=$tid $status ORDER BY $col $order";
} else {	
	$sql	= "SELECT R.*, login, CONCAT(first_name, ' ', second_name, ' ', last_name) AS full_name, R.final_score+0.0 AS fs FROM ".TABLE_PREFIX."tests_results R LEFT JOIN  ".TABLE_PREFIX."members M USING (member_id) WHERE R.test_id=$tid $status ORDER BY $col $order, R.final_score $order";
}

$result = mysql_query($sql, $db);
if ($anonymous == 1) {
	$guest_text = '<em>'._AT('anonymous').'</em>';
} else {
	$guest_text = '- '._AT('guest').' -';
}
while ($row = mysql_fetch_assoc($result)) {
	$row['full_name'] = $row['full_name'] ? $row['full_name'] : $guest_text;
	$row['login']     = $row['login']     ? $row['login']     : $guest_text;
	$rows[$row['result_id']] = $row;
}

$num_results = mysql_num_rows($result);

//count unmarked: no need to do this query if filtre is already getting unmarked
if (isset($_GET['status']) && ($_GET['status'] != '') && ($_GET['status'] == 0)) {
	$num_unmarked = $num_results;
} else {
	$sql		= "SELECT count(*) as cnt FROM ".TABLE_PREFIX."tests_results R, ".TABLE_PREFIX."members M WHERE R.test_id=$tid AND R.member_id=M.member_id AND R.final_score=''";
	$result	= mysql_query($sql, $db);
	$row = mysql_fetch_array($result);
	$num_unmarked = $row['cnt'];
}

?>
<h3><?php echo AT_print($row['title'], 'tests.title'); ?></h3><br />

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="tid" value="<?php echo $tid; ?>" />

	<div class="input-form">
		<div class="row">
			<h3><?php echo _AT('results_found', $num_results); ?></h3>
		</div>

		<div class="row">
			<?php echo _AT('status'); ?><br />
			<input type="radio" name="status" value="1" id="s0" <?php if ($_GET['status'] == 1) { echo 'checked="checked"'; } ?> /><label for="s0"><?php echo _AT('marked_label', $num_sub - $num_unmarked); ?></label> 

			<input type="radio" name="status" value="0" id="s1" <?php if ($_GET['status'] == 0) { echo 'checked="checked"'; } ?> /><label for="s1"><?php echo _AT('unmarked_label', $num_unmarked); ?></label> 

			<input type="radio" name="status" value="2" id="s2" <?php if (!isset($_GET['status']) || ($_GET['status'] != 0 && $_GET['status'] != 1)) { echo 'checked="checked"'; } ?> /><label for="s2"><?php echo _AT('all_label', $num_sub); ?></label> 

		</div>

		<div class="row buttons">
			<input type="submit" name="filter" value="<?php echo _AT('filter'); ?>" />
			<input type="submit" name="reset_filter" value="<?php echo _AT('reset_filter'); ?>" />
		</div>
	</div>
</form>

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="tid" value="<?php echo $tid; ?>" />

<table class="data" summary="" rules="cols">
<colgroup>
	<?php if ($col == 'login'): ?>
		<col />
		<col class="sort" />
		<col span="3" />
	<?php elseif ($col == 'full_name'): ?>
		<col span="2" />
		<col class="sort" />
		<col span="2" />
	<?php elseif($col == 'date_taken'): ?>
		<col span="3" />
		<col class="sort" />
		<col span="1" />
	<?php elseif($col == 'fs'): ?>
		<col span="4" />
		<col class="sort" />
	<?php endif; ?>
</colgroup>
<thead>
<tr>
	<th scope="col" width="1%">&nbsp;</th>
	<th scope="col"><a href="tools/tests/results.php?tid=<?php echo $tid.$page_string.SEP.$orders[$order]; ?>=login"><?php echo _AT('login_name'); ?></a></th>
	<th scope="col"><a href="tools/tests/results.php?tid=<?php echo $tid.$page_string.SEP.$orders[$order]; ?>=full_name"><?php echo _AT('full_name'); ?></a></th>
	<th scope="col"><a href="tools/tests/results.php?tid=<?php echo $tid.$page_string.SEP.$orders[$order]; ?>=date_taken"><?php echo _AT('date_taken'); ?></a></th>
	<th scope="col"><a href="tools/tests/results.php?tid=<?php echo $tid.$page_string.SEP.$orders[$order]; ?>=fs"><?php echo _AT('mark'); ?></a></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="6"><input type="submit" name="edit" value="<?php echo _AT('view_mark_test'); ?>" /> <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" /></td>
</tr>
</tfoot>
<tbody>
<?php if ($rows): ?>
	<?php foreach ($rows as $row): ?>
		<tr onmousedown="document.form['r<?php echo $row['result_id']; ?>'].checked = true;rowselect(this);" id="r_<?php echo $row['result_id']; ?>">
			<td><input type="radio" name="id" value="<?php echo $row['result_id']; ?>" id="r<?php echo $row['result_id']; ?>" /></td>
			<td><label for="r<?php echo $row['result_id']; ?>"><?php echo $row['login']; ?></label></td>
			<td><?php echo $row['full_name']; ?></td>
			<td><?php echo AT_date('%j/%n/%y %G:%i', $row['date_taken'], AT_DATE_MYSQL_DATETIME); ?></td>
			<td align="center">
				<?php if ($out_of) {
					if ($random) {
						$out_of = get_random_outof($tid, $row['result_id']);
					}

					if ($row['final_score'] != '') { 
						echo $row['final_score'].'/'.$out_of;
					} else {
						echo _AT('unmarked');
					}
				} else {
					echo _AT('na');
				}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="4"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>
</tbody>
</table>
</form>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>