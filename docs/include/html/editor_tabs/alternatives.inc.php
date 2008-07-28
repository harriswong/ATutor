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

?>

<script type="text/javascript">
function openIt(x){ 
    if (x=='1'){
		document.getElementById('2').style.display = "none";
     	document.getElementById('1').style.display = "block";
    }
    else {
		document.getElementById('1').style.display = "none";
  		document.getElementById('2').style.display = "block";
  	}
}
</script>


<div class="row_alternatives" id="radio_alt">
	<input type="radio" name="alternatives" value="0" id="single_resources" onClick="openIt(1)"/>
	<label for="single_resources">Define alternatives to non-textual resources.</label>
	<br/>
	<input type="radio" name="alternatives" value="0" id="whole_page" onClick="openIt(2)"/>
	<label for="whole_page">Define alternatives to textual resources.</label>
</div>

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

<div class="row_alternatives" id="1" style="display: none;">
	<div class="column_primary">
		<?php 
		define('AT_INCLUDE_PATH', '../../include/');

		/* content id of an optional chapter */
		//	$cid = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 0;
		$c   = isset($_REQUEST['c'])   ? intval($_REQUEST['c'])   : 0;

		$course_language = $system_courses[$course_id]['primary_language'];
		$courseLanguage =& $languageManager->getLanguage($course_language);

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
								//	'link'		=> 'href',
									'script'	=> 'src',
								//	'form'		=> 'action',
									'input'		=> 'src',
									'iframe'	=> 'src',
									'embed'		=> 'src'
								//	'param'		=> 'value'
									);

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
			
		/* generate the resources and save the HTML files */
			
		ob_start();
							 
		global $html_content_template, $default_html_style, $parser, $my_files;
		global $course_id, $course_language_charset, $course_language_code;
		static $paths;
	//	global $ims_template_xml, $used_glossary_terms, $glossary, $zipped_files;

		$space  = '    ';
		$prefix = '                    ';
	    $depth = 0;
		$parent_id = $top_content_parent_id;
		$_menu = $content;
		$path= '';
		$children=array();
		//$string=$toc_html;
	 
		$top_level = $_menu[$parent_id];
		if (!is_array($paths)) {
			$paths = array();
		}

		if ( is_array($top_level) ) {
	
			$counter = 1;
			$num_items = count($top_level);
			foreach ($top_level as $garbage => $content) {
				$link = '';
			
				if ($content['content_path']) {
					$content['content_path'] .= '/';
				}
				/* @See: include/lib/format_content.inc.php */
				$content['text'] = str_replace('CONTENT_DIR/', '', $content['text']);
		
				/* calculate how deep this page is: */
				$path = '../';
				if ($content['content_path']) {
					$depth = substr_count($content['content_path'], '/');
					$path .= str_repeat('../', $depth);
				}
			
				//$content['text'] = format_content($content['text'], $content['formatting'], $glossary, $path);
				//$content['title'] = htmlspecialchars($content['title']);

				/* add the resource dependancies */
				$my_files = array();
				$content_files = "\n";
				$parser->parse($content['text']);

				/* handle @import */
				$import_files = get_import_files($content['text']);
			
				if (count($import_files) > 0) $my_files = array_merge($my_files, $import_files);
			
				$i=0;
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

					$resources[$i] = $file;
					$i++;
				}
			}
		}

		$organizations_str = ob_get_contents();
		ob_end_clean();

		$n=count($resources);
		
		if ($n==0)
			echo '<p>No non-textual resources!</p>';
		else {
			$sql	= "SELECT * FROM ".TABLE_PREFIX."primary_resources WHERE content_id=".$cid." order by primary_resource_id";
	      	$result	= mysql_query($sql, $db);
	      	if (mysql_num_rows($result) > 0) {
	      		$j=0;
				while ($row = mysql_fetch_assoc($result)) {
					$resources_db[$j]=$row['resource'];
					$j++;
				}
			}
			$m=count($resources_db);
			for ($i=0; $i < $n; $i++){
				for($j=0; $j < $m; $j++){
					if (trim($resources[$i])==trim($resources_db[$j])){
						$present[$i]=true;
					}
				}
				if ($present[$i]==false) {
					$sql_ins= "INSERT INTO ".TABLE_PREFIX."primary_resources VALUES (NULL, $cid, '$resources[$i]', NULL)";
					$r 		= mysql_query($sql_ins, $db);
				}
			}
			$sql	= "SELECT * FROM ".TABLE_PREFIX."primary_resources WHERE content_id=".$cid." order by primary_resource_id";
	      	$result	= mysql_query($sql, $db);
	      	while ($row = mysql_fetch_assoc($result)) {
	      		$present=false;	
	      		for ($i=0; $i < $n; $i++){
	      			if (trim($resources[$i])==trim($row['resource'])) {
	      				$present=true;	
	      				?>
	      				<div class="resource_box">
	      					<p>
	      						<input type="radio" name="resources" value="0" id="<?php echo $row['resource']?>"/>
								<label for="<?php echo $row['resource']?>"><a href="<?php echo $row['resource']?>" target="_blank"><?php echo $row['resource']?></a></label>
							</p>
							<fieldset>
								<legend>Resource type</legend>
						<?php
						$sql_types		= "SELECT * FROM ".TABLE_PREFIX."resource_types";
						$types			= mysql_query($sql_types, $db);
			
						$sql_set_types	= "SELECT type_id FROM ".TABLE_PREFIX."primary_resources_types where primary_resource_id=".$row[primary_resource_id];
						$set_types		= mysql_query($sql_set_types, $db);
						
						$primary_types = false;
						$j=0;
						if (mysql_num_rows($set_types) > 0){
							while ($set_type = mysql_fetch_assoc($set_types)) {
								$primary_types[$j] = $set_type[type_id];
								$j++;
							}
						}
						else echo '<p class="unsaved">Define resource type!</p>';
						
						
						while ($type = mysql_fetch_assoc($types)) {
							echo '<input type="checkbox" name="'.$type['type'].'" value="'.$type['type'].'" id="'.$type['type'].'_'.$row[primary_resource_id].'"';
							$m = count($primary_types);
							for ($j=0; $j < $m; $j++){
								if (trim($primary_types[$j]) == trim($type[type_id])){
									echo 'checked="checked"';
									continue;
								}
							}
							echo '/>';
							echo '<label for="'.$type['type'].'_'.$row[primary_resource_id].'">'.$type['type'].'</label><br/>';		
						}	
						echo '</fieldset>';

						$languages = $languageManager->getAvailableLanguages();
						echo '<label for="lang_'.$row[primary_resource_id].'">Resource language</label><br />';
						echo '<select name="lang_'.$row[primary_resource_id].'" id="lang_'.$row[primary_resource_id].'">';
						foreach ($languages as $codes)
						{
							$language = current($codes);
							$lang_code = $language->getCode();
							$lang_native_name = $language->getNativeName();
							$lang_english_name = $language->getEnglishName()
							?>
								<option value="<?php echo $lang_code ?>"
								<?php if($lang_code == $row[language_code]) echo 'selected'?>><?php echo $lang_english_name . ' - '. $lang_native_name ?></option>
							<?php
						}
						?>
						</select>
						<?php
						$sql_alt	= "SELECT * FROM ".TABLE_PREFIX."secondary_resources WHERE primary_resource_id=".$row[primary_resource_id]." order by secondary_resource_id";
	      				$result_alt	= mysql_query($sql_alt, $db);
	      				if (mysql_num_rows($result_alt) > 0) {
	      					?>
								<h2 class="alternatives_to">Alternatives to <?php echo $row['resource'];?></h2>
								<?php
							while ($alternative = mysql_fetch_assoc($result_alt)){
								?>
								
	      						<div class="alternative_box">
	      						<p><a href="<?php echo $alternative['secondary_resource']?>" target="_blank"><?php echo $alternative['secondary_resource']?></a></p>
								<fieldset>
									<legend>Resource type</legend>
									<?php
									$sql_types		= "SELECT * FROM ".TABLE_PREFIX."resource_types";
									$types			= mysql_query($sql_types, $db);
			
									$sql_set_types	= "SELECT type_id FROM ".TABLE_PREFIX."secondary_resources_types where secondary_resource_id=".$alternative[secondary_resource_id];
									$set_types		= mysql_query($sql_set_types, $db);
						
									$secondary_types = false;
									$j=0;
									if (mysql_num_rows($set_types) > 0){
										while ($set_type = mysql_fetch_assoc($set_types)) {
											$secondary_types[$j] = $set_type[type_id];
										$j++;
										}
									}
									else echo'<p class="unsaved">Define resource type!</p>';
									
									while ($type = mysql_fetch_assoc($types)) {
										echo '<input type="checkbox" name="'.$type['type'].'" value="'.$type['type'].'" id="'.$type['type'].'_'.$alternative[secondary_resource_id].'"';
										$m = count($secondary_types);
										for ($j=0; $j < $m; $j++){
											if (trim($secondary_types[$j]) == trim($type[type_id])){
												echo 'checked="checked"';
												continue;
											}
										}
										echo '/>';
										echo '<label for="'.$type['type'].'_'.$row[primary_resource_id].'">'.$type['type'].'</label><br/>';		
									}	
									echo '</fieldset>';

									$languages = $languageManager->getAvailableLanguages();
									echo '<label for="lang_'.$alternative[secondary_resource_id].'">Resource language</label><br />';
									echo '<select name="lang_'.$alternative[secondary_resource_id].'" id="lang_'.$alternative[secondary_resource_id].'">';
									foreach ($languages as $codes)
									{
										$language = current($codes);
										$lang_code = $language->getCode();
										$lang_native_name = $language->getNativeName();
										$lang_english_name = $language->getEnglishName()
									?>
										<option value="<?php echo $lang_code ?>"
										<?php if($lang_code == $alternative[language_code]) echo 'selected'?>><?php echo $lang_english_name . ' - '. $lang_native_name ?></option>
										<?php
									}
									?>
									</select>
									
								</div>
								<?php
								}
							}
						
						?>
						
					</div>
		<?php			
					}
				}
				if ($present==false){
					$sql_del = "DELETE FROM ".TABLE_PREFIX."primary_resources WHERE content_id=".$cid." and resource='".$row['resource']."'";
					$result_del = mysql_query($sql_del, $db);
				}
			
			}
		}		
		//FINO A QUI TUTTO BENE! 
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
	echo '<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'].'?pathext='.urlencode($pathext).SEP. 'popup='.$popup.SEP. 'tab='.$current_tab.'">';
	
	echo $current_tab;
	
	echo 'UFFA';
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
		echo '<form onsubmit="openWindow(\''.AT_BASE_HREF.'tools/prog.php\');" name="form1" method="post" action="../tools/filemanager/upload.php?popup='.$popup.'" enctype="multipart/form-data">';
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
	<div class="row_alternatives" id="2" style="display: none;">
		PROVA PAGINA INTERA
	</div>
	