DROP TABLE IF EXISTS xlite_landing_links;
CREATE TABLE xlite_landing_links (
  link_id char(32) NOT NULL default '',
  session_id char(32) NOT NULL default '',
  expiry int(11) unsigned NOT NULL default 0,
  PRIMARY KEY (link_id),
  KEY expiry(expiry)
);

INSERT INTO `xlite_config` VALUES (93,'drupal_root_url','DrupalConnector','text',10,'');
INSERT INTO `xlite_config_translations` VALUES (70,'en',93,'Root URL of the Drupal installation','');

