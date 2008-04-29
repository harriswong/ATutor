<?php
/*
Harris' tesitng file for url rewrite
*/

define('AT_INCLUDE_PATH', 'include/');
$_user_location	= 'public';

//require(AT_INCLUDE_PATH . 'vitals.inc.php');
require(AT_INCLUDE_PATH . 'classes/UrlRewrite/UrlParser.class.php');
define('AT_URL_PARSER_LOADED', true);

$pathinfo = $_SERVER['PATH_INFO'];
$url_parser = new UrlParser($pathinfo);
$path_array =  $url_parser->getPathArray();
$obj = $path_array[1];

/* 
 * Addresses the issue for relative uri 
 * @refer to constants.inc.php $_rel_link
 */
$pretty_rel_link = $obj->redirect();

if ($obj == null){
	//if there is no such rules defined.
	echo 'No such page';
	exit;
}


if ($obj != null){
	$var_query = $obj->parsePrettyQuery();
	save2Get($var_query);
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