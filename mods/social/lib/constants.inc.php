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

//Constants Declaration
define(AT_SHINDIG_URL,						'http://localhost/shindig_old/php');
//define(AT_SHINDIG_URL,						'http://social.atutor.ca/shindig/php');

//Privacy Control Constants, field indices
//Profile
define(AT_SOCIAL_PROFILE_BASIC,				0);	//Basic profile info
define(AT_SOCIAL_PROFILE_PROFILE,			1);	//All Profile info
define(AT_SOCIAL_PROFILE_STATUS_UPDATE,		2);
define(AT_SOCIAL_PROFILE_MEDIA,				3);
define(AT_SOCIAL_PROFILE_CONNECTION,		4);
define(AT_SOCIAL_PROFILE_EDUCATION,			5);
define(AT_SOCIAL_PROFILE_POSITION,			6);	//Job
//Search 		
define(AT_SOCIAL_SEARCH_VISIBILITY,			0);	//Who can find the user via search
//Search Results set? What to return via the search
define(AT_SOCIAL_SEARCH_PROFILE,			1);	//Search through profile?
define(AT_SOCIAL_SEARCH_CONNECTION,			2);	//Search through connection?
define(AT_SOCIAL_SEARCH_EDUCATION,			3);	//Search through education?
define(AT_SOCIAL_SEARCH_POSITION,			4);	//Search through job position?
//Actvity
define(AT_SOCIAL_ACTIVITIES_PROFILE,		0);	//Profile updates
define(AT_SOCIAL_ACTIVITIES_CONNECTION,		1);	//Adding a connection
define(AT_SOCIAL_ACTIVITIES_APPLICATION,	2);	//Adding an application
//Application 
//TODO: Application can record activities via ATutorAppDataService, restrict it.

//Any additional flags should be added to 
//  lib/classes/PrivacyControl/PrivacyController.class.php::getPermissionLevels()
define(AT_SOCIAL_EVERYONE_VISIBILITY,				0);	//Everyone that is not a friend of mine
define(AT_SOCIAL_FRIENDS_VISIBILITY,				1);	//Friends
define(AT_SOCIAL_FRIENDS_OF_FRIENDS_VISIBILITY,		2);	//Friends of Friends
define(AT_SOCIAL_NETWORK_VISIBILITY,				3);	//Network
define(AT_SOCIAL_OWNER_VISIBILITY,					4);	//Myself
define(AT_SOCIAL_GROUPS_VISIBILITY,					5);	//Groups

//Display control
define('SOCIAL_FRIEND_ACTIVITIES_MAX',				10); //Activity class constants
define('SOCIAL_FRIEND_HOMEPAGE_MAX',				15); //# of friends to display on the homepage contact's box
define('SOCIAL_GROUP_HOMEPAGE_MAX',					3); //# of friends to display on the homepage contact's box
define('SOCIAL_GROUP_MAX',							5); //# of friends to display on the homepage contact's box
define('SOCIAL_APPLICATION_UPDATE_SCHEDULE',		10); //in days
define('SOCIAL_NUMBER_OF_PEOPLE_YOU_MAY_KNOW',		3);	 //the number of "people you may know" to be displayed on the home tab
define('SOCIAL_FRIEND_SEARCH_MAX',					50); //search result maximum
?>