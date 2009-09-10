<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }

global $db;

$sql = "SELECT * FROM ".TABLE_PREFIX."links L INNER JOIN ".TABLE_PREFIX."links_categories C ON C.cat_id = L.cat_id WHERE owner_id=$_SESSION[course_id] ORDER BY SubmitDate DESC";
$result = mysql_query($sql, $db);

if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$path = "links/index.php?view=".$row['link_id'];
		$content_list[] = array('start'=>'[link]','title' => $row['LinkName'], 'path' => $path , 'image' => AT_BASE_HREF.'images/home-links_sm.png','end' => '[/link]');
	}
	return $content_list;	
} else {
	$msg->addInfo('NO_LINK');
	$msg->printInfos();
	return;
}


?>