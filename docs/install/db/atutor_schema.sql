#####################################################
# Database setup SQL for a new install of ATutor
#####################################################
# $Id$

# --------------------------------------------------------
# Table structure for table `admin_log`
# since 1.5

CREATE TABLE `admin_log` (
  `login` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `operation` varchar(20) NOT NULL default '',
  `table` varchar(30) NOT NULL default '',
  `num_affected` tinyint(3) NOT NULL default '0',
  `details` varchar(255) NOT NULL default '',
  KEY `login` (`login`)
) TYPE=MyISAM;


CREATE TABLE `admins` (
   `login` VARCHAR( 30 ) NOT NULL ,
   `password` VARCHAR( 30 ) NOT NULL ,
   `real_name` VARCHAR( 30 ) NOT NULL ,
   `email` VARCHAR( 50 ) NOT NULL ,
   `privileges` MEDIUMINT UNSIGNED NOT NULL ,
   `last_login` DATETIME NOT NULL ,
   PRIMARY KEY ( `login` )
);

# --------------------------------------------------------
# Table structure for table `backups`
# since 1.4.3

CREATE TABLE `backups` (
  `backup_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` varchar(100) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default '0',
  `system_file_name` varchar(50) NOT NULL default '',
  `file_name` varchar(150) NOT NULL default '',
  `contents` TEXT NOT NULL default '',
  PRIMARY KEY  (`backup_id`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `content`

CREATE TABLE `content` (
  `content_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `content_parent_id` mediumint(8) unsigned NOT NULL default '0',
  `ordering` tinyint(4) NOT NULL default '0',
  `last_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `revision` tinyint(3) unsigned NOT NULL default '0',
  `formatting` tinyint(4) NOT NULL default '0',
  `release_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `keywords` varchar(100) NOT NULL default '',
  `content_path` varchar(100) NOT NULL default '',
  `title` varchar(150) NOT NULL default '',
  `text` text NOT NULL,
  `inherit_release_date` TINYINT UNSIGNED DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`content_id`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM ;


# --------------------------------------------------------
# Table structure for table `course_cats`

CREATE TABLE `course_cats` (
  `cat_id` mediumint(8) unsigned NOT NULL auto_increment,
  `cat_name` varchar(100) NOT NULL default '',
  `cat_parent` mediumint(8) unsigned NOT NULL default '0',
  `theme` VARCHAR(30) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `course_enrollment`

CREATE TABLE `course_enrollment` (
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `approved` enum('y','n','a') NOT NULL default 'n',
  `privileges` smallint(5) unsigned NOT NULL default '0',
  `role` varchar(35) NOT NULL default '',
  `last_cid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`member_id`,`course_id`)
) TYPE=MyISAM;



# --------------------------------------------------------
# Table structure for table `course_stats`

CREATE TABLE `course_stats` (
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `login_date` date NOT NULL default '0000-00-00',
  `guests` mediumint(8) unsigned NOT NULL default '0',
  `members` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`course_id`,`login_date`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `courses`

CREATE TABLE `courses` (
  `course_id` mediumint(8) unsigned NOT NULL auto_increment,
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `cat_id` mediumint(8) unsigned NOT NULL default '0',
  `content_packaging` enum('none','top','all') NOT NULL default 'top',
  `access` enum('public','protected','private') NOT NULL default 'public',
  `created_date` date NOT NULL default '0000-00-00',
  `title` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `notify` tinyint(4) NOT NULL default '0',
  `max_quota` varchar(30) NOT NULL default '',
  `max_file_size` varchar(30) NOT NULL default '',
  `hide` tinyint(4) NOT NULL default '0',
  `preferences` text NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  `copyright` text NOT NULL,
  `banner_text` text NOT NULL,
  `banner_styles` text NOT NULL,
  `primary_language` varchar(5) NOT NULL default '',
  `rss` tinyint NOT NULL default 0,
  `icon` varchar(20) NOT NULL default '',
  `home_links` VARCHAR( 255 ) NOT NULL ,
  `main_links` VARCHAR( 255 ) NOT NULL ,
  `side_menu` VARCHAR( 255 ) NOT NULL ,
  PRIMARY KEY  (`course_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `forums`

CREATE TABLE `forums` (
  `forum_id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(60) NOT NULL default '',
  `description` text NOT NULL,
  `num_topics` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,
  `num_posts` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,
  `last_post` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
  PRIMARY KEY  (`forum_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `forums_accessed`

CREATE TABLE `forums_accessed` (
  `post_id` mediumint(8) unsigned NOT NULL default '0',
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `last_accessed` timestamp(14) NOT NULL,
  `subscribe` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`post_id`,`member_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `forums_courses`

CREATE TABLE `forums_courses` (
  `forum_id` MEDIUMINT UNSIGNED NOT NULL default '0',
  `course_id` MEDIUMINT UNSIGNED NOT NULL default '0',
  PRIMARY KEY (`forum_id`,`course_id`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM;



# --------------------------------------------------------
# Table structure for table `forums_subscriptions`
#

CREATE TABLE `forums_subscriptions` (
  forum_id mediumint(8) unsigned NOT NULL default '0',
  member_id mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`forum_id`,`member_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `forums_threads`

CREATE TABLE `forums_threads` (
  `post_id` mediumint(8) unsigned NOT NULL auto_increment,
  `parent_id` mediumint(8) unsigned NOT NULL default '0',
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `forum_id` mediumint(8) unsigned NOT NULL default '0',
  `login` varchar(20) NOT NULL default '',
  `last_comment` datetime NOT NULL default '0000-00-00 00:00:00',
  `num_comments` mediumint(8) unsigned NOT NULL default '0',
  `subject` varchar(100) NOT NULL default '',
  `body` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked` tinyint(4) NOT NULL default '0',
  `sticky` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`post_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `glossary`

CREATE TABLE `glossary` (
  `word_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `word` varchar(60) NOT NULL default '',
  `definition` text NOT NULL,
  `related_word_id` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`word_id`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `groups`

CREATE TABLE `groups` (
`group_id` MEDIUMINT UNSIGNED NOT NULL auto_increment,
`course_id` MEDIUMINT UNSIGNED NOT NULL default '0',
`title` varchar(20) NOT NULL default '',
PRIMARY KEY ( `group_id` ),
KEY `course_id` (`course_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `groups_members`

CREATE TABLE `groups_members` (
`group_id` MEDIUMINT UNSIGNED NOT NULL default '0',
`member_id` MEDIUMINT UNSIGNED NOT NULL default '0',
 PRIMARY KEY  (`group_id`,`member_id`)
);

# --------------------------------------------------------
# Table structure for table `instructor_approvals`

CREATE TABLE `instructor_approvals` (
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `request_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `notes` text NOT NULL,
  PRIMARY KEY  (`member_id`)
) TYPE=MyISAM;


CREATE TABLE `languages` (
  `language_code` varchar(5) NOT NULL default '',
  `char_set` varchar(20) NOT NULL default '',
  `direction` varchar(4) NOT NULL default '',
  `reg_exp` varchar(31) NOT NULL default '',
  `native_name` varchar(20) NOT NULL default '',
  `english_name` varchar(20) NOT NULL default '',
  `status` TINYINT UNSIGNED DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`language_code`,`char_set`)
) TYPE=MyISAM;

#
# Dumping data for table `languages`
#

INSERT INTO `languages` VALUES ('en', 'iso-8859-1', 'ltr', 'en([-_][[:alpha:]]{2})?|english', 'English', 'English', 3);
    

# --------------------------------------------------------
# Table structure for table `language_pages`

CREATE TABLE `language_pages` (
  `term` varchar(30) NOT NULL default '',
  `page` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`term`,`page`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `master_list`

CREATE TABLE `master_list` (
  `public_field` CHAR( 30 ) NOT NULL ,
  `hash_field` CHAR( 40 ) NOT NULL ,
  `member_id` MEDIUMINT UNSIGNED NOT NULL ,
  PRIMARY KEY ( `public_field` )
);

# --------------------------------------------------------
# Table structure for table `members`

CREATE TABLE `members` (
  `member_id` mediumint(8) unsigned NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `website` varchar(200) NOT NULL default '',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `dob` date NOT NULL default '0000-00-00',
  `gender` enum('m','f') NOT NULL default 'm',
  `address` varchar(255) NOT NULL default '',
  `postal` varchar(15) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `province` varchar(50) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `phone` varchar(15) NOT NULL default '',
  `status` tinyint(4) NOT NULL default '0',
  `preferences` text NOT NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `language` varchar(5) NOT NULL default '',
  `inbox_notify` tinyint(3) unsigned NOT NULL default '0',
  `alternate_email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`member_id`),
  UNIQUE KEY `login` (`login`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `member_track`

CREATE TABLE `member_track` (
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `content_id` mediumint(8) unsigned NOT NULL default '0',
  `counter` mediumint(8) unsigned NOT NULL default '0',
  `duration` mediumint(8) unsigned NOT NULL default '0',
  `last_accessed` datetime default NULL,
  KEY `member_id` (`member_id`),
  KEY `content_id` (`content_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `messages`

CREATE TABLE `messages` (
  `message_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `from_member_id` mediumint(8) unsigned NOT NULL default '0',
  `to_member_id` mediumint(8) unsigned NOT NULL default '0',
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `new` tinyint(4) NOT NULL default '0',
  `replied` tinyint(4) NOT NULL default '0',
  `subject` varchar(150) NOT NULL default '',
  `body` text NOT NULL,
  PRIMARY KEY  (`message_id`),
  KEY `to_member_id` (`to_member_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `news`

CREATE TABLE `news` (
  `news_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `formatting` tinyint(4) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `body` text NOT NULL,
  PRIMARY KEY  (`news_id`)
) TYPE=MyISAM;

# --------------------------------------------------------

# Table structure for table `polls`
CREATE TABLE `polls` (
  `poll_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `course_id` MEDIUMINT UNSIGNED NOT NULL ,
  `question` VARCHAR( 100 ) NOT NULL ,
  `created_date` DATETIME NOT NULL ,
  `total` SMALLINT UNSIGNED NOT NULL ,
  `choice1` VARCHAR( 100 ) NOT NULL ,
  `count1` SMALLINT UNSIGNED NOT NULL ,
  `choice2` VARCHAR( 100 ) NOT NULL ,
  `count2` SMALLINT UNSIGNED NOT NULL ,
  `choice3` VARCHAR( 100 ) NOT NULL ,
  `count3` SMALLINT UNSIGNED NOT NULL ,
  `choice4` VARCHAR( 100 ) NOT NULL ,
  `count4` SMALLINT UNSIGNED NOT NULL ,
  `choice5` VARCHAR( 100 ) NOT NULL ,
  `count5` SMALLINT UNSIGNED NOT NULL ,
  `choice6` VARCHAR( 100 ) NOT NULL ,
  `count6` SMALLINT UNSIGNED NOT NULL ,
  `choice7` VARCHAR( 100 ) NOT NULL ,
  `count7` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY ( `poll_id` ) ,
  INDEX ( `course_id` )
) TYPE=MyISAM;

# --------------------------------------------------------

# Table structure for table `polls_members`

CREATE TABLE `polls_members` (
  `poll_id` MEDIUMINT UNSIGNED NOT NULL ,
  `member_id` MEDIUMINT UNSIGNED NOT NULL ,
  PRIMARY KEY ( `poll_id` , `member_id` )
) TYPE=MyISAM;

# --------------------------------------------------------

# Table structure for table `related_content`
CREATE TABLE `related_content` (
  `content_id` mediumint(8) unsigned NOT NULL default '0',
  `related_content_id` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`content_id`,`related_content_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `resource_categories`

CREATE TABLE `resource_categories` (
  `CatID` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `CatName` varchar(100) NOT NULL default '',
  `CatParent` mediumint(8) unsigned default NULL,
  PRIMARY KEY  (`CatID`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `resource_links`

CREATE TABLE `resource_links` (
  `LinkID` mediumint(8) unsigned NOT NULL auto_increment,
  `CatID` mediumint(8) unsigned NOT NULL default '0',
  `Url` varchar(255) NOT NULL default '',
  `LinkName` varchar(64) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  `Approved` tinyint(8) default '0',
  `SubmitName` varchar(64) NOT NULL default '',
  `SubmitEmail` varchar(64) NOT NULL default '',
  `SubmitDate` date NOT NULL default '0000-00-00',
  `hits` int(11) default '0',
  PRIMARY KEY  (`LinkID`)
) TYPE=MyISAM ;


# --------------------------------------------------------
# Table structure for table `tests`

CREATE TABLE `tests` (
  `test_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `format` tinyint(4) NOT NULL default '0',
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `randomize_order` tinyint(4) NOT NULL default '0',
  `num_questions` tinyint(3) unsigned NOT NULL default '0',
  `instructions` text NOT NULL,
  `content_id` mediumint(8) NOT NULL,
  `result_release` tinyint(4) unsigned NOT NULL,
  `random` tinyint(4) unsigned NOT NULL,
  `difficulty` tinyint(4) unsigned NOT NULL,
  `num_takes` tinyint(4) unsigned NOT NULL,
  `anonymous` tinyint(4) NOT NULL default '0',
  `out_of` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`test_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `tests_answers`

CREATE TABLE `tests_answers` (
  `result_id` mediumint(8) unsigned NOT NULL default '0',
  `question_id` mediumint(8) unsigned NOT NULL default '0',
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `answer` text NOT NULL,
  `score` varchar(5) NOT NULL default '',
  `notes` text NOT NULL,
  PRIMARY KEY  (`result_id`,`question_id`,`member_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `tests_groups`

CREATE TABLE `tests_groups` (
  `test_id` MEDIUMINT UNSIGNED NOT NULL default '0',
  `group_id` MEDIUMINT UNSIGNED NOT NULL default '0',
  PRIMARY KEY (`test_id`,`group_id`),
  KEY `test_id` (`test_id`)
) TYPE=MyISAM;


# --------------------------------------------------------
# Table structure for table `tests_questions`

CREATE TABLE `tests_questions` (
  `question_id` mediumint(8) unsigned NOT NULL auto_increment,
  `category_id` mediumint(8) unsigned NOT NULL default '0',
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `type` tinyint(3) unsigned NOT NULL default '0',
  `feedback` text NOT NULL,
  `question` text NOT NULL,
  `choice_0` varchar(255) NOT NULL default '',
  `choice_1` varchar(255) NOT NULL default '',
  `choice_2` varchar(255) NOT NULL default '',
  `choice_3` varchar(255) NOT NULL default '',
  `choice_4` varchar(255) NOT NULL default '',
  `choice_5` varchar(255) NOT NULL default '',
  `choice_6` varchar(255) NOT NULL default '',
  `choice_7` varchar(255) NOT NULL default '',
  `choice_8` varchar(255) NOT NULL default '',
  `choice_9` varchar(255) NOT NULL default '',
  `answer_0` tinyint(4) NOT NULL default '0',
  `answer_1` tinyint(4) NOT NULL default '0',
  `answer_2` tinyint(4) NOT NULL default '0',
  `answer_3` tinyint(4) NOT NULL default '0',
  `answer_4` tinyint(4) NOT NULL default '0',
  `answer_5` tinyint(4) NOT NULL default '0',
  `answer_6` tinyint(4) NOT NULL default '0',
  `answer_7` tinyint(4) NOT NULL default '0',
  `answer_8` tinyint(4) NOT NULL default '0',
  `answer_9` tinyint(4) NOT NULL default '0',
  `properties` tinyint(4) NOT NULL default '0',
  `content_id` mediumint(8) NOT NULL,  
  PRIMARY KEY  (`question_id`),
  KEY `category_id` (category_id)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `tests_questions_assoc`

CREATE TABLE `tests_questions_assoc` (
  `test_id` mediumint(8) unsigned NOT NULL default '0',
  `question_id` mediumint(8) unsigned NOT NULL default '0',
  `weight` varchar(4) NOT NULL default '',
  `ordering` tinyint(3) unsigned NOT NULL default '0',
  `required` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`test_id`,`question_id`),
  KEY `test_id` (`test_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `tests_questions_categories`

CREATE TABLE `tests_questions_categories` (
  `category_id` mediumint(8) unsigned NOT NULL auto_increment,
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `title` char(50) NOT NULL default '',
  PRIMARY KEY  (`category_id`),
  KEY `course_id` (`course_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `tests_results`

CREATE TABLE `tests_results` (
  `result_id` mediumint(8) unsigned NOT NULL auto_increment,
  `test_id` mediumint(8) unsigned NOT NULL default '0',
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `date_taken` datetime NOT NULL default '0000-00-00 00:00:00',
  `final_score` char(5) NOT NULL default '',
  PRIMARY KEY  (`result_id`),
  KEY `test_id` (`test_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
# Table structure for table `themes`
# since 1.4.3

CREATE TABLE `themes` (
  `title` varchar(20) NOT NULL default '',
  `version` varchar(10) NOT NULL default '',
  `dir_name` varchar(20) NOT NULL default '',
  `last_updated` date NOT NULL default '0000-00-00',
  `extra_info` varchar(255) NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`title`)
);

# insert the default theme
INSERT INTO `themes` VALUES ('Atutor', '1.5', 'default', NOW(), 'This is the default ATutor theme and cannot be deleted as other themes inherit from it. Please do not alter this theme directly as it would complicate upgrading. Instead, create a new theme derived from this one.', 2);
INSERT INTO `themes` VALUES ('Atutor Classic', '1.5', 'default_classic', NOW(), 'This is the ATutor Classic theme which makes use of the custom Header and logo images. To customize those images you must edit the <code>theme.cfg.php</code> in this theme\'s directory.', 1);


# --------------------------------------------------------
# Table structure for table `users_online`

CREATE TABLE `users_online` (
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `login` varchar(20) NOT NULL default '',
  `expiry` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`member_id`)
) TYPE=HEAP MAX_ROWS=500;


# ----------------
# SCORM RTE tables

CREATE TABLE `packages` (
      `package_id` mediumint(8) unsigned NOT NULL auto_increment,
      `source`     varchar(255) NOT NULL,
      `time`       datetime NOT NULL,
      `course_id`  mediumint(8) unsigned NOT NULL,
      `ptype`      varchar(63) NOT NULL,
      PRIMARY KEY (package_id)
) TYPE=MyISAM;

CREATE TABLE `scorm_1_2_org` (
      `org_id`     mediumint(8) unsigned NOT NULL auto_increment,
      `package_id` mediumint(8) unsigned NOT NULL,

      `title`         varchar(255) NOT NULL,
      `credit`        varchar(15)  not null default 'no-credit',
      `lesson_mode`   varchar(15)  not null default 'browse',

      PRIMARY KEY (org_id),
      KEY         (package_id)
) TYPE=MyISAM;

CREATE TABLE `scorm_1_2_item` (
      `item_id`    mediumint(8) unsigned NOT NULL auto_increment,
      `org_id`     mediumint(8) unsigned NOT NULL,
      `idx`             varchar(15)  NOT NULL,
      `title`           varchar(255),
      `href`            varchar(255),
      `scormtype`       varchar(15),
      `prerequisites`   varchar(255),
      `maxtimeallowed`  varchar(255),
      `timelimitaction` varchar(255),
      `datafromlms`     varchar(255),
      `masteryscore`    mediumint(8),

      PRIMARY KEY (item_id),
      KEY (org_id)
)TYPE=MyISAM;


CREATE TABLE `cmi` (
      `cmi_id`        mediumint(8) unsigned NOT NULL auto_increment,
      `item_id`       mediumint(8) unsigned NOT NULL,
      `member_id`     mediumint unsigned NOT NULL ,
      `lvalue`        varchar(63) NOT NULL,
      `rvalue`        blob,
       PRIMARY KEY (cmi_id),
      UNIQUE KEY (item_id, member_id,lvalue)
)TYPE=MyISAM;
