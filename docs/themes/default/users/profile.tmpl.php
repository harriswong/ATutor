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
// $Id: edit.php 3111 2005-01-18 19:32:00Z joel $

require(AT_INCLUDE_PATH.'header.inc.php');
global $msg;
global $languageManager;

$msg->printAll();

?>
<fieldset><strong><legend><?php echo _AT('account_information'); ?></legend></strong>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table cellspacing="1" cellpadding="0" border="0" summary="">
<tr>
	<td class="row1"><?php echo _AT('login_name'); ?>:</td>
	<td class="row1"><?php debug($tmpl_profile); echo $row['login'];?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="email"><?php echo _AT('email_address'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['email']));?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="pri_langs"><?php echo _AT('language'); ?>:</label></td>
	<td class="row1"><?php echo $_SESSION['lang']; ?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="language"><?php echo _AT('status'); ?>:</label></td>
	<td class="row1" align="left">
<?php
	if ($row['status']) { 
		echo _AT('instructor'); 
	} else { 
		echo _AT('student'); 
		if (ALLOW_INSTRUCTOR_REQUESTS) {
			echo ' <br /><a href="users/request_instructor.php">'._AT('request_instructor_account').'</a>';
		} else {
			echo '<br /><small>'._AT('request_instructor_disabled').'</small>';
		}
	}
?>
	</td>
</tr>
<?php
	echo '<tr><td class="row1">'._AT('auto_login1').':</td><td class="row1">';
	if ( ($_COOKIE['ATLogin'] != '') && ($_COOKIE['ATPass'] != '') ) {
		echo _AT('auto_enable');
	} else {
		echo _AT('auto_disable');
	}
	
	echo '<br /><br /></td></tr>'."\n";
?>
</table>
</fieldset><br />

<fieldset><strong><legend><?php echo _AT('personal_information'); ?></strong></legend>
<table cellspacing="1" cellpadding="0" border="0" summary="">
<tr>
	<td class="row1"><label for="first_name"><?php   echo _AT('first_name'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['first_name']));?></td>
</tr>
<tr>
	<td class="row1"><label for="last_name"><?php   echo _AT('last_name'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['last_name']));?></td>
</tr>
<tr>
	<td class="row1"><?php echo _AT('date_of_birth'); ?>:</td>
	<td class="row1">
	<?php
	$dob = explode('-',$row['dob']); 

	if (!isset($yr) && ($dob[0] > 0)) { $yr = $dob[0]; }
	if (!isset($mo) && ($dob[1] > 0)) { $mo = $dob[1]; }
	if (!isset($day) && ($dob[2] > 0)) { $day = $dob[2]; }
	?>
	<?php echo $day.'-'.$mo.'-'.$yr; ?>
	</td>
</tr>
<tr>
	<td class="row1"><?php   echo _AT('gender'); ?>:</td>
	<td class="row1"><?php
	if ($row['gender'] == 'm'){
		$gender = _AT('male');
	} else if ($row['gender'] == 'f'){
		$gender = _AT('female');
	} else {
		$gender = _AT('not_specified');
	}
	echo $gender;
	?></td>
</tr>
<tr>
	<td class="row1"><label for="address"><?php   echo _AT('street_address'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['address']));?></td>
</tr>
<tr>
	<td class="row1"><label for="postal"><?php   echo _AT('postal_code'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['postal']));?></td>
</tr>
<tr>
	<td class="row1"><label for="city"><?php   echo _AT('city'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['city'])); ?></td>
</tr>
<tr>
	<td class="row1"><label for="province"><?php   echo _AT('province'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['province']));?></td>
</tr>
<tr>
	<td class="row1"><label for="country"><?php   echo _AT('country'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['country']));?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="phone"><?php   echo _AT('phone'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['phone']));?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="website"><?php   echo _AT('web_site'); ?>:</label></td>
	<td class="row1"><?php echo stripslashes(htmlspecialchars($row['website']));?></td>
</tr>
</table>
</fieldset>

</form>
<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>