<fieldset>
<legend><strong>ATutor Setting</strong> </legend>  
	<div class="row">
		<?php if (defined('AT_ENABLE_CATEGORY_THEMES') && AT_ENABLE_CATEGORY_THEMES): ?>
			<?php echo _AT('themes_disabled'); ?>
		<?php else: ?>
			<label for="theme"><?php echo _AT('theme'); ?></label><br />
				<select name="theme" id="theme"><?php
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
		<?php echo _AT('inbox_notification'); ?><br />
		<?php
			$yes = $no  = '';
			if ($this->notify == 1) {
				$yes = ' checked="checked"';
			} else {
				$no  = ' checked="checked"';
			}
		?>
		<input type="radio" name="mnot" id="mnot_yes" value="1" <?php echo $yes; ?> /><label for="mnot_yes"><?php echo _AT('enable'); ?></label> 
		<input type="radio" name="mnot" id="mnot_no" value="0" <?php echo $no; ?> /><label for="mnot_no"><?php echo _AT('disable'); ?></label>		
	</div>

	<div class="row">
		<?php echo _AT('show_numbers');  ?><br />
		<?php
			$num = $num2 = '';
			if ($_SESSION['prefs']['PREF_NUMBERING'] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="numbering" id="num_en" value="1" <?php echo $num; ?> /><label for="num_en"><?php echo _AT('enable');  ?></label> 
			<input type="radio" name ="numbering" id="num_dis" value="0" <?php echo $num2; ?> /><label for="num_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<?php echo _AT('jump_redirect'); ?><br />
		<?php
			$num = $num2 = '';
			if ($_SESSION['prefs']['PREF_JUMP_REDIRECT'] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="use_jump_redirect" id="jump_en" value="1" <?php echo $num; ?> /><label for="jump_en"><?php echo _AT('enable');  ?></label> 
			<input type="radio" name ="use_jump_redirect" id="jump_dis" value="0" <?php echo $num2; ?> /><label for="jump_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<?php echo _AT('auto_login1');  ?><br /><?php
			$auto_en = $auto_dis = '';
			if ( !empty($_COOKIE['ATLogin']) && !empty($_COOKIE['ATPass']) ) {
				$auto_en = 'checked="checked"';
			} else {
				$auto_dis = 'checked="checked"';
			}
		?><input type="radio" name ="auto" id="auto_en" value="enable" <?php echo $auto_en; ?> /><label for="auto_en"><?php echo _AT('enable');  ?></label> 
		<input type="radio" name ="auto" id="auto_dis" value="disable" <?php echo $auto_dis; ?> /><label for="auto_dis"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<?php echo _AT('form_focus');  ?><br />
		<?php
			$num = $num2 = '';
			if ($_SESSION['prefs']['PREF_FORM_FOCUS'] == 1) {
				$num = ' checked="checked"';
			} else {
				$num2 = ' checked="checked"';
			}
			?><input type="radio" name ="form_focus" id="focus_on" value="1" <?php echo $num; ?> /><label for="focus_on"><?php echo _AT('enable');  ?></label> 
			<input type="radio" name ="form_focus" id="focus_off" value="0" <?php echo $num2; ?> /><label for="focus_off"><?php echo _AT('disable');  ?></label>
	</div>

	<div class="row">
		<?php
			$num0 = $num1 = $num2 = '';
			if ($_SESSION['prefs']['PREF_CONTENT_EDITOR'] == 1) {
				$num1 = ' checked="checked"';
			} else if ($_SESSION['prefs']['PREF_CONTENT_EDITOR'] == 2) {
				$num2 = ' checked="checked"';
			} else {
				$num0 = ' checked="checked"';
			}
		?>
		<?php echo _AT('content_editor'); ?><br />
		<input type="radio" name="content_editor" id="ce_0" value="0" <?php echo $num0; ?>/><label for="ce_0"><?php echo _AT('plain_text');?></label>
		<input type="radio" name="content_editor" id="ce_1" value="1" <?php echo $num1; ?>/><label for="ce_1"><?php echo _AT('html'); ?></label>
		<input type="radio" name="content_editor" id="ce_2" value="2" <?php echo $num2; ?>/><label for="ce_2"><?php echo _AT('html') . ' - '. _AT('visual_editor'); ?></label>
	</div>
</fieldset>