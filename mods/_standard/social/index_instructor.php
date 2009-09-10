<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2009										*/
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: index_instructor.php 8692 2009-07-13 19:01:32Z hwong $

define('AT_INCLUDE_PATH', '../../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_SOCIAL);
require (AT_INCLUDE_PATH.'header.inc.php');
?>

Hello Instructor!! :)

<?php require (AT_INCLUDE_PATH.'footer.inc.php'); ?>