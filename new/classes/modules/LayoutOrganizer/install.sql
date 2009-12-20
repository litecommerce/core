ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'template', 'Products list look&feel', 'modules/LayoutOrganizer/list.tpl', 'LayoutOrganizer', 10, 'select');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'columns', 'Number of columns', 3, 'LayoutOrganizer', 20, 'select');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'show_description', 'Show product description', 'Y', 'LayoutOrganizer', 30, 'checkbox');
INSERT INTO xlite_config ( name , comment , value , category , orderby , type ) VALUES ( 'show_price', 'Show product price', 'Y', 'LayoutOrganizer', 40, 'checkbox');

ALTER TABLE xlite_categories ADD custom_template INT(11) NOT NULL default '-1';
ALTER TABLE xlite_categories ADD custom_template_enabled CHAR(1) NOT NULL default '1';
ALTER TABLE xlite_categories ADD template_name VARCHAR(255) NOT NULL default '';
ALTER TABLE xlite_categories ADD sc_custom_template INT(11) NOT NULL default '-1';
ALTER TABLE xlite_categories ADD sc_custom_template_enabled CHAR(1) NOT NULL default '1';
ALTER TABLE xlite_categories ADD sc_template_name VARCHAR(255) NOT NULL default '';
ALTER TABLE xlite_categories ADD p_custom_template INT(11) NOT NULL default '-1';
ALTER TABLE xlite_categories ADD p_custom_template_enabled CHAR(1) NOT NULL default '1';
ALTER TABLE xlite_categories ADD p_template_name VARCHAR(255) NOT NULL default '';

ALTER TABLE xlite_products ADD custom_template INT(11) NOT NULL default '-1';
ALTER TABLE xlite_products ADD custom_template_enabled CHAR(1) NOT NULL default '1';
ALTER TABLE xlite_products ADD template_name VARCHAR(255) NOT NULL default '';

CREATE TABLE xlite_templates_schemes (
    scheme_id int(11) NOT NULL auto_increment,
    type char(1) NOT NULL default '0',
    name varchar(255) NOT NULL default '',
    order_by int(11) NOT NULL default '0',
    enabled int(1) NOT NULL default '1',
    cat_template varchar(255) NOT NULL default '',
    scat_template varchar(255) NOT NULL default '',
    prod_template varchar(255) NOT NULL default '',
    PRIMARY KEY  (scheme_id),
    KEY name (name),
    KEY name_2 (name,order_by,enabled)
);

INSERT INTO xlite_templates_schemes (scheme_id, type, name, order_by, enabled, cat_template, scat_template, prod_template) VALUES (1,'0','Forest Green',1,1,'skins/default/en/modules/LayoutOrganizer/schemes/scheme001_Forest_Green/cat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme001_Forest_Green/scat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme001_Forest_Green/prod_template.tpl');
INSERT INTO xlite_templates_schemes (scheme_id, type, name, order_by, enabled, cat_template, scat_template, prod_template) VALUES (2,'0','Soap Bubbles',2,1,'skins/default/en/modules/LayoutOrganizer/schemes/scheme002_Soap_Bubbles/cat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme002_Soap_Bubbles/scat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme002_Soap_Bubbles/prod_template.tpl');
INSERT INTO xlite_templates_schemes (scheme_id, type, name, order_by, enabled, cat_template, scat_template, prod_template) VALUES (3,'0','Film Tape',3,1,'skins/default/en/modules/LayoutOrganizer/schemes/scheme003_Film_Tape/cat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme003_Film_Tape/scat_template.tpl','skins/default/en/modules/LayoutOrganizer/schemes/scheme003_Film_Tape/prod_template.tpl');
