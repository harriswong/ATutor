<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_ADMIN);

$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/';
$_section[1][0] = _AT('course_copyright2');
if ($_POST['cancel']) {
	if ($_POST['pid'] != 0) {
		$msg->addFeedback('CANCELLED');
		Header('Location: ../index.php?cid='.$_POST['pid']);
		exit;
	}
	
	$msg->addFeedback('CANCELLED');
	Header('Location: ../tools/index.php');
	exit;
}

if($_POST['update']){
	$head_sql ="UPDATE ".TABLE_PREFIX."courses SET copyright='".$_POST['copyright']."' WHERE course_id='$_SESSION[course_id]'";
	$result = mysql_query($head_sql, $db);
	$msg->addFeedback('COPYRIGHT_UPDATED');
}

require(AT_INCLUDE_PATH.'header.inc.php');


?>


<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<input type="hidden" name="update" value="1" />

<div class="input-form">
	<div class="row">
		<label for="copyright"><?php echo _AT('course_copyright'); ?></label><br />
		<textarea name="copyright" rows="5" cols="65" id="copyright"><?php
			$getcopyright_sql="select copyright from ".TABLE_PREFIX."courses where course_id='$_SESSION[course_id]'";
			$result2=mysql_query($getcopyright_sql, $db);
			while($row=mysql_fetch_row($result2)){
				$show_edit_copyright = $row[0];
			}
			if (strlen($show_edit_copyright) > 0){
				echo $show_edit_copyright;
			}
			?></textarea>
	</div>

	<div class="row buttons">
		<input type="submit" value="<?php echo _AT('save'); ?>" accesskey="s" /> 
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>