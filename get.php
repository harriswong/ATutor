<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', 'include/');
$_ignore_page = true; /* without this we wouldn't know where we're supposed to go */
require(AT_INCLUDE_PATH . '/vitals.inc.php');

$mime['ez'] = 'application/andrew-inset';
$mime['hqx'] = 'application/mac-binhex40';
$mime['cpt'] = 'application/mac-compactpro';
$mime['doc'] = 'application/msword';
$mime['bin'] = 'application/octet-stream';
$mime['dms'] = 'application/octet-stream';
$mime['lha'] = 'application/octet-stream';
$mime['lzh'] = 'application/octet-stream';
$mime['exe'] = 'application/octet-stream';
$mime['class'] = 'application/octet-stream';
$mime['oda'] = 'application/oda';
$mime['pdf'] = 'application/pdf';
$mime['ai'] = 'application/postscript';
$mime['eps'] = 'application/postscript';
$mime['ps'] = 'application/postscript';
$mime['rtf'] = 'application/rtf';
$mime['smi'] = 'application/smil';
$mime['smil'] = 'application/smil';
$mime['mif'] = 'application/vnd.mif';
$mime['ppt'] = 'application/vnd.ms-powerpoint';
$mime['slc'] = 'application/vnd.wap.slc';
$mime['sic'] = 'application/vnd.wap.sic';
$mime['wmlc'] = 'application/vnd.wap.wmlc';
$mime['wmlsc'] = 'application/vnd.wap.wmlscriptc';
$mime['bcpio'] = 'application/x-bcpio';
$mime['bz2'] = 'application/x-bzip2';
$mime['vcd'] = 'application/x-cdlink';
$mime['pgn'] = 'application/x-chess-pgn';
$mime['cpio'] = 'application/x-cpio';
$mime['csh'] = 'application/x-csh';
$mime['dcr'] = 'application/x-director';
$mime['dir'] = 'application/x-director';
$mime['dxr'] = 'application/x-director';
$mime['dvi'] = 'application/x-dvi';
$mime['spl'] = 'application/x-futuresplash';
$mime['gtar'] = 'application/x-gtar';
$mime['gz'] = 'application/x-gzip';
$mime['tgz'] = 'application/x-gzip';
$mime['hdf'] = 'application/x-hdf';
$mime['js'] = 'application/x-javascript';
$mime['kwd'] = 'application/x-kword';
$mime['kwt'] = 'application/x-kword';
$mime['ksp'] = 'application/x-kspread';
$mime['kpr'] = 'application/x-kpresenter';
$mime['kpt'] = 'application/x-kpresenter';
$mime['chrt'] = 'application/x-kchart';
$mime['kil'] = 'application/x-killustrator';
$mime['skp'] = 'application/x-koan';
$mime['skd'] = 'application/x-koan';
$mime['skt'] = 'application/x-koan';
$mime['skm'] = 'application/x-koan';
$mime['latex'] = 'application/x-latex';
$mime['nc'] = 'application/x-netcdf';
$mime['cdf'] = 'application/x-netcdf';
$mime['rpm'] = 'application/x-rpm';
$mime['sh'] = 'application/x-sh';
$mime['shar'] = 'application/x-shar';
$mime['swf'] = 'application/x-shockwave-flash';
$mime['sit'] = 'application/x-stuffit';
$mime['sv4cpio'] = 'application/x-sv4cpio';
$mime['sv4crc'] = 'application/x-sv4crc';
$mime['tar'] = 'application/x-tar';
$mime['tcl'] = 'application/x-tcl';
$mime['tex'] = 'application/x-tex';
$mime['texinfo'] = 'application/x-texinfo';
$mime['texi'] = 'application/x-texinfo';
$mime['t'] = 'application/x-troff';
$mime['tr'] = 'application/x-troff';
$mime['roff'] = 'application/x-troff';
$mime['man'] = 'application/x-troff-man';
$mime['me'] = 'application/x-troff-me';
$mime['ms'] = 'application/x-troff-ms';
$mime['ustar'] = 'application/x-ustar';
$mime['src'] = 'application/x-wais-source';
$mime['zip'] = 'application/zip';
$mime['au'] = 'audio/basic';
$mime['snd'] = 'audio/basic';
$mime['mid'] = 'audio/midi';
$mime['midi'] = 'audio/midi';
$mime['kar'] = 'audio/midi';
$mime['mpga'] = 'audio/mpeg';
$mime['mp2'] = 'audio/mpeg';
$mime['mp3'] = 'audio/mpeg';
$mime['aif'] = 'audio/x-aiff';
$mime['aiff'] = 'audio/x-aiff';
$mime['aifc'] = 'audio/x-aiff';
$mime['ram'] = 'audio/x-pn-realaudio';
$mime['rm'] = 'audio/x-pn-realaudio';
$mime['ra'] = 'audio/x-realaudio';
$mime['wav'] = 'audio/x-wav';
$mime['pdb'] = 'chemical/x-pdb';
$mime['xyz'] = 'chemical/x-pdb';
$mime['gif'] = 'image/gif';
$mime['ief'] = 'image/ief';
$mime['jpeg'] = 'image/jpeg';
$mime['jpg'] = 'image/jpeg';
$mime['jpe'] = 'image/jpeg';
$mime['png'] = 'image/png';
$mime['tiff'] = 'image/tiff';
$mime['tif'] = 'image/tiff';
$mime['wbmp'] = 'image/vnd.wap.wbmp';
$mime['ras'] = 'image/x-cmu-raster';
$mime['pnm'] = 'image/x-portable-anymap';
$mime['pbm'] = 'image/x-portable-bitmap';
$mime['pgm'] = 'image/x-portable-graymap';
$mime['ppm'] = 'image/x-portable-pixmap';
$mime['rgb'] = 'image/x-rgb';
$mime['xbm'] = 'image/x-xbitmap';
$mime['xpm'] = 'image/x-xpixmap';
$mime['xwd'] = 'image/x-xwindowdump';
$mime['igs'] = 'model/iges';
$mime['iges'] = 'model/iges';
$mime['msh'] = 'model/mesh';
$mime['mesh'] = 'model/mesh';
$mime['silo'] = 'model/mesh';
$mime['wrl'] = 'model/vrml';
$mime['vrml'] = 'model/vrml';
$mime['css'] = 'text/css';
$mime['asc'] = 'text/plain';
$mime['txt'] = 'text/plain';
$mime['rtx'] = 'text/richtext';
$mime['rtf'] = 'text/rtf';
$mime['sgml'] = 'text/sgml';
$mime['sgm'] = 'text/sgml';
$mime['tsv'] = 'text/tab-separated-values';
$mime['sl'] = 'text/vnd.wap.sl';
$mime['si'] = 'text/vnd.wap.si';
$mime['wml'] = 'text/vnd.wap.wml';
$mime['wmls'] = 'text/vnd.wap.wmlscript';
$mime['etx'] = 'text/x-setext';
$mime['xml'] = 'text/xml';
$mime['mpeg'] = 'video/mpeg';
$mime['mpg'] = 'video/mpeg';
$mime['mpe'] = 'video/mpeg';
$mime['qt'] = 'video/quicktime';
$mime['mov'] = 'video/quicktime';
$mime['avi'] = 'video/x-msvideo';
$mime['movie'] = 'video/x-sgi-movie';
$mime['ice'] = 'x-conference/x-cooltalk';
$mime['html'] = 'text/html';
$mime['htm'] = 'text/html';

/* manually added: */
$mime['xls'] = 'application/vnd.ms-excel';

//get path to file
$args = substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']));
$file = AT_CONTENT_DIR . $_SESSION['course_id'] . $args;

//send header mime type
$ext = pathinfo($file);
$ext = $ext['extension'];
if ($ext == '') {
	$ext = 'application/octet-stream';
}

//check that this file is within the content directory & exists

// NOTE!! for some reason realpath() is not returning FALSE when the file doesn't exist! NOTE!!
$real = realpath($file);

if (file_exists($real) && (substr($real, 0, strlen(AT_CONTENT_DIR)) == AT_CONTENT_DIR)) {
 	header('Content-Type: '.$mime[$ext]);
	echo @file_get_contents($real);
	exit;
} else {
	header('HTTP/1.1 404 Not Found');
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
	<title>404 Not Found</title>
</head>
<body>
<h1>Not Found</h1>
The requested URL <strong><?php echo $file; ?></strong> was not found on this server.
</body>
</html>
<?php
	exit;
}

?>