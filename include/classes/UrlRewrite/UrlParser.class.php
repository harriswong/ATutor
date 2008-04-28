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
// $Id: UrlParser.class.php 7208 2008-04-15 10:00:24Z harris $

// Add classes for the rewrite 
require_once(dirname(__FILE__) . '/UrlRewrite.class.php');

/**
* UrlParser
* Class for rewriting pretty urls.
* @access	public
* @author	Harris Wong
* @package	UrlParser
*/
class UrlParser {
	//Variables
	var $path_array;	//an array [0]->course_id; [1]->class obj; [2]->extra queries

	// Constructor
	function UrlParser($pathinfo=''){
		if ($pathinfo==''){
			$pathinfo = $_SERVER['PATH_INFO'];
		}
		$this->parsePathInfo($pathinfo);
	}

	/**
	 * This function will take in the pathinfo and return an array of elements 
	 * retrieved from the path info.
	 * An ATutor pathinfo will always be in the format of /<course_slug>/<type>/<parts>
	 * course_slug is the course_slug defined in course preference
	 * type is the forums, content, tests, blogs, etc.
	 * parts is the extra info about this url request.
	 * @param	string	the pathinfo from the URL
	 * @access	private
	 */
	function parsePathinfo($pathinfo){
		global $db;
		$pathinfo = strtolower($pathinfo);
//		debug($pathinfo);
		/* 
		 * matches[1] = course slug/id
		 * matches[2] = path
		 * matches[3] = filename
		 * matches[4] = query string in pretty format
		 * @http://ca3.php.net/preg_match
		 */
		preg_match('/(\/[\w]+)([\/\w]*)\/([\w\_\.]+\.php)([\/\w\W]*)/', $pathinfo, $matches);

		//Check if this is using a course_slug.
		if ($_config['course_dir_name']=true){
			//check if this is a course slug or course id.
			$course_id = intval(substr($matches[1], 1));
			if ($course_id==0){
				//it's a course slug, log into the course.
				$sql	= "SELECT course_id FROM ".TABLE_PREFIX."courses WHERE course_dir_name='$matches[1]'";
				$result = mysql_query($sql, $db);
				$row = mysql_fetch_assoc($result);
				$course_id = $row['course_id'];
			}
//			$_SESSION['course_id'] = $course_id;
		} 
		

		//Check which tool type this is from
		$url_obj = new UrlRewrite($matches[2], $matches[3], $matches[4]);

		$this->path_array = array($course_id, $url_obj);
	}

	
	/**
	 * return the path array
	 */
	function getPathArray(){
		return $this->path_array;
	}


	/**
	 * This function is used to convert the input URL to a pretty URL.
	 * @param	int		course id
	 * @param	string	normal URL, WITHOUT the <prototal>://<host>
	 * @return	pretty url
	 */
	function convertToPrettyUrl($course_id, $url){
		list($front, $end) = preg_split('/\?/', $url);
		$obj = $this->path_array[1];

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
		return 'harris.php/'.$course_id.'/'.$front.'/'.$obj->constructPrettyUrl($end);
	}
}
?>