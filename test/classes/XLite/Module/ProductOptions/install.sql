ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
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
  PRIMARY KEY  (option_id),
  KEY product_id (product_id),
  KEY orderby (orderby)
) TYPE=MyISAM;
ALTER TABLE xlite_product_options ADD opttype varchar(32) NOT NULL default 'SelectBox';
ALTER TABLE xlite_product_options ADD cols int(32) NOT NULL default '0';
ALTER TABLE xlite_product_options ADD rows int(32) NOT NULL default '0';
ALTER TABLE xlite_product_options MODIFY options TEXT NOT NULL default '';
UPDATE xlite_product_options SET opttype='Text', cols=25 WHERE options='' and cols=0;
CREATE TABLE xlite_product_options_ex (
  option_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  exception varchar(255) NOT NULL default '',
  PRIMARY KEY  (option_id),
  KEY product_id (product_id)
) TYPE=MyISAM;
CREATE TABLE xlite_product_options_js (
  product_id int(11) NOT NULL default '0',
  javascript_code text,
  PRIMARY KEY  (product_id)
) TYPE=MyISAM;
ALTER TABLE xlite_order_items ADD options TEXT NOT NULL;

INSERT INTO xlite_config VALUES ('clone_product_options','Clone product options when product is cloned','Y','ProductOptions',30,'checkbox');
ALTER TABLE xlite_products ADD expansion_limit int NOT NULL default 0;
ALTER TABLE xlite_product_options ADD parent_option_id INT(11) DEFAULT '0' NOT NULL;
ALTER TABLE xlite_product_options ADD categories TEXT NOT NULL;

ALTER TABLE xlite_product_options_ex CHANGE exception exception text NOT NULL default '';
