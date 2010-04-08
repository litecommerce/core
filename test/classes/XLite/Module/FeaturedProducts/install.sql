DROP TABLE IF EXISTS xlite_featured_products;
CREATE TABLE xlite_featured_products (
  product_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY  (category_id,product_id)
) TYPE=MyISAM;

INSERT INTO xlite_config VALUES ('featured_products_look','Featured products look&feel','grid','FeaturedProducts',10,'select');
