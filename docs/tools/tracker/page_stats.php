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
// $Id: page_stats.php 2734 2004-12-08 20:21:10Z joel $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_ADMIN);

require(AT_INCLUDE_PATH.'header.inc.php');

//get sorting order from user input
if ($_GET['col'] && $_GET['order']) {
	$col   = $addslashes($_GET['col']);
	$order = $addslashes($_GET['order']);
}

//set default sorting order
else {
	$col   = "counter";
	$order = "desc";
}

/*create a table that lists all the content pages and the number of time they were viewed*/
$result = $contentManager->getTrackerInfo($col, $order);

echo '<table class="data static" rules="cols" summary="">';
echo '<thead>';
echo '<tr>';

	echo '<th scope="col">';
		echo _AT('page_title');
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?col=title' . SEP . 'order=asc" title="' . _AT('title_ascending') . '"><img src="images/asc.gif" alt="' . _AT('title_ascending') . '" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>';

		echo '<a href="' . $_SERVER['PHP_SELF'] . '?col=title' . SEP . 'order=desc" title="' . _AT('title_descending') . '"><img src="images/desc.gif" alt="' . _AT('title_descending') . '" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>';
	echo '</th>';

	echo '<th scope="col">';
		echo _AT('no_of_hits');
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?col=counter' . SEP . 'order=asc" title="' . _AT('hits_ascending') . '"><img src="images/asc.gif" alt="' . _AT('hits_ascending') . '" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>';

		echo '<a href="' . $_SERVER['PHP_SELF'] . '?col=counter' . SEP . 'order=desc" title="' . _AT('hits_descending') . '"><img src="images/desc.gif" alt="' . _AT('hits_descending') . '" style="height:0.50em; width:0.83em" border="0" height="7" width="11" /></a>';
	echo '</th>';

echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		echo '<tr>';
			echo '<td><a href='.$_base_href.'?cid='.$row['content_id']. '>' . AT_print($row['title'], 'content.title') . '</a></td>';
			echo '<td>' . $row['counter'] . '</td>';
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