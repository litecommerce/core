--
-- module: GoogleCheckout
--
-- @version $Id: install.sql,v 1.2 2007/12/21 12:46:10 alien Exp $
--

ALTER TABLE xlite_orders ADD COLUMN google_id varchar(16) NOT NULL default '';
ALTER TABLE xlite_orders ADD COLUMN google_total decimal(12,2) NOT NULL default '0.00';
ALTER TABLE xlite_orders ADD COLUMN google_details text NOT NULL default '';
ALTER TABLE xlite_orders ADD COLUMN google_status varchar(5) NOT NULL default '';
ALTER TABLE xlite_orders ADD COLUMN google_carrier varchar(64) NOT NULL default '';

ALTER TABLE xlite_products ADD COLUMN google_disabled int(1) NOT NULL default 0;

--INSERT INTO xlite_payment_methods (payment_method,name,details,class,params,orderby,enabled) VALUES ('google_checkout','Google Checkout','Visa, Mastercard, American Express','google_checkout','a:20:{s:11:"merchant_id";s:0:"";s:12:"merchant_key";s:0:"";s:8:"testmode";s:1:"Y";s:15:"secure_testmode";s:2:"on";s:8:"currency";s:3:"USD";s:12:"order_prefix";s:3:"LC_";s:17:"status_chargeable";s:1:"Q";s:14:"status_charged";s:1:"P";s:13:"status_failed";s:1:"F";s:9:"check_avs";a:3:{i:0;s:1:"Y";i:1;s:1:"P";i:2;s:1:"A";}s:9:"check_cvn";a:1:{i:0;s:1:"M";}s:10:"check_prot";s:2:"on";s:13:"merchant_calc";s:2:"on";s:22:"disable_customer_notif";s:1:"1";s:21:"default_shipping_cost";s:1:"0";s:20:"display_product_note";s:1:"1";s:16:"remove_discounts";s:1:"1";s:20:"substatus_chargeable";s:1:"Q";s:17:"substatus_charged";s:1:"P";s:16:"substatus_failed";s:1:"F";}', 10,0);


