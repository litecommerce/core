
DROP TABLE IF EXISTS xlite_card_types;
CREATE TABLE xlite_card_types (
  code varchar(16) NOT NULL default '',
  card_type varchar(24) NOT NULL default '',
  cvv2 int(1) NOT NULL default '1',
  orderby int(11) NOT NULL default '0',
  enabled int(1) NOT NULL default '1',
  PRIMARY KEY  (code),
  KEY card_type (card_type),
  KEY cvv2 (cvv2),
  KEY orderby (orderby),
  KEY enabled (enabled)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_categories;
CREATE TABLE xlite_categories (
  category_id int(11) unsigned NOT NULL auto_increment,
  image_width int(11) unsigned NOT NULL default '0',
  image_height int(11) unsigned NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  description text NOT NULL,
  meta_tags varchar(255) NOT NULL default '',
  views_stats int(11) NOT NULL default '0',
  order_by int(11) NOT NULL default '0',
  membership varchar(32) NOT NULL default '',
  threshold_bestsellers int(11) unsigned NOT NULL default '1',
  parent int(11) unsigned default '0',
  image mediumblob,
  image_source char(1) NOT NULL default 'D',
  image_type varchar(32) NOT NULL default '',
  enabled int(1) NOT NULL default '1',
  meta_desc text NOT NULL default '',
  meta_title varchar(255) NOT NULL default '',
  PRIMARY KEY  (category_id),
  KEY order_by (order_by),
  KEY name (name),
  KEY meta_tags (meta_tags),
  KEY views_stats (views_stats),
  KEY membership (membership),
  KEY threshold_bestsellers (threshold_bestsellers),
  KEY parent (parent),
  KEY image_source (image_source),
  KEY image_type (image_type),
  KEY enabled (enabled),
  KEY meta_title (meta_title),
  FULLTEXT KEY meta_desc (meta_desc),
  FULLTEXT KEY description (description)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_config;
CREATE TABLE xlite_config (
  name varchar(32) NOT NULL default '',
  comment varchar(255) NOT NULL default '',
  value text NOT NULL,
  category varchar(32) NOT NULL default '',
  orderby int(11) NOT NULL default '0',
  type enum('','text','textarea','checkbox','country','state','select','serialized','separator') default NULL,
  PRIMARY KEY  (category,name),
  KEY orderby (orderby),
  KEY type (type),
  FULLTEXT KEY value (value)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_waitingips;
CREATE TABLE xlite_waitingips (
  id int NOT NULL auto_increment,
  ip varchar(32) NOT NULL DEFAULT '',
  unique_key varchar(50) NOT NULL DEFAULT '',
  first_date int(11) NOT NULL DEFAULT '0',
  last_date int(11) NOT NULL DEFAULT '0',
  count int NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  UNIQUE (ip)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_htaccess;
CREATE TABLE xlite_htaccess (
  id int NOT NULL auto_increment,
  filename varchar(64) NOT NULL DEFAULT '',
  content text NOT NULL DEFAULT '',
  hash varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  KEY hash (hash),
  UNIQUE (filename)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_countries;
CREATE TABLE xlite_countries (
  country varchar(50) NOT NULL default '',
  code char(2) NOT NULL default '',
  language varchar(32) NOT NULL default '',
  charset varchar(32) NOT NULL default 'iso-8859-1',
  enabled int(1) NOT NULL default '1',
  eu_member char(1) NOT NULL default 'N',
  shipping_zone int(11) NOT NULL default '0',
  PRIMARY KEY  (code),
  KEY country (country),
  KEY language (language),
  KEY charset (charset),
  KEY enabled (enabled),
  KEY eu_member (eu_member),
  KEY shipping_zone (shipping_zone)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_log;
CREATE TABLE xlite_log (
  unixtime int(11) NOT NULL default '0',
  ident varchar(16) NOT NULL default '',
  priority int(11) default NULL,
  message varchar(200) default NULL,
  KEY unixtime (unixtime,ident),
  KEY priority (priority),
  KEY message (message)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_modules;
CREATE TABLE xlite_modules (
  module_id int(6) NOT NULL auto_increment,
  name varchar(64) NOT NULL default '',
  enabled int(1) unsigned NOT NULL default '0',
  dependencies varchar(1024) NOT NULL default '',
  mutual_modules varchar(1024) NOT NULL default '',
  type int(1) unsigned NOT NULL default '0',
  version varchar(12) NOT NULL default '',
  PRIMARY KEY (module_id),
  UNIQUE KEY (name),
  KEY enabled (enabled),
  KEY dependencies (dependencies),
  KEY mutual_modules (mutual_modules),
  KEY type (type)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_order_items;
CREATE TABLE xlite_order_items (
  item_id varchar(255) NOT NULL default '',
  order_id int(11) NOT NULL default '0',
  orderby int(11) NOT NULL default '0',
  product_id int(11) NOT NULL default '0',
  product_name varchar(255) NOT NULL default '',
  product_sku varchar(32) NOT NULL default '',
  price double NOT NULL default '0',
  amount int(11) NOT NULL default '1',
  PRIMARY KEY  (order_id,item_id),
  KEY orderby (orderby),
  KEY product_id (product_id),
  KEY price (price),
  KEY amount (amount)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_orders;
CREATE TABLE xlite_orders (
  profile_id int(11) NOT NULL default '0',
  orig_profile_id int(11) NOT NULL default '0',
  total decimal(12,2) NOT NULL default '0.00',
  subtotal decimal(12,2) NOT NULL default '0.00',
  shipping_cost decimal(12,2) NOT NULL default '0.00',
  tax decimal(12,2) NOT NULL default '0.00',
  tracking varchar(32) default NULL,
  date int(11) default NULL,
  status char(1) default 'I',
  payment_method varchar(64) default NULL,
  details text,
  notes text,
  order_id int(11) NOT NULL auto_increment,
  shipping_id int(11) default NULL,
  detail_labels text,
  taxes text,
  PRIMARY KEY  (order_id),
  KEY xlite_order_date (date),
  KEY profile_id (profile_id),
  KEY orig_profile_id (orig_profile_id),
  KEY total (total),
  KEY subtotal (subtotal),
  KEY shipping_cost (shipping_cost),
  KEY tax (tax),
  KEY tracking (tracking),
  KEY status (status),
  KEY payment_method (payment_method),
  FULLTEXT KEY details (details),
  FULLTEXT KEY notes (notes),
  KEY shipping_id (shipping_id),
  FULLTEXT KEY detail_labels (detail_labels),
  FULLTEXT KEY taxes (taxes)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_payment_methods;
CREATE TABLE xlite_payment_methods (
  payment_method varchar(64) NOT NULL default '',
  name varchar(128) NOT NULL default '',
  details varchar(255) NOT NULL default '',
  class varchar(64) NOT NULL default '',
  params text NOT NULL,
  orderby int(11) NOT NULL default '0',
  enabled int(1) NOT NULL default '1',
  PRIMARY KEY  (payment_method),
  KEY orderby (orderby),
  KEY name (name),
  KEY details (details),
  KEY class (class),
  FULLTEXT KEY params (params),
  KEY enabled (enabled)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_product_links;
CREATE TABLE xlite_product_links (
  product_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  orderby int(11) NOT NULL default '0',
  PRIMARY KEY  (category_id,product_id),
  KEY xlite_product_links_product (product_id),
  KEY orderby (orderby)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_products;
CREATE TABLE xlite_products (
  product_id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  description text NOT NULL,
  brief_description text NOT NULL,
  meta_tags varchar(255) NOT NULL default '',
  price decimal(12,2) NOT NULL default '0.00',
  sale_price decimal(12,2) NOT NULL default '0.00',
  sku varchar(32) NOT NULL default '',
  thumbnail_source char(1) NOT NULL default 'D',
  thumbnail_type varchar(32) NOT NULL default '',
  thumbnail mediumblob,
  image_source char(1) NOT NULL default 'D',
  image_type varchar(32) NOT NULL default '',
  image mediumblob,
  order_by int(11) NOT NULL default '0',
  enabled int(11) NOT NULL default '1',
  weight decimal(12,2) NOT NULL default '0.00',
  tax_class varchar(32) NOT NULL default '',
  free_shipping int(11) NOT NULL default '0',
  meta_desc text NOT NULL default '',
  meta_title varchar(255) NOT NULL default '',
  PRIMARY KEY  (product_id),
  KEY order_by (order_by),
  KEY name (name),
  FULLTEXT KEY description (description),
  FULLTEXT KEY brief_description (brief_description),
  KEY meta_tags (meta_tags),
  KEY price (price),
  KEY sku (sku),
  KEY thumbnail_source (thumbnail_source),
  KEY thumbnail_type (thumbnail_type),
  KEY image_source (image_source),
  KEY image_type (image_type),
  KEY enabled (enabled),
  KEY weight (weight),
  KEY tax_class (tax_class),
  KEY free_shipping (free_shipping),
  FULLTEXT KEY meta_desc (meta_desc),
  KEY meta_title (meta_title)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_extra_fields;
CREATE TABLE xlite_extra_fields (
  field_id int(11) NOT NULL auto_increment,
  product_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  default_value varchar(255) NOT NULL default '',
  enabled int(1) NOT NULL default '1',
  order_by int(11) NOT NULL default '0',
  parent_field_id int(11) NOT NULL default '0',
  categories TEXT NOT NULL,
  PRIMARY KEY  (field_id),
  KEY product_id (product_id),
  KEY order_by (order_by),
  KEY name (name),
  KEY default_value (default_value),
  KEY enabled (enabled),
  KEY parent_field_id (parent_field_id),
  FULLTEXT KEY categories (categories)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_extra_field_values;
CREATE TABLE xlite_extra_field_values (
  product_id int(11) NOT NULL default '0',
  field_id int(11) NOT NULL default '0',
  value text NOT NULL default '',
  KEY field_key (product_id, field_id),
  FULLTEXT KEY value (value)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_profiles;
CREATE TABLE xlite_profiles (
  profile_id int(11) NOT NULL auto_increment,
  login varchar(128) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  password_hint varchar(128) NOT NULL default '',
  password_hint_answer varchar(128) NOT NULL default '',
  access_level int(11) NOT NULL default '0',
  cms_profile_id int(11) NOT NULL default '0',
  cms_name varchar(32) NOT NULL default '',
  billing_title varchar(32) NOT NULL default '',
  billing_firstname varchar(128) NOT NULL default '',
  billing_lastname varchar(128) NOT NULL default '',
  billing_company varchar(255) NOT NULL default '',
  billing_phone varchar(32) NOT NULL default '',
  billing_fax varchar(32) NOT NULL default '',
  billing_address varchar(64) NOT NULL default '',
  billing_city varchar(64) NOT NULL default '',
  billing_state int(11) NOT NULL default '0',
  billing_custom_state varchar(64) NOT NULL default '',
  billing_country char(2) NOT NULL default '',
  billing_zipcode varchar(32) NOT NULL default '',
  shipping_title varchar(32) NOT NULL default '',
  shipping_firstname varchar(128) NOT NULL default '',
  shipping_lastname varchar(128) NOT NULL default '',
  shipping_company varchar(255) NOT NULL default '',
  shipping_phone varchar(32) NOT NULL default '',
  shipping_fax varchar(32) NOT NULL default '',
  shipping_address varchar(64) NOT NULL default '',
  shipping_city varchar(64) NOT NULL default '',
  shipping_state int(11) NOT NULL default '0',
  shipping_custom_state varchar(64) NOT NULL default '',
  shipping_country char(2) NOT NULL default '',
  shipping_zipcode varchar(32) NOT NULL default '',
  extra_fields text NOT NULL,
  card_name varchar(255) NOT NULL default '',
  card_type varchar(16) NOT NULL default '',
  card_number varchar(42) NOT NULL default '',
  card_expire varchar(4) NOT NULL default '',
  card_cvv2 char(3) NOT NULL default '',
  first_login int(11) NOT NULL default '0',
  last_login int(11) NOT NULL default '0',
  status char(1) NOT NULL default 'E',
  referer varchar(255) NOT NULL default '',
  membership varchar(32) NOT NULL default '',
  pending_membership varchar(32) NOT NULL default '',
  order_id int(11) NOT NULL default '0',
  sidebar_boxes TEXT NOT NULL,
  PRIMARY KEY (profile_id),
  KEY (cms_profile_id),
  KEY login (login),
  KEY order_id (order_id),
  KEY password (password),
  KEY access_level (access_level),
  KEY billing_firstname (billing_firstname),
  KEY billing_lastname (billing_lastname),
  KEY billing_company (billing_company),
  KEY billing_address (billing_address),
  KEY billing_city (billing_city),
  KEY billing_state (billing_state),
  KEY billing_country (billing_country),
  KEY billing_zipcode (billing_zipcode),
  KEY shipping_firstname (shipping_firstname),
  KEY shipping_lastname (shipping_lastname),
  KEY shipping_company (shipping_company),
  KEY shipping_address (shipping_address),
  KEY shipping_city (shipping_city),
  KEY shipping_state (shipping_state),
  KEY shipping_country (shipping_country),
  KEY shipping_zipcode (shipping_zipcode),
  KEY first_login (first_login),
  KEY last_login (last_login),
  KEY status (status),
  KEY membership (membership),
  KEY pending_membership (pending_membership)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_search_stat;
CREATE TABLE xlite_search_stat (
  query varchar(64) NOT NULL default '',
  product_count int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (query),
  KEY product_count (product_count),
  KEY count (count)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_sessions;
CREATE TABLE xlite_sessions (
  id varchar(32) NOT NULL default '',
  expiry int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (id),
  KEY expiry (expiry)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_shipping;
CREATE TABLE xlite_shipping (
  shipping_id int(11) NOT NULL auto_increment,
  class varchar(32) NOT NULL default '',
  destination char(1) NOT NULL default 'N',
  name varchar(128) NOT NULL default '',
  enabled int(11) NOT NULL default '1',
  order_by int(11) NOT NULL default '0',
  PRIMARY KEY  (shipping_id),
  KEY class (class),
  KEY destination (destination),
  KEY name (name),
  KEY enabled (enabled),
  KEY order_by (order_by)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_shipping_rates;
CREATE TABLE xlite_shipping_rates (
  shipping_id int(11) NOT NULL default '0',
  min_weight decimal(12,2) NOT NULL default '0.00',
  max_weight decimal(12,2) NOT NULL default '999999.00',
  min_total decimal(12,2) NOT NULL default '0.00',
  max_total decimal(12,2) NOT NULL default '999999.00',
  shipping_zone int(11) NOT NULL default '0',
  flat decimal(12,2) NOT NULL default '0.00',
  per_item decimal(12,2) NOT NULL default '0.00',
  percent decimal(12,2) NOT NULL default '0.00',
  per_lbs decimal(12,2) NOT NULL default '0.00',
  min_items int(11) NOT NULL default '0',
  max_items int(11) NOT NULL default '999999',
  PRIMARY KEY  (shipping_id,shipping_zone,min_weight,min_total,min_items),
  KEY max_weight (max_weight),
  KEY max_total (max_total),
  KEY flat (flat),
  KEY per_item (per_item),
  KEY percent (percent),
  KEY per_lbs (per_lbs),
  KEY max_items (max_items)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_states;
CREATE TABLE xlite_states (
  state_id int(11) NOT NULL auto_increment,
  state varchar(32) NOT NULL default '',
  code varchar(32) NOT NULL default '',
  shipping_zone int(11) NOT NULL default '0',
  country_code char(2) NOT NULL default '',
  PRIMARY KEY  (state_id),
  KEY code (code,country_code),
  KEY state (state),
  KEY shipping_zone (shipping_zone)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_upgrades;
CREATE TABLE xlite_upgrades (
  from_ver varchar(10) NOT NULL default '',
  to_ver varchar(10) NOT NULL default '',
  date int(11) NOT NULL default '0',
  PRIMARY KEY  (from_ver,to_ver),
  KEY date (date)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_forms;
CREATE TABLE xlite_forms (
  form_id varchar(32) NOT NULL default '',
  session_id varchar(32) NOT NULL default '',
  date int(11) NOT NULL default '0',
  PRIMARY KEY  (form_id,session_id),
  KEY date (date)
) TYPE=MyISAM;

-- ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';

