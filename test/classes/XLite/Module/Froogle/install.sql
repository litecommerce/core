INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'froogle_host', 'Froogle host', 'hedwig.google.com', 'Froogle', '5', 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'froogle_username', 'Froogle username', '', 'Froogle', '10', 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'froogle_password', 'Froogle password', '', 'Froogle', '15', 'text');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'froogle_file_name', 'File name (if this option is empty, \'username.txt\' will be used)', '', 'Froogle', '20', 'text');

INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'direct_product_url', 'Override "Allow direct URL access to products in the categories<br>which are not available"','0','Froogle',25, 'select');

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('export_label', 'The "product_type" field includes', 'category', 'Froogle', '30', 'select');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('export_custom_label', 'Custom "product_type" value', '', 'Froogle', '35', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('froogle_brand', 'Brand', '', 'Froogle', '40', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('froogle_expiration', 'Expiration date', '14', 'Froogle', '50', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('froogle_id_format', 'Id format', 'product_%pid', 'Froogle', '60', 'text');
