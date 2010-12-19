# sql file for basiclti module

INSERT INTO `language_text` VALUES ('en', '_module','basiclti','External Tools',NOW(),'');

# More Language entries at the end

CREATE TABLE `basiclti_tools` (
	`id` mediumint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`toolid` varchar(32) NOT NULL,
	`course_id` mediumint(10) NOT NULL DEFAULT '0',
	`title` varchar(255) NOT NULL,
	`description` varchar(1024),
	`timecreated` TIMESTAMP,
	`timemodified` TIMESTAMP,
	`toolurl` varchar(1023) NOT NULL,
	`resourcekey` varchar(1023) NOT NULL,
	`password` varchar(1023) NOT NULL,
	`preferheight` mediumint(4) NOT NULL DEFAULT '0',
	`allowpreferheight` mediumint(1) NOT NULL DEFAULT '0',
	`sendname` mediumint(1) NOT NULL DEFAULT '0',
	`sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
	`acceptgrades` mediumint(1) NOT NULL DEFAULT '0',
	`allowroster` mediumint(1) NOT NULL DEFAULT '0',
	`allowsetting` mediumint(1) NOT NULL DEFAULT '0',
	`allowcustomparameters` mediumint(1) NOT NULL DEFAULT '0',
	`customparameters` varchar(2048) NOT NULL,
	`organizationid` varchar(64) NOT NULL,
	`organizationurl` varchar(255) NOT NULL,
	`organizationdescr` varchar(255) NOT NULL,
	`launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
	`debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (id)
);

CREATE TABLE `basiclti` (
	`id` mediumint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`course_id` mediumint(10) NOT NULL DEFAULT '0',
	`toolid` varchar(32) NOT NULL,
	`name` varchar(255) NOT NULL,
	`intro` varchar(1024),
	`timecreated` TIMESTAMP,
	`timemodified` TIMESTAMP,
	`sendname` mediumint(1) NOT NULL DEFAULT '0',
	`sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
	`acceptgrades` mediumint(1) NOT NULL DEFAULT '0',
	`allowroster` mediumint(1) NOT NULL DEFAULT '0',
	`allowsetting` mediumint(1) NOT NULL DEFAULT '0',
	`customparameters` varchar(255) NOT NULL,
	`launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
	`debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
	`placementsecret` varchar(1023) NOT NULL,
	`timeplacementsecret` mediumint(10) NOT NULL DEFAULT '0',
	`oldplacementsecret` varchar(1023) NOT NULL,
	UNIQUE KEY (id, course_id)
);

CREATE TABLE `basiclti_content` (
	`id` mediumint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`content_id` mediumint(10) NOT NULL DEFAULT '0',
	`course_id` mediumint(10) NOT NULL DEFAULT '0',
	`toolid` varchar(32) NOT NULL,
	`preferheight` mediumint(4) NOT NULL DEFAULT '0',
	`sendname` mediumint(1) NOT NULL DEFAULT '0',
	`sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
	`gradebook_test_id` mediumint(10) NOT NULL DEFAULT '0',
	`allowroster` mediumint(1) NOT NULL DEFAULT '0',
	`allowsetting` mediumint(1) NOT NULL DEFAULT '0',
	`customparameters` varchar(2048) NOT NULL,
	`launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
	`debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
	`placementsecret` varchar(1023) NOT NULL,
	`timeplacementsecret` mediumint(10) NOT NULL DEFAULT '0',
	`oldplacementsecret` varchar(1023) NOT NULL,
	`setting` varchar(8192) NOT NULL,
	UNIQUE KEY (id, course_id, content_id)
);

# More Language Entries

INSERT INTO `language_text` VALUES ('en', '_module','basiclti_text','Support for integrating External Tools that support IMS Basic Learning Tools Interoperability..',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_create','New External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_content_title','External Tool Settings',NOW(),'');
# When course_id is zero, it is a system-wide tool made by the admin
INSERT INTO `language_text` VALUES ('en', '_module','basiclti_tool','External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','basiclti_content_text','External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','basiclti_comment','You can choose and configure an External Tool associated with this Content Item.',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_choose_tool','Select External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','blti_missing_tool','External Tool configuration has is missing toolid:',NOW(),'');


INSERT INTO `language_text` VALUES ('en', '_module','bl_acceptgrades','Accept Grades From External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_acceptgrades_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_acceptgrades_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_acceptgrades_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_acceptgrades_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowcustomparameters','Allow Additional Custom Parameters',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowcustomparameters_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowcustomparameters_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowcustomparameters_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowcustomparameters_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowpreferheight','Allow Frame Height to be Changed',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowpreferheight_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowpreferheight_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowpreferheight_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowpreferheight_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowroster','Allow External Tool To Retrieve Roster',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowroster_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowroster_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowroster_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowroster_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowsetting','Allow External Tool to use the Setting Service',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowsetting_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowsetting_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowsetting_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_allowsetting_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_customparameters','Custom Parameters',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_debuglaunch','Launch Tool in Debug Mode',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_debuglaunch_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_debuglaunch_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_debuglaunch_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_debuglaunch_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_description','Description',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_launchinpopup','Launch Tool in Pop Up Window',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_launchinpopup_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_launchinpopup_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_launchinpopup_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_launchinpopup_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_organizationdescr','Organization Description',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_organizationid','Organization Identifier (typically DNS)',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_organizationurl','Organization URL',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_password','Tool Secret',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_preferheight','Frame Height',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_resourcekey','Tool Key (oauth_consumer_key)',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendemailaddr','Send User Mail Addresses to External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendemailaddr_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendemailaddr_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendemailaddr_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendemailaddr_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendname','Send User Names to External Tool',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendname_content','Specify in each Content Item',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendname_instructor','Delegate to Instructor',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendname_off','Never',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_sendname_on','Always',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_title','Title',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_toolid','ToolId (must be unique across system)',NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','bl_toolurl','Tool Launch URL',NOW(),'');

