ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
CREATE TABLE xlite_newsletters (
  news_id int(11) NOT NULL auto_increment,
  subject varchar(128) NOT NULL default '',
  body text NOT NULL,
  send_date int(11) NOT NULL default '0',
  list_id int(11) NOT NULL default '0',
  PRIMARY KEY  (news_id),
  KEY send_date (send_date)
);

CREATE TABLE xlite_news_subscribers (
  list_id int(11) NOT NULL default '0',
  email varchar(128) NOT NULL default '',
  since_date int(11) NOT NULL default '0',
  PRIMARY KEY  (list_id, email)
);

CREATE TABLE xlite_newslists (
  list_id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  description text NOT NULL,
  show_as_news int(1) NOT NULL default '0',
  enabled int(1) NOT NULL default '1',
  PRIMARY KEY  (list_id)
);

INSERT INTO xlite_config VALUES ('recent_messages','Number of items to show in the news headlines box','3','Newsletters',10,'text');
UPDATE xlite_config SET comment= 'Number of items to show in the news headlines box' WHERE category='Newsletters' AND name='recent_messages';

INSERT INTO xlite_config VALUES ('subscribers_per_page_admin','Newsletter subscribers per page','20','Newsletters',20,'text');
UPDATE xlite_config SET comment= 'Newsletter subscribers per page' WHERE category='Newsletters' AND name='subscribers_per_page_admin';

INSERT INTO xlite_config VALUES ('subscribers_per_page_mail','Subscribers per message send-out progress screen','100','Newsletters',30,'text');
UPDATE xlite_config SET comment= 'Subscribers per message send-out progress screen' WHERE category='Newsletters' AND name='subscribers_per_page_mail';
INSERT INTO xlite_config VALUES ('news_order','News message display order','D','Newsletters',15,'select');
