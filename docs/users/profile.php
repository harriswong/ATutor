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

$page = 'profile';
$_user_location	= 'users';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');


if ($_SESSION['valid_user'] !== true) {
	require(AT_INCLUDE_PATH.'header.inc.php');

	$info = array('INVALID_USER', $_SESSION['course_id']);
	$msg->printInfos($info);
	
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	Header('Location: profile.php');
	exit;
}

if (isset($_POST['submit'])) {
	$error = '';

	// email check
	if ($_POST['email'] == '') {
		$msg->addError('EMAIL_MISSING');
	} else {
		if(!eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$", $_POST['email'])) {
			$msg->addError('EMAIL_INVALID');
		}
		$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."members WHERE email='$_POST[email]' AND member_id<>$_SESSION[member_id]",$db);
		if(mysql_num_rows($result) != 0) {
			$msg->addError('EMAIL_EXISTS');
		} else if ($_POST['email'] != $_POST['email2']) {
			$msg->addError('EMAIL_MISMATCH');
		}
	}

	// password check
	if ($_POST['password'] == '') { 
		$msg->addError('PASSWORD_MISSING');
	}
	// check for valid passwords
	if ($_POST['password'] != $_POST['password2']) {
		$msg->addError('PASSWORD_MISMATCH');
	}
		
	
	//check date of birth
	$mo = intval($_POST['month']);
	$day = intval($_POST['day']);
	$yr = intval($_POST['year']);

	/* let's us take (one or) two digit years (ex. 78 = 1978, 3 = 2003) */
	if ($yr < date('y')) { 
		$yr += 2000; 
	} else if ($yr < 1900) { 
		$yr += 1900; 
	} 

	$dob = $yr.'-'.$mo.'-'.$day;

	if ($mo && $day && $yr && !checkdate($mo, $day, $yr)) {	
		$msg->addError('DOB_INVALID');
	} else if (!$mo || !$day || !$yr) {
		$dob = '0000-00-00';
		$yr = $mo = $day = 0;
	}
		
	$login = strtolower($_POST['login']);
	if (!$msg->containsErrors()) {			
		if (($_POST['website']) && (!ereg('://',$_POST['website']))) { $_POST['website'] = 'http://'.$_POST['website']; }
		if ($_POST['website'] == 'http://') { $_POST['website'] = ''; }

		// insert into the db.
		$_POST['password']   = $addslashes($_POST['password']);
		$_POST['website']    = ''; //$addslashes($_POST['website']);
		$_POST['first_name'] = $addslashes($_POST['first_name']);
		$_POST['last_name']  = $addslashes($_POST['last_name']);
		$_POST['address']    = ''; //$addslashes($_POST['address']);
		$_POST['postal']     = ''; //$addslashes($_POST['postal']);
		$_POST['city']       = ''; //$addslashes($_POST['city']);
		$_POST['province']   = ''; //$addslashes($_POST['province']);
		$_POST['country']    = ''; //$addslashes($_POST['country']);
		$_POST['phone']      = ''; //$addslashes($_POST['phone']);
		$_POST['email3']     = $addslashes($_POST['email3']);

		if (!defined('AT_EMAIL_CONFIRMATION') || !AT_EMAIL_CONFIRMATION) {
			$email = "email='$_POST[email]', ";
		} else {
			$email = '';
		}

		$sql = "UPDATE ".TABLE_PREFIX."members SET password='$_POST[password]', $email website='$_POST[website]', first_name='$_POST[first_name]', last_name='$_POST[last_name]', dob='$dob', gender='$_POST[gender]', address='$_POST[address]', postal='$_POST[postal]', city='$_POST[city]', province='$_POST[province]', country='$_POST[country]', phone='$_POST[phone]', language='$_SESSION[lang]', alternate_email='$_POST[email3]' WHERE member_id=$_SESSION[member_id]";

		$result = mysql_query($sql,$db);
		if (!$result) {
			$msg->printErrors('DB_NOT_UPDATED');
			exit;
		}

		$msg->addFeedback('PROFILE_UPDATED');

		if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION) {
			$sql	= "SELECT email, creation_date FROM ".TABLE_PREFIX."members WHERE member_id=$_SESSION[member_id]";
			$result = mysql_query($sql, $db);
			$row    = mysql_fetch_assoc($result);

			if ($row['email'] != $_POST['email']) {
				$code = substr(md5($_POST['email'] . $row['creation_date'] . $_SESSION['member_id']), 0, 10);
				$confirmation_link = $_base_href . 'confirm.php?id='.$_SESSION['member_id'].SEP .'e='.urlencode($_POST['email']).SEP.'m='.$code;

				/* send the email confirmation message: */
				require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');
				$mail = new ATutorMailer();

				$mail->From     = EMAIL;
				$mail->AddAddress($_POST['email']);
				$mail->Subject = SITE_NAME . ' - ' . _AT('email_confirmation_subject');
				$mail->Body    = _AT('email_confirmation_message', SITE_NAME, $confirmation_link);

				$mail->Send();

				$msg->addFeedback('CONFIRM_EMAIL');
			}
		}

		header('Location: ./profile.php');
		exit;
	}
}

$sql	= 'SELECT * FROM '.TABLE_PREFIX.'members WHERE member_id='.$_SESSION['member_id'];
$result = mysql_query($sql,$db);
$row = mysql_fetch_assoc($result);

if (!isset($_POST['submit'])) {
	$_POST = $row;
	list($_POST['year'],$_POST['month'],$_POST['day']) = explode('-', $row['dob']);
	$_POST['password2'] = $_POST['password'];
}

//get employee # for user profile display
if (defined('AT_MASTER_LIST') && AT_MASTER_LIST) { 
	$sql	= 'SELECT public_field FROM '.TABLE_PREFIX.'master_list WHERE member_id='.$_SESSION['member_id'];
	$result_stud_id = mysql_query($sql,$db);
	$row_stud_id = mysql_fetch_assoc($result_stud_id);
	$_POST['student_id'] = $row_stud_id['public_field'];
}


/* template starts here */

$savant->assign('row', $row);

$onload = 'document.form.password.focus();';

$savant->display('registration.tmpl.php');

?>