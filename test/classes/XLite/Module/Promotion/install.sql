ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
CREATE TABLE xlite_special_offers (
    offer_id int not null auto_increment primary key, 
    date int not null, 
	start_date int not null,
	end_date int not null,
	status varchar(10) default 'Trash',
    title varchar(80) not null, 
    product_id int, 
    category_id int, 
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
    bonusCountries text);
CREATE TABLE xlite_special_offer_products (
    offer_id int not null,
    product_id int not null,
    type char(1) not null, 
    primary key (offer_id,product_id,type));
CREATE TABLE xlite_special_offer_bonusprices (
    offer_id int not null,
    product_id int not null,
    category_id int not null,
    bonusType varchar(1) not null default '\$',
    price decimal(12,2) not null, 
    primary key (offer_id,product_id,category_id));
CREATE TABLE xlite_special_offer_memberships (
	offer_id int not null,
	membership varchar(50) not null,
	primary key (offer_id,membership));
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
    timesUsed int default 0 not null);
ALTER TABLE xlite_discount_coupons ADD parent_id int NOT NULL DEFAULT 0;
ALTER TABLE xlite_discount_coupons ADD new_link_mode int(1) NOT NULL DEFAULT 0;
INSERT INTO xlite_payment_methods (payment_method,name,details,orderby,class,enabled,params) values ('bonus_points','Pay by bonus points','','70','bonus_points',1,'');
ALTER TABLE xlite_special_offers ADD start_date int not null;
ALTER TABLE xlite_special_offers ADD end_date int not null;
ALTER TABLE xlite_special_offers ADD status varchar(10) default '';
ALTER TABLE xlite_special_offers ADD COLUMN allProducts int(11) AFTER category_id;
ALTER TABLE xlite_profiles ADD bonusPoints int;
ALTER TABLE xlite_orders ADD payedByPoints decimal(12,2) default 0;
ALTER TABLE xlite_orders ADD discountCoupon varchar(12);
ALTER TABLE xlite_orders ADD discount decimal(12,2) default 0;
ALTER TABLE xlite_order_items ADD bonusItem int default 0 not null;
ALTER TABLE xlite_order_items DROP primary key;
ALTER TABLE xlite_order_items ADD primary key(order_id,item_id,bonusItem);
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('bonusPointsCost', 'Bonus point purchasing capacity ($ per bonus)', '1.0', 'Promotion', '20', 'text');
UPDATE xlite_config SET comment='Bonus point purchasing capacity ($ per bonus)' WHERE category='Promotion' AND name='bonusPointsCost';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('earnBonusPointsRate', 'Bonus points awarded on each $ of a purchase subtotal (bonus rate per $; number of bonus points is rounded down to the next lower integer)', '0', 'Promotion', '30', 'text');
UPDATE xlite_config SET comment='Bonus points awarded on each $ of a purchase subtotal (bonus rate per $; number of bonus points is rounded down to the next lower integer)' WHERE category='Promotion' AND name='earnBonusPointsRate';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('showBonusList', 'Show applicable special offers during checkout', 'Y', 'Promotion', '40', 'checkbox');
UPDATE xlite_config SET comment='Show applicable special offers during checkout' WHERE category='Promotion' AND name='showBonusList';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('allowDC', 'Allow discount coupons in customer zone', 'Y', 'Promotion', '50', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('only_positive_price', 'The discounted price cannot be less than zero', 'Y', 'Promotion', '60', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('offerScheme', 'Offer application scheme to be used', '0', 'Promotion', '70', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('coupons_per_page', 'Number of discount coupons to be displayed per page in the admin zone', '20', 'Promotion', '80', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ('auto_expand_coupon_orders', 'Expand the list of orders, associated with discount coupons', 'Y', 'Promotion', '80', 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ('discounts_after_taxes','Discounts charged after taxes application','N','Taxes',210,'checkbox');

UPDATE xlite_config SET type='checkbox' WHERE name='offerScheme' AND category='Promotion';
