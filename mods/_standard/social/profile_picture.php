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
// $Id: profile_picture.php 8694 2009-07-14 14:44:12Z hwong $
$_user_location	= 'public';
define('AT_INCLUDE_PATH', '../../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');
$member_id = $_SESSION['member_id'];
$_custom_css = $_base_path . AT_SOCIAL_BASENAME . 'module.css'; // use a custom stylesheet
require('html/profile_picture.html.php'); ?>