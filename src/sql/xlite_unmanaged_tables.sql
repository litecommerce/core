-- Unmanaged Doctrine table structure
-- SVN: $Id$

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

DROP TABLE IF EXISTS xlite_extra_field_values;
CREATE TABLE xlite_extra_field_values (
  product_id INT NOT NULL DEFAULT '0',
  field_id INT NOT NULL DEFAULT '0',
  value text(65536) NOT NULL DEFAULT '',
  KEY field_key (product_id, field_id),
  KEY value (value (65536))
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_extra_fields;
CREATE TABLE xlite_extra_fields (
  field_id INT NOT NULL AUTO_INCREMENT,
  product_id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL DEFAULT '',
  DEFAULT_value VARCHAR(255) NOT NULL DEFAULT '',
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  order_by INT NOT NULL DEFAULT '0',
  parent_field_id INT NOT NULL DEFAULT '0',
  categories text NOT NULL,
  PRIMARY KEY  (field_id),
  KEY product_id (product_id),
  KEY order_by (order_by),
  KEY name (name),
  KEY DEFAULT_value (DEFAULT_value),
  KEY enabled (enabled),
  KEY parent_field_id (parent_field_id),
  KEY categories (categories (65536))
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_htaccess;
CREATE TABLE xlite_htaccess (
  id INT NOT NULL AUTO_INCREMENT,
  filename VARCHAR(64) NOT NULL DEFAULT '',
  content text NOT NULL DEFAULT '',
  hash VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  KEY hash (hash),
  UNIQUE (filename)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_log;
CREATE TABLE xlite_log (
  unixtime INT NOT NULL DEFAULT '0',
  ident VARCHAR(16) NOT NULL DEFAULT '',
  priority INT DEFAULT NULL,
  message VARCHAR(200) DEFAULT NULL,
  KEY unixtime (unixtime,ident),
  KEY priority (priority),
  KEY message (message)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_search_stat;
CREATE TABLE xlite_search_stat (
  query VARCHAR(64) NOT NULL DEFAULT '',
  product_count INT NOT NULL DEFAULT '0',
  count INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (query),
  KEY product_count (product_count),
  KEY count (count)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_upgrades;
CREATE TABLE xlite_upgrades (
  from_ver VARCHAR(10) NOT NULL DEFAULT '',
  to_ver VARCHAR(10) NOT NULL DEFAULT '',
  date INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (from_ver,to_ver),
  KEY date (date)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_waitingips;
CREATE TABLE xlite_waitingips (
  id INT NOT NULL AUTO_INCREMENT,
  ip VARCHAR(32) NOT NULL DEFAULT '',
  unique_key VARCHAR(50) NOT NULL DEFAULT '',
  first_date INT NOT NULL DEFAULT '0',
  last_date INT NOT NULL DEFAULT '0',
  count INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  UNIQUE (ip)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
