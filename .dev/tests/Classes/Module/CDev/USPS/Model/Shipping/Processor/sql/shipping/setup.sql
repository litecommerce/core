UPDATE xlite_shipping_methods SET enabled = 0 WHERE processor != 'usps';
UPDATE xlite_shipping_methods SET enabled = 1 WHERE processor = 'usps';

