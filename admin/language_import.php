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
// $Id$

$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }

require(AT_INCLUDE_PATH.'classes/pclzip.lib.php');
require_once(AT_INCLUDE_PATH.'classes/Language/LanguageEditor.class.php');
require_once(AT_INCLUDE_PATH.'classes/Language/LanguagesParser.class.php');

require_once(AT_INCLUDE_PATH.'classes/Message/Message.class.php');

global $savant;
$msg =& new Message($savant);

/* to avoid timing out on large files */
set_time_limit(0);


if (isset($_POST['submit_import'])) {
	require_once(AT_INCLUDE_PATH.'classes/Language/RemoteLanguageManager.class.php');
	$remoteLanguageManager =& new RemoteLanguageManager();
	$filename = AT_CONTENT_DIR . 'import/ATutor_language_file.zip';
	$remoteLanguageManager->fetchLanguage($_POST['language'], $filename);

	$_FILES['file']['name']     = 'ATutor_language_file.zip';
	$_FILES['file']['tmp_name'] = $filename;

} else if (!is_uploaded_file($_FILES['file']['tmp_name']) || !$_FILES['file']['size']) {
	$_SESSION['done'] = 1;

	require(AT_INCLUDE_PATH.'header.inc.php');
	$msg->addError('LANG_IMPORT_FAILED');
	
	@unlink($import_path . 'language.csv');
	
	$msg->printErrors();
	require(AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}

$_SESSION['done'] = 1;

if (!$_FILES['file']['name']) {
	require(AT_INCLUDE_PATH.'header.inc.php');
	$msg->printErrors('IMPORTFILE_EMPTY');
	
	require(AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}

/* check if ../content/import/ exists */
$import_path = AT_CONTENT_DIR . 'import/';

if (!is_dir($import_path)) {
	if (!@mkdir($import_path, 0700)) {
		require(AT_INCLUDE_PATH.'header.inc.php');
		$msg->printErrors('IMPORTDIR_FAILED');
		
		require(AT_INCLUDE_PATH.'footer.inc.php'); 
		exit;
	}
}

$import_path = AT_CONTENT_DIR . 'import/';
$archive = new PclZip($_FILES['file']['tmp_name']);
if ($archive->extract(	PCLZIP_OPT_PATH,	$import_path) == 0) {
	exit('Error : ' . $archive->errorInfo(true));
}

$language_xml = @file_get_contents($import_path.'language.xml');

$languageParser =& new LanguageParser();
$languageParser->parse($language_xml);
$languageEditor =& $languageParser->getLanguageEditor(0);

if ($languageManager->exists($languageEditor->getCode(), $languageEditor->getLocale())) {
	require(AT_INCLUDE_PATH.'header.inc.php'); 
	$msg->printErrors('LANG_EXISTS');
	
	require(AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
} // else:

$languageEditor->import($import_path . 'language_text.sql');

// remove the files:
@unlink($import_path . 'language.xml');
@unlink($import_path . 'language_text.sql');
@unlink($import_path . 'readme.txt');
@unlink($_FILES['file']['tmp_name']);

$msg->addFeedback('IMPORT_LANG_SUCCESS');
header('Location: language.php');
exit;

?>