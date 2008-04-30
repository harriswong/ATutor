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
	function redirect(){
		//redirect to that url.
		return '/'.$this->getPage();
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
	 * This function is used to convert the input URL to a pretty URL.
	 * @param	int		course id
	 * @param	string	normal URL, WITHOUT the <prototal>://<host>
	 * @return	pretty url
	 */
	function convertToPrettyUrl($course_id, $url){
		list($front, $end) = preg_split('/\?/', $url);

		$front_array = explode('/', $front);
//		debug($front_array);

		//find out what kind of link this is, pretty url? relative url? or PHP_SELF url?
		$dir_deep	 = substr_count(AT_INCLUDE_PATH, '..');
		$url_parts	 = explode('/', $_SERVER['PHP_SELF']);
		$host_dir	 = implode('/', array_slice($url_parts, 0, count($url_parts) - $dir_deep-1));

		//The relative link is a pretty URL
		if(in_array('harris.php', $front_array)===TRUE){
			$front_result = array();			
			//spit out the URL in between 'harris.php' to *.php
			//note, pretty url is defined to be harris.php/course_slug/type/location/...
			//ie. harris.php/1/forum/view.php/...
			$needle = array_search('harris.php', $front_array);
			$front_array = array_slice($front_array, $needle + 2);  //+2 because we want the entries after the course_slug
			//cut off everything at the back
			foreach($front_array as $fk=>$fv){
				array_push($front_result, $fv);
				if 	(preg_match('/\.php/', $fv)==1){
					break;
				}
			}
			$front = implode('/', $front_result);
		} elseif (strpos($front, $host_dir)!==FALSE){
//			debug('here');
			//Not a relative link, it contains the full PHP_SELF path.
			$front = substr($front, strlen($host_dir)+1);  //stripe off the slash after the host_dir as well
		}
		return 'harris.php/'.$course_id.'/'.$front.'/'.$this->constructPrettyUrl($end);
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