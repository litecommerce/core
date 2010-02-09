ALTER TABLE xlite_profiles ADD drupal_uid int(10) unsigned NOT NULL default 0;
ALTER TABLE xlite_profiles ADD INDEX drupal_uid(drupal_uid);
