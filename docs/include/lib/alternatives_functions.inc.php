<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: alternatives.inc.php 7208 2008-07-04 16:07:24Z silvia $

function link_name_resource($resource){
	echo '<a href="'.$resource.'" target="_blank">'.$resource.'</a>';
}
 
function checkbox_types($resource_id, $alt, $kind){
	global $db;
	
	echo '<fieldset>';
	echo '<legend>Resource type</legend>';

	$sql_types		= "SELECT * FROM ".TABLE_PREFIX."resource_types";
	$types			= mysql_query($sql_types, $db);

	$sql_set_types	= "SELECT type_id FROM ".TABLE_PREFIX.$alt."_resources_types where ".$alt."_resource_id=".$resource_id;
	$set_types		= mysql_query($sql_set_types, $db);
	//echo $sql_set_types;
	
	$resource_types	= false;
	$j 				= 0;
	
	if (mysql_num_rows($set_types) > 0){
		while ($set_type = mysql_fetch_assoc($set_types)) {
			$resource_types[$j] = $set_type[type_id];
			$j++;
		}
	}
	else echo '<p class="unsaved">Define resource type!</p>';
	
	while ($type = mysql_fetch_assoc($types)) {
		if ((($alt == 'primary')) && ($kind == 'non_textual') && (($type['type'] == 'textual') || ($type['type'] == 'sign_language')))
			continue;
		else {
			echo '<input type="checkbox" name="checkbox_'.$type['type'].'_'.$resource_id.'" value="'.$type['type'].'_'.$resource_id.'" id="'.$type['type'].'_'.$resource_id.'"';
		
			$m = count($resource_types);
			for ($j=0; $j < $m; $j++){
				if (trim($resource_types[$j]) == trim($type[type_id])){
					echo 'checked="checked"';
					continue;
				}
			}
			echo '/>';
			echo '<label for="'.$type['type'].'_'.$resource_id.'">'.$type['type'].'</label><br/>';	
		}	
	}	
	echo '</fieldset>';
}
	
function delete_alternative($resource, $cid, $current_tab){
	echo '<a href="'.$_SERVER['PHP_SELF'].'?cid='.$cid. SEP .'tab='.$current_tab . SEP . 'act=delete'. SEP .'id_alt='.$resource[secondary_resource_id].' ">Delete <strong>'. $resource[secondary_resource].'</strong></a>';
}						
						
						
					