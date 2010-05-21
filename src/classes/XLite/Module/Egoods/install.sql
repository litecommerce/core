DROP TABLE IF EXISTS xlite_downloadable_files;
CREATE TABLE xlite_downloadable_files (
  file_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  store_type char(1) NOT NULL default '',
  data text NOT NULL,
  delivery char(1) NOT NULL default '',
  PRIMARY KEY  (file_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_downloadable_links;
CREATE TABLE xlite_downloadable_links (
  access_key varchar(32) NOT NULL default '',
  file_id int(11) NOT NULL default '0',
  available_downloads int(11) NOT NULL default '0',
  exp_time int(11) NOT NULL default '0',
  expire_on char(1) NOT NULL default '',
  link_type char(1) NOT NULL default '',
  PRIMARY KEY  (access_key)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_downloads_statistics;
CREATE TABLE xlite_downloads_statistics (
  stat_id int(11) NOT NULL auto_increment,
  file_id int(11) NOT NULL default '0',
  date int(11) NOT NULL default '0',
  headers text NOT NULL,
  PRIMARY KEY  (stat_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_pin_codes;
CREATE TABLE xlite_pin_codes (
  pin_id int(11) NOT NULL auto_increment,
  pin varchar(255) NOT NULL default '',
  enabled int(1) NOT NULL default '0',
  product_id int(11) NOT NULL default '0',
  item_id varchar(255) NOT NULL default '',
  order_id int(11) NOT NULL default '0',
  PRIMARY KEY  (pin_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_pin_settings;
CREATE TABLE xlite_pin_settings (
  product_id int(11) NOT NULL default '0',
  pin_type char(1) NOT NULL default '',
  gen_cmd_line varchar(255) NOT NULL default '',
  low_available_limit int(11) NOT NULL default '10',	
  PRIMARY KEY  (product_id)
) TYPE=MyISAM;

ALTER TABLE xlite_order_items ADD pincodes text NOT NULL default '';
ALTER TABLE xlite_order_items ADD egoods text NOT NULL default '';
ALTER TABLE xlite_products ADD egood_free_for_memberships varchar(255) NOT NULL default '';

INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'egoods_store_dir', 'Directory where egoods will be stored<br><i>Either absolute or relative to the LiteCommerce root directory</i>', '', 'Egoods', 10, 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'exp_days', 'Egoods links expire in (days)', '7', 'Egoods', 20, 'text');
UPDATE xlite_config SET comment= 'Egoods links expire in (days)' WHERE category='Egoods' AND name='exp_days';
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'exp_downloads', 'Number of available downloads', '10', 'Egoods', 30, 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'link_expires', 'Egoods links expire on', 'D', 'Egoods', 10, 'select');
UPDATE xlite_config SET comment= 'Egoods links expire on' WHERE category='Egoods' AND name='link_expires';
INSERT xlite_config ( name , comment , value , category , orderby , type) VALUES ('pincodes_per_page' , 'Pin codes per page' , '30' , 'Egoods' , 40 , 'text');

