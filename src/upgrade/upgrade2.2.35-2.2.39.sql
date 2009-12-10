INSERT INTO xlite_config VALUES ('display_check_number','Display check number for eCheck payment method','Y','General',215,'checkbox');

UPDATE xlite_config SET comment='Use HTTPS in the Customer Zone (for login, checkout, profile and shopping cart pages)' WHERE name = 'customer_security' AND category = 'Security';

INSERT INTO xlite_config VALUES ('allow_admin_ip','Allow admin IP','','SecurityIP',40,'');
INSERT INTO xlite_config VALUES ('form_id_protection','Enable admin forms protection system','Y','Security',90,'checkbox');
INSERT INTO xlite_config VALUES ('admin_ip_protection','Enable admin IP protection system','N','Security',100,'checkbox');
INSERT INTO xlite_config VALUES ('captcha_protection_system','Enable Captcha protection system','N','Security',110,'checkbox');
INSERT INTO xlite_config VALUES ('last_date','','0','Htaccess',0,'');
INSERT INTO xlite_config VALUES ('htaccess_protection','Enable .htaccess verification system','Y','Security',110,'checkbox');

UPDATE xlite_config SET comment='Include order details (credit card or eCheck information) into admin order notification message' WHERE name='show_cc_info' AND category='Email';

INSERT INTO xlite_config VALUES ('captcha_protection','Image generator options','','Captcha',0,'separator');
INSERT INTO xlite_config VALUES ('captcha_type','Type of string that should be used for the image','all','Captcha',10,'select');
INSERT INTO xlite_config VALUES ('captcha_length','Length of string','5','Captcha',20,'text');
INSERT INTO xlite_config VALUES ('captcha_separator_pages','Where to display','','Captcha',30,'separator');
INSERT INTO xlite_config VALUES ('active_captcha_pages','active_captcha_pages','','Captcha',30,'serialized');

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

DROP TABLE IF EXISTS xlite_forms;
CREATE TABLE xlite_forms (
  form_id varchar(32) NOT NULL default '',
  session_id varchar(32) NOT NULL default '',
  date int(11) NOT NULL default '0',
  PRIMARY KEY  (form_id,session_id),
  KEY date (date)
) TYPE=MyISAM;

ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
