<?php
/****************************************************************/
/* ATutor                                                       */
/****************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca                                             */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

$_user_location	= 'public';

define('AT_INCLUDE_PATH', 'include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

if (isset($_COOKIE['FHA_REGISTER'])) {
	$cookie_registration_lockout = $_COOKIE['FHA_REGISTER'];
	if (time()-$cookie_registration_lockout < 3600) {
		$msg->addError('REGISTRATION_LOCKED');
		require(AT_INCLUDE_PATH.'header.inc.php');
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	} else {
		setcookie('FHA_REGISTER', 'garbage', time()-31536000);
		unset ($_COOKIE['FHA_REGISTER']);
		unset ($_SESSION['register_attempts']);
	}
}


if (isset($_POST['cancel'])) {
	header('Location: ./login.php');
	exit;
} else if (isset($_POST['submit'])) {
	/* email check */
	if ($_POST['email'] == '') {
		$msg->addError('EMAIL_MISSING');
	} else if (!eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$", $_POST['email'])) {
		$msg->addError('EMAIL_INVALID');
	} else {
		$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."members WHERE email LIKE '$_POST[email]'",$db);
		if (mysql_num_rows($result) != 0) {
			$msg->addError('EMAIL_EXISTS');
		} else if ($_POST['email'] != $_POST['email2']) {
			$msg->addError('EMAIL_MISMATCH');
		} /*else if ((strpos($_POST['email'], 'fraserhealth.ca') !== FALSE) && (substr_count($_POST['email'], '.') != 2)) {
			// if email uses FHA email then check for the dot.
			$msg->addError('EMAIL_FHA_MISMATCH');
		} else if ($_POST['email'] == 'firstname.lastname@fraserhealth.ca') {
			$msg->addError('EMAIL_FHA_MISMATCH');
		}*/
	}

	/* if alternate email not empty */
	/*if ($_POST['email3'] != '') {
		if ($_POST['email4'] == '') {
			$msg->addError('ALT_EMAIL_CONFIRM');
		} else if ($_POST['email3'] != $_POST['email4']) {
			$msg->addError('ALT_EMAIL_MISMATCH');
		}
	}*/
	$chk_email = $addslashes($_POST['email']);
	$chk_login = $addslashes($_POST['login']);

	if (intval($_POST['secret']) != $_SESSION['secret']) {
		$msg->addError('SECRET_ERROR');
	}

	$_POST['password'] = trim($_POST['password']);
	$_POST['first_name'] = trim($_POST['first_name']);
	$_POST['last_name'] = trim($_POST['last_name']);

	/* login name check */
	if ($_POST['login'] == '') {
		$msg->addError('LOGIN_NAME_MISSING');
	} else {
		/* check for special characters */
		if (!(eregi("^[a-zA-Z0-9_]([a-zA-Z0-9_])*$", $_POST['login']))) {
			$msg->addError('LOGIN_CHARS');
		} else {
			$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."members WHERE login='$chk_login'",$db);
			if (mysql_num_rows($result) != 0) {
				$msg->addError('LOGIN_EXISTS');
			} else {
				$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."admins WHERE login='$chk_login'",$db);
				if (mysql_num_rows($result) != 0) {
					$msg->addError('LOGIN_EXISTS');
				}
			}
		}
	}

	/* password check:	*/
	if ($_POST['password'] == '') { 
		$msg->addError('PASSWORD_MISSING');
	} else {
		// check for valid passwords
		if ($_POST['password'] != $_POST['password2']){
			$msg->addError('PASSWORD_MISMATCH');
		} else if (strlen($_POST['password']) < 8) {
			$msg->addError('PASSWORD_LENGTH');
		} else if ((preg_match('/[a-z]+/i', $_POST['password']) + preg_match('/[0-9]+/i', $_POST['password']) + preg_match('/[_\-\/+!@#%^$*&)(|.]+/i', $_POST['password'])) < 2) {
			$msg->addError('PASSWORD_CHARS');
		}

	}

	if ($_POST['email'] == '') {
		$msg->addError('EMAIL_MISSING');
	} else if (!eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$", $_POST['email'])) {
		$msg->addError('EMAIL_INVALID');
	}
	$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."members WHERE email='$chk_email'",$db);
	if (mysql_num_rows($result) != 0) {
		$msg->addError('EMAIL_EXISTS');
	}

	/*if (!$_POST['first_name']) { 
		$msg->addError('FIRST_NAME_MISSING');
	}

	if (!$_POST['last_name']) { 
		$msg->addError('LAST_NAME_MISSING');
	}

	// check if first+last is unique
	if ($_POST['first_name'] && $_POST['last_name']) {
		$first_name_sql  = $addslashes($_POST['first_name']);
		$last_name_sql   = $addslashes($_POST['last_name']);
		$second_name_sql = $addslashes($_POST['second_name']);

		$sql = "SELECT member_id FROM ".TABLE_PREFIX."members WHERE first_name='$first_name_sql' AND second_name='$second_name_sql' AND last_name='$last_name_sql' LIMIT 1";
		$result = mysql_query($sql, $db);
		if (mysql_fetch_assoc($result)) {
			$msg->addError('FIRST_LAST_NAME_UNIQUE');
		}
	}*/

	$_POST['login'] = strtolower($_POST['login']);

	//check date of birth
	$mo = intval($_POST['month']);
	$day = intval($_POST['day']);
	$yr = intval($_POST['year']);

	/* let's us take (one or) two digit years (ex. 78 = 1978, 3 = 2003) */
	if ($yr <= date('y')) { 
		$yr += 2000; 
	} else if ($yr < 1900) { 
		$yr += 1900; 
	} 

	$dob = $yr.'-'.$mo.'-'.$day;

	unset($master_list_sql);
	if (defined('AT_MASTER_LIST') && AT_MASTER_LIST) {
		
		$student_id  = $addslashes($_POST['student_id']);
		$dob_y = intval($_POST['year']);
		$dob_m = str_pad(intval($_POST['month']), 2, 0, STR_PAD_LEFT);
		$dob_d = str_pad(intval($_POST['day']), 2, 0, STR_PAD_LEFT);
		$dob = $dob_y . $dob_m . $dob_d;
		$student_pin = sha1($dob);

		$sql    = "SELECT member_id FROM ".TABLE_PREFIX."master_list WHERE public_field='$student_id' AND hash_field='$student_pin'";
		$result = mysql_query($sql, $db);
		if (!($row = mysql_fetch_assoc($result)) || $row['member_id']) {
			// the row wasn't found, or it was found but already used
			$msg->addError('REGISTER_MASTER_USED');
		} else {
			$master_list_sql = "UPDATE ".TABLE_PREFIX."master_list SET member_id=LAST_INSERT_ID() WHERE public_field='$student_id' AND hash_field='$student_pin'";
		}
	}

	if (!$msg->containsErrors()) {
		if (($_POST['website']) && (!ereg("://",$_POST['website']))) { 
			$_POST['website'] = "http://".$_POST['website']; 
		}
		if ($_POST['website'] == 'http://') { 
			$_POST['website'] = ''; 
		}
		if (isset($_POST['private_email'])) {
			$_POST['private_email'] = 1;
		} else {
			$_POST['private_email'] = 0;
		}
		$_POST['postal'] = strtoupper(trim($_POST['postal']));

		$_POST['email']      = $addslashes($_POST['email']);
		$_POST['login']      = $addslashes($_POST['login']);
		$_POST['password']   = $addslashes($_POST['password']);
		$_POST['website']    = ''; //$addslashes($_POST['website']);
		$_POST['first_name'] = $addslashes($_POST['first_name']);
		$_POST['second_name'] = $addslashes($_POST['second_name']);
		$_POST['last_name']  = $addslashes($_POST['last_name']);
		$_POST['address']    = ''; //$addslashes($_POST['address']);
		$_POST['postal']     = ''; //$addslashes($_POST['postal']);
		$_POST['city']       = ''; //$addslashes($_POST['city']);
		$_POST['province']   = ''; //$addslashes($_POST['province']);
		$_POST['country']    = ''; //$addslashes($_POST['country']);
		$_POST['phone']      = ''; //$addslashes($_POST['phone']);
		$_POST['email3'] = $addslashes($_POST['email3']);

		if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION) {
			$status = AT_STATUS_UNCONFIRMED;
		} else if (defined('AUTO_APPROVE_INSTRUCTORS') && AUTO_APPROVE_INSTRUCTORS) {
			$status = AT_STATUS_INSTRUCTOR;
		} else {
			$status = AT_STATUS_STUDENT;
		}
		$now = date('Y-m-d H:i:s'); // we use this later for the email confirmation.

		/* insert into the db. (the last 0 for status) */
		$sql = "INSERT INTO ".TABLE_PREFIX."members VALUES (0,'$_POST[login]','$_POST[password]','$_POST[email]','$_POST[website]','$_POST[first_name]','$_POST[second_name]','$_POST[last_name]', '$dob', '$_POST[gender]', '$_POST[address]','$_POST[postal]','$_POST[city]','$_POST[province]','$_POST[country]', '$_POST[phone]', $status, '$_config[pref_defaults]', '$now','$_SESSION[lang]', $_config[pref_inbox_notify], $_POST[private_email], '$_POST[email3]')";

		/* insert into the db */
		//$sql = "INSERT INTO ".TABLE_PREFIX."members VALUES (0,'$_POST[login]','$_POST[password]','$_POST[email]','$_POST[website]','$_POST[first_name]','$_POST[second_name]','$_POST[last_name]', '$dob', '$_POST[gender]', '$_POST[address]','$_POST[postal]','$_POST[city]','$_POST[province]','$_POST[country]', '$_POST[phone]', $status, '$_config[pref_defaults]', '$now','$_SESSION[lang]', $_config[pref_inbox_notify], $_POST[private_email])";

		$result = mysql_query($sql, $db);
		$m_id	= mysql_insert_id($db);
		if (!$result) {
			require(AT_INCLUDE_PATH.'header.inc.php');
			$msg->addError('DB_NOT_UPDATED');
			$msg->printAll();
			require(AT_INCLUDE_PATH.'footer.inc.php');
			exit;
		}

		if (isset($master_list_sql)) {
			mysql_query($master_list_sql, $db);
		}
		
		if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION) {
			$msg->addFeedback('REG_THANKS_CONFIRM');

			$code = substr(md5($_POST['email'] . $now . $m_id), 0, 10);
			$confirmation_link = $_base_href . 'confirm.php?id='.$m_id.SEP.'m='.$code;

			/* send the email confirmation message: */
			require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');
			$mail = new ATutorMailer();

			$mail->From     = $_config['contact_email'];
			$mail->AddAddress($_POST['email']);
			$mail->Subject = SITE_NAME . ' - ' . _AT('email_confirmation_subject');
			$mail->Body    = _AT('email_confirmation_message', SITE_NAME, $confirmation_link);

			$mail->Send();

		} else {
			$msg->addFeedback('REG_THANKS');
		}

		unset($_SESSION['register_attempts']);
		require(AT_INCLUDE_PATH.'header.inc.php');

		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	} else {
		$_SESSION['register_attempts']++;

		if ($_SESSION['register_attempts'] == FHA_ATTEMPTS) {
			// note: if this IF statement executes, it is the 3rd failed attempt.
			$last_attempt = time();
			$cookie_expire = time()+31536000; // Expire in 1 year.
			setcookie('FHA_REGISTER', $last_attempt, $cookie_expire);
			$msg->addError('REGISTRATION_LOCKED');
			require(AT_INCLUDE_PATH.'header.inc.php');
			require(AT_INCLUDE_PATH.'footer.inc.php');
			exit;
		} else if ($_SESSION['register_attempts'] == FHA_ATTEMPTS-1) {
			$msg->addError('REGISTRATION_LOCK_WARNING2');
		} else {
			$msg->addError('REGISTRATION_LOCK_WARNING1');
		}

	}
}

unset($_SESSION['member_id']);
unset($_SESSION['valid_user']);
unset($_SESSION['login']);
unset($_SESSION['is_admin']);
unset($_SESSION['course_id']);
unset($_SESSION['is_guest']);

$_SESSION['secret'] = mt_rand(1000, 9999);

/*****************************/
/* template starts down here */

if (defined('AT_MASTER_LIST') && AT_MASTER_LIST) {
	$onload = 'document.form.student_id.focus();';
} else {
	$onload = 'document.form.login.focus();';
}

$savant->assign('languageManager', $languageManager);
$savant->assign('FHA_reg_feedback', $FHA_reg_feedback);


$savant->display('registration.tmpl.php');

?>