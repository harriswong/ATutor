<?php
/************************************************************************/
/* ATutor														        */
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg & Boon-Hau Teh */
/* Adaptive Technology Resource Centre / University of Toronto          */
/* http://atutor.ca												        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.				        */
/************************************************************************/
// $Id$

// NOTE! please see include/html/search.inc.php NOTE!

function score_cmp($a, $b) {
    if ($a['score'] == $b['score']) {
        return 0;
    }
    return ($a['score'] > $b['score']) ? -1 : 1;
}

function get_search_result($words, $predicate, $course_id, &$num_found, &$total_score) {
	global $addslashes, $db, $highlight_system_courses;

	$search_results = array();
	$lower_words    = array();

	$predicate = " $predicate "; // either 'AND' or 'OR'

	$words = explode(' ',$words);
	$num_words = count($words);
	$course_score = 0;
	for ($i=0; $i<$num_words; $i++) {
		$lower_words[$i] = strtolower($words[$i]);

		if ($words_sql) {
			$words_sql .= $predicate;
		}
		$words[$i] = $addslashes($words[$i]);
		$words_sql .= ' (C.title LIKE "%'.$words[$i].'%" OR C.text LIKE "%'.$words[$i].'%" OR C.keywords LIKE "%'.$words[$i].'%")';

		/* search through the course title and description keeping track of its total */
		$course_score += 15 * substr_count(strtolower($highlight_system_courses[$course_id]['title']),       $lower_words[$i]);
		$course_score += 12 * substr_count(strtolower($highlight_system_courses[$course_id]['description']), $lower_words[$i]);

		$highlight_system_courses[$course_id]['title']       = highlight($highlight_system_courses[$course_id]['title'],       $words[$i]);
		$highlight_system_courses[$course_id]['description'] = highlight($highlight_system_courses[$course_id]['description'], $words[$i]);
	}

	$sql =  'SELECT C.last_modified, C.course_id, C.content_id, C.title, C.text, C.keywords FROM '.TABLE_PREFIX.'content AS C WHERE C.course_id='.$course_id;
	$sql .= ' AND ('.$words_sql.') LIMIT 200';
	
	$result = mysql_query($sql, $db);
	while($row = mysql_fetch_assoc($result)) {
		$score = 0;

		$row['title'] = strip_tags($row['title']);
		$row['text']  = strip_tags($row['text']);

		$lower_title     = strtolower($row['title']);
		$lower_text		 = strtolower($row['text']);
		$lower_keywords  = strtolower($row['keywords']);

		if (strlen($row['text']) > 270) {
			$row['text']  = substr($row['text'], 0, 268).'...';
		}

		for ($i=0; $i<$num_words; $i++) {
			$score += 8 * substr_count($lower_keywords, $lower_words[$i]); /* keywords are weighed more */
			$score += 4 * substr_count($lower_title,    $lower_words[$i]);    /* titles are weighed more */
			$score += 1 * substr_count($lower_text,     $lower_words[$i]);

			$row['title']	  = highlight($row['title'],	$words[$i]);
			$row['text']	  = highlight($row['text'],		$words[$i]);
			$row['keywords']  = highlight($row['keywords'], $words[$i]);

		}
		if ($score != 0) {
			$score += $course_score;
		}
		$row['score'] = $score;
		$search_results[] = $row;

		$total_score += $score;
	}

	if ($total_score == 0) {
		$total_score = $course_score;
	}

	if ((count($search_results) == 0) && $course_score && ($_GET['display_as'] != 'pages')) {
		$num_found++;
	}

	$num_found += count($search_results);

	return $search_results;
}


// My Courses - All courses you're enrolled in (including hidden)
function get_my_courses($member_id) {
	global $db;

	$list = array();

	$sql = "SELECT course_id FROM ".TABLE_PREFIX."course_enrollment WHERE member_id=$member_id AND (approved='y' OR approved='a')";
	$result = mysql_query($sql, $db);
	while ($row = mysql_fetch_assoc($result)) {
		$list[] = $row['course_id']; // list contains all the Course IDs
	}

	return $list;
}


// All courses (display hidden too if you're enrolled in it)
function get_all_courses($member_id) {
	global $system_courses, $db;

	$list = array();

	$num_courses = count($system_courses);

	// add all the courses that are not hidden,then find the hidden courses that you're enrolled in and then add that to array
	foreach ($system_courses as $course_id => $course_info) {
		if (!$course_info['hide']) {
			$list[] = $course_id;
		}
	}

	// if there aren't any hidden courses:
	if (count($system_courses) == count($list)) {
		return $list;
	}

	if ($_SESSION['valid_user']) {
		$my_courses = implode(',', get_my_courses($member_id));
		$sql = "SELECT course_id FROM ".TABLE_PREFIX."courses WHERE hide=1 AND course_id IN (0, $my_courses)";
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_assoc($result)) {
			$list[] = $row['course_id'];
		}
	}
	return $list;
}

function print_search_pages($result) {
	global $count;

	foreach ($result as $items) {
		uasort($result, 'score_cmp');

		echo '<h5>' . $count . '. ';
		
		if ($_SESSION['course_id'] != $items['course_id']) {

			echo '<a href="bounce.php?course='.$items['course_id'].SEP.'p='.urlencode('index.php?cid='.$items['content_id'].SEP.'words='.$_GET['words']).'">'.$items['title'].'</a> ';
		} else {
			echo '<a href="?cid='.$items['content_id'].SEP.'words='.$_GET['words'].'">'.$items['title'].'</a> ';
		}
		echo '</h5>'."\n";

		echo '<p><small>'.$items['text'];

		echo '<br /><small class="search-info">[<strong>'._AT('keywords').':</strong> ';
		if ($items['keywords']) {
			echo $items['keywords'];
		} else {
			echo '<em>'._AT('none').'</em>';
		}

		echo '. <strong>'._AT('updated').':</strong> ';
		echo AT_date(_AT('inbox_date_format'), $items['last_modified'], AT_DATE_MYSQL_DATETIME);

		echo ']</small>';

		echo '</small></p>'."\n";
		$count++;
	}
}

?>