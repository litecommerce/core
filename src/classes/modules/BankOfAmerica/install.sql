ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_payment_methods SET payment_method = 'bank_of_america_cc', name = 'Credit Card', details = 'Visa, Mastercard, American Express', orderby = '30', class = 'bank_of_america_cc', enabled = 0, params = '';
