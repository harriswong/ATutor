<?php
/************************************************************************/
/* ATutor								*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto		*/
/* http://atutor.ca							*/
/*									*/
/* This program is free software. You can redistribute it and/or	*/
/* modify it under the terms of the GNU General Public License		*/
/* as published by the Free Software Foundation.			*/
/************************************************************************/
// $Id: index.php 2734 2004-12-08 20:21:10Z joel $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_ADMIN);

require(AT_INCLUDE_PATH.'header.inc.php');

/*create a table that lists all the content pages and the number of time they were viewed*/
$result = $contentManager->getTrackerInfo();

echo '<table class="data static" rules="cols" summary="">';
echo '<thead>';
echo '<tr>';
echo '<th>' . _AT('page_title') . '</th>';
echo '<th>' . _AT('no_of_hits') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if ($result) {
	while ($row = mysql_fetch_assoc($result)) {
		echo '<tr>';
			echo '<td>' . AT_print($row['title'], 'content.title') . '</td>';
			echo '<td>' . $row['counter'] . '</td>';
		echo '<tr>';




	} //end while
	echo '</tbody>';

} else {
	echo '<tr><td>' . _AT('tracker_data_empty') . '</td></tr>';
	echo '</tbody>';
}
echo '</table>';

require(AT_INCLUDE_PATH.'footer.inc.php');

?>