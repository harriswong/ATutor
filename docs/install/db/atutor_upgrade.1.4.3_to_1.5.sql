###############################################################
# Database upgrade SQL from ATutor 1.4.3 to ATutor 1.5
###############################################################

ALTER TABLE `courses` ADD `icon` VARCHAR( 20 ) NOT NULL , ADD `home_links` VARCHAR( 255 ) NOT NULL , ADD `main_links` VARCHAR( 255 ) NOT NULL;

UPDATE `courses` SET home_links='forum/list.php|glossary/index.php|discussions/achat/index.php|resources/tile/index.php|links/index.php|tools/my_tests.php|sitemap.php|export.php|my_stats.php|polls/index.php';
UPDATE `courses` SET main_links='forum/list.php|glossary/index.php';


#ALTER TABLE `content` ADD `counter` MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL;

CREATE TABLE `member_track` (
  `member_id` mediumint(8) unsigned NOT NULL default '0',
  `course_id` mediumint(8) unsigned NOT NULL default '0',
  `content_id` mediumint(8) unsigned NOT NULL default '0',
  `counter` mediumint(8) unsigned NOT NULL default '0',
  `last_accessed` datetime default NULL,
  PRIMARY KEY  (`member_id`,`course_id`,`content_id`)
);
