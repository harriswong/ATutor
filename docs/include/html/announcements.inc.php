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

if (!defined('AT_INCLUDE_PATH')) { exit; }

?>
	<ol>
		<li><a href="<?php echo $_base_path; ?>search.php">Search</a></li>
		<li><a href="<?php echo $_base_path; ?>sitemap.php">Site-Map</a></li>
		<li><a href="<?php echo $_base_path; ?>export.php"><?php echo _AT('export_content'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>discussions/achat/index.php"><?php echo _AT('chat'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>links/index.php"><?php echo _AT('links'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>resources/tile/index.php"><?php echo _AT('tile_search'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>glossary/index.php"><?php echo _AT('glossary'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>tools/tracker.php"><?php echo _AT('my_tracker'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>tools/my_tests.php"><?php echo _AT('my_tests'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>forum/list.php"><?php echo _AT('forums'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>polls/index.php"><?php echo _AT('polls'); ?></a></li>
		<li><a href="<?php echo $_base_path; ?>acollab.php">ACollab</a></li>
	</ol>
<?php

	require_once(AT_INCLUDE_PATH.'lib/test_result_functions.inc.php');
	
	if (!authenticate(AT_PRIV_ANNOUNCEMENTS, AT_PRIV_RETURN) && $_SESSION['enroll'] == AT_ENROLL_NO) {
		echo '<small> - ';
		echo '<a href="'.$_base_path.'enroll.php?course='.$_SESSION['course_id'].'">'._AT('enroll').'</a></small>';
	}

	/* help for content pages */

	if (defined('AT_SHOW_TEST_BOX') && AT_SHOW_TEST_BOX) {
		// print new available tests
		
		$sql	= "SELECT T.test_id, T.title FROM ".TABLE_PREFIX."tests T WHERE T.course_id=$_SESSION[course_id] AND T.start_date<=NOW() AND T.end_date>= NOW() ORDER BY T.start_date, T.title";
		$result	= mysql_query($sql, $db);
		$num_tests = mysql_num_rows($result);
		$tests = '';
		while (($row = mysql_fetch_assoc($result)) && authenticate_test($row['test_id'])) {
			$tests .= '<a href="'.$_base_path.'tools/take_test.php?tid='.$row['test_id'].'">'.$row['title'].'</a><br />';
		} 

		if ($tests) { ?>
				<table border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td class="test-box"><small><a href="<?php echo $_base_href ?>tools/my_tests.php?g=32"><?php echo _AT('curren_tests_surveys'); ?></a></small></td>
				</tr>
				<tr>
					<td class="dropdown"><?php echo $tests; ?></td>
				</tr>
				</table><br />
		<?php 
		}
	}

	$sql	= "SELECT COUNT(*) AS cnt FROM ".TABLE_PREFIX."news WHERE course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {	
		$num_results = $row['cnt'];
		$results_per_page = NUM_ANNOUNCEMENTS;
		$num_pages = ceil($num_results / $results_per_page);
		$page = intval($_GET['p']);
		if (!$page) {
			$page = 1;
		}	
		$count = (($page-1) * $results_per_page) + 1;

		$offset = ($page-1)*$results_per_page;

		$sql = "SELECT N.* FROM ".TABLE_PREFIX."news N WHERE N.course_id=$_SESSION[course_id] ORDER BY date DESC LIMIT $offset, $results_per_page";
	}

	$result = mysql_query($sql, $db);
	if (mysql_num_rows($result) == 0) {
		echo '<em>'._AT('no_announcements').'</em>';
	} else {
		$news = array();
		while ($row = mysql_fetch_assoc($result)) {
			/* this can't be cached because it called _AT */

			$news[$row['news_id']] = array(
							'date'		=> AT_date(	_AT('announcement_date_format'), 
													$row['date'], 
													AT_DATE_MYSQL_DATETIME),
 							'title'		=> AT_print($row['title'], 'news.title'),
							'body'		=> AT_print($row['body'], 'news.body', $row['formatting']));
					

		}

		echo '<table border="0" cellspacing="1" cellpadding="0" width="98%" summary="">';
		
		foreach ($news as $news_id => $news_item) {
			echo '<tr>';
			echo '<td>';
			echo '<br /><h4>'.$news_item['title'];
			/*
			unset($editors);
			$editors[] = array('priv' => AT_PRIV_ANNOUNCEMENTS, 'title' => _AT('edit'), 'url' => $_base_path.'editor/edit_news.php?aid='.$news_id);
			$editors[] = array('priv' => AT_PRIV_ANNOUNCEMENTS, 'title' => _AT('delete'), 'url' => $_base_path.'editor/delete_news.php?aid='.$news_id);
			print_editor($editors , $large = false);
			*/

			echo '</h4>';

			echo $news_item['body'];

			echo '<br /><small class="date">'._AT('posted').' '.$news_item['date'].'</small>';
			echo '</td>';
			echo '</tr>';
			echo '<tr><td class="row3" height="1"><img src="'.$_base_path.'images/clr.gif" height="1" width="1" alt="" /></td></tr>';
		}
		echo '</table><br />';
		if($num_pages>1) {
			echo _AT('page').': | ';
			for ($i=1; $i<=$num_pages; $i++) {
				if ($i == $page) {
					echo '<strong>'.$i.'</strong>';
				} else {
					echo '<a href="'.$_SERVER['PHP_SELF'].'?p='.$i.'">'.$i.'</a>';
				}
				echo ' | ';
			}
		}
	}


?>
