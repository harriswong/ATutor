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
//require_once(dirname(__FILE__) . '/BlogsUrl.class.php');
//require_once(dirname(__FILE__) . '/BrowseUrl.class.php');
//require_once(dirname(__FILE__) . '/ChatUrl.class.php');
require_once(dirname(__FILE__) . '/ContentUrl.class.php');
//require_once(dirname(__FILE__) . '/DirectoryUrl.class.php');
//require_once(dirname(__FILE__) . '/FaqUrl.class.php');
require_once(dirname(__FILE__) . '/FileStorageUrl.class.php');
require_once(dirname(__FILE__) . '/ForumsUrl.class.php');
//require_once(dirname(__FILE__) . '/GlossaryUrl.class.php');
//require_once(dirname(__FILE__) . '/GoogleSearchUrl.class.php');
require_once(dirname(__FILE__) . '/LinksUrl.class.php');
//require_once(dirname(__FILE__) . '/PollsUrl.class.php');
//require_once(dirname(__FILE__) . '/ReadingListUrl.class.php');
require_once(dirname(__FILE__) . '/TestsUrl.class.php');
//require_once(dirname(__FILE__) . '/SitemapUrl.class.php');


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
		$pathinfo = strtolower($pathinfo);
		$path_array = explode('/', $pathinfo, 4); //the first one is always empty.
		$url_obj = null;

		//Invalid ATutor URLs
		if (sizeof($path_array) < 3){
			return false;
		}

		//Check if this is using a course_slug.
		if ($_config['course_slug']==1){
			//It is using a course slug, get its relative course_id
			//TODO: Get course slug, sql? Overhead per page load
		} 
		$course_id = $pathinfo[1];

		//Check which tool type this is from
		$url_obj =& $this->getToolObject($path_array[2]);

		$this->path_array = array($course_id, $url_obj, $path_array[3]);
	}

	
	/**
	 * This function takes in the tool type string, and return the object back.
	 * If the object is not found, return null.
	 */
	function getToolObject($tool_name){
		$url_obj = null;	//initialize
		switch ($tool_name){
			case 'blogs': 
				break;
			case 'browse':
				break;
			case 'chat':
				break;
			case 'content':
			case 'content.php':
				$url_obj =& new ContentUrl();
				break;
			case 'directory':
				break;
			case 'faq':
				break;
			case 'file_storage':
				$url_obj =& new FileStorageUrl();
				break;
			case 'forum':
				$url_obj =& new ForumsUrl();
				break;
			case 'glossary':
				break;
			case 'google_search':
				break;
			case 'links':
				$url_obj =& new LinksUrl();
				break;
			case 'polls':
				break;
			case 'reading_list':
				break;
			case 'tools':
			case 'test':
				$url_obj =& new TestsUrl();
				break;
			case 'sitemap':
				break;
			default:
				break;
		}
		return $url_obj;
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
		//TODO
		//Needs to handle PHP_SELF for URL
		list($front, $end) = preg_split('/\?/', $url);

		$front_array = explode('/', $front);
		//assume the first chunk is always the class name
		foreach($front_array as $k=>$v){
			$obj = $this->getToolObject($v);  //create class object for the type
			if ($obj != null){
				break;  //break loop
			}
		}

		//if obj is still null after a full url walk
		if ($obj==null){
			return '';
		}

		//handles exception cases
		if ($obj->getClassName()=='file_storage'){
			//we need to know which file to open, ie. comments.php, index.php, or revisions.php.  
			$filepos = array_search('harris.php', $front_array)+1;
			return 'harris.php/'.$course_id.'/'.$obj->constructPrettyUrl($end, $front_array[$filepos]);
		}
		return 'harris.php/'.$course_id.'/'.$obj->constructPrettyUrl($end);
	}
}
?>