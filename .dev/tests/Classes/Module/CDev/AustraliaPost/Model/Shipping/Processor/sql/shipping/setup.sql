UPDATE xlite_shipping_methods SET enabled = 0 WHERE processor != 'aupost';
UPDATE xlite_shipping_methods SET enabled = 1 WHERE processor = 'aupost';

