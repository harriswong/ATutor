<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
	define('AT_INCLUDE_PATH', '../include/');
	require(AT_INCLUDE_PATH.'vitals.inc.php');


	if ($_POST['cancel']) {
		if ($_POST['pcid'] != '') {
			$msg->addFeedback('CANCELLED');
			Header('Location: ../index.php?cid='.$_POST['pcid']);
			exit;
		}
		
		$msg->addFeedback('CANCELLED');
		header('Location: ../glossary/index.php');
		exit;
	}

	if (isset($_POST['submit'])) {
		$num_terms = intval($_POST['num_terms']);

		for ($i=0; $i<$num_terms; $i++) {

			if ($_POST['ignore'][$i] == '') {
				if ($_POST['word'][$i] == '') {
					$msg->addError('TERM_EMPTY');
				}

				if ($_POST['definition'][$i] == '') {
					$msg->addError('DEFINITION_EMPTY');;
				}

				if ($terms_sql != '') {
					$terms_sql .= ', ';
				}

				$_POST['related_term'][$i] = intval($_POST['related_term'][$i]);

				/* for each item check if it exists: */

				if ($glossary[$_POST[word][$i]] != '' ) {
					$errors = array('TERM_EXISTS', $_POST[word][$i]);
					$msg->addError($errors);
				} else {
					$_POST['word'][$i]         = $addslashes($_POST['word'][$i]);
					$_POST['definition'][$i]   = $addslashes($_POST['definition'][$i]);
					$_POST['related_term'][$i] = $addslashes($_POST['related_term'][$i]);

					$terms_sql .= "(0, $_SESSION[course_id], '{$_POST[word][$i]}', '{$_POST[definition][$i]}', {$_POST[related_term][$i]})";
				}
			}
		}

		if ($errors == '') {
			$sql = "INSERT INTO ".TABLE_PREFIX."glossary VALUES $terms_sql";
			$result = mysql_query($sql, $db);

			$msg->addFeedback('GLOS_UPDATED');
			header('Location: ../glossary/index.php');
			exit;
		}
		$_GET['pcid'] = $_POST['pcid'];
	}

	$_section[0][0] = _AT('tools');
	$_section[0][1] = 'tools/';
	$_section[1][0] = _AT('glossary');
	$_section[1][1] = 'glossary/';
	$_section[2][0] = _AT('add_glossary');

	$onload = 'onload="document.form.title0.focus()"';

	unset($word);

	$num_terms = 1;

	require(AT_INCLUDE_PATH.'header.inc.php');
	
	$msg->printAll();

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<div class="input-form">
	<input type="hidden" name="num_terms" value="<?php echo $num_terms; ?>" />
<?php
	for ($i=0;$i<$num_terms;$i++) {
		if ($glossary[$word[$i]] != '') {
			echo '<input type="hidden" name="ignore['.$i.']" value="1" />';
			continue;
		}
		
		for ($j=0;$j<$i;$j++) {
			if ($word[$j] == $word[$i]) {
				echo '<input type="hidden" name="ignore['.$i.']" value="1" />';
				continue 2;
			}
		}

		if ($word[$i] == '') {
			$word[$i] = ContentManager::cleanOutput($_POST['word'][$i]);
		}
?>
		<div class="row">
			<label for="title<?php echo $i; ?>"><?php echo _AT('glossary_term');  ?>:</label><br />
			<input type="text" name="word[<?php echo $i; ?>]" size="30" value="<?php echo trim($word[$i]); ?>" id="title<?php echo $i; ?>" /><?php			
			if ($_GET['pcid'] != '') { 
				echo '<input type="checkbox" name="ignore['.$i.']" value="1" id="ig'.$i.'" /><label for="ig'.$i.'">Ignore this term</label>.';	
			}
			?>
		</div>

		<div class="row">
			<label for="body<?php echo $i; ?>"><?php echo _AT('glossary_definition');  ?>:</label><br />
			<textarea name="definition[<?php echo $i; ?>]" class="formfield" cols="55" rows="7" id="body<?php echo $i; ?>"><?php echo ContentManager::cleanOutput($_POST['definition'][$i]); ?></textarea>
		</div>

		<div class="row">
			<?php echo _AT('glossary_related');  ?>:<br />
<?php
				$sql = "SELECT * FROM ".TABLE_PREFIX."glossary WHERE course_id=$_SESSION[course_id] ORDER BY word";
				$result = mysql_query($sql, $db);
				if ($row_g = mysql_fetch_assoc($result)) {
					echo '<select name="related_term['.$i.']">';
					echo '<option value="0"></option>';
					do {
						echo '<option value="'.$row_g['word_id'].'">'.$row_g['word'].'</option>';
					} while ($row_g = mysql_fetch_assoc($result));
					echo '</select>';
				} else {
					echo _AT('none_available');
				}
			} // endfor
?>
		</div>

		<div class="row buttons">
			<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" />
			<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
		</div>

	</div>
	</form>

<?php
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>