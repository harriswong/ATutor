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

/**
* UrlRewrite
* Class for rewriting pretty urls.
* @access	public
* @author	Harris Wong
* @package	UrlRewrite
*/
class UrlRewrite  {
	// local variables
	var $rule;		//an array that maps [lvl->query parts]
	var $className;	//the name of this class

	// constructor
	function UrlRewrite() {
	}

	// public 
	function setRule($rule) {
		echo 'parent setting the rule';
		$this->rule = $rule;
	}

	/**
	 * This will return the class name of the function.
	 */
	function setClassName($className){
		$this->className = $className;
	}

	// protected
	function getRule($rule_key) {
		return 'i am the parent: '.$rule;
	}

	// public
	//deprecated
	function redirect($url){
		//redirect to that url.
	}

	//public 
	function parsePrettyUrl($query){
		//return empty array if query is empty
		if (empty($query)){
			return array();
		}
		return explode('/', $query);
	}


	//public
	function parseQuery($query){
		//return empty array if query is empty
		if (empty($query)){
			return array();
		}
		parse_str($query, $result);
		return $result;

	}


	/**
	 * This will return the class name of the function.
	 */
	function getClassName(){
		return $this->className;
	}
}
?>