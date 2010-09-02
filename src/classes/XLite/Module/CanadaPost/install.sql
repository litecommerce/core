DROP TABLE IF EXISTS xlite_cps_cache;
CREATE TABLE xlite_cps_cache(
	weight decimal(12,2) not null,
	origin_zipcode varchar(12) not null,
	origin_country varchar(40) not null,
	dest_zipcode varchar(12) not null,
	dest_city varchar(40) not null,
	dest_country varchar(40) not null,
	dest_state varchar(40) not null,
	insured decimal(12,2) not null default 0,
	packed char(1) not null default 'Y',
	height decimal(12,2) not null,
	width decimal(12,2) not null,
	length decimal(12,2) not null,
	rates varchar(255) not null,
	date int not null,
	PRIMARY KEY (weight,origin_zipcode,origin_country,dest_zipcode,dest_city,dest_country,dest_state,insured,packed,height,width,length)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (62,'currency_rate','CanadaPost','',0,'1');
INSERT INTO `xlite_config` VALUES (135,'height','CanadaPost','',0,'2.00');
INSERT INTO `xlite_config` VALUES (142,'insured','CanadaPost','',0,'0');
INSERT INTO `xlite_config` VALUES (147,'length','CanadaPost','',0,'37.47');
INSERT INTO `xlite_config` VALUES (168,'merchant_id','CanadaPost','',0,'');
INSERT INTO `xlite_config` VALUES (194,'packed','CanadaPost','',0,'Y');
INSERT INTO `xlite_config` VALUES (251,'test_server','CanadaPost','',0,'1');
INSERT INTO `xlite_config` VALUES (282,'width','CanadaPost','',0,'24.13');


INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2001,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2002,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2003,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2004,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2005,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2006,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2007,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2008,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2009,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2010,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2011,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2012,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2013,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2014,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2015,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2016,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2017,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2018,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2019,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2020,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2021,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2022,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2023,'cps','cps','',1,0);
INSERT INTO `xlite_shipping_methods` (`method_id`, `processor`, `carrier`, `code`, `enabled`, `position`) VALUES (2024,'cps','cps','',1,0);


INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2001,'Canada Post Expedited');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2002,'Canada Post Expedited Evening');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2003,'Canada Post Expedited Saturday');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2004,'Canada Post Expedited US Business');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2005,'Canada Post Expedited US Commercial');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2006,'Canada Post International Purolator Courier');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2007,'Canada Post International Puropack');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2008,'Canada Post Parcel Air');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2009,'Canada Post Parcel Air US');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2010,'Canada Post Parcel Surface');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2011,'Canada Post Parcel Surface US');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2012,'Canada Post Priority Courier');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2013,'Canada Post Regular');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2014,'Canada Post Small Packets Air');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2015,'Canada Post Small Packets Air International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2016,'Canada Post Small Packets Surface');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2017,'Canada Post Small Packets Surface International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2018,'Canada Post US Purolator Courier');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2019,'Canada Post US Puropack');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2020,'Canada Post Xpresspost');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2021,'Canada Post Xpresspost Evening');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2022,'Canada Post XPressPost International');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2023,'Canada Post Xpresspost Saturday');
INSERT INTO `xlite_shipping_method_translations` (`code`, `id`, `name`) VALUES ('en',2024,'Canada Post Xpresspost USA');

