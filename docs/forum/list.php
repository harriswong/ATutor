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
define('AT_INCLUDE_PATH', '../include/');

require (AT_INCLUDE_PATH.'vitals.inc.php');

$_section[0][0] = _AT('discussions');
$_section[0][1] = 'discussions/';
$_section[1][0] = _AT('forums');
$_section[1][1] = 'forum/list.php';

require_once(AT_INCLUDE_PATH.'lib/forums.inc.php');
require (AT_INCLUDE_PATH.'header.inc.php');

/*
if ((authenticate(AT_PRIV_FORUMS, AT_PRIV_RETURN) || authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) && $_SESSION['prefs'][PREF_EDIT]) {
	$msg->addHelp('CREATE_FORUMS');
} else if ((authenticate(AT_PRIV_FORUMS, AT_PRIV_RETURN) || authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) && !$_SESSION['prefs'][PREF_EDIT]) {
	$help = array('ENABLE_EDITOR', $_my_uri);
	$msg->addHelp($help);
}
*/

$msg->addHelp('SHARED_FORUMS');
$msg->addHelp('SUBSCRIBE_FORUMS');
$msg->printHelps();

$msg->printAll(); // print everything but the Helps which were printed first, above

echo '<table class="data" summary="" rules="cols">';
echo '<thead><tr>';
echo '	<th scope="col" class="cat"><a name="list"></a><small>'._AT('forum').'</small> ';
/*
unset($editors);
$editors[] = array('priv' => AT_PRIV_FORUMS, 'title' => _AT('add_forum'), 'url' => 'editor/add_forum.php');
print_editor($editors , $large = false);
*/
echo '</th>';
echo '	<th scope="col"><small>'._AT('forum_topics').'</small></th>';
echo '	<th scope="col"><small>'._AT('posts').'</small></th>';
echo '	<th scope="col"><small>'._AT('last_post').'</small></th>';
echo '</tr></thead><tbody>';

$shared  = array();
$general = array();
$all_forums = get_forums($_SESSION['course_id']);
//output course forums
$num_shared    = count($all_forums['shared']);
$num_nonshared = count($all_forums['nonshared']);

if ($num_shared || $num_nonshared) {
	foreach ($all_forums as $shared => $forums) {
		if ($num_shared && $num_nonshared) {
			if ($shared == 'nonshared') {
				echo '<tr>';
				echo '<th colspan="4">' . _AT('course_forums') . '</th>';
				echo '</tr>';
			} else {
				echo '<tr>';
				echo '<th colspan="4">' . _AT('shared_forums') . '</th>';
				echo '</tr>';
			}
		}

		foreach ($forums as $row) {
			echo '<tr><td class="row1 lineL"><a href="forum/index.php?fid='.$row['forum_id'].'"><strong>'.$row['title'].'</strong></a> ';

			if ($_SESSION['enroll']) {
				$sql	= "SELECT 1 AS constant FROM ".TABLE_PREFIX."forums_subscriptions WHERE forum_id=$row[forum_id] AND member_id=$_SESSION[member_id]";
				$result1 = mysql_query($sql, $db);
				echo ' [ ';
				if ($row1 = mysql_fetch_row($result1)) {
					echo '<a href="forum/subscribe_forum.php?fid='.$row['forum_id'].SEP.'us=1">'._AT('unsubscribe1').'</a>';
				} else {
					echo '<a href="forum/subscribe_forum.php?fid='.$row['forum_id'].'">'._AT('subscribe1').'</a>';
				}
				echo ' ]';
			}

			echo '<p>'.$row['description'].'</p></td>';
			echo '<td class="row1" align="center" valign="top">'.$row['num_topics'].'</td>';
			echo '<td class="row1" align="center" valign="top">'.$row['num_posts'].'</td>';
			echo '<td class="row1 lineR" align="right" nowrap="nowrap" valign="top">';

			if ($row['last_post'] == '0000-00-00 00:00:00') {
				echo '<em>'._AT('na').'</em>';
			} else {
				echo $row['last_post'];
			}
			echo '</td>';
			echo '</tr>';
		}
	} 
} else {
	echo '<tr><td class="row1" colspan="4"><em>'._AT('no_forums').'</em></td></tr>';
}
echo '</tbody></table>';

require (AT_INCLUDE_PATH.'footer.inc.php');
?>
