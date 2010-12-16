DROP TABLE IF EXISTS xlite_option_groups;
CREATE TABLE xlite_option_groups (
  `group_id` int(11) NOT NULL auto_increment PRIMARY KEY,
  `product_id` int(11) NOT NULL default 0,
  `orderby` int(11) NOT NULL default 0,
  `type` char(1) NOT NULL default 'g',
  `view_type` char(1) NOT NULL default 's',
  `cols` tinyint unsigned NOT NULL default 0,
  `rows` tinyint unsigned NOT NULL default 0,
  `enabled` tinyint(1) unsigned NOT NULL default 0,
  KEY product_id (product_id, orderby)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_option_group_translations;
CREATE TABLE xlite_option_group_translations (
  label_id int(11) NOT NULL auto_increment,
  code char(2) NOT NULL,
  id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL default '',
  fullname text NOT NULL,
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_options;
CREATE TABLE xlite_options (
  option_id int(11) NOT NULL auto_increment PRIMARY KEY,
  group_id int(11) NOT NULL default 0,
  orderby int(11) NOT NULL default 0,
  enabled tinyint(1) unsigned NOT NULL default 0,
  KEY grp (group_id, enabled, orderby)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_option_surcharges;
CREATE TABLE xlite_option_surcharges (
  `surcharge_id` int(11) NOT NULL auto_increment PRIMARY KEY,
  `option_id` int(11) NOT NULL default 0,
  `type` varchar(32) NOT NULL default 'price',
  `modifier` decimal(16,4) NOT NULL default '0.0000',
  `modifier_type` char(1) NOT NULL default '%',
  UNIQUE KEY ot (`option_id`, `type`)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_option_translations;
CREATE TABLE xlite_option_translations (
  label_id int(11) NOT NULL auto_increment,
  code char(2) NOT NULL,
  id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_option_exceptions;
CREATE TABLE xlite_option_exceptions (
  id int(11) NOT NULL auto_increment PRIMARY KEY,
  option_id int(11) NOT NULL default 0,
  exception_id int(11) NOT NULL default 0,
  UNIQUE KEY (option_id, exception_id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_order_item_options;
CREATE TABLE xlite_order_item_options (
  `id` int(11) NOT NULL auto_increment PRIMARY KEY,
  `item_id` int(11) NOT NULL default 0,
  `group_id` int(11) NOT NULL default 0,
  `option_id` int(11) default 0,
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `orderby` int(11) NOT NULL default 0,
  KEY item (item_id, orderby)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


-- ALTER TABLE xlite_products ADD expansion_limit int NOT NULL default 0;

-- INSERT INTO `xlite_config` VALUES (40,'clone_product_options','CDev\\ProductOptions','checkbox',30,'Y');
-- INSERT INTO `xlite_config_translations` VALUES (34,'en',40,'Clone product options when product is cloned','');

