<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }

?>

<table width="300" border="0" cellspacing="0" cellpadding="0" summary="">
	<tr>
		<td><img src="<?php echo $this->tmpl_base_path; ?>images/clr.gif" height="4" width="4" alt="" /></td>
	</tr>
	<tr>
		<td class="dropdown-heading closed" valign="top">
			<?php //print_popup_help($this->tmpl_popup_help); ?>
			<?php echo $this->tmpl_menu_url; ?>
			<small><a href="<?php echo $this->tmpl_open_url; ?>" accesskey="<?php echo $this->tmpl_access_key; ?>" title="<?php echo $this->tmpl_dropdown_open; ?> <?php if ($this->tmpl_access_key): echo 'ALT-'.$this->tmpl_access_key; endif; ?>"><?php echo $this->tmpl_dropdown_open; ?></a></small>
		</td>
	</tr>
</table>
