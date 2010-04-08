ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config VALUES ('userid','','','USPS',0,'');
INSERT INTO xlite_config VALUES ('server','','','USPS',0,'');
INSERT INTO xlite_config VALUES ('container_express','','None','USPS',0,'');
INSERT INTO xlite_config VALUES ('container_priority','','None','USPS',0,'');
INSERT INTO xlite_config VALUES ('mailtype','','Package','USPS',0,'');
INSERT INTO xlite_config VALUES ('machinable','','True','USPS',0,'');
INSERT INTO xlite_config VALUES ('package_size','','Regular','USPS',0,'');
INSERT INTO xlite_config VALUES ('value_of_content','','500','USPS',0,'');
INSERT INTO xlite_config VALUES ('fcmailtype','','LETTER','USPS',0,'');
INSERT INTO xlite_config VALUES ('dim_girth','','34','USPS',0,'');
INSERT INTO xlite_config VALUES ('dim_height','','15','USPS',0,'');
INSERT INTO xlite_config VALUES ('dim_lenght','','18','USPS',0,'');
INSERT INTO xlite_config VALUES ('dim_width','','16','USPS',0,'');
CREATE TABLE xlite_usps_int_cache (
  ounces int(11) NOT NULL default '0',
  country varchar(50) NOT NULL default '',
  mailtype varchar(40) NOT NULL default '',
  value_of_content varchar(40) NOT NULL default '',
  date int(11) NOT NULL default '0',
  rates varchar(100) NOT NULL default '',
  PRIMARY KEY  (ounces,country,mailtype,value_of_content)
) TYPE=MyISAM;
ALTER TABLE xlite_usps_int_cache ADD COLUMN value_of_content varchar(40) NOT NULL AFTER mailtype;
ALTER TABLE xlite_usps_int_cache DROP PRIMARY KEY;
ALTER TABLE xlite_usps_int_cache ADD PRIMARY KEY (ounces,country,mailtype,value_of_content);
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
ALTER TABLE xlite_usps_nat_cache ADD COLUMN dim_lenght varchar(32) NOT NULL AFTER container_express;
ALTER TABLE xlite_usps_nat_cache ADD COLUMN dim_width varchar(32) NOT NULL AFTER dim_lenght;
ALTER TABLE xlite_usps_nat_cache ADD COLUMN dim_height varchar(32) NOT NULL AFTER dim_width;
ALTER TABLE xlite_usps_nat_cache ADD COLUMN dim_girth varchar(32) NOT NULL AFTER dim_height;
ALTER TABLE xlite_usps_nat_cache ADD COLUMN fcmailtype varchar(32) NOT NULL AFTER dim_girth;
ALTER TABLE xlite_usps_nat_cache DROP PRIMARY KEY;
ALTER TABLE xlite_usps_nat_cache ADD PRIMARY KEY (ounces,ziporig,zipdest,package_size,machinable,container_priority,container_express,dim_lenght,dim_width,dim_height,dim_girth,fcmailtype);

DELETE FROM xlite_shipping WHERE shipping_id>=150 AND shipping_id<=158 AND class="usps";
DELETE FROM xlite_shipping WHERE shipping_id>=168 AND shipping_id<=184 AND class="usps";
DELETE FROM xlite_shipping WHERE shipping_id>=319 AND shipping_id<=323 AND class="usps";

REPLACE INTO xlite_shipping VALUES (150,'usps','L','U.S.P.S. First-Class Mail Parcel',1,0);
REPLACE INTO xlite_shipping VALUES (151,'usps','L','U.S.P.S. First-Class Mail Flat',1,0);
REPLACE INTO xlite_shipping VALUES (152,'usps','L','U.S.P.S. Priority Mail Flat-Rate Envelope',1,0);
REPLACE INTO xlite_shipping VALUES (153,'usps','L','U.S.P.S. Express Mail PO to PO',1,0);
REPLACE INTO xlite_shipping VALUES (154,'usps','I','U.S.P.S. First-Class Mail International',1,0);
REPLACE INTO xlite_shipping VALUES (155,'usps','I','U.S.P.S. Priority Mail International Flat Rate Envelope',1,0);
REPLACE INTO xlite_shipping VALUES (156,'usps','I','U.S.P.S. Priority Mail International',1,0);
REPLACE INTO xlite_shipping VALUES (157,'usps','I','U.S.P.S. Express Mail International (EMS) Flat Rate Envelope',1,0);
REPLACE INTO xlite_shipping VALUES (158,'usps','I','U.S.P.S. Express Mail International (EMS)',1,0);
REPLACE INTO xlite_shipping VALUES (168,'usps','I','U.S.P.S. Global Express Guaranteed Document',1,0);
REPLACE INTO xlite_shipping VALUES (169,'usps','I','U.S.P.S. Global Express Guaranteed',1,0);
REPLACE INTO xlite_shipping VALUES (170,'usps','L','U.S.P.S. Priority Mail',1,0);
REPLACE INTO xlite_shipping VALUES (171,'usps','L','U.S.P.S. Media Mail',1,0);
REPLACE INTO xlite_shipping VALUES (172,'usps','L','U.S.P.S. Library Mail',1,0);
REPLACE INTO xlite_shipping VALUES (173,'usps','L','U.S.P.S. Bound Printed Matter',1,0);
REPLACE INTO xlite_shipping VALUES (174,'usps','L','U.S.P.S. Parcel Post',1,0);
REPLACE INTO xlite_shipping VALUES (175,'usps','L','U.S.P.S. Priority Mail Flat-Rate Box',1,0);
REPLACE INTO xlite_shipping VALUES (176,'usps','L','U.S.P.S. First-Class Mail',1,0);
REPLACE INTO xlite_shipping VALUES (177,'usps','L','U.S.P.S. Express Mail',1,0);
REPLACE INTO xlite_shipping VALUES (178,'usps','L','U.S.P.S. Express Mail Flat-Rate Envelope',1,0);
REPLACE INTO xlite_shipping VALUES (179,'usps','I','U.S.P.S. Priority Mail International Flat Rate Box',1,0);
REPLACE INTO xlite_shipping VALUES (180,'usps','I','U.S.P.S. Global Express Guaranteed Non-Document Non-Rectangular',1,0);
REPLACE INTO xlite_shipping VALUES (181,'usps','I','U.S.P.S. Global Express Guaranteed Non-Document Rectangular',1,0);
REPLACE INTO xlite_shipping VALUES (182,'usps','I','U.S.P.S. Postcards',1,0);
