<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

function checkUserInfo($record) {
	global $db, $addslashes;

	if(empty($record['remove'])) {
		$record['remove'] = FALSE;			
	}

	//error flags for this record
	$record['err_email'] = FALSE;
	$record['err_uname'] = FALSE;
	$record['exists']    = FALSE;

	$record['email'] = trim($record['email']);

	/* email check */
	if ($record['email'] == '') {
		$record['err_email'] = _AT('import_err_email_missing');
	} else if (!eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$", $record['email'])) {
		$record['err_email'] = _AT('import_err_email_invalid');
	}

	$record['email'] = $addslashes($record['email']);

	$sql="SELECT * FROM ".TABLE_PREFIX."members WHERE email LIKE '$record[email]'";
	$result = mysql_query($sql,$db);
	if (mysql_num_rows($result) != 0) {
		$row = mysql_fetch_assoc($result);
		$record['exists'] = _AT('import_err_email_exists');
		$record['fname']  = $row['first_name']; 
		$record['lname']  = $row['last_name'];
		$record['email']  = $row['email'];
		$record['uname']  = $row['login'];
		$record['status'] = $row['status'];
	}

	/* username check */
	if (empty($record['uname'])) {
		$record['uname'] = stripslashes (strtolower (substr ($record['fname'], 0, 1).$_POST['sep_choice'].$record['lname']));
	} 		

	$record['uname'] = preg_replace("{[^a-zA-Z0-9._-]}","", trim($record['uname']));

	if (!(eregi("^[a-zA-Z0-9._-]([a-zA-Z0-9._-])*$", $record['uname']))) {
		$record['err_uname'] = _AT('import_err_username_invalid');
	} 

	if (isset($record['status']) && $record['status'] == AT_STATUS_DISABLED) {
		$record['err_disabled'] = true;
	} else {
		$record['err_disabled'] = false;
	}

	$record['uname'] = $addslashes($record['uname']);

	$sql = "SELECT member_id FROM ".TABLE_PREFIX."members WHERE login='$record[uname]'";
	$result = mysql_query($sql,$db);
	if ((mysql_num_rows($result) != 0) && !$record['exists']) {
		$record['err_uname'] = _AT('import_err_username_exists');
	} else {
		$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."admins WHERE login='$record[uname]'",$db);
		if (mysql_num_rows($result) != 0) {
			$record['err_uname'] = _AT('import_err_username_exists');
		}
	}	

	$sql = "SELECT member_id FROM ".TABLE_PREFIX."members WHERE first_name='$record[fname]' AND last_name='$record[lname]' LIMIT 1";
	$result = mysql_query($sql,$db);
	if ((mysql_num_rows($result) != 0) && !$record['exists']) {
		$record['err_uname'] = _AT('import_err_full_name_exists');
	}

	/* removed record? */
	if ($record['remove']) {
		//unset errors 
		$record['err_email'] = '';
		$record['err_uname'] = '';
		$record['err_disabled'] = '';
	}

	$record['fname'] = htmlspecialchars(stripslashes(trim($record['fname'])));
	$record['lname'] = htmlspecialchars(stripslashes(trim($record['lname'])));
	$record['email'] = htmlspecialchars(stripslashes(trim($record['email'])));
	$record['uname'] = htmlspecialchars(stripslashes(trim($record['uname'])));

	return $record;
}

function add_users($user_list, $enroll, $course) {
	global $db, $_base_href;
	global $msg;
	global $_config;

	require_once(AT_INCLUDE_PATH.'classes/phpmailer/atutormailer.class.php');

	if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION) {
		$status = AT_STATUS_UNCONFIRMED;
	} else {
		$status = AT_STATUS_STUDENT;
	}


	foreach ($user_list as $student) {
		if (!$student['remove'])  {

			if (!$student['exists']) {
				$student = sql_quote($student);
				$now = date('Y-m-d H:i:s'); // we use this later for the email confirmation.

		
				$sql = "INSERT INTO ".TABLE_PREFIX."members VALUES (0,'$student[uname]','$student[uname]','$student[email]','','$student[fname]','', '$student[lname]', '', '', '','','','','', '', $status, '$_config[pref_defaults]', '$now','$_config[default_language]', $_config[pref_inbox_notify], 0, 1)";

				if ($result = mysql_query($sql, $db)) {
					$student['exists'] = _AT('import_err_email_exists');
					$m_id = mysql_insert_id($db);

					$sql = "INSERT INTO ".TABLE_PREFIX."course_enrollment (member_id, course_id, approved, last_cid) VALUES ($m_id, $course, '$enroll', 0)";

					if ($result = mysql_query($sql,$db)) {
						$enrolled_list .= '<li>' . $student['uname'] . '</li>';

						if (defined('AT_EMAIL_CONFIRMATION') && AT_EMAIL_CONFIRMATION) {

							// send email here.
							$code = substr(md5($student['email'] . $now . $m_id), 0, 10);
							$confirmation_link = $_base_href . 'confirm.php?id='.$m_id.SEP.'m='.$code;
			
							$subject = $_config['site_name'].': '._AT('email_confirmation_subject');
							$body = _AT(array('new_account_enroll_confirm', $_SESSION['course_title'], $confirmation_link))."\n\n";
						} else {
							$subject = $_config['site_name'].': '._AT('account_information');
							$body = _AT(array('new_account_enroll',$_base_href, $_SESSION['course_title']))."\n\n";
						}
						
						//$body .= SITE_NAME.': '._AT('account_information')."\n";
						$body .= _AT('web_site') .' : '.$_base_href."\n";
						$body .= _AT('login_name') .' : '.$student['uname'] . "\n";
						$body .= _AT('password') .' : '.$student['uname'] . "\n";

						$mail = new ATutorMailer;
						$mail->From     = $_config['contact_email'];
						$mail->AddAddress($student['email']);
						$mail->Subject = $subject;
						$mail->Body    = $body;
						$mail->Send();

						unset($mail);
					} else {
						$already_enrolled .= '<li>' . $student['uname'] . '</li>';
					}
				} else {
					//$msg->addError('LIST_IMPORT_FAILED');	
				}
			} else if (! $student['err_disabled']) {
				$sql = "SELECT member_id FROM ".TABLE_PREFIX."members WHERE email='$student[email]'";
				$result = mysql_query($sql, $db);
				if ($row = mysql_fetch_assoc($result)) {
				
					$m_id = $row['member_id'];

					$sql = "INSERT INTO ".TABLE_PREFIX."course_enrollment (member_id, course_id, approved, last_cid, role) VALUES ($m_id, $course, '$enroll', 0, '$role')";

					if($result = mysql_query($sql,$db)) {
						$enrolled_list .= '<li>' . $student['uname'] . '</li>';
					} else {
						$sql = "REPLACE INTO ".TABLE_PREFIX."course_enrollment (member_id, course_id, approved, last_cid, role) VALUES ($m_id, $course, '$enroll', 0, '$role')";
						$result = mysql_query($sql,$db);
						$enrolled_list .= '<li>' . $student['uname'] . '</li>';
					}
				}
			} else if ($student['err_disabled']) {
				$not_enrolled_list .= '<li>' . $student['uname'] . '</li>';
			}
		}
	}
	if ($already_enrolled) {
		$feedback = array('ALREADY_ENROLLED', $already_enrolled);
		$msg->addFeedback($feedback);
	}
	if ($enrolled_list) {
		$feedback = array('ENROLLED', $enrolled_list);
		$msg->addFeedback($feedback);
	}
	if ($not_enrolled_list) {
		$feedback = array('NOT_ENROLLED', $not_enrolled_list);
		$msg->addFeedback($feedback);
	}
}

?>