DROP TABLE IF EXISTS xlite_ints_cache;
CREATE TABLE xlite_ints_cache (
  pounds decimal(12,2) NOT NULL default '0.00',
  orig_country char(2) NOT NULL default '',
  dest_country char(2) NOT NULL default '',
  orig_zipcode varchar(32) NOT NULL default '',
  dest_zipcode varchar(32) NOT NULL default '',
  delivery char(3) NOT NULL default '',
  pickup char(3) NOT NULL default '',
  length decimal(12,2) NOT NULL default '0.00',
  width decimal(12,2) NOT NULL default '0.00',
  height decimal(12,2) NOT NULL default '0.00',
  packaging char(3) NOT NULL default '',
  contents char(3) NOT NULL default '',
  codvalue int(11) NOT NULL default '0',
  insvalue int(11) NOT NULL default '0',
  date int(11) NOT NULL default '0',
  rates varchar(255) NOT NULL default '',
  PRIMARY KEY  (pounds,orig_country,dest_country,orig_zipcode,dest_zipcode,delivery,pickup,length,width,height,packaging,contents,codvalue,insvalue)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (55,'contents','Intershipper',NULL,0,'OTR');
INSERT INTO `xlite_config` VALUES (78,'delivery','Intershipper',NULL,0,'COM');
INSERT INTO `xlite_config` VALUES (94,'dunit','Intershipper',NULL,0,'IN');
INSERT INTO `xlite_config` VALUES (136,'height','Intershipper',NULL,0,'');
INSERT INTO `xlite_config` VALUES (143,'insvalue','Intershipper',NULL,0,'');
INSERT INTO `xlite_config` VALUES (148,'length','Intershipper',NULL,0,'');
INSERT INTO `xlite_config` VALUES (192,'packaging','Intershipper',NULL,0,'BOX');
INSERT INTO `xlite_config` VALUES (201,'password','Intershipper',NULL,0,'');
INSERT INTO `xlite_config` VALUES (203,'pickup','Intershipper',NULL,0,'DRP');
INSERT INTO `xlite_config` VALUES (263,'userid','Intershipper',NULL,0,'');
INSERT INTO `xlite_config` VALUES (283,'width','Intershipper',NULL,0,'');


INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3001,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3002,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3003,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3004,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3005,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3006,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3007,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3008,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3009,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3010,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3011,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3012,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3013,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3014,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3015,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3016,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3017,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3018,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3019,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3020,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3021,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3022,'intershipper','intershipper','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (3023,'intershipper','intershipper','',1,0);


INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3001,'DHL Overnight');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3002,'FedEx Canadian Ground');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3003,'FedEx Canadian International Economy');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3004,'FedEx Canadian International Priority');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3005,'FedEx Ground');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3006,'FedEx International Economy');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3007,'FedEx International Priority');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3008,'UPS 3 Day Select');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3009,'UPS Canadian Expedited');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3010,'UPS Canadian Express');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3011,'UPS Canadian Express Plus');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3012,'UPS Ground (Non-Machinable)');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3013,'UPS Next Day Air');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3014,'UPS Next Day Air Early AM');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3015,'UPS Next Day Air Saver');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3016,'UPS Standard Canadian Service');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3017,'USPS Express Mail Addresses');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3018,'USPS Express Mail PO');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3019,'USPS Global AirMail Parcel');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3020,'USPS Global Express Guaranteed Parcels');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3021,'USPS Global Express Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3022,'USPS Ground Machine');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',3023,'USPS Priority Mail');

