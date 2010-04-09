-- DROP TABLE IF EXISTS xlite_ups_cache;
CREATE TABLE xlite_ups_cache(
        pounds decimal(12,2) not null,
        origin_zipcode varchar(12) not null,
        origin_country varchar(2) not null,
        destination_zipcode varchar(12) not null,
        destination_country varchar(2) not null,
        packaging char(2) not null,
        pickup char(2) not null,
        width decimal(12,2) not null,
        height decimal(12,2) not null,
        length decimal(12,2) not null,
        codvalue decimal(12,2) not null default 0,
        insured decimal(12,2) not null default 0,
        sat_delivery int not null default 0,
        sat_pickup int not null default 0,
        residential int not null default 1,
        rates varchar(255) not null,
        date int not null,
		PRIMARY KEY (pounds,origin_zipcode,origin_country,destination_zipcode,destination_country,packaging,pickup,width,height,length,insured,sat_delivery,sat_pickup,residential,codvalue)
) TYPE=MyISAM;

INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (134,'ups','I','UPS Standard',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (24,'ups','L','UPS 3 Day Select',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (23,'ups','L','UPS Ground',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (133,'ups','I','UPS Worldwide Express Plus',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (132,'ups','I','UPS Worldwide Expedited',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (131,'ups','I','UPS Worldwide Express',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (25,'ups','L','UPS 2nd Day Air A.M.',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (26,'ups','L','UPS 2nd Day Air',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (27,'ups','L','UPS Next Day Air Saver',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (28,'ups','L','UPS Next Day Air Early A.M.',1,0);
INSERT INTO xlite_shipping(shipping_id,class,destination,name,enabled,order_by) VALUES (29,'ups','L','UPS Next Day Air',1,0);

INSERT INTO xlite_config(name,value,category) VALUES ('insured','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('height','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('length','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('width','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('pickup','01','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('packaging','00','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('server','https://www.ups.com/ups.app/xml/Rate','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('accessKey','','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('password','','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('residential','1','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('sat_delivery','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('sat_pickup','0','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('userid','','UPS');
INSERT INTO xlite_config(name,value,category) VALUES ('weight_unit','LBS','UPS');


