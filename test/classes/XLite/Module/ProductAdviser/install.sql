DROP TABLE IF EXISTS xlite_related_products;
CREATE TABLE xlite_related_products (
	product_id int(11) NOT NULL default '0',
	related_product_id int(11) NOT NULL default '0',
	order_by int(11) NOT NULL default '0',
	PRIMARY KEY (related_product_id,product_id)
) TYPE=MyISAM;

INSERT INTO xlite_config VALUES ('filters_preferences', '', '', 'ProductAdviser', 1000, 'serialized');

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'admin_related_products_enabled', 
	'Enable the functionality', 
	'Y', 
	'ProductAdviser', 
	'0', 
	'checkbox'
);
UPDATE xlite_config SET comment='Enable the functionality', orderby='0' WHERE category='ProductAdviser' AND name='admin_related_products_enabled';

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'related_products_enabled', 
	'Display related products in the Customer Zone', 
	'Y', 
	'ProductAdviser', 
	'5', 
	'checkbox'
);
UPDATE xlite_config SET comment='Display related products in the Customer Zone', orderby='5' WHERE category='ProductAdviser' AND name='related_products_enabled';

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_template', 
	'Look&feel of a product list', 
	'list', 
	'ProductAdviser', 
	'10', 
	'select'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_columns', 
	'Number of columns', 
	'3', 
	'ProductAdviser', 
	'11', 
	'select'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_show_descr', 
	'Show product description', 
	'Y', 
	'ProductAdviser', 
	'12', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_show_price', 
	'Show product price', 
	'Y', 
	'ProductAdviser', 
	'13', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_show_buynow', 
	'Show \'Add to Cart\' button', 
	'Y', 
	'ProductAdviser', 
	'14', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'rp_bulk_shopping', 
	'Enable multiple additions at once', 
	'N', 
	'ProductAdviser', 
	'15', 
	'checkbox'
);
UPDATE xlite_config SET comment='Enable multiple additions at once' WHERE category='ProductAdviser' AND name='rp_bulk_shopping';

DROP TABLE IF EXISTS xlite_products_recently_viewed;
CREATE TABLE xlite_products_recently_viewed (
	sid VARCHAR(32) NOT NULL default '',
	product_id int(11) NOT NULL default '0',
	views_number int(11) NOT NULL default '0',
	last_viewed int(11) NOT NULL default '0',
	PRIMARY KEY (sid,product_id),
	KEY (views_number),
	KEY (last_viewed)
) TYPE=MyISAM;

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'number_recently_viewed', 
	'Number of products in the \"Recently viewed\" box', 
	'5', 
	'ProductAdviser', 
	'20', 
	'text'
);

DROP TABLE IF EXISTS xlite_products_new_arrivals;
CREATE TABLE xlite_products_new_arrivals (
	product_id int(11) NOT NULL default '0',
	added int(11) NOT NULL default '0',
	new char(1) NOT NULL default 'N',
	updated int(11) NOT NULL default '0',
	PRIMARY KEY (product_id),
	KEY (added),
	KEY (new),
	KEY (updated)
) TYPE=MyISAM;

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'number_new_arrivals', 
	'Number of products in the \"New Arrivals\" box', 
	'5', 
	'ProductAdviser', 
	'40', 
	'text'
);
UPDATE xlite_config SET comment= 'Number of products in the \"New Arrivals\" list' WHERE category='ProductAdviser' AND name='number_new_arrivals';

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'period_new_arrivals', 
	'Period during which a product is to be marked as \"New\" (hours)', 
	'240', 
	'ProductAdviser', 
	'45', 
	'text'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'category_new_arrivals', 
	'Show category-specific new arrivals', 
	'N', 
	'ProductAdviser', 
	'47', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'new_arrivals_type', 
	'New arrivals displayed in', 
	'sidebar', 
	'ProductAdviser', 
	'49', 
	'select'
);

DROP TABLE IF EXISTS xlite_products_also_buy;
CREATE TABLE xlite_products_also_buy (
	product_id int(11) NOT NULL default '0',
	product_id_also_buy int(11) NOT NULL default '0',
	counter int(11) NOT NULL default '0',
	PRIMARY KEY (product_id, product_id_also_buy),
	KEY (counter)
) TYPE=MyISAM;

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'admin_products_also_buy_enabled', 
	'Enable the functionality', 
	'Y', 
	'ProductAdviser', 
	'60', 
	'checkbox'
);
UPDATE xlite_config SET comment='Enable the functionality', orderby='60' WHERE category='ProductAdviser' AND name='admin_products_also_buy_enabled';

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'products_also_buy_enabled', 
	'Display recommended products in the Customer Zone', 
	'Y', 
	'ProductAdviser', 
	'65', 
	'checkbox'
);
UPDATE xlite_config SET comment='Display recommended products in the Customer Zone', orderby='65' WHERE category='ProductAdviser' AND name='products_also_buy_enabled';

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'pab_template', 
	'Look&feel of a product list', 
	'list', 
	'ProductAdviser', 
	'75', 
	'select'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'pab_columns', 
	'Number of columns', 
	'3', 
	'ProductAdviser', 
	'76', 
	'select'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'pab_show_descr', 
	'Show product description', 
	'Y', 
	'ProductAdviser', 
	'77', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'pab_show_price', 
	'Show product price', 
	'Y', 
	'ProductAdviser', 
	'78', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'pab_show_buynow', 
	'Show \'Add to Cart\' button', 
	'Y', 
	'ProductAdviser', 
	'79', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'customer_notifications_enabled', 
	'Enable the \"Customer Notifications\" functionality', 
	'Y', 
	'ProductAdviser', 
	'80', 
	'checkbox'
);

INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'number_notifications', 
	'Number of notification requests per page (in Admin zone)', 
	'20', 
	'ProductAdviser', 
	'85', 
	'text'
);

DROP TABLE IF EXISTS xlite_customers_notifications;
CREATE TABLE xlite_customers_notifications (
	notify_id int(11) NOT NULL auto_increment,
	type enum('product','price') default NULL,
	status enum('Q','U','S','D') NOT NULL default 'Q',
	notify_key TEXT NOT NULL,
	date int(11) default '0',
	profile_id int(11) NOT NULL default '0',
	email varchar(128) NOT NULL default '',
	person_info varchar(255) NOT NULL default '',
	product_id int(11) NOT NULL default '0',
	product_options TEXT NOT NULL,
	quantity int(11) NOT NULL default '0',
	price double NOT NULL default '0',
	PRIMARY KEY (notify_id),
	KEY type (type),
	KEY date (date),
	KEY profile_id (profile_id),
	KEY email (email),
	KEY product_id (product_id)
) TYPE=MyISAM;

UPDATE xlite_config SET orderby='1000' WHERE category='ProductAdviser' AND name='customer_notifications_enabled';
INSERT INTO xlite_config 
(
	name, 
	comment, 
	value, 
	category, 
	orderby, 
	type
) 
VALUES 
(
	'customer_notifications_mode', 
	'Enable Customer Notifications', 
	'3', 
	'ProductAdviser', 
	'80', 
	'select'
);

