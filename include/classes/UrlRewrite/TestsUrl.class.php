<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/

require_once(dirname(__FILE__) . '/UrlRewrite.class.php');

/**
* TestsUrl
* Class for rewriting pretty urls in tests
* @access	public
* @author	Harris Wong
* @package	UrlParser
*/
class TestsUrl extends UrlRewrite {
	// local variables
	var $rule;		//an array that maps [lvl->query parts]

	// constructor
	function TestsUrl() {
		$this->rule = array(0=>'tid', 1=>'rid');
	}

	// public
	function getRule($rule_key){

	}

	//
	function setRule($rule){
		echo 'child setting the rule';
		$this->rule = $rule;
	}

	// public
	// deprecated
	function redirect($parts){
		$sublvl = parent::parseParts($parts);
		//0=>fid 1=>pid
		$query = '';
		foreach($sublvl as $order=>$label){
			//construct query
			$query .= $this->rule[$order].'='.$label.'&';
		}
		$query = substr(trim($query), 0, -1);
//		return 'forum/view.php?'.$query;
		return 'tools/my_tests.php';
	}

	// public
	/**
	 * This method will read the parts and tries to put it together as an array.
	 * So that this can get assigned to the GET/POST/REQUEST variable.
	 * @param	string	this is the query after /forums/
	 * @return	an array of parts mapped by their query rules.
	 */
	function parts2Array($parts){
		$sublvl = parent::parseParts($parts);
		$result = array();

		//if there are no extra query, link it to the defaulted page
		if (empty($sublvl)){
			$result['page_to_load'] = 'tools/my_tests.php';
		}
		foreach ($sublvl as $order => $label){
			if ($this->rule[$order]=='tid'){
				$result['page_to_load'] = 'tools/test_intro.php';
			} elseif ($this->rule[$order]=='rid'){
				$result['page_to_load'] = 'tools/view_results.php';
			}
			$result[$this->rule[$order]] = $label;
		}
		return $result;
	}

	function getClassName(){
		return 'tests';
	}


}
?>