DROP TABLE IF EXISTS xlite_product_options;
CREATE TABLE xlite_product_options (
  option_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  optclass varchar(128) NOT NULL default '',
  opttext varchar(255) NOT NULL default '',
  options text NOT NULL default '',
  orderby int(11) NOT NULL default '0',
  opttype varchar(32) NOT NULL default 'SelectBox',
  cols int(32) NOT NULL default '0',
  rows int(32) NOT NULL default '0',
  parent_option_id INT(11) DEFAULT '0' NOT NULL,
  categories TEXT NOT NULL,
  PRIMARY KEY  (option_id),
  KEY product_id (product_id),
  KEY orderby (orderby)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_product_options_ex;
CREATE TABLE xlite_product_options_ex (
  option_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  exception text NOT NULL default '',
  PRIMARY KEY  (option_id),
  KEY product_id (product_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_product_options_js;
CREATE TABLE xlite_product_options_js (
  product_id int(11) NOT NULL default '0',
  javascript_code text,
  PRIMARY KEY  (product_id)
) TYPE=MyISAM;

ALTER TABLE xlite_order_items ADD options TEXT NOT NULL;

ALTER TABLE xlite_products ADD expansion_limit int NOT NULL default 0;

INSERT INTO `xlite_config` VALUES (40,'clone_product_options','ProductOptions','checkbox',30,'Y');
INSERT INTO `xlite_config_translations` VALUES (34,'en',40,'Clone product options when product is cloned','');

