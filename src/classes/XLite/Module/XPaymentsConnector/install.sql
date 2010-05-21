DROP TABLE IF EXISTS xlite_xpc_configurations;
CREATE TABLE xlite_xpc_configurations ( 
    confid int(6) NOT NULL default 0,
	name varchar(255) NOT NULL default '',
	module varchar(255) NOT NULL default '',
	auth_exp int(11) NOT NULL default 0,
	capture_min decimal(12,2) NOT NULL default '0.00',
	capture_max decimal(12,2) NOT NULL default '0.00',
	hash char(32) NOT NULL default '',
    is_auth char(1) NOT NULL default '',
    is_capture char(1) NOT NULL default '',
    is_void char(1) NOT NULL default '',
    is_refund char(1) NOT NULL default '',
    is_part_refund char(1) NOT NULL default '',
    is_accept char(1) NOT NULL default '',
    is_decline char(1) NOT NULL default '',
    is_get_info char(1) NOT NULL default '',
    PRIMARY KEY (confid)
) TYPE=MyISAM;

ALTER TABLE xlite_orders ADD xpc_txnid varchar(32) NOT NULL default '';
ALTER TABLE xlite_payment_methods ADD xpc_confid int(6) NOT NULL default 0;
ALTER TABLE xlite_payment_methods ADD KEY xpc_confid(xpc_confid);

INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_sep1','X-Payments connection options','','XPaymentsConnector',0,'separator');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_sep2','Payments <-> Order statuses compatibility list','','XPaymentsConnector',75,'separator');


INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_shopping_cart_id','Shopping cart ID','','XPaymentsConnector',10,'text');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_xpayments_url','X-Payments URL','','XPaymentsConnector',20,'text');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_public_key','Encryption key','','XPaymentsConnector',40,'textarea');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_private_key','Decryption key','','XPaymentsConnector',50,'textarea');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_private_key_password','Decryption key password','','XPaymentsConnector',60,'text');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_allowed_ip_addresses','IP addresses of X-Payments callbacks','','XPaymentsConnector',70,'text');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_new','Set order status if payment is in status \'New\' to','I','XPaymentsConnector',80,'selector');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_auth','Set order status if payment is in status \'Authorized\' to','A','XPaymentsConnector',90,'selector');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_charged','Set order status if payment is in status \'Charged\' to','P','XPaymentsConnector',100,'selector');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_declined','Set order status if payment is in status \'Declined\' to','F','XPaymentsConnector',110,'selector');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_refunded','Set order status if payment is in status \'Refunded\' to','','XPaymentsConnector',120,'selector');
INSERT INTO xlite_config (`name`, `comment`, `value`, `category`, `orderby`, `type`) VALUES ('xpc_status_part_refunded','Set order status if payment is in status \'Partially Refunded\' to','','XPaymentsConnector',120,'selector');
