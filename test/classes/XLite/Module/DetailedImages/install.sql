ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
CREATE TABLE xlite_images (
        image_id int(11) NOT NULL auto_increment,
        product_id int(11) NOT NULL default '0',
        image mediumblob,
        image_source char(1) NOT NULL default 'D',
        image_type varchar(32) NOT NULL default 'image/jpeg',
        alt varchar(255) NOT NULL default '',
        enabled int(1) NOT NULL default '1',
        order_by int(11) NOT NULL default '0',
		is_zoom char(1) NOT NULL default '',
        PRIMARY KEY  (image_id),
		KEY pz (product_id, is_zoom)
) TYPE=MyISAM;
ALTER TABLE xlite_images MODIFY image mediumblob;
