ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config VALUES ('qb_date_format','Date format (for QuickBooks files)','%m/%d/%Y','AccountingPackage',10,'text');
