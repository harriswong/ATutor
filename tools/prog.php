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

$_user_location = 'prog';
$_ignore_page = true; /* used for the close the page option */
define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

if ($_GET['tile']) {
	$lang_variable = 'tile_progress';
} else {
	$lang_variable = 'upload_progress';
}

if (!$_GET['frame']) { 
	$_SESSION['done'] = 0;
	session_write_close();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?php echo $myLang->getCode(); ?>">
<head>
	<title><?php echo _AT($lang_variable); ?></title>
	<?php if ($_GET['frame']) { ?>
		<META HTTP-EQUIV="refresh" content="3;URL=prog.php?frame=1"> 
	<?php } ?>
	<link rel="stylesheet" href="<?php echo $_base_path; ?>themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/styles.css" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; <?php echo $myLang->getCharacterSet(); ?>" />
</head>
<body <?php
	if ($_SESSION['done']) {
		echo 'onLoad="parent.window.close();"';
	}
?>>
<?php if (!$_GET['frame']) {  ?>
&nbsp;<a href="javascript:window.close();"><?php echo _AT('close'); ?></a>
<h3><?php echo _AT($lang_variable); ?></h3>
<p><small><?php echo _AT('window_auto_close'); ?></small></p>

<br /><br />
<table border="0" align="center">
<tr>
	<td><img src="<?php echo $_base_href; ?>images/transfer.gif" height="20" width="90" alt="<?php echo _AT($lang_variable); ?>"></td>
	<td valign="middle"><iframe src="prog.php?frame=1" width="100" height="25" frameborder="0" scrolling=no marginwidth="0" marginheight="1">
</iframe>
<?php } else { 
	$tmp_dir = ini_get('upload_tmp_dir') . DIRECTORY_SEPARATOR;
	if (!$_GET['t']) {
		$newest_file_name = '';
		$newest_file_time = 0;
		// get the name of the temp file.
		if ($dir = @opendir($tmp_dir)) {
			while (($file = readdir($dir)) !== false) {
				if ((strlen($file) == 9) && (substr($file, 0, 3) == 'php')) {
					$filedata = stat($tmp_dir . $file);
					if ($filedata['mtime'] > $newest_file_time) {
						$newest_file_time = $filedata['mtime'];
						$newest_file_name = $file;
						$size = $filedata['size'] / 1024;
					}
				}
			}
			closedir($dir);
		}
	} else {
		$filedata = stat($tmp_dir . $_GET['t']);
		$size = $filedata['size'] / AT_KBYTE_SIZE;
	}

	echo '<small>';
	if ($size == '') {
		echo '<em>'._AT('unknown').'</em> KB';
	} else {
		echo number_format($size, 2).' KB';
	}
	echo '</small>';
} ?></td>
</tr>
</table>
</body>
</html>