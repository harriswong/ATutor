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
// $Id: preferences.php 3193 2005-02-04 19:16:37Z joel $

require(AT_INCLUDE_PATH.'header.inc.php');

global $msg;

$msg->printErrors();
$msg->printAll();

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="prefs">

<table border="0" class="bodyline" cellspacing="1" cellpadding="0">
<tr>
<th colspan="2" class="cyan"><?php print_popup_help('POSITION_OPTIONS'); echo _AT('pos_options')?></th>
</tr>
<tr>
<td class="row1"><label for="seq"><?php echo _AT('seq_links');  ?>:</label></td>
<td class="row1"><?php
	/* sequence links preference */
	if ($_SESSION['prefs'][PREF_SEQ] == TOP) {
		$top = ' selected="selected"';
	} else if ($_SESSION['prefs'][PREF_SEQ] == BOTTOM) {
		$bottom = ' selected="selected"';
	} else {
		$both = ' selected="selected"';
	}
	?><select name="seq" id="seq">
		<option value="<?php echo TOP; ?>"<?php echo $top; ?>><?php echo _AT('top');  ?></option>
		<option value="<?php echo BOTTOM; ?>"<?php echo $bottom; ?> ><?php echo _AT('bottom');  ?></option>
		<option value="<?php echo BOTH; ?>"<?php echo $both; ?>><?php echo _AT('top_bottom');  ?></option>
	  </select><br /></td>
</tr>
<tr>
	<td class="row1"><label for="toc"><?php echo _AT('table_of_contents');  ?>:</label></td>
	<td class="row1"><?php
	// table of contents preference
	$top = $bottom = '';
	if ($_SESSION['prefs'][PREF_TOC] == TOP) {
		$top	= ' selected="selected"';
	} else if ($_SESSION['prefs'][PREF_TOC] == BOTTOM) {
		$bottom = ' selected="selected"';
	} else {
		$neither = ' selected="selected"';
	}
	?><select name="toc" id="toc">
		<option value="<?php echo TOP; ?>"<?php echo $top; ?>><?php echo _AT('top');  ?></option>
		<option value="<?php echo BOTTOM; ?>"<?php echo $bottom; ?>><?php echo _AT('bottom');  ?></option>
		<option value="<?php echo NEITHER; ?>"<?php echo $neither; ?>><?php echo _AT('neither');  ?></option>
	  </select></td>
</tr>
</table>

<table border="0" class="bodyline" cellspacing="1" cellpadding="0">
<tr>
	<th colspan="2" class="cyan"><?php print_popup_help('DISPLAY_OPTIONS'); ?><?php echo _AT('disp_options');  ?></th>
</tr>
<tr>
	<td class="row1"><?php
	/* Show Topic Numbering Preference */
	if ($_SESSION['prefs'][PREF_NUMBERING] == 1) {
		$num = ' checked="checked"';
	}
	?> <input type="checkbox" name="numering" value="1" <?php echo $num;?> id="numbering" /></td>
	<td class="row1"><label for="numbering"><?php echo _AT('show_numbers');  ?></label></td>
</tr>
<tr>
	<td class="row1"><?php
		/* Show Breadcrumbs Preference */
		$num = '';
		if ($_SESSION['prefs'][PREF_BREADCRUMBS] == 1) {
			$num = ' checked="checked"';
		}
		?><input type="checkbox" name="breadcrumbs" value="1" <?php echo $num;?> id="breadcrumbs" /></td>
	<td class="row1"><label for="breadcrumbs"><?php echo _AT('show_breadcrumbs');  ?></label></td>
</tr>
<tr>
	<td class="row1"><?php
		$num = '';
		if ($_SESSION['prefs'][PREF_HEADINGS] == 1) {
			$num = ' checked="checked"';
		}
		?> <input type="checkbox" name="headings" value="1" <?php echo $num;?> id="heading" /></td>
	<td class="row1"><label for="heading"><?php echo _AT('show_headings');  ?></label></td>
</tr>
<tr>
	<td class="row1"><?php
		$num = '';
		if ($_SESSION['prefs'][PREF_HELP] == 1) {
			$num = ' checked="checked"';
		}
		?><input type="checkbox" name ="use_help" id="use_help" value="1" <?php echo $num; ?> /></td>
	<td class="row1"><label for="use_help"><?php echo _AT('show_help');  ?></label><br /></td>
</tr>
<tr>
	<td class="row1"><?php
		$num = '';
		if ($_SESSION['prefs'][PREF_MINI_HELP] == 1) {
			$num = ' checked="checked"';
		}
		?><input type="checkbox" name ="use_mini_help" id="use_mini_help" value="1" <?php echo $num; ?> /></td>
	<td class="row1"><label for="use_mini_help"><?php echo _AT('show_mini_help');  ?></label><br /></td>
</tr>
<tr>
	<td class="row1"><?php
		$num = '';
		if (isset($_SESSION['prefs'][PREF_JUMP_REDIRECT]) && $_SESSION['prefs'][PREF_JUMP_REDIRECT]) {
			$num = ' checked="checked"';
		}
		?><input type="checkbox" name="use_jump_redirect" value="1" id="use_jump_redirect" <?php echo $num; ?> /></td>
	<td class="row1"><label for="use_jump_redirect"><?php echo _AT('jump_redirect');  ?></label><br /></td>
</tr>
</table>

<table border="0" class="bodyline" cellspacing="1" cellpadding="0">
<tr>
	<th colspan="2" class="cyan"><?php print_popup_help('MENU_OPTIONS'); ?><?php  echo _AT('menus'); ?></th>
</tr>
<tr>
	<td class="row1" align="center"><?php

		$num_stack = count($_stacks);

		for ($i = 0; $i< 8; $i++) {
			echo '<select name="stack'.$i.'">'."\n";
			echo '<option value="">'._AT('empty').'</option>'."\n";
			for ($j = 0; $j<$num_stack; $j++) {
				echo '<option value="'.$j.'"';
				if (isset($_SESSION['prefs'][PREF_STACK][$i]) && ($j == $_SESSION[prefs][PREF_STACK][$i])) {
					echo ' selected="selected"';
				}
				echo '>'._AT($_stacks[$j]['file']).'</option>'."\n";
			}
			echo '</select>'."\n";
			echo '<br />'; 
		}

	?></td>
	</tr>
	</table>

<table border="0"  class="bodyline" cellspacing="1" cellpadding="0">
<tr>
	<th colspan="2" class="cyan"><?php print_popup_help('THEME_OPTIONS');  echo _AT('theme'); ?></th>
</tr>
<?php if (defined('AT_ENABLE_CATEGORY_THEMES') && AT_ENABLE_CATEGORY_THEMES): ?>
	<tr>
		<td><?php echo _AT('themes_disabled'); ?></td>
	</tr>
<?php else: ?>
	<tr>
		<td class="row1"><label for="seq_icons"><?php echo _AT('theme');  ?>:</label></td>
		<td class="row1"><select name="theme"><?php
						
						$_themes = get_enabled_themes();
					
						foreach ($_themes as $theme) {
							if (!$theme) {
								continue;
							}

							$theme_fldr = get_folder($theme);

							if ($theme_fldr == $_SESSION['prefs']['PREF_THEME']) {
								echo '<option value="'.$theme_fldr.'" selected="selected">'.$theme.'</option>'."\n";
							} else {
								echo '<option value="'.$theme_fldr.'">'.$theme.'</option>'."\n";
							}
						}
						?>
						</select></td>
	</tr>
<?php endif; ?>
</table>
<br />
<input type="submit" name="submit" value="<?php echo _AT('set_prefs'); ?>" title="<?php echo _AT('set_prefs'); ?>" accesskey="s" class="button" />
</form>

<?php
require(AT_INCLUDE_PATH.'footer.inc.php');
?>