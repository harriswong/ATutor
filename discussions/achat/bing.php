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

define('AT_INCLUDE_PATH', '../../include/');

	require(AT_INCLUDE_PATH.'vitals.inc.php');
	require('include/functions.inc.php');
	$chatID	 = $_GET['chatID'];
	$uniqueID= intval($_GET['uniqueID']);

	$myPrefs = getPrefs($_GET['chatID']);

	howManyMessages(&$topMsgNum, &$bottomMsgNum);
	if ($myPrefs['lastChecked'] < $topMsgNum && $myPrefs['lastRead'] < $topMsgNum) {
		$myPrefs['lastChecked'] = $topMsgNum;
		writePrefs($myPrefs, $chatID);
		print "yes\n";
	}
	print "$topMsgNum $myPrefs[lastChecked] $myPrefs[lastRead] \n";
?>