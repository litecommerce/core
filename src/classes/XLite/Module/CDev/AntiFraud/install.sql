ALTER TABLE xlite_countries ADD risk_country int(11) NOT NULL default 0;


INSERT INTO `xlite_config` VALUES (13,'always_keep_info','AntiFraud','checkbox',70,'Y');
INSERT INTO `xlite_config` VALUES (14,'antifraud_force_queued','AntiFraud','',60,'Y');
INSERT INTO `xlite_config` VALUES (15,'antifraud_license','AntiFraud','text',20,'');
INSERT INTO `xlite_config` VALUES (16,'antifraud_order_total','AntiFraud','text',50,'500');
INSERT INTO `xlite_config` VALUES (17,'antifraud_risk_factor','AntiFraud','text',40,'5');
INSERT INTO `xlite_config` VALUES (18,'antifraud_safe_distance','AntiFraud','text',30,'500');
INSERT INTO `xlite_config` VALUES (19,'antifraud_url','AntiFraud','text',10,'https://secure.qualiteam.biz:443');
INSERT INTO `xlite_config` VALUES (69,'declined_orders_multiplier','AntiFraud','',180,'1.5');
INSERT INTO `xlite_config` VALUES (95,'duplicate_ip_multiplier','AntiFraud','',170,'2');
INSERT INTO `xlite_config` VALUES (183,'order_total_multiplier','AntiFraud','',160,'2');
INSERT INTO `xlite_config` VALUES (208,'processed_orders_multiplier','AntiFraud','',190,'2');
INSERT INTO `xlite_config` VALUES (221,'risk_country_multiplier','AntiFraud','',200,'7');

INSERT INTO `xlite_config_translations` VALUES (12,'en',13,'Always keep AntiFraud information:','');
INSERT INTO `xlite_config_translations` VALUES (13,'en',14,'Suspicious order handling:','');
INSERT INTO `xlite_config_translations` VALUES (14,'en',15,'AntiFraud Service License key:','');
INSERT INTO `xlite_config_translations` VALUES (15,'en',16,'Order total threshold:','');
INSERT INTO `xlite_config_translations` VALUES (16,'en',17,'Risk Factor threshold:','');
INSERT INTO `xlite_config_translations` VALUES (17,'en',18,'AntiFraud Safe Distance (km):','');
INSERT INTO `xlite_config_translations` VALUES (18,'en',19,'AntiFraud Service URL:','');
INSERT INTO `xlite_config_translations` VALUES (56,'en',69,'Risk factor multiplier for customers who have declined orders in their order histories:','');
INSERT INTO `xlite_config_translations` VALUES (71,'en',95,'Risk factor multiplier for IP addresses used by multiple customers:','');
INSERT INTO `xlite_config_translations` VALUES (139,'en',183,'Risk factor multiplier for orders exceeding order total threshold:','');
INSERT INTO `xlite_config_translations` VALUES (154,'en',208,'Risk factor divider for customers with reliable order histories:','');
INSERT INTO `xlite_config_translations` VALUES (165,'en',221,'Risk factor extra points for customers coming from fraud-risk countries:','');

