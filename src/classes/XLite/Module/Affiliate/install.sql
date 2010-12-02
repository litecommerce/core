DROP TABLE IF EXISTS xlite_partner_fields;
CREATE TABLE xlite_partner_fields ( 
        field_id int(11) NOT NULL auto_increment,
        field_type varchar(16) NOT NULL default 'text',
        name varchar(255) NOT NULL default '',
        value text NOT NULL,
        cols int(11) NOT NULL default '25',
        rows int(11) NOT NULL default '4',
        orderby int(11) NOT NULL default '0',
        required int(1) NOT NULL default '1',
        enabled int(1) NOT NULL default '1',
        PRIMARY KEY (field_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_partner_plans;
CREATE TABLE xlite_partner_plans (
        plan_id int(11) NOT NULL auto_increment,
        title varchar(64) NOT NULL default '',
        payment_limit int(11) NOT NULL default '100',
        enabled int(1) NOT NULL default '1',
        PRIMARY KEY  (plan_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_partner_plan_commissions;
CREATE TABLE xlite_partner_plan_commissions (
        plan_id int(11) NOT NULL default '0',
        commission decimal(12,2) NOT NULL default '0.00',
        commission_type char(1) NOT NULL default '%',
        item_id int(11) NOT NULL default '0',
        item_type char(1) NOT NULL default 'B',
        PRIMARY KEY  (plan_id,item_id,item_type)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_banners;
CREATE TABLE xlite_banners (
        banner_id int(11) NOT NULL auto_increment,
        name varchar(255) NOT NULL default '',
        banner text NOT NULL,
        banner_source char(1) NOT NULL default 'D',
        banner_type varchar(32) NOT NULL default 'image/jpeg',
        type varchar(32) NOT NULL default 'image',
        body text NOT NULL,
        align varchar(16) NOT NULL default 'bottom',
        alt varchar(255) NOT NULL default '',
        link_target varchar(16) NOT NULL default '_blank',
        enabled int(1) NOT NULL default '1',
        width int(11) NOT NULL default '0',
        height int(11) NOT NULL default '0',
        banner_width int(11) NOT NULL default '0',
        banner_height int(11) NOT NULL default '0',
        PRIMARY KEY  (banner_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_banner_stats;
CREATE TABLE xlite_banner_stats (
        stat_id int(11) NOT NULL auto_increment,
        stat_type char(1) NOT NULL default 'V',
        partner_id int(11) NULL,
        banner_id int(11) NULL,
        product_id int(11) NULL,
        date int(11) NOT NULL default '0',
        referrer varchar(255) NOT NULL default '',
        PRIMARY KEY (stat_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_partner_payments;
CREATE TABLE xlite_partner_payments (
        payment_id int(11) NOT NULL auto_increment,
        partner_id int(11) NOT NULL default '0',
        order_id int(11) NOT NULL default '0',
        commissions decimal(12,2) NOT NULL default '0.00',
        paid int(1) NOT NULL default '0',
        add_date int(11) NOT NULL default '0',
        paid_date int(11) NOT NULL default '0',
        affiliate int(11) NOT NULL default '0',
        PRIMARY KEY  (payment_id),
        KEY payment_key (partner_id, order_id),
        KEY order_id (order_id),
        KEY affiliate (affiliate)
) TYPE=MyISAM;


ALTER TABLE xlite_profiles ADD parent int(11) NOT NULL default '0';
ALTER TABLE xlite_profiles ADD partner_fields TEXT NOT NULL default '';
ALTER TABLE xlite_profiles ADD plan int(11) NOT NULL default '0';
ALTER TABLE xlite_profiles ADD pending_plan int(11) NOT NULL default '0';
ALTER TABLE xlite_profiles ADD reason varchar(255) NOT NULL default '';
ALTER TABLE xlite_profiles ADD partner_signup int(11) NOT NULL default '0';

ALTER TABLE xlite_orders ADD partnerClick int(11) NOT NULL default '0';
ALTER TABLE xlite_order_items ADD commissions decimal(12,2) NOT NULL default '0.00';

INSERT INTO `xlite_config` VALUES (73,'default_plan','Affiliate','select',20,'');
INSERT INTO `xlite_config` VALUES (99,'enable_advanced_banner','Affiliate','checkbox',60,'Y');
INSERT INTO `xlite_config` VALUES (106,'enable_order_totals','Affiliate','checkbox',70,'Y');
INSERT INTO `xlite_config` VALUES (171,'moderated','Affiliate','checkbox',10,'N');
INSERT INTO `xlite_config` VALUES (198,'partner_cookie_lifetime','Affiliate','text',50,'7');
INSERT INTO `xlite_config` VALUES (218,'registration_enabled','Affiliate','checkbox',5,'Y');
INSERT INTO `xlite_config` VALUES (253,'tiers_number','Affiliate','select',30,'1');
INSERT INTO `xlite_config` VALUES (254,'tier_commission_rates','Affiliate','serialized',40,'a:4:{i:2;s:4:\"0.00\";i:3;s:4:\"0.00\";i:4;s:4:\"0.00\";i:5;s:4:\"0.00\";}');

INSERT INTO `xlite_config_translations` VALUES (59,'en',73,'Default affiliate plan','');
INSERT INTO `xlite_config_translations` VALUES (75,'en',99,'Enable product banners customization','');
INSERT INTO `xlite_config_translations` VALUES (82,'en',106,'Partners can see order totals','');
INSERT INTO `xlite_config_translations` VALUES (127,'en',171,'New partner registration is moderated','');
INSERT INTO `xlite_config_translations` VALUES (148,'en',198,'Cookie expiry period (days)','');
INSERT INTO `xlite_config_translations` VALUES (163,'en',218,'New partner registration is enabled','');
INSERT INTO `xlite_config_translations` VALUES (192,'en',253,'Number of partnership tiers','');
INSERT INTO `xlite_config_translations` VALUES (193,'en',254,'Tier commission rates','');

