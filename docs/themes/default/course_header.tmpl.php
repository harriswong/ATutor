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
// $Id$

if (!defined('AT_INCLUDE_PATH')) { exit; }
?>
<!-- content table -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="" id="content">
<tr>
	<?php if ($this->tmpl_menu_open && $this->tmpl_menu_left): ?>
		<td id="menu" width="20%" valign="top" rowspan="2" style="padding:5px">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
			<tr>
				<td class="dropdown-heading closed" valign="top">
					<?php print_popup_help('MAIN_MENU'); ?>
					<small><a name="menu"></a><a href="<?php echo $this->tmpl_close_menu_url; ?>" accesskey="6" title="<?php echo _AT('close_menus')?>: Alt-6"><?php echo _AT('close_menus'); ?></a></small>
				</td>
			</tr>
			<?php if(false && show_pen()): ?>
				<tr><td height="5"></td></tr>
				<tr>
					<td class="pen" valign="top">
						<?php print_popup_help('EDITOR'); ?><small><img src="<?php echo $this->tmpl_pen_image; ?>" alt="<?php echo _AT('editor'); ?>" title="<?php echo _AT('editor'); ?>" /> <?php echo $this->tmpl_pen_link; ?></small>
					</td>
				</tr>
			<?php endif; ?>
			</table>

			<!-- dropdown menus -->
			<?php require(AT_INCLUDE_PATH.'html/dropdowns.inc.php'); ?>
			<!-- end dropdown menus -->
		</td>
	<?php endif; ?>

	<td width="3"><img src="<?php echo $this->tmpl_base_path; ?>images/clr.gif" width="3" height="3" alt="" /></td>

	<td valign="top" width="<?php echo $this->tmpl_width; ?>">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" summary="">
		<tr>
			<?php if ($this->tmpl_menu_closed && $this->tmpl_menu_left): ?>
				<td width="20%" valign="top" style="padding-top:5px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
					<tr>
						<td class="dropdown-heading closed" valign="top">
							<?php print_popup_help('MAIN_MENU'); ?>
							<small><a name="menu"></a><a href="<?php echo $this->tmpl_open_menu_url; ?>" accesskey="6" title="<?php echo _AT('open_menus'); ?> ALT-6"><?php echo _AT('open_menus'); ?></a></small>
						</td>
					</tr>
					<?php if(false && show_pen()): ?>
						<tr><td height="5"></td></tr>
						<tr>
							<td class="pen" valign="top">
								<?php print_popup_help('EDITOR'); ?><small><small><img src="<?php echo $this->tmpl_pen_image; ?>" alt="<?php echo _AT('editor'); ?>" title="<?php echo _AT('editor'); ?>" /></small> <?php echo $this->tmpl_pen_link; ?></small>
							</td>
						</tr>
					<?php endif; ?>
					</table>
				</td>
			<?php endif; ?>

			<td width="80%" valign="top"></td>
			<?php if ($this->tmpl_menu_closed && !$this->tmpl_menu_left): ?>
				<td width="20%" valign="top" style="padding:5px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
					<tr>
						<td class="dropdown-heading closed" valign="top">
							<?php print_popup_help('MAIN_MENU');?>
							<small><a name="menu"></a><a href="<?php echo $this->tmpl_open_menu_url; ?>" accesskey="6" title="<?php echo _AT('open_menus'); ?> ALT-6"><?php echo _AT('open_menus'); ?></a></small>
						</td>
					</tr>
					<?php if(false && show_pen()): ?>
						<tr><td height="5"></td></tr>
						<tr>
							<td class="pen" valign="top">
								<?php print_popup_help('EDITOR'); ?><small><img src="<?php echo $this->tmpl_pen_image; ?>" alt="<?php echo _AT('editor'); ?>" title="<?php echo _AT('editor'); ?>" /> <?php echo $this->tmpl_pen_link; ?></small>
							</td>
						</tr>
					<?php endif; ?>
					</table>
				</td>
			<?php endif; ?>	
		</tr>
		</table>
<a name="course-content"></a>