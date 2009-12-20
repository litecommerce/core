
--
-- module: FlyoutCategories
--
-- @version $Id: install.sql,v 1.5 2007/12/21 12:46:09 alien Exp $
--

ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config VALUES ('scheme','Selected scheme','0','FlyoutCategories',10,'select');
INSERT INTO xlite_config VALUES ('category_autoupdate','Automatically rebuild category menu layout','N','FlyoutCategories',20,'checkbox');
INSERT INTO xlite_config VALUES ('force_js_in_layout','Force JavaScript in the layout engine','Y','FlyoutCategories',25,'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('last_categories_processed', '', '0', 'FlyoutCategories', '0', 'text');

CREATE TABLE xlite_fcategories_schemes (
  scheme_id int(11) NOT NULL auto_increment,
  type char(1) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  order_by int(11) NOT NULL default '0',
  max_depth int(11) NOT NULL default '4',
  explorer int(11) NOT NULL default '0',
  templates varchar(255) NOT NULL default '',
  options text NOT NULL,
  PRIMARY KEY  (scheme_id),
  KEY name (name),
  KEY name_2 (name,order_by)
) TYPE=MyISAM;

ALTER TABLE xlite_categories ADD COLUMN smallimage mediumblob default NULL;
ALTER TABLE xlite_categories ADD COLUMN smallimage_source char(1) NOT NULL default 'D';
ALTER TABLE xlite_categories ADD COLUMN smallimage_type varchar(32) NOT NULL default '';
ALTER TABLE xlite_categories ADD COLUMN smallimage_auto int(1) NOT NULL default 1;

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('smallimage_width', 'Small category icon width (pixels)', '64', 'FlyoutCategories', '30', 'select');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('resize_quality', 'Small category icon resize quality', '95', 'FlyoutCategories', '40', 'select');


INSERT INTO xlite_fcategories_schemes VALUES (1,'','Icons',10,4,0,'skins/default/en/modules/FlyoutCategories/schemes/001_Icons','a:10:{s:9:\"cat_icons\";a:3:{s:4:\"type\";s:9:\"check_box\";s:11:\"description\";s:21:\"Show categories icons\";s:5:\"value\";s:1:\"1\";}s:12:\"subcat_icons\";a:3:{s:4:\"type\";s:9:\"check_box\";s:11:\"description\";s:32:\"Show subcategories icons (arrow)\";s:5:\"value\";s:1:\"1\";}s:13:\"rootcat_icons\";a:3:{s:4:\"type\";s:9:\"check_box\";s:11:\"description\";s:28:\"Show icons for root category\";s:5:\"value\";s:1:\"1\";}s:14:\"drop_direction\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:14:\"Drop direction\";s:6:\"points\";a:2:{i:0;s:4:\"left\";i:1;s:5:\"right\";}s:5:\"value\";s:5:\"right\";}s:10:\"drop_logic\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:10:\"Drop logic\";s:6:\"points\";a:2:{i:0;s:5:\"smart\";i:1;s:5:\"fixed\";}s:5:\"value\";s:5:\"smart\";}s:5:\"color\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:12:\"Select color\";s:6:\"points\";a:3:{i:0;s:4:\"blue\";i:1;s:4:\"grey\";i:2;s:6:\"yellow\";}s:5:\"value\";s:4:\"blue\";}s:11:\"popup_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:30:\"Category popup delay (seconds)\";s:5:\"value\";s:3:\"0.5\";}s:11:\"close_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:30:\"Category close delay (seconds)\";s:5:\"value\";s:3:\"1.0\";}s:19:\"rootcat_icons_width\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:36:\"Root categories icons width (pixels)\";s:6:\"points\";a:3:{i:0;s:2:\"16\";i:1;s:2:\"24\";i:2;s:2:\"32\";}s:5:\"value\";s:2:\"16\";}s:18:\"subcat_icons_width\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:34:\"Subcategories icons width (pixels)\";s:6:\"points\";a:3:{i:0;s:2:\"16\";i:1;s:2:\"24\";i:2;s:2:\"32\";}s:5:\"value\";s:2:\"16\";}}');
INSERT INTO xlite_fcategories_schemes VALUES (2,'0','Explorer',20,4,1,'skins/default/en/modules/FlyoutCategories/schemes/002_Explorer','a:1:{s:5:\"color\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:12:\"Select color\";s:6:\"points\";a:3:{i:0;s:4:\"grey\";i:1;s:7:\"classic\";i:2;s:4:\"blue\";}s:5:\"value\";s:4:\"grey\";}}');
INSERT INTO xlite_fcategories_schemes VALUES (3,'0','Horizontal Menu',30,4,0,'skins/default/en/modules/FlyoutCategories/schemes/003_Horizontal','a:5:{s:10:\"drop_logic\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:10:\"Drop logic\";s:6:\"points\";a:2:{i:0;s:5:\"smart\";i:1;s:5:\"fixed\";}s:5:\"value\";s:5:\"smart\";}s:5:\"color\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:12:\"Select color\";s:6:\"points\";a:3:{i:0;s:4:\"blue\";i:1;s:9:\"blue_grey\";i:2;s:11:\"yellow_grey\";}s:5:\"value\";s:4:\"blue\";}s:12:\"subcat_icons\";a:3:{s:4:\"type\";s:9:\"check_box\";s:11:\"description\";s:32:\"Show subcategories icons (arrow)\";s:5:\"value\";s:1:\"1\";}s:11:\"popup_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:27:\"Category popup delay (sec.)\";s:5:\"value\";s:3:\"0.5\";}s:11:\"close_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:27:\"Category close delay (sec.)\";s:5:\"value\";s:3:\"1.0\";}}');
INSERT INTO xlite_fcategories_schemes VALUES (4,'0','Candy',40,4,0,'skins/default/en/modules/FlyoutCategories/schemes/004_Candy','a:5:{s:14:\"drop_direction\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:14:\"Drop direction\";s:6:\"points\";a:2:{i:0;s:4:\"left\";i:1;s:5:\"right\";}s:5:\"value\";s:5:\"right\";}s:10:\"drop_logic\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:10:\"Drop logic\";s:6:\"points\";a:2:{i:0;s:5:\"smart\";i:1;s:5:\"fixed\";}s:5:\"value\";s:5:\"smart\";}s:5:\"color\";a:4:{s:4:\"type\";s:10:\"select_box\";s:11:\"description\";s:12:\"Select color\";s:6:\"points\";a:4:{i:0;s:10:\"curve_blue\";i:1;s:12:\"curve_yellow\";i:2;s:9:\"grey_blue\";i:3;s:6:\"yellow\";}s:5:\"value\";s:10:\"curve_blue\";}s:11:\"popup_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:27:\"Category popup delay (sec.)\";s:5:\"value\";s:3:\"0.5\";}s:11:\"close_delay\";a:3:{s:4:\"type\";s:8:\"text_box\";s:11:\"description\";s:27:\"Category close delay (sec.)\";s:5:\"value\";s:3:\"1.0\";}}');

