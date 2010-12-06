INSERT INTO xlite_payment_methods (method_id, service_name, class) VALUES (30, 'QuantumGateway','Module\\CDev\\Quantum\\Model\\Payment\\Processor\\Quantum');

INSERT INTO xlite_payment_method_translations (id, code, name) VALUES (30, 'en', 'QuantumGateway');

INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'login', '');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'prefix', 'xlite');
INSERT INTO xlite_payment_method_settings (method_id, name, value) VALUES (30, 'hash', '');

