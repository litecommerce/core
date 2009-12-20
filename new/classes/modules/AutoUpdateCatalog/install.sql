ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config VALUES ('confirm_category_update','Confirm category page(s) update','Y','AutoUpdateCatalog',10,'checkbox');
INSERT INTO xlite_config VALUES ('confirm_product_update','Confirm product page(s) update','N','AutoUpdateCatalog',20,'checkbox');
