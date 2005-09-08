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
// $Id$

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'lib/filemanager.inc.php');

if (!authenticate(AT_PRIV_FILES,AT_PRIV_RETURN)) {
	authenticate(AT_PRIV_CONTENT);
}


$popup = $_REQUEST['popup'];
$framed = $_REQUEST['framed'];

/* get this courses MaxQuota and MaxFileSize: */
$sql	= "SELECT max_quota, max_file_size FROM ".TABLE_PREFIX."courses WHERE course_id=$_SESSION[course_id]";
$result = mysql_query($sql, $db);
$row	= mysql_fetch_array($result);
$my_MaxCourseSize	= $row['max_quota'];
$my_MaxFileSize	= $row['max_file_size'];

	if ($my_MaxCourseSize == AT_COURSESIZE_DEFAULT) {
		$my_MaxCourseSize = $MaxCourseSize;
	}
	if ($my_MaxFileSize == AT_FILESIZE_DEFAULT) {
		$my_MaxFileSize = $MaxFileSize;
	} else if ($my_MaxFileSize == AT_FILESIZE_SYSTEM_MAX) {
		$my_MaxFileSize = megabytes_to_bytes(substr(ini_get('upload_max_filesize'), 0, -1));
	}

$path = AT_CONTENT_DIR . $_SESSION['course_id'].'/'.$_POST['pathext'];

if (isset($_POST['submit'])) {

	if($_FILES['uploadedfile']['name'])	{

		$_FILES['uploadedfile']['name'] = trim($_FILES['uploadedfile']['name']);
		$_FILES['uploadedfile']['name'] = str_replace(' ', '_', $_FILES['uploadedfile']['name']);

		$path_parts = pathinfo($_FILES['uploadedfile']['name']);
		$ext = $path_parts['extension'];

		/* check if this file extension is allowed: */
		/* $IllegalExtentions is defined in ./include/config.inc.php */
		if (in_array($ext, $IllegalExtentions)) {
			$_SESSION['done'] = 1;

			$errors = array('FILE_ILLEGAL', $ext);
			$msg->addError($errors);
			header('Location: index.php?pathext='.$_POST['pathext']);
			exit;
		}

		/* also have to handle the 'application/x-zip-compressed'  case	*/
		if (   ($_FILES['uploadedfile']['type'] == 'application/x-zip-compressed')
			|| ($_FILES['uploadedfile']['type'] == 'application/zip')
			|| ($_FILES['uploadedfile']['type'] == 'application/x-zip')){
			$is_zip = true;						
		}

	
		/* anything else should be okay, since we're on *nix.. hopefully */
		$_FILES['uploadedfile']['name'] = str_replace(array(' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|', '\''), '', $_FILES['uploadedfile']['name']);


		/* if the file size is within allowed limits */
		if( ($_FILES['uploadedfile']['size'] > 0) && ($_FILES['uploadedfile']['size'] <= $my_MaxFileSize) ) {

			/* if adding the file will not exceed the maximum allowed total */
			$course_total = dirsize($path);

			if ((($course_total + $_FILES['uploadedfile']['size']) <= ($my_MaxCourseSize + $MaxCourseFloat)) || ($my_MaxCourseSize == AT_COURSESIZE_UNLIMITED)) {

				/* check if this file exists first */
				if (file_exists($path.$_FILES['uploadedfile']['name'])) {
					/* this file already exists, so we want to prompt for override */

					/* save it somewhere else, temporarily first			*/
					/* file_name.time ? */
					$_FILES['uploadedfile']['name'] = substr(time(), -4).'.'.$_FILES['uploadedfile']['name'];

					$f = array('FILE_EXISTS',
									substr($_FILES['uploadedfile']['name'], 5), 
									$_FILES['uploadedfile']['name'],
									$_POST['pathext'],
									$_GET['popup'],
									SEP);
					$msg->addFeedback($f);
				}

				/* copy the file in the directory */
				$result = move_uploaded_file( $_FILES['uploadedfile']['tmp_name'], $path.$_FILES['uploadedfile']['name'] );

				if (!$result) {
					require(AT_INCLUDE_PATH.'header.inc.php');
					$msg->printErrors('FILE_NOT_SAVED');
					echo '<a href="tools/filemanager/index.php?pathext=' . $_POST['pathext'] . SEP . 'popup=' . $_GET['popup'] . '">' . _AT('back') . '</a>';
					require(AT_INCLUDE_PATH.'footer.inc.php');
					exit;
				} else {
					if ($is_zip) {
						$f = array('FILE_UPLOADED_ZIP',
										urlencode($_POST['pathext']), 
										urlencode($_FILES['uploadedfile']['name']), 
										$_GET['popup'],
										SEP);
						$msg->addFeedback($f);
						
						$_SESSION['done'] = 1;
						header('Location: index.php?pathext=' . $_POST['pathext'] . SEP . 'popup=' . $_GET['popup']);
						exit;
					} /* else */

					$msg->addFeedback('FILE_UPLOADED');

					$_SESSION['done'] = 1;
					header('Location: index.php?pathext='.$_POST['pathext'].SEP.'popup='.$_GET['popup']);
					exit;
				}
			} else {
				$_SESSION['done'] = 1;
				$msg->addError(array('MAX_STORAGE_EXCEEDED', get_human_size($my_MaxCourseSize)));
				header('Location: index.php?pathext='.$_POST['pathext'].SEP.'popup='.$_GET['popup']);
				exit;
			}
		} else {
			$_SESSION['done'] = 1;
			$msg->addError(array('FILE_TOO_BIG', get_human_size($my_MaxFileSize)));
			session_write_close();

			header('Location: index.php?pathext='.$_POST['pathext'].SEP.'popup='.$_GET['popup']);
			exit;
		}
	} else {
		$_SESSION['done'] = 1;
		$msg->addError('FILE_NOT_SELECTED');
		header('Location: index.php?pathext='.$_POST['pathext'].SEP.'popup='.$_GET['popup']);
		exit;
	}
}


?>