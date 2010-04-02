-- Create the default account
REPLACE INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id, cms_name) VALUES (1,'rnd_tester@cdev.ru','fe01ce2a7fbac8fafaed7c982a04e229',100,0,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1058449247,'E',1,'____DRUPAL____');
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id, cms_name) VALUES (2,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',0,0,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',2,'____DRUPAL____');
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (3,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,1,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (4,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,2,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (5,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,3,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (6,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,4,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (7,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,5,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);
INSERT INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id) VALUES (8,'demo@litecommerce.com','fe01ce2a7fbac8fafaed7c982a04e229',100,6,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1063630489,'E',0);

-- Update config table
UPDATE xlite_config SET comment='Check this to temporary close the shop (not available in Demo store)' WHERE name='shop_closed';
UPDATE xlite_config SET value='Y' WHERE name='customer_security';
UPDATE xlite_config SET value="http://sns.qualiteam.biz/litecommerce" WHERE name="collectorURL";
UPDATE xlite_config SET value="https://sns.qualiteam.biz/litecommerce" WHERE name="collectorHTTPSURL";
UPDATE xlite_config SET value = 'Y' WHERE name = 'enable_sale_price';
UPDATE xlite_config SET value = 'Y' WHERE name = 'you_save';
REPLACE INTO `xlite_config` VALUES ('memberships','Membership levels','a:0:{}','Memberships',0,'serialized');
REPLACE INTO `xlite_config` VALUES ('membershipsCollection','Membership levels','a:3:{i:1;a:3:{s:7:\"orderby\";s:2:\"10\";s:10:\"membership\";s:4:\"Gold\";s:13:\"membership_id\";i:1;}i:2;a:3:{s:7:\"orderby\";s:2:\"20\";s:10:\"membership\";s:8:\"Platinum\";s:13:\"membership_id\";i:2;}i:3;a:3:{s:7:\"orderby\";s:2:\"30\";s:10:\"membership\";s:10:\"Wholesaler\";s:13:\"membership_id\";i:3;}}','Memberships',0,'serialized');


-- Enable demo modules
--TODO - revert after develop period UPDATE xlite_modules SET enabled=1 where name='DemoMode';
UPDATE xlite_modules SET enabled = '1' WHERE name IN ('Affiliate', 'Bestsellers', 'FeaturedProducts', 'DetailedImages', 'ProductOptions', 'InventoryTracking', 'WishList', 'MultiCategories', 'DrupalConnector', 'JoomlaConnector', 'GiftCertificates', 'ProductAdviser', 'AdvancedSearch', 'WholesaleTrading');

-- Configure look and feel for category products
UPDATE xlite_config SET value='modules/ShowcaseOrganizer/icons.tpl' WHERE category='ShowcaseOrganizer' AND name='template';
UPDATE xlite_config SET value='2' WHERE category='ShowcaseOrganizer' AND name='so_columns';

-- Update categories (shorten it to 4 enabled)
UPDATE xlite_categories SET enabled=0 WHERE category_id=62;
UPDATE xlite_categories SET enabled=0 WHERE category_id=75;
UPDATE xlite_categories SET enabled=0 WHERE category_id=103;
UPDATE xlite_categories SET enabled=0 WHERE category_id=108;
UPDATE xlite_categories SET enabled=0 WHERE category_id=4;

-- Update product inventory cards
INSERT INTO xlite_inventories (inventory_id, amount, low_avail_limit, enabled) VALUES ('69|Cover:Soft',100,10,1);
INSERT INTO xlite_inventories (inventory_id, amount, low_avail_limit, enabled) VALUES ('16128|Size:S|Color:White',100,10, 1);
INSERT INTO xlite_inventories (inventory_id, amount, low_avail_limit, enabled) VALUES ('16128|Size:S|Color:Blue',150,10, 1);
INSERT INTO xlite_inventories (inventory_id, amount, low_avail_limit, enabled) VALUES ('205',100,10,1);
INSERT INTO xlite_inventories (inventory_id, amount, low_avail_limit, enabled) VALUES ('205|Size:M',50,10,1);

-- Inser demo order data (also used as the Best selling products products)
INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (3,1,521.43,521.43,0.00,0.00,'',UNIX_TIMESTAMP(NOW()),'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',1,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('144|Cover:Soft',1,0,144,59.99,1,'a:1:{i:0;O:8:\"stdClass\":6:{s:5:\"class\";s:5:\"Cover\";s:9:\"option_id\";i:0;s:6:\"option\";s:4:\"Soft\";s:9:\"surcharge\";s:1:\"0\";s:6:\"isZero\";b:1;s:8:\"absolute\";b:1;}}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('147|Cover:Soft',1,1,147,59.99,1,'a:1:{i:0;O:8:\"stdClass\":6:{s:5:\"class\";s:5:\"Cover\";s:9:\"option_id\";i:0;s:6:\"option\";s:4:\"Soft\";s:9:\"surcharge\";s:1:\"0\";s:6:\"isZero\";b:1;s:8:\"absolute\";b:1;}}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('154|Size:S|Color:White',1,2,154,39.5,1,'a:2:{i:0;O:8:\"stdClass\":6:{s:5:\"class\";s:4:\"Size\";s:9:\"option_id\";i:0;s:6:\"option\";s:1:\"S\";s:9:\"surcharge\";s:1:\"0\";s:6:\"isZero\";b:1;s:8:\"absolute\";b:1;}i:1;O:8:\"stdClass\":6:{s:5:\"class\";s:5:\"Color\";s:9:\"option_id\";i:0;s:6:\"option\";s:5:\"White\";s:9:\"surcharge\";s:1:\"0\";s:6:\"isZero\";b:1;s:8:\"absolute\";b:1;}}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('205',1,3,205,41.95,1,'');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',1,4,174,320,1,'');

INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (4,1,320,320,0.00,0.00,'',UNIX_TIMESTAMP(NOW())+60,'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',2,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',2,4,174,320,1,'');

INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (5,1,320,320,0.00,0.00,'',UNIX_TIMESTAMP(NOW())+120,'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',3,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',3,4,174,320,1,'');

INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (6,1,320,320,0.00,0.00,'',UNIX_TIMESTAMP(NOW())+180,'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',4,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',4,4,174,320,1,'');

INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (7,1,320,320,0.00,0.00,'',UNIX_TIMESTAMP(NOW())+240,'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',5,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',5,4,174,320,1,'');

INSERT INTO xlite_orders (profile_id, orig_profile_id, total, subtotal, shipping_cost, tax, tracking, date, status, payment_method, details, notes, order_id, shipping_id, detail_labels, taxes) VALUES (8,1,320,320,0.00,0.00,'',UNIX_TIMESTAMP(NOW())+300,'P','PhoneOrdering','O:8:\"stdClass\":0:{}','',6,2,'O:8:\"stdClass\":0:{}','a:1:{s:3:\"Tax\";d:0;}');
INSERT INTO xlite_order_items (item_id, order_id, orderby, product_id, price, amount, options) VALUES ('174',6,4,174,320,1,'');



-- use images from file system
update xlite_products set image=concat('pi_',product_id,'.',substring(image_type, 7)),image_source='F' where image_source='D';
update xlite_products set thumbnail=concat('pt_',product_id,'.', substring(thumbnail_type, 7)),thumbnail_source='F' where thumbnail_source='D';
update xlite_categories set image=concat('ci_',category_id,'.', substring(image_type, 7)),image_source='F' where image_source='D';

REPLACE INTO xlite_images VALUES (1,205,'di_205.jpeg','F','image/jpeg','Beatles "Vintage Logo" T-shirt', 1, 1,'Y');
REPLACE INTO xlite_images VALUES (2,205,'di_206.jpeg','F','image/jpeg','Beatles "Vintage Logo" T-shirt #2', 1, 2,'');
REPLACE INTO xlite_images VALUES (3,205,'di_207.jpeg','F','image/jpeg','Beatles "Vintage Logo" T-shirt #3', 1, 3,'');

INSERT INTO xlite_partner_plans VALUES (1, 'TestPlan', 100, 1);
INSERT INTO xlite_partner_plan_commissions VALUES (1, '2.00', '%', 144, 'P');
INSERT INTO xlite_partner_plan_commissions VALUES (1, '3.00', '%', 23, 'C');
INSERT INTO xlite_banners VALUES (1, 'GraphBanner', 'GIF89an\0\0’\0\0DEF ¥ÜˇÈ∫ãîö‰Àì•±∫cccˇÓ…ˇÈ∑Ó”ôWWW€√é√ØÇ—ªâôãlˇˇˇ¿œŸVY[SZ^^be;;;pvzyÄÖÇäê∑≈œù®Ø}ÖäˇÚ’Æª≈\\`bhlpìû§ùùùMOP◊◊◊ﬂ∆êkkkÊ÷≥˘Ó’RTU‰ÿΩ∞∞∞∂´ìˇÌ≈ÿ¡åˇÒ“§óy„—®ªß~[^_â~dÃ∑á‘ΩãÛ◊õ∞ûx µàÕ∏áËŒï™ôuŸ¬ç˜‹†˘ﬂß”ºä˛‰´!˘\0\0\0\0\0,\0\0\0\0n\0\0\0ˇ@àpH,\Zè»§r…l:ü–®tJÖ4\0†£)Tø‡rq›#Ü„4HNﬁìd\0ü¥/»\'…ÎmpÇoÖpDuàbD\'/=	;;\0mGC§§•zrêß®B¶Eßè|≤®ªïC1%+?59\r8\'^F\0ÉØå≤¨¨œ∞u!ØoÕ∞rBŸqB!Õæ\Z*-ƒ<	#\r>\Z£¥“≠¢`·*0!í¿hB.àB»`•(6º˚AÅ\Z¿Ëêèà°CB\n·6NõS9‘A\'•í q©gB\"\nP`ëqÄˇô@É:´y‡fN\n22¯)¥©∫ú$\"æ√©3i\0$rj› µ´◊Ø`√äK6ßêú ⁄M= 3Äe„ ùKwÏŸ\"@µ\"\nD‘L∏pÿªR(»‘˜h≈Æ$pçúì≤ÿ»ò\rkã8±çu:ﬁjY´Â“_1´ﬁÃzkgº B7¶˙\0ıd…aK€nm±_—¿eW∆mZÚÍ⁄ôã+G~zur’∆u?üN¸∏Ô—^C€¶Ï˙r“ﬁ°áüY<ÛËÂΩ_?*ñÍv‹Õá_Œæ}‰ÚÒ«óÆˇ~Á–`πG\\~ÕáﬂmÚ≈◊ÅË•7üyØ·D€W\"x†y˘-®·~˜ı◊s›ÉÍA¿’Ñf»†âﬂ’ó`á*^ùÖ⁄ˆ\Zpa	¯!sÁI7ﬁçûhûéı≠6#â]—ˆ£}Hòcq\nˆË‚p\nB…$n3“òoXfiñàE÷®Âó¨UiÂVDÇiÊ\\bñ9Êôlñï¶_p>÷Êún:eÁùE\0;', 'D', 'image/gif', 'image', 'Test banner', 'top', 'Test banner', '_blank', 1, 0, 0, 0, 0);
INSERT INTO xlite_banners VALUES (2, 'MediaRichBanner', 'GIF89an\0\0’\0\0DEF ¥ÜˇÈ∫ãîö‰Àì•±∫cccˇÓ…ˇÈ∑Ó”ôWWW€√é√ØÇ—ªâôãlˇˇˇ¿œŸVY[SZ^^be;;;pvzyÄÖÇäê∑≈œù®Ø}ÖäˇÚ’Æª≈\\`bhlpìû§ùùùMOP◊◊◊ﬂ∆êkkkÊ÷≥˘Ó’RTU‰ÿΩ∞∞∞∂´ìˇÌ≈ÿ¡åˇÒ“§óy„—®ªß~[^_â~dÃ∑á‘ΩãÛ◊õ∞ûx µàÕ∏áËŒï™ôuŸ¬ç˜‹†˘ﬂß”ºä˛‰´!˘\0\0\0\0\0,\0\0\0\0n\0\0\0ˇ@àpH,\Zè»§r…l:ü–®tJÖ4\0†£)Tø‡rq›#Ü„4HNﬁìd\0ü¥/»\'…ÎmpÇoÖpDuàbD\'/=	;;\0mGC§§•zrêß®B¶Eßè|≤®ªïC1%+?59\r8\'^F\0ÉØå≤¨¨œ∞u!ØoÕ∞rBŸqB!Õæ\Z*-ƒ<	#\r>\Z£¥“≠¢`·*0!í¿hB.àB»`•(6º˚AÅ\Z¿Ëêèà°CB\n·6NõS9‘A\'•í q©gB\"\nP`ëqÄˇô@É:´y‡fN\n22¯)¥©∫ú$\"æ√©3i\0$rj› µ´◊Ø`√äK6ßêú ⁄M= 3Äe„ ùKwÏŸ\"@µ\"\nD‘L∏pÿªR(»‘˜h≈Æ$pçúì≤ÿ»ò\rkã8±çu:ﬁjY´Â“_1´ﬁÃzkgº B7¶˙\0ıd…aK€nm±_—¿eW∆mZÚÍ⁄ôã+G~zur’∆u?üN¸∏Ô—^C€¶Ï˙r“ﬁ°áüY<ÛËÂΩ_?*ñÍv‹Õá_Œæ}‰ÚÒ«óÆˇ~Á–`πG\\~ÕáﬂmÚ≈◊ÅË•7üyØ·D€W\"x†y˘-®·~˜ı◊s›ÉÍA¿’Ñf»†âﬂ’ó`á*^ùÖ⁄ˆ\Zpa	¯!sÁI7ﬁçûhûéı≠6#â]—ˆ£}Hòcq\nˆË‚p\nB…$n3“òoXfiñàE÷®Âó¨UiÂVDÇiÊ\\bñ9Êôlñï¶_p>÷Êún:eÁùE\0;', 'D', 'image/gif', 'rich', '<TABLE cellspacing=1 cellpadding=0 border=0 bgcolor=#0000ff>\r\n<TR>\r\n<TD>[url][obj][/url]</TD>\r\n</TR>\r\n<TR>\r\n<TD bgcolor=#0000FF align=center>[url]<FONT color=#FFFFFF>TestBanner</FONT>[/url]</TD>\r\n</TR>\r\n</TABLE>', 'bottom', '', '_blank', 1, 0, 0, 0, 0);

TRUNCATE xlite_newslists;
INSERT INTO xlite_newslists (list_id, name, description, show_as_news, enabled) VALUES (1,'News List #1','LiteCommerce News',1,1);

TRUNCATE xlite_newsletters;
INSERT INTO xlite_newsletters (news_id, subject, body, send_date, list_id) VALUES (1,'\"INVENTORY TRACKING\" AND \"PRODUCT OPTIONS\" ADD-ONS ARE IMPROVED.','<P align=justify>\r\nRequested functionality is available now! We made those add-ons more stable and added new hot features.\r\n<BR><BR>\r\nThe following fetuares were added to the \"<a href=\"http://www.litecommerce.com/addon.html?addon=inventory\"><u>InventoryTracking</u></a>\" add-on:\r\n<UL>\r\n<LI>the ability to clone inventory data of a product during the cloning of the product;\r\n<LI>the ability to track products with defined product options without tracking the product options.\r\n</UL>\r\nThe following fetuares were added to the \"<a  href=\"http://www.litecommerce.com/addon.html?addon=options\"><u>ProductOptions</u></a>\" add-on:\r\n<UL>\r\n<LI>the ability to clone product options of a product during the cloning of the product;\r\n<LI>now all inventory tracking data will be recalculated automatically when admin changes/deletes a product option class or  a product options list.\r\n</UL>\r\n</P>\r\n<BR>\r\n',1110528360,1);
INSERT INTO xlite_newsletters (news_id, subject, body, send_date, list_id) VALUES (2,'LITECOMMERCE SECURITY PRODUCTS','<P>\r\nTo establish secure and confidential online transactions within your e-store and to increase the level of trust of your customers it is highly recommended to utilize SSL certificates and TrustLogo seals on every e-commerce web site.\r\n<P>\r\nWe are glad to inform you that we are now offering SSL certificates and TrustLogos for sale. These products are provided by the world leading Certification Authority - <A href=\"http://www.comodogroup.com\" rel=\"nofollow\">Comodo Group</A>. For details, conditions and prices, please visit <A href=\"http://www.litecommerce.com/ssl_certificates.html\"><u>SSL certificates page</u></A> and <A href=\"http://www.litecommerce.com/trustlogo.html\"><u>TrustLogo page</u></A>. We target to provide you with up-to-date security technologies in order to make your online business with LiteCommerce profitable and perfect.\r\n</P>\r\n',1111305960,1);

TRUNCATE xlite_news_subscribers;
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user1@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user2@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user16@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user17@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user18@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user19@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user20@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user21@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user22@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user23@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user24@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user25@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user26@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user27@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user28@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user29@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user160@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user161@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user162@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user163@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user164@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user165@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user166@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user167@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user168@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user169@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user170@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user171@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user172@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user173@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user174@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user175@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user176@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user177@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user178@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user179@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user180@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user181@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user182@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user183@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user184@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user185@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user186@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user187@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user188@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user189@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user190@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user191@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user192@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user193@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user194@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user195@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user196@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user197@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user198@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user199@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user201@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user202@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user203@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user204@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user205@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user206@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user207@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user208@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user209@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user285@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user284@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user283@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user282@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user281@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user280@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user279@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user278@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user277@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user276@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user275@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user274@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user273@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user272@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user271@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user270@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user269@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user268@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user267@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user266@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user265@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user264@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user263@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user262@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user261@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user260@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user259@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user258@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user257@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user256@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user255@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user254@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user253@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user252@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user251@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user250@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user249@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user248@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user247@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user246@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user245@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user244@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user243@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user242@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user241@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user240@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user239@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user238@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user237@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user236@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user235@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user234@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user233@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user232@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user231@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user230@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user229@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user228@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user227@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user226@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user225@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user224@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user223@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user222@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user221@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user220@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user219@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user218@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user217@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user216@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user215@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user214@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user213@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user212@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user211@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user210@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user286@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user287@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user288@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user289@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user290@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user291@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user292@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user298@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user297@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user296@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user295@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user294@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user293@example.com',1100546388);
INSERT INTO xlite_news_subscribers (list_id, email, since_date) VALUES (1,'user299@example.com',1100546388);

-- Dump for ProductAdviser module
truncate xlite_products_new_arrivals;
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (241, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (120, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (163, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (174, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (119, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());
INSERT INTO xlite_products_new_arrivals (product_id, added, new, updated) VALUES (165, UNIX_TIMESTAMP(), 'Y', UNIX_TIMESTAMP());

TRUNCATE xlite_related_products;
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '119', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '165', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '241', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '171', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '172', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '173', 10);
INSERT INTO xlite_related_products (product_id, related_product_id, order_by) VALUES ('174', '177', 10);

TRUNCATE xlite_products_also_buy;
INSERT INTO xlite_products_also_buy (product_id, product_id_also_buy, counter) VALUES ('174', '171', 3);
INSERT INTO xlite_products_also_buy (product_id, product_id_also_buy, counter) VALUES ('174', '172', 5);
INSERT INTO xlite_products_also_buy (product_id, product_id_also_buy, counter) VALUES ('174', '177', 1);

TRUNCATE xlite_extra_fields;
INSERT INTO xlite_extra_fields (field_id, product_id, name) VALUES (1, 205, 'Country');
INSERT INTO xlite_extra_fields (field_id, product_id, name) VALUES (2, 205, 'ASIN');

TRUNCATE xlite_extra_field_values;
INSERT INTO xlite_extra_field_values (field_id, product_id, value) VALUES (1, 205, 'Switzerland');
INSERT INTO xlite_extra_field_values (field_id, product_id, value) VALUES (2, 205, 'B001OBX76S');

TRUNCATE xlite_wholesale_pricing;
INSERT INTO xlite_wholesale_pricing (product_id, amount, price, membership) VALUES (205, 4, 89.99, 'all');
INSERT INTO xlite_wholesale_pricing (product_id, amount, price, membership) VALUES (205, 9, 85.99, 'all');

update xlite_products set expansion_limit = 1 where product_id = 205;
insert into xlite_purchase_limit values (205, 10, 100);
