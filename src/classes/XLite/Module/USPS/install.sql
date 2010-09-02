DROP TABLE IF EXISTS xlite_usps_int_cache;
CREATE TABLE xlite_usps_int_cache (
  ounces int(11) NOT NULL default '0',
  country varchar(50) NOT NULL default '',
  mailtype varchar(40) NOT NULL default '',
  value_of_content varchar(40) NOT NULL default '',
  date int(11) NOT NULL default '0',
  rates varchar(100) NOT NULL default '',
  PRIMARY KEY  (ounces,country,mailtype,value_of_content)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_usps_nat_cache;
CREATE TABLE xlite_usps_nat_cache (
  ounces int(11) NOT NULL default '0',
  ziporig varchar(12) NOT NULL default '',
  zipdest varchar(12) NOT NULL default '',
  package_size varchar(32) NOT NULL default '',
  machinable varchar(32) NOT NULL default '',
  container_priority varchar(32) NOT NULL default '',
  container_express varchar(32) NOT NULL default '',
  dim_lenght varchar(32) NOT NULL default '',
  dim_width varchar(32) NOT NULL default '',
  dim_height varchar(32) NOT NULL default '',
  dim_girth varchar(32) NOT NULL default '',
  fcmailtype varchar(32) NOT NULL default '',
  rates varchar(100) NOT NULL default '',
  date int(11) NOT NULL default '0',
  PRIMARY KEY  (ounces,ziporig,zipdest,package_size,machinable,container_priority,container_express,dim_lenght,dim_width,dim_height,dim_girth,fcmailtype)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (53,'container_express','USPS','',0,'None');
INSERT INTO `xlite_config` VALUES (54,'container_priority','USPS','',0,'None');
INSERT INTO `xlite_config` VALUES (82,'dim_girth','USPS','',0,'34');
INSERT INTO `xlite_config` VALUES (83,'dim_height','USPS','',0,'15');
INSERT INTO `xlite_config` VALUES (84,'dim_lenght','USPS','',0,'18');
INSERT INTO `xlite_config` VALUES (86,'dim_width','USPS','',0,'16');
INSERT INTO `xlite_config` VALUES (117,'fcmailtype','USPS','',0,'LETTER');
INSERT INTO `xlite_config` VALUES (139,'https','USPS','',0,'N');
INSERT INTO `xlite_config` VALUES (162,'machinable','USPS','',0,'True');
INSERT INTO `xlite_config` VALUES (163,'mailtype','USPS','',0,'Package');
INSERT INTO `xlite_config` VALUES (191,'package_size','USPS','',0,'Regular');
INSERT INTO `xlite_config` VALUES (231,'server','USPS','',0,'');
INSERT INTO `xlite_config` VALUES (264,'userid','USPS','',0,'');
INSERT INTO `xlite_config` VALUES (272,'value_of_content','USPS','',0,'500');


INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5001,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5002,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5003,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5004,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5005,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5006,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5007,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5008,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5009,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5010,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5011,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5012,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5013,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5014,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5015,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5016,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5017,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5018,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5019,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5020,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5021,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5022,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5023,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5024,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5025,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5026,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5027,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5028,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5029,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5030,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5031,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5032,'usps','usps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (5033,'usps','usps','',1,0);


INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5001,'U.S.P.S. Airmail Parcel Post');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5002,'U.S.P.S. Bound Printed Matter');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5003,'U.S.P.S. Economy (Surface) Letter Post');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5004,'U.S.P.S. Express Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5005,'U.S.P.S. Express Mail Flat-Rate Envelope');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5006,'U.S.P.S. Express Mail International (EMS)');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5007,'U.S.P.S. Express Mail International (EMS) Flat-Rate Envelope');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5008,'U.S.P.S. Express Mail P.O.');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5009,'U.S.P.S. First Class Mail International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5010,'U.S.P.S. First Class Mail International Large Envelope');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5011,'U.S.P.S. First Class Mail International Letters');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5012,'U.S.P.S. First Class Mail International Package');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5013,'U.S.P.S. First-Class Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5014,'U.S.P.S. Global Express Guaranteed');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5015,'U.S.P.S. Global Express Guaranteed Document');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5016,'U.S.P.S. Global Express Guaranteed Non-Document Non-Rectangular');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5017,'U.S.P.S. Global Express Guaranteed Non-Document Rectangular');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5018,'U.S.P.S. Ground (Machinable)');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5019,'U.S.P.S. Ground (Non-Machinable)');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5020,'U.S.P.S. Library Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5021,'U.S.P.S. Media Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5022,'U.S.P.S. Media Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5023,'U.S.P.S. Parcel Post');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5024,'U.S.P.S. Postcards');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5025,'U.S.P.S. Priority Mail');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5026,'U.S.P.S. Priority Mail Flat-Rate Box');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5027,'U.S.P.S. Priority Mail Flat-Rate Envelope');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5028,'U.S.P.S. Priority Mail Flat-Rate Letter');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5029,'U.S.P.S. Priority Mail International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5030,'U.S.P.S. Priority Mail International Flat-Rate Box');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5031,'U.S.P.S. Priority Mail International Flat-Rate Envelope');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5032,'U.S.P.S. Priority Mail International Large Flat-Rate Box');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',5033,'U.S.P.S. USPS GXG Envelopes');

