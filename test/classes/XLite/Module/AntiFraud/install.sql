ALTER TABLE xlite_countries ADD risk_country int(11) NOT NULL default 0;

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('antifraud_url', 'AntiFraud Service URL:', 'https://secure.qualiteam.biz:443', 'AntiFraud',10,'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES
('antifraud_license', 'AntiFraud Service License key:', '', 'AntiFraud',20,'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES
('antifraud_safe_distance', 'AntiFraud Safe Distance (km):', '500', 'AntiFraud',30,'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES
('antifraud_risk_factor', 'Risk Factor threshold:', '5', 'AntiFraud',40,'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES
('antifraud_order_total', 'Order total threshold:', '500', 'AntiFraud',50,'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES
('antifraud_force_queued', 'Suspicious order handling:', 'Y', 'AntiFraud',60,'');

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('always_keep_info', 'Always keep AntiFraud information:', 'Y', 'AntiFraud',70,'checkbox');     

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('order_total_multiplier', 'Risk factor multiplier for orders exceeding order total threshold:', '2', 'AntiFraud',160,'');     
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('duplicate_ip_multiplier', 'Risk factor multiplier for IP addresses used by multiple customers:', '2', 'AntiFraud',170,'');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('declined_orders_multiplier', 'Risk factor multiplier for customers who have declined orders in their order histories:', '1.5', 'AntiFraud',180,'');      
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('processed_orders_multiplier', 'Risk factor divider for customers with reliable order histories:', '2', 'AntiFraud',190,''); 
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('risk_country_multiplier', 'Risk factor extra points for customers coming from fraud-risk countries:', '7', 'AntiFraud',200,'');      

UPDATE xlite_config SET orderby=160 WHERE name='order_total_multiplier' AND category='AntiFraud';
UPDATE xlite_config SET orderby=170 WHERE name='duplicate_ip_multiplier' AND category='AntiFraud';
UPDATE xlite_config SET orderby=180 WHERE name='declined_orders_multiplier' AND category='AntiFraud';
UPDATE xlite_config SET orderby=190 WHERE name='processed_orders_multiplier' AND category='AntiFraud';
UPDATE xlite_config SET orderby=200 WHERE name='risk_country_multiplier' AND category='AntiFraud';
