<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/

$page = 'language';
$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }

require_once(AT_INCLUDE_PATH.'classes/Message/Message.class.php');

global $savant;
$msg =& new Message($savant);

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	//shozub -- is this supposed to be lang_codeinstead of delete_lang???
	header('Location: language.php?lang_code='.$_POST['delete_lang']);
	exit;
}

if (isset($_POST['submit_yes'])) {
	require_once(AT_INCLUDE_PATH . 'classes/Language/LanguageEditor.class.php');

	$lang =& $languageManager->getLanguage($_POST['lang_code']);
	$languageEditor =& new LanguageEditor($lang);
	$languageEditor->deleteLanguage();

	$msg->addFeedback('LANG_DELETED');
	header('Location: language.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

echo '<h3>'._AT('language').'</h3>';

$msg->printAll();

echo '<h4>'._AT('delete_language').'</h4>';

$language =& $languageManager->getLanguage($_GET['lang_code']);
if ($language === FALSE) {
	$msg->addError('LANG_NOT_FOUND'); // Originally AT_LANG_NOT_FOUND, make error code
	$msg->printAll();
		
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}
		
$hidden_vars['lang_code'] = $_GET['lang_code'];

$confirm = array('DELETE_LANG', $language->getEnglishName());
$msg->addConfirm($confirm, $hidden_vars);
$msg->printConfirm();
	
require(AT_INCLUDE_PATH.'footer.inc.php'); 

?>