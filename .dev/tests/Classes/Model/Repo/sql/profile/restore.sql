DELETE FROM xlite_profiles;
DELETE FROM xlite_profile_addresses;

-- Create the default account: master / master
REPLACE INTO xlite_profiles SET profile_id = 1, login = 'rnd_tester@cdev.ru', password = 'eb0a191797624dd3a48fa681d3061212', access_level = 100, order_id = 0, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____';

REPLACE INTO xlite_profile_addresses SET address_id = 1, profile_id = 1, is_billing = 1, is_shipping = 1, title = 'Mr.', firstname = 'Admin', lastname = 'Admin', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';


-- Create the default account: guest / guest
REPLACE INTO xlite_profiles SET profile_id = 2, login = 'rnd_tester@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, order_id = 0, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 2, cms_name = '____DRUPAL____';

REPLACE INTO xlite_profile_addresses SET address_id = 2, profile_id = 2, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

REPLACE INTO xlite_profile_addresses SET address_id = 3, profile_id = 2, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 34, country_code = 'US', zipcode = '10001';


-- Create the account related to order #1
REPLACE INTO xlite_profiles SET profile_id = 3, login = 'rnd_tester@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, order_id = 1, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 2, cms_name = '____DRUPAL____';

REPLACE INTO xlite_profile_addresses SET address_id = 4, profile_id = 3, is_billing = 1, is_shipping = 1, title = 'Mr.', firstname = 'Admin', lastname = 'Admin', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

-- Create the account related to order #2
REPLACE INTO xlite_profiles SET profile_id = 4, login = 'rnd_tester@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, order_id = 2, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 2, cms_name = '____DRUPAL____';

REPLACE INTO xlite_profile_addresses SET address_id = 5, profile_id = 4, is_billing = 1, is_shipping = 1, title = 'Mr.', firstname = 'Admin', lastname = 'Admin', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

