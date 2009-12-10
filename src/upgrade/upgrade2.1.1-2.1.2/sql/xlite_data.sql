INSERT INTO xlite_card_types VALUES ('VISA','Visa',1,10,1);
INSERT INTO xlite_card_types VALUES ('MC','MasterCard',1,20,1);
INSERT INTO xlite_card_types VALUES ('AMEX','American Express',1,30,1);

insert into xlite_config values ('include_tax_message', 'Includes tax message', ', including VAT', 'Taxes', 0, 'text');
INSERT INTO xlite_config VALUES ('orders_department','Orders department e-mail address','bit-bucket@x-cart.com','Company',9,'text');
INSERT INTO xlite_config VALUES ('newsletter_email','Reply-To newsletter email address','bit-bucket@x-cart.com','Company',55,'text');
INSERT INTO xlite_config VALUES ('support_department','Help/Support department e-mail address','bit-bucket@x-cart.com','Company',10,'text');
INSERT INTO xlite_config VALUES ('users_department','Users department e-mail address','bit-bucket@x-cart.com','Company',8,'text');
INSERT INTO xlite_config VALUES ('site_administrator','Site administrator e-mail address','bit-bucket@x-cart.com','Company',8,'text');
INSERT INTO xlite_config VALUES ('location_address','Company address','*Your company address*','Company',2,'text');
INSERT INTO xlite_config VALUES ('location_city','Company city','*You company city*','Company',3,'text');
INSERT INTO xlite_config VALUES ('location_state','Company state','0','Company',4,'state');
INSERT INTO xlite_config VALUES ('location_zipcode','Company zip/postal code','50001','Company',5,'text');
INSERT INTO xlite_config VALUES ('location_country','Company country','US','Company',7,'country');
INSERT INTO xlite_config VALUES ('company_website','Company website','http://','Company',1,'text');
INSERT INTO xlite_config VALUES ('company_name','Company name','*Your company name*','Company',0,'text');
INSERT INTO xlite_config VALUES ('company_phone','Company phone','(555) 555-5555','Company',3,'text');
INSERT INTO xlite_config VALUES ('company_fax','Company fax','(555) 555-5555','Company',3,'text');
INSERT INTO xlite_config VALUES ('start_year','Year when store started its operation','2004','Company',150,'text');
INSERT INTO xlite_config VALUES ('shop_closed','Check this to temporary close the shop','N','General',5,'checkbox');
INSERT INTO xlite_config VALUES ('minimal_order_amount','Minimum allowed order total','10','General',20,'text');
INSERT INTO xlite_config VALUES ('maximal_order_amount','Maximum allowed order total','99999','General',30,'text');
INSERT INTO xlite_config VALUES ('show_cc_info','Include order details (credit card information) into admin order notification message','N','Email',0,'checkbox');
INSERT INTO xlite_config VALUES ('customer_security','Turn on HTTPS for customer\'s zone (login, checkout, profile, orders)','N','Security',0,'checkbox');
INSERT INTO xlite_config VALUES ('admin_security','Turn on HTTPS for administrator\'s zone','N','Security',0,'checkbox');
INSERT INTO xlite_config VALUES ('default_country','Default country in the registration form','US','General',100,'country');
INSERT INTO xlite_config VALUES ('httpsClient','HTTPS client (used for integration with payment/shipping services)','autodetect','Security',0,'');
INSERT INTO xlite_config VALUES ('products_per_page','Products per page','10','General',100,'text');
INSERT INTO xlite_config VALUES ('products_per_page_admin','Products per page (admin)','30','General',100,'text');
INSERT INTO xlite_config VALUES ('orders_per_page','Orders per page (admin)','30','General',100,'text');
INSERT INTO xlite_config VALUES ('users_per_page','Users per page (admin)','30','General',100,'text');
INSERT INTO xlite_config VALUES ('redirect_to_cart','Redirect customer to cart','Y','General',15,'checkbox');
INSERT INTO xlite_config VALUES ('recent_orders','How many latest orders to show in admin zone','10','General',100,'text');
INSERT INTO xlite_config VALUES ('enable_init_order_notif_customer','Enable email notifications for customers about initially placed orders','N','Email',0,'checkbox');
INSERT INTO xlite_config VALUES ('enable_init_order_notif','Enable email notifications for admin about initially placed orders','N','Email',0,'checkbox');
INSERT INTO xlite_config VALUES ('buynow_button_enabled','Enable \"Buy Now\" button in the product list','Y','General',100,'checkbox');
INSERT INTO xlite_config VALUES ('enable_anon_checkout','Enable anonymous checkout','Y','General',100,'checkbox');
INSERT INTO xlite_config VALUES ('show_thumbnails','Show thumbnails in product list','Y','General',100,'checkbox');
INSERT INTO xlite_config VALUES ('weight_symbol','Weight symbol','lbs','General',100,'text');
INSERT INTO xlite_config VALUES ('weight_unit','Weight unit','lbs','General',100,'select');
INSERT INTO xlite_config VALUES ('tax_rates','Tax rates','a:3:{i:0;s:6:\"Tax:=0\";i:1;a:2:{s:9:\"condition\";s:30:\"product class=shipping service\";s:6:\"action\";s:6:\"Tax:=0\";}i:2;a:2:{s:9:\"condition\";s:22:\"product class=Tax free\";s:6:\"action\";s:6:\"Tax:=0\";}}','Taxes',20,'');
INSERT INTO xlite_config VALUES ('use_billing_info','Use billing info to calculate taxes','N','Taxes',10,'checkbox');
INSERT INTO xlite_config VALUES ('taxes','','a:1:{i:0;a:2:{s:4:\"name\";s:3:\"Tax\";s:13:\"display_label\";s:3:\"Tax\";}}','Taxes',0,'text');
INSERT INTO xlite_config VALUES ('prices_include_tax','Prices include tax','N','Taxes',200,'checkbox');
INSERT INTO xlite_config VALUES ('add_on_mode','Switch the shop to \"Checkout desk\" operation mode','N','General',10,'checkbox');
INSERT INTO xlite_config VALUES ('version','','2.1.2','Version',0,'');
INSERT INTO xlite_config VALUES ('order_starting_number','Order starting number','1','General',240,'text');
INSERT INTO xlite_config VALUES ('memberships','Membership levels','a:0:{}','Memberships',0,'serialized');
INSERT INTO xlite_config VALUES ('product_layout','','sku,name,category,brief_description,description,thumbnail,image,price,tax_class,weight,order_by,enabled,NULL','ImportExport',0,NULL);
INSERT INTO xlite_config VALUES ('user_layout','','login, password, billing_firstname, billing_lastname','ImportExport',0,NULL);
INSERT INTO xlite_config VALUES ('params','','a:0:{}','XCartImport',0,'serialized');
INSERT INTO xlite_config VALUES ('safe_mode','Safe mode (do not initialize modules; also available thru safe_mode=on in URL','N','General',200,'checkbox');
INSERT INTO xlite_config VALUES ('shipping_code','','1702936249','Shipping',0,'text');
INSERT INTO xlite_config VALUES ('callback_code','','','Shipping',0,'text');
INSERT INTO xlite_config VALUES ('callback_status','','','Shipping',0,'text');
INSERT INTO xlite_config VALUES ('license','','','License',0,'text');
INSERT INTO xlite_config VALUES ('login_lifetime','How many days to store last login name','3','General',100,'text');
INSERT INTO xlite_config VALUES ('subcategories_look','Subcategories look&feel','category_subcategories.tpl','General',110,'select');
INSERT INTO xlite_config VALUES ('thousand_delim','Currency thousands delimiter',',','General',200,'select');
INSERT INTO xlite_config VALUES ('decimal_delim','Currency decimal delimiter','.','General',210,'select');
INSERT INTO xlite_config VALUES ('price_format','Currency format','$ %s','General',220,'text');
INSERT INTO xlite_config values ('date_format','Date format', '%m/%d/%Y', 'General', 225, 'select');
INSERT INTO xlite_config values ('time_format','Time format', '%H:%M', 'General', 230, 'select');
INSERT INTO xlite_config (category, name, value, type) values ('Images','defaultSources','', 'serialized');


INSERT INTO xlite_countries VALUES ('Afghanistan','AF','Dari, Pushtu','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Albania','AL','Albanian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Algeria','DZ','Arabic','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('American Samoa','AS','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Andorra','AD','Catalan','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Angola','AO','Portuguese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Anguilla','AI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Antarctica','AQ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Antigua and Barbuda','AG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Argentina','AR','Spanish','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Armenia','AM','Armenian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Aruba','AW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Australia','AU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Austria','AT','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Azerbaijan','AZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bahamas','BS','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bahrain','BH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bangladesh','BD','Bengali','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Barbados','BB','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Belarus','BY','Belarussian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Belgium','BE','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Belize','BZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Benin','BJ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bermuda','BM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bhutan','BT','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bolivia','BO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bosnia and Herzegowina','BA','Bosnian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Botswana','BW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bouvet Island','BV','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Brazil','BR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('British Indian Ocean Territory','IO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('British Virgin Islands','VG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Brunei Darussalam','BN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Bulgaria','BG','Bulgarian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Burkina Faso','BF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Burundi','BI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cambodia','KH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cameroon','CM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Canada','CA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cape Verde','CV','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cayman Islands','KY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Central African Republic','CF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Chad','TD','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Chile','CL','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('China','CN','Chinese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Christmas Island','CX','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cocos (Keeling) Islands','CC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Colombia','CO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Comoros','KM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Congo, Democratic Republic of','CD','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cook Islands','CK','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Costa Rica','CR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cote D\'ivoire','CI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Croatia','HR','Croatian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cuba','CU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Cyprus','CY','Greek','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Czech Republic','CZ','Czech','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Czechoslovakia','CS','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Denmark','DK','Danish','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Djibouti','DJ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Dominica','DM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Dominican Republic','DO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('East Timor','TP','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Ecuador','EC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Egypt','EG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('El Salvador','SV','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Equatorial Guinea','GQ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Eritrea','ER','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Estonia','EE','Estonian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Ethiopia','ET','Amharic','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Falkland Islands (Malvinas)','FK','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Faroe Islands','FO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Fiji','FJ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Finland','FI','Finnish, Swedish','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('France','FR','French','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('France, Metropolitan','FX','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('French Guiana','GF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('French Polynesia','PF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('French Southern Territories','TF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Gabon','GA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Gambia','GM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Georgia','GE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Germany','DE','German','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Ghana','GH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Gibraltar','GI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Greece','GR','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Greenland','GL','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Grenada','GD','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guadeloupe','GP','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guam','GU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guatemala','GT','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guinea','GN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guinea-Bissau','GW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Guyana','GY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Haiti','HT','Creole','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Heard and McDonald Islands','HM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Honduras','HN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Hong Kong','HK','Mandarin','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Hungary','HU','Hungarian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Iceland','IS','Icelandic','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('India','IN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Indonesia','ID','Indonesian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Iraq','IQ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Ireland','IE','Irish','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Islamic Republic of Iran','IR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Israel','IL','Hebrew','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Italy','IT','Italian','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Jamaica','JM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Japan','JP','Japanese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Jordan','JO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Kazakhstan','KZ','Kazakh','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Kenya','KE','Kiswahili','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Kiribati','KI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Korea','KP','Korean','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Korea, Republic of','KR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Kuwait','KW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Kyrgyzstan','KG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Laos','LA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Latvia','LV','Latvian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Lebanon','LB','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Lesotho','LS','Sesotho','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Liberia','LR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Libyan Arab Jamahiriya','LY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Liechtenstein','LI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Lithuania','LT','Lithuanian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Luxembourg','LU','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Macau','MO','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Macedonia','MK','Macedonian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Madagascar','MG','Malagasy','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Malawi','MW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Malaysia','MY','Malay','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Maldives','MV','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mali','ML','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Malta','MT','Maltese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Marshall Islands','MH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Martinique','MQ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mauritania','MR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mauritius','MU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mayotte','YT','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mexico','MX','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Micronesia','FM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Moldova, Republic of','MD','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Monaco','MC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mongolia','MN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Montserrat','MS','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Morocco','MA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Mozambique','MZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Myanmar','MM','Burmese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Namibia','NA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Nauru','NR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Nepal','NP','Nepali','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Netherlands','NL','Dutch','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Netherlands Antilles','AN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('New Caledonia','NC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('New Zealand','NZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Nicaragua','NI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Niger','NE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Nigeria','NG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Niue','NU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Norfolk Island','NF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Northern Mariana Islands','MP','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Norway','NO','Norwegian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Oman','OM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Pakistan','PK','Urdu','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Palau','PW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Panama','PA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Papua New Guinea','PG','Pidgin','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Paraguay','PY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Peru','PE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Philippines','PH','Filipino','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Pitcairn','PN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Poland','PL','Polish','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Portugal','PT','Portuguese','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Puerto Rico','PR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Qatar','QA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Reunion','RE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Romania','RO','Romanian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Russian Federation','RU','Russian','Windows-1251',1,'N',0);
INSERT INTO xlite_countries VALUES ('Rwanda','RW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Saint Lucia','LC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Samoa','WS','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('San Marino','SM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Sao Tome and Principe','ST','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Saudi Arabia','SA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Senegal','SN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Seychelles','SC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Sierra Leone','SL','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Singapore','SG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Slovakia','SK','Slovak','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Slovenia','SI','Slovene','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Solomon Islands','SB','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Somalia','SO','Somali','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('South Africa','ZA','Afrikaans','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Spain','ES','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Sri Lanka','LK','Sinhala','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('St. Helena','SH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('St. Kitts And Nevis','KN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('St. Pierre and Miquelon','PM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('St. Vincent And The Greadines','VC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Sudan','SD','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Suriname','SR','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Svalbard and Jan Mayen Islands','SJ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Swaziland','SZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Sweden','SE','Swedish','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('Switzerland','CH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Syrian Arab Republic','SY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Taiwan','TW','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tajikistan','TJ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tanzania, United Republic of','TZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Thailand','TH','Thai','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Togo','TG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tokelau','TK','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tonga','TO','Tongan','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Trinidad and Tobago','TT','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tunisia','TN','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Turkey','TR','Turkish','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Turkmenistan','TM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Turks and Caicos Islands','TC','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Tuvalu','TV','Tuvaluan','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Uganda','UG','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Ukraine','UA','Ukrainian','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('United Arab Emirates','AE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('United Kingdom (Great Britain)','GB','','iso-8859-1',1,'Y',0);
INSERT INTO xlite_countries VALUES ('United States','US','English','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('United States Virgin Islands','VI','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Uruguay','UY','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Uzbekistan','UZ','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Vanuatu','VU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Vatican City State','VA','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Venezuela','VE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Viet Nam','VN','Vietnamese','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Wallis And Futuna Islands','WF','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Western Sahara','EH','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Yemen','YE','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Yugoslavia','YU','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Zambia','ZM','','iso-8859-1',1,'N',0);
INSERT INTO xlite_countries VALUES ('Zimbabwe','ZW','','iso-8859-1',1,'N',0);
















INSERT INTO xlite_payment_methods VALUES ('credit_card','Credit Card','Visa, Mastercard, American Express','credit_card','O:8:\"stdClass\":8:{s:3:\"url\";s:49:\"https://secure.authorize.net/gateway/transact.dll\";s:5:\"login\";s:0:\"\";s:3:\"key\";s:0:\"\";s:4:\"test\";s:5:\"FALSE\";s:4:\"cvv2\";s:1:\"0\";s:12:\"md5HashValue\";s:0:\"\";s:6:\"prefix\";s:7:\"X-lite-\";s:8:\"currency\";s:3:\"USD\";}',10,1);
INSERT INTO xlite_payment_methods VALUES ('purchase_order','Purchase Order','','offline','O:8:\"stdClass\":3:{s:3:\"url\";s:53:\"https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c\";s:5:\"login\";s:3:\"123\";s:12:\"md5HashValue\";s:4:\"test\";}',20,1);
INSERT INTO xlite_payment_methods VALUES ('phone_ordering','Phone Ordering','Phone: (555) 555-5555','offline','O:8:\"stdClass\":3:{s:3:\"url\";s:53:\"https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c\";s:5:\"login\";s:3:\"123\";s:12:\"md5HashValue\";s:4:\"test\";}',30,1);
INSERT INTO xlite_payment_methods VALUES ('fax_ordering','Fax Ordering','Fax: (555) 555-5555','offline','',40,1);
INSERT INTO xlite_payment_methods VALUES ('money_ordering','Money Order','US Banks Only','offline','',45,1);
INSERT INTO xlite_payment_methods VALUES ('echeck','Check','Check payment','echeck','O:8:\"stdClass\":3:{s:3:\"url\";s:53:\"https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c\";s:5:\"login\";s:3:\"123\";s:12:\"md5HashValue\";s:4:\"test\";}',50,1);
INSERT INTO xlite_payment_methods VALUES ('cod','COD','Cash On Delivery','offline','',60,1);



INSERT INTO xlite_shipping VALUES (2,'offline','L','National shipping',1,10);
INSERT INTO xlite_shipping VALUES (3,'offline','I','International shipping',1,20);



INSERT INTO xlite_shipping_rates VALUES (-1,0.00,999999.00,0.00,999999.00,-1,0.00,0.00,0,999999);
















