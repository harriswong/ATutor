###############################################################
# Database upgrade SQL from ATutor 1.4.3 to ATutor 1.5
###############################################################

ALTER TABLE `courses` ADD `icon` VARCHAR( 20 ) NOT NULL , ADD `home_links` VARCHAR( 255 ) NOT NULL , ADD `main_links` VARCHAR( 255 ) NOT NULL;
