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

authenticate(AT_PRIV_TEST_CREATE);


$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/index.php';
$_section[1][0] = _AT('test_manager');
$_section[1][1] = 'tools/tests/index.php';
$_section[2][0] = _AT('question_database');
$_section[2][1] = 'tools/tests/question_db.php';
$_section[3][0] = _AT('cats_categories');

if ($_POST['submit'] == _AT('edit')) {
	if ($_POST['category']) {
		header('Location: question_cats_manage.php?catid='.$_POST['category']);
		exit;
	} else {
		$msg->addError('NO_CAT_SELECTED');
	}

} else if ($_POST['submit'] == _AT('delete')) {
	if (isset($_POST['category'])) {
		//confirm
		header('Location: question_cats_delete.php?catid='.$_POST['category']);
		exit;

	} else {
		$msg->addError('NO_CAT_SELECTED');
	}	
} 

require(AT_INCLUDE_PATH.'header.inc.php');


$msg->addHelp('QUESTION_CATEGORIES');
$msg->printAll();

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">

<div align="center">
<span class="editorsmallbox">
	<small><img src="<?php echo $_base_path; ?>images/pen2.gif" border="0" class="menuimage12" alt="<?php echo _AT('editor'); ?>" title="<?php echo _AT('editor'); ?>" height="14" width="16" /> <a href="tools/tests/question_cats_manage.php"><?php echo _AT('add'); ?></a></small>
</span>
</div>

<div class="input-form">
<?php 
	$sql	= "SELECT * FROM ".TABLE_PREFIX."tests_questions_categories WHERE course_id=$_SESSION[course_id] ORDER BY title";
	$result	= mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {
		do {
?>
			<div class="row">
				<input type="radio" id="cat_<?php echo $row['category_id']; ?>" name="category" value="<?php echo $row['category_id']; ?>" />
				<label for="cat_<?php echo $row['category_id']; ?>"><?php echo $row['title']; ?></label>
			</div>
<?php 
		} while ($row = mysql_fetch_assoc($result));
?>

		<div class="row buttons">
			<input type="submit" value="<?php echo _AT('edit'); ?>"   name="submit" />
			<input type="submit" value="<?php echo _AT('delete'); ?>" name="submit" />
		</div>
<?php

	} else {
		echo '<tr><td class="row1">'._AT('cats_no_categories').'</td></tr>';
		echo '<tr><td height="1" class="row2" colspan="2"></td></tr>';
	}
?>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>