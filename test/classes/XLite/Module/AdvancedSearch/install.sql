ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
ALTER TABLE xlite_profiles ADD search_settings varchar(255) NOT NULL default '';



