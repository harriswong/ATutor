<?php
/************************************************************************/
/* ATutor								*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto		*/
/* http://atutor.ca							*/
/*									*/
/* This program is free software. You can redistribute it and/or	*/
/* modify it under the terms of the GNU General Public License		*/
/* as published by the Free Software Foundation.			*/
/************************************************************************/
// $Id: index.php 7208 2008-01-09 16:07:24Z greg $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'header.inc.php');
if (authenticate(AT_PRIV_ADMIN,AT_PRIV_RETURN))
{



?>
<table style="width:200px"align="left"class="data static" rules="cols" summary="">
<thead>
<tr>
	<th scope="col"><?php echo _AT('enrolled_students'); ?></th>
	
</tr>
</thead>
<tbody>
<?php
	$sql = "SELECT M.member_id, CONCAT(M.first_name, ' ', M.second_name, ' ', M.last_name) AS Name
	        FROM ".TABLE_PREFIX."members M , ".TABLE_PREFIX."course_enrollment E
	        WHERE M.member_id = E.member_id
            AND E.course_id = $_SESSION[course_id]
            AND E.approved = 'y'
            AND M.status = 2
            ORDER BY M.first_name ASC";
	$result = mysql_query($sql, $db);
	
if ($row = mysql_fetch_assoc($result)) : 
 do 
  {?>
	  <tr>
	   <tr onmousedown="document.location='<?php echo AT_BASE_HREF; ?>mods/users_stats/users_stats1.php?id=<?php echo $row['member_id']; ?>">
		<td align="center"><a  href="<?php echo AT_BASE_HREF; ?>mods/users_stats/users_stats1.php?id=<?php echo $row['member_id'];?>"><?php echo ($row['Name']); ?></a></td>
   <?php
  } while ($row = mysql_fetch_assoc($result));
    
else: ?> <!--dati non presenti-->
	<tr>
		<td colspan="4"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php endif; ?>   
    
    
    
	 </tr>
</tbody>
</table>
 <?php
}
else
{ ?>
	<tr onmousedown="document.location='<?php echo AT_BASE_HREF; ?>mods/users_stats/users_stats1.php">
    <td><a href="mods/users_stats/users_stats1.php?"><img align="middle" src="mods/users_stats/images/tracker.gif"  hspace="30"></a><br> </td> <?php
}


require(AT_INCLUDE_PATH.'footer.inc.php'); ?>