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
// $Id$

define('AT_INCLUDE_PATH', '../../include/');

require (AT_INCLUDE_PATH.'vitals.inc.php');

$path = array();

/* called at the start of en element */
/* builds the $path array which is the path from the root to the current element */
function startElement($parser, $name, $attrs) {
	global $path;
	array_push($path, $name);
}

/* called when an element ends */
/* removed the current element from the $path */
function endElement($parser, $name) {
	global $my_data, $path, $tile_title, $tile_description, $tile_identifier;

	if ($path == array('lom', 'general', 'title', 'langstring')) {
		$tile_title = $my_data;
	} else if ($path == array('lom', 'general', 'description', 'langstring')) {
		$tile_description = $my_data;
	} else if ($path == array('lom', 'general', 'identifier')) {
		$tile_identifier = $my_data;
	}

	$my_data = '';
	array_pop($path);
}

/* called when there is character data within elements */
/* constructs the $items array using the last entry in $path as the parent element */
function characterData($parser, $data){
	global $my_data;
	$my_data .= $data;
}

require (AT_INCLUDE_PATH.'header.inc.php');
	

$msg->printAll();

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>#search_results" method="get" name="form">

<div class="input-form" style="width: 40%">
	<div class="row">
		<label for="words2"><?php echo _AT('search_words'); ?></label><br />
		<input type="text" name="query" size="40" id="words2" value="<?php echo stripslashes(htmlspecialchars($_GET['query'])); ?>" />
	</div>

	<div class="row">
		<label for="words2"><?php echo _AT('search_in'); ?></label><br />

		<input type="radio" name="field" value="anyField" checked="checked" id="taf" /><label for="taf"><?php echo _AT('tile_any_field'); ?></label><br />
		<input type="radio" name="field" value="title" id="tt" /><label for="tt"><?php echo _AT('tile_title'); ?></label><br />
		<input type="radio" name="field" value="author" id="ta" /><label for="ta"><?php echo _AT('tile_author'); ?></label><br />
		<input type="radio" name="field" value="subject" id="tk" /><label for="tk"><?php echo _AT('tile_keyword'); ?></label><br />
		<input type="radio" name="field" value="description" id="td" /><label for="td"><?php echo _AT('tile_description'); ?></label><br />
		<input type="radio" name="field" value="technicalFormat" id="tf" /><label for="tf"><?php echo _AT('tile_technical_format'); ?></label>
	</div>

	<div class="row buttons">
		<input type="submit" name="submit" value="<?php echo _AT('search'); ?>" />
	</div>
</div>
</form>
<br />
<?php

if (isset($_GET['query'])) {

	require(AT_INCLUDE_PATH . 'classes/nusoap.php');

	// Create the client instance
	$client = new soapclient(AT_TILE_WSDL, true);

	// Check for an error
	$error = $client->getError();
	if ($error) {
		// Display the error

		$msg->addError('TILE_UNAVAILABLE');
		$msg->printAll();

		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}

	// Create the proxy
	$proxy = $client->getProxy();

	$search_input = array('query' => $_GET['query'], 'field' => $_GET['field'], 'content' => 'contentPackage');

	$results = $proxy->doSearch($search_input);

	if ($results) {
		$num_results = count($results);
	} else {
		$num_results = 0;
	}
	echo '<h3>'. _AT('results_found', $num_results).'</h3>';
	echo '<ol>';
	if ($num_results) {
		foreach ($results as $result) {

			$xml_parser = xml_parser_create();

			xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false); /* conform to W3C specs */
			xml_set_element_handler($xml_parser, 'startElement', 'endElement');
			xml_set_character_data_handler($xml_parser, 'characterData');

			if (!xml_parse($xml_parser, $result, true)) {
				die(sprintf("XML error: %s at line %d",
							xml_error_string(xml_get_error_code($xml_parser)),
							xml_get_current_line_number($xml_parser)));
			}

			xml_parser_free($xml_parser);

			$tile_title = str_replace('<', '&lt;', $tile_title);

			echo '<li><strong>' . $tile_title . '</strong> - <a href="'.AT_TILE_EXPORT.'?cp='.$tile_identifier.'">'._AT('download').'</a>';
			if (authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) {
				echo ' | <a href="tools/tile/import.php?cp='.$tile_identifier.SEP.'title='.urlencode($tile_title).'">'._AT('import').'</a>';
			}
			echo '<br />';
			if (strlen($tile_description) > 200) {
				echo '<small>' . $tile_description  . '</small>';
			} else {
				echo $tile_description;
			}

			echo '<br /></li>';

			unset($tile_title);
			unset($tile_description);
			unset($tile_identifier);
		}
	}
	echo '</ol>';
}
	require(AT_INCLUDE_PATH.'footer.inc.php');
?>