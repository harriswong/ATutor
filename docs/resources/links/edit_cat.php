<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('resources');
$_section[0][1] = 'resources/';
$_section[1][0] = _AT('links_database');
$_section[1][1] = 'resources/links/';
$_section[2][0] = _AT('edit_category');

authenticate(AT_PRIV_LINKS);

if (isset($_POST['submit'])) {
	$_POST['CatID'] = intval($_POST['CatID']);

	$sql	= "UPDATE ".TABLE_PREFIX."resource_categories SET CatName='$_POST[cat_name]' WHERE CatID=$_POST[CatID] AND course_id=$_SESSION[course_id]";

	$result	= mysql_query($sql, $db);
	
	$msg->addFeedback('LINK_CAT_EDITED');
	header('Location: index.php');
	exit;
}

require (AT_INCLUDE_PATH.'header.inc.php');

$_GET['CatID'] = intval($_GET['CatID']);

	require('mysql.php'); /* Access to all the database functions */
	$db2 = new MySQL;
	if(!$db2->init()) {
		$msg->printErrors('NO_DB_CONNECT');
		exit;
	}


	$catName = $db2->get_CatNames($_GET['CatID']);

?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="CatID" value="<?php echo $_GET['CatID']; ?>" />

	<div class="input-form">
		<div class="row">
			<label for="cat"><?php echo _AT('category_name'); ?></label><br />
			<input name="cat_name" size="40" value="<?php echo stripslashes(htmlspecialchars($catName)); ?>" id="cat" />
		</div>

		<div class="row buttons">
			<input type="submit" name="submit" value="<?php echo _AT('edit_category'); ?>" accesskey="s" />
			<input type="reset" value=" <?php echo _AT('reset'); ?> " />
		</div>
	</div>
	</form>

<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>