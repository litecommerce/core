DROP TABLE IF EXISTS xlite_extra_fields;
CREATE TABLE xlite_extra_fields (
  field_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  default_value varchar(255) NOT NULL default '',
  enabled int(1) NOT NULL default '1',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY  (field_id),
  KEY product_id (product_id),
  KEY order_by (order_by)
); 
DROP TABLE IF EXISTS xlite_extra_field_values;
CREATE TABLE xlite_extra_field_values (
  product_id int(11) NOT NULL default '0',
  field_id int(11) NOT NULL default '0',
  value text NOT NULL default '',
  KEY field_key (product_id, field_id),
  FULLTEXT KEY value (value)
);
UPDATE xlite_config SET orderby=20 WHERE name='minimal_order_amount';
INSERT INTO xlite_config values ('maximal_order_amount','Maximum allowed order total', '99999', 'General', 30, 'text');
INSERT INTO xlite_config VALUES ('enable_anon_checkout','Enable anonymous checkout','Y','General',100,'checkbox');

