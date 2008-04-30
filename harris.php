<?php
/*
Harris' tesitng file for url rewrite
*/

define('AT_INCLUDE_PATH', 'include/');
$_user_location	= 'public';  //like browse, and registration, doesn't need username/passwords to get into

//	require_once(AT_INCLUDE_PATH . 'vitals.inc.php');
require_once(AT_INCLUDE_PATH . 'classes/UrlRewrite/UrlParser.class.php');
define('AT_REDIRECT_LOADED', true);
include_once(AT_INCLUDE_PATH.'config.inc.php');
require(AT_INCLUDE_PATH.'lib/constants.inc.php');
require_once(AT_INCLUDE_PATH.'lib/mysql_connect.inc.php');

$pathinfo = $_SERVER['PATH_INFO'];
$url_parser = new UrlParser($pathinfo);
$path_array =  $url_parser->getPathArray();
$AT_PRETTY_URL_COURSE_ID = $path_array[0];

$obj = $path_array[1];

/* 
 * Addresses the issue for relative uri 
 * @refer to constants.inc.php $_rel_link
 */
$_rel_url = $obj->redirect();

//check if we are in the requested course, if not, bounce to it.
//if ($_SESSION['course_id'] != $course_id){
//	debug('why am i being loaded..stop it stop it!!!!!!');exit;
//	header('Location: '.AT_BASE_HREF.'bounce.php?course='.$course_id);
//	exit;
//}

if ($obj == null){
	//if there is no such rules defined.
	echo 'No such page';
	exit;
}

if ($obj != null){
	$var_query = $obj->parsePrettyQuery();
	save2Get($var_query);	//remake all the _GET and _REQUEST variables so that the vitals can use it
	$_user_location	= '';	//reset user_location so that the vital file in each page would validate
	$pretty_current_page = $obj->getPage();
//	debug($obj->constructPrettyUrl('a=3&b=4&c=5&d=21=123=1\'23=1\'=2'));
	include($obj->getPage()); 
} 

function save2Get($var_query){
	if (empty($var_query))
		return;
	foreach($var_query as $k=>$v){
		if ($k=='page_to_load'){
			continue;
		}
		$_GET[$k] = $v;
		$_REQUEST[$k] = $v;
	}
}
?>