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
// $Id: member_stats.php 2734 2004-12-08 20:21:10Z joel $

define('AT_INCLUDE_PATH', './include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

require(AT_INCLUDE_PATH.'header.inc.php');

	//Table displays all content pages with no. of hits by user
	echo '<table class="data static" rules="cols" summary="">';
	echo '<thead>';
	echo '<tr>';
		echo '<th scope="col">';
			echo _AT('page_title');
		echo '</th>';
		echo '<th scope="col">';
			echo _AT('no_of_hits');
		echo '</th>';
		echo '<th scope="col">';
			echo _AT('last_accessed');
		echo '</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	$sql = "SELECT counter, last_accessed, content_id FROM ".TABLE_PREFIX."member_track WHERE member_id=$_SESSION[member_id] AND course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$sql1    = "SELECT title FROM ".TABLE_PREFIX."content WHERE content_id=$row[content_id]";
			$result1 = mysql_query($sql1, $db);
			$row1    = mysql_fetch_assoc($result1);

			echo '<tr>';
				echo '<td><a href='.$_base_href.'content.php?cid='.$row['content_id']. '>' . AT_print($row1['title'], 'content.title') . '</a></td>';
				echo '<td>' . $row['counter'] . '</td>';
				echo '<td>' . AT_date('%h:%i:%s  %d %M, %Y', $row['last_accessed'], AT_DATE_MYSQL_DATETIME) . '</td>';
			echo '</tr>';
		} //end while

		echo '</tbody>';

	} else {
		echo '<tr><td>' . _AT('tracker_data_empty') . '</td></tr>';
		echo '</tbody>';
	}
	echo '</table>';

require(AT_INCLUDE_PATH.'footer.inc.php');

?>