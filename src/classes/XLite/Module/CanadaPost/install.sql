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


INSERT INTO xlite_shipping VALUES (79,'cps','L','Canada Post Priority Courier',1,0);
INSERT INTO xlite_shipping VALUES (80,'cps','L','Canada Post Xpresspost',1,0);
INSERT INTO xlite_shipping VALUES (81,'cps','L','Canada Post Expedited',1,0);
INSERT INTO xlite_shipping VALUES (82,'cps','L','Canada Post Regular',1,0);
INSERT INTO xlite_shipping VALUES (83,'cps','L','Canada Post Xpresspost Evening',1,0);
INSERT INTO xlite_shipping VALUES (84,'cps','L','Canada Post Expedited Evening',1,0);
INSERT INTO xlite_shipping VALUES (85,'cps','L','Canada Post Xpresspost Saturday',1,0);
INSERT INTO xlite_shipping VALUES (86,'cps','L','Canada Post Expedited Saturday',1,0);
INSERT INTO xlite_shipping VALUES (87,'cps','I','Canada Post Parcel Surface US',1,0);
INSERT INTO xlite_shipping VALUES (88,'cps','I','Canada Post Parcel Air US',1,0);
INSERT INTO xlite_shipping VALUES (89,'cps','I','Canada Post Xpresspost USA',1,0);
INSERT INTO xlite_shipping VALUES (90,'cps','I','Canada Post US Purolator Courier',1,0);
INSERT INTO xlite_shipping VALUES (91,'cps','I','Canada Post US Puropack',1,0);
INSERT INTO xlite_shipping VALUES (92,'cps','I','Canada Post Parcel Surface',1,0);
INSERT INTO xlite_shipping VALUES (93,'cps','I','Canada Post Parcel Air',1,0);
INSERT INTO xlite_shipping VALUES (94,'cps','I','Canada Post International Purolator Courier',1,0);
INSERT INTO xlite_shipping VALUES (95,'cps','I','Canada Post International Puropack',1,0);
INSERT INTO xlite_shipping VALUES (96,'cps','I','Canada Post Small Packets Surface',1,0);
INSERT INTO xlite_shipping VALUES (97,'cps','I','Canada Post Small Packets Air',1,0);
INSERT INTO xlite_shipping VALUES (98,'cps','I','Canada Post Expedited US Commercial',1,0);
INSERT INTO xlite_shipping VALUES (99,'cps','I','Canada Post Small Packets Surface International',1,0);
INSERT INTO xlite_shipping VALUES (100,'cps','I','Canada Post Small Packets Air International',1,0); 
INSERT INTO xlite_shipping VALUES (101,'cps','I','Canada Post XPressPost International',1,0); 
INSERT INTO xlite_shipping VALUES (102,'cps','I','Canada Post Expedited US Business',1,0);


