<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
session_write_close();
//authenticate(USER_CLIENT, USER_ADMIN);
require(AT_INCLUDE_PATH.'lib/chat.inc.php');

$myPrefs = getPrefs($_SESSION['login']);

require('include/html/chat_header.inc.php');
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
	<th class="box" align="left"><?php echo _AC('chat_options'); ?></th>
</tr>
</table>

<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
	<td align="right"><a href="prefs.php" target="_top" onfocus="this.className='highlight'" onblur="this.className=''"><?php echo _AC('chat_edit_prefs'); ?></a> |  <a href="index.php" target="_top" accesskey="q" onfocus="this.className='highlight'" onblur="this.className=''"><?php  echo _AC('chat_exit'); ?></a></td></tr></table>
<br /><br />
<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
<th class="box"
align="left"><?php echo _AC('chat_list_and_history'); ?></th></tr></table><?php
	echo '<ul class="users">';
	if ($dir = opendir(AT_CONTENT_DIR . 'chat/'.$_SESSION['course_id'].'/users/')) {
		while (($file = readdir($dir)) !== false) {
			if (($file == '..') || ($file == '.')) {
				continue;
			}

			$chatName	= substr($file, 0, -strlen('.prefs'));
			$la			= getLastAccessed($chatName);
			$now		= time();
			if (($la == 0) || (!$la)) {
				$la = 0;
			} else if ($now - $la < $admin['chatSessionLifeSpan']) {
				$colour	= getChatIDColour($chatName, $myPrefs['colours']);
				if ($chatName == $_SESSION['login']) {
					echo '<li><a href="filterHistory.php?filterChatID='.$chatName.'" target="_top" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'"><span style="color:'.$colour.'">'.$chatName.'</span></a> ('._AC('chat_you').')</li>';
				} else if($chatName != '') {
					echo '<li><a href="filterHistory.php?filterChatID='.$chatName.'" target="_top" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'"><span style="color:'.$colour.'">'.$chatName.'</span></a></li>';
				}
			} else {
                resetLastAccessed($chatName);
				$topMsgNum = $bottomMsgNum = 0;
                howManyMessages($topMsgNum, $bottomMsgNum);
				postMessage(_AC('chat_system'),
							_AC(chat_user_logged_out, $chatName),
							$topMsgNum,
							$bottomMsgNum);
			}
		}
	}
	closedir($dir);
	echo '</ul>';

	echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">
           <tr><td align="right"><a href="history.php" target="_top" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'">'._AC('chat_full_history').'</a> | <a href="options.php" target="options" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'">'._AC('chat_refresh_user_list').'</a></td></tr></table>';

    //if ($myPrefs['navigationAidFlag'] > 0) {
        echo '<br /><br />';
		echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">
           <tr><td align="left"><h4>'._AC('chat_quick_keys').'</h4></td></tr></table>';

        echo '<ul><li>'._AC('chat_altc').'</li>
               <li>'._AC('chat_post').'</li>
               <li>'._AC('chat_altr').'</li>
               <li>'._AC('chat_altm').'</li>
               <li>'._AC('chat_altq').'</li></ul>';
    //}
?>
<?php
require('include/html/chat_footer.inc.php');
?>
