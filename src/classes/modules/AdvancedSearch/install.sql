ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
ALTER TABLE xlite_profiles ADD search_settings varchar(255) NOT NULL default '';
INSERT INTO xlite_config VALUES ('prices','Price ranges','a:0:{}','AdvancedSearch',10, '');
INSERT INTO xlite_config VALUES ('weights','Weight ranges','a:0:{}','AdvancedSearch', 20, '');



