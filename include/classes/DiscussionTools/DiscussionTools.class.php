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
// $Id$

/**
 * A class for DiscussionTools
 * based on:
 *  http://www.imsglobal.org/profile/cc/ccv1p0/derived_schema/domainProfile_5/imsdt_v1p0_localised.xsd
 */
class DiscussionTools {
	//global variables
	var $title = '';	//The Forum title
	var $text = '';		//The description of the discussion tools.

	//constructor
	function DiscussionTools($title, $text){
		$this->title = $title;
		$this->text = $text;
	}

	function getTitle(){
		return $this->title;
	}

	function getText(){
		return $this->text;
	}
}
?>