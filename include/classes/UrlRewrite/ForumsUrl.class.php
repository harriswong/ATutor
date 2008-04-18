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
* UrlParser
* Class for rewriting pretty urls.
* @access	public
* @author	Harris Wong
* @package	UrlParser
*/
class ForumsUrl extends UrlRewrite {
	// local variables
	var $rule;		//an array that maps [lvl->query parts]

	// constructor
	function ForumsUrl() {
		$this->rule = array(0=>'fid', 1=>'pid');
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
		return 'forum/view.php';
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
			$result['page_to_load'] = 'forum/list.php';
		}
		foreach ($sublvl as $order => $label){
			if ($this->rule[$order]=='pid'){
				$result['page_to_load'] = 'forum/view.php';
			} elseif ($this->rule[$order]=='fid'){
				$result['page_to_load'] = 'forum/index.php';
			}
			$result[$this->rule[$order]] = $label;
		}
		return $result;
	}

	function getClassName(){
		return 'forums';
	}


}
?>