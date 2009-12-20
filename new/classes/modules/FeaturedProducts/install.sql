ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
CREATE TABLE xlite_featured_products (
  product_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY  (category_id,product_id)
) TYPE=MyISAM;
INSERT INTO xlite_config VALUES ('featured_products_look','Featured products look&feel','modules/FeaturedProducts/featuredProducts_icons.tpl','FeaturedProducts',10,'select');
