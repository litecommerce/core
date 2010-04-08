DROP TABLE IF EXISTS xlite_order_history;
CREATE TABLE xlite_order_history(
	order_history_id int(11) NOT NULL auto_increment,
	order_id int(11) NOT NULL default '0',
	date int(11) default NULL,
	login varchar(255) NOT NULL default '',	
	changes text,
	secureChanges text,
	PRIMARY KEY (order_history_id),
	KEY order_id (order_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_order_statuses;
CREATE TABLE xlite_order_statuses(
	status_id int(11) NOT NULL auto_increment,	
	status	char(1) unique,
	name	varchar(255) default '',
	notes	text,
	parent	char(1) NOT NULL default '',
	orderby int(11) NOT NULL default '0',
	email int(1) NOT NULL default '1',
	cust_email int(1) NOT NULL default '1',
	PRIMARY KEY (status_id)	
) TYPE = MyISAM;

INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('Q','Queued','Queued','', 10);
INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('P','Processed','Processed','', 20);
INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('I','Incomplete','Incomplete','', 30);
INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('F','Failed','Failed','', 40);
INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('D','Declined','Declined','', 50);
INSERT INTO xlite_order_statuses (status, name, notes, parent, orderby) VALUES ('C','Completed','Completed','', 60);

ALTER TABLE xlite_orders ADD substatus char(1) default '';
ALTER TABLE xlite_orders ADD admin_notes text;
ALTER TABLE xlite_orders ADD COLUMN manual_edit int(1) NOT NULL default '0';

ALTER TABLE xlite_order_items ADD COLUMN aom_extra text NOT NULL default '';

INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('clone_silent', 'Do not send order status change notifications during the "clone order" procedure', 'Y', 'AOM', '10', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('cc_info_history', 'Store Credit Card info in order history', 'N', 'AOM', '20', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('order_update_notification', 'When an order is modified e-mail order details to', '', 'AOM', '30', 'serialized');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('status_inheritance', 'Enable status inheritance', 'N', 'AOM', '15', 'checkbox');
