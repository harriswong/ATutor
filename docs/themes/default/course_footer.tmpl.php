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
if (!defined('AT_INCLUDE_PATH')) { exit; } ?>

<br />
<?php echo $this->tmpl_next_prev_links; ?>

	<div align="right" id="top">
		<small><br />
		<?php echo $this->tmpl_help_link; ?>

		<?php if ($this->tmpl_show_imgs): ?>
			<a href="<?php echo $_SERVER['REQUEST_URI']; ?>#course-content" title="<?php _AT('back_to_top'); ?> ALT-c"><img src="<?php echo $this->tmpl_base_path; ?>images/top.gif" alt="<?php _AT('back_to_top'); ?>" border="0" class="menuimage4" height="25" width="28"  /></a><br />
		<?php endif; ?>
		<?php if ($this->tmpl_show_seq_icons): ?>
			<a href="<?php echo $_SERVER['REQUEST_URI']; ?>#course-content" title="<?php _AT('back_to_top'); ?> ALT-c"><?php echo _AT('top'); ?></a>
		<?php endif; ?>
		&nbsp;&nbsp;</small>
	</div>

	</td>
	<?php if ($this->tmpl_right_menu_open): ?>
		<td width="20%" valign="top" rowspan="2" style="padding:5px">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
			<tr>
				<td valign="top" class="dropdown-heading closed"><?php print_popup_help($this->tmpl_popup_help); 
				echo $this->tmpl_menu_url; ?>
				<small><a href="<?php echo $this->tmpl_close_menu_url; ?>" accesskey="6" title="<?php echo $this->tmpl_close_menus; ?> ALT-6"><?php echo $this->tmpl_close_menus; ?></a></small></td>
			</tr>
			<?php if(false && show_pen()): ?>
				<tr><td height="5"></td></tr>
				<tr>
					<td class="pen" valign="top">
						<?php print_popup_help('EDITOR'); ?><small><small><img src="<?php echo $this->tmpl_pen_image; ?>" alt="<?php echo _AT('editor'); ?>" title="<?php echo _AT('editor'); ?>" style="	height:1.4em; width:1.6em;" /></small> <?php echo $this->tmpl_pen_link; ?></small>
					</td>
				</tr>
			<?php endif; ?>
			</table>
			<!-- dropdown menus -->
			<?php require(AT_INCLUDE_PATH.'html/dropdowns.inc.php'); ?>
			<!-- end dropdown menus -->
		</td>
	<?php endif; ?>
</tr>
</table>
<div align="center"><small><?php echo $this->tmpl_custom_copyright; ?></small></div>