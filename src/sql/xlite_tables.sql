/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

DROP TABLE IF EXISTS xlite_categories;
CREATE TABLE xlite_categories (
  category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  parent_id INT UNSIGNED,
  lpos INT NOT NULL DEFAULT '0',
  rpos INT NOT NULL DEFAULT '0',
  membership_id INT DEFAULT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  cleanUrl VARCHAR(255) NOT NULL DEFAULT '',
  show_title TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (category_id),
  KEY parent_id (parent_id),
  KEY lpos (lpos),
  KEY rpos (rpos),
  KEY membership_id (membership_id),
  KEY enabled (enabled),
  KEY clean_url (cleanUrl)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_category_images;
CREATE TABLE xlite_category_images (
  image_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id INT UNSIGNED NOT NULL DEFAULT '0',
  path VARCHAR(512) NOT NULL DEFAULT '',
  mime VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
  width INT NOT NULL DEFAULT '0',
  height INT NOT NULL DEFAULT '0',
  size INT NOT NULL DEFAULT '0',
  date INT NOT NULL DEFAULT '0',
  hash VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (image_id),
  KEY id (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_category_quick_flags;
CREATE TABLE xlite_category_quick_flags (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id INT UNSIGNED DEFAULT NULL,
  subcategories_count_all INT NOT NULL DEFAULT '0',
  subcategories_count_enabled INT NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY (category_id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_category_translations;
CREATE TABLE xlite_category_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT '0',
  name VARCHAR(255),
  description text,
  meta_tags VARCHAR(255) DEFAULT '',
  meta_desc text,
  meta_title VARCHAR(255) DEFAULT '',
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_config;
CREATE TABLE xlite_config (
  config_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(32) NOT NULL DEFAULT '',
  category VARCHAR(32) NOT NULL DEFAULT '',
  type VARCHAR(16) DEFAULT NULL,
  orderby INT NOT NULL DEFAULT '0',
  value text NOT NULL,
  PRIMARY KEY  (config_id),
  UNIQUE KEY nc (category, name),
  KEY orderby (orderby),
  KEY type (type),
  KEY value (value (65536))
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_config_translations;
CREATE TABLE xlite_config_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT 0,
  option_name VARCHAR(255) NOT NULL,
  option_comment VARCHAR(255) NOT NULL,
  PRIMARY KEY (label_id),
  KEY ci (code, id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_currencies;
CREATE TABLE xlite_currencies (
  code CHAR(3) NOT NULL,
  currency_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  symbol VARCHAR(16) NOT NULL,
  e TINYINT(1) NOT NULL,
  UNIQUE KEY code(code)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_currency_translations;
CREATE TABLE xlite_currency_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT UNSIGNED NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


DROP TABLE IF EXISTS xlite_waitingips;
CREATE TABLE xlite_waitingips (
  id INT NOT NULL AUTO_INCREMENT,
  ip VARCHAR(32) NOT NULL DEFAULT '',
  unique_key VARCHAR(50) NOT NULL DEFAULT '',
  first_date INT NOT NULL DEFAULT '0',
  last_date INT NOT NULL DEFAULT '0',
  count INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  UNIQUE (ip)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_htaccess;
CREATE TABLE xlite_htaccess (
  id INT NOT NULL AUTO_INCREMENT,
  filename VARCHAR(64) NOT NULL DEFAULT '',
  content text NOT NULL DEFAULT '',
  hash VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  KEY hash (hash),
  UNIQUE (filename)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_countries;
CREATE TABLE xlite_countries (
  country VARCHAR(50) NOT NULL DEFAULT '',
  code CHAR(2) NOT NULL DEFAULT '',
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  eu_member CHAR(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY  (code),
  KEY country (country),
  KEY enabled (enabled),
  KEY eu_member (eu_member)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_log;
CREATE TABLE xlite_log (
  unixtime INT NOT NULL DEFAULT '0',
  ident VARCHAR(16) NOT NULL DEFAULT '',
  priority INT DEFAULT NULL,
  message VARCHAR(200) DEFAULT NULL,
  KEY unixtime (unixtime,ident),
  KEY priority (priority),
  KEY message (message)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_modules;
CREATE TABLE xlite_modules (
  moduleId INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(64) NOT NULL DEFAULT '',
  author VARCHAR(64) NOT NULL DEFAULT '',
  enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  installed TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  version VARCHAR(32) NOT NULL DEFAULT '',
  status int(1) UNSIGNED NOT NULL DEFAULT '0',
  moduleName VARCHAR(255) NOT NULL DEFAULT '',
  authorName VARCHAR(255) NOT NULL DEFAULT '',
  description text,
  changelog text,
  hash VARCHAR(32),
  packHash VARCHAR(32),
  price decimal(16,4) NOT NULL DEFAULT '0.0000',
  currency VARCHAR(3) NOT NULL DEFAULT '',
  uploadCode VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (moduleId),
  UNIQUE KEY an (author,name),
  KEY enabled (enabled)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_order_items;
CREATE TABLE xlite_order_items (
  item_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL DEFAULT '0',
  object_id INT UNSIGNED NOT NULL DEFAULT '0',
  object_type VARCHAR(16) NOT NULL DEFAULT 'product',
  name VARCHAR(255) NOT NULL,
  sku VARCHAR(255) NOT NULL DEFAULT '',
  price numeric(16,4) NOT NULL DEFAULT '0.0000',
  amount INT NOT NULL DEFAULT '1',
  subtotal numeric(16,4) NOT NULL DEFAULT '0.0000',
  total numeric(16,4) NOT NULL DEFAULT '0.0000',
  KEY ooo (order_id, object_id, object_type),
  KEY price (price),
  KEY amount (amount)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_orders;
CREATE TABLE xlite_orders (
  order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  profile_id INT DEFAULT NULL,
  orig_profile_id INT DEFAULT NULL,
  total numeric(16,4) NOT NULL DEFAULT '0.00',
  subtotal numeric(16,4) NOT NULL DEFAULT '0.00',
  tracking VARCHAR(32) DEFAULT NULL,
  date INT DEFAULT NULL,
  status CHAR(1) DEFAULT 'I',
  notes text,
  taxes text,
  shipping_id INT DEFAULT NULL,
  is_order TINYINT(1) NOT NULL DEFAULT 1,
  currency_id INT UNSIGNED NOT NULL DEFAULT 840,
  KEY `date` (`date`),
  KEY profile_id (profile_id),
  KEY orig_profile_id (orig_profile_id),
  KEY total (total),
  KEY subtotal (subtotal),
  KEY tracking (tracking),
  KEY status (status),
  KEY notes (notes (65536)),
  KEY shipping_id (shipping_id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_order_details;
CREATE TABLE xlite_order_details (
  detail_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL DEFAULT '',
  label VARCHAR(255) DEFAULT NULL,
  value text NOT NULL,
  KEY oname (order_id, name)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_order_modifiers;
CREATE TABLE xlite_order_modifiers (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL DEFAULT '0',
  code VARCHAR(32) NOT NULL DEFAULT '',
  name VARCHAR(255) NOT NULL DEFAULT '',
  is_visible TINYINT(1) NOT NULL DEFAULT 0,
  is_summable TINYINT(1) NOT NULL DEFAULT 1,
  subcode VARCHAR(32) NOT NULL DEFAULT '',
  surcharge numeric(16,4) NOT NULL DEFAULT '0.0000',
  KEY ocs (order_id, code, subcode)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_order_item_modifiers;
CREATE TABLE xlite_order_item_modifiers (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL DEFAULT '0',
  code VARCHAR(32) NOT NULL DEFAULT '',
  name VARCHAR(255) NOT NULL DEFAULT '',
  is_visible TINYINT(1) NOT NULL DEFAULT 0,
  is_summable TINYINT(1) NOT NULL DEFAULT 1,
  subcode VARCHAR(32) NOT NULL DEFAULT '',
  surcharge numeric(16,4) NOT NULL DEFAULT '0.0000',
  KEY ics (item_id, code, subcode)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_payment_methods;
CREATE TABLE xlite_payment_methods (
  method_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(128) NOT NULL DEFAULT '',
  class VARCHAR(64) NOT NULL DEFAULT '',
  orderby INT NOT NULL DEFAULT '0',
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  KEY orderby (orderby),
  KEY class (class, enabled),
  KEY enabled (enabled)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_payment_method_translations;
CREATE TABLE xlite_payment_method_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL,
  description text NOT NULL DEFAULT '',
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_payment_method_settings;
CREATE TABLE xlite_payment_method_settings (
  setting_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  method_id INT NOT NULL DEFAULT 0,
  name VARCHAR(128) NOT NULL,
  value text NOT NULL,
  KEY mn (method_id, name)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_payment_transactions;
CREATE TABLE xlite_payment_transactions (
  `transaction_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL DEFAULT 0,
  `method_id` INT NOT NULL DEFAULT 0,
  `method_name` VARCHAR(128) NOT NULL,
  `method_local_name` VARCHAR(255) NOT NULL,
  `status` CHAR(1) NOT NULL DEFAULT 'I',
  `value` numeric(16,4) NOT NULL DEFAULT '0.0000',
  `type` VARCHAR(8) NOT NULL DEFAULT 'sale',
  `note` VARCHAR(255) NOT NULL DEFAULT '',
  KEY o (order_id, status),
  KEY pm (method_id, status),
  KEY status (status)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_payment_transaction_data;
CREATE TABLE xlite_payment_transaction_data (
  data_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  transaction_id INT NOT NULL DEFAULT 0,
  name VARCHAR(128) NOT NULL,
  label VARCHAR(255) NOT NULL DEFAULT '',
  access_level CHAR(1) NOT NULL DEFAULT 'A',
  value text NOT NULL,
  KEY tn (transaction_id, name)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_products;
CREATE TABLE xlite_products (
  product_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  price numeric(12,2) NOT NULL DEFAULT '0.00',
  sale_price numeric(12,2) NOT NULL DEFAULT '0.00',
  sku VARCHAR(32) NOT NULL DEFAULT '' COLLATE utf8_general_ci,
  enabled INT NOT NULL DEFAULT '1',
  weight numeric(12,2) NOT NULL DEFAULT '0.00',
  tax_class VARCHAR(32) NOT NULL DEFAULT '',
  free_shipping INT NOT NULL DEFAULT '0',
  clean_url VARCHAR(255) NOT NULL DEFAULT '',
  javascript text NOT NULL,
  PRIMARY KEY (product_id),
  KEY price (price),
  KEY sku (sku),
  KEY enabled (enabled),
  KEY weight (weight),
  KEY tax_class (tax_class),
  KEY free_shipping (free_shipping),
  KEY clean_url (clean_url)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_category_products;
CREATE TABLE xlite_category_products (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL DEFAULT '0',
  category_id INT UNSIGNED NOT NULL DEFAULT '0',
  orderby INT NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY pair (category_id,product_id),
  KEY orderby (orderby)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_product_images;
CREATE TABLE xlite_product_images (
  `image_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` INT NOT NULL DEFAULT '0',
  `path` VARCHAR(512) NOT NULL DEFAULT '',
  `mime` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
  `width` INT NOT NULL DEFAULT '0',
  `height` INT NOT NULL DEFAULT '0',
  `size` INT NOT NULL DEFAULT '0',
  `date` INT NOT NULL DEFAULT '0',
  `hash` VARCHAR(32) NOT NULL DEFAULT '',
  `alt` VARCHAR(255) NOT NULL DEFAULT '',
  `orderby` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (image_id),
  KEY id (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_product_translations;
CREATE TABLE xlite_product_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL COLLATE utf8_general_ci,
  description text NOT NULL COLLATE utf8_general_ci,
  brief_description text NOT NULL COLLATE utf8_general_ci,
  meta_tags VARCHAR(255) NOT NULL DEFAULT '',
  meta_desc text NOT NULL,
  meta_title VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_extra_fields;
CREATE TABLE xlite_extra_fields (
  field_id INT NOT NULL AUTO_INCREMENT,
  product_id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL DEFAULT '',
  DEFAULT_value VARCHAR(255) NOT NULL DEFAULT '',
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  order_by INT NOT NULL DEFAULT '0',
  parent_field_id INT NOT NULL DEFAULT '0',
  categories text NOT NULL,
  PRIMARY KEY  (field_id),
  KEY product_id (product_id),
  KEY order_by (order_by),
  KEY name (name),
  KEY DEFAULT_value (DEFAULT_value),
  KEY enabled (enabled),
  KEY parent_field_id (parent_field_id),
  KEY categories (categories (65536))
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_extra_field_values;
CREATE TABLE xlite_extra_field_values (
  product_id INT NOT NULL DEFAULT '0',
  field_id INT NOT NULL DEFAULT '0',
  value text(65536) NOT NULL DEFAULT '',
  KEY field_key (product_id, field_id),
  KEY value (value (65536))
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_profiles;
CREATE TABLE xlite_profiles (
  profile_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(128) NOT NULL DEFAULT '',
  password VARCHAR(32) NOT NULL DEFAULT '',
  password_hint VARCHAR(128) NOT NULL DEFAULT '',
  password_hint_answer VARCHAR(128) NOT NULL DEFAULT '',
  access_level INT NOT NULL DEFAULT '0',
  cms_profile_id INT NOT NULL DEFAULT '0',
  cms_name VARCHAR(32) NOT NULL DEFAULT '',
  added INT NOT NULL DEFAULT '0',
  first_login INT NOT NULL DEFAULT '0',
  last_login INT NOT NULL DEFAULT '0',
  status CHAR(1) NOT NULL DEFAULT 'E',
  referer VARCHAR(255) NOT NULL DEFAULT '',
  membership_id int,
  pending_membership_id int,
  order_id INT DEFAULT NULL,
  language VARCHAR(2) NOT NUll DEFAULT 'en',
  last_shipping_id INT DEFAULT NULL,
  last_payment_id INT DEFAULT NULL,
  KEY (cms_profile_id),
  KEY login (login),
  KEY order_id (order_id),
  KEY password (password),
  KEY access_level (access_level),
  KEY first_login (first_login),
  KEY last_login (last_login),
  KEY status (status),
  KEY membership_id (membership_id),
  KEY pending_membership_id (pending_membership_id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_profile_addresses;
CREATE TABLE xlite_profile_addresses (
  address_id INT NOT NULL AUTO_INCREMENT,
  profile_id INT NOT NULL DEFAULT 0,
  is_billing TINYINT(1) NOT NULL DEFAULT 0,
  is_shipping TINYINT(1) NOT NULL DEFAULT 0,
  address_type CHAR(1) NOT NULL DEFAULT 'R',
  title VARCHAR(32) NOT NULL DEFAULT '',
  firstname VARCHAR(128) NOT NULL DEFAULT '',
  lastname VARCHAR(128) NOT NULL DEFAULT '',
  phone VARCHAR(32) NOT NULL DEFAULT '',
  street VARCHAR(64) NOT NULL DEFAULT '',
  city VARCHAR(64) NOT NULL DEFAULT '',
  state_id INT NOT NULL DEFAULT '0',
  custom_state VARCHAR(64) NOT NULL DEFAULT '',
  country_code CHAR(2) DEFAULT NULL,
  zipcode VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (address_id),
  KEY profile_id (profile_id),
  KEY is_billing (is_billing),
  KEY is_shipping (is_shipping)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_search_stat;
CREATE TABLE xlite_search_stat (
  query VARCHAR(64) NOT NULL DEFAULT '',
  product_count INT NOT NULL DEFAULT '0',
  count INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (query),
  KEY product_count (product_count),
  KEY count (count)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_sessions;
CREATE TABLE xlite_sessions (
  id INT NOT NULL AUTO_INCREMENT,
  sid CHAR(32) NOT NULL,
  expiry INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY id (id),
  UNIQUE KEY sid (sid),
  KEY expiry (expiry)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_session_cells;
CREATE TABLE xlite_session_cells (
  `cell_id` INT NOT NULL AUTO_INCREMENT,
  `id` INT NOT NULL DEFAULT 0,
  `name` VARCHAR(255) NOT NULL,
  `value` text,
  `type` VARCHAR(16),
  PRIMARY KEY (cell_id),
  KEY id (id),
  UNIQUE KEY iname (id, name),
  CONSTRAINT `xlite_session_to_cells` FOREIGN KEY (`id`) REFERENCES `xlite_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


DROP TABLE IF EXISTS xlite_shipping_methods;
CREATE TABLE xlite_shipping_methods (
  method_id INT NOT NULL AUTO_INCREMENT,
  processor VARCHAR(255) NOT NULL DEFAULT '',
  carrier VARCHAR(255) NOT NULL DEFAULT '',
  code VARCHAR(32) NOT NULL DEFAULT '',
  enabled TINYINT(1) NOT NULL DEFAULT '1',
  position INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (method_id),
  KEY processor (processor),
  KEY carrier (carrier),
  KEY enabled (enabled),
  KEY position (position)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_shipping_method_translations;
CREATE TABLE xlite_shipping_method_translations (
  label_id INT NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (label_id),
  KEY ci (code,id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_shipping_markups;
CREATE TABLE xlite_shipping_markups (
  markup_id INT NOT NULL AUTO_INCREMENT,
  method_id INT NOT NULL DEFAULT '0',
  zone_id INT NOT NULL DEFAULT '0',
  min_weight numeric(12,2) NOT NULL DEFAULT '0.00',
  max_weight numeric(12,2) NOT NULL DEFAULT '999999.00',
  min_total numeric(12,2) NOT NULL DEFAULT '0.00',
  max_total numeric(12,2) NOT NULL DEFAULT '999999.00',
  min_items INT NOT NULL DEFAULT '0',
  max_items INT NOT NULL DEFAULT '999999',
  markup_flat numeric(12,2) NOT NULL DEFAULT '0.00',
  markup_percent numeric(12,2) NOT NULL DEFAULT '0.00',
  markup_per_item numeric(12,2) NOT NULL DEFAULT '0.00',
  markup_per_weight numeric(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY  (markup_id),
  KEY rate (method_id,zone_id,min_weight,min_total,min_items),
  KEY max_weight (max_weight),
  KEY max_total (max_total),
  KEY max_items (max_items),
  KEY markup_flat (markup_flat),
  KEY markup_per_item (markup_per_item),
  KEY markup_percent (markup_percent),
  KEY markup_per_weight (markup_per_weight)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_states;
CREATE TABLE xlite_states (
  state_id INT NOT NULL AUTO_INCREMENT,
  state VARCHAR(32) NOT NULL DEFAULT '',
  code VARCHAR(32) NOT NULL DEFAULT '',
  country_code CHAR(2) DEFAULT NULL,
  PRIMARY KEY  (state_id),
  UNIQUE KEY code (code, country_code),
  KEY state (state)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_upgrades;
CREATE TABLE xlite_upgrades (
  from_ver VARCHAR(10) NOT NULL DEFAULT '',
  to_ver VARCHAR(10) NOT NULL DEFAULT '',
  date INT NOT NULL DEFAULT '0',
  PRIMARY KEY  (from_ver,to_ver),
  KEY date (date)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_form_ids;
CREATE TABLE xlite_form_ids (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  form_id VARCHAR(32) NOT NULL,
  date INT NOT NULL,
  KEY session_id (session_id),
  UNIQUE KEY fs(form_id, session_id),
  CONSTRAINT `xlite_session_to_forms` FOREIGN KEY session_id (`session_id`) REFERENCES `xlite_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_view_lists;
CREATE TABLE xlite_view_lists (
  list_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  class VARCHAR(512) NOT NULL DEFAULT '',
  list VARCHAR(255) NOT NULL DEFAULT '',
  zone VARCHAR(16) NOT NULL DEFAULT 'customer',
  child VARCHAR(512) DEFAULT '',
  weight mediumint NOT NULL DEFAULT 0,
  tpl VARCHAR(1024) NOT NULL DEFAULT '',
  KEY clzw (class, list, zone, weight)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_template_patches;
CREATE TABLE xlite_template_patches (
  patch_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  zone VARCHAR(16) NOT NULL DEFAULT 'customer',
  lang VARCHAR(2) NOT NULL DEFAULT '',
  tpl VARCHAR(64) NOT NULL DEFAULT '',
  patch_type VARCHAR(8) NOT NULL DEFAULT '',
  xpath_query VARCHAR(255) NOT NUll DEFAULT '',
  xpath_insert_type VARCHAR(16) NOT NULL DEFAULT 'before',
  xpath_block text NOT NULL,
  regexp_pattern VARCHAR(255) NOT NUll DEFAULT '',
  regexp_replace text NOT NULL,
  custom_callback VARCHAR(128) NOT NUll DEFAULT '',
  KEY zlt (zone, lang, tpl)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


-- ALTER TABLE xlite_modules CHANGE version version VARCHAR(12) NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS xlite_languages;
CREATE TABLE xlite_languages (
  lng_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code CHAR(2) NOT NULL,
  code3 CHAR(3) NOT NULL DEFAULT '',
  r2l TINYINT(1) NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 0,
  UNIQUE KEY code3 (code3),
  UNIQUE KEY code2 (code),
  KEY status(status)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_language_translations;
CREATE TABLE xlite_language_translations (
  label_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT 0,
  name VARCHAR(64) NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_language_labels;
CREATE TABLE xlite_language_labels (
  label_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL DEFAULT '',
  UNIQUE KEY name (name)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_language_label_translations;
CREATE TABLE xlite_language_label_translations (
  label_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT 0,
  label text NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_memberships;
CREATE TABLE xlite_memberships (
  membership_id INT AUTO_INCREMENT PRIMARY KEY,
  orderby INT NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_membership_translations;
CREATE TABLE xlite_membership_translations (
  label_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  code CHAR(2) NOT NULL,
  id INT NOT NULL DEFAULT 0,
  name VARCHAR(128) NOT NULL,
  KEY ci (code, id),
  KEY i (id)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_zones;
CREATE TABLE xlite_zones (
  zone_id INT NOT NULL AUTO_INCREMENT,
  zone_name VARCHAR(64) NOT NULL DEFAULT '',
  is_default TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (zone_id),
  KEY zone_name (zone_name),
  KEY zone_DEFAULT (is_default)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS xlite_zone_elements;
CREATE TABLE xlite_zone_elements (
  element_id INT NOT NULL AUTO_INCREMENT,
  zone_id INT NOT NULL DEFAULT '0',
  element_value VARCHAR(255) NOT NULL DEFAULT '',
  element_type CHAR(1) NOT NULL DEFAULT '',
  PRIMARY KEY (element_id),
  KEY type_value (element_type,element_value),
  KEY id_type (zone_id,element_type)
) ENGINE InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


