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

global $msg, $_stacks;

$msg->printErrors();
$msg->printAll();

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="prefs">

<div class="input-form">
	<div class="row">
		<?php if (defined('AT_ENABLE_CATEGORY_THEMES') && AT_ENABLE_CATEGORY_THEMES): ?>
			<?php echo _AT('themes_disabled'); ?>
		<?php else: ?>
			<label for="seq_icons"><?php echo _AT('theme'); ?></label><br />
				<select name="theme"><?php
							$_themes = get_enabled_themes();
							
							foreach ($_themes as $theme) {
								if (!$theme) {
									continue;
								}

								$theme_fldr = get_folder($theme);

								if ($theme_fldr == $_SESSION['prefs']['PREF_THEME']) {
									echo '<option value="'.$theme_fldr.'" selected="selected">'.$theme.'</option>';
								} else {
									echo '<option value="'.$theme_fldr.'">'.$theme.'</option>';
								}
							}
						?>
					</select>
		<?php endif; ?>
	</div>

	<div class="row">
		<label for="seq"><?php echo _AT('seq_links'); ?></label><br />
		<?php
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
		  </select>
	</div>

	<div class="row">
		<label for="toc"><?php echo _AT('table_of_contents'); ?></label><br />
		<?php
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
		  </select>
	</div>

	<div class="row">
		<label for="numbering"><?php echo _AT('show_numbers');  ?></label><br />
		<?php
			$num = '';  $num2 = '';
			if ($_SESSION['prefs'][PREF_NUMBERING] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="numbering" id="num_en" value="1" <?php echo $num; ?> /><label for="num_en"><?php echo _AT('enable');  ?></label><br />
			<input type="radio" name ="numbering" id="num_dis" value="0" <?php echo $num2; ?> /><label for="num_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<label for="use_help"><?php echo _AT('help'); ?></label><br />
		<?php
			$num = '';  $num2 = '';
			if ($_SESSION['prefs'][PREF_HELP] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="use_help" id="help_en" value="1" <?php echo $num; ?> /><label for="help_en"><?php echo _AT('enable');  ?></label><br />
			<input type="radio" name ="use_help" id="help_dis" value="0" <?php echo $num2; ?> /><label for="help_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<label for="use_mini_help"><?php echo _AT('show_mini_help'); ?></label><br />
		<?php
			$num = '';  $num2 = '';
			if ($_SESSION['prefs'][PREF_MINI_HELP] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="use_mini_help" id="mhelp_en" value="1" <?php echo $num; ?> /><label for="mhelp_en"><?php echo _AT('enable');  ?></label><br />
			<input type="radio" name ="use_mini_help" id="mhelp_dis" value="0" <?php echo $num2; ?> /><label for="mhelp_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<label for="use_jump_redirect"><?php echo _AT('jump_redirect');  ?>:</label><br />
		<?php
			$num = '';  $num2 = '';
			if ($_SESSION['prefs'][PREF_JUMP_REDIRECT] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="use_jump_redirect" id="jump_en" value="1" <?php echo $num; ?> /><label for="jump_en"><?php echo _AT('enable');  ?></label><br />
			<input type="radio" name ="use_jump_redirect" id="jump_dis" value="0" <?php echo $num2; ?> /><label for="jump_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<label for="seq_icons"><?php echo _AT('menus'); ?>:</label><br />
		<?php
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
			} ?>
	</div>

	<div class="buttons">
		<input type="submit" name="submit" value="<?php echo _AT('apply'); ?>" accesskey="s" />
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" accesskey="s" />
	</div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>