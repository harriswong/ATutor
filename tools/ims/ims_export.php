<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'classes/Message/Message.class.php');

// supress notices:
error_reporting(E_ALL ^ E_NOTICE);

global $savant;
$msg =& new Message($savant);

/* content id of an optional chapter */
$cid = intval($_REQUEST['cid']);
$c   = intval($_REQUEST['c']);

if (isset($_REQUEST['to_tile']) && !isset($_POST['cancel'])) {
	/* for TILE */

	/* redirect to TILE import servlet */

	require(AT_INCLUDE_PATH.'vitals.inc.php');
	if (!authenticate(AT_PRIV_ADMIN, AT_PRIV_RETURN)) {
		/* user can't be authenticated */
		header('HTTP/1.1 404 Not Found');
		echo 'Document not found.';
		exit;
	}

	$m = md5(DB_PASSWORD . 'x' . ADMIN_PASSWORD . 'x' . $_SERVER['SERVER_ADDR'] . 'x' . $cid . 'x' . $_SESSION['course_id'] . 'x' . date('Ymd'));

	header('Location: '.AT_TILE_IMPORT. '?cp='.urlencode($_base_href. 'tools/ims/ims_export.php?cid='.$cid.'&c='.$_SESSION['course_id'].'&m='.$m));
	exit;
} else if (isset($_GET['m'])) {
	/* for TILE */

	/* request (hopefully) coming from a TILE server, send the content package */

	$_user_location = 'public';
	require(AT_INCLUDE_PATH.'vitals.inc.php');
	$m = md5(DB_PASSWORD . 'x' . ADMIN_PASSWORD . 'x' . $_SERVER['SERVER_ADDR'] . 'x' . $cid . 'x' . $c . 'x' . date('Ymd'));
	if (($m != $_GET['m']) || !$c) {
		header('HTTP/1.1 404 Not Found');
		echo 'Document not found.';
		exit;
	}
	
	$course_id = $c;

} else {
	require(AT_INCLUDE_PATH.'vitals.inc.php');
	$course_id = $_SESSION['course_id'];
}

$instructor_id   = $system_courses[$course_id]['member_id'];
$course_desc     = $system_courses[$course_id]['description'];
$course_title    = $system_courses[$course_id]['title'];
$course_language = $system_courses[$course_id]['primary_language'];

$courseLanguage =& $languageManager->getLanguage($course_language);

$course_language_charset = $courseLanguage->getCharacterSet();
$course_language_code = $courseLanguage->getCode();

require(AT_INCLUDE_PATH.'classes/zipfile.class.php');				/* for zipfile */
require(AT_INCLUDE_PATH.'classes/vcard.php');						/* for vcard */
require(AT_INCLUDE_PATH.'classes/XML/XML_HTMLSax/XML_HTMLSax.php');	/* for XML_HTMLSax */
require(AT_INCLUDE_PATH.'ims/ims_template.inc.php');				/* for ims templates + print_organizations() */

if (isset($_POST['cancel'])) {
	$msg->addFeedback('EXPORT_CANCELLED');
	header('Location: ../index.php');
	exit;
}


$zipfile = new zipfile(); 
$zipfile->create_dir('resources/');

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
	$sql = "SELECT *, UNIX_TIMESTAMP(last_modified) AS u_ts FROM ".TABLE_PREFIX."content WHERE course_id=$course_id ORDER BY content_parent_id, ordering";
} else {
	$sql = "SELECT *, UNIX_TIMESTAMP(last_modified) AS u_ts FROM ".TABLE_PREFIX."content WHERE course_id=$course_id AND release_date<=NOW() ORDER BY content_parent_id, ordering";
}
$result = mysql_query($sql, $db);
while ($row = mysql_fetch_assoc($result)) {
	$content[$row['content_parent_id']][] = $row;
	if ($cid == $row['content_id']) {
		$top_content = $row;
		$top_content_parent_id = $row['content_parent_id'];
	}
}

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
	$ims_course_title = $course_title . ' - ' . $content[$top_content_parent_id][0]['title'];
} else {
	$ims_course_title = $course_title;
}


/* generate the imsmanifest.xml header attributes */
$imsmanifest_xml = str_replace(array('{COURSE_TITLE}', '{COURSE_DESCRIPTION}', '{COURSE_PRIMARY_LANGUAGE_CHARSET}', '{COURSE_PRIMARY_LANGUAGE_CODE}'), 
							  array($ims_course_title, $course_desc, $course_language_charset, $course_language_code),
							  $ims_template_xml['header']);

/* get the first content page to default the body frame to */
$first = $content[$top_content_parent_id][0];

/* generate the resources and save the HTML files */
$old_pref = $_SESSION['prefs'][PREF_CONTENT_ICONS];
$_SESSION['prefs'][PREF_CONTENT_ICONS] = 2;

unset($learning_concept_tags);
ob_start();
print_organizations($top_content_parent_id, $content, 0, '', array(), $toc_html);
$organizations_str = ob_get_contents();
ob_end_clean();

if ($used_glossary_terms) {
	$used_glossary_terms = array_unique($used_glossary_terms);
	sort($used_glossary_terms);
	reset($used_glossary_terms);

	$terms_xml = '';
	foreach ($used_glossary_terms as $term) {
		$terms_xml .= str_replace(	array('{TERM}', '{DEFINITION}'),
									array($term, $glossary[$term]),
									$glossary_term_xml);
		$terms_html .= str_replace(	array('{ENCODED_TERM}', '{TERM}', '{DEFINITION}'),
									array(urlencode($term), $term, $glossary[$term]),
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

/* restore old pref */
$_SESSION['prefs'][PREF_CONTENT_ICONS] = $old_pref;

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
$imsmanifest_xml .= str_replace(	array('{ORGANIZATIONS}',	'{RESOURCES}', '{COURSE_TITLE}'),
									array($organizations_str,	$resources, $ims_course_title),
									$ims_template_xml['final']);

/* generate the vcard for the instructor/author */
$sql = "SELECT first_name, last_name, email, website, login, phone FROM ".TABLE_PREFIX."members WHERE member_id=$instructor_id";
$result = mysql_query($sql, $db);
$vcard = new vCard();
if ($row = mysql_fetch_assoc($result)) {
	$vcard->setName($row['last_name'], $row['first_name'], $row['login']);
	$vcard->setEmail($row['email']);
	$vcard->setNote('Originated from an ATutor at '.$_base_href.'. See ATutor.ca for additional information.');
	$vcard->setURL($row['website']);

	$imsmanifest_xml = str_replace('{VCARD}', $vcard->getVCard(), $imsmanifest_xml);
} else {
	$imsmanifest_xml = str_replace('{VCARD}', '', $imsmanifest_xml);
}


/* save the imsmanifest.xml file */

$zipfile->add_file($frame,			 'index.html');
$zipfile->add_file($toc_html,		 'toc.html');
$zipfile->add_file($imsmanifest_xml, 'imsmanifest.xml');
$zipfile->add_file($html_mainheader, 'header.html');
if ($glossary_xml) {
	$zipfile->add_file($glossary_xml,  'glossary.xml');
	$zipfile->add_file($glossary_html, 'glossary.html');
}
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/adlcp_rootv1p2.xsd'), 'adlcp_rootv1p2.xsd');
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/ims_xml.xsd'), 'ims_xml.xsd');
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/imscp_rootv1p1p2.xsd'), 'imscp_rootv1p1p2.xsd');
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/imsmd_rootv1p2p1.xsd'), 'imsmd_rootv1p2p1.xsd');
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/ims.css'), 'ims.css');
$zipfile->add_file(file_get_contents(AT_INCLUDE_PATH.'ims/footer.html'), 'footer.html');
$zipfile->add_file(file_get_contents('../../images/logo.gif'), 'logo.gif');

$zipfile->close(); // this is optional, since send_file() closes it anyway

$zipfile->send_file(str_replace(array(' ', ':'), '_', $ims_course_title) . '_ims');

exit;
?>