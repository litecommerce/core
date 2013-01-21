DROP TABLE IF EXISTS `%%PREFIX%%menu_translations`;
CREATE TABLE `%%PREFIX%%menu_translations` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` char(2) NOT NULL,
  PRIMARY KEY (`label_id`),
  KEY `IDX_CC54E72BBF396750` (`id`),
  KEY `ci` (`code`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `%%PREFIX%%page_translations`;
CREATE TABLE `%%PREFIX%%page_translations` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `teaser` longtext NOT NULL,
  `body` longtext NOT NULL,
  `metaKeywords` longtext NOT NULL,
  `code` char(2) NOT NULL,
  PRIMARY KEY (`label_id`),
  KEY `IDX_D4ABAC0BF396750` (`id`),
  KEY `ci` (`code`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

