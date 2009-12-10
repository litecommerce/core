ALTER TABLE xlite_card_types ADD INDEX card_type (card_type);
ALTER TABLE xlite_card_types ADD INDEX cvv2 (cvv2);
ALTER TABLE xlite_card_types ADD INDEX orderby (orderby);
ALTER TABLE xlite_card_types ADD INDEX enabled (enabled);

ALTER TABLE xlite_categories ADD meta_desc text NOT NULL default '';
ALTER TABLE xlite_categories ADD meta_title varchar(255) NOT NULL default '';
ALTER TABLE xlite_categories DROP INDEX category_id;  
ALTER TABLE xlite_categories ADD INDEX meta_tags (meta_tags);
ALTER TABLE xlite_categories ADD INDEX views_stats (views_stats);
ALTER TABLE xlite_categories ADD INDEX membership (membership);
ALTER TABLE xlite_categories ADD INDEX threshold_bestsellers (threshold_bestsellers);
ALTER TABLE xlite_categories ADD INDEX parent (parent);
ALTER TABLE xlite_categories ADD INDEX image_source (image_source);
ALTER TABLE xlite_categories ADD INDEX image_type (image_type);
ALTER TABLE xlite_categories ADD INDEX enabled (enabled);
ALTER TABLE xlite_categories ADD INDEX meta_title (meta_title);
ALTER TABLE xlite_categories ADD FULLTEXT meta_desc (meta_desc);
ALTER TABLE xlite_categories ADD FULLTEXT description (description);

ALTER TABLE xlite_config CHANGE type type ENUM('','text','textarea','checkbox','country','state','select','serialized','separator');
ALTER TABLE xlite_config ADD INDEX type (type);
ALTER TABLE xlite_config ADD FULLTEXT value (value);

ALTER TABLE xlite_countries ADD INDEX language (language);
ALTER TABLE xlite_countries ADD INDEX charset (charset);
ALTER TABLE xlite_countries ADD INDEX enabled (enabled);
ALTER TABLE xlite_countries ADD INDEX eu_member (eu_member);
ALTER TABLE xlite_countries ADD INDEX shipping_zone (shipping_zone);

ALTER TABLE xlite_log ADD INDEX priority (priority);
ALTER TABLE xlite_log ADD INDEX message (message);

ALTER TABLE xlite_modules CHANGE version version varchar(5) NOT NULL default '0';
ALTER TABLE xlite_modules ADD type INT(11) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE xlite_modules ADD INDEX module_id (module_id);
ALTER TABLE xlite_modules ADD INDEX description (description);
ALTER TABLE xlite_modules ADD INDEX enabled (enabled);
ALTER TABLE xlite_modules ADD INDEX dependencies (dependencies);
ALTER TABLE xlite_modules ADD INDEX version (version);
ALTER TABLE xlite_modules ADD INDEX type (type);

ALTER TABLE xlite_order_items ADD INDEX orderby (orderby);
ALTER TABLE xlite_order_items ADD INDEX product_id (product_id);
ALTER TABLE xlite_order_items ADD INDEX price (price);
ALTER TABLE xlite_order_items ADD INDEX amount (amount);

ALTER TABLE xlite_orders ADD INDEX profile_id (profile_id);
ALTER TABLE xlite_orders ADD INDEX orig_profile_id (orig_profile_id);
ALTER TABLE xlite_orders ADD INDEX total (total);
ALTER TABLE xlite_orders ADD INDEX subtotal (subtotal);
ALTER TABLE xlite_orders ADD INDEX shipping_cost (shipping_cost);
ALTER TABLE xlite_orders ADD INDEX tax (tax);
ALTER TABLE xlite_orders ADD INDEX tracking (tracking);
ALTER TABLE xlite_orders ADD INDEX status (status);
ALTER TABLE xlite_orders ADD INDEX payment_method (payment_method);
ALTER TABLE xlite_orders ADD FULLTEXT details (details);
ALTER TABLE xlite_orders ADD FULLTEXT notes (notes);
ALTER TABLE xlite_orders ADD INDEX shipping_id (shipping_id);
ALTER TABLE xlite_orders ADD FULLTEXT detail_labels (detail_labels);
ALTER TABLE xlite_orders ADD FULLTEXT taxes (taxes);

ALTER TABLE xlite_payment_methods ADD INDEX name (name);
ALTER TABLE xlite_payment_methods ADD INDEX details (details);
ALTER TABLE xlite_payment_methods ADD INDEX class (class);
ALTER TABLE xlite_payment_methods ADD FULLTEXT params (params);
ALTER TABLE xlite_payment_methods ADD INDEX enabled (enabled);

ALTER TABLE xlite_product_links ADD INDEX orderby (orderby);

ALTER TABLE xlite_products ADD meta_desc text NOT NULL default '';
ALTER TABLE xlite_products ADD meta_title varchar(255) NOT NULL default '';
ALTER TABLE xlite_products ADD INDEX name (name);
ALTER TABLE xlite_products ADD FULLTEXT description (description);
ALTER TABLE xlite_products ADD FULLTEXT brief_description (brief_description);
ALTER TABLE xlite_products ADD free_shipping int(11) NOT NULL default '0';
ALTER TABLE xlite_products ADD INDEX meta_tags (meta_tags);
ALTER TABLE xlite_products ADD INDEX price (price);
ALTER TABLE xlite_products ADD INDEX sku (sku);
ALTER TABLE xlite_products ADD INDEX thumbnail_source (thumbnail_source);
ALTER TABLE xlite_products ADD INDEX thumbnail_type (thumbnail_type);
ALTER TABLE xlite_products ADD INDEX image_source (image_source);
ALTER TABLE xlite_products ADD INDEX image_type (image_type);
ALTER TABLE xlite_products ADD INDEX enabled (enabled);
ALTER TABLE xlite_products ADD INDEX weight (weight);
ALTER TABLE xlite_products ADD INDEX tax_class (tax_class);
ALTER TABLE xlite_products ADD INDEX free_shipping (free_shipping);
ALTER TABLE xlite_products ADD FULLTEXT meta_desc (meta_desc);
ALTER TABLE xlite_products ADD INDEX meta_title (meta_title);

ALTER TABLE xlite_extra_fields ADD parent_field_id int(11) NOT NULL default '0';
ALTER TABLE xlite_extra_fields ADD categories TEXT NOT NULL;
ALTER TABLE xlite_extra_fields ADD INDEX name (name);
ALTER TABLE xlite_extra_fields ADD INDEX default_value (default_value);
ALTER TABLE xlite_extra_fields ADD INDEX enabled (enabled);
ALTER TABLE xlite_extra_fields ADD INDEX parent_field_id (parent_field_id);
ALTER TABLE xlite_extra_fields ADD FULLTEXT categories (categories);

ALTER TABLE xlite_profiles ADD sidebar_boxes TEXT NOT NULL;
ALTER TABLE xlite_profiles ADD billing_custom_state varchar(64) NOT NULL default '' AFTER billing_state;
ALTER TABLE xlite_profiles ADD shipping_custom_state varchar(64) NOT NULL default '' AFTER shipping_state;
ALTER TABLE xlite_profiles ADD INDEX password (password);
ALTER TABLE xlite_profiles ADD INDEX access_level (access_level);
ALTER TABLE xlite_profiles ADD INDEX billing_firstname (billing_firstname);
ALTER TABLE xlite_profiles ADD INDEX billing_lastname (billing_lastname);
ALTER TABLE xlite_profiles ADD INDEX billing_company (billing_company);
ALTER TABLE xlite_profiles ADD INDEX billing_address (billing_address);
ALTER TABLE xlite_profiles ADD INDEX billing_city (billing_city);
ALTER TABLE xlite_profiles ADD INDEX billing_state (billing_state);
ALTER TABLE xlite_profiles ADD INDEX billing_country (billing_country);
ALTER TABLE xlite_profiles ADD INDEX billing_zipcode (billing_zipcode);
ALTER TABLE xlite_profiles ADD INDEX shipping_firstname (shipping_firstname);
ALTER TABLE xlite_profiles ADD INDEX shipping_lastname (shipping_lastname);
ALTER TABLE xlite_profiles ADD INDEX shipping_company (shipping_company);
ALTER TABLE xlite_profiles ADD INDEX shipping_address (shipping_address);
ALTER TABLE xlite_profiles ADD INDEX shipping_city (shipping_city);
ALTER TABLE xlite_profiles ADD INDEX shipping_state (shipping_state);
ALTER TABLE xlite_profiles ADD INDEX shipping_country (shipping_country);
ALTER TABLE xlite_profiles ADD INDEX shipping_zipcode (shipping_zipcode);
ALTER TABLE xlite_profiles ADD INDEX first_login (first_login);
ALTER TABLE xlite_profiles ADD INDEX last_login (last_login);
ALTER TABLE xlite_profiles ADD INDEX status (status);
ALTER TABLE xlite_profiles ADD INDEX membership (membership);
ALTER TABLE xlite_profiles ADD INDEX pending_membership (pending_membership);

ALTER TABLE xlite_search_stat ADD INDEX product_count (product_count);
ALTER TABLE xlite_search_stat ADD INDEX count (count);

ALTER TABLE xlite_sessions ADD INDEX expiry (expiry);

ALTER TABLE xlite_shipping ADD INDEX class (class);
ALTER TABLE xlite_shipping ADD INDEX destination (destination);
ALTER TABLE xlite_shipping ADD INDEX name (name);
ALTER TABLE xlite_shipping ADD INDEX enabled (enabled);
ALTER TABLE xlite_shipping ADD INDEX order_by (order_by);

ALTER TABLE xlite_shipping_rates ADD percent decimal(12,2) NOT NULL default '0.00';
ALTER TABLE xlite_shipping_rates ADD per_lbs decimal(12,2) NOT NULL default '0.00';
ALTER TABLE xlite_shipping_rates ADD INDEX max_weight (max_weight);
ALTER TABLE xlite_shipping_rates ADD INDEX max_total (max_total);
ALTER TABLE xlite_shipping_rates ADD INDEX flat (flat);
ALTER TABLE xlite_shipping_rates ADD INDEX per_item (per_item);
ALTER TABLE xlite_shipping_rates ADD INDEX percent (percent);
ALTER TABLE xlite_shipping_rates ADD INDEX per_lbs (per_lbs);
ALTER TABLE xlite_shipping_rates ADD INDEX max_items (max_items);

ALTER TABLE xlite_states ADD INDEX state (state);
ALTER TABLE xlite_states ADD INDEX shipping_zone (shipping_zone);

ALTER TABLE xlite_upgrades ADD INDEX date (date);


UPDATE xlite_states SET code='NU', state='Nunavut' WHERE state='NWT/Nunavut' AND country_code='CA';


INSERT INTO xlite_card_types VALUES ('JCB','JCB',0,60,0);
INSERT INTO xlite_card_types VALUES ('UKE','Visa Electron',1,70,0);
INSERT INTO xlite_card_types VALUES ('DICL','Diners Club',0,70,0);

INSERT INTO xlite_config VALUES ('operation_presentation','Maintenance and operation','','General',100,'separator');

UPDATE xlite_config SET orderby = 110, comment='Maintenance mode (close the store for maintenance)' WHERE category = 'General' AND name = 'shop_closed';
UPDATE xlite_config SET orderby = 120, comment='Checkout desk operation mode' WHERE category = 'General' AND name = 'add_on_mode';
UPDATE xlite_config SET orderby = 130, comment='Safe mode (do not initialize modules)' WHERE category = 'General' AND name = 'safe_mode';


INSERT INTO xlite_config VALUES ('customer_presentation','Customer Zone settings','','General',200,'separator');
INSERT INTO xlite_config VALUES ('enable_credit_card_validation','Validate credit cards','10','General',210,'checkbox');
UPDATE xlite_config SET orderby = 220, comment='Category listings format' WHERE category = 'General' AND name = 'subcategories_look';
UPDATE xlite_config SET orderby = 230 WHERE category = 'General' AND name = 'show_thumbnails';
UPDATE xlite_config SET orderby = 240 WHERE category = 'General' AND name = 'buynow_button_enabled';
UPDATE xlite_config SET orderby = 250, comment='Products per category listing page' WHERE category = 'General' AND name = 'products_per_page';
INSERT INTO xlite_config VALUES ('def_calc_shippings_taxes','Show shipping rates & taxes to unregistered customers<br>(presuming that a customer comes from the default country)','N','General',260,'checkbox');
UPDATE xlite_config SET orderby = 270 WHERE category = 'General' AND name = 'enable_anon_checkout';
UPDATE xlite_config SET orderby = 280 WHERE category = 'General' AND name = 'minimal_order_amount';
UPDATE xlite_config SET orderby = 290 WHERE category = 'General' AND name = 'maximal_order_amount';
UPDATE xlite_config SET orderby = 300, comment='Redirect customer to cart when adding a product' WHERE category = 'General' AND name = 'redirect_to_cart';
UPDATE xlite_config SET orderby = 310 WHERE category = 'General' AND name = 'default_country';
INSERT INTO xlite_config VALUES ('default_zipcode','Default zip/postal code in the registration form','10000','General',320,'text');


INSERT INTO xlite_config VALUES ('admin_presentation','Administrator Zone settings','','General',400,'separator');
UPDATE xlite_config SET orderby = 410, comment='Products per page' WHERE category = 'General' AND name = 'products_per_page_admin';
UPDATE xlite_config SET orderby = 420, comment='Users per page' WHERE category = 'General' AND name = 'users_per_page';
UPDATE xlite_config SET orderby = 430, comment='Orders per page' WHERE category = 'General' AND name = 'orders_per_page';
UPDATE xlite_config SET orderby = 440, comment='Amount of orders in the recent orders list' WHERE category = 'General' AND name = 'recent_orders';
UPDATE xlite_config SET orderby = 450, comment='Days to store last login data' WHERE category = 'General' AND name = 'login_lifetime';
UPDATE xlite_config SET orderby = 460, comment='Initial order number' WHERE category = 'General' AND name = 'order_starting_number';
INSERT INTO xlite_config VALUES ('enable_categories_extra_fields','Enable extra fields in the category management dialogue','Y','General',470,'checkbox');
INSERT INTO xlite_config VALUES ('enable_extra_fields_inherit','Enable products to inherit extra fields from disabled categories (for MultiCategories)','Y','General',480,'checkbox');
INSERT INTO xlite_config VALUES ('admin_zone_sbbe_enabled','Enable folding sidebar menus','Y','General',490,'checkbox');
UPDATE xlite_config SET value='2.2 build 17' WHERE name='version' AND category='Version';


INSERT INTO xlite_config VALUES ('unit_presentation','Units of measurement','','General',600,'separator');
UPDATE xlite_config SET orderby = 610 WHERE category = 'General' AND name = 'weight_unit';
UPDATE xlite_config SET orderby = 620 WHERE category = 'General' AND name = 'weight_symbol';
UPDATE xlite_config SET orderby = 630 WHERE category = 'General' AND name = 'price_format';
UPDATE xlite_config SET orderby = 640 WHERE category = 'General' AND name = 'thousand_delim';
UPDATE xlite_config SET orderby = 650 WHERE category = 'General' AND name = 'decimal_delim';
UPDATE xlite_config SET orderby = 660 WHERE category = 'General' AND name = 'date_format';
UPDATE xlite_config SET orderby = 670 WHERE category = 'General' AND name = 'time_format';


UPDATE xlite_config SET orderby='360' WHERE category='Company' AND name='company_name';
UPDATE xlite_config SET orderby='370' WHERE category='Company' AND name='company_website';
UPDATE xlite_config SET orderby='380' WHERE category='Company' AND name='start_year';


UPDATE xlite_config SET orderby='390', comment="Street address" WHERE category='Company' AND name='location_address';
UPDATE xlite_config SET orderby='400', comment="City" WHERE category='Company' AND name='location_city';
UPDATE xlite_config SET orderby='410', comment="Phone" WHERE category='Company' AND name='company_phone';
UPDATE xlite_config SET orderby='420', comment="Fax" WHERE category='Company' AND name='company_fax';
UPDATE xlite_config SET orderby='430', comment="State" WHERE category='Company' AND name='location_state';
UPDATE xlite_config SET orderby='440', comment="Zip/postal code" WHERE category='Company' AND name='location_zipcode';
UPDATE xlite_config SET orderby='450', comment="Country" WHERE category='Company' AND name='location_country';

INSERT INTO xlite_config VALUES ('company_identity','Identity','','Company',350,'separator');
INSERT INTO xlite_config VALUES ('company_address','Address','','Company',385,'separator');
INSERT INTO xlite_config VALUES ('custom_location_state','Other state (specify)','','Company',435,'text');
INSERT INTO xlite_config VALUES ('company_contacts','Contacts','','Company',455,'separator');


UPDATE xlite_config SET orderby='460', comment="Site administrator e-mail" WHERE category='Company' AND name='site_administrator';
UPDATE xlite_config SET orderby='470', comment="Customer relations e-mail" WHERE category='Company' AND name='users_department';
UPDATE xlite_config SET orderby='480', comment="Sales department e-mail" WHERE category='Company' AND name='orders_department';
UPDATE xlite_config SET orderby='490', comment="HelpDesk/Support service e-mail" WHERE category='Company' AND name='support_department';
DELETE FROM xlite_config WHERE category='Company' AND name='newsletter_email';


UPDATE xlite_config SET comment='Use HTTPS in the Customer Zone (for login, checkout, profile and shopping cart pages)' WHERE name = 'customer_security' AND category = 'Security';
UPDATE xlite_config SET orderby='10', comment='Use HTTPS in the Administrator Zone' WHERE name = 'admin_security' AND category = 'Security';
UPDATE xlite_config SET orderby='20', comment='HTTPS client to use (for integration with secure payment/shipping services)' WHERE name = 'httpsClient' AND category = 'Security';
INSERT INTO xlite_config VALUES ('logoff_clear_cart','Clear cart on customer logoff','N','Security',10,'checkbox');
DELETE FROM xlite_config WHERE name='customer_password_hint' AND category='Security';


DELETE FROM xlite_config WHERE category='Email' AND name='show_cc_info';
UPDATE xlite_config SET orderby='0', comment="E-mail order details to customers after order placement" WHERE category='Email' AND name='enable_init_order_notif_customer';
UPDATE xlite_config SET orderby='10', comment="E-mail order details to the sales department after order placement" WHERE category='Email' AND name='enable_init_order_notif';
INSERT INTO xlite_config VALUES ('valid_email_domains','Valid domain names for email','com;net;edu;mil;gov;org;biz','Email',30,'text');
