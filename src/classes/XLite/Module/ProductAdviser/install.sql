DROP TABLE IF EXISTS xlite_related_products;
CREATE TABLE xlite_related_products (
	product_id int(11) NOT NULL default '0',
	related_product_id int(11) NOT NULL default '0',
	order_by int(11) NOT NULL default '0',
	PRIMARY KEY (related_product_id,product_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_products_recently_viewed;
CREATE TABLE xlite_products_recently_viewed (
	sid VARCHAR(32) NOT NULL default '',
	product_id int(11) NOT NULL default '0',
	views_number int(11) NOT NULL default '0',
	last_viewed int(11) NOT NULL default '0',
	PRIMARY KEY (sid,product_id),
	KEY (views_number),
	KEY (last_viewed)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_products_new_arrivals;
CREATE TABLE xlite_products_new_arrivals (
	product_id int(11) NOT NULL default '0',
	added int(11) NOT NULL default '0',
	new char(1) NOT NULL default 'N',
	updated int(11) NOT NULL default '0',
	PRIMARY KEY (product_id),
	KEY (added),
	KEY (new),
	KEY (updated)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_products_also_buy;
CREATE TABLE xlite_products_also_buy (
	product_id int(11) NOT NULL default '0',
	product_id_also_buy int(11) NOT NULL default '0',
	counter int(11) NOT NULL default '0',
	PRIMARY KEY (product_id, product_id_also_buy),
	KEY (counter)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_customers_notifications;
CREATE TABLE xlite_customers_notifications (
	notify_id int(11) NOT NULL auto_increment,
	type enum('product','price') default NULL,
	status enum('Q','U','S','D') NOT NULL default 'Q',
	notify_key TEXT NOT NULL,
	date int(11) default '0',
	profile_id int(11) NOT NULL default '0',
	email varchar(128) NOT NULL default '',
	person_info varchar(255) NOT NULL default '',
	product_id int(11) NOT NULL default '0',
	product_options TEXT NOT NULL,
	quantity int(11) NOT NULL default '0',
	price double NOT NULL default '0',
	PRIMARY KEY (notify_id),
	KEY type (type),
	KEY date (date),
	KEY profile_id (profile_id),
	KEY email (email),
	KEY product_id (product_id)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (7,'admin_products_also_buy_enabled','ProductAdviser','checkbox',60,'Y');
INSERT INTO `xlite_config` VALUES (8,'admin_related_products_enabled','ProductAdviser','checkbox',0,'Y');
INSERT INTO `xlite_config` VALUES (36,'category_new_arrivals','ProductAdviser','checkbox',47,'N');
INSERT INTO `xlite_config` VALUES (63,'customer_notifications_enabled','ProductAdviser','checkbox',1000,'Y');
INSERT INTO `xlite_config` VALUES (64,'customer_notifications_mode','ProductAdviser','select',80,'3');
INSERT INTO `xlite_config` VALUES (119,'filters_preferences','ProductAdviser','serialized',1000,'');
INSERT INTO `xlite_config` VALUES (172,'new_arrivals_type','ProductAdviser','select',49,'sidebar');
INSERT INTO `xlite_config` VALUES (173,'number_new_arrivals','ProductAdviser','text',40,'5');
INSERT INTO `xlite_config` VALUES (174,'number_notifications','ProductAdviser','text',85,'20');
INSERT INTO `xlite_config` VALUES (176,'number_recently_viewed','ProductAdviser','text',20,'5');
INSERT INTO `xlite_config` VALUES (186,'pab_columns','ProductAdviser','select',76,'3');
INSERT INTO `xlite_config` VALUES (187,'pab_show_buynow','ProductAdviser','checkbox',79,'Y');
INSERT INTO `xlite_config` VALUES (188,'pab_show_descr','ProductAdviser','checkbox',77,'Y');
INSERT INTO `xlite_config` VALUES (189,'pab_show_price','ProductAdviser','checkbox',78,'Y');
INSERT INTO `xlite_config` VALUES (190,'pab_template','ProductAdviser','select',75,'list');
INSERT INTO `xlite_config` VALUES (202,'period_new_arrivals','ProductAdviser','text',45,'240');
INSERT INTO `xlite_config` VALUES (209,'products_also_buy_enabled','ProductAdviser','checkbox',65,'Y');
INSERT INTO `xlite_config` VALUES (219,'related_products_enabled','ProductAdviser','checkbox',5,'Y');
INSERT INTO `xlite_config` VALUES (222,'rp_bulk_shopping','ProductAdviser','checkbox',15,'N');
INSERT INTO `xlite_config` VALUES (223,'rp_columns','ProductAdviser','select',11,'3');
INSERT INTO `xlite_config` VALUES (224,'rp_show_buynow','ProductAdviser','checkbox',14,'Y');
INSERT INTO `xlite_config` VALUES (225,'rp_show_descr','ProductAdviser','checkbox',12,'Y');
INSERT INTO `xlite_config` VALUES (226,'rp_show_price','ProductAdviser','checkbox',13,'Y');
INSERT INTO `xlite_config` VALUES (227,'rp_template','ProductAdviser','select',10,'list');

INSERT INTO `xlite_config_translations` VALUES (6,'en',7,'Enable the functionality','');
INSERT INTO `xlite_config_translations` VALUES (7,'en',8,'Enable the functionality','');
INSERT INTO `xlite_config_translations` VALUES (30,'en',36,'Show category-specific new arrivals','');
INSERT INTO `xlite_config_translations` VALUES (50,'en',63,'Enable the \"Customer Notifications\" functionality','');
INSERT INTO `xlite_config_translations` VALUES (51,'en',64,'Enable Customer Notifications','');
INSERT INTO `xlite_config_translations` VALUES (128,'en',172,'New arrivals displayed in','');
INSERT INTO `xlite_config_translations` VALUES (129,'en',173,'Number of products in the \"New Arrivals\" list','');
INSERT INTO `xlite_config_translations` VALUES (130,'en',174,'Number of notification requests per page (in Admin zone)','');
INSERT INTO `xlite_config_translations` VALUES (132,'en',176,'Number of products in the \"Recently viewed\" box','');
INSERT INTO `xlite_config_translations` VALUES (142,'en',186,'Number of columns','');
INSERT INTO `xlite_config_translations` VALUES (143,'en',187,'Show \'Add to Cart\' button','');
INSERT INTO `xlite_config_translations` VALUES (144,'en',188,'Show product description','');
INSERT INTO `xlite_config_translations` VALUES (145,'en',189,'Show product price','');
INSERT INTO `xlite_config_translations` VALUES (146,'en',190,'Look&feel of a product list','');
INSERT INTO `xlite_config_translations` VALUES (149,'en',202,'Period during which a product is to be marked as \"New\" (hours)','');
INSERT INTO `xlite_config_translations` VALUES (155,'en',209,'Display recommended products in the Customer Zone','');
INSERT INTO `xlite_config_translations` VALUES (164,'en',219,'Display related products in the Customer Zone','');
INSERT INTO `xlite_config_translations` VALUES (166,'en',222,'Enable multiple additions at once','');
INSERT INTO `xlite_config_translations` VALUES (167,'en',223,'Number of columns','');
INSERT INTO `xlite_config_translations` VALUES (168,'en',224,'Show \'Add to Cart\' button','');
INSERT INTO `xlite_config_translations` VALUES (169,'en',225,'Show product description','');
INSERT INTO `xlite_config_translations` VALUES (170,'en',226,'Show product price','');
INSERT INTO `xlite_config_translations` VALUES (171,'en',227,'Look&feel of a product list','');

