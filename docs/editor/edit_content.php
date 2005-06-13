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

define('AT_INCLUDE_PATH', '../include/');

$get_related_glossary = true;
require(AT_INCLUDE_PATH.'vitals.inc.php');
$cid = intval($_REQUEST['cid']);

if ($_POST) {
	$do_check = TRUE;
} else {
	$do_check = FALSE;
}

require(AT_INCLUDE_PATH.'lib/editor_tab_functions.inc.php');
	
if ($_POST['close'] || $_GET['close']) {
	if ($_GET['close']) {
		$msg->addFeedback('CONTENT_UPDATED');
	} else {
		$msg->addFeedback('CLOSED');
		if ($cid == 0) {
			header('Location: '.$_base_href.'tools/content/index.php');
			exit;
		}
	}
	
	if ($_REQUEST['cid'] == 0) {
		header('Location: '.$_base_path.'content.php?cid='.$_REQUEST['new_pid']);
		exit;
	}
	header('Location: '.$_base_path.'content.php?cid='.$_REQUEST['cid']);
	exit;
}
	
$tabs = get_tabs();	
$num_tabs = count($tabs);
for ($i=0; $i < $num_tabs; $i++) {
	if (isset($_POST['button_'.$i]) && ($_POST['button_'.$i] != -1)) { 
		$current_tab = $i;
		$_POST['current_tab'] = $i;
		break;
	}
}

if (isset($_POST['submit_file'])) {
	paste_from_file();
} else if (isset($_POST['submit']) && ($_POST['submit'] != 'submit1')) {
	/* we're saving. redirects if successful. */
	save_changes(true);
}
if (isset($_GET['tab'])) {
	$current_tab = intval($_GET['tab']);
}

if (!isset($current_tab) && isset($_POST['button_1']) && ($_POST['button_1'] == -1) && !isset($_POST['submit'])) {
	$current_tab = 1;
} else if (!isset($current_tab) && (($_POST['desc_submit'] != '') || ($_POST['reverse'] != ''))) {
	$current_tab = 4;  /* after clicking 'make decisions' on accessibility tab */
} else if (!isset($current_tab)) {
	$current_tab = 0;
}

if ($cid) {
	$_section[0][0] = _AT('edit_content');
} else {
	$_section[0][0] = _AT('add_content');
}

if ($current_tab == 0) {
	//used for visual editor
	if (($_POST['setvisual'] && !$_POST['settext']) || $_GET['setvisual']){
		$onload = 'initEditor();';
	} else {
		$onload = ' document.form.ctitle.focus();';
	}
}

if ($cid) {
	$result = $contentManager->getContentPage($cid);

	if (!($content_row = @mysql_fetch_assoc($result))) {
		require(AT_INCLUDE_PATH.'header.inc.php');
		$msg->printErrors('PAGE_NOT_FOUND');
		require (AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}

	$path	= $contentManager->getContentPath($cid);

	if (defined('AT_FORCE_GET_FILE') && AT_FORCE_GET_FILE) {
		$course_base_href = 'get.php/';
	} else {
		$course_base_href = 'content/' . $_SESSION['course_id'] . '/';
	}

	if ($content_row['content_path']) {
		$content_base_href .= $content_row['content_path'].'/';
	}
} else {
	if (defined('AT_FORCE_GET_FILE') && AT_FORCE_GET_FILE) {
		$content_base_href = 'get.php/';
	} else {
		$content_base_href = 'content/' . $_SESSION['course_id'] . '/';
	}
}

if ($current_tab == 4) {
	/* kludge for issue #1626: */
	/* fixes the base href for the AChecker tab. */
	$course_base_href = '';
	$content_base_href = '';
}

require(AT_INCLUDE_PATH.'header.inc.php');

$cid = intval($_REQUEST['cid']);
$pid = intval($_REQUEST['pid']);

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?cid=<?php echo $cid; ?>" method="post" name="form" enctype="multipart/form-data">
<?php

	if ($cid) {
		$content_row = sql_quote($content_row);
		if (isset($_POST['current_tab'])) {
			//$changes_made = check_for_changes($content_row);
		} else {
			$changes_made = array();

			$_POST['formatting'] = $content_row['formatting'];
			$_POST['title']      = $content_row['title'];
			$_POST['body_text']  = $content_row['text'];
			$_POST['keywords']   = $content_row['keywords'];

			$_POST['day']   = substr($content_row['release_date'], 8, 2);
			$_POST['month'] = substr($content_row['release_date'], 5, 2);
			$_POST['year']  = substr($content_row['release_date'], 0, 4);
			$_POST['hour']  = substr($content_row['release_date'], 11, 2);
			$_POST['minute']= substr($content_row['release_date'], 14, 2);

			$_POST['ordering'] = $_POST['new_ordering'] = $content_row['ordering'];
			$_POST['related'] = $contentManager->getRelatedContent($cid);

			$_POST['pid'] = $pid = $_POST['new_pid'] = $content_row['content_parent_id'];

			$_POST['related_term'] = $glossary_ids_related;
		}
	} else {
		$cid = 0;
		if (!isset($_POST['current_tab'])) {
			$_POST['day']  = date('d');
			$_POST['month']  = date('m');
			$_POST['year'] = date('Y');
			$_POST['hour'] = date('H');
			$_POST['minute']  = 0;

			if (isset($_GET['pid'])) {
				$pid = intval($_GET['pid']);
				$_POST['pid'] = 0;
				$_POST['new_pid'] = $pid;
				$_POST['ordering'] = count($contentManager->getContent(0))+1;
				$_POST['new_ordering'] = count($contentManager->getContent($pid))+1;
			} else {
				$_POST['pid'] = $_POST['new_pid'] = 0;
				$_POST['ordering'] = $_POST['new_ordering'] = count($contentManager->getContent($pid))+1;
			}
			$pid = 0;
		}
		//$changes_made = check_for_changes($content_row);
	}


	echo '<input type="hidden" name="cid" value="'.$cid.'" />';
	echo '<input type="hidden" name="title" value="'.htmlspecialchars(stripslashes($_POST['title'])).'" />';
	if ($current_tab != 0) {
		echo '<input type="hidden" name="body_text" value="'.htmlspecialchars(stripslashes($_POST['body_text'])).'" />';
		echo '<input type="hidden" name="setvisual" value="'.$_POST['setvisual'].'" />';
		echo '<input type="hidden" name="settext" value="'.$_POST['settext'].'" />';		
		echo '<input type="hidden" name="formatting" value="'.$_POST['formatting'].'" />';
	}
	if ($current_tab != 1) {
		echo '<input type="hidden" name="new_ordering" value="'.$_POST['new_ordering'].'" />';
		echo '<input type="hidden" name="new_pid" value="'.$_POST['new_pid'].'" />';
	}

	echo '<input type="hidden" name="ordering" value="'.$_POST['ordering'].'" />';
	echo  '<input type="hidden" name="pid" value="'.$pid.'" />';

	echo '<input type="hidden" name="day" value="'.$_POST['day'].'" />';
	echo '<input type="hidden" name="month" value="'.$_POST['month'].'" />';
	echo '<input type="hidden" name="year" value="'.$_POST['year'].'" />';
	echo '<input type="hidden" name="hour" value="'.$_POST['hour'].'" />';
	echo '<input type="hidden" name="minute" value="'.$_POST['minute'].'" />';

	echo '<input type="hidden" name="current_tab" value="'.$current_tab.'" />';

	if (is_array($_POST['related']) && ($current_tab != 1)) {
		foreach($_POST['related'] as $r_id) {
			echo '<input type="hidden" name="related[]" value="'.$r_id.'" />';
		}
	}

	echo '<input type="hidden" name="keywords" value="'.htmlspecialchars(stripslashes($_POST['keywords'])).'" />';

	/* get glossary terms */
	$matches = find_terms(stripslashes($_POST['body_text']));
	$num_terms = count($matches[0]);
	$matches = $matches[0];
	$word = str_replace(array('[?]', '[/?]'), '', $matches);

	if (is_array($word)) {
		/* update $_POST['glossary_defs'] with any new/changed terms */
		for($i=0; $i<$num_terms; $i++) {
			$word[$i] = urlencode($word[$i]);
			if (!isset($_POST['glossary_defs'][$word[$i]])) {
				$_POST['glossary_defs'][$word[$i]] = $glossary[$word[$i]];
			}
		}
	}

	if (is_array($_POST['glossary_defs']) && ($current_tab != 2)) {
		foreach($_POST['glossary_defs'] as $w => $d) {
			/* this term still exists in the content */
			if (!in_array($w, $word)) {
				unset($_POST['glossary_defs'][$w]);
				continue;
			}
			echo '<input type="hidden" name="glossary_defs['.$w.']" value="'.htmlspecialchars(stripslashes($d)).'" />';
		}
		if (isset($_POST['related_term'])) {
			foreach($_POST['related_term'] as $w => $d) {
				echo '<input type="hidden" name="related_term['.$w.']" value="'.$d.'" />';
			}
		}
	}

	if ($do_check) {
		$changes_made = check_for_changes($content_row);
	}

?>
<div align="center">
	<?php output_tabs($current_tab, $changes_made); ?>
</div>
<div class="input-form" style="width: 95%">

	<?php if ($changes_made): ?>
		<div class="unsaved">
			<span style="color:red;"><?php echo _AT('save_changes_unsaved'); ?></span> 
				<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" title="<?php echo _AT('save_changes'); ?> alt-s" accesskey="s" style="border: 1px solid red;" /> 
				<input type="submit" name="close" class="button green" value="<?php echo _AT('close'); ?>" />  <input type="checkbox" id="close" name="save_n_close" value="1" <?php if ($_SESSION['save_n_close']) { echo 'checked="checked"'; } ?> />
				<label for="close"><?php echo _AT('close_after_saving'); ?></label>
		</div>

	<?php else: ?>
		<div class="saved">
			<?php //if ($cid) { echo _AT('save_changes_saved'); } ?> <input type="submit" name="submit" value="<?php echo _AT('save'); ?>" title="<?php echo _AT('save_changes'); ?> alt-s" accesskey="s" /> <input type="submit" name="close" value="<?php echo _AT('close'); ?>" /> <input type="checkbox" style="border:0px;" id="close" name="save_n_close" value="1" <?php if ($_SESSION['save_n_close']) { echo 'checked="checked"'; } ?> /><label for="close"><?php echo _AT('close_after_saving'); ?></label>
		</div>
	<?php endif; ?>

	<?php include(AT_INCLUDE_PATH.'html/editor_tabs/'.$tabs[$current_tab][1]); ?>

</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>