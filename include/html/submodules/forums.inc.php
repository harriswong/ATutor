<?php

if (!defined('AT_INCLUDE_PATH')) { exit; }
global $db;
global $_base_path;

$post_limit = 3;		//Numero massimo dei possibili sottocontenuti visualizzabili nella home-page

$forum_list = get_group_concat('forums_courses', 'forum_id', "course_id={$_SESSION['course_id']}");

if ($forum_list != 0) {	//si esegue il controllo per verificare se saranno stati attivati forum per il corso specifico
	$sql = "SELECT subject, post_id, forum_id, member_id FROM ".TABLE_PREFIX."forums_threads WHERE parent_id=0 AND forum_id IN ($forum_list) ORDER BY last_comment DESC LIMIT $post_limit";
	$result = mysql_query($sql, $db);

	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$threads_list[] = array('sub_url' => $_base_path.url_rewrite('forum/view.php?fid=' . $row['forum_id'] . SEP . 'pid=' . $row['post_id']) , 'sub_text' => $row['subject']); 
		}
		return $threads_list;
		
	} else {
		return 0;
	}
} else {
	return 0;
}
?>