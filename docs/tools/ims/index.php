<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2007 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

require(AT_INCLUDE_PATH.'header.inc.php');

if (!isset($_main_menu)) {
	$_main_menu = $contentManager->getContent();
}

function print_menu_sections(&$menu, $parent_content_id = 0, $depth = 0, $ordering = '') {
	$my_children = $menu[$parent_content_id];
	$cid = $_GET['cid'];

	if (!is_array($my_children)) {
		return;
	}
	foreach ($my_children as $children) {
		echo '<option value="'.$children['content_id'].'"';
		if ($cid == $children['content_id']) {
			echo ' selected="selected"';
		}
		echo '>';
		echo str_pad('', $depth, '-') . ' ';
		if ($parent_content_id == 0) {
			$new_ordering = $children['ordering'];
			echo $children['ordering'];
		} else {
			$new_ordering = $ordering.'.'.$children['ordering'];
			echo $ordering . '.'. $children['ordering'];
		}
		echo ' '.$children['title'].'</option>';

		print_menu_sections($menu, $children['content_id'], $depth+1, $new_ordering);
	}
}

if (!authenticate(AT_PRIV_CONTENT, AT_PRIV_RETURN) && ($_SESSION['packaging'] == 'none')) {
	echo '<p>'._AT('content_packaging_disabled').'</p>';
	require (AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
} else if (!authenticate(AT_PRIV_CONTENT, AT_PRIV_RETURN) && ($_SESSION['packaging'] == 'top')) {
	$_main_menu = array($_main_menu[0]);
}
?>
<form method="post" action="tools/ims/ims_export.php">
<div class="input-form">
	<div class="row">
		<h3><?php echo _AT('export_content'); ?></h3>
		<p><?php echo _AT('export_content_info'); ?></p>
	</div>

<?php if ($_main_menu[0]): ?>
	<div class="row">
		<label for="select_cid"><?php echo _AT('export_content_package_what'); ?></label><br />
		<select name="cid" id="select_cid">
			<option value="0"><?php echo _AT('export_entire_course_or_chap'); ?></option>
			<option value="0"></option>
			<?php
				print_menu_sections($_main_menu);
			?>
		</select>
	</div>

	<?php if (authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)): ?>
			<div class="row">
				<input type="checkbox" name="to_tile" id="to_tile" value="1" />
				<label for="to_tile"><?php echo _AT('tile_export'); ?></label>
			</div>
	<?php endif; ?>
	
	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('export'); ?>" />
		<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
	</div>
<?php else: ?>
	<div class="row">
		<strong><?php echo _AT('none_found'); ?></strong>
	</div>
<?php endif; ?>

</div>
</form>

<?php if (!authenticate(AT_PRIV_CONTENT, AT_PRIV_RETURN)) {
		require (AT_INCLUDE_PATH.'footer.inc.php'); 
		exit;
}
?>
<br /><br />


<form name="form1" method="post" action="tools/ims/ims_import.php" enctype="multipart/form-data" onsubmit="openWindow('<?php echo AT_BASE_HREF; ?>tools/prog.php');">
<div class="input-form">
	<div class="row">
		<h3><?php echo _AT('import_content'); ?></h3>
		<p><?php echo _AT('import_content_info'); ?></p>
	</div>

	<div class="row">
		<label for="select_cid2"><?php echo _AT('import_content_package_where'); ?></label><br />
		<select name="cid" id="select_cid2">
			<option value="0"><?php echo _AT('import_content_package_bottom_subcontent'); ?></option>
			<option value="0"></option>
			<?php
				print_menu_sections($_main_menu);
			?>
		</select>
	</div>
	
	<div class="row">
		<label for="to_file"><?php echo _AT('upload_content_package'); ?></label><br />
		<input type="file" name="file" id="to_file" />
	</div>

	<div class="row">
		<label for="to_url"><?php echo _AT('specify_url_to_content_package'); ?></label><br />
		<input type="text" name="url" value="http://" size="40" id="to_url" />
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" onclick="setClickSource('submit');" value="<?php echo _AT('import'); ?>" />
		<input type="submit" name="cancel" onclick="document.form1.enctype='';setClickSource('cancel');" value="<?php echo _AT('cancel'); ?>" />
	</div>
</div>
</form>

<script language="javascript" type="text/javascript">

var but_src;
function setClickSource(name) {
	but_src = name;
}

function openWindow(page) {
	if (but_src != "cancel") {
		newWindow = window.open(page, "progWin", "width=400,height=200,toolbar=no,location=no");
		newWindow.focus();
	}
}
</script>

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>