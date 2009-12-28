ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_payment_methods SET payment_method = 'netbilling_cc', name = 'Netbilling', details = 'Visa, Mastercard, American Express', orderby = '15', class = 'netbilling_cc', enabled = 0, params = 'O:8:"stdClass":3:{s:7:"account";s:0:"";s:8:"site_tag";s:0:"";s:3:"key";s:0:"0";}';
