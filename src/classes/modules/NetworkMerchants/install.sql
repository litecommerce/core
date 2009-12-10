ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_payment_methods SET payment_method = 'networkmerchants_cc', name = 'NetworkMerchants', details = 'Visa, Mastercard, American Express', orderby = '15', class = 'networkmerchants_cc', enabled = 0, params = '';
