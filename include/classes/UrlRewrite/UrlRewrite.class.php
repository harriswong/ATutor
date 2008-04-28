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
	var $path;		//the path of this script
	var $filename;	//script name
	var $query;		//the queries of the REQUEST

	// constructor
	function UrlRewrite($path, $filename, $query) {
		$this->path = $path;
		$this->filename = $filename;
		$this->query = $query;
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
	//deprecated
	function redirect($url){
		//redirect to that url.
	}

	//public
	function parsePrettyQuery(){
		$result = array();

		//return empty array if query is empty
		if (empty($this->query)){
			return $result;
		}
		
		//If the first char is /, cut it
		if (strpos($this->query, '/') == 0){
			$query_parts = explode('/', substr($this->query, 1));
		} else {
			$query_parts = explode('/', $this->query);
		}

		//assign dynamic pretty url
		foreach ($query_parts as $array_index=>$key_value){
			if($array_index%2 == 0 && $query_parts[$array_index]!=''){
				$result[$key_value] = $query_parts[$array_index+1];
			}
		}
		return $result;
	}


	//public
	function parseQuery($query){
		//return empty array if query is empty
		if (empty($query)){
			return array();
		}

		parse_str($this->query, $result);
		return $result;
	}


	//public
	//This method will construct a pretty url based on the given query
	function constructPrettyUrl($query){
		$pretty_url = '';		//init url
		$query_parts = explode(SEP, $query);		
		foreach ($query_parts as $index=>$attributes){
			list($key, $value) = preg_split('/\=/', $attributes, 2);
			$pretty_url .= $key . '/' . $value .'/';
		}
		return $pretty_url;
	}

	/**
	 * Return the paths where this script is
	 */
	function getPath(){
		if ($this->path != ''){
			return substr($this->path, 1).'/';
		}
		return '';
	}

	/**
	 * Return the script name
	 */
	function getFileName(){
		return $this->filename;
	}

	/**
	 * 
	 */
	function getPage(){
		return $this->getPath().$this->getFileName();
	}
}
?>