DROP TABLE IF EXISTS xlite_wholesale_pricing;
CREATE TABLE xlite_wholesale_pricing (
  price_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  amount int(11) NOT NULL default '0',
  price decimal(12,2) NOT NULL default '0.00',
  membership varchar(255) NOT NULL default '',
  PRIMARY KEY  (price_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_product_access;
CREATE TABLE xlite_product_access (
  product_id int(11) NOT NULL default '0',
  show_group text NOT NULL,
  show_price_group text NOT NULL,
  sell_group text NOT NULL,
  PRIMARY KEY  (product_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_purchase_limit;
CREATE TABLE xlite_purchase_limit (
  product_id int(11) NOT NULL auto_increment,
  min varchar(32) NOT NULL default '',
  max varchar(32) NOT NULL default '',
  PRIMARY KEY  (product_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_global_discount;
CREATE TABLE xlite_global_discount (
  discount_id int(11) NOT NULL auto_increment,
  subtotal decimal(12,2) NOT NULL default '0.00',
  discount decimal(12,2) NOT NULL default '0.00',
  discount_type char(1) NOT NULL default '',
  membership varchar(255) NOT NULL default '',
  PRIMARY KEY  (discount_id)
) TYPE=MyISAM;

ALTER TABLE xlite_profiles ADD membership_exp_date int(11) NOT NULL default '0';
ALTER TABLE xlite_profiles ADD tax_id varchar(32) NOT NULL default '';
ALTER TABLE xlite_profiles ADD vat_number varchar(32) NOT NULL default '';
ALTER TABLE xlite_profiles ADD gst_number varchar(32) NOT NULL default '';
ALTER TABLE xlite_profiles ADD pst_number varchar(32) NOT NULL default '';
ALTER TABLE xlite_profiles ADD membership_history TEXT NOT NULL;

ALTER TABLE xlite_orders ADD global_discount decimal(12,2) NOT NULL default '0.00';

ALTER TABLE xlite_products ADD selling_membership varchar(64) NOT NULL default '';
ALTER TABLE xlite_products ADD validaty_period varchar(32) NOT NULL default '';

ALTER TABLE xlite_order_items ADD wholesale_price decimal(12,2) NOT NULL default '-1.00'  AFTER price;

INSERT INTO `xlite_config` VALUES (26,'bulk_categories','WholesaleTrading','text',30,'');
INSERT INTO `xlite_config` VALUES (42,'clone_wholesale_pricing','WholesaleTrading','checkbox',110,'Y');
INSERT INTO `xlite_config` VALUES (43,'clone_wholesale_productaccess','WholesaleTrading','checkbox',120,'Y');
INSERT INTO `xlite_config` VALUES (44,'clone_wholesale_purchaselimit','WholesaleTrading','checkbox',100,'Y');
INSERT INTO `xlite_config` VALUES (87,'direct_addition','WholesaleTrading','checkbox',90,'');
INSERT INTO `xlite_config` VALUES (185,'override_membership','WholesaleTrading','checkbox',80,'');
INSERT INTO `xlite_config` VALUES (206,'price_denied_message','WholesaleTrading','text',20,'N/A');
INSERT INTO `xlite_config` VALUES (277,'WholesalerFieldsGst','WholesaleTrading','checkbox',60,'');
INSERT INTO `xlite_config` VALUES (278,'WholesalerFieldsPst','WholesaleTrading','checkbox',70,'');
INSERT INTO `xlite_config` VALUES (279,'WholesalerFieldsTaxId','WholesaleTrading','checkbox',40,'');
INSERT INTO `xlite_config` VALUES (280,'WholesalerFieldsVat','WholesaleTrading','checkbox',50,'');

INSERT INTO `xlite_config_translations` VALUES (23,'en',26,'Categories for bulk shopping','');
INSERT INTO `xlite_config_translations` VALUES (36,'en',42,'Clone wholesale pricing settings when a product is cloned','');
INSERT INTO `xlite_config_translations` VALUES (37,'en',43,'Clone product access settings when a product is cloned','');
INSERT INTO `xlite_config_translations` VALUES (38,'en',44,'Clone purchase limit settings when a product is cloned','');
INSERT INTO `xlite_config_translations` VALUES (65,'en',87,'Enable direct addition to the cart for products with disabled catalog visibility','');
INSERT INTO `xlite_config_translations` VALUES (141,'en',185,'Membership granted through product purchase overrides current membership','');
INSERT INTO `xlite_config_translations` VALUES (152,'en',206,'Message when price is denied','');
INSERT INTO `xlite_config_translations` VALUES (206,'en',277,'Add GST Registration number field to the registration form','');
INSERT INTO `xlite_config_translations` VALUES (207,'en',278,'Add PST Registration number field to the registration form','');
INSERT INTO `xlite_config_translations` VALUES (208,'en',279,'Add Sales Permit/Tax ID field to the registration form','');
INSERT INTO `xlite_config_translations` VALUES (209,'en',280,'Add VAT Registration number field to the registration form','');

