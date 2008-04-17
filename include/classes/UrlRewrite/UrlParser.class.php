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
/*
require_once(dirname(__FILE__) . '/BlogsUrl.class.php');
require_once(dirname(__FILE__) . '/BrowseUrl.class.php');
require_once(dirname(__FILE__) . '/ChatUrl.class.php');
require_once(dirname(__FILE__) . '/ContentUrl.class.php');
require_once(dirname(__FILE__) . '/DirectoryUrl.class.php');
require_once(dirname(__FILE__) . '/FaqUrl.class.php');
require_once(dirname(__FILE__) . '/FileStorageUrl.class.php');
*/
require_once(dirname(__FILE__) . '/ForumsUrl.class.php');
/*
require_once(dirname(__FILE__) . '/GlossaryUrl.class.php');
require_once(dirname(__FILE__) . '/GoogleSearchUrl.class.php');
require_once(dirname(__FILE__) . '/LinksUrl.class.php');
require_once(dirname(__FILE__) . '/PollsUrl.class.php');
require_once(dirname(__FILE__) . '/ReadingListUrl.class.php');
require_once(dirname(__FILE__) . '/TestsUrl.class.php');
require_once(dirname(__FILE__) . '/SitemapUrl.class.php');
*/

/**
* UrlParser
* Class for rewriting pretty urls.
* @access	public
* @author	Harris Wong
* @package	UrlParser
*/
class UrlParser {
	// Constructor
	function UrlParser(){
	}

	/**
	 * This function will take in the pathinfo and return an array of elements 
	 * retrieved from the path info.
	 * An ATutor pathinfo will always be in the format of /<course_slug>/<type>/<parts>
	 * course_slug is the course_slug defined in course preference
	 * type is the forums, content, tests, blogs, etc.
	 * parts is the extra info about this url request.
	 * @param	string	the pathinfo from the URL
	 * @access	public
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

		//Check which course type this is from
		switch ($path_array[2]){
			case 'blogs': 
				break;
			case 'browse':
				break;
			case 'chat':
				break;
			case 'content':
				break;
			case 'directory':
				break;
			case 'faq':
				break;
			case 'file_storage':
				break;
			case 'forums':
				$url_obj =& new ForumsUrl();
				break;
			case 'glossary':
				break;
			case 'google_search':
				break;
			case 'links':
				break;
			case 'polls':
				break;
			case 'reading_list':
				break;
			case 'tests':
				break;
			case 'sitemap':
				break;
		}
		return array($course_id, $url_obj, $path_array[3]);
	}
}



?>