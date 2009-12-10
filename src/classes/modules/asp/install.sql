drop table if exists xlite_asp_shops;
create table xlite_asp_shops (
    id int not null primary key auto_increment,
    url varchar(255) not null,
    path varchar(255) not null,
    enabled int default 1,
    profile varchar(32) not null default ''
);
alter table xlite_asp_shops add secure_url varchar(255) not null default '';
ALTER TABLE xlite_asp_shops ADD COLUMN memory_limit varchar(12) NOT NULL default '16M';
ALTER TABLE xlite_asp_shops ADD COLUMN name varchar(32) NOT NULL default '';
ALTER TABLE xlite_asp_shops ADD COLUMN db_name text NOT NULL default '';
ALTER TABLE xlite_asp_shops ADD COLUMN db_username text NOT NULL default '';
ALTER TABLE xlite_asp_shops ADD COLUMN db_password text NOT NULL default '';

drop table if exists xlite_asp_profiles;
create table xlite_asp_profiles (
    name varchar(32) not null default '',
    rules varchar(255) not null default ''
);


drop table if exists xlite_asp_modules;
create table xlite_asp_modules (
    shop_id int not null,
    module varchar(64) not null,
    primary key (shop_id, module)
);


drop table if exists xlite_asp_config;
CREATE TABLE xlite_asp_config (
  name varchar(32) NOT NULL default '',
  comment varchar(255) NOT NULL default '',
  value text NOT NULL,
  category varchar(32) NOT NULL default '',
  orderby int(11) NOT NULL default '0',
  type enum('text','textarea','checkbox','country','state','select','serialized') default NULL,
  PRIMARY KEY  (category,name),
  KEY orderby (orderby)
) TYPE=MyISAM;

insert into xlite_asp_profiles(name,rules) values('web based','template_editor,db_backup,shop_backup,css_edit,image_edit,import_users,batch_product,image_files_fs,image_files_db');
insert into xlite_asp_profiles(name,rules) values('advanced','wysiwyg,template_editor,db_backup,shop_backup,css_edit,image_edit,import_users,batch_product,image_files_fs,image_files_db');
-- insert into xlite_asp_config(root_user,root_password) values('','');
INSERT INTO xlite_asp_config VALUES ('cp_security','Access Control Center via HTTPS','N','Security',0,'checkbox');
UPDATE xlite_asp_config SET comment= 'Access Control Center via HTTPS' WHERE category='Security' AND name='cp_security';
INSERT INTO xlite_asp_config (name, comment, value, category, orderby, type) VALUES ('shops_per_page', 'Shops per page', '10', 'LookFeel', '10', 'text');

-- use images from file system
update xlite_products set image=concat('pi_',product_id,'.gif'),image_source='F' where image_source='D';
update xlite_products set thumbnail=concat('pt_',product_id,'.gif'),thumbnail_source='F' where thumbnail_source='D';
update xlite_categories set image=concat('ci_',category_id,'.gif'),image_source='F' where image_source='D';

