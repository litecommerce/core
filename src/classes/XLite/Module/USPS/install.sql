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


INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Airmail Parcel Post', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Bound Printed Matter', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Economy (Surface) Letter Post', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Express Mail', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Express Mail Flat-Rate Envelope', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Express Mail International (EMS)', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Express Mail International (EMS) Flat-Rate Envelope', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Express Mail P.O.', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. First Class Mail International', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. First Class Mail International Large Envelope', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. First Class Mail International Letters', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. First Class Mail International Package', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. First-Class Mail', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Global Express Guaranteed', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Global Express Guaranteed Document', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Global Express Guaranteed Non-Document Non-Rectangular', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Global Express Guaranteed Non-Document Rectangular', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Ground (Machinable)', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Ground (Non-Machinable)', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Library Mail', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Media Mail', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Media Mail', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Parcel Post', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Postcards', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail Flat-Rate Box', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail Flat-Rate Envelope', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail Flat-Rate Letter', 'L');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail International', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail International Flat-Rate Box', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail International Flat-Rate Envelope', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. Priority Mail International Large Flat-Rate Box', 'I');
INSERT INTO xlite_shipping (class, name, destination) VALUES ('usps', 'U.S.P.S. USPS GXG Envelopes', 'I');
