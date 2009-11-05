# Add folder node into `content` table
ALTER TABLE `content` add `content_type` tinyint NOT NULL DEFAULT 0;

# --------------------------------------------------------
# Table structure for table `content_forums_assoc`

CREATE TABLE `content_forums_assoc` (
`content_id` INTEGER UNSIGNED NOT NULL,
`forum_id` INTEGER UNSIGNED NOT NULL,
PRIMARY KEY ( `content_id` , `forum_id` )
)
TYPE = MyISAM;

