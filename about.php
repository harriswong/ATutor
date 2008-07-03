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
// $Id$

$_user_location	= 'public';

define('AT_INCLUDE_PATH', 'include/');
require(AT_INCLUDE_PATH.'/vitals.inc.php');
require(AT_INCLUDE_PATH.'header.inc.php');

?>
<p><?php echo _AT('atutor_is');  ?></p>

<?php echo _AT('atutor_links');  ?>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>