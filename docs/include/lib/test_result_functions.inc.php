<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2007 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }

// returns T/F whether or not this member can view this test:
function authenticate_test($tid, $taking_test = false) {
	if (authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) {
		return TRUE;
	}
	if (!$_SESSION['enroll']) {
		return FALSE;
	}
	global $db;

	if ($taking_test) {
		$sql = "SELECT format FROM ".TABLE_PREFIX."tests WHERE course_id=$_SESSION[course_id] AND test_id=$tid";
		$result = mysql_query($sql, $db);
		$row = mysql_fetch_assoc($result);

		if ($row['format']) {
			$sql    = "SELECT UNIX_TIMESTAMP(MAX(date_taken)) AS last_taken FROM ".TABLE_PREFIX."tests_results WHERE member_id=$_SESSION[member_id] AND test_id=$tid";
			$result = mysql_query($sql, $db);
			if ($row    = mysql_fetch_assoc($result)) {
				//$seven_days_past = time() - 7 * 24 * 60 * 60;
				$twenty_four_hours = time() - 24 * 60 * 60;
				if ($row['last_taken'] > $twenty_four_hours) {
					global $msg;
					$msg->addError('TEST_24HOURS');
					return FALSE;
				}
			}
		}
	}

	$sql    = "SELECT approved FROM ".TABLE_PREFIX."course_enrollment WHERE member_id=$_SESSION[member_id] AND course_id=$_SESSION[course_id] AND approved='y'";
	$result = mysql_query($sql, $db);
	if (!($row = mysql_fetch_assoc($result))) {
		return FALSE;
	}

	$sql    = "SELECT group_id FROM ".TABLE_PREFIX."tests_groups WHERE test_id=$tid";
	$result = mysql_query($sql, $db);
	if (mysql_num_rows($result) == 0) {
		// not limited to any group; everyone has access:
		return TRUE;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$sql     = "SELECT * FROM ".TABLE_PREFIX."groups_members WHERE group_id=$row[group_id] AND member_id=$_SESSION[member_id]";
		$result2 = mysql_query($sql, $db);

		if ($row2 = mysql_fetch_assoc($result2)) {
			return TRUE;
		}
	}

	return FALSE;
}

function print_question_cats($cat_id = 0) {	

	global $db;

	echo '<option value="0">'._AT('cats_uncategorized').'</option>';
	$sql	= 'SELECT * FROM '.TABLE_PREFIX.'tests_questions_categories WHERE course_id='.$_SESSION['course_id'].' ORDER BY title';
	$result	= mysql_query($sql, $db);

	while ($row = mysql_fetch_array($result)) {
		echo '<option value="'.$row['category_id'].'"';
		if ($row['category_id'] == $cat_id) {
			echo 'selected="selected" ';
		}
		echo '>'.$row['title'].'</option>';
	}
}

function print_VE ($area) {
?>
	<script type="text/javascript" language="javascript">
		document.writeln('<a href="#" onclick="javascript:window.open(\'<?php echo AT_BASE_HREF; ?>tools/tests/form_editor.php?area=<?php echo $area; ?>\',\'newWin1\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,copyhistory=0,width=640,height=480\'); return false;" style="cursor: pointer; text-decoration: none" ><?php echo _AT('use_visual_editor'); ?></a>');
	</script>

<?php
	//possibley add a <noscript> link to filemanager with target="_blank"
}

function get_random_outof($test_id, $result_id) {	
	global $db;
	$total = 0;

	$sql	= 'SELECT SUM(Q.weight) AS weight FROM '.TABLE_PREFIX.'tests_questions_assoc Q, '.TABLE_PREFIX.'tests_answers A WHERE Q.test_id='.$test_id.' AND Q.question_id=A.question_id AND A.result_id='.$result_id;

	$result	= mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {
		return $row['weight'];
	}

	return 0;
}

?>