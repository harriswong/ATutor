<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay & Joel Kronenberg        */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }
global $contentManager;
global $_my_uri;
global $_base_path, $path;
global $savant;

ob_start();
$related = $contentManager->getRelatedContent($_SESSION['s_cid']);

if (count($related) == 0) {
	echo '<small><i>'._AT('none_found').'</i></small>';
} else {
	for ($i=0; $i < count($related); $i++) {
		echo '&#176; <a href="'.$_base_path.'content.php?cid='.$related[$i].'">';
		echo $contentManager->_menu_info[$related[$i]]['title'];
		echo '</a>';
		echo '<br />';
	}
}
	

$savant->assign('tmpl_dropdown_contents', ob_get_contents());
ob_end_clean();
$savant->assign('tmpl_close_url', $_my_uri.'disable='.PREF_RELATED.SEP.'menu_jump=3');
$savant->assign('tmpl_dropdown_close', _AT('close_related_topics'));

$savant->assign('title', _AT('related_topics'));
$savant->display('include/box.tmpl.php');
?>