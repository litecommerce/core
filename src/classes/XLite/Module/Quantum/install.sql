INSERT INTO xlite_payment_methods (method_id, service_name, class) VALUES (30, 'QuntumGateway','Module\\Quantum\\Model\\Payment\\Processor\\Quantum');

INSERT INTO xlite_payment_method_translations (id, code, name) VALUES (30, 'en', 'QuntumGateway');

INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'login', '');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'prefix', 'xlite');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'hash', '');

