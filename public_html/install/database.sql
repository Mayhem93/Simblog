CREATE TABLE IF NOT EXISTS `{prefix}post` (
	`id` MEDIUMINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` TINYTEXT NOT NULL,
	`pinned` TINYINT(1) NOT NULL DEFAULT '0',
	`category` TEXT NOT NULL,
	`tags` TEXT NOT NULL,
	`content` TEXT NOT NULL,
	`utime` INT(10) NOT NULL,
	`utime_mod` INT(10) NULL,
	`comments_count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT
AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{prefix}comment` (
	`id` MEDIUMINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_id` MEDIUMINT(6) UNSIGNED NOT NULL,
	`author` TINYTEXT NOT NULL,
	`email` TINYTEXT NOT NULL,
	`ip` TINYTEXT NOT NULL,
	`content` TEXT NOT NULL,
	`utime` TINYTEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT
AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{prefix}category` (
	`name` TINYTEXT NOT NULL
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}page` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` TINYTEXT NOT NULL,
	`content` MEDIUMTEXT NOT NULL,
	`description` TINYTEXT NULL DEFAULT NULL,
	`parent` INT(10) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{prefix}skin_content` (
	`zone_id` INT(2) UNSIGNED NOT NULL,
	`content` TEXT NULL,
	PRIMARY KEY (`zone_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;