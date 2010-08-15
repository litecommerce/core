-- Update config table
-- shop_close
UPDATE xlite_config_translations SET option_name = 'Check this to temporary close the shop (not available in Demo store)' WHERE id = 234;

UPDATE xlite_config SET value = 'Y' WHERE name = 'customer_security';
UPDATE xlite_config SET value = 'Y' WHERE name = 'enable_sale_price';
UPDATE xlite_config SET value = 'Y' WHERE name = 'you_save';

UPDATE xlite_config SET value = 'a:3:{i:1;a:3:{s:7:\"orderby\";s:2:\"10\";s:10:\"membership\";s:4:\"Gold\";s:13:\"membership_id\";i:1;}i:2;a:3:{s:7:\"orderby\";s:2:\"20\";s:10:\"membership\";s:8:\"Platinum\";s:13:\"membership_id\";i:2;}i:3;a:3:{s:7:\"orderby\";s:2:\"30\";s:10:\"membership\";s:10:\"Wholesaler\";s:13:\"membership_id\";i:3;}}' WHERE name = 'membershipsCollection';

-- Enable demo modules
UPDATE xlite_modules SET enabled = '1' WHERE name IN (
	'AdvancedSearch',
	'AuthorizeNet',
	'Bestsellers',
	'DetailedImages',
	'DrupalConnector',
	'FeaturedProducts',
	'GiftCertificates',
	'GoogleCheckout',
	'InventoryTracking',
	'JoomlaConnector',
	'PayPalPro',
	'ProductAdviser',
	'ProductOptions',
	'UPSOnlineTools',
	'USPS',
	'WishList',
	'WholesaleTrading'
);

-- Test gift certificate
INSERT INTO xlite_giftcerts VALUES ('TESTGIFT',1,'Mr. Guest Guest','123','E','demo@litecommerce.com','','','','','0','','','','','','50.00','50.00','A',1270203328,1332411328,0,'','','no_border',NULL);

INSERT INTO xlite_zones VALUES (1,'United States area');
INSERT INTO xlite_zone_elements VALUES (1,1,'US','C');

INSERT INTO xlite_zones VALUES (2,'New York area');
INSERT INTO xlite_zone_elements VALUES (2,2,'US','C');
INSERT INTO xlite_zone_elements VALUES (3,2,'US_NY','S');
INSERT INTO xlite_zone_elements VALUES (4,2,'New York','T');
INSERT INTO xlite_zone_elements VALUES (5,2,'NY','T');
INSERT INTO xlite_zone_elements VALUES (6,2,'101%','Z');
INSERT INTO xlite_zone_elements VALUES (7,2,'102%','Z');

INSERT INTO xlite_zones VALUES (3,'California area');
INSERT INTO xlite_zone_elements VALUES (8,3,'US','C');
INSERT INTO xlite_zone_elements VALUES (9,3,'US_CA','S');
INSERT INTO xlite_zone_elements VALUES (10,3,'9%','Z');

INSERT INTO xlite_zones VALUES (4,'Europe');
INSERT INTO xlite_zone_elements VALUES (11,4,'DE','C');
INSERT INTO xlite_zone_elements VALUES (12,4,'GB','C');
INSERT INTO xlite_zone_elements VALUES (13,4,'FR','C');
INSERT INTO xlite_zone_elements VALUES (14,4,'ES','C');

INSERT INTO xlite_zones VALUES (5,'Atlantida');

