CREATE TABLE IF NOT EXISTS `albums` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(75) NOT NULL,
	`location` varchar(75) DEFAULT NULL,
	`description` varchar(250) DEFAULT NULL,
	`cover` varchar(150) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `albums` (`id`, `name`, `location`, `description`, `cover`) VALUES
(1, 'Chess', '', '', '1283372395-8e980b54bac316d9320f140e469a00eb.jpg'),
(2, 'Coffee', '', '', '1283372573-8b0d2cbe3d19751d4c3ad12fc8299941.jpg');

CREATE TABLE IF NOT EXISTS `photos` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`path` varchar(100) NOT NULL,
	`description` varchar(255) DEFAULT NULL,
	`albumId` bigint(20) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `i_albumId` (`albumId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `photos` (`id`, `path`, `description`, `albumId`) VALUES
(1, '1283372395-8e980b54bac316d9320f140e469a00eb.jpg', NULL, 1),
(2, '1283372506-bec01db74e0d456386d9ea6cf5b7131d.jpg', NULL, 1),
(3, '1283372547-e0fa4836fda6df94b974a95acde21072.jpg', NULL, 1),
(4, '1283372573-8b0d2cbe3d19751d4c3ad12fc8299941.jpg', NULL, 2);
