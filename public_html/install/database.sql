CREATE TABLE IF NOT EXISTS `post` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` TINYTEXT NOT NULL,
	`content` TEXT NOT NULL,
	`date_posted` TINYTEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT
AUTO_INCREMENT=1;