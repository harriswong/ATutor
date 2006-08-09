<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: index.php 6407 2006-06-26 19:32:35Z heidi $
define('AT_INCLUDE_PATH', '../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
require (AT_INCLUDE_PATH.'header.inc.php');

?>
<?php
$day_in_seconds = 86400;

$today_day   = date('j');
$today_month = date('n');
$today_year  = date('Y');


if (isset($_GET['y'], $_GET['m'])) {
	$display_month = intval($_GET['m']);
	$display_year = intval($_GET['y']);
} else {
	$display_month = $today_month;
	$display_year  = $today_year;
}

/*
// for testing:
$today_day   = 31;
$today_month = 7;
$today_year  = 2006;
*/

$previous_month = $next_month = $display_month;
$previous_year  = $next_year  = $display_year;

$previous_month--;
$next_month++;

if ($previous_month < 1) {
	$previous_month = 12;
	$previous_year--;
}

if ($next_month > 12) {
	$next_month = 1;
	$next_year++;
}

// calculate first day:
$day_offset = date('w', mktime(0, 0, 0, $display_month, 1, $display_year));
$start_timestamp = mktime(0, 0, 0, $display_month, 1- $day_offset, $display_year);

$start_day = date('j', $start_timestamp);
$start_month = date('n', $start_timestamp);
$start_year = date('Y', $start_timestamp);
$start_full_date = "$start_year-$start_month-$start_day";


// calculate number of weeks in this month:
$num_days_in_month = date('t', mktime(0, 0, 0, $display_month, 1, $display_year));
$day_offset = 7- date('w', mktime(0, 0, 0, $display_month, $num_days_in_month, $display_year));
$end_timestamp = mktime(0, 0, 0, $display_month, $num_days_in_month + $day_offset, $display_year);

$end_day = date('j', $end_timestamp);
$end_month = date('n', $end_timestamp);
$end_year = date('Y', $end_timestamp);
$end_full_date = "$end_year-$end_month-$end_day";

if (($end_timestamp - $start_timestamp)/$day_in_seconds > 36) {
	$num_days = 42;
} else {
	$num_days = 35;
}

// get entries from the different calendar sources:
$entries = array();
$entries[8][2][] = array('title' => 'entry title', 'colour' => '#1e1');
//$entries[8][2][] = array('title' => 'entry title with longer title');
//$entries[8][12][] = array('title' => 'entry title');
//$entries[8][13][] = array('title' => 'entry title');

$sql = "SELECT TO_DAYS('$start_full_date') AS now_to_days";
$result = mysql_query($sql, $db);
$row = mysql_fetch_assoc($result);
$now_days = $row['now_to_days'];

$commandees =& $moduleCommander->getCommandees('calendar_source');

$class_colours = array('#c33', '#33c', '#f23aa3', '#3c3');

$num_commandees = count($commandees);
for ($i = 0; $i < $num_commandees; $i++) {
	$sql = $commandees[$i]->runCommand('calendar_source', 'get_sql', $_SESSION['course_id'], $start_full_date, $end_full_date);

	$result = mysql_query($sql, $db);
	while ($row = mysql_fetch_assoc($result)) {
		$new_row = array();
		$new_row['title'] = $row['title'];
		$new_row['tooltip'] = $commandees[$i]->getName().': '. $row['title'];
		$new_row['colour']  = $class_colours[$i];

        if (isset($row['end_month'], $row['end_year']) && $row['start_month'] == $row['end_month'] && $row['start_year'] == $row['end_year']) {
			// simple case:
            for ($j = $row['start_day']; $j <= $row['end_day']; $j++) {
				$entries[$row['start_month']][$j][] = $new_row;
			}
        } else if (isset($row['end_month'], $row['end_year'])) {
            // complicated case:
			for ($j = max($now_days, $row['start_days']); $j <= min($row['end_days'], $now_days+$num_days); $j++) {
				$tmp_month = date('n', mktime(0,0,0,1, 1+($j - 719528), 1970));
                $tmp_day = date('j', mktime(0,0,0,1, 1+($j - 719528), 1970));
				$entries[$tmp_month][$tmp_day][] = $new_row;
			}
        } else {
			// single day
			$entries[$row['start_month']][$row['start_day']][] = $new_row;
		}
	}
}

?>
<pre>
- move style sheet
- deal with bg colours
- start/end times
- entire month entries (only show at start of month)
- links to the source
</pre>
<style>
div.day {
	text-align: right;
	background-color: #eef;
	padding: 3px;
	font-size: x-small;
}
td.day {
	height: 150px;
	border-left: 1px solid #ccc;
	width: 14.28%;
	vertical-align: top;
	border-top: 1px solid #ccc;
	font-size: x-small;
}
td.day:last-child {
	border-right: 1px solid #ccc;
}
td.day:hover {
	background-color: #efe;
}
/* today: */
td.day.today {
	background-color: #ffc;
}
td.day.today div.day {
	background-color: #ddf;
	font-weight: bold;
}
/* past: */
td.day.past {
	background-color: #fafafa;
}
td.day.past div.day {
	color: #999;
}
ul.items {
	list-style: none;
	margin: 0px;
	padding: 0px;
	width: 100%;
	height: 150px;
	overflow: hidden;
}
li.item {
	border: none;
	 padding: 0px;
	 width: 100%;
	 margin: 0px;
	 margin-bottom: 1px;
}
li.item a {
	font-weight: normal;
	font-size: 1.2em;
	display: block;
	color: white;
	padding: 4px;
	background-repeat: no-repeat;
	background-position: left center;
	text-decoration: none;
}
list.item a:hover {
	color: white;
}
li.item a {
	background-color: #c33;
}
li.item.announcement a {
	background-color: #c33;
}
li.item.assignment a {
	background-color: #33c;
}
li.item.reading_list a {
	background-color: #f23aa3;
}
li.item.test a {
	background-color: #3c3;
}
</style>

<div align="center" style="margin-bottom: 5px">
	<h2><a href="<?php echo $_SERVER['PHP_SELF']; ?>?y=<?php echo $previous_year; ?>&m=<?php echo $previous_month; ?>">&lt;</a> 
	<?php echo AT_Date('%F', $display_month, AT_DATE_INDEX_VALUE). ' ' . $display_year; ?> 
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?y=<?php echo $next_year; ?>&m=<?php echo $next_month; ?>">&gt;</a></h2>
</div>

<table width="99%" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid #ccc;">
<thead>
<tr>
	<th><?php echo AT_Date('%D', 1, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 2, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 3, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 4, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 5, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 6, AT_DATE_INDEX_VALUE); ?></th>
	<th><?php echo AT_Date('%D', 7, AT_DATE_INDEX_VALUE); ?></th>
</tr>
</thead>
<tbody>
<tr>
<?php for($i = 0; $i< $num_days; $i++): ?>
	<?php
		$timestamp = mktime(0, 0, 0, $start_month, $start_day + $i, $start_year);
		$day   = date('j', $timestamp);
		$month = date('n', $timestamp);
	?>
	<?php if ($i % 7 == 0): ?>
		</tr><tr>
	<?php endif; ?>
	<?php if ($month != $display_month): ?>
		<td class="day past">
	<?php elseif ($today_month == $month && $today_day == $day): ?>
		<td class="day today">
	<?php else: ?>
		<td class="day">
	<?php endif; ?>
		<div class="day">
			<?php if ($day == 1): ?>
				<?php echo date('F', $timestamp); ?>
			<?php endif; ?>
			<?php echo $day; ?>
		</div>

	<?php if (isset($entries[$month], $entries[$month][$day])): ?>
		<ul class="items" id="i<?php echo $i; ?>">
		<?php for ($j = 0; $j<1; $j++): ?>
		<?php foreach ($entries[$month][$day] as $entry): ?>
			<li class="item"><a href="<?php echo $entry['url']; ?>" title="<?php echo $entry['tooltip']; ?>" style="background-color: <?php echo $entry['colour']; ?>"><?php echo $entry['title']; ?></a></li>
		<?php endforeach; ?>
		<?php endfor; ?>
		</ul>
	<?php endif; ?>
	</td>
<?php endfor; ?>
</tr>
</tbody>
</table>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>