<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2008                                      */
/* Written by Greg Gay, Joel Kronenberg & Chris Ridpath         */
/* Inclusive Design Institute                                   */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

require(AT_INCLUDE_PATH.'header.inc.php');

$sql = "SELECT * FROM ".TABLE_PREFIX."reading_list WHERE course_id=$_SESSION[course_id] ORDER BY date_start";
$resultReadings = mysql_query($sql, $db);
?>

<?php if (($resultReadings != 0) && ($rowReadings = mysql_fetch_assoc($resultReadings))) : ?>

		<?php do { ?>
			<?php $id = $rowReadings['resource_id']; 


$sql = "SELECT * FROM ".TABLE_PREFIX."external_resources WHERE course_id=$_SESSION[course_id] AND resource_id=$id";
$result = mysql_query($sql, $db);
if ($row = mysql_fetch_assoc($result)){ 
	$row['type']		= intval($row['type']);
	$row['title']		= htmlentities_utf8($row['title']);
	$row['author']		= htmlentities_utf8($row['author']);
	$row['publisher']	= htmlentities_utf8($row['publisher']);
	$row['date']		= htmlentities_utf8($row['date']);
	$row['comments']	= htmlentities_utf8($row['comments']);

	if ($row['type'] == RL_TYPE_BOOK): ?>
	<div class="input-form">
		<p><?php  echo _AT('title'). ": <strong>". $row['title']. "</strong>"; ?><br/>
			<?php  echo _AT('rl_type_of_resource'). ": ". _AT($_rl_types[$row['type']]); ?><br/>
			<?php  echo _AT('author'). ": ". $row['author']; ?><br/>
			<?php  echo _AT('rl_publisher'). ": ". $row['publisher']; ?><br/>
			<?php  echo _AT('date'). ": ". $row['date']; ?><br/>
			<?php  echo _AT('rl_isbn_number'). ": ". $row['id']; ?><br/>
			<?php  echo _AT('comment'). ": ". $row['comments']; ?>
		</p>
	</div>
<?php elseif ($row['type'] == RL_TYPE_URL): ?>
	<div class="input-form">	
		<p><?php  echo _AT('title'). ": <strong>". $row['title']. "</strong>"; ?><br/>
			<?php  echo _AT('rl_type_of_resource'). ": ". _AT($_rl_types[$row['type']]); ?><br/>
			<?php echo _AT('location'). ": " ?><a href="<?php echo $row['url']?>"><?php echo $row['url']; ?></a><br/>
			<?php  echo _AT('author'). ": ". $row['author']; ?><br/>
			<?php  echo _AT('comment'). ": ". $row['comments']; ?>
			</p>
	</div>
<?php elseif ($row['type'] == RL_TYPE_HANDOUT): ?>
	<div class="input-form">	
		<p><?php  echo _AT('title'). ": <strong>". $row['title']. "</strong>"; ?><br/>
			<?php  echo _AT('rl_type_of_resource'). ": ". _AT($_rl_types[$row['type']]); ?><br/>
			<?php  echo _AT('author'). ": ". $row['author']; ?><br/>
			<?php  echo _AT('date'). ": ". $row['date']; ?><br/>
			<?php  echo _AT('comment'). ": ". $row['comments']; ?>
		</p>
	</div>
<?php elseif ($row['type'] == RL_TYPE_AV): ?>
	<div class="input-form">	
		<p><?php  echo _AT('title'). ": <strong>". $row['title']. "</strong>" ; ?><br/>
			<?php  echo _AT('rl_type_of_resource'). ": ". _AT($_rl_types[$row['type']]); ?><br/>
			<?php  echo _AT('author'). ": ". $row['author']; ?><br />
			<?php  echo _AT('date'). ": ". $row['date']; ?><br/>
			<?php  echo _AT('comment'). ": ". $row['comments']; ?>
		</p>
	</div>
<?php elseif ($row['type'] == RL_TYPE_FILE): ?>
	<div class="input-form">	
		<p><?php  echo _AT('title'). ": <strong>". $row['title']. "</strong>"; ?><br/>
			<?php  echo _AT('rl_type_of_resource'). ": ". _AT($_rl_types[$row['type']]); ?><br/>
			<?php  echo _AT('author'). ": ". $row['author']; ?><br/>
			<?php  echo _AT('rl_publisher'). ": ". $row['publisher']; ?><br/>
			<?php  echo _AT('date'). ": ". $row['date']; ?><br/>
			<?php  echo _AT('id'). ": ". $row['id']; ?><br/>
			<?php  echo _AT('comment'). ": ". $row['comments']; ?>
		</p>
	</div>
<?php endif;
}
?>
	<?php } while($rowReadings = mysql_fetch_assoc($resultReadings)); ?>
<?php else: ?>
	<table  class="data" style="width: 95%;"><tr>
		<td colspan="3"><strong><?php echo _AT('none_found'); ?></strong></td>
	</tr></table>
<?php endif; ?>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>