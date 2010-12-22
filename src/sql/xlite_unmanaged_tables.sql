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
