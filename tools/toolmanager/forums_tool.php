<?php

if (!defined('AT_INCLUDE_PATH')) { exit; }
global $db;

$sql = "SELECT f.* FROM ".TABLE_PREFIX."forums f INNER JOIN ".TABLE_PREFIX."forums_courses fc USING (forum_id) WHERE fc.course_id = $_SESSION[course_id]";
$result = mysql_query($sql, $db);

if(mysql_num_rows($result) != 0){
	while ($row = mysql_fetch_assoc($result)) {
		$path =  "forum/index.php?fid=".$row['forum_id']; 					// memorizzo i dati necessari per comporre i link di ogni elemento
		$content_list[] = array('start'=>'[forum]','title' => $row['title'], 'path' => $path, 'image' => AT_BASE_HREF.'images/home-forums_sm.png','end'=>'[/forum]');
	}	
	return $content_list; 	
} else {
	$msg->addInfo('NO_FORUMS');
	$msg->printInfos();
	return;
}
?>