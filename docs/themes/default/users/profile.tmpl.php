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
	<td class="row1"><?php echo $this->row['login'];?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="password"><?php   echo _AT('password'); ?>:</label></td>
	<td class="row1" valign="top"><input id="password" class="formfield" name="password" type="password"  size="15" maxlength="15" value="<?php echo stripslashes(htmlspecialchars($this->row['password'])); ?>" /><br />
	<small class="spacer">&middot; <?php echo _AT('combination'); ?><br />
	&middot; <?php echo _AT('15_max_chars'); ?></small></td>
</tr>
<tr>
	<td class="row1"><label for="password2"><?php echo _AT('password_again'); ?>:</label></td>
	<td class="row1"><input id="password2" class="formfield" name="password2" type="password" size="15" maxlength="15" value="<?php if ($_POST['submit']){ echo stripslashes(htmlspecialchars($_POST['password2'])); } else { echo stripslashes(htmlspecialchars($this->row['password'])); }?>" /></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="email"><?php   echo _AT('email_address'); ?>:</label></td>
	<td class="row1"><input id="email" class="formfield" name="email" type="text" size="30" maxlength="60"  value="<?php echo stripslashes(htmlspecialchars($this->row['email']));?>" /></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="pri_langs"><?php echo _AT('language'); ?>:</label></td>
	<td class="row1"><?php $languageManager->printDropdown($_SESSION['lang'], 'lang', 'pri_langs'); ?></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="language"><?php echo _AT('status'); ?>:</label></td>
	<td class="row1" align="left">
<?php
	if ($this->row['status']) { 
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
	<tr><td class="row1"><?php echo _AT('auto_login1'); ?>:</td>
	<td class="row1">
	<?php
		if ( ($_COOKIE['ATLogin'] != '') && ($_COOKIE['ATPass'] != '') ) {
			echo _AT('auto_enable');
		} else {
			echo _AT('auto_disable');
		}
	?>
	<br /><br />
	</td>
</tr>
</table>
</fieldset><br />

<fieldset><strong><legend><?php echo _AT('personal_information'); ?></strong></legend>
<table cellspacing="1" cellpadding="0" border="0" summary="">
<tr>
	<td class="row1"><label for="first_name"><?php   echo _AT('first_name'); ?>:</label></td>
	<td class="row1"><input id="first_name" class="formfield" name="first_name" type="text" value="<?php echo stripslashes(htmlspecialchars($this->row['first_name']));?>" /></td>
</tr>
<tr>
	<td class="row1"><label for="last_name"><?php   echo _AT('last_name'); ?>:</label></td>
	<td class="row1"><input id="last_name" class="formfield" name="last_name" type="text"  value="<?php echo stripslashes(htmlspecialchars($this->row['last_name']));?>" /></td>
</tr>
<tr>
	<td class="row1"><?php echo _AT('date_of_birth'); ?>:</td>
	<td class="row1">
	<?php
	$dob = explode('-',$this->row['dob']); 

	if (!isset($yr) && ($dob[0] > 0)) { $yr = $dob[0]; }
	if (!isset($mo) && ($dob[1] > 0)) { $mo = $dob[1]; }
	if (!isset($day) && ($dob[2] > 0)) { $day = $dob[2]; }
	?>
	<input title="<?php echo _AT('day'); ?>" id="day" class="formfield" name="day" type="text" size="2" maxlength="2" value="<?php echo $day; ?>" />-<input title="<?php echo _AT('month'); ?>" id="month" class="formfield" name="month" type="text" size="2" maxlength="2" value="<?php echo $mo; ?>" />-<input title="<?php echo _AT('year'); ?>" id="year" class="formfield" name="year" type="text" size="4" maxlength="4" value="<?php echo $yr; ?>" /><small> <?php echo _AT('dd_mm_yyyy'); ?></small>
	</td>
</tr>
<tr>
	<td class="row1"><?php   echo _AT('gender'); ?>:</td>
	<td class="row1"><?php
	if ($this->row['gender'] == 'm'){
		$m = ' checked="checked"';
	}
	if ($this->row['gender'] == 'f'){
		$f = ' checked="checked"';
	}

	?><input type="radio" name="gender" id="m" <?php echo $m;?> value="m" /><label for="m"><?php   echo _AT('male'); ?></label> <input type="radio" value="f" name="gender" id="f" <?php echo $f;?>  size="2" maxlength="2" /><label for="f"><?php   echo _AT('female'); ?></label> <input type="radio" value="ns" name="gender" id="ns" <?php if (($this->row['gender'] == 'ns') || ($this->row['gender'] == '')) { echo 'checked="checked"'; } ?> /><label for="ns"><?php echo _AT('not_specified'); ?></label></td>
</tr>
<tr>
	<td class="row1"><label for="address"><?php   echo _AT('street_address'); ?>:</label></td>
	<td class="row1"><input id="address" class="formfield" name="address" size="40" type="text"   value="<?php echo stripslashes(htmlspecialchars($this->row['address']));?>" /></td>
</tr>
<tr>
	<td class="row1"><label for="postal"><?php   echo _AT('postal_code'); ?>:</label></td>
	<td class="row1"><input id="postal" class="formfield" name="postal" size="7" type="text"   value="<?php echo stripslashes(htmlspecialchars($this->row['postal']));?>" /></td>
</tr>
<tr>
	<td class="row1"><label for="city"><?php   echo _AT('city'); ?>:</label></td>
	<td class="row1"><input id="city" class="formfield" name="city" type="text" value="<?php echo stripslashes(htmlspecialchars($this->row['city'])); ?>" /><br /></td>
</tr>
<tr>
	<td class="row1"><label for="province"><?php   echo _AT('province'); ?>:</label></td>
	<td class="row1"><input id="province" class="formfield" name="province" type="text"   value="<?php echo stripslashes(htmlspecialchars($this->row['province']));?>" /></td>
</tr>
<tr>
	<td class="row1"><label for="country"><?php   echo _AT('country'); ?>:</label></td>
	<td class="row1"><input id="country" class="formfield" name="country" type="text"   value="<?php echo stripslashes(htmlspecialchars($this->row['country']));?>" /></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="phone"><?php   echo _AT('phone'); ?>:</label></td>
	<td class="row1"><input class="formfield" size="11" name="phone" id="phone" type="text" value="<?php echo stripslashes(htmlspecialchars($this->row['phone']));?>" /> <small>(Eg. 123-456-7890)</small></td>
</tr>
<tr>
	<td class="row1" valign="top"><label for="website"><?php   echo _AT('web_site'); ?>:</label></td>
	<td class="row1"><input id="website" class="formfield" name="website" size="40" type="text" value="<?php echo stripslashes(htmlspecialchars($this->row['website']));?>" /><br /><br /></td>
</tr>
</table>
</fieldset>

<br /><p align="center"><input type="submit" class="button" value=" <?php   echo _AT('update_profile'); ?> [Alt-s]" name="submit" accesskey="s" /> <input type="submit" name="cancel" class="button" value=" <?php echo  _AT('cancel'); ?>" /></p>

</form>
<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>