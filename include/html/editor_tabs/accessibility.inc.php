<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$
if (!defined('AT_INCLUDE_PATH')) { exit; }

require_once(AT_INCLUDE_PATH.'classes/Message/Message.class.php');

global $savant;
$msg =& new Message($savant);

//make decisions
if ($_POST['desc_submit']) {
	//get list of decisions	
	$desc_query = '';
	if (is_array($_POST['d'])) {
		for($i=0; $i<count($_POST['d']); $i++) {
			$desc_query .= '&'.$i.'='.$_POST['d'][$i];
		}

		$checker_url = AT_ACHECKER_URL. 'decisions;'
					.'jsessionid='.$_POST['sessionid']
					.'?file='.urlencode($_POST['pg_url'])
					.'&output=chunk'
					.'&name='.$_SESSION['login']
					.'&email='.urlencode($_base_href)
					.$desc_query;

		if (@file_get_contents($checker_url) === false) {
			$msg->addInfo('DECISION_NOT_SAVED');
		}

	} else {
		$msg->addInfo('DECISION_NOT_SAVED');
	}
} else if (isset($_POST['reverse'])) {
	list($achecker_id, $achecker_element, $achecker_identifier) = explode('_', key($_POST['reverse']), 3);

	$reverse_url = AT_ACHECKER_URL . 'decisions;'
					.'jsessionid='.$_POST['sessionid']
					.'?file='.urlencode($_POST['pg_url'])
					.'&lang=eng'
					.'&reverse=true'
					.$achecker_element
					.$achecker_identifier
					.'&checkid='.$achecker_id;



	if (@file_get_contents($reverse_url) === false) {
		$msg->addInfo('DECISION_NOT_REVERSED');
	} else {
		$msg->addInfo('DECISION_REVERSED');
	}
}

?>
	<tr>
		<td colspan="2" valign="top" align="left" class="row1">
		<?php 					
			echo '<input type="hidden" name="body_text" value="'.htmlspecialchars(stripslashes($_POST['body_text'])).'" />';

			if (!$cid) {
				$msg->printInfos('SAVE_CONTENT');

				echo '</td>
					</tr>';

				return;
			}

		$msg->printInfos();
		if ($_POST['body_text'] != '') {
			//save temp file
			$_POST['content_path'] = $content_row['content_path'];
			write_temp_file();

			$pg_url = $_base_href.'get_acheck.php/'.$_POST['cid'] . '.html';

			$checker_url = AT_ACHECKER_URL.'Checkacc?file='.urlencode($pg_url)
							. '&guide=wcag-1-0-aa&output=chunk&line=5'
							. '&vurl=' . urlencode($_base_href . 'editor/view_item.php');

			$report = @file_get_contents($checker_url);

			if ($report == 1) {
				$msg->printErrors('INVALID_URL');
			} else if ($report === false) {
				$msg->printInfos('SERVICE_UNAVAILABLE');
			} else {
				echo '<input type="hidden" name="pg_url" value="'.$pg_url.'" />';
				echo $report;	

				echo '<p>'._AT('access_credit').'</p>';
			}
			//delete file
			@unlink(AT_CONTENT_DIR . $_POST['cid'] . '.html');
		
		} else {
			$msg->printInfos('NO_PAGE_CONTENT');
		} 

	?>
		</td>
	</tr>