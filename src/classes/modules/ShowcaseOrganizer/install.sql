ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'template', 'Products list look&feel', 'modules/ShowcaseOrganizer/list.tpl', 'ShowcaseOrganizer', 10, 'select');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'so_columns', 'Columns number', 1, 'ShowcaseOrganizer', 20, 'select');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'so_show_description', 'Show product description', 'Y', 'ShowcaseOrganizer', 30, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'so_show_price', 'Show product price', 'Y', 'ShowcaseOrganizer', 40, 'checkbox');
