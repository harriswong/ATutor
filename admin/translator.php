<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

/*if ( !isset($db)					|| 
	 !isset($_INCLUDE_PATH)			|| 
	 !isset($_SESSION['language'])	) {
		 
		 exit;
}*/

if ($_POST['function'] == 'edit_term') {
	if ($_POST['submit2']) {
		delete_term($_POST['v'], $_POST['k'], $_POST['page']);
	} 
	else {
		$success_error = update_term($_POST['text'], $_POST['context'], $_POST['v'], $_POST['k']);
	}
}
else if ($_POST['function'] == 'add_term') {
	$success_error = add_term($_POST['text'], $_POST['context'], $_POST['v'], $_POST['k']);
	$_REQUEST['page'] = 'none';
}

if ($_REQUEST['n']) {
	$n = ' checked="checked"';
		}
if ($_REQUEST['u']) {
	$u = ' checked="checked"';
}

if ($_SESSION['language'] != 'en') {
	echo '<li>Choose the New and Updated filters to display only language that has not been translated, or language that needs to be modified<br />';

	echo '<table border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #cccccc;"><tr><td  bgcolor="#eeeeee" nowrap="nowrap"><h5 class="heading2">Filter</h5></td><td>';
	echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">
		<input type="hidden" name="v" value="'.$_REQUEST['v'].'" />
		<input type="hidden" name="f" value="'.$_REQUEST['f'].'" /><input type="checkbox" name="n" id="n" value="1" '.$n.' /><label for="n">New Language</label>, <input type="checkbox" name="u" id="u" value="1" '.$u.'/><label for="u">Updated Language</label> <input type="submit" name="filter" value="Apply" class="button" /></form></td></tr></table><br />';
	echo '</li>';
}


//<!--//display messages and templates, with option to add new language terms/messages//-->
echo '<li>Choose Template or Msgs to display a list of language variables. Click on a variable name to display its associated language.';
echo '<ul>';
	foreach ($variables as $row) {
		echo '<li>';
		echo '<strong>';
		echo '<a href="'.$_SERVER['PHP_SELF'].'?v='.$row.SEP.'f='.$_REQUEST['f'].SEP.'n='.$_REQUEST['n'].SEP.'u='.$_REQUEST['u'].'">';
		echo ucwords(str_replace('_', '', $row));
		echo '</a>';
		echo ' | <a href="'.$_SERVER['PHP_SELF'].'?v='.$row.SEP.'new=1">new</a>';
		echo '</strong>';
		echo '</li>';
	}
	echo '</ul><br /></li>';
	echo '</ul><br /></li>';
	echo '<li> <strong><em>'.$atutor_test.'Open ATutor</a></em></strong> in another window to view the translation as it occurs. You will need to Register, login, then update your account to <strong>instructor</strong> through your Profile screen. </li></ol>';

	echo '<hr />';


	if ($_REQUEST['new']) {
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="v" value="<?php echo $_REQUEST['v']; ?>" />
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
	<input type="hidden" name="function" value="add_term" />

	<table border="0" cellspacing="0" cellpadding="3" width="75%" align="center" class="box">
	<tr>
		<th colspan="2" class="box">New</th>
	</tr>
	<tr>
		<td align="right"><b>Variable:</b></td>
		<td><tt><?php echo $_REQUEST['v'];?></tt></td>
	</tr>
	<tr>
		<td align="right"><b>Term:</b></td>
		<td><input type="text" name="k" class="input" /></td>
	</tr>
	<tr>
		<td align="right"><b>Context:</b></td>
		<td><input type="text" name="context" class="input" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b><tt><?php echo $langs[$_SESSION['language']]['name'];?></tt> text:</b></td>
		<td><textarea cols="45" rows="5" name="text" class="input2"><?php echo $row2['text'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Save ALT-S" class="submit" accesskey="s" /></td>
	</tr>
	</table>
</form>
<?php
	}
	if ($_REQUEST['v'] && $_REQUEST['k']) {
		$sql	= "SELECT * FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE term='$_REQUEST[k]' AND variable='$_REQUEST[v]' AND `language_code`='$_REQUEST[f]'";
		$result	= mysql_query($sql, $db);
		$row	= mysql_fetch_assoc($result);
		if ($row == '') {
			echo '<p>The source language was not found for that item (try using the English source).</p>';
			require (AT_INCLUDE_PATH.'footer.inc.php');
			exit;
		}

		if ($_SESSION['language'] == 'en') {
			$row2 = $row;
		} else {
			$sql	= "SELECT text FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE term='$_REQUEST[k]' AND variable='$_REQUEST[v]' AND `language_code`='$_SESSION[language]'";
		}

		$result	= mysql_query($sql, $db);
		$row2	= mysql_fetch_array($result);

function trans_form() {
	global $row0;
	global $row;
	global $row2;
	global $langs;
	global $success_error;
	global $db;
	global $_TABLE_SUFFIX;
	global $_TABLE_PREFIX;
?>
<br />
<a name="anchor"></a>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#anchor">
	<input type="hidden" name="v" value="<?php echo $row['variable']; ?>" />
	<input type="hidden" name="k" value="<?php echo $row['term']; ?>" />
	<input type="hidden" name="f" value="<?php echo $_REQUEST['f']; ?>" />
	<input type="hidden" name="page" value="<?php echo $row0['page']; ?>" />
	<input type="hidden" name="function" value="edit_term" />

	<?php ?>

	<table border="0" cellspacing="0" cellpadding="2" width="90%" align="center" class="box">
	<tr>
		<th class="box" colspan="2">Edit</th>
	</tr>
	<tr>
		<td align="right"><b>Context:</b></td>
		<td><?php
			if ($_SESSION['language'] == 'en') {
				echo '<input type="text" name="context" class="input" value="'.$row['context'].'" size="45" />';
			} else {
				if ($row['context'] == '') {
					echo '<em>None specified.</em>';
				} else {
					echo $row['context'];
				}
			} ?>&nbsp;</td>
	</tr>
	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b>Pages:</b></td>
		<td><?php 
					$sql	= "SELECT * FROM ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." WHERE `term`='$_REQUEST[k]' ORDER BY page LIMIT 11";
					$result	= mysql_query($sql, $db);

					if (mysql_num_rows($result) > 10) {
						echo '<em>Global (more than 10 pages)</em>';
					} else {
						while ($page_row = mysql_fetch_array($result)) {
							echo $page_row['page'] . '<br />';
						}
					}

				 ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b><tt><?php echo $langs[$_REQUEST['f']]['name'];?></tt> text:</b></td>
		<td><?php echo str_replace('<', '&lt;', $row['text']); ?></td>
	</tr>
	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b><tt><?php echo $langs[$_SESSION['language']]['name'];?></tt> text:</b></td>
		<td><textarea cols="55" rows="8" name="text" class="input2"><?php echo $row2['text'];?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Save ALT-S" class="button" accesskey="s" />
		&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="submit2" value=" Delete " onClick="return confirm('Do you really want to delete?');" class="button" /></td>
	</tr>
	</table>
	</form>

	<?php
		echo $success_error;
	}
}
	//displaying templates
	if ($_REQUEST['v'] == $variables[0]) {
		echo '<ul>';
		
		echo '<li><a href="'.$_SERVER['PHP_SELF'].'?v='.$_REQUEST['v'].SEP.'page=all'.SEP.'f='.$_REQUEST['f'].SEP.'n='.$_REQUEST['n'].SEP.'u='.$_REQUEST['u'].'#anchor1">View All Terms</a></li>';
		
		if ($_REQUEST['page'] == 'all') {
			echo '<a name="anchor1"></a>';
			display_all_terms($_REQUEST['v'], $_REQUEST['k'], $_REQUEST['f'], $_REQUEST['n'], $_REQUEST['u']);
		}

		echo '<li><a href="'.$_SERVER['PHP_SELF'].'?v='.$_REQUEST['v'].SEP.'page=none'.SEP.'f='.$_REQUEST['f'].SEP.'n='.$_REQUEST['n'].SEP.'u='.$_REQUEST['u'].'#anchor1">View Unused Terms</a></li>';

		if ($_REQUEST['page'] == 'none') {
			echo '<a name="anchor1"></a>';
			display_unused_terms($_REQUEST['v'], $_REQUEST['k'], $_REQUEST['f'], $_REQUEST['n'], $_REQUEST['u']);
		}

		$sql0 = "SELECT DISTINCT page FROM ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." ORDER BY page";
		$result0 = mysql_query($sql0, $db);
				
		while ($row0 = mysql_fetch_assoc($result0)) {

			if ($_REQUEST['page'] == $row0['page']) {
				echo '<a name="anchor1"></a>';
				display_page_terms($_REQUEST['v'], $_REQUEST['k'], $_REQUEST['f'], $_REQUEST['n'], $_REQUEST['u'], $row0['page']);
			}
			else {
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?v='.$_REQUEST['v'].SEP.'page='.urlencode($row0['page']).SEP.'f='.$_REQUEST['f'].SEP.'n='.$_REQUEST['n'].SEP.'u='.$_REQUEST['u'].'#anchor1">'.$row0['page'].'</a></li>';
			}
		}
		echo '</ul>';
	}	
	else {
		//displaying messages
		display_all_terms($_REQUEST['v'], $_REQUEST['k'], $_REQUEST['f'], $_REQUEST['n'], $_REQUEST['u']);
	}

require ($_INCLUDE_PATH.'footer.inc.php'); 


function delete_term($variable, $term, $page) {
	global $addslashes, $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;

	$sql = "DELETE FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND term='$term'";
	$result = mysql_query($sql, $db);

	if ($page != '') {
		$sql3 = "DELETE FROM ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." WHERE page='$page' AND term='$term'";
		$result3 = mysql_query($sql3, $db);
	}


	unset($_REQUEST['k']);
	echo '<div class="good">Success: deleted.</div>';
}

function update_term($text, $context, $variable, $term) {
	global $addslashes, $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;
	
	$term    = $addslashes(trim($term));
	$text    = $addslashes(trim($text));
	$context = $addslashes(trim($context));

	if ($_SESSION['language'] == 'en') {
		$sql	= "UPDATE ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." SET `text`='$text', revised_date=NOW(), context='$context' WHERE variable='$variable' AND term='$term' AND language_code='en'";
	}
	
	else {
		$sql	= "REPLACE INTO ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." VALUES ('$_SESSION[language]', '$variable', '$term', '$text', NOW(), '')";

		$trans = get_html_translation_table(HTML_ENTITIES);
		$trans = array_flip($trans);
		$sql = strtr($sql, $trans);
	}

	$result = mysql_query($sql, $db);
	
	if (!$result) {
		echo mysql_error($db);
		echo '<div class="error">Error: changes not saved!</div>';
		$success_error = '<div class="error">Error: changes not saved!</div>';
		return $success_error;
	}
	else {
		echo '<div class="good">Success: changes saved.</div>';
		$success_error = '<div class="good">Success: changes saved.</div>';
		return $success_error;
	}
}

function add_term($text, $context, $variable, $term) {
	global $addslashes, $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;

	$term    = $addslashes(trim($term));
	$text    = $addslashes(trim($text));
	$context = $addslashes(trim($context));

	$sql	= "INSERT INTO ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." VALUES ('en', '$variable', '$term', '$text', NOW(), '$context')";
	$result = mysql_query($sql, $db);

	if (!$result) {
		echo '<div class="error">Error: that term already exists!</div>';
		$success_error = '';		
	} else {
		echo '<div class="good">Success: term added.</div>';
		$success_error = '<div class="good">Success: term added.</div>';
		return $success_error;
	}
}

function display_page_terms ($variable, $term1, $lang_code, $new, $updated, $page) {
	global $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;
	
	echo '<li><a href="'.$_SERVER['PHP_SELF'].'?v='.$variable.SEP.'page='.urlencode($page).SEP.'f='.$lang_code.SEP.'n='.$new.SEP.'u='.updated.'#anchor">'.$page.'</a></li>';
			
	$sql1 = "SELECT term FROM ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." WHERE page='$page' ORDER BY term";
	$result1 = mysql_query($sql1, $db);

	$term_list = array();

	while ($row1 = mysql_fetch_assoc($result1)) {
	
		if ($_SESSION['language'] != 'en') {
			$sql	= "SELECT term, revised_date+0  AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND `language_code`='$_SESSION[language]' AND term='$row1[term]' ORDER BY `term`";
			$result = mysql_query($sql, $db);
								
			while ($row = mysql_fetch_assoc($result)) {
				$t_keys[$row['term']] = $row['r_date'];
			}
		}
		$term_list[] = $row1['term'];
	}

	echo '<ul>';
	
	foreach ($term_list as $term) {

		if ($_REQUEST['f'] == 'en') {
			$sql	= "SELECT *, revised_date+0 AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$_REQUEST[v]' AND language_code='en' AND term='$term'";
		} else {
			$sql	= "SELECT * FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$_REQUEST[v]' AND language_code='$_REQUEST[f]' AND term='$term'";
		}
				
		$result	= mysql_query($sql, $db);
		$row = mysql_fetch_assoc($result);

		if ($_SESSION['language'] != 'en') {
			if ($new && $updated) {
				if ((!($t_keys[$row['term']] == '')) && (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']]))) {
					continue;
				}
			} else if ($new) {
				if (!($t_keys[$row['term']] == '')) {	
					continue;
				}
			} else if ($updated) {
				if (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']])) {
					continue;
				}
			}
		}

		if ($term == $term1) {
			trans_form();
			echo '<li class="selected">';
		} else {
			echo '<li>';
		}
		echo '<small>';

		if ($_SESSION['language'] != 'en') {
			if ($t_keys[$row['term']] == '') {
				echo '<b>*New*</b> ';
			} else if ($t_keys[$term] < $row['r_date']) {
				echo '<b>*Updated*</b> ';
			}
		}

		if ($term != $term1) {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?v='.$variable.SEP.'k='.$term.SEP.'f='.$lang_code.SEP.'n='.$new.SEP.'u='.$updated.SEP.'page='.urlencode($page).'#anchor">';
			echo $term;
			echo '</a>';
		} else {
			echo $term;
		}
		echo '</small>';
		echo '</li>';
	}
	echo '</ul>';
}

function display_all_terms ($variable, $term1, $lang_code, $new, $updated) {
	global $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;

	if ($_SESSION['language'] != 'en') {
		$sql	= "SELECT term, revised_date+0  AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND `language_code`='$_SESSION[language]' ORDER BY `term`";
		$result = mysql_query($sql, $db);

		$t_keys = array();
		while ($row = mysql_fetch_assoc($result)) {
			$t_keys[$row['term']] = $row['r_date'];
		}
	}

	if ($lang_code == 'en') {
		$sql	= "SELECT *, revised_date+0 AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND language_code='en' ORDER BY term";
	} else {
		$sql	= "SELECT * FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND language_code='$lang_code' ORDER BY term";
	}
	$result	= mysql_query($sql, $db);

	echo '<ul>';
	while ($row = mysql_fetch_assoc($result)) {
		if ($_SESSION['language'] != 'en') {
			if ($new && $updated) {
				if ((!($t_keys[$row['term']] == '')) && (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']]))) {
					continue;
				}
			} else if ($new) {
				if (!($t_keys[$row['term']] == '')) {	
					continue;
				}
			} else if ($updated) {
				if (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']])) {
					continue;
				}
			}
		}


		if ($row['term'] == $term1) {
			trans_form($result);
			echo '<li class="selected">';

		} else {
			echo '<li>';
		}
		echo '<small>';
		if ($_SESSION['language'] != 'en') {
			if ($t_keys[$row['term']] == '') {
				echo '<b>*New*</b> ';
			} else if ($t_keys[$row['term']] < $row['r_date']) {
				echo '<b>*Updated*</b> ';
			}
		}

		if ($row['term'] != $term1) {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?v='.$variable.SEP.'k='.$row['term'].SEP.'page=all'.SEP.'f='.$lang_code.SEP.'n='.$new.SEP.'u='.$updated.'#anchor">';
			echo $row['term'];
			echo '</a>';
		} else {
			echo $row['term'];
		}
		echo '</small>';
		echo '</li>';
	}
	echo '</ul>';
}

function display_unused_terms ($variable, $term1, $lang_code, $new, $updated) {
	global $db, $_TABLE_PREFIX, $_TABLE_SUFFIX;

	if ($_SESSION['language'] != 'en') {
		$sql	= "SELECT term, revised_date+0  AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." WHERE variable='$variable' AND `language_code`='$_SESSION[language]' ORDER BY `term`";
		$result = mysql_query($sql, $db);

		$t_keys = array();
		while ($row = mysql_fetch_assoc($result)) {
			$t_keys[$row['term']] = $row['r_date'];
		}
	}

	if ($lang_code == 'en') {
		$sql	= "SELECT lt.*, lt.revised_date+0 AS r_date FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." lt LEFT JOIN ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." lp ON lt.term = lp.term WHERE lp.term IS NULL AND lt.variable='$variable' AND lt.language_code='en' ORDER BY lt.term";
	} else {
		$sql	= "SELECT lt.* FROM ".$_TABLE_PREFIX."language_text".$_TABLE_SUFFIX." lt LEFT JOIN ".$_TABLE_PREFIX."language_pages".$_TABLE_SUFFIX." lp ON lt.term = NULL WHERE lt.variable='$variable' AND lt.language_code='$lang_code' ORDER BY lt.term";
	}
	$result	= mysql_query($sql, $db);

	echo '<ul>';
	while ($row = mysql_fetch_assoc($result)) {
		//echo $row['r_date'].'d;flskdf;sldkf';
		if ($_SESSION['language'] != 'en') {
			if ($new && $updated) {
				if ((!($t_keys[$row['term']] == '')) && (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']]))) {
					continue;
				}
			} else if ($new) {
				if (!($t_keys[$row['term']] == '')) {	
					continue;
				}
			} else if ($updated) {
				if (!(($t_keys[$row['term']] < $row['r_date']) && $t_keys[$row['term']])) {
					continue;
				}
			}
		}


		if ($row['term'] == $term1) {
			trans_form($result);
			echo '<li class="selected">';

		} else {
			echo '<li>';
		}
		echo '<small>';
		if ($_SESSION['language'] != 'en') {
			if ($t_keys[$row['term']] == '') {
				echo '<b>*New*</b> ';
			} else if ($t_keys[$row['term']] < $row['r_date']) {
				echo '<b>*Updated*</b> ';
			}
		}

		if ($row['term'] != $term1) {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?v='.$variable.SEP.'k='.urlencode($row['term']).SEP.'page=none'.SEP.'f='.$lang_code.SEP.'n='.$new.SEP.'u='.$updated.'#anchor">';
			echo $row['term'];
			echo '</a>';
		} else {
			echo $row['term'];
		}
		echo '</small>';
		echo '</li>';
	}
	echo '</ul>';
}

?>