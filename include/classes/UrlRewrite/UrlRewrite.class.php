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
	var $rule;

	// constructor
	function UrlRewrite() {
	}

	// public 
	function setRule($rule) {
		echo 'parent setting the rule';
		$this->rule = $rule;
	}

	// protected
	function getRule($rule_key) {
		return 'i am the parent: '.$rule;
	}

	// public
	function redirect($url){
		//redirect to that url.
	}

	//public 
	function parseParts($query){
		//return empty array if query is empty
		if (empty($query)){
			return array();
		}
		return explode('/', $query);
	}


}
?>