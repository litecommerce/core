INSERT INTO xlite_payment_methods (method_id, service_name, class) VALUES (10, 'AuthorizeNet SIM','Module\\CDev\\AuthorizeNet\\Model\\Payment\\Processor\\AuthorizeNetSIM');

INSERT INTO xlite_payment_method_translations (id, code, name) VALUES (10, 'en', 'Authorize.Net SIM');

INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (10, 'login', '');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (10, 'key', '');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (10, 'test', '1');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (10, 'prefix', 'xlite');
