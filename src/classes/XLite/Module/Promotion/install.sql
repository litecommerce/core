DROP TABLE IF EXISTS xlite_special_offers;
CREATE TABLE xlite_special_offers (
    offer_id int not null auto_increment primary key, 
    date int not null, 
	start_date int not null,
	end_date int not null,
	status varchar(10) default 'Trash',
    title varchar(80) not null, 
    product_id int, 
	category_id int,
	allProducts int(11) not null default 0,
    conditionType varchar(15) not null, 
    bonusType varchar(15) not null, 
    amount decimal(12,2), 
    bonusAmount decimal(12,2), 
    bonusAmountType char(1) not null default '%', 
    bonusAllProducts int, 
    bonusCategory_id int, 
    enabled int not null default 1, 
    order_id int default 0 not null,
    bonusAllCountries int default 1,
	bonusCountries text
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_special_offer_products;
CREATE TABLE xlite_special_offer_products (
    offer_id int not null,
    product_id int not null,
    type char(1) not null, 
	primary key (offer_id,product_id,type)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_special_offer_bonusprices;
CREATE TABLE xlite_special_offer_bonusprices (
    offer_id int not null,
    product_id int not null,
    category_id int not null,
    bonusType varchar(1) not null default '\$',
    price decimal(12,2) not null, 
	primary key (offer_id,product_id,category_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_special_offer_memberships;
CREATE TABLE xlite_special_offer_memberships (
	offer_id int not null,
	membership varchar(50) not null,
	primary key (offer_id,membership)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_discount_coupons;
CREATE TABLE xlite_discount_coupons (
    coupon_id int not null auto_increment primary key, 
    order_id int not null default 0, 
    coupon varchar(32) not null, 
    status varchar(1) not null, 
    times int not null, 
    discount decimal(12,2) not null, 
    type varchar(8) not null, 
    expire int not null, 
    applyTo varchar(8) not null, 
    minamount decimal(12,2) not null, 
    product_id int not null, 
    category_id int not null,
	timesUsed int default 0 not null,
	parent_id int NOT NULL DEFAULT 0,
	new_link_mode int(1) NOT NULL DEFAULT 0
) TYPE=MyISAM;

ALTER TABLE xlite_profiles ADD bonusPoints int;
ALTER TABLE xlite_orders ADD payedByPoints decimal(12,2) default 0;
ALTER TABLE xlite_orders ADD discountCoupon varchar(12);
ALTER TABLE xlite_orders ADD discount decimal(12,2) default 0;
ALTER TABLE xlite_order_items ADD bonusItem int default 0 not null;
ALTER TABLE xlite_order_items DROP primary key;
ALTER TABLE xlite_order_items ADD primary key(order_id,item_id,bonusItem);

INSERT INTO `xlite_config` VALUES (11,'allowDC','Promotion','checkbox',50,'Y');
INSERT INTO `xlite_config` VALUES (20,'auto_expand_coupon_orders','Promotion','checkbox',80,'Y');
INSERT INTO `xlite_config` VALUES (25,'bonusPointsCost','Promotion','text',20,'1.0');
INSERT INTO `xlite_config` VALUES (58,'coupons_per_page','Promotion','text',80,'20');
INSERT INTO `xlite_config` VALUES (96,'earnBonusPointsRate','Promotion','text',30,'0');
INSERT INTO `xlite_config` VALUES (177,'offerScheme','Promotion','checkbox',70,'0');
INSERT INTO `xlite_config` VALUES (178,'only_positive_price','Promotion','checkbox',60,'Y');
INSERT INTO `xlite_config` VALUES (235,'showBonusList','Promotion','checkbox',40,'Y');

INSERT INTO `xlite_config_translations` VALUES (10,'en',11,'Allow discount coupons in customer zone','');
INSERT INTO `xlite_config_translations` VALUES (19,'en',20,'Expand the list of orders, associated with discount coupons','');
INSERT INTO `xlite_config_translations` VALUES (22,'en',25,'Bonus point purchasing capacity ($ per bonus)','');
INSERT INTO `xlite_config_translations` VALUES (48,'en',58,'Number of discount coupons to be displayed per page in the admin zone','');
INSERT INTO `xlite_config_translations` VALUES (72,'en',96,'Bonus points awarded on each $ of a purchase subtotal (bonus rate per $; number of bonus points is rounded down to the next lower integer)','');
INSERT INTO `xlite_config_translations` VALUES (133,'en',177,'Offer application scheme to be used','');
INSERT INTO `xlite_config_translations` VALUES (134,'en',178,'The discounted price cannot be less than zero','');
INSERT INTO `xlite_config_translations` VALUES (176,'en',235,'Show applicable special offers during checkout','');

INSERT INTO xlite_payment_methods (payment_method,name,details,orderby,class,enabled,params) values ('bonus_points','Pay by bonus points','','70','bonus_points',1,'');

