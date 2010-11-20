-- Update config table
-- shop_close
UPDATE xlite_config_translations SET option_name = 'Check this to temporary close the shop (not available in Demo store)' WHERE id = 234;

UPDATE xlite_config SET value = 'Y' WHERE name = 'customer_security';
UPDATE xlite_config SET value = 'Y' WHERE name = 'enable_sale_price';
UPDATE xlite_config SET value = 'Y' WHERE name = 'you_save';

UPDATE xlite_config SET value = 'a:3:{i:1;a:3:{s:7:\"orderby\";s:2:\"10\";s:10:\"membership\";s:4:\"Gold\";s:13:\"membership_id\";i:1;}i:2;a:3:{s:7:\"orderby\";s:2:\"20\";s:10:\"membership\";s:8:\"Platinum\";s:13:\"membership_id\";i:2;}i:3;a:3:{s:7:\"orderby\";s:2:\"30\";s:10:\"membership\";s:10:\"Wholesaler\";s:13:\"membership_id\";i:3;}}' WHERE name = 'membershipsCollection';

UPDATE xlite_modules SET enabled = '0';

-- Test gift certificate
--INSERT INTO xlite_giftcerts VALUES ('TESTGIFT',1,'Mr. Guest Guest','123','E','demo@litecommerce.com','','','','','0','','','','','','50.00','50.00','A',1270203328,1332411328,0,'','','no_border',NULL);

-- Zones [5] (do not change zones as it is used in the unit-tests)

INSERT INTO xlite_zones VALUES (10,'United States area',0);
INSERT INTO xlite_zone_elements VALUES (1,10,'US','C');

INSERT INTO xlite_zones VALUES (20,'New York area',0);
INSERT INTO xlite_zone_elements VALUES (2,20,'US','C');
INSERT INTO xlite_zone_elements VALUES (3,20,'US_NY','S');
INSERT INTO xlite_zone_elements VALUES (4,20,'New York','T');
INSERT INTO xlite_zone_elements VALUES (5,20,'NY','T');
INSERT INTO xlite_zone_elements VALUES (6,20,'101%','Z');
INSERT INTO xlite_zone_elements VALUES (7,20,'102%','Z');

INSERT INTO xlite_zones VALUES (30,'California area',0);
INSERT INTO xlite_zone_elements VALUES (8,30,'US','C');
INSERT INTO xlite_zone_elements VALUES (9,30,'US_CA','S');
INSERT INTO xlite_zone_elements VALUES (10,30,'9%','Z');

INSERT INTO xlite_zones VALUES (40,'Europe',0);
INSERT INTO xlite_zone_elements VALUES (11,40,'DE','C');
INSERT INTO xlite_zone_elements VALUES (12,40,'GB','C');
INSERT INTO xlite_zone_elements VALUES (13,40,'FR','C');
INSERT INTO xlite_zone_elements VALUES (14,40,'ES','C');

INSERT INTO xlite_zones VALUES (50,'Atlantida',0);

-- Shipping methods [2]

INSERT INTO xlite_shipping_methods (method_id, processor, carrier, code, enabled, position) VALUES (100, 'offline', '', '', 1, 10);
INSERT INTO xlite_shipping_method_translations (code, id, name) VALUES ('en', 100, 'Courier');

INSERT INTO xlite_shipping_methods (method_id, processor, carrier, code, enabled, position) VALUES (101, 'offline', '', '', 1, 20);
INSERT INTO xlite_shipping_method_translations (code, id, name) VALUES ('en', 101, 'Local shipping');

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (100, 10, 0, 999999.00, 0, 999999.00, 0, 999999.00, 2.45, 1.5, 0, 0);

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (101, 10, 0, 999999.00, 0, 999999.00, 0, 999999.00, 3.45, 1.75, 0, 0);

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (100, 20, 0, 999999.00, 0, 999999.00, 0, 999999.00, 2.50, 1.5, 0, 0);

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (101, 20, 0, 999999.00, 0, 999999.00, 0, 999999.00, 3.50, 1.75, 0, 0);

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (100, 1, 0, 999999.00, 0, 999999.00, 0, 999999.00, 5.50, 1.75, 0, 0);

INSERT INTO xlite_shipping_markups (method_id,zone_id,min_weight,max_weight,min_total,max_total,min_items,max_items,markup_flat,markup_percent,markup_per_item,markup_per_weight) VALUES (101, 1, 0, 999999.00, 0, 999999.00, 0, 999999.00, 6.50, 1.85, 0, 0);

