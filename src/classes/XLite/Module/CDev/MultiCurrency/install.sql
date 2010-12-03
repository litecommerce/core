DROP TABLE IF EXISTS xlite_country_currencies;
CREATE TABLE xlite_country_currencies (
	currency_id 	int(11) 		auto_increment,
	code 			varchar(3)		NOT NULL default '',
	name			varchar(50)		NOT NULL default '',
	exchange_rate	decimal(12,4)	NOT NULL default '0.00',
	price_format	varchar(50)		NOT NULL default '$ %s',
	base			int(1)			NOT NULL default '0',
	enabled			int(1)			NOT NULL default '0',
	order_by		int(11)			NOT NULL default '0',
	countries		text			NOT NULL default '',
	PRIMARY KEY (currency_id),
    KEY orderby (order_by)
) TYPE=MyISAM;

INSERT INTO `xlite_config` VALUES (57,'country_currency','MultiCurrency','checkbox',10,'Y');

INSERT INTO `xlite_config_translations` VALUES (47,'en',57,'Display default currency & customer\'s national currency only ( [*] this option does not have effect when a customer is not logged in)','');

