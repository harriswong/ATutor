<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2009										*/
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: menu_inline_editor_submit.php 8810 2009-10-05 14:43:50Z cindy $

define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

global $db;

if (trim($_POST['field']) <> "" && trim($_POST['value']) <> "")
{
	$fields = explode('|', $_POST['field']);
	$content_id = intval($fields[1]);
	
	if ($content_id > 0)
	{
		$sql	= "UPDATE ".TABLE_PREFIX."content SET title='".$addslashes($_POST['value'])."' WHERE content_id=$content_id";
		$result = mysql_query($sql, $db);
		$row	= mysql_fetch_array($result);
	}
}
?>