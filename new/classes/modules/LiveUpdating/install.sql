ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
CREATE TABLE xlite_updates (
	update_id CHAR(32) NOT NULL default '',
	type ENUM('core', 'module') NOT NULL default 'core',
	version VARCHAR(32) NOT NULL default '2.0',
	module_name VARCHAR(64) NOT NULL default'',
	description VARCHAR(255) NOT NULL default '',
	status enum('N','A','S') NOT NULL default 'N',
	importance enum('critical', 'bug fix', 'new feature') NOT NULL default 'bug fix',
	date int(11) NOT NULL default '0',
	applied int(11) NOT NULL default '0',
	dependencies TEXT NOT NULL,
	PRIMARY KEY (update_id),
	KEY (status),
	KEY (date),
	KEY (applied)
);

CREATE TABLE xlite_update_items (
	update_item_id int(11) unsigned NOT NULL auto_increment,
	server_item_id int(11) unsigned NOT NULL default 0,
	server_file_id int(11) unsigned NOT NULL default 0,
	update_id CHAR(32) NOT NULL default '',
	type ENUM('file','function','table','diff') NOT NULL DEFAULT 'file',
	update_data mediumblob,
	data_type enum('text', 'bin') NOT NULL default 'text',
	file_name text NOT NULL default '',
	file_new int(1) NOT NULL default '0',
	PRIMARY KEY (update_item_id),
	KEY (update_id),
	KEY (type)
);

CREATE TABLE xlite_update_items_files (
	update_item_id int(11) unsigned NOT NULL auto_increment,
	update_id CHAR(32) NOT NULL default '',
	filename VARCHAR(255) NOT NULL default '',
	PRIMARY KEY (update_item_id),
	KEY (update_id, filename)
);

INSERT INTO xlite_config VALUES ('filters_preferences', '', '', 'LiveUpdating', 1000, 'serialized');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('number_updates', 'Updates per page', '20', 'LiveUpdating', '10', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('auto_check', 'Automatically check for updates', 'Y', 'LiveUpdating', '20', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('modules_news', 'Display notifications when new module versions are available', 'Y', 'LiveUpdating', '25', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('use_ftp', 'Use FTP access to LiteCommerce files', 'Y', 'LiveUpdating', '30', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_host', 'FTP host', '', 'LiveUpdating', '40', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_port', 'FTP port', '21', 'LiveUpdating', '41', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_login', 'FTP login', '', 'LiveUpdating', '42', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_password', 'FTP password', '', 'LiveUpdating', '43', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_dir', 'FTP location of LiteCommerce root directory', '', 'LiveUpdating', '44', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('ftp_passive', 'Use FTP passive mode', 'N', 'LiveUpdating', '45', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('modules_last_update', '', '', 'LiveUpdating', '', 'serialized');
