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

-- ALTER TABLE xlite_orders ADD xpc_txnid varchar(32) NOT NULL default '';
-- ALTER TABLE xlite_payment_methods ADD xpc_confid int(6) NOT NULL default 0;
-- ALTER TABLE xlite_payment_methods ADD KEY xpc_confid(xpc_confid);

INSERT INTO `xlite_config` VALUES (285,'xpc_allowed_ip_addresses','XPaymentsConnector','text',70,'');
INSERT INTO `xlite_config` VALUES (286,'xpc_private_key','XPaymentsConnector','textarea',50,'');
INSERT INTO `xlite_config` VALUES (287,'xpc_private_key_password','XPaymentsConnector','text',60,'');
INSERT INTO `xlite_config` VALUES (288,'xpc_public_key','XPaymentsConnector','textarea',40,'');
INSERT INTO `xlite_config` VALUES (289,'xpc_sep1','XPaymentsConnector','separator',0,'');
INSERT INTO `xlite_config` VALUES (290,'xpc_sep2','XPaymentsConnector','separator',75,'');
INSERT INTO `xlite_config` VALUES (291,'xpc_shopping_cart_id','XPaymentsConnector','text',10,'');
INSERT INTO `xlite_config` VALUES (292,'xpc_status_auth','XPaymentsConnector','',90,'A');
INSERT INTO `xlite_config` VALUES (293,'xpc_status_charged','XPaymentsConnector','',100,'P');
INSERT INTO `xlite_config` VALUES (294,'xpc_status_declined','XPaymentsConnector','',110,'F');
INSERT INTO `xlite_config` VALUES (295,'xpc_status_new','XPaymentsConnector','',80,'I');
INSERT INTO `xlite_config` VALUES (296,'xpc_status_part_refunded','XPaymentsConnector','',120,'');
INSERT INTO `xlite_config` VALUES (297,'xpc_status_refunded','XPaymentsConnector','',120,'');
INSERT INTO `xlite_config` VALUES (298,'xpc_xpayments_url','XPaymentsConnector','text',20,'');

INSERT INTO `xlite_config_translations` VALUES (210,'en',285,'IP addresses of X-Payments callbacks','');
INSERT INTO `xlite_config_translations` VALUES (211,'en',286,'Decryption key','');
INSERT INTO `xlite_config_translations` VALUES (212,'en',287,'Decryption key password','');
INSERT INTO `xlite_config_translations` VALUES (213,'en',288,'Encryption key','');
INSERT INTO `xlite_config_translations` VALUES (214,'en',289,'X-Payments connection options','');
INSERT INTO `xlite_config_translations` VALUES (215,'en',290,'Payments <-> Order statuses compatibility list','');
INSERT INTO `xlite_config_translations` VALUES (216,'en',291,'Shopping cart ID','');
INSERT INTO `xlite_config_translations` VALUES (217,'en',292,'Set order status if payment is in status \'Authorized\' to','');
INSERT INTO `xlite_config_translations` VALUES (218,'en',293,'Set order status if payment is in status \'Charged\' to','');
INSERT INTO `xlite_config_translations` VALUES (219,'en',294,'Set order status if payment is in status \'Declined\' to','');
INSERT INTO `xlite_config_translations` VALUES (220,'en',295,'Set order status if payment is in status \'New\' to','');
INSERT INTO `xlite_config_translations` VALUES (221,'en',296,'Set order status if payment is in status \'Partially Refunded\' to','');
INSERT INTO `xlite_config_translations` VALUES (222,'en',297,'Set order status if payment is in status \'Refunded\' to','');
INSERT INTO `xlite_config_translations` VALUES (223,'en',298,'X-Payments URL','');

