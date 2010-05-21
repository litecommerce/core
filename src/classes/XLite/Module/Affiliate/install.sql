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
        banner mediumblob NOT NULL,
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


INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('registration_enabled', 'New partner registration is enabled', 'Y', 'Affiliate', '5', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('moderated', 'New partner registration is moderated', 'N', 'Affiliate', '10', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('default_plan', 'Default affiliate plan', '', 'Affiliate', '20', 'select');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('tiers_number', 'Number of partnership tiers', '1', 'Affiliate', '30', 'select');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('tier_commission_rates', 'Tier commission rates', 'a:4:{i:2;s:4:"0.00";i:3;s:4:"0.00";i:4;s:4:"0.00";i:5;s:4:"0.00";}', 'Affiliate', '40', 'serialized');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('partner_cookie_lifetime', 'Cookie expiry period (days)', '7', 'Affiliate', '50', 'text');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('enable_advanced_banner', 'Enable product banners customization', 'Y', 'Affiliate', '60', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('enable_order_totals', 'Partners can see order totals', 'Y', 'Affiliate', '70', 'checkbox');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('partner_profile', '', 'a:8:{s:17:"billing_firstname";s:2:"on";s:16:"billing_lastname";s:2:"on";s:13:"billing_phone";s:2:"on";s:15:"billing_address";s:2:"on";s:12:"billing_city";s:2:"on";s:13:"billing_state";s:2:"on";s:15:"billing_country";s:2:"on";s:15:"billing_zipcode";s:2:"on";}', 'Miscellaneous', '0', 'serialized');
INSERT INTO xlite_config (name, comment, value, category, orderby, type) VALUES ('partner_product_banner', '', 'a:11:{s:11:"link_target";s:4:"_top";s:5:"image";s:9:"thumbnail";s:6:"border";i:1;s:12:"product_name";i:1;s:11:"description";s:0:"";s:7:"message";s:8:"Buy new!";s:16:"background_color";s:6:"ffffff";s:10:"text_color";s:6:"000000";s:10:"link_color";s:6:"6633ff";s:5:"width";i:120;s:6:"height";i:240;}', 'Miscellaneous', '0', 'serialized');


