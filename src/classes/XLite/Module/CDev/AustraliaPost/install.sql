-- DROP TABLE IF EXISTS xlite_aupost_cache;
-- CREATE TABLE xlite_aupost_cache (
--    weight decimal(12,2) not null,
--    origin_zipcode varchar(12) not null,
--    dest_zipcode varchar(12) not null,
--    dest_country varchar(40) not null,
--    height decimal(12,2) not null,
--    width decimal(12,2) not null,
--    length decimal(12,2) not null,
--    rates varchar(255) not null,
--    shipping_dates varchar(255) not null,
--    date int not null,
--    PRIMARY KEY (weight, origin_zipcode, dest_zipcode, dest_country, height, width, length)
-- ) TYPE=MyISAM;


INSERT INTO `xlite_config` VALUES (61,'currency_rate','CDev\\AustraliaPost','',0,'1');
INSERT INTO `xlite_config` VALUES (134,'height','CDev\\AustraliaPost','',0,'10');
INSERT INTO `xlite_config` VALUES (146,'length','CDev\\AustraliaPost','',0,'100');
INSERT INTO `xlite_config` VALUES (281,'width','CDev\\AustraliaPost','',0,'100');


INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1001,'aupost','aupost','AIR',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1002,'aupost','aupost','ECI_D',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1003,'aupost','aupost','ECI_M',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1004,'aupost','aupost','EXPRESS',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1005,'aupost','aupost','EPI',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1006,'aupost','aupost','STANDARD',0,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (1007,'aupost','aupost','SEA',0,0);

INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1001,'Australia Post Air Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1002,'Australia Post Express Courier International Document');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1003,'Australia Post Express Courier International Merchandise');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1004,'Australia Post Express Parcels');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1005,'Australia Post Express Post International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1006,'Australia Post Regular Parcels');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',1007,'Australia Post Sea Mail');

