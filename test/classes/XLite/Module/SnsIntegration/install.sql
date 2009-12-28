ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ("collectorURL", "SNS collector HTTP/HTTPS directory URL (do not include trailing '/')", "http://", "SnsIntegration", "10", "text");
UPDATE xlite_config SET comment='SNS collector HTTP/HTTPS directory URL (do not include trailing \'/\')' WHERE name='collectorURL' AND category='SnsIntegration';
DELETE FROM xlite_config WHERE name='collectorHTTPSURL' AND category='SnsIntegration';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ("collectorLanguage", "SNS collector platform", "php", "SnsIntegration", "20", "select");
UPDATE xlite_config SET comment='SNS collector platform' WHERE name='collectorLanguage' AND category='SnsIntegration';
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ("showOperatorButton", "Show operator button", "N", "SnsIntegration", "30", "checkbox");
INSERT INTO xlite_config (name, comment, value, category, orderby, type) values ("passphrase", "Passphrase", "", "SnsIntegration", "40", "text");

ALTER TABLE xlite_orders ADD snsClientId varchar(20);
UPDATE xlite_config SET comment='SNS collector HTTP/HTTPS directory URL (do not include trailing \'/\')' WHERE name='collectorURL' AND category='SnsIntegration';
