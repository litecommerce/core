DROP TABLE IF EXISTS xlite_featured_products;
CREATE TABLE xlite_featured_products (
  id int(11) unsigned NOT NULL AUTO_INCREMENT, 
  product_id int(11) unsigned NOT NULL default '0',
  category_id int(11) unsigned NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY (id),
  UNIQUE KEY pair (category_id,product_id),
  CONSTRAINT FOREIGN KEY (`product_id`) REFERENCES `xlite_products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES `xlite_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO `xlite_config` VALUES (118,'featured_products_look','FeaturedProducts','select',10,'grid');
INSERT INTO `xlite_config_translations` VALUES (92,'en',118,'Featured products look&feel','');

