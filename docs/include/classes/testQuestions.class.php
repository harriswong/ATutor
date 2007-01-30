<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: results_all_quest.php 6702 2007-01-23 18:45:57Z joel $

/**
 * Used to create question objects based on $question_type.
 * A singleton that creates one obj per question since
 * questions are all stateless.
 * Returns a reference to the question object.
 */
function & test_question_factory($question_type) {
	static $objs, $question_classes;

	if (isset($objs[$question_type])) {
		return $objs[$question_type];
	}
	if (!isset($question_classes)) {
		$question_classes = array();
		$question_classes[AT_TESTS_MC]       = 'MultichoiceQuestion';
		$question_classes[AT_TESTS_TF]       = 'TruefalseQuestion';
		$question_classes[AT_TESTS_LONG]     = 'LongQuestion';
		$question_classes[AT_TESTS_LIKERT]   = 'LikertQuestion';
		$question_classes[AT_TESTS_MATCHING] = 'MatchingQuestion';
		$question_classes[AT_TESTS_ORDERING] = 'OrderingQuestion';
	}

	if (isset($question_classes[$question_type])) {
		global $savant;
		$objs[$question_type] =& new $question_classes[$question_type]($savant);
	} else {
		return FALSE;
	}

	return $objs[$question_type];
}

function test_question_qti_export(/* array */ $question_ids) {
	require(AT_INCLUDE_PATH.'classes/zipfile.class.php'); // for zipfile
	require(AT_INCLUDE_PATH.'lib/html_resource_parser.inc.php'); // for get_html_resources()
	require(AT_INCLUDE_PATH.'classes/XML/XML_HTMLSax/XML_HTMLSax.php');	// for XML_HTMLSax

	global $savant, $db, $system_courses, $languageManager;

	$course_language = $system_courses[$_SESSION['course_id']]['primary_language'];
	$courseLanguage =& $languageManager->getLanguage($course_language);
	$course_language_charset = $courseLanguage->getCharacterSet();

	$zipfile = new zipfile();
	$zipfile->create_dir('resources/'); // for all the dependency files
	$resources    = array();
	$dependencies = array();

	asort($question_ids);

	$question_ids_delim = implode(',',$question_ids);
	$sql = "SELECT * FROM ".TABLE_PREFIX."tests_questions WHERE course_id=$_SESSION[course_id] AND question_id IN($question_ids_delim)";
	$result = mysql_query($sql, $db);

	while ($row = mysql_fetch_assoc($result)) {
		$obj = test_question_factory($row['type']);
		$xml = $obj->exportQTI($row, $course_language_charset);
		$local_dependencies = array();

		$text_blob = implode(' ', $row);
		$local_dependencies = get_html_resources($text_blob);
		$dependencies = array_merge($dependencies, $local_dependencies);

		$resources[] = array('href'         => 'question_'.$row['question_id'].'.xml',
							 'dependencies' => array_keys($local_dependencies));

		$zipfile->add_file($xml, 'question_'.$row['question_id'].'.xml');
	}

	// add any dependency files:
	foreach ($dependencies as $resource => $resource_server_path) {
		$zipfile->add_file(@file_get_contents($resource_server_path), 'resources/' . $resource, filemtime($resource_server_path));
	}

	// construct the manifest xml
	$savant->assign('resources', $resources);
	$savant->assign('dependencies', array_keys($dependencies));
	$savant->assign('encoding', $course_language_charset);
	$manifest_xml = $savant->fetch('test_questions/manifest_qti_2p1.tmpl.php');

	$zipfile->add_file($manifest_xml, 'imsmanifest.xml');

	$zipfile->close();

	$filename = str_replace(array(' ', ':'), '_', $_SESSION['course_title'].'-'._AT('question_database').'-'.date('Ymd'));
	$zipfile->send_file($filename);
	exit;
}


/**
 * testQuestion
 *
 * Note that all PHP 5 OO declarations and signatures are commented out to be
 * backwards compatible with PHP 4.
 *
 */
/*abstract */ class AbstractTestQuestion  {
	/**
	* Savant2 $savant - refrence to the savant obj
	*/
	/*protected */ var $savant;

	/**
	* int $count - keeps count of the question number (when displaying the question)
	*/
	/*protected */static $count = 0;

	// abstract public function qtiExport();
	// abstract public function qtiImport();

	/**
	* Constructor method.  Initialises variables.
	*/
	function __construct(&$savant) {
		$this->savant =& $savant;
	}

	/**
	* Public interface for resetting the question counter
	*/
	/*final public */function resetCounter() {
		self::$count = 0;
	}

	/**
	* Display the current question (for taking or previewing a test/question)
	*/
	/*final public */function display($row) {
		// print the generic question header
		$this->displayHeader($row['weight']);

		// print the question specific template
		$this->assignDisplayVariables($row);
		$this->savant->display('test_questions/' . $this->getDisplayTemplateName($row));
		
		// print the generic question footer
		$this->displayFooter();
	}

	/**
	* Display the result for the current question
	*/
	/*final public */function displayResult($row, $answer_row, $editable = FALSE) {
		// print the generic question header
		$this->displayHeader($row['weight'], (int) $answer_row['score'], $editable ? $row['question_id'] : FALSE);

		// print the question specific template
		$this->assignDisplayResultVariables($row, $answer_row);
		$this->savant->display('test_questions/' . $this->getDisplayResultTemplateName($row));
		
		// print the generic question footer
		$this->displayFooter();
	}


	/**
	* print the question template header
	*/
	/*final public */function displayResultStatistics($row, $answers) {
		self::$count++;

		$this->assignDisplayStatisticsVariables($row, $answers);
		$this->savant->display('test_questions/' . $this->getDisplayResultStatisticsTemplateName( ));
	}

	/*final public */function exportQTI($row, $encoding) {
		$this->savant->assign('encoding', $encoding);
		$this->assignQTIVariables($row);
		$xml = $this->savant->fetch('test_questions/'. $this->getQTITemplateName());

		return $xml;
	}


	/**
	* print the question template header
	*/
	/*final private */function displayHeader($weight, $score = FALSE, $question_id = FALSE) {
		self::$count++;

		$this->savant->assign('question_id', $question_id);
		$this->savant->assign('score', $score);
		$this->savant->assign('weight', $weight);
		$this->savant->assign('type',   _AT($this->sType));
		$this->savant->assign('number', self::$count);
		$this->savant->display('test_questions/header.tmpl.php');
	}

	/**
	* print the question template footer
	*/
	/*final private */function displayFooter() {
		$this->savant->display('test_questions/footer.tmpl.php');
	}

	/**
	* return only the non-empty choices from $row.
	* assumes choices are sequential.
	*/
	/*protected */function getChoices($row) {
		$choices = array();
		for ($i=0; $i < 10; $i++) {
			if ($row['choice_'.$i] != '') {
				$num_choices++;
				$choices[] = $row['choice_'.$i];
			} else {
				break;
			}
		}
		return $choices;
	}
}

/**
* orderingQuestion
*
*/
class OrderingQuestion extends AbstractTestQuestion {
	/*protected */ var $template = 'ordering.tmpl.php';
	/*protected */ var $sType = 'test_ordering';

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		$answers = explode('|', $answer_row['answer']);

		$num_choices = count($this->getChoices($row));

		global $_base_href;

		$this->savant->assign('base_href', $_base_href);
		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('answers', $answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignQTIVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		// determine the number of choices this question has
		// and save those choices to be re-assigned back to $row
		// in the randomized order.
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		// randomize the order of choices and re-assign to $row
		ordering_seed($row['question_id']);
		$rand = array_rand($choices, $num_choices);
		for ($i=0; $i < 10; $i++) {
			$row['choice_'.$i] = $choices[$rand[$i]];
		}

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$num_results = 0;		
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}

		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$final_answers = array(); // assoc array of # of times that key was used correctly 0, 1, ...  $num -1
		foreach ($answers as $key => $value) {
			$values = explode('|', $key);
			// we assume $values is never empty and contains $num number of answers
			for ($i=0; $i<=$num_choices; $i++) {
				if ($values[$i] == $i) {
					$final_answers[$i] += $answers[$key]['count'];
				}
			}
		}

		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('answers', $final_answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) { return $this->template; }
	/*protected */function getDisplayResultTemplateName($row) { return 'ordering_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName() { return 'ordering_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { return 'ordering_qti_2p1.tmpl.php'; }


	/*public */function mark($row) { 

		ordering_seed($row['question_id']);
		$num_choices = count($_POST['answers'][$row['question_id']]);
		$answers = range(0, $num_choices-1);
		$answers = array_rand($answers, $num_choices);

		$num_answer_correct = 0;

		$ordered_answers = array();

		for ($i = 0; $i < $num_choices ; $i++) {
			$_POST['answers'][$row['question_id']][$i] = intval($_POST['answers'][$row['question_id']][$i]);

			if ($_POST['answers'][$row['question_id']][$i] == -1) {
				// nothing to do. it was left blank
			} else if ($_POST['answers'][$row['question_id']][$i] == $answers[$i]) {
				$num_answer_correct++;
			}
			$ordered_answers[$answers[$i]] = $_POST['answers'][$row['question_id']][$i];
		}
		ksort($ordered_answers);

		$score = 0;

		// to avoid roundoff errors:
		if ($num_answer_correct == $num_choices) {
			$score = $row['weight'];
		} else if ($num_answer_correct > 0) {
			$score = number_format($row['weight'] / $num_choices * $num_answer_correct, 2);
			if ( (float) (int) $score == $score) {
				$score = (int) $score; // a whole number with decimals, eg. "2.00"
			} else {
				$score = trim($score, '0'); // remove trailing zeros, if any, eg. "2.50"
			}
		}

		$_POST['answers'][$row['question_id']] = implode('|', $ordered_answers);

		return $score;
	}
}

/**
* truefalseQuestion
*
*/
class TruefalseQuestion extends AbstracttestQuestion {
	/*protected */ var $template = 'truefalse.tmpl.php';
	/*protected */ var $sType = 'test_tf';

	/*protected */function assignQTIVariables($row) {
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		global $_base_href;

		$this->savant->assign('base_href', $_base_href);
		$this->savant->assign('answers', $answer_row['answer']);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$num_results = 0;		
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}

		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('num_blanks', (int) $answers['-1']['count']);
		$this->savant->assign('num_true', (int) $answers['1']['count']);
		$this->savant->assign('num_false', (int) $answers['2']['count']);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) { return $this->template; }
	/*protected */function getDisplayResultTemplateName($row) { return 'truefalse_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName() { return 'truefalse_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { return 'truefalse_qti_2p1.tmpl.php'; }

	/*public */function mark($row) { 
		$_POST['answers'][$row['question_id']] = intval($_POST['answers'][$row['question_id']]);

		if ($row['answer_0'] == $_POST['answers'][$row['question_id']]) {
			return (int) $row['weight'];
		} // else:
		return 0;
	}
}

/**
* likertQuestion
*
*/
class LikertQuestion extends AbstracttestQuestion {
	/*protected */ var $template = 'likert.tmpl.php';
	/*protected */ var $sType = 'test_lk';

	/*protected */function assignQTIVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		$this->savant->assign('answer', $answer_row['answer']);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$num_results = 0;		
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}
		
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$sum = 0;
		for ($i=0; $i<$num_choices; $i++) {
			$sum += ($i+1) * $answers[$i]['count'];
		}
		$average = round($sum/$num_results, 1);

		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('average', $average);
		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('num_blanks', (int) $answers['-1']['count']);
		$this->savant->assign('answers', $answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) { return $this->template; }
	/*protected */function getDisplayResultTemplateName($row) { return 'likert_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName() { return 'likert_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { return 'likert_qti_2p1.tmpl.php'; }

	/*public */function mark($row) { 
		$_POST['answers'][$row['question_id']] = intval($_POST['answers'][$row['question_id']]);
		return 0;
	}
}

/**
* longQuestion
*
*/
class LongQuestion extends AbstracttestQuestion {
	/*protected */ var $template = 'long.tmpl.php';
	/*protected */ var $sType = 'test_open';

	/*protected */function assignQTIVariables($row) {
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		$this->savant->assign('answer', $answer_row['answer']);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$num_results = 0;		
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}
		
		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('num_blanks', (int) $answers['']['count']);
		$this->savant->assign('answers', $answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) { return $this->template; }
	/*protected */function getDisplayResultTemplateName($row) { return 'long_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName() { return 'long_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { return 'long_qti_2p1.tmpl.php'; }

	/*public */function mark($row) { 
		global $addslashes;
		$_POST['answers'][$row['question_id']] = $addslashes($_POST['answers'][$row['question_id']]);
		return 0;
	}
}

/**
* matchingQuestion
*
*/
class MatchingQuestion extends AbstracttestQuestion {
	/*protected */ var $template = 'matching.tmpl.php';
	/*protected */ var $sType = 'test_matching';

	/*protected */function assignQTIVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$num_options = 0;
		for ($i=0; $i < 10; $i++) {
			if ($row['option_'. $i] != '') {
				$num_options++;
			}
		}

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('num_options', $num_options);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		$num_options = 0;
		for ($i=0; $i < 10; $i++) {
			if ($row['option_'. $i] != '') {
				$num_options++;
			}
		}

		$answer_row['answer'] = explode('|', $answer_row['answer']);

		global $_letters, $_base_href;

		$this->savant->assign('base_href', $_base_href);
		$this->savant->assign('answers', $answer_row['answer']);
		$this->savant->assign('letters', $_letters);
		$this->savant->assign('num_options', $num_options);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$num_options = 0;
		for ($i=0; $i < 10; $i++) {
			if ($row['option_'. $i] != '') {
				$num_options++;
			}
		}
		
		global $_letters, $_base_href;

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('base_href', $_base_href);
		$this->savant->assign('letters', $_letters);
		$this->savant->assign('num_options', $num_options);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$num_results = 0;
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}
					
		foreach ($answers as $key => $value) {
			$values = explode('|', $key);
			if (count($values) > 1) {
				for ($i=0; $i<count($values); $i++) {
					$answers[$values[$i]]['count']++;
				}
			}
		}

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('answers', $answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) {
		if ($row['properties'] == 1) {
			// drag n drop
			return 'matchingdd.tmpl.php';
		} // else:
	
		return $this->template;
	}
	/*protected */function getDisplayResultTemplateName($row) { return 'matching_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName() { return 'matching_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { return 'matching_qti_2p1.tmpl.php'; }

	/*public */function mark($row) { 
		$num_choices = count($_POST['answers'][$row['question_id']]);
		$num_answer_correct = 0;
		foreach ($_POST['answers'][$row['question_id']] as $item_id => $response) {
			if ($row['answer_' . $item_id] == $response) {
				$num_answer_correct++;
			}
			$_POST['answers'][$row['question_id']][$item_id] = intval($_POST['answers'][$row['question_id']][$item_id]);
		}

		$score = 0;
		// to avoid roundoff errors:
		if ($num_answer_correct == $num_choices) {
			$score = $row['weight'];
		} else if ($num_answer_correct > 0) {
			$score = number_format($row['weight'] / $num_choices * $num_answer_correct, 2);
			if ( (float) (int) $score == $score) {
				$score = (int) $score; // a whole number with decimals, eg. "2.00"
			} else {
				$score = trim($score, '0'); // remove trailing zeros, if any
			}
		}

		$_POST['answers'][$row['question_id']] = implode('|', $_POST['answers'][$row['question_id']]);

		return $score;
	}
}

/**
* multichoiceQuestion
*
*/
class MultichoiceQuestion extends AbstracttestQuestion {
	/*protected */var $template = 'multichoice.tmpl.php';
	/*protected */var $sType = 'test_mc';

	/*protected */function assignQTIVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayResultVariables($row, $answer_row) {
		if (array_sum(array_slice($row, 16, -6)) > 1) {
			$answer_row['answer'] = explode('|', $answer_row['answer']);
		} else {
			$answer_row['answer'] = array($answer_row['answer']);
		}

		global $_base_href;

		$this->savant->assign('base_href', $_base_href);
		$this->savant->assign('answers', $answer_row['answer']);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayVariables($row) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('row', $row);
	}

	/*protected */function assignDisplayStatisticsVariables($row, $answers) {
		$choices = $this->getChoices($row);
		$num_choices = count($choices);

		$num_results = 0;
		foreach ($answers as $answer) {
			$num_results += $answer['count'];
		}
					
		foreach ($answers as $key => $value) {
			$values = explode('|', $key);
			if (count($values) > 1) {
				for ($i=0; $i<count($values); $i++) {
					$answers[$values[$i]]['count']++;
				}
			}
		}


		$this->savant->assign('num_choices', $num_choices);
		$this->savant->assign('num_results', $num_results);
		$this->savant->assign('num_blanks', (int) $answers['-1']['count']);
		$this->savant->assign('answers', $answers);
		$this->savant->assign('row', $row);
	}

	/*protected */function getDisplayTemplateName($row) {
		$total_answers = 0;
		for ($i=0; $i < 10; $i++) {
			$total_answers += $row['answer_'.$i];
		}
		if ($total_answers > 1) {
			return 'multianswer.tmpl.php';
		} // else:
		return $this->template;
	}
	/*protected */function getDisplayResultTemplateName($row) { return 'multichoice_result.tmpl.php'; }
	/*protected */function getDisplayResultStatisticsTemplateName( ) { return 'multichoice_stats.tmpl.php'; }
	/*protected */function getQTITemplateName() { 
		$total_answers = 0;
		for ($i=0; $i < 10; $i++) {
			$total_answers += $row['answer_'.$i];
		}
		if ($total_answers > 1) {
			return 'multianswer_qti_2p1.tmpl.php';
		} // else:
		return 'multichoice_qti_2p1.tmpl.php';
	}

	/*public */function mark($row) { 
		$num_correct = array_sum(array_slice($row, 3));
		if ($num_correct > 1) {
			// multi correct
			if (is_array($_POST['answers'][$row['question_id']]) && count($_POST['answers'][$row['question_id']]) > 1) {
				if (($i = array_search('-1', $_POST['answers'][$row['question_id']])) !== FALSE) {
					unset($_POST['answers'][$row['question_id']][$i]);
				}
				$num_answer_correct = 0;
				foreach ($_POST['answers'][$row['question_id']] as $item_id => $answer) {
					if ($row['answer_' . $answer]) {
						// correct answer
						$num_answer_correct++;
					} else {
						// wrong answer
						$num_answer_correct--;
					}
					$_POST['answers'][$row['question_id']][$item_id] = intval($_POST['answers'][$row['question_id']][$item_id]);
				}
				if ($num_answer_correct == $num_correct) {
					$score = $row['weight'];
				} else {
					$score = 0;
				}
				$_POST['answers'][$row['question_id']] = implode('|', $_POST['answers'][$row['question_id']]);
			} else {
				// no answer given
				$_POST['answers'][$row['question_id']] = '-1'; // left blank
				$score = 0;
			}
		} else {
			// single correct answer
			$_POST['answers'][$row['question_id']] = intval($_POST['answers'][$row['question_id']]);
			if ($row['answer_' . $_POST['answers'][$row['question_id']]]) {
				$score = $row['weight'];
			} else if ($_POST['answers'][$row['question_id']] == -1) {
				$has_answer = 0;
				for($i=0; $i<10; $i++) {
					$has_answer += $row['answer_'.$i];
				}
				if (!$has_answer && $row['weight']) {
					// If MC has no answer and user answered "leave blank"
					$score = $row['weight'];
				}
			}
		}
		return $score;
	}
}
?>