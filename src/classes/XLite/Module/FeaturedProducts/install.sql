DROP TABLE IF EXISTS xlite_featured_products;
CREATE TABLE xlite_featured_products (
  product_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY  (category_id,product_id)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (118,'featured_products_look','FeaturedProducts','select',10,'grid');
INSERT INTO `xlite_config_translations` VALUES (92,'en',118,'Featured products look&feel','');

