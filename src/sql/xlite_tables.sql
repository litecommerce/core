DROP TABLE IF EXISTS xlite_categories;
CREATE TABLE xlite_categories (
  category_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  lpos int(11) NOT NULL DEFAULT '0',
  rpos int(11) NOT NULL DEFAULT '0',
  views_stats int(11) NOT NULL DEFAULT '0',
  order_by int(11) NOT NULL DEFAULT '0',
  membership_id int(11) DEFAULT '0',
  threshold_bestsellers int(11) unsigned NOT NULL DEFAULT '1',
  enabled int(1) NOT NULL DEFAULT '1',
  clean_url varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (category_id),
  KEY order_by (order_by),
  KEY views_stats (views_stats),
  KEY membership (membership_id),
  KEY threshold_bestsellers (threshold_bestsellers),
  KEY enabled (enabled),
  KEY clean_url (clean_url)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_category_images;
CREATE TABLE xlite_category_images (
  image_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  id int(11) NOT NULL DEFAULT '0',
  path varchar(512) NOT NULL DEFAULT '',
  mime varchar(64) NOT NULL DEFAULT 'image/jpeg',
  width int(11) NOT NULL DEFAULT '0',
  height int(11) NOT NULL DEFAULT '0',
  size int(11) NOT NULL DEFAULT '0',
  date int(11) NOT NULL DEFAULT '0',
  hash varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (image_id),
  KEY id (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_category_products;
CREATE TABLE xlite_category_products (
  product_id int(11) NOT NULL DEFAULT '0',
  category_id int(11) NOT NULL DEFAULT '0',
  orderby int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (category_id,product_id),
  KEY xlite_product_links_product (product_id),
  KEY orderby (orderby),
  KEY xlite_product_links_category (category_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_category_translations;
CREATE TABLE xlite_category_translations (
  label_id int(11) NOT NULL AUTO_INCREMENT,
  code char(2) NOT NULL,
  id int(11) NOT NULL DEFAULT '0',
  name char(255) NOT NULL,
  description text NOT NULL,
  meta_tags varchar(255) NOT NULL DEFAULT '',
  meta_desc text NOT NULL,
  meta_title varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_config;
CREATE TABLE xlite_config (
  config_id int NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  category varchar(32) NOT NULL default '',
  type enum('','text','textarea','checkbox','country','state','select','serialized','separator') default NULL,
  orderby int(11) NOT NULL default '0',
  value text NOT NULL,
  PRIMARY KEY  (config_id),
  UNIQUE KEY nc (category, name),
  KEY orderby (orderby),
  KEY type (type),
  FULLTEXT KEY value (value)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_config_translations;
CREATE TABLE xlite_config_translations (
  label_id int(11) NOT NULL auto_increment,
  code char(2) NOT NULL,
  id int(11) NOT NULL default 0,
  option_name char(255) NOT NULL,
  option_comment char(255) NOT NULL,
  PRIMARY KEY (label_id),
  KEY ci (code, id),
  KEY i (id)
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
  thumbnail_width int(11) NOT NULL default 0,
  thumbnail_height int(11) NOT NULL default 0,
  thumbnail_size int(11) NOT NULL default 0,
  thumbnail mediumblob,
  image_source char(1) NOT NULL default 'D',
  image_type varchar(32) NOT NULL default '',
  image_width int(11) NOT NULL default 0,
  image_height int(11) NOT NULL default 0,
  image_size int(11) NOT NULL default 0,
  image mediumblob,
  order_by int(11) NOT NULL default '0',
  enabled int(11) NOT NULL default '1',
  weight decimal(12,2) NOT NULL default '0.00',
  tax_class varchar(32) NOT NULL default '',
  free_shipping int(11) NOT NULL default '0',
  meta_desc text NOT NULL default '',
  meta_title varchar(255) NOT NULL default '',
  clean_url varchar(255) NOT NULL default '',
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
  KEY meta_title (meta_title),
  KEY clean_url(clean_url)
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
  billing_type char(1) NOT NULL default 'R',
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
  shipping_type char(1) NOT NULL default 'R',
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
  membership int(11) NOT NULL default 0,
  pending_membership int(11) NOT NULL default 0,
  order_id int(11) NOT NULL default '0',
  sidebar_boxes TEXT NOT NULL,
  language varchar(2) NOT NUll default 'en',
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

DROP TABLE IF EXISTS xlite_view_lists;
CREATE TABLE xlite_view_lists (
  list_id int(11) NOT NULL auto_increment PRIMARY KEY,
  class varchar(64) NOT NULL default '',
  list varchar(32) NOT NULL default '',
  child varchar(64) NOT NULL default '',
  weight int(11) NOT NULL default 0,
  callback varchar(64) NOT NULL default '',
  KEY cl (class, list, weight)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_view_lists;
CREATE TABLE xlite_view_lists (
  list_id int(11) NOT NULL auto_increment PRIMARY KEY,
  class varchar(64) NOT NULL default '',
  list varchar(64) NOT NULL default '',
  zone varchar(16) NOT NULL default 'customer',
  child varchar(64) NOT NULL default '',
  weight mediumint unsigned NOT NULL default 0,
  tpl varchar(255) NOT NULL default '',
  KEY clzw (class, list, zone, weight)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_template_patches;
CREATE TABLE xlite_template_patches (
  patch_id int(11) NOT NULL auto_increment PRIMARY KEY,
  zone varchar(16) NOT NULL default 'customer',
  lang varchar(2) NOT NULL default '',
  tpl varchar(64) NOT NULL default '',
  patch_type varchar(8) NOT NULL default '',
  xpath_query varchar(255) NOT NUll default '',
  xpath_insert_type varchar(16) NOT NULL default 'before',
  xpath_block text NOT NULL,
  regexp_pattern varchar(255) NOT NUll default '',
  regexp_replace text NOT NULL,
  custom_callback varchar(128) NOT NUll default '',
  KEY zlt (zone, lang, tpl)
) TYPE=MyISAM;


-- ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS xlite_languages;
CREATE TABLE xlite_languages (
  lng_id int(11) NOT NULL auto_increment PRIMARY KEY,
  code char(2) NOT NULL,
  code3 char(3) NOT NULL default '',
  r2l int(1) NOT NULL default 0,
  status int(1) NOT NULL default 0,
  UNIQUE KEY code3 (code3),
  UNIQUE KEY code2 (code),
  KEY status(status)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_language_translations;
CREATE TABLE xlite_language_translations (
  label_id int(11) NOT NULL auto_increment PRIMARY KEY,
  code char(2) NOT NULL,
  id int(11) NOT NULL default 0,
  name char(64) NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_language_labels;
CREATE TABLE xlite_language_labels (
  label_id int(11) NOT NULL auto_increment PRIMARY KEY,
  name varchar(255) NOT NULL default '',
  KEY name (name)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_language_label_translations;
CREATE TABLE xlite_language_label_translations (
  label_id int(11) NOT NULL auto_increment PRIMARY KEY,
  code char(2) NOT NULL,
  id int(11) NOT NULL default 0,
  label text NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_memberships;
CREATE TABLE xlite_memberships (
  membership_id int(11) NOT NULL auto_increment PRIMARY KEY,
  orderby int(11) NOT NULL default 0,
  active int(1) NOT NULL default 1
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_membership_translations;
CREATE TABLE xlite_membership_translations (
  label_id int(11) NOT NULL auto_increment PRIMARY KEY,
  code char(2) NOT NULL,
  id int(11) NOT NULL default 0,
  name char(128) NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) TYPE=MyISAM;

