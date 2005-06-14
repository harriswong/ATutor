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

$page	 = 'password_reminder';
$_user_location	= 'public';
define('AT_INCLUDE_PATH', 'include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

if (isset($_POST['cancel'])) {
	header('Location: about.php');
	exit;
} else if (isset($_POST['form_password_reminder'])) {
	$sql	= "SELECT login, password, email FROM ".TABLE_PREFIX."members WHERE email='$_POST[form_email]'";
	$result = mysql_query($sql,$db);
	if (mysql_num_rows($result) == 0) {
		$msg->addError('EMAIL_NOT_FOUND');
	} else {
		$row = mysql_fetch_array($result);
		$r_login = $row['login'];	
		$r_passwd= $row['password'];
		$r_email = $row['email'];

		$message = _AT('hello').','."\n"._AT('password_request2')."\n".$HTTP_SERVER_VARS["REMOTE_ADDR"].'.'."\n";
		$message .= _AT('login_name').': '.$r_login."\n"._AT('password').': '.$r_passwd."\n";

		require(AT_INCLUDE_PATH . 'classes/phpmailer/atutormailer.class.php');

		$mail = new ATutorMailer;

		$mail->From     = EMAIL;
		$mail->AddAddress($r_email);
		$mail->Subject = 'FHA Online Learning System Reminder';
		$mail->Body    = $message;

		if(!$mail->Send()) {
		   //echo 'There was an error sending the message';
		   $msg->printErrors('SENDING_ERROR');
		   exit;
		}

		unset($mail);


		$success = true;
	}
}

/*****************************/
/* template starts down here */

$onload = 'document.form.form_email.focus();';

if ($errors || !$success) {
	$savant->display('password_reminder.tmpl.php');
} else {
	$savant->display('password_reminder_feedback.tmpl.php');
}

?>