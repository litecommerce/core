
--
-- module: UPSOnlineTools
--
-- @version $Id: install.sql,v 1.5 2009/02/25 10:28:52 fundaev Exp $
--

DROP TABLE IF EXISTS xlite_ups_online_tools_cache;
CREATE TABLE xlite_ups_online_tools_cache(
		pounds decimal(12,2) not null,
		origin_address varchar(64) not null,
		origin_state varchar(12) not null,
        origin_zipcode varchar(12) not null,
        origin_country varchar(2) not null,
		origin_city varchar(64) not null default '',
		destination_address varchar(64) not null,
		destination_state varchar(12) not null,
        destination_zipcode varchar(12) not null,
        destination_country varchar(2) not null,
		destination_city varchar(64) not null default '',
        pickup char(2) not null,
        sat_delivery int not null default 0,
        sat_pickup int not null default 0,
        residential int not null default 1,
		fingerprint varchar(64) not null,
        rates varchar(255) not null,
        date int not null,
		PRIMARY KEY (pounds,origin_zipcode,origin_country,origin_city,destination_zipcode,destination_country,destination_city,pickup,sat_delivery,sat_pickup,residential,fingerprint)
) TYPE=MyISAM;

ALTER TABLE xlite_products ADD COLUMN ups_width decimal(12,2) NOT NULL default 1;
ALTER TABLE xlite_products ADD COLUMN ups_height decimal(12,2) NOT NULL default 1;
ALTER TABLE xlite_products ADD COLUMN ups_length decimal(12,2) NOT NULL default 1;
ALTER TABLE xlite_products ADD COLUMN ups_handle_care int(1) NOT NULL default 0;
ALTER TABLE xlite_products ADD COLUMN ups_add_handling int(1) NOT NULL default 0;
ALTER TABLE xlite_products ADD COLUMN ups_declared_value_set int(1) NOT NULL default 0;
ALTER TABLE xlite_products ADD COLUMN ups_declared_value decimal(12,2) NOT NULL default 0.00;
ALTER TABLE xlite_products ADD COLUMN ups_packaging int(11) NOT NULL default 0;

ALTER TABLE xlite_orders ADD COLUMN ups_containers text NOT NULL default '';

INSERT INTO `xlite_config` VALUES (1,'account_type','UPSOnlineTools',NULL,0,'01');
INSERT INTO `xlite_config` VALUES (21,'av_quality','UPSOnlineTools',NULL,0,'exact');
INSERT INTO `xlite_config` VALUES (22,'av_status','UPSOnlineTools',NULL,0,'Y');
INSERT INTO `xlite_config` VALUES (28,'cache_autoclean','UPSOnlineTools','text',0,'1');
INSERT INTO `xlite_config` VALUES (56,'conversion_rate','UPSOnlineTools',NULL,0,'1');
INSERT INTO `xlite_config` VALUES (60,'currency_code','UPSOnlineTools',NULL,0,'');
INSERT INTO `xlite_config` VALUES (79,'delivery_conf','UPSOnlineTools',NULL,0,'0');
INSERT INTO `xlite_config` VALUES (81,'devlicense','UPSOnlineTools',NULL,0,'EBA2F47A37670E96');
INSERT INTO `xlite_config` VALUES (85,'dim_units','UPSOnlineTools',NULL,0,'inches');
INSERT INTO `xlite_config` VALUES (92,'display_gdlib','UPSOnlineTools','text',0,'0');
INSERT INTO `xlite_config` VALUES (137,'height','UPSOnlineTools',NULL,0,'10');
INSERT INTO `xlite_config` VALUES (149,'length','UPSOnlineTools',NULL,0,'10');
INSERT INTO `xlite_config` VALUES (150,'level_display_method','UPSOnlineTools','text',0,'0');
INSERT INTO `xlite_config` VALUES (193,'packaging_type','UPSOnlineTools',NULL,0,'00');
INSERT INTO `xlite_config` VALUES (195,'packing_algorithm','UPSOnlineTools','text',0,'0');
INSERT INTO `xlite_config` VALUES (196,'packing_limit','UPSOnlineTools','text',0,'150');
INSERT INTO `xlite_config` VALUES (220,'residential','UPSOnlineTools',NULL,0,'');
INSERT INTO `xlite_config` VALUES (230,'server','UPSOnlineTools',NULL,0,'https://www.ups.com/ups.app/xml/');
INSERT INTO `xlite_config` VALUES (259,'upsoptions','UPSOnlineTools','serialized',0,'');
INSERT INTO `xlite_config` VALUES (260,'UPS_accesskey','UPSOnlineTools',NULL,0,'');
INSERT INTO `xlite_config` VALUES (261,'UPS_password','UPSOnlineTools',NULL,0,'');
INSERT INTO `xlite_config` VALUES (262,'UPS_username','UPSOnlineTools',NULL,0,'');
INSERT INTO `xlite_config` VALUES (274,'visual_container_width','UPSOnlineTools','text',0,'200');
INSERT INTO `xlite_config` VALUES (284,'width','UPSOnlineTools',NULL,0,'10');

INSERT INTO `xlite_config_translations` VALUES (147,'en',195,'Packing algorithm','');


UPDATE xlite_countries SET eu_member='Y' WHERE code='BG';
UPDATE xlite_countries SET eu_member='Y' WHERE code='CY';
UPDATE xlite_countries SET eu_member='Y' WHERE code='EE';
UPDATE xlite_countries SET eu_member='Y' WHERE code='HU';
UPDATE xlite_countries SET eu_member='Y' WHERE code='LV';
UPDATE xlite_countries SET eu_member='Y' WHERE code='LT';
UPDATE xlite_countries SET eu_member='Y' WHERE code='MT';
UPDATE xlite_countries SET eu_member='Y' WHERE code='MC';
UPDATE xlite_countries SET eu_member='Y' WHERE code='PL';
UPDATE xlite_countries SET eu_member='Y' WHERE code='RO';
UPDATE xlite_countries SET eu_member='Y' WHERE code='SK';
UPDATE xlite_countries SET eu_member='Y' WHERE code='SI';


INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4001,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4002,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4003,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4004,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4005,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4006,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4007,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4008,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4009,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4010,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4011,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4012,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4013,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4014,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4015,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4016,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4017,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4018,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4019,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4020,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4021,'ups','ups','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (4022,'ups','ups','',1,0);


INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4001,'UPS 2nd Day Air');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4002,'UPS 2nd Day Air A.M.');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4003,'UPS 3 Day Select');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4004,'UPS Expedited');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4005,'UPS Express');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4006,'UPS Express Early A.M.');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4007,'UPS Express Plus');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4008,'UPS Ground');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4009,'UPS Next Day Air');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4010,'UPS Next Day Air Early A.M.');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4011,'UPS Next Day Air Saver');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4012,'UPS Saver');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4013,'UPS Standard');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4014,'UPS Standard to Canada');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4015,'UPS Today Dedicated Courrier');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4016,'UPS Today Express');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4017,'UPS Today Express Saver');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4018,'UPS Today Intercity');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4019,'UPS Today Standard');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4020,'UPS Worldwide Expedited');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4021,'UPS Worldwide Express');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',4022,'UPS Worldwide Express Plus');

