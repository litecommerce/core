DELETE FROM xlite_config where name="options_expansion_limit";
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'price_denied_message', 'Message when price is denied', 'N/A', 'WholesaleTrading', 20, 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'bulk_categories', 'Categories for bulk shopping', '', 'WholesaleTrading', 30, 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'WholesalerFieldsTaxId', 'Add Sales Permit/Tax ID field to the registration form', '', 'WholesaleTrading', 40, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'WholesalerFieldsVat', 'Add VAT Registration number field to the registration form', '', 'WholesaleTrading', 50, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'WholesalerFieldsGst', 'Add GST Registration number field to the registration form', '', 'WholesaleTrading', 60, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'WholesalerFieldsPst', 'Add PST Registration number field to the registration form', '', 'WholesaleTrading', 70, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'override_membership', 'Membership granted through product purchase overrides current membership', '', 'WholesaleTrading', 80, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'direct_addition', 'Enable direct addition to the cart for products with disabled catalog visibility', '', 'WholesaleTrading', 90, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'clone_wholesale_purchaselimit','Clone purchase limit settings when a product is cloned','Y','WholesaleTrading',100,'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'clone_wholesale_pricing','Clone wholesale pricing settings when a product is cloned','Y','WholesaleTrading',110,'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'clone_wholesale_productaccess','Clone product access settings when a product is cloned','Y','WholesaleTrading',120,'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ('discounts_after_taxes','Discounts charged after taxes application','N','Taxes',210,'checkbox');

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
