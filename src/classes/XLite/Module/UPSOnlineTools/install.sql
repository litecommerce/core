
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

INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (134,'ups','L','UPS Standard',1,20);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (24,'ups','L','UPS 3 Day Select',1,70);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (23,'ups','L','UPS Ground',1,80);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (133,'ups','I','UPS Worldwide Express Plus',1,30);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (132,'ups','I','UPS Worldwide Expedited',1,120);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (131,'ups','I','UPS Worldwide Express',1,110);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (25,'ups','L','UPS 2nd Day Air A.M.',1,60);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (26,'ups','L','UPS 2nd Day Air',1,50);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (27,'ups','L','UPS Next Day Air Saver',1,100);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (28,'ups','L','UPS Next Day Air Early A.M.',1,10);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (29,'ups','L','UPS Next Day Air',1,90);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (140,'ups','I','UPS Standard to Canada',1,40);

INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (141,'ups','I','UPS Express',1,130);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (142,'ups','I','UPS Expedited',1,140);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (30,'ups','I','UPS Saver',1,150);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (143,'ups','I','UPS Express Early A.M.',1,160);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (144,'ups','I','UPS Express Plus',1,170);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (31,'ups','L','UPS Today Standard',1,180);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (32,'ups','L','UPS Today Dedicated Courrier',1,190);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (33,'ups','L','UPS Today Intercity',1,200);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (34,'ups','L','UPS Today Express',1,210);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (35,'ups','L','UPS Today Express Saver',1,220);

INSERT INTO xlite_config(name,value,category) VALUES ('server','https://www.ups.com/ups.app/xml/','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('devlicense','EBA2F47A37670E96','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('UPS_accesskey','','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('UPS_username','','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('UPS_password','','UPSOnlineTools');

INSERT INTO xlite_config(name,value,category) VALUES ('account_type','01','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('packaging_type','00','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('length','10','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('width','10','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('height','10','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category,type) VALUES ('upsoptions','','UPSOnlineTools', 'serialized');
INSERT INTO xlite_config(name,value,category) VALUES ('conversion_rate','1','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('av_status','Y','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('av_quality','exact','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('dim_units','inches','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('currency_code','','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('residential','','UPSOnlineTools');
INSERT INTO xlite_config(name,value,category) VALUES ('delivery_conf','0','UPSOnlineTools');

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('packing_algorithm', 'Packing algorithm', '0', 'UPSOnlineTools', '0', 'text');

INSERT INTO xlite_config (name, value, category, type) VALUES ('display_gdlib', '0', 'UPSOnlineTools', 'text');
INSERT INTO xlite_config (name, value, category, type) VALUES ('visual_container_width', '200', 'UPSOnlineTools', 'text');
INSERT INTO xlite_config (name, value, category, type) VALUES ('packing_limit', '150', 'UPSOnlineTools', 'text');

INSERT INTO xlite_config (name, value, category, type) VALUES ('cache_autoclean', '1', 'UPSOnlineTools', 'text');
INSERT INTO xlite_config (name, value, category, type) VALUES ('level_display_method', '0', 'UPSOnlineTools', 'text');


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
