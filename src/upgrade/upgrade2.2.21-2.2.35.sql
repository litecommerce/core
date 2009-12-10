UPDATE xlite_card_types SET card_type='Maestro/Switch' WHERE code='SW';

REPLACE INTO xlite_config VALUES ('add_on_mode_page','Checkout desk operation mode main page','cart.php?target=cart','General',125,'select');

REPLACE INTO xlite_config VALUES ('direct_product_url','Allow direct URL access to products from disabled categories','N','General',255,'checkbox');

UPDATE xlite_config SET value='2007' WHERE category='Company' AND name='start_year';

REPLACE INTO xlite_config VALUES ('show_cc_info','Include order details (credit card information) into admin order notification message','N','Email',40,'checkbox');


UPDATE xlite_countries SET eu_member='Y' WHERE code='BG';
UPDATE xlite_countries SET eu_member='Y' WHERE code='CY';
UPDATE xlite_countries SET eu_member='Y' WHERE code='EE';
UPDATE xlite_countries SET eu_member='Y' WHERE code='HU';
UPDATE xlite_countries SET eu_member='Y' WHERE code='LV';
UPDATE xlite_countries SET eu_member='Y' WHERE code='LT';
UPDATE xlite_countries SET eu_member='Y' WHERE code='MT';
UPDATE xlite_countries SET eu_member='Y' WHERE code='MC';
UPDATE xlite_countries SET eu_member='Y' WHERE code='PL';
UPDATE xlite_countries SET eu_member='Y' WHERE code='RO';
UPDATE xlite_countries SET eu_member='Y' WHERE code='SK';
UPDATE xlite_countries SET eu_member='Y' WHERE code='SI';


UPDATE xlite_categories SET membership='%' WHERE category_id='40';
UPDATE xlite_categories SET membership='%' WHERE category_id='41';
UPDATE xlite_categories SET membership='%' WHERE category_id='42';
UPDATE xlite_categories SET membership='%' WHERE category_id='43';

UPDATE xlite_categories SET membership='%' WHERE category_id='44';
UPDATE xlite_categories SET membership='%' WHERE category_id='45';
UPDATE xlite_categories SET membership='%' WHERE category_id='46';
UPDATE xlite_categories SET membership='%' WHERE category_id='47';

