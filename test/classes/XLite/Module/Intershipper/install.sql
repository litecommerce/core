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


INSERT INTO xlite_shipping VALUES (220,'intershipper','I','FedEx International Economy',1,0);
INSERT INTO xlite_shipping VALUES (219,'intershipper','I','FedEx International Priority',1,0);
INSERT INTO xlite_shipping VALUES (218,'intershipper','I','USPS Global Express Mail',1,0);
INSERT INTO xlite_shipping VALUES (217,'intershipper','I','USPS Global AirMail Parcel',1,0);
INSERT INTO xlite_shipping VALUES (216,'intershipper','I','USPS Express Mail PO',1,0);
INSERT INTO xlite_shipping VALUES (215,'intershipper','I','USPS Global Express Guaranteed Parcels',1,0);
INSERT INTO xlite_shipping VALUES (214,'intershipper','I','UPS Standard Canadian Service',1,0);
INSERT INTO xlite_shipping VALUES (213,'intershipper','I','UPS Canadian Expedited',1,0);
INSERT INTO xlite_shipping VALUES (212,'intershipper','I','UPS Canadian Express Plus',1,0);
INSERT INTO xlite_shipping VALUES (211,'intershipper','I','UPS Canadian Express',1,0);
INSERT INTO xlite_shipping VALUES (210,'intershipper','I','FedEx Canadian Ground',1,0);
INSERT INTO xlite_shipping VALUES (209,'intershipper','I','FedEx Canadian International Economy',1,0);
INSERT INTO xlite_shipping VALUES (208,'intershipper','I','FedEx Canadian International Priority',1,0);
INSERT INTO xlite_shipping VALUES (207,'intershipper','L','DHL Overnight',1,0);
INSERT INTO xlite_shipping VALUES (206,'intershipper','L','USPS Ground Machine',1,0);
INSERT INTO xlite_shipping VALUES (205,'intershipper','L','USPS Priority Mail',1,0);
INSERT INTO xlite_shipping VALUES (204,'intershipper','L','USPS Express Mail Addresses',1,0);
INSERT INTO xlite_shipping VALUES (203,'intershipper','L','UPS Ground (Non-Machinable)',1,0);
INSERT INTO xlite_shipping VALUES (202,'intershipper','L','UPS Next Day Air Early AM',1,0);
INSERT INTO xlite_shipping VALUES (201,'intershipper','L','UPS Next Day Air',1,0);
INSERT INTO xlite_shipping VALUES (200,'intershipper','L','UPS Next Day Air Saver',1,0);
INSERT INTO xlite_shipping VALUES (199,'intershipper','L','UPS 3 Day Select',1,0);
INSERT INTO xlite_shipping VALUES (198,'intershipper','L','FedEx Ground',1,0);

INSERT INTO xlite_config(name,value,category) VALUES ('contents','OTR','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('delivery','COM','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('dunit','IN','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('height','','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('insvalue','','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('length','','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('packaging','BOX','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('password','','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('pickup','DRP','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('userid','','Intershipper');
INSERT INTO xlite_config(name,value,category) VALUES ('width','','Intershipper');


