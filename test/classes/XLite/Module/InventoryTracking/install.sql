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

INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'track_placed_order', 'Track inventory when order is placed', 'N', 'InventoryTracking', '10', 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'clone_inventory', 'Clone inventory data when product is cloned', 'Y', 'InventoryTracking', '40', 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'create_inventory', 'Automatically create product\'s inventory record when a new product is created', 'Y', 'InventoryTracking', '50', 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'inventory_amount', 'Default inventory amount', '10', 'InventoryTracking', '60', 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'low_amount', 'Default low available limit', '10', 'InventoryTracking', '70', 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'send_notification', 'Enable low stock limit mail notification to admin', 'Y', 'InventoryTracking', '20', 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'exclude_product', 'Exclude out-of-stock products from product list (not available for the products with product options set)', 'N', 'InventoryTracking', '30', 'checkbox');
UPDATE xlite_config SET comment= 'Exclude out-of-stock products from product list' WHERE category='InventoryTracking' AND name='exclude_product';

