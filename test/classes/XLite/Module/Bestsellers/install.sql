INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'number_of_bestsellers', 'Number of products in the bestsellers list', '5', 'Bestsellers', '115', 'text');
UPDATE xlite_config SET comment='Number of products in the bestsellers list' WHERE category='Bestsellers' AND name='number_of_bestsellers';
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'bestsellers_thumbnails', 'Show thumbnails in the list', 'Y', 'Bestsellers', '115', 'checkbox');
UPDATE xlite_config SET comment='Show thumbnails in the list' WHERE category='Bestsellers' AND name='bestsellers_thumbnails';
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'bestsellers_menu', 'Display the list of bestsellers in', '1', 'Bestsellers', '115', 'select');
UPDATE xlite_config SET comment='Display the list of bestsellers in', type='select' WHERE category='Bestsellers' AND name='bestsellers_menu';
UPDATE xlite_config SET value='0' WHERE category='Bestsellers' AND name='bestsellers_menu' AND value='N';

CREATE INDEX xlite_product_links_category ON xlite_product_links (category_id);
