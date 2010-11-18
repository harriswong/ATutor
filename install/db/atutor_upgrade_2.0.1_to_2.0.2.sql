
#--------------- BasicLTI Tables Start -------------------
CREATE TABLE  `basiclti_tools` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `toolid` varchar(32) NOT NULL,
  `course_id` mediumint(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timemodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `toolurl` varchar(1023) NOT NULL,
  `resourcekey` varchar(1023) NOT NULL,
  `password` varchar(1023) NOT NULL,
  `preferheight` mediumint(4) NOT NULL DEFAULT '0',
  `sendname` mediumint(1) NOT NULL DEFAULT '0',
  `sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
  `acceptgrades` mediumint(1) NOT NULL DEFAULT '0',
  `customparameters` varchar(255) NOT NULL,
  `organizationid` varchar(64) NOT NULL,
  `organizationurl` varchar(255) NOT NULL,
  `organizationdescr` varchar(255) NOT NULL,
  `launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
  `debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE  `basiclti_content` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `content_id` mediumint(10) NOT NULL DEFAULT '0',
  `course_id` mediumint(10) NOT NULL DEFAULT '0',
  `toolid` varchar(32) NOT NULL,
  `sendname` mediumint(1) NOT NULL DEFAULT '0',
  `sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
  `acceptgrades` mediumint(1) NOT NULL DEFAULT '0',
  `customparameters` varchar(255) NOT NULL,
  `launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
  `debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
  `gradesecret` varchar(1023) NOT NULL,
  `timegradesecret` mediumint(10) NOT NULL DEFAULT '0',
  `oldgradesecret` varchar(1023) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`course_id`,`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE  `basiclti` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `course_id` mediumint(10) NOT NULL DEFAULT '0',
  `toolid` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `intro` varchar(1024) DEFAULT NULL,
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timemodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sendname` mediumint(1) NOT NULL DEFAULT '0',
  `sendemailaddr` mediumint(1) NOT NULL DEFAULT '0',
  `acceptgrades` mediumint(1) NOT NULL DEFAULT '0',
  `customparameters` varchar(255) NOT NULL,
  `launchinpopup` mediumint(1) NOT NULL DEFAULT '0',
  `debuglaunch` mediumint(1) NOT NULL DEFAULT '0',
  `gradesecret` varchar(1023) NOT NULL,
  `timegradesecret` mediumint(10) NOT NULL DEFAULT '0',
  `oldgradesecret` varchar(1023) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  ;

# will probably want to move this in _core before 2.0.2
INSERT INTO `modules` VALUES ('basiclti', 2, 67108864, 16384, 35, 0);

#--------------- BasicLTI Tables End -------------------