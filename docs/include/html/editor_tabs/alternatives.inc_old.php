<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: alternatives.inc.php 7208 2008-07-04 16:07:24Z silvia $

if (!defined('AT_INCLUDE_PATH')) { exit; }
global $db;

//echo $_POST['current_tab'];


function get_files($path) {
	//creiamo un ciclo che legga i contenuti e li metta nell'array
	$dir = opendir($path);
	while ($file = readdir($dir)) {
		$file_array[] = $file;
	}	
	//facciamo un ciclo foreach per prendere gli elementi
	foreach ($file_array as $file) {
	//tutti tranne quelli che iniziano per "." o per ".."
		if ( $file == ".." || $file == ".") {
			continue;
			}
		$nomeFile = explode('.', $file);
		if ($nomeFile[1] == 'db')
			continue;
		else
			if ($nomeFile[0] == 'imsmanifest')
				continue;
			else
				if ($nomeFile[1] != ''){
					echo '<div class="resource_box">';
					echo '<table>';
					echo '<tr>';
					echo '<td>'.$file.'</td>';
					echo '<td>';
					echo '<fieldset>';
					echo '<legend>Type:</legend>';
					echo '<input type="checkbox" name="auditory" value="auditory" id="auditory"/>';
					echo '<label for="auditory">Auditory</label><br/>';
					echo '<input type="checkbox" name="tactile" value="tactile" id="tactile"/>';
					echo '<label for="tactile">Tactile</label><br/>';
					echo '<input type="checkbox" name="textual" value="textual" id="textual"/>';
					echo '<label for="textual">Textual</label><br/>';
					echo '<input type="checkbox" name="visual" value="visual" id="visual"/>';
					echo '<label for="visual">Visual</label>';
					echo '</fieldset>';
					echo '</td>';
					echo '<td>';
					echo 'Primary Resource:<br/>';
					echo '<select name="primary">';
			   		echo '<option value="-">-</option>';
					// qui ci vanno SOLO le risorse primarie
					echo '</td>';
					
					echo '</tr>';
					echo '</table>';
					echo '</div>';
				}
				else 
				{
					$newpath = $path.'/'.$file;
					get_files($newpath);
				}
	}
	return true;
}
?>
<!--	<div class="row">
		<p>Add alternatives: 
			<script type="text/javascript" language="javascript">
				document.write(" <a href=\"#\" onclick=\"window.open('<?php //echo AT_BASE_HREF; ?>tools/filemanager/index.php?framed=1<?php //echo SEP; ?>popup=1<?php //echo SEP; ?>cp=<?php //echo $content_row['content_path']; ?>','newWin1','menubar=0,scrollbars=1,resizable=1,width=640,height=490'); return false;\"><?php //echo _AT('open_file_manager'); ?> </a>");
			
			</script>
			<noscript>
				<a href="<?php //echo AT_BASE_HREF; ?>tools/filemanager/index.php?framed=1"><?php //echo _AT('open_file_manager'); ?></a>
			</noscript>		
		</p>	
	</div>-->
	<div class="row_alternatives">
		<div class="column_primary">
			
		<!--	<p>Define alternatives to the single resources.</p> -->
		<?php 
	
		//require(AT_INCLUDE_PATH.'classes/XML/XML_HTMLSax/XML_HTMLSax.php');	/* for XML_HTMLSax */

//		echo $changes_made[0];
//		echo ";;";
		
/*		if ($do_check) {
		$changes_made = check_for_changes($content_row);
	}*/
		
/*		$sql	= "SELECT title, text FROM ".TABLE_PREFIX."content WHERE content_id=".$cid;
		
		$result	= mysql_query($sql, $db);
				
		if (mysql_num_rows($result) > 0) {
		//	echo '<ul style="padding: 0px; list-style: none;">';
			while ($row = mysql_fetch_assoc($result)) {
			//	$type = 'class="user"';
			//	if ($system_courses[$_SESSION['course_id']]['member_id'] == $row['member_id']) {
			//		$type = 'class="user instructor" title="'._AT('instructor').'"';
			//	}
			//	echo '<li style="padding: 3px 0px;"><a href="'.$_base_path.'profile.php?id='.$row['member_id'].'" '.$type.'>'.AT_print($row['login'], 'members.login').'</a></li>';
				echo '<input type="radio" name="alternatives" value="0" id="resource1"/>';
				echo '<label for="resource1">Define alternatives to resource1.</label>';
				echo '<div class="resource_box">';
				$title=$row['title'];
				echo $title;
				
				$sql	= "SELECT resource FROM ".TABLE_PREFIX."primary_resources WHERE resource='".$title."'";
				$res	= mysql_query($sql, $db);
				//$n= mysql_num_rows($res);
				
				if (mysql_num_rows($res) == 0) {
						$sql_ins= "INSERT INTO ".TABLE_PREFIX."primary_resources VALUES (NULL, $cid, '$title', NULL)";
						$r 		= mysql_query($sql_ins, $db);
						$sql_ins= "SELECT primary_resource_id FROM ".TABLE_PREFIX."primary_resources WHERE resource='".$title."' AND content_id=$cid";
						$r 		= mysql_query($sql_ins, $db);
						
						if (mysql_num_rows($r) > 0){
							while ($rw = mysql_fetch_assoc($r)){
								$sql_ins= "INSERT INTO ".TABLE_PREFIX."primary_resources_types VALUES ($rw[primary_resource_id], 3)";
								$re		= mysql_query($sql_ins, $db);
							}
						}
					} 
				echo '</div>';
				
				$page_content = $row['text'];
				//$parsed_content= array();
				  
				

				$content = split("<", $page_content);
				$i = count($content);
				if ($i == 1){
					echo '<input type="radio" name="alternatives" value="0" id="resource2"/>';
					echo '<label for="resource2">Define alternatives to resource2.</label>';
					echo '<div class="resource_box">';
					echo $content[0];
					echo '</div>';}
				else {
				$n=1;
				$j=1;
				while ($i > 0){
					$closed_content = split(">", $content[$n]);
					if ($closed_content[0] != '' and $closed_content[0] != ' ' and $closed_content[0] != '   '){
						$parsed_content[$j] = "<".$closed_content[0].">";
						$j++;
					}
					if ($closed_content[1] != '' and $closed_content[0] != ' ' and $closed_content[0] != '   '){
						$parsed_content[$j] = $closed_content[1];
						$j++;
					}
					$n++;
					$i--;
				}
				
				echo '<input type="radio" name="alternatives" value="0" id="title1"/>';
				echo '<label for="title1">Define alternatives to title1.</label>';
				echo '<div class="resource_box">';
				//echo $parsed_content[2];
			
				echo '</div>';
				}
			} 
			
		} else {
			echo '<em>'._AT('none_found').'</em><br />';
		}
		*/
		
define('AT_INCLUDE_PATH', '../../include/');
/* content id of an optional chapter */
$cid = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;
$c   = isset($_REQUEST['c'])   ? intval($_REQUEST['c'])   : 0;

$instructor_id   = $system_courses[$course_id]['member_id'];
$course_desc     = $system_courses[$course_id]['description'];
$course_title    = $system_courses[$course_id]['title'];
$course_language = $system_courses[$course_id]['primary_language'];

$courseLanguage =& $languageManager->getLanguage($course_language);
//$course_language_charset = $courseLanguage->getCharacterSet();
//$course_language_code = $courseLanguage->getCode();

require(AT_INCLUDE_PATH.'classes/XML/XML_HTMLSax/XML_HTMLSax.php');	/* for XML_HTMLSax */
require(AT_INCLUDE_PATH.'ims/ims_template.inc.php');				/* for ims templates + print_organizations() */

		/*
	the following resources are to be identified:
	even if some of these can't be images, they can still be files in the content dir.
	theoretically the only urls we wouldn't deal with would be for a <!DOCTYPE and <form>

	img		=> src
	a		=> href				// ignore if href doesn't exist (ie. <a name>)
	object	=> data | classid	// probably only want data
	applet	=> classid | archive			// whatever these two are should double check to see if it's a valid file (not a dir)
	link	=> href
	script	=> src
	form	=> action
	input	=> src
	iframe	=> src

*/
class MyHandler {
    function MyHandler(){}
    function openHandler(& $parser,$name,$attrs) {
		global $my_files;

		$name = strtolower($name);
		$attrs = array_change_key_case($attrs, CASE_LOWER);

		$elements = array(	'img'		=> 'src',
							'a'			=> 'href',				
							'object'	=> array('data', 'classid'),
							'applet'	=> array('classid', 'archive'),
							'link'		=> 'href',
							'script'	=> 'src',
							'form'		=> 'action',
							'input'		=> 'src',
							'iframe'	=> 'src',
							'embed'		=> 'src',
							'param'		=> 'value');
	
		/* check if this attribute specifies the files in different ways: (ie. java) */
		if (is_array($elements[$name])) {
			$items = $elements[$name];

			foreach ($items as $item) {
				if ($attrs[$item] != '') {

					/* some attributes allow a listing of files to include seperated by commas (ie. applet->archive). */
					if (strpos($attrs[$item], ',') !== false) {
						$files = explode(',', $attrs[$item]);
						foreach ($files as $file) {
							$my_files[] = trim($file);
						}
					} else {
						$my_files[] = $attrs[$item];
					}
				}
			}
		} else if (isset($elements[$name]) && ($attrs[$elements[$name]] != '')) {
			/* we know exactly which attribute contains the reference to the file. */
			$my_files[] = $attrs[$elements[$name]];
		}
    }
    function closeHandler(& $parser,$name) { }
}

/* get all the content */
$content = array();
$paths	 = array();
$top_content_parent_id = 0;

$handler=new MyHandler();
$parser =& new XML_HTMLSax();
$parser->set_object($handler);
$parser->set_element_handler('openHandler','closeHandler');

if (authenticate(AT_PRIV_CONTENT, AT_PRIV_RETURN)) {
	$sql = "SELECT *, UNIX_TIMESTAMP(last_modified) AS u_ts FROM ".TABLE_PREFIX."content WHERE content_id=$cid ORDER BY content_parent_id, ordering";
} else {
	$sql = "SELECT *, UNIX_TIMESTAMP(last_modified) AS u_ts FROM ".TABLE_PREFIX."content WHERE content_id=$cid ORDER BY content_parent_id, ordering";
}

$result = mysql_query($sql, $db);
while ($row = mysql_fetch_assoc($result)) {
	if (authenticate(AT_PRIV_CONTENT, AT_PRIV_RETURN) || $contentManager->isReleased($row['content_id']) === TRUE) {
		$content[$row['content_parent_id']][] = $row;
		if ($cid == $row['content_id']) {
			$top_content = $row;
			$top_content_parent_id = $row['content_parent_id'];
		}
	}
}


//SILVIA: forse questo controllo è superfluo
if ($cid) {
	/* filter out the top level sections that we don't want */
	$top_level = $content[$top_content_parent_id];
	foreach($top_level as $page) {
		if ($page['content_id'] == $cid) {
			$content[$top_content_parent_id] = array($page);
		} else {
			/* this is a page we don't want, so might as well remove it's children too */
			unset($content[$page['content_id']]);
		}
	}
//	$ims_course_title = $course_title . ' - ' . $content[$top_content_parent_id][0]['title'];
} //else {
//	$ims_course_title = $course_title;
//}


/* generate the imsmanifest.xml header attributes */
/*$imsmanifest_xml = str_replace(array('{COURSE_TITLE}', '{COURSE_DESCRIPTION}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'), 
							  array($ims_course_title, $course_desc, $course_language_charset, $course_language_code),
							  $ims_template_xml['header']);*/
//debug($imsmanifest_xml);
//exit;

/* get the first content page to default the body frame to */
$first = $content[$top_content_parent_id][0];

/* generate the resources and save the HTML files */

$used_glossary_terms = array();

ob_start();
//print_organizations($top_content_parent_id, $content, 0, '', array(), $toc_html);
							 
	global $html_content_template, $default_html_style, $zipfile, $resources, $ims_template_xml, $parser, $my_files;
	global $used_glossary_terms, $course_id, $course_language_charset, $course_language_code;
	static $paths, $zipped_files;
	global $glossary;

	$space  = '    ';
	$prefix = '                    ';
    $depth = 0;
    $parent_id = $top_content_parent_id;
    $_menu = $content;
    $path= '';
    $children=array();
    $string=$toc_html;
	 
/*	if ($depth == 0) {
		$string .= '<ul>';
	}*/
	$top_level = $_menu[$parent_id];
	if (!is_array($paths)) {
		$paths = array();
	}
	if (!is_array($zipped_files)) {
		$zipped_files = array();
	}
	if ( is_array($top_level) ) {
		
		$counter = 1;
		$num_items = count($top_level);
		foreach ($top_level as $garbage => $content) {
			$link = '';
				
			if ($content['content_path']) {
				$content['content_path'] .= '/';
			}

			$link = $prevfix.'<item identifier="MANIFEST01_ITEM'.$content['content_id'].'" identifierref="MANIFEST01_RESOURCE'.$content['content_id'].'" parameters="">'."\n";
			$html_link = '<a href="resources/'.$content['content_path'].$content['content_id'].'.html" target="body">'.$content['title'].'</a>';
			
			/* save the content as HTML files */
			/* @See: include/lib/format_content.inc.php */
			$content['text'] = str_replace('CONTENT_DIR/', '', $content['text']);
			/* get all the glossary terms used */
			/*$terms = find_terms($content['text']);
			if (is_array($terms)) {
				foreach ($terms[2] as $term) {
					$used_glossary_terms[] = $term;
				}
			}*/

			/* calculate how deep this page is: */
			$path = '../';
			if ($content['content_path']) {
				$depth = substr_count($content['content_path'], '/');

				$path .= str_repeat('../', $depth);
			}
			
			$content['text'] = format_content($content['text'], $content['formatting'], $glossary, $path);

			/* add HTML header and footers to the files */
			
			/* use default style if <style> is not in imported html head */
			/*
			$head = '';
			if ($content['use_customized_head'])
			{
				if (stripos($content['head'], '<style') > 0)
				{
					$head = $content['head'];
				}
				else
				{
					if (strlen($content['head']) > 0)  
						$head = $content['head'] . $default_html_style;
					else 
						$head = $default_html_style;
				}
			}

			$content['text'] = str_replace(	array('{TITLE}',	'{CONTENT}', '{KEYWORDS}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}', '{HEAD}'),
									array($content['title'],	$content['text'], $content['keywords'], $course_language_charset, $course_language_code, $head),
									$html_content_template);
			*/					
			/* duplicate the paths in the content_path field in the zip file */
			/*
			if ($content['content_path']) {
				if (!in_array($content['content_path'], $paths)) {
					$zipfile->create_dir('resources/'.$content['content_path'], time());
					$paths[] = $content['content_path'];
				}
			}*/

			//questa riga di sotto è da scommentare!
			//$zipfile->add_file($content['text'], 'resources/'.$content['content_path'].$content['content_id'].'.html', $content['u_ts']);
			$content['title'] = htmlspecialchars($content['title']);

			/* add the resource dependancies */
			$my_files = array();
			$content_files = "\n";
			$parser->parse($content['text']);

			/* handle @import */
			$import_files = get_import_files($content['text']);
			
			if (count($import_files) > 0) $my_files = array_merge($my_files, $import_files);
			
			foreach ($my_files as $file) {
				/* filter out full urls */
				$url_parts = @parse_url($file);
				if (isset($url_parts['scheme'])) {
					continue;
				}

				/* file should be relative to content. let's double check */
				if ((substr($file, 0, 1) == '/')) {
					continue;
				}

				
				echo '<a href="'.$file.'" target="_blank">'.$file.'</a>';

				echo ' ; ';
			//	$file_path = realpath(AT_CONTENT_DIR . $course_id . '/' . $content['content_path'] . $file);

				/* check if this file exists in the content dir, if not don't include it */
			/*	if (file_exists($file_path) && 	is_file($file_path) && !in_array($file_path, $zipped_files)) {
					$zipped_files[] = $file_path;
					$dir = substr(dirname($file_path), strlen(AT_CONTENT_DIR . $course_id));

					if (!in_array($dir, $paths) && $dir) {
						$dir = str_replace('\\', '/', substr($dir, 1));
						$zipfile->create_dir('resources/' . $dir, time());
						
						$paths[] = $dir;
					}

					$file_info = stat( $file_path );
					$zipfile->add_file(@file_get_contents($file_path), 'resources/' . $content['content_path'] . $file, $file_info['mtime']);

					$content_files .= str_replace('{FILE}', $content['content_path'] . $file, $ims_template_xml['file']);
				}*/
			}

			/******************************/
		/*	$resources .= str_replace(	array('{CONTENT_ID}', '{PATH}', '{FILES}'),
										array($content['content_id'], $content['content_path'], $content_files),
										$ims_template_xml['resource']);

*//*
			for ($i=0; $i<$depth; $i++) {
				$link .= $space;
			}
			
			$title = $prefix.$space.'<title>'.$content['title'].'</title>';

			if ( is_array($_menu[$content['content_id']]) ) {
				/* has children */
/*
				$html_link = '<li>'.$html_link.'<ul>';
				for ($i=0; $i<$depth; $i++) {
					if ($children[$i] == 1) {
						echo $space;
						//$html_link = $space.$html_link;
					} else {
						echo $space;
						//$html_link = $space.$html_link;
					}
				}

			} else {
				/* doesn't have children */
/*
				$html_link = '<li>'.$html_link.'</li>';
				if ($counter == $num_items) {
					for ($i=0; $i<$depth; $i++) {
						if ($children[$i] == 1) {
							echo $space;
							//$html_link = $space.$html_link;
						} else {
							echo $space;
							//$html_link = $space.$html_link;
						}
					}
				} else {
					for ($i=0; $i<$depth; $i++) {
						echo $space;
						//$html_link = $space.$html_link;
					}
				}
				$title = $space.$title;
			}

			echo $prefix.$link;
			echo $title;
			echo "\n";

			$string .= $html_link."\n";

			$depth ++;
			print_organizations($content['content_id'],
								$_menu, 
								$depth, 
								$path.$counter.'.', 
								$children,
								$string);
			$depth--;

			$counter++;
			for ($i=0; $i<$depth; $i++) {
				echo $space;
			}
			echo $prefix.'</item>';
			echo "\n";
		}
		$string .= '</ul>';
		if ($depth > 0) {
			$string .= '</li>';
		}

*/	}
}

$organizations_str = ob_get_contents();
ob_end_clean();

echo $organizations_str;


if (count($used_glossary_terms)) {
	$used_glossary_terms = array_unique($used_glossary_terms);
	sort($used_glossary_terms);
	reset($used_glossary_terms);

	$terms_xml = '';
	foreach ($used_glossary_terms as $term) {
		$term_key = urlencode($term);
		$glossary[$term_key] = str_replace('&', '&amp;', $glossary[$term_key]);
		$escaped_term = str_replace('&', '&amp;', $term);
		$terms_xml .= str_replace(	array('{TERM}', '{DEFINITION}'),
									array($escaped_term, $glossary[$term_key]),
									$glossary_term_xml);

		$terms_html .= str_replace(	array('{ENCODED_TERM}', '{TERM}', '{DEFINITION}'),
									array($term_key, $term, $glossary[$term_key]),
									$glossary_term_html);
	}

	$glossary_body_html = str_replace('{BODY}', $terms_html, $glossary_body_html);

	$glossary_xml = str_replace(array('{GLOSSARY_TERMS}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}'),
							    array($terms_xml, $course_language_charset),
								$glossary_xml);
	$glossary_html = str_replace(	array('{CONTENT}', '{KEYWORDS}', '{TITLE}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'),
									array($glossary_body_html, '', 'Glossary', $course_language_charset, $course_language_code),
									$html_template);
	$toc_html .= '<ul><li><a href="glossary.html" target="body">'._AT('glossary').'</a></li></ul>';
} else {
	unset($glossary_xml);
}

$toc_html = str_replace(array('{TOC}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'),
					    array($toc_html, $course_language_charset, $course_language_code),
						$html_toc);

if ($first['content_path']) {
	$first['content_path'] .= '/';
}
$frame = str_replace(	array('{COURSE_TITLE}',		'{FIRST_ID}', '{PATH}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'),
						array($ims_course_title, $first['content_id'], $first['content_path'], $course_language_charset, $course_language_code),
						$html_frame);

$html_mainheader = str_replace(array('{COURSE_TITLE}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'),
							   array($ims_course_title, $course_language_charset, $course_language_code),
							   $html_mainheader);



/* append the Organizations and Resources to the imsmanifest */
/*$imsmanifest_xml .= str_replace(	array('{ORGANIZATIONS}',	'{RESOURCES}', '{COURSE_TITLE}'),
									array($organizations_str,	$resources, $ims_course_title),
									$ims_template_xml['final']);


*/
		
		/*	echo '<h2>'.AT_print($stripslashes($_POST['title']), 'content.title').'</h2>';
	
			if ($_POST['body_text']) {
				echo format_content($stripslashes($_POST['body_text']), $_POST['formatting'], $_POST['glossary_defs']);
			} else { 
				global $msg;
			
				$msg->printInfos('NO_PAGE_CONTENT');
		
			}*/

		?>		

		</div>

		
		<div class="column_equivalent">
		<?php 
		
		
		// from tools/filemanager/index.php
		if ((isset($_REQUEST['popup']) && $_REQUEST['popup']) && 
	(!isset($_REQUEST['framed']) || !$_REQUEST['framed'])) {
	$popup = TRUE;
	$framed = FALSE;
} else if (isset($_REQUEST['framed']) && $_REQUEST['framed'] && isset($_REQUEST['popup']) && $_REQUEST['popup']) {
	$popup = TRUE;
	$framed = TRUE;
} else {
	$popup = FALSE;
	$framed = FALSE;
}
// fine tools/filemanager/index.php

// tools/filemanager/top.php
require(AT_INCLUDE_PATH.'lib/filemanager.inc.php');

if (!$_GET['f']) {
	$_SESSION['done'] = 0;
}
if (!authenticate(AT_PRIV_FILES,AT_PRIV_RETURN)) {
	authenticate(AT_PRIV_CONTENT);
}


$current_path = AT_CONTENT_DIR.$_SESSION['course_id'].'/';

if (isset($_POST['rename'])) {
	if (!is_array($_POST['check'])) {
		// error: you must select a file/dir to rename
		$msg->addError('NO_FILE_SELECT');
	} else if (count($_POST['check']) != 1) {
		// error: you must select one file/dir to rename
		$msg->addError('NO_ITEM_SELECTED');
	} else {
		header('Location: rename.php?pathext='.urlencode($_POST['pathext']).SEP.'framed='.$framed.SEP.'popup='.$popup.SEP.'oldname='.urlencode($_POST['check'][0]));
		exit;
	}
} else if (isset($_POST['edit'])) {
	if (!isset($_POST['check'][0])) {
		// error: you must select a file/dir 
		$msg->addError('NO_FILE_SELECT');
	} else if (count($_POST['check']) != 1) {
		// error: you must select one file/dir to rename
		$msg->addError('NO_ITEM_SELECTED');
	} else {
		$file = $_POST['check'][0];
		header('Location: edit.php?pathext='.urlencode($_POST['pathext']).SEP.'framed='.$framed.SEP.'popup='.$popup.SEP.'file=' . $file);
		exit;
	}
} else if (isset($_POST['delete'])) {
	
	if (!is_array($_POST['check'])) {
		$msg->addError('NO_FILE_SELECT');
	} else {

		$list = implode(',', $_POST['check']);
		header('Location: delete.php?pathext=' . urlencode($_POST['pathext']) . SEP . 'framed=' . $framed . SEP . 'popup=' . $popup . SEP . 'list=' . urlencode($list));
		exit;
	}
} else if (isset($_POST['move'])) {

	if (!is_array($_POST['check'])) {
		$msg->addError('NO_FILE_SELECT');
	} else {

		$list = implode(',', $_POST['check']);		
		header('Location: move.php?pathext='.urlencode($_POST['pathext']).SEP.'framed='.$framed.SEP.'popup='.$popup.SEP.'list='.urlencode($list));
		exit;
	}
}

$MakeDirOn = true;

/* get this courses MaxQuota and MaxFileSize: */
$sql	= "SELECT max_quota, max_file_size FROM ".TABLE_PREFIX."courses WHERE course_id=$_SESSION[course_id]";
$result = mysql_query($sql, $db);
$row	= mysql_fetch_array($result);
$my_MaxCourseSize	= $row['max_quota'];
$my_MaxFileSize		= $row['max_file_size'];

if ($my_MaxCourseSize == AT_COURSESIZE_DEFAULT) {
	$my_MaxCourseSize = $MaxCourseSize;
}
if ($my_MaxFileSize == AT_FILESIZE_DEFAULT) {
	$my_MaxFileSize = $MaxFileSize;
} else if ($my_MaxFileSize == AT_FILESIZE_SYSTEM_MAX) {
	$my_MaxFileSize = megabytes_to_bytes(substr(ini_get('upload_max_filesize'), 0, -1));
}

$MaxSubDirs  = 5;
$MaxDirDepth = 10;

if ($_GET['pathext'] != '') {
	$pathext = urldecode($_GET['pathext']);
} else if ($_POST['pathext'] != '') {
	$pathext = $_POST['pathext'];
}

if (strpos($pathext, '..') !== false) {
	require(AT_INCLUDE_PATH.'header.inc.php');
	$msg->printErrors('UNKNOWN');	
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}
if($_GET['back'] == 1) {
	$pathext  = substr($pathext, 0, -1);
	$slashpos = strrpos($pathext, '/');
	if($slashpos == 0) {
		$pathext = '';
	} else {
		$pathext = substr($pathext, 0, ($slashpos+1));
	}

}

$start_at = 2;
/* remove the forward or backwards slash from the path */
$newpath = $current_path;
$depth = substr_count($pathext, '/');

if ($pathext != '') {
	$bits = explode('/', $pathext);
	foreach ($bits as $bit) {
		if ($bit != '') {
			$bit_path .= $bit;

			$_section[$start_at][0] = $bit;
			$_section[$start_at][1] = '../tools/filemanager/index.php?pathext=' . urlencode($bit_path) . SEP . 'popup=' . $popup . SEP . 'framed=' . $framed;

			$start_at++;
		}
	}
	$bit_path = "";
	$bit = "";
}

/* if upload successful, close the window */
if ($f) {
	$onload = 'closeWindow(\'progWin\');';
}

/* make new directory */
if ($_POST['mkdir_value'] && ($depth < $MaxDirDepth) ) {
	$_POST['dirname'] = trim($_POST['dirname']);

	/* anything else should be okay, since we're on *nix..hopefully */
	$_POST['dirname'] = ereg_replace('[^a-zA-Z0-9._]', '', $_POST['dirname']);

	if ($_POST['dirname'] == '') {
		$msg->addError(array('FOLDER_NOT_CREATED', $_POST['dirname'] ));
	} 
	else if (strpos($_POST['dirname'], '..') !== false) {
		$msg->addError('BAD_FOLDER_NAME');
	}	
	else {
		$result = @mkdir($current_path.$pathext.$_POST['dirname'], 0700);
		if($result == 0) {
			$msg->addError(array('FOLDER_NOT_CREATED', $_POST['dirname'] ));
		}
		else {
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		}
	}
}

$newpath = substr($current_path.$pathext, 0, -1);

/* open the directory */
if (!($dir = @opendir($newpath))) {
	if (isset($_GET['create']) && ($newpath.'/' == $current_path)) {
		@mkdir($newpath);
		if (!($dir = @opendir($newpath))) {
			require(AT_INCLUDE_PATH.'header.inc.php');
			$msg->printErrors('CANNOT_CREATE_DIR');			
			require(AT_INCLUDE_PATH.'footer.inc.php');
			exit;
		} else {
			$msg->addFeedback('CONTENT_DIR_CREATED');
		}
	} else {
		require(AT_INCLUDE_PATH.'header.inc.php');

		$msg->printErrors('CANNOT_OPEN_DIR');
		require(AT_INCLUDE_PATH.'footer.inc.php');
		exit;
	}
}

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
}

//fine header.inc.php







// fine tools/filemanager/top.php		
		
		//require(AT_INCLUDE_PATH.'html/filemanager_display.inc.php');

		
function get_file_extension($file_name) {
	$ext = pathinfo($file_name);
	return $ext['extension'];
}

function get_file_type_icon($file_name) {
	static $mime;

	$ext = get_file_extension($file_name);

	if (!isset($mime)) {
		require(AT_INCLUDE_PATH .'lib/mime.inc.php');
	}

	if (isset($mime[$ext]) && $mime[$ext][1]) {
		return $mime[$ext][1];
	}
	return 'generic';
}

function get_relative_path($src, $dest) {
	if ($src == '') {
		$path = $dest;
	} else if (substr($dest, 0, strlen($src)) == $src) {
		$path = substr($dest, strlen($src) + 1);
	} else {
		$path = '../' . $dest;
	}

	return $path;
}

// get the course total in Bytes 
$course_total = dirsize($current_path);

$framed = intval($_GET['framed']);
$popup = intval($_GET['popup']);


if (defined('AT_FORCE_GET_FILE') && AT_FORCE_GET_FILE) {
	$get_file = 'get.php/';
} else {
	$get_file = 'content/' . $_SESSION['course_id'] . '/';
}


echo '<p>'._AT('current_path').' ';

if ($pathext != '') {
	echo '<a href="'.$_SERVER['PHP_SELF'].'?popup=' . $popup . SEP . 'framed=' . $framed.'">'._AT('home').'</a> ';
}
else {
	echo _AT('home');
}



if ($pathext == '') {
	$pathext = urlencode($_POST['pathext']);
}

if ($pathext != '') {
	$bits = explode('/', $pathext);

	foreach ($bits as $bit) {
		if ($bit != '') {
			$bit_path .= $bit . '/';
			echo ' / ';

			if ($bit_path == $pathext) {
				echo $bit;
			} else {
				echo '<a href="'.$_SERVER['PHP_SELF'].'?pathext=' . urlencode($bit_path) . SEP . 'popup=' . $popup . SEP . 'framed=' . $framed . '">' . $bit . '</a>';
			}
		}
	}
	$bit_path = "";
	$bit = "";
}
echo '</p>';



if ($popup == TRUE) {
	$totalcol = 6;
} else {
	$totalcol = 5;
}
$labelcol = 3;

if (TRUE || $framed != TRUE) {

	if ($_GET['overwrite'] != '') {
		// get file name, out of the full path
		$path_parts = pathinfo($current_path.$_GET['overwrite']);

		if (!file_exists($path_parts['dirname'].'/'.$pathext.$path_parts['basename'])
			|| !file_exists($path_parts['dirname'].'/'.$pathext.substr($path_parts['basename'], 5))) {
			/* source and/or destination does not exist */
			$msg->addErrors('CANNOT_OVERWRITE_FILE');
		} else {
			@unlink($path_parts['dirname'].'/'.$pathext.substr($path_parts['basename'], 5));
			$result = @rename($path_parts['dirname'].'/'.$pathext.$path_parts['basename'], $path_parts['dirname'].'/'.$pathext.substr($path_parts['basename'], 5));

			if ($result) {
				$msg->addFeedback('FILE_OVERWRITE');
			} else {
				$msg->addErrors('CANNOT_OVERWRITE_FILE');
			}
		}
	}
	
	// filemanager listing table
	// make new directory 
	echo '<fieldset><legend class="group_form">'._AT('add').'</legend>';
	echo '<table cellspacing="1" cellpadding="0" border="0" summary="" align="center">';
	echo '<tr><td colspan="2">';
	echo '<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'].'?pathext='.urlencode($pathext).SEP. 'popup='.$popup .SEP.'tab='.$current_tab.'">';
	//$_GET['current_tab'] = $current_tab;
	//echo $_GET['current_tab'];
	
	
	if( $MakeDirOn ) {
		if ($depth < $MaxDirDepth) {
			echo '<input type="text" name="dirname" size="20" /> ';
			echo '<input type="hidden" name="mkdir_value" value="true" /> ';
			echo '<input type="submit" name="mkdir" value="'._AT('create_folder').'" class="button" />';
			echo '&nbsp;<small class="spacer">'._AT('keep_it_short').'';
		} else {
			echo _AT('depth_reached');
		}
	}
	echo '<input type="hidden" name="pathext" value="'.$pathext.'" />';
	echo '<input type="hidden" name="current_tab" value="'.$current_tab.'" />';
	echo '</form></td></tr>';

	$my_MaxCourseSize = $system_courses[$_SESSION['course_id']]['max_quota'];

	// upload file 
	if (($my_MaxCourseSize == AT_COURSESIZE_UNLIMITED) 
		|| (($my_MaxCourseSize == AT_COURSESIZE_DEFAULT) && ($course_total < $MaxCourseSize))
		|| ($my_MaxCourseSize-$course_total > 0)) {
		echo '<tr><td  colspan="1">';
		echo '<form onsubmit="openWindow(\''.AT_BASE_HREF.'tools/prog.php\');" name="form1" method="post" action="tools/filemanager/upload.php?popup='.$popup.'" enctype="multipart/form-data">';
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$my_MaxFileSize.'" />';
		echo '<input type="file" name="uploadedfile" class="formfield" size="20" />';
		echo '<input type="submit" name="submit" value="'._AT('upload').'" class="button" />';
		echo '<input type="hidden" name="pathext" value="'.$pathext.'" />  ';
		echo _AT('or'); 
		echo ' <a href="../tools/filemanager/new.php?pathext=' . urlencode($pathext) . SEP . 'framed=' . $framed . SEP . 'popup=' . $popup . '">' . _AT('file_manager_new') . '</a>';

		if ($popup == TRUE) {
			echo '<input type="hidden" name="popup" value="1" />';
		}
		echo '</form>';
		echo '</td></tr></table></fieldset>';

	} else {
		echo '</table>';
		echo '</fieldset>';
		$msg->printInfos('OVER_QUOTA');
	}
	echo '<br />';
}
// Directory and File listing 


echo '<form name="checkform" action="'.$_SERVER['PHP_SELF'].'?pathext='.urlencode($pathext).SEP.'popup='.$popup .SEP. 'framed='.$framed.'" method="post">';
echo '<input type="hidden" name="pathext" value ="'.$pathext.'" />';



?>
<table class="data static" summary="" border="0" rules="groups" style="width: 90%">
<thead>
<tr>
	<th scope="col"><input type="checkbox" name="checkall" onclick="Checkall(checkform);" id="selectall" title="<?php echo _AT('select_all'); ?>" /></th>
	<th>&nbsp;</th>
	<th scope="col"><?php echo _AT('name');   ?></th>
<!--	<th scope="col"><?php //echo _AT('date');   ?></th>
	<th scope="col"><?php //echo _AT('size');   ?></th>-->
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="5"><input type="submit" name="rename" value="<?php echo _AT('rename'); ?>" /> 
		<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" /> 
		<input type="submit" name="move"   value="<?php echo _AT('move'); ?>" /></td>
</tr>
<!--
<tr>
	<td colspan="4" align="right"><strong><?php //echo _AT('directory_total'); ?>:</strong></td>
	<td align="right">&nbsp;<strong><?php //echo get_human_size(dirsize($current_path.$pathext.$file.'/')); ?></strong>&nbsp;</td>
</tr>

<tr>
	<td colspan="4" align="right"><strong><?php // echo _AT('course_total'); ?>:</strong></td>
	<td align="right">&nbsp;<strong><?php // echo get_human_size($course_total); ?></strong>&nbsp;</td>
</tr>
<tr>
	<td colspan="4" align="right"><strong><?php //echo _AT('course_available'); ?>:</strong></td>
	<td align="right"><strong><?php /*
		if ($my_MaxCourseSize == AT_COURSESIZE_UNLIMITED) {
			echo _AT('unlimited');
		} else if ($my_MaxCourseSize == AT_COURSESIZE_DEFAULT) {
			echo get_human_size($MaxCourseSize-$course_total);
		} else {
			echo get_human_size($my_MaxCourseSize-$course_total);
		} */ ?></strong>&nbsp;</td>
</tr>-->
</tfoot>
<?php


if($pathext) : ?>
	<tr>
		<td colspan="5"><a href="<?php echo $_SERVER['PHP_SELF'].'?back=1'.SEP.'pathext='.$pathext.SEP. 'popup=' . $popup .SEP. 'framed=' . $framed .SEP.'cp='.$_GET['cp']; ?>"><img src="images/arrowicon.gif" border="0" height="11" width="10" alt="" /> <?php echo _AT('back'); ?></a></td>
	</tr>
<?php endif; ?>
<?php
$totalBytes = 0;

if ($dir == '')
	$dir=opendir($current_path);

// loop through folder to get files and directory listing
while (false !== ($file = readdir($dir)) ) {

	// if the name is not a directory 
	if( ($file == '.') || ($file == '..') ) {
		continue;
	}

	// get some info about the file
	$filedata = stat($current_path.$pathext.$file);
	$path_parts = pathinfo($file);
	$ext = strtolower($path_parts['extension']);

	$is_dir = false;

	// if it is a directory change the file name to a directory link 
	if(is_dir($current_path.$pathext.$file)) {
		$size = dirsize($current_path.$pathext.$file.'/');
		$totalBytes += $size;
		$filename = '<a href="'.$_SERVER['PHP_SELF'].'?pathext='.urlencode($pathext.$file.'/'). SEP . 'popup=' . $popup . SEP . 'framed='. $framed . SEP.'cp='.$_GET['cp'].'">'.$file.'</a>';
		$fileicon = '&nbsp;';
		$fileicon .= '<img src="../images/folder.gif" alt="'._AT('folder').':'.$file.'" height="18" width="20" class="img-size-fm1" />';
		$fileicon .= '&nbsp;';
		if(!$MakeDirOn) {
			$deletelink = '';
		}

		$is_dir = true;
	} else if ($ext == 'zip') {

		$totalBytes += $filedata[7];
		$filename = $file;
		$fileicon = '&nbsp;<img src="../images/icon-zip.gif" alt="'._AT('zip_archive').':'.$file.'" height="16" width="16" border="0" class="img-size-fm2" />&nbsp;';

	} else {
		$totalBytes += $filedata[7];
		$filename = $file;
		$fileicon = '&nbsp;<img src="../images/file_types/'.get_file_type_icon($filename).'.gif" height="16" width="16" alt="" title="" class="img-size-fm2" />&nbsp;';
	} 
	$file1 = strtolower($file);
	// create listing for dirctor or file
	if ($is_dir) {
		
		$dirs[$file1] .= '<tr><td  align="center" width="0%">';
		$dirs[$file1] .= '<input type="checkbox" id="'.$file.'" value="'.$file.'" name="check[]"/></td>';
		$dirs[$file1] .= '<td  align="center"><label for="'.$file.'" >'.$fileicon.'</label></td>';
		$dirs[$file1] .= '<td >&nbsp;';
		$dirs[$file1] .= $filename.'</td>';
/*
		$dirs[$file1] .= '<td  align="right">&nbsp;';
		$dirs[$file1] .= AT_date(_AT('filemanager_date_format'), $filedata[10], AT_DATE_UNIX_TIMESTAMP);
		$dirs[$file1] .= '&nbsp;</td>';

		$dirs[$file1] .= '<td  align="right">';
		$dirs[$file1] .= get_human_size($size).'</td>';
*/		
	} else {
		$files[$file1] .= '<tr> <td  align="center">';
		$files[$file1] .= '<input type="checkbox" id="'.$file.'" value="'.$file.'" name="check[]"/> </td>';
		$files[$file1] .= '<td  align="center"><label for="'.$file.'">'.$fileicon.'</label></td>';
		$files[$file1] .= '<td >&nbsp;';

		if ($framed) {
			$files[$file1] .= '<a href="'.$get_file.$pathext.urlencode($filename).'">'.$filename.'</a>';
		} else {
			$files[$file1] .= '<a href="tools/filemanager/preview.php?file='.$pathext.$filename.SEP.'pathext='.urlencode($pathext).SEP.'popup='.$popup.'">'.$filename.'</a>';
		}

		if ($ext == 'zip') {
			$files[$file1] .= ' <a href="tools/filemanager/zip.php?pathext=' . urlencode($pathext) . SEP . 'file=' . urlencode($file) . SEP . 'popup=' . $popup . SEP . 'framed=' . $framed .'">';
			$files[$file1] .= '<img src="../images/archive.gif" border="0" alt="'._AT('extract_archive').'" title="'._AT('extract_archive').'"height="16" width="11" class="img-size-fm3" />';
			$files[$file1] .= '</a>';
		}

		if (in_array($ext, $editable_file_types)) {
			$files[$file1] .= ' <a href="tools/filemanager/edit.php?pathext=' . urlencode($pathext) . SEP . 'popup=' . $popup . SEP . 'framed=' . $framed . SEP . 'file=' . $file . '">';
			$files[$file1] .= '<img src="../images/edit.gif" border="0" alt="'._AT('extract_archive').'" title="'._AT('edit').'" height="15" width="18" class="img-size-fm4" />';
			$files[$file1] .= '</a>';
		}

		$files[$file1] .= '&nbsp;</td>';

		$files[$file1] .= '<td  align="right" style="white-space:nowrap">';

		if ($popup == TRUE) {
			$files[$file1] .= '<input class="button" type="button" name="insert" value="' ._AT('insert') . '" onclick="javascript:insertFile(\'' . $file . '\', \'' . get_relative_path($_GET['cp'], $pathext) . '\', \'' . $ext . '\');" />&nbsp;';
		}
/*
		$files[$file1] .= AT_date(_AT('filemanager_date_format'), $filedata[10], AT_DATE_UNIX_TIMESTAMP);
		$files[$file1] .= '&nbsp;</td>';
		
		$files[$file1] .= '<td  align="right" style="white-space:nowrap">';
		$files[$file1] .= get_human_size($filedata[7]).'</td>';*/
	}
} // end while

// sort listing and output directories
if (is_array($dirs)) {
	ksort($dirs, SORT_STRING);
	foreach($dirs as $x => $y) {
		echo $y;
	}
}

//sort listing and output files
if (is_array($files)) {
	ksort($files, SORT_STRING);
	foreach($files as $x => $y) {
		echo $y;
	}
}


echo '</table></form>';



?>

<script type="text/javascript">
//<!--
function insertFile(fileName, pathTo, ext) { 

	// pathTo + fileName should be relative to current path (specified by the Content Package Path)

	if (ext == "gif" || ext == "jpg" || ext == "jpeg" || ext == "png") {
		var info = "<?php echo _AT('alternate_text'); ?>";
		var html = '<img src="' + pathTo+fileName + '" border="0" alt="' + info + '" />';

		if (window.opener.document.form.setvisual.value == 1) {
			if (window.parent.tinyMCE)
				window.parent.tinyMCE.execCommand('mceInsertContent', false, html);

			if (window.opener.tinyMCE)
				window.opener.tinyMCE.execCommand('mceInsertContent', false, html);
		} else {
			insertAtCursor(window.opener.document.form.body_text, html);
		}
	} else if (ext == "mpg" || ext == "avi" || ext == "wmv" || ext == "mov" || ext == "swf" || ext == "mp3" || ext == "wav" || ext == "ogg" || ext == "mid") {
		var html = '[media]'+ pathTo + fileName + '[/media]';
		if (window.opener.document.form.setvisual.value == 1) {
			if (window.parent.tinyMCE)
				window.parent.tinyMCE.execCommand('mceInsertContent', false, html);

			if (window.opener.tinyMCE)
				window.opener.tinyMCE.execCommand('mceInsertContent', false, html);
		} else {
			insertAtCursor(window.opener.document.form.body_text, html);
		}
	} else {
		var info = "<?php echo _AT('put_link'); ?>";
		var html = '<a href="' + pathTo+fileName + '">' + info + '</a>';

		if (window.opener.document.form.setvisual.value == 1) {
			if (window.parent.tinyMCE)
				window.parent.tinyMCE.execCommand('mceInsertContent', false, html);

			if (window.opener.tinyMCE)
				window.opener.tinyMCE.execCommand('mceInsertContent', false, html);
		} else {
			insertAtCursor(window.opener.document.form.body_text, html);
		}
	}
}
function insertAtCursor(myField, myValue) {
	//IE support
	if (window.opener.document.selection) {
		myField.focus();
		sel = window.opener.document.selection.createRange();
		sel.text = myValue;
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		+ myValue
		+ myField.value.substring(endPos, myField.value.length);
		myField.focus();
	} else {
		myField.value += myValue;
		myField.focus();
	}
}
//-->
</script>
		

<?php


closedir($dir);

?>
<script type="text/javascript">
//<!--
function Checkall(form){ 
  for (var i = 0; i < form.elements.length; i++){    
    eval("form.elements[" + i + "].checked = form.checkall.checked");  
  } 
}
function openWindow(page) {
	newWindow = window.open(page, "progWin", "width=400,height=200,toolbar=no,location=no");
	newWindow.focus();
}
//-->
</script>



	
		</div>
	</div>
	