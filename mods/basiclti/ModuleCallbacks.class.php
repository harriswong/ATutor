<?php

/**
 * This class contains the callback functions that are called in the core scripts to manipulate content etc. 
 *
 * Note:
 * 1. CHANGE the class name and ensure its uniqueness by prefixing with the module name
 * 2. DO NOT change the script name. Leave as "ModuleCallbacks.class.php"
 * 3. DO NOT change the names of the methods.
 * 4. REGISTER the unique class name in module.php
 *
 * @access	public
 */
if (!defined('AT_INCLUDE_PATH')) exit;

class BasicLTICallbacks {
	/*
	 * To append output onto course content page 
	 * @param: None
	 * @return: a string, plain or html, to be appended to course content page
	 */ 
	public static function appendContent($cid) {
		if ( !is_int($_SESSION['course_id']) || $_SESSION['course_id'] < 1 ) return;
		$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_content
			WHERE content_id=".$cid." AND course_id = ".$_SESSION['course_id'];
		global $db;
		$instanceresult = mysql_query($sql, $db);
 		if ( $instanceresult == false ) return;
		$instancerow = mysql_fetch_assoc($instanceresult);
		if ( $instancerow === false ) return;
		return '<iframe src="'.AT_BASE_HREF.'mods/basiclti/launch/launch.php?cid='.$cid.'" height="1200" width="100%"></iframe>'."\n";
	}

}

?>
