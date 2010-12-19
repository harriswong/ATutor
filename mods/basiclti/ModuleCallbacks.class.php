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
		$basiclti_content_row = mysql_fetch_assoc($instanceresult);
		if ( $basiclti_content_row === false ) return;
		$toolid = $basiclti_content_row['toolid'];
		$sql = "SELECT * FROM ".TABLE_PREFIX."basiclti_tools
                		WHERE toolid='".$toolid."'";
		$contentresult = mysql_query($sql, $db);
		$basiclti_tool_row = mysql_fetch_assoc($contentresult);
		if ( ! $basiclti_tool_row ) {
			return _AT('blti_missing_tool').$toolid;
		}
		// Figure height
		$height = 1200;
		if ( isset($basiclti_tool_row['preferheight']) && $basiclti_tool_row['preferheight'] > 0 ) {
			$height = $basiclti_tool_row['preferheight'];
		}
		if ( $basiclti_tool_row['allowpreferheight'] == 2 && isset($basiclti_content_row['preferheight']) && $basiclti_content_row['preferheight'] > 0 ) {
			$height = $basiclti_content_row['preferheight'];
		}
		return '<iframe src="'.AT_BASE_HREF.'mods/basiclti/launch/launch.php?cid='.$cid.'" height="'.$height.'" width="100%"></iframe>'."\n";
	}

}

?>
