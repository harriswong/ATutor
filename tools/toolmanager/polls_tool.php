<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $db;

$sql = "SELECT * FROM ".TABLE_PREFIX."polls WHERE course_id=$_SESSION[course_id] ORDER BY created_date DESC";
$result = mysql_query($sql, $db);

if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) { 
		$path = "polls/index.php#".$row['poll_id'];
		$content_list[] = array('title' => $row['question'], 'path' => $path , 'image' => AT_BASE_HREF.'images/home-polls_icon.png'); 
	}
	return $content_list;	
} else {
	$msg->addInfo('NO_POLLS');
	$msg->printInfos();
	return;
}


?>