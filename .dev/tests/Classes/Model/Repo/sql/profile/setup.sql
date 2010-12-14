DELETE FROM xlite_profile_addresses;
UPDATE xlite_orders SET profile_id = NULL, orig_profile_id = NULL;
UPDATE xlite_profiles SET order_id = NULL;
DELETE FROM xlite_profiles;

-- Create the admin account (password: master)
INSERT INTO xlite_profiles SET profile_id = 1, login = 'rnd_tester@cdev.ru', password = 'eb0a191797624dd3a48fa681d3061212', access_level = 100, added = UNIX_TIMESTAMP(NOW())-60*60*24*369, first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____';

INSERT INTO xlite_profile_addresses SET address_id = 1, profile_id = 1, is_billing = 1, is_shipping = 1, title = 'Mr.', firstname = 'Charly', lastname = 'Root', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';


-- Create the customer account #1 (guest)
INSERT INTO xlite_profiles SET profile_id = 2, login = 'rnd_tester@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, added = UNIX_TIMESTAMP(NOW())-60*60*24*5, first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____', referer = 'http://www.google.com/';

INSERT INTO xlite_profile_addresses SET address_id = 2, profile_id = 2, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'John', lastname = 'Smith', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 3, profile_id = 2, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'John', lastname = 'Smith', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 34, country_code = 'US', zipcode = '10001';

-- Create the customer account #2 (guest)
INSERT INTO xlite_profiles SET profile_id = 3, login = 'rnd_tester01@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, added = UNIX_TIMESTAMP(NOW())-60*60*24*2, first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____', referer = 'http://www.google.ru/', pending_membership_id = 2;

INSERT INTO xlite_profile_addresses SET address_id = 4, profile_id = 3, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'John Patrick', lastname = 'Smith', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 5, profile_id = 3, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'John Patrick', lastname = 'Smith', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 34, country_code = 'US', zipcode = '10001';


-- Create the customer account #3 (guest)
INSERT INTO xlite_profiles SET profile_id = 4, login = 'rnd_tester02@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____', membership_id = 2, language = 'de';

INSERT INTO xlite_profile_addresses SET address_id = 6, profile_id = 4, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'Patrick', lastname = 'Smith', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 7, profile_id = 4, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'Patrick', lastname = 'Smith', phone = '9876543210', street = '12 apt, 34 street', city = 'Paris', custom_state = 'custom state test', state_id = 0, country_code = 'FR', zipcode = '74359';


-- Create the customer account #4 (guest)
INSERT INTO xlite_profiles SET profile_id = 5, login = 'rnd_tester03@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, added = UNIX_TIMESTAMP(NOW())-60*60*24*32, first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'D', cms_profile_id = 1, cms_name = '____DRUPAL____', pending_membership_id = 1, membership_id = 2;

INSERT INTO xlite_profile_addresses SET address_id = 8, profile_id = 5, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 9, profile_id = 5, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 37, country_code = 'US', zipcode = '10001';


-- Create the customer account #5 (guest)
INSERT INTO xlite_profiles SET profile_id = 6, login = 'rnd_tester04@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____';

INSERT INTO xlite_profile_addresses SET address_id = 10, profile_id = 6, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 11, profile_id = 6, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 34, country_code = 'US', zipcode = '10001';


-- Create the admin account #2 (root)
INSERT INTO xlite_profiles SET profile_id = 7, login = 'rnd_tester05@rrf.ru', password = '63a9f0ea7bb98050796b649e85481845', access_level = 100, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____';

INSERT INTO xlite_profile_addresses SET address_id = 12, profile_id = 7, is_billing = 1, is_shipping = 0, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '0123456789', street = 'test street address', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

INSERT INTO xlite_profile_addresses SET address_id = 13, profile_id = 7, is_billing = 0, is_shipping = 1, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '9876543210', street = '12 apt, 34 street', city = 'New York', state_id = 34, country_code = 'US', zipcode = '10001';



-- Create the customer account #7 (guest) - related to the order
INSERT INTO xlite_profiles SET profile_id = 8, login = 'rnd_tester02@rrf.ru', password = '084e0343a0486ff05530df6c705c8bb4', access_level = 0, order_id = 1, added = UNIX_TIMESTAMP(NOW()), first_login = UNIX_TIMESTAMP(NOW()), last_login = UNIX_TIMESTAMP(NOW()), status = 'E', cms_profile_id = 1, cms_name = '____DRUPAL____';
UPDATE xlite_orders SET profile_id = 8 WHERE order_id = 1;

INSERT INTO xlite_profile_addresses SET address_id = 14, profile_id = 8, is_billing = 1, is_shipping = 1, title = 'Mr.', firstname = 'Guest', lastname = 'Guest', phone = '0123456789', street = '51 apt, 87 street', city = 'Edmond', state_id = 38, country_code = 'US', zipcode = '73003';

