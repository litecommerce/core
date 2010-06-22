DROP TABLE IF EXISTS xlite_inventories;
CREATE TABLE xlite_inventories (
  inventory_id varchar(255) NOT NULL default '',
  amount int(11) NOT NULL default '0',
  low_avail_limit int(11) NOT NULL default '10',
  enabled int(1) NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  inventory_sku varchar(32) NOT NULL default '',
  PRIMARY KEY (inventory_id),
  KEY order_by (order_by)
) TYPE=MyISAM;

ALTER TABLE xlite_orders ADD inventory_changed int(1) NOT NULL DEFAULT '0';

ALTER TABLE xlite_products ADD sku_variants text NOT NULL default '';
ALTER TABLE xlite_products ADD tracking int NOT NULL default '0';

INSERT INTO `xlite_config` VALUES (39,'clone_inventory','InventoryTracking','checkbox',40,'Y');
INSERT INTO `xlite_config` VALUES (59,'create_inventory','InventoryTracking','checkbox',50,'Y');
INSERT INTO `xlite_config` VALUES (108,'exclude_product','InventoryTracking','checkbox',30,'N');
INSERT INTO `xlite_config` VALUES (144,'inventory_amount','InventoryTracking','text',60,'10');
INSERT INTO `xlite_config` VALUES (161,'low_amount','InventoryTracking','text',70,'10');
INSERT INTO `xlite_config` VALUES (229,'send_notification','InventoryTracking','checkbox',20,'Y');
INSERT INTO `xlite_config` VALUES (257,'track_placed_order','InventoryTracking','checkbox',10,'N');

INSERT INTO `xlite_config_translations` VALUES (33,'en',39,'Clone inventory data when product is cloned','');
INSERT INTO `xlite_config_translations` VALUES (49,'en',59,'Automatically create product\'s inventory record when a new product is created','');
INSERT INTO `xlite_config_translations` VALUES (84,'en',108,'Exclude out-of-stock products from product list','');
INSERT INTO `xlite_config_translations` VALUES (110,'en',144,'Default inventory amount','');
INSERT INTO `xlite_config_translations` VALUES (120,'en',161,'Default low available limit','');
INSERT INTO `xlite_config_translations` VALUES (173,'en',229,'Enable low stock limit mail notification to admin','');
INSERT INTO `xlite_config_translations` VALUES (196,'en',257,'Track inventory when order is placed','');

