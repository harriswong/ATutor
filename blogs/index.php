<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay & Joel Kronenberg        */
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
require_once(AT_INCLUDE_PATH .'classes/subscribe.class.php');

$sub = new subscription();

if (isset($_GET)){
	if ($_GET['subscribe'] == "set"){
		if($sub->set_subscription('blog',$_SESSION['member_id'],$_GET['group_id'])){;
			$msg->addFeedback('BLOG_SUBSCRIBED');
		}
	} else if ($_GET['subscribe'] == "unset"){
		$sub->unset_subscription('blog',$_SESSION['member_id'],$_GET['group_id']);
		$msg->addFeedback('BLOG_UNSUBSCRIBED');
	}
}

require (AT_INCLUDE_PATH.'header.inc.php');

$sql = "SELECT G.group_id, G.title, G.modules FROM ".TABLE_PREFIX."groups G INNER JOIN ".TABLE_PREFIX."groups_types T USING (type_id) WHERE T.course_id=$_SESSION[course_id] ORDER BY G.title";
$result = mysql_query($sql, $db);

echo '<ol id="tools">';

$blogs = false;
while ($row = mysql_fetch_assoc($result)) {
	if (strpos($row['modules'], '_standard/blogs') !== FALSE) {
		// retrieve the last posted date/time from this blog
		$sql = "SELECT MAX(date) AS date FROM ".TABLE_PREFIX."blog_posts WHERE owner_type=".BLOGS_GROUP." AND owner_id={$row['group_id']}";
		$date_result = mysql_query($sql, $db);
		if (($date_row = mysql_fetch_assoc($date_result)) && $date_row['date']) {
			$last_updated = ' - ' . _AT('last_updated', AT_date(_AT('forum_date_format'), $date_row['date'], AT_DATE_MYSQL_DATETIME));
		} else {
			$last_updated = '';
		}

		echo '<li class="top-tool" style="position:relative;"><span style="position:absolute; left:4em;"><a href="'.url_rewrite('blogs/view.php?ot='.BLOGS_GROUP.SEP.'oid='.$row['group_id']).'">'.$row['title'].$last_updated.'</a></span><span style="position:absolute; right:1em;">';
		
		// Check if subscribed and make appropriate button
		if ($sub->is_subscribed('blog',$_SESSION['member_id'],$row['group_id'])){
			echo '<a href="'.$_SERVER['PHP_SELF'].'?group_id='.$row['group_id'].'&subscribe=unset"><img border="0" src="'.AT_BASE_HREF.'images/unsubscribe-envelope.png" alt="" /> '._AT('blog_unsubscribe').'</a>';
		} else {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?group_id='.$row['group_id'].'&subscribe=set"><img border="0" src="'.AT_BASE_HREF.'images/subscribe-envelope.png" alt="" /> '._AT('blog_subscribe').'</a>';		
		}
		$blogs = true;
	}
}
echo '</ol>';

if (!$blogs) {
	echo _AT('none_found');
}
?>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>