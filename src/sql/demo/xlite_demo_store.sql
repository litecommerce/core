
-- Categories [7]

REPLACE INTO `xlite_categories` VALUES (1,0,1,16,0,1,'',0);
INSERT INTO `xlite_categories` VALUES (1002,1,4,5,0,1,'apparel',0);
INSERT INTO `xlite_categories` VALUES (1003,1004,9,10,0,1,'cube-goodies',1);
INSERT INTO `xlite_categories` VALUES (1004,1,8,17,0,1,'toys',0);
-- Commented until an e-goods module is implemented
--INSERT INTO `xlite_categories` VALUES (3002,1,6,7,0,1,'downloadables',0);
INSERT INTO `xlite_categories` VALUES (4002,1004,15,16,0,1,'rc-toys',1);
INSERT INTO `xlite_categories` VALUES (4003,1004,11,12,0,1,'science-toys',1);
INSERT INTO `xlite_categories` VALUES (4004,1004,13,14,0,1,'puzzles',1);
INSERT INTO `xlite_categories` VALUES (4005,1,2,3,0,1,'igoods',0);

INSERT INTO xlite_category_quick_flags VALUES (1,1,3,3);
INSERT INTO xlite_category_quick_flags VALUES (2,1002,0,0);
-- Commented until an e-goods module is implemented
-- INSERT INTO xlite_category_quick_flags VALUES (3,3002,0,0);
INSERT INTO xlite_category_quick_flags VALUES (4,1004,4,4);
INSERT INTO xlite_category_quick_flags VALUES (5,4004,0,0);
INSERT INTO xlite_category_quick_flags VALUES (6,1003,0,0);
INSERT INTO xlite_category_quick_flags VALUES (7,4003,0,0);
INSERT INTO xlite_category_quick_flags VALUES (8,4002,0,0);
INSERT INTO xlite_category_quick_flags VALUES (9,4005,0,0);

-- Commented until an e-goods module is implemented
-- INSERT INTO `xlite_category_images` VALUES (101,3002,'demo_store_c3002.jpeg','image/jpeg',154,160,10267,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (102,1004,'demo_store_c1004.jpeg','image/jpeg',140,160,12860,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (103,4004,'demo_store_c4004.jpeg','image/jpeg',156,160,16022,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (104,1003,'demo_store_c1003.jpeg','image/jpeg',150,160,12662,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (105,4003,'demo_store_c4003.jpeg','image/jpeg',160,130,10698,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (106,4002,'demo_store_c4002.jpeg','image/jpeg',160,156,13711,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (107,1002,'demo_store_c1002.jpeg','image/jpeg',156,160,11592,1278412215,'');
INSERT INTO `xlite_category_images` VALUES (108,4005,'demo_store_c4005.jpg','image/jpeg',115,160,13433,1278412215,'');

INSERT INTO `xlite_category_translations` VALUES (1,'en',1,'','','','','');
-- Commented until an e-goods module is implemented
-- INSERT INTO `xlite_category_translations` VALUES (101,'en',3002,'Downloadables','<img src=\"{{WEB_LC_ROOT}}/public/downloadables.png\" alt=\"\" />','','','');
INSERT INTO `xlite_category_translations` VALUES (102,'en',1004,'Toys','<img src=\"{{WEB_LC_ROOT}}/public/toys.png\" alt=\"\" />','','','');
INSERT INTO `xlite_category_translations` VALUES (103,'en',4004,'Puzzles','','','','');
INSERT INTO `xlite_category_translations` VALUES (104,'en',1003,'Cube Goodies','Category-child','','','');
INSERT INTO `xlite_category_translations` VALUES (105,'en',4003,'Science Toys','','','','');
INSERT INTO `xlite_category_translations` VALUES (106,'en',4002,'RC Toys','','','','');
INSERT INTO `xlite_category_translations` VALUES (107,'en',1002,'Apparel','<img src=\"{{WEB_LC_ROOT}}/public/apparel.png\" alt=\"\" />','','','');
INSERT INTO `xlite_category_translations` VALUES (108,'en',4005,'iGoods','<img src=\"{{WEB_LC_ROOT}}/public/igoods.png\" alt=\"\" />','','','');

INSERT INTO `xlite_category_products` SET product_id = '4003', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4004', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4009', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4030', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4031', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4013', category_id = '1003', orderby = '0';
-- Commented until an e-goods module is implemented
-- INSERT INTO `xlite_category_products` SET product_id = '4059', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4057', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4036', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4061', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4060', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4058', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4062', category_id = '3002', orderby = '0';
-- INSERT INTO `xlite_category_products` SET product_id = '4063', category_id = '3002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4016', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4022', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4021', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4012', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4042', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4045', category_id = '4004', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4043', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '159702', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4049', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4005', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4008', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4010', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4007', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4006', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '3002', category_id = '1002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '159704', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4052', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4056', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4054', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4038', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4033', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4026', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4050', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4053', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4055', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4051', category_id = '4002', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4046', category_id = '4004', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4047', category_id = '4004', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4048', category_id = '4004', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4039', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4040', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4041', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4044', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4035', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4034', category_id = '4003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4027', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4020', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4011', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4014', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4017', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4018', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4028', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4029', category_id = '1003', orderby = '0';

INSERT INTO `xlite_category_products` SET product_id = '4015', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4023', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4032', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4025', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4019', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4024', category_id = '1003', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '4047', category_id = '1004', orderby = '10';
INSERT INTO `xlite_category_products` SET product_id = '4039', category_id = '1004', orderby = '20';
INSERT INTO `xlite_category_products` SET product_id = '4015', category_id = '1004', orderby = '30';
INSERT INTO `xlite_category_products` SET product_id = '4032', category_id = '1004', orderby = '40';
INSERT INTO `xlite_category_products` SET product_id = '4045', category_id = '1004', orderby = '50';
INSERT INTO `xlite_category_products` SET product_id = '4024', category_id = '1004', orderby = '60';
INSERT INTO `xlite_category_products` SET product_id = '4035', category_id = '1004', orderby = '70';
INSERT INTO `xlite_category_products` SET product_id = '4043', category_id = '1004', orderby = '80';
INSERT INTO `xlite_category_products` SET product_id = '4012', category_id = '1004', orderby = '90';
INSERT INTO `xlite_category_products` SET product_id = '4019', category_id = '1004', orderby = '100';
INSERT INTO `xlite_category_products` SET product_id = '4017', category_id = '1004', orderby = '110';
INSERT INTO `xlite_category_products` SET product_id = '4041', category_id = '1004', orderby = '120';

INSERT INTO `xlite_category_products` SET product_id = '5000', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5001', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5002', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5003', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5004', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5005', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5006', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5007', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5008', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5009', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5010', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5011', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5012', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5013', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5014', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5015', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5016', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5017', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5018', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5019', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5020', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5021', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5022', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5023', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5024', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5025', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5026', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5027', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5028', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5029', category_id = '4005', orderby = '0';
INSERT INTO `xlite_category_products` SET product_id = '5030', category_id = '4005', orderby = '0';


-- Products [65]

INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4003,'19.99','19.99','00001',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4004,'18.99','18.99','00002',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4009,'29.99','29.99','00007',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4030,'11.99','11.99','00028',0,1,'0.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4031,'7.99','7.99','00029',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4013,'29.99','29.99','00011',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4059,'39.95','39.95','00057',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4016,'12.99','12.99','00014',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4022,'5.99','5.99','00020',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4021,'24.99','24.99','00019',0,1,'1.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4012,'7.99','7.99','00010',0,1,'0.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4042,'19.99','19.99','00040',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4045,'11.99','11.99','00043',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4043,'5.99','5.99','00041',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (159702,'39.99','39.99','00094',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4049,'9.99','9.99','00047',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4005,'18.99','18.99','00003',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4008,'18.99','18.99','00006',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4010,'19.99','19.99','00008',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4007,'15.99','15.99','00005',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4006,'15.99','15.99','00004',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (3002,'17.99','17.99','00000',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (159704,'59.99','59.99','00095',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4052,'29.99','29.99','00050',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4056,'39.99','39.99','00054',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4054,'24.99','24.99','00052',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4038,'49.99','49.99','00036',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4033,'24.99','24.99','00031',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4026,'34.99','34.99','00024',0,1,'2.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4050,'19.99','19.99','00048',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4053,'39.99','39.99','00051',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4055,'474.99','474.99','00053',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4051,'29.99','29.99','00049',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4046,'19.99','19.99','00044',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4047,'9.99','9.99','00045',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4048,'19.99','19.99','00046',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4039,'14.99','14.99','00037',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4040,'19.99','19.99','00038',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4041,'19.99','19.99','00039',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4044,'19.99','19.99','00042',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4035,'12.99','12.99','00033',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4034,'7.99','7.99','00032',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4027,'4.99','4.99','00025',0,1,'0.05','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4020,'9.99','9.99','00018',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4011,'49.00','49.00','00009',0,1,'1.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4014,'39.99','39.99','00012',0,1,'2.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4017,'24.99','24.99','00015',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4018,'14.99','14.99','00016',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4028,'13.99','13.99','00026',0,1,'0.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4029,'5.99','5.99','00027',0,1,'0.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4057,'4.95','4.95','00055',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4036,'4.95','4.95','00034',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4061,'0.49','0.49','00059',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4015,'24.99','24.99','00013',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4023,'9.99','9.99','00021',0,1,'0.08','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4032,'9.99','9.99','00030',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4025,'5.99','5.99','00023',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4019,'8.99','8.99','00017',0,1,'1.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4024,'34.99','34.99','00022',0,1,'1.50','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4060,'6.50','6.50','00058',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4058,'34.95','34.95','00056',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4062,'49.95','49.95','00060',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (4063,'5.95','5.95','00061',0,1,'0.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5000,'129.95','129.95','00084',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5001,'19.00','19.00','00078',0,1,'0.22','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5002,'29.00','29.00','00081',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5003,'79.00','79.00','00080',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5004,'369.00','369.00','00062',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5005,'199.99','199.99','00035',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5006,'229.00','229.00','00067',0,1,'4.40','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5007,'29.00','29.00','00077',0,1,'0.22','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5008,'69.00','69.00','00075',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5009,'269.95','269.95','00085',0,1,'0.32','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5010,'19.95','19.95','00079',0,1,'0.22','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5011,'49.95','49.95','00066',0,1,'2.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5012,'79.95','79.95','00065',0,1,'4.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5013,'1199.00','1199.00','00064',0,1,'17.64','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5014,'29.95','29.95','00086',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5015,'34.95','34.95','00090',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5016,'29.95','29.95','00089',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5017,'34.95','34.95','00088',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5018,'199.00','199.00','00063',0,1,'1.06','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5019,'249.00','249.00','00070',0,1,'1.06','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5020,'149.00','149.00','00069',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5021,'29.00','29.00','00087',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5022,'79.00','79.00','00068',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5023,'14.95','14.95','00076',0,1,'1.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5024,'999.00','999.00','00071',0,1,'2.27','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5025,'1699.00','1699.00','00072',0,1,'4.40','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5026,'599.00','599.00','00073',0,1,'4.40','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5027,'2499.00','2499.00','00074',0,1,'10.00','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5028,'29.00','29.00','00082',0,1,'4.40','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5029,'29.95','29.95','00091',0,1,'0.10','',0,'');
INSERT INTO `xlite_products` (`product_id`, `price`, `sale_price`, `sku`, `order_by`, `enabled`, `weight`, `tax_class`, `free_shipping`, `clean_url`) VALUES (5030,'59.95','59.95','00083',0,1,'0.32','',0,'');

INSERT INTO xlite_product_translations VALUES (1,'en',4003,'Planet Express Babydoll','','','','','');
INSERT INTO xlite_product_translations VALUES (2,'en',4004,'Digital Angel','','','','','');
INSERT INTO xlite_product_translations VALUES (3,'en',4009,'Electronic Drum Kit Shirt','','','','','');
INSERT INTO xlite_product_translations VALUES (4,'en',4030,'Ninja Star Push Pins','<h5>Ninja note attack</h5>\n<p>There are many nonverbal signs you can use to let those in your office know how you feel about them. You can leave a sticky note saying \"Good Job\" on their monitor. You can put laxatives in their coffee. Or, you can do as the ninjas done did: stick an angry note on their cubicle wall with throwing stars. Of course not real throwing stars, but <em>Ninja Star Push Pins</em>.</p>\n<p>Each set will bring you three Ninja Stars of posting doom. Each star has one corner cut off and replaced with two push pins, so when they are embedded in the wall (or cork board or foam board) they look like they were thrown with great force. And apart from looking cool in the wall, the stories of how the <em>Ninja Star Push Pins</em> got there in the first place will quickly become the things of office legend - because nothing adds emphasis to a sign or note like a Ninja Star.</p>\n<h6>Ninja Star Push Pins</h6>\n<ul>\n<li>These push pins make it look like your office space has been attacked by a bunch of ninjas. </li>\n<li>Made of injection molded ABS with a chrome plated finish. </li>\n<li>Three Ninja Star Push Pins per set. </li>\n<li><strong>Dimensions:</strong> approx. 2.75\" tip to tip </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2Fb704%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (5,'en',4031,'Acrobots','<h5>Futuristic, Posable, Magnetic, Acrobots!</h5>\n<p>Articulated, stackable and very artistic, Acrobots feature crystal clear round heads, magnets in their hands and feet, flexible joints and the ability to contort into hundreds of shapes. Pose them gracefully and stick \'em on metallic stuff. Great to fidget with.</p>\n<p>Get a bunch! They love to play together and they\'ll stay happier longer. You want your Acrobots to be happy don\'t you? Oh, and never forget that no matter what happens - YOU are always their King. That way you can never feel guilty about how you pose them. Assorted colors.</p>\n<p><strong>Dimensions:</strong> About 4\" tall. Includes 1 Acrobot (random color) containing 6 pivot points.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F6748%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.ftjcfx.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (6,'en',4013,'Space Man Moppel LED Light','<h5>LED Light from Lightyears Away</h5>\n<p>In the future, astronauts will be accompanied by tiny robot companions. These robots will most probably have a variety of hand/tool attachments, jetpacks, magnetic feet, and various lights and cameras. Cartoons and literature tell us these robots will also have quirky personalities and emotional problems. Until that bright future arrives, we have the Space Man Moppel.</p>\n<p>The Space Man Moppel is the perfect buddy for the sci-fi lover. Each foot has two power magnets, so the Space Man can stand in almost any pose, any direction, and any gravity! The arms, legs, and neck are super bendy, too. One hand is molded to hold a cable or two, and the other is a strong alligator clip for securing notes, thin wires, or anything else your mind can imagine. With a push of the button, your Space Man Moppel\'s head will illuminate an area of your workspace in a soft, white, LED glow. And there is nothing as lovely as an LED glow.</p>\n<p>Each Space Man Moppel is approx. 12\" tall and uses one 9V battery (included!)</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F910b%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.ftjcfx.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (7,'en',4059,'Paint Shop Pro Web Graphics [PDF]','<h5>Author: Lori Davis</h5>\n<p>One of those books that you will keep permanently by your computer for instant reference. -- <em>Stuart Pyne, Paint Shop Pro Users Group Librarian</em> <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<h6>Product Description</h6>\n<p>This guide to creating great graphics using Paint Shop Pro focuses on Web-specific solutions, showing users how to build simple graphics from scratch, modify existing logos and graphics, and create complex button bars, navigational schemes, and site designs. Tips for using scanners and digital cameras to edit and customize photos are included, and updates to Paint Shop Pro\'s line drawing tool, text tool, and their new animation shop are covered. <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>Publisher:</strong> Muska &amp; Lipman (December 1, 2000)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005Y1OL?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005Y1OL\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005Y1OL\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (8,'en',4016,'Giant Paper Clip Wall Hook','<h5>For your hang-ups</h5>\n<p>Paper clips are one of the greatest inventions of humankind. With the one exception of trying to help you using office productivity software (you know what we mean), the paper clip can do no wrong. Sure it can hold paper together, but as MacGuyver has shown, it can do oh so much more. Need to pick a lock? Paper clip. Gotta diffuse a bomb in a hurry? Paper Clip! Need to hang up your coat in style? <em>Giant Paper Clip Hook!</em></p>\n<p>Let a <em>Giant Paper Clip Hook</em> hang proudly on your wall as a testament to the wonders of the paper clip. Hang your coat or hat on it proudly. But don\'t forget the multifaceted marvel that is the paper clip; even this bigger version has multiple uses. The <em>Giant Paper Clip Hook</em> could be a great hanging point for your Spider-Man action figures - or an effigy of your evil boss. The choice is up to you.</p>\n<h6>Giant Paper Clip Hook</h6>\n<ul>\n<li>Mount to your wall, and then use the power of a paper clip to hold your coats. </li>\n<li><strong>Includes:</strong>Paper Clip Hook and mounting hardware. </li>\n<li><strong>Dimensions:</strong> 11\" x 3\" </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2Fb9bc%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (9,'en',4022,'Build Your Own Stonehenge Kit','<h5>Mini Megaliths</h5>\n<p>In the year 10,000BC, aliens from Uranus landed on Earth. They didn\'t find anyone cool to talk to, so they just added graffiti to the countryside and left. That graffiti is Stonehenge. Since that time, druids and scientists have tried to provide meaning to the awe inspiring stones, but have never riddled out the true message. We have. How? We can\'t tell you. What does Stonehenge really say? <em>That</em>, we can tell you. It says... \"WTF?\" in Uranean.</p>\n<p>This kit will help you spread the joys of an extraterrestrial WTF? to your home or office. The largest stone is about 1.5\" tall, so that the entire structure can neatly surround your coffee mug. <em>But how will I know which stones go where?</em>, you ask. Simple, we reply with a smile, there is a puzzle/map/Stonehenge base which is included. All the stones are numbered, so it\'s real easy to assemble properly. You also get a little book with tons of information on what scientists think Stonehenge was all about - but now you\'ll know the truth.</p>\n<p>The Set includes: 16 stone replicas, puzzle map/base, and 1 Mini Book. Perfect for Spinal Tap dioramas, too.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F8eed%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (10,'en',4021,'Finger Drums','<h5>Riff on This!</h5>\n<p>The oldest drums discovered (so far) have been from around 6000BC. What this means is pretty clear: humans like hitting things; humans like music; and humans like hitting things to make music. We like hitting things too - and we love music. So good thing for everyone we found these Finger Drums. Stop hitting your mugs and monitor and telephone with pens and start tapping a real beat on your Finger Drums drum set.</p>\n<p>Each drum set features five touch sensitive percussive instruments. You get three smaller drums (snare and two toms), bass drum (with working pedal), and crash cymbal. All you have to do is hit them with your fingers to get a beat a-rockin\'. You\'ll also get a mini light show, as each drum (and cymbal) lights up on impact. And the super cool thing is the record feature. Got a good riff going? Then record it and play it back ad nausuem. This feature has many uses, but we find it\'s perfect for joke telling. Record your own sting and play it back after each punch line. Drums make all jokes funnier. Even yours.</p>\n<p>Finger Drums are approx. 6.5\" X 5.5\" X 3\" and use 3 AAA batteries (included).</p>\n<p>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/7CmNkLe2VGw\" type=\"application/x-shockwave-flash\">\n<param name=\"wmode\" value=\"transparent\" />\n<param name=\"src\" value=\"http://www.youtube.com/v/7CmNkLe2VGw\" />\n</object>\n</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F922f%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (11,'en',4012,'Collector\'s Edition Slinky','<h5>It\'s SLINKY!</h5>\n<p>Everybody sing! <em>\"What falls down stairs and rolls out of chairs and makes a jingly sound?\"</em> No, it\'s not grandma. It\'s SLINKY! Everyone knows the Slinky song (the real one) because it is has been running continuously on television longer than any other commercial jingle (since 1962). To celebrate this simple, fun, and timeless toy, we\'re proudly offering the Collector\'s Edition Slinky to you, our friends.</p>\n<p>Your Slinky will arrive in a simple cardboard box sporting the original package design. Not only that, but your Slinky will look just like it did when it was created in 1945 (dark metal - not silvery or plastic-y like you might remember). And keep in mind, a Slinky is not just a toy; it can be a great tool to show science stuff - like wave motion. So learn and play with Slinky. It\'s more than just a spring; it\'s a wonderful thing!</p>\n<p>Box measures 3\" x 3\" x 2.5\" - Slinky fits inside and can stretch much, much longer.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F9a73%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (12,'en',4042,'Mini Solar-Powered Car','<h5>Car is tiny, power source is very large</h5>\n<p>Solar cars are, unfortunately, still in the research and development phase, although we saw one recently that drove from Texas to New York in 5 days. Despite being impressive, it\'ll likely be years before we have one sitting in the driveway, charging. Until then this working miniature model will have to keep us entertained.</p>\n<p>The world\'s smallest solar powered car - it zooms along when the sun shines on the solar panel. No batteries - it gets its power just from the sun. No assembly required. Measures a tiny 3.3 x 2.2 x 1.4 cm. When the sun shines on the solar panel, the panel makes electricity that turns the motor and the wheels. A fun demonstration of solar power. Will also work when close to a strong artificial light source. Not for children under 3 years old, due to small parts.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2F9e8d%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (13,'en',4045,'Pyramid Brain Twist','<h5>A new dimension in puzzles!</h5>\n<p>When trying to solve The Cube, you had to train your brain to keep track of individual tiles as they travelled around the six-sided surface. So, you think that if we reduce the number of sides to four it might be easier? Think again!</p>\n<p>This colorful pyramid puzzle features four sides and four axes of rotation, but wait! Pull the points apart, and your pyramid turns inside out! Now you have a different set of colors, and the orientation of your original colored tiles will immediately come into question. Was that blue tile over here, or over there? Wait, where did that red one go, then?</p>\n<p>&nbsp;</p>\n<p>With only thirty-five million combinations, it should be a piece of cake, right? You\'ll just have to try it and see for yourself just how turned around you can get. I wouldn\'t be surprised if you were able to get all four sides to match up, but flip the tiles over and you may discover that the other side is all jumbled again! Clearly, your work is not done yet!</p>\n<h6>Features</h6>\n<ul>\n<li>Unique pyramid puzzle brain-teaser</li>\n<li>Four sides</li>\n<li>Four axes of rotation</li>\n<li>Turn the puzzle inside-out just to mess with your mind!</li>\n<li>Thirty-five million combinations</li>\n<li>4.3 inches (110mm) on a side</li>\n<li>For kids ages eight to one-hundred-eight</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fgames%2Fb0d6%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (14,'en',4043,'Heart Hand Warmer','<h5>Let your heart warm your hands</h5>\n<p>Being cold isn\'t fun. Vampires know this. That\'s one of the reasons they drink blood - to keep warm. Well, to celebrate the joys of being a vampire and to help keep your hands warm this winter, we bring you the perfectly named Heart Hand Warmer. But unlike most hand warmers, this one you can use over and over again. And, you don\'t have to pop it in the microwave to heat up.</p>\n<p>The magic with the heart hand warmer is in the metal disc you see inside it. All you do is click the disc back and forth a few times and you\'ll see the clearish gel inside become opaque. As the opaqueness spreads, so does the heat! It\'s a crazy chemical reaction which we like to call AWESOME! Seriously, the pretty red gel is a supersaturated solution. Bending the disc starts an exothermic crystallization of the gel (i.e. it goes opaque and gets hot). And when it loses its warmth, all you have to do is wrap it in a towel and throw it into boiling water for about 15-20 minutes and the Heart Hand Warmer resets itself. Once it cools off from its boiling bath, it is ready to be used again. A great, fun way to keep your fingers warm this winter. Got a chilly butt? Then slip it in your back pocket. Just be careful when you sit down.</p>\n<p>Dimensions: approx 3.75\" wide</p>\n<p>Added Bonus: You can pop the heart hand warmer in the fridge for a few hours and use it as a cooling pack too!</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/qSdV0kFp3rg\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/qSdV0kFp3rg\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2F9e29%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (15,'en',159702,'Robo-Q Tiny R/C Robot','<h5>We Welcome Our New Mini Robot Overlords</h5>\n<p>Here at ThinkGeek we don\'t discriminate when it comes to robots. Robotic arms, rovers, R2-D2, shiny futuristic robots or old steampunk style robots... we love them all. But when we saw these miniscule Robo-Q robots from Japan it was pure robo-technolust at first sight.</p>\n<p>Not only are these the smallest walking robots we\'ve ever seen, they have fully autonomous modes that allows them to avoid obstacles or chase after the included soccer ball. Of course you can also take full control with the included remote, but watch out for the inevitable robot revolt.</p>\n<p><strong>Important Note</strong><br />This product is imported from Japan and the manual is all in Japanese. However it\'s easy enough to insert the batteries and your mini robot going.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/VqxSOO6mT5k\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/VqxSOO6mT5k\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n<h6>Product Features</h6>\n<ul>\n<li>Amazingly small robots can avoid obstacles and play soccer</li>\n<li>Control your robot directly through the remote</li>\n<li>Two autonomous modes: chase the included ball, or avoid obstacles</li>\n<li>Full control with three walking speeds and right or left turns</li>\n<li>Magnetic charging coupler on remote</li>\n<li>Robot is powered by built-in rechargeable LI battery, charges from remote</li>\n<li>20 minutes charge time, 5 minutes continuous run time</li>\n<li>Robot stores inside remote</li>\n<li>Remote requires 4 x AA batteries (not included)</li>\n<li>Includes Robot, Remote, Soccer Ball, and Manual (in Japanese)</li>\n<li>Four different robot varieties to choose from. Each is on a different channel.</li>\n<li>Imported from Japan</li>\n<li>Robot height: 3.4cm / 1.33in</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fb8d5%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.awltovhc.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (16,'en',4049,'Thumb-Size R/C Mini Cooper','<h5>Mini Mini Mini... Mini Coopers</h5>\n<p>With the current fuel crisis forcing people into smaller and smaller vehicles and spelling impending doom for society as we know it... we figured it was a perfect time to offer these Thumb-Size R/C Mini Coopers. Their all-electric engines run off of button type batteries and are very fuel efficient. Hey... they can\'t carry much groceries in the trunk, but they do feature full control with forward/reverse and right/left turning... plus the remote looks like a tiny cellphone. Take that high gas prices!</p>\n<p><strong>Important Note</strong><br />The Red and Silver versions of the Thumb-Size R/C Mini Cooper operate on the same frequency. You cannot race them at the same time as one controller will drive both cars.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n<tbody>\n<tr valign=\"top\">\n<td>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/sc6oW8PiQyQ\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/sc6oW8PiQyQ\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td width=\"6\">&nbsp;</td>\n<td>\n<h6>Product Features</h6>\n<ul>\n<li>Tiny R/C Mini Cooper Cars are only 2.25\" Long </li>\n<li>Full control with forward and reverse / right and left turns </li>\n<li>Choose from Red or Silver </li>\n<li>Remote looks like a tiny cell phone </li>\n<li>Batteries Included </li>\n</ul>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fa427%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (17,'en',4005,'Self-Rescuing Princess','','','','','');
INSERT INTO xlite_product_translations VALUES (18,'en',4008,'Planet Express','','','','','');
INSERT INTO xlite_product_translations VALUES (19,'en',4010,'Wi-Fi Detector Shirt','','','','','');
INSERT INTO xlite_product_translations VALUES (20,'en',4007,'Caffeine Molecule','','','','','');
INSERT INTO xlite_product_translations VALUES (21,'en',4006,'Nanotechnology is Huge','','','','','');
INSERT INTO xlite_product_translations VALUES (22,'en',3002,'Binary Mom','','','','','');
INSERT INTO xlite_product_translations VALUES (23,'en',159704,'Ducati R/C Motorcycle with Leaning Rider','<h5>Start your Engines</h5>\n<p>We\'ve always had a soft spot for R/C motorcycles... the problem is training wheels. Nothing looks worse than a R/C motorcycle with two tiny wheels on the bottom to keep it from falling over. It\'s an insult to R/C toys everywhere. But luckily this 1:12 scale R/C Ducati Racing Motorcycle needs no extra help to balance and looks sharp with intricate detail and racing logos. It features fully proportional digital control with working suspension.</p>\n<p>However this Ducati\'s main claim to fame is an animated rider who turns his head and leans into each turn. Not simply cosmetic, the weight of the rider actually causes the bike to lean and go into a turn. It\'s great stuff and looks awesome zipping across your office parking lot.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/TPPjE55yFec\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/TPPjE55yFec\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n<h6>Product Features</h6>\n<ul>\n<li>1:12 Scale R/C Motorcycle features amazing detail and a animated rider who leans into each turn</li>\n<li>Fully assembled and ready to race</li>\n<li>Comes with everything needed including Motorcycle, Rider, Controller and Charger</li>\n<li>Full Function Digital proportional control</li>\n<li>Auto Stable technology for balancing</li>\n<li>Adjustable acceleration curve</li>\n<li>Adjustable braking</li>\n<li>6 minutes race time, 20 minutes charge time</li>\n<li>27Mhz Frequency</li>\n<li>4.8 V Ni-MH Battery</li>\n<li>Requires 4 x AA Batteries for Controller (not included)</li>\n<li>Includes 120V AC wall adapter</li>\n<li>Motorcycle is 6.75\" in length</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fb8ab%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (24,'en',4052,'Dark Blade 3-Channel R/C Helicopter','<h5>The Ultimate Mini Copter... Created by ThinkGeek</h5>\n<p>Here at ThinkGeek it seems like no matter how many little R/C helicopters we fly, we\'re always looking for a new high with the next greatest model. Which is why we decided to kick the vicious copter cycle and go ahead and create the perfect micro indoor helicopter. The Dark Blade 3-Channel R/C Helicopter is an original ThinkGeek creation combining a futuristic drop-ship style design with precise flying characteristics and an innovative rear propeller system. Plus it just happens to be the smallest 3-channel R/C copter we\'ve ever seen. So go ahead, enjoy the fruits of our helicopter obsession... we don\'t mind.</p>\n<p><strong>Precise Control</strong><br />The secret of the incredible stability of the Dark Blade 3-Channel R/C Helicopter comes down to the torque control afforded by the dual rotor design. A traditional helicopter with only one rotor generates massive torque when the top blade spins. Normally this would cause the copter to spin out of control, but the rotor on the tail is designed to offset this torque. Therefore piloting a traditional helicopter involves constantly adjusting the tail speed whenever you increase the speed of the top rotor... difficult and time consuming to learn. The Dark Blade 3-Channel R/C Helicopter has dual rotors on the top which spin in opposite directions. These opposite spinning blades cancel out uncontrolled torque making for incredibly stable flight. For forward and reverse motion the tail rotor then blows up or down, pitching the copter forward or backward and causing it to move in that direction. It\'s a great control system and a pleasure to fly.</p>\n<p><strong>Innovative Forward Propulsion System</strong><br />If you look carefully at the Dark Blade, you\'ll see two small side propellers that would appear to push the copter forward. Actually these side propellers are purely cosmetic. All the forward action happens with the small rear propeller hidden <em>beneath</em> the tail. This propeller then spins to pitch the Dark Blade copter forward or backward. In most small 3-Channel R/C copters the rear propeller needs to be mounted at some distance from the top rotors to get enough leverage to pitch the copter, this forces the helicopter design to be longer. However, the tail above the rear rotor in the Dark Blade acts to magnify the thrust of the propeller and allows a more compact 3-channel design.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/wP9NwryiDPc\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/wP9NwryiDPc\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<h6>Product Features</h6>\n<ul>\n<li>Easiest to fly, most precise controlling R/C copter yet </li>\n<li>Three channel control gives you fully proportional up/down, right/left and forward/backward control </li>\n<li>Amazing mini size for a 3-channel helicopter </li>\n<li>Futuristic drop-ship style design </li>\n<li>Sleek black plastic body. No foam cockpit. </li>\n<li>Choose from two different channels and fly with a friend </li>\n<li>Both channel copters are black in color, a red or blue LED headlight lets you know which is yours </li>\n<li>Dual counter-rotating blades give incredible hovering and stability </li>\n<li>Innovative tail design with hidden downward facing rear rotor </li>\n<li>Helicopter Charges from Remote </li>\n<li>Easy to adjust trim dial (rather than push buttons) </li>\n<li>7 Minute fly time, 10 minute charge time </li>\n<li>Requires 6 AA batteries, not included </li>\n<li>Copter is 11 cm in length with a top blade diameter of 13.5 cm </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fb5c4%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (25,'en',4056,'Palmsize Battle Tanks','<h5>Petite Tanks Mean Serious Business</h5>\n<p>Office disputes here at ThinkGeek are handled in a most diplomatic manner. As standard procedure, the offending parties are teleported to the surface of Vulcan where they duel to the death to resolve their differences. However due to the high employee turnover rate we have revised the policy to declare a victor based on Palmzsize Battle Tank warfare. These detailed diminutive r/c tanks feature authentic tank sounds and IR based \"laser tag\" action with realistic recoil. A hit momentarily disables your opponent. Get enough hits and take control of your enemy\'s tank. Each set contains two-tanks, but buy an extra set and up to four tanks can battle at once to solve those more complicated office disagreements.</p>\n<p><strong>Important Note:</strong><br />We know you read directions... but just in case you don\'t, please realize that each tank can be set to any of four channels. But they must be set up in turn before the battle begins.</p>\n<h6>Preparing for Battle</h6>\n<ol type=\"1\">\n<li>Set the remote to the channel you desire (1-4) by using the switch on the left side</li>\n<li>Turn on your tank, turn on the remote. The tank\'s light should be flashing red.</li>\n<li>Push the small round button just below the \"on/off\" switch on the remote.</li>\n<li>The tanks light now turns solid green. It is now set to the correct channel.</li>\n<li>Repeat this procedure for each tank. If you turn the tank off, you will have to set the channel again.</li>\n</ol>\n<p><strong>Battle Modes</strong><br />On the front of each tank, next to the indicator LED light is a small button. Push this button after the tank\'s channel has been set and you can toggle between two battle modes.</p>\n<ul>\n<li><strong>Standard Mode (Indicator light is Green)</strong><br />5 hits by the enemy and your tank is immobilized.... you lose </li>\n<li><strong>Capture Mode (Indicator light is Red)</strong><br />5 hits by the enemy and they take control of your tank. Your tank now behaves as if it is set to the same channel as your opponent\'s tank. Your opponent can drive and fire your tank. This is obviously most useful when 3 or 4 tanks battle. </li>\n</ul>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n<tbody>\n<tr valign=\"top\">\n<td>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/qFt9EQZ4arM\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/qFt9EQZ4arM\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td width=\"6\">&nbsp;</td>\n<td>\n<h6>Product Features</h6>\n<ul>\n<li>Mini R/C Tanks can battle with infrared </li>\n<li>Realistic recoil when shooting </li>\n<li>Battle with up to 4 Tanks at once </li>\n<li>Play \"Capture Mode\" and control your opponents tank after 5 successful hits </li>\n<li>Internal Rechargeable battery charges from remote </li>\n<li>5 minute charge time, 5 minute play time </li>\n<li>Includes bunker and wall obstacles </li>\n<li>Requires 6 AA Batteries (not included) </li>\n<li>Each tank is 4.5\" long including turret </li>\n</ul>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2F9969%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (26,'en',4054,'R/C Desktop Forklift','<h5>Tiny Forklift Does Your Bidding!</h5>\n<p>When we were little kids, we played in sandboxes with toy steamshovels, drove die-cast dumptrucks, and stared in awe and wonder at the heavy equipment at construction sites. Is it the desire for demolition that drives our fascination with heavy equipment, or is it something deeper? Do we secretly long for a world where machines do our bidding? Of course we do, but we have learned from the lessons of Battlestar, haven\'t we?</p>\n<p>If we keep our machines tiny and simple, we may yet survive as a race. So say we all...</p>\n<p>This tiny machine does all the heavy lifting for us, if by heavy we mean a few ounces. Still, it\'s a fun way to relive our childhood by playing with forklifts like we\'ve always wanted to! Radio controlled, this little guy tootles around your desk, lifting and moving stuff from one place to another. Bring me my highly caffeinated beverage, now!</p>\n<p>The R/C Forklift takes 4 AAA batteries, and a 9 volt battery (not included) in the controller.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"425\" height=\"350\" data=\"http://www.youtube.com/v/6Az6HZhzfNc\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/6Az6HZhzfNc\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2F924e%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (27,'en',4038,'Edge Robotic Arm Kit','<h5>Flesh Based Arms are so 2008</h5>\n<p>In the future every home will have a sophisticated robot arm. You know... to do the dishes, walk the dog, and assemble Lego kits for us. But waiting for the future always takes so long, which is why we recommend you pick up this fine Edge Robotic Arm Kit right now. With a bit of tinkering, and no soldering you\'ll have a passable replacement for your own meaty appendage. Five axes of robotic movement are at your command with the wired remote. Command the gripper to open and close, wrist motion of 120 degrees, an extensive elbow range of 300 degrees, base rotation of 270 degrees, base motion of 180 degrees, vertical reach of 15 inches, horizontal reach of 12.6 inches, and lifting capacity of 100g. whew! An LED spotlight on the gripper illuminates your work. Soon you\'ll be creating your own tiny union-free automobile assembly line.</p>\n<p><strong>Important Note</strong><br />You need to assemble the Edge Robotic Arm Kit. No soldering is required, but you will need to be able to follow directions carefully. Assembly time is about 2 hours.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n<tbody>\n<tr valign=\"top\">\n<td>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/t-tQyrBm48M\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/t-tQyrBm48M\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td width=\"6\">&nbsp;</td>\n<td>\n<h6>Product Features</h6>\n<ul>\n<li>Build your own fully functional Robot Arm </li>\n<li>Features 5 motors with 5 axes of movement </li>\n<li>LED Spotlight on gripper illuminates your work </li>\n<li>Wired remote controls all arm functions </li>\n<li>Assembly Required (About 2 hours. No Soldering Needed) </li>\n<li>Lifting capacity of 100g </li>\n<li>Uses 4 x D Batteries (not included) </li>\n</ul>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2Fb696%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.ftjcfx.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (28,'en',4033,'BuckyBalls Magnetic Building Spheres','<h5>Play with your balls at work</h5>\n<p>Ah, carbon - how sweet you are. You are the basis of life on Earth, you let our pencils write, and you form the most fortuitous fullerenes. And what are fullerenes, but a collection of balls. Now, imagine replacing those hard to play with atoms, with rare earth magnetic spheres. Suddenly, you have <em>BuckyBalls Magnetic Building Spheres</em>, and now your life will never be the same.</p>\n<p>Because <em>BuckyBalls Magnetic Building Spheres</em> are really that cool. You can build basic shapes, complex sculptures, magnetic jewelry, or even outfit your refrigerator in bold and unique ways. Just look at all the shapes and forms you can make with these bad boys - it\'s amazing! <em>BuckyBalls Magnetic Building Spheres</em> are just what you need to help you brave the boredom of the office. And really, when else will you get to play with your BuckyBalls at work and not get in trouble?</p>\n<table border=\"0\">\n<tbody>\n<tr valign=\"top\">\n<td>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/mbOjCJD5XYk\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/mbOjCJD5XYk\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td width=\"12\">&nbsp;</td>\n<td>\n<p><strong>BuckyBalls Magnetic Building Spheres</strong></p>\n<ul>\n<li>Super powerful, rare earth, magnetic balls - for you to play with.</li>\n<li>Make sculptures, puzzles, patterns, shapes, jewelry . . . the joy is endless.</li>\n<li>Each set contains 216 BuckyBalls.</li>\n<li><strong>Ages:</strong> Not for kids or adults under 12 years of age.</li>\n<li><strong>Dimensions:</strong> each Buckyball is approx. 0.125\" in diameter.</li>\n</ul>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2Fbbe8%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (29,'en',4026,'LED Jellyfish Mood Lamp','<h5>Dancing Desktop Jellies</h5>\n<p>Blackbeard was just about the most ruthless pirate ever. His management style was unique, to say the least. If one of his crew misbehaved, he would drop them in a large tank full of jellyfish and delight as the jewels he kept at the bottom of the tank reflected different colors into the ballet of agony that played out before him. According to the infamous pirate\'s diaries, it really calmed his nerves, too. Wow. Well, while we don\'t recommend all that for your office, there is something we can take from this story: colorful jellyfish are relaxing.</p>\n<p>This desktop tank holds three jellyfish which \"swim\" around the tank (thanks to a gently contrived current). In the top of the tank are 6 bright LEDs, which let you set the mood. You can either have them blend softly from one color to the next, or stop on your favorite color. Either way, the jellies are happy to frolic in their kaleidoscopic, quiet menace. And if one of your subordinates ever acts up, just remind him or her about the Blackbeard story...and let them know there\'s room in your jellyfish tank for a hand or two. Sometimes threats are all you need. Arrrgh.</p>\n<p><strong>Note:</strong> If you are having any troubles getting your jellies to swim about properly, remember to add just a few drops of liquid dish soap to the water as per the instructions. It\'s the part that makes the magic happen. Thanks!</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n<tbody>\n<tr valign=\"top\">\n<td>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/x-XohyrmYgk\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/x-XohyrmYgk\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td width=\"6\">&nbsp;</td>\n<td>\n<p><strong>LED Jellyfish Mood Lamp</strong></p>\n<ul>\n<li>Lifelike jellyfish movement </li>\n<li>6 bright LEDs - cycle through colors or select your favorite one </li>\n<li>Includes: Tank, 3 Jellyfish, Power cord (110V), and Instructions </li>\n<li>Dimensions: 7\" x 10\" x 4.5\" (with 3\" long jellyfish) </li>\n</ul>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F9a8c%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.ftjcfx.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (30,'en',4050,'ChoroQ Qsteer Mario Kart R/C Racers','<h5>Battle Mode on Your Desk</h5>\n<p>The days of boring, difficult-to-handle remote control cars are over! Why, you ask? Because the ChoroQ Qsteer Mario Kart R/C Racers have arrived, and neither of those negative descriptions apply! Can\'t all mini electronic vehicles just get along?</p>\n<p>Imported from Japan, these officially licensed Nintendo cars are perfect for the diehard Mario aficionado. Available in either Mario or Yoshi, you can maneuver the cars around the office using your desk as a Grand Prix racetrack, all the while evading your boss. Both styles come in individual channels so you can have your very own Battle Mode right in the discomfort of your own workplace. Even use the Green Shell and Banana accessories to recreate the perfect setting. Thanks, Wii love you too.</p>\n<p><strong>Important Note</strong><br />These ChoroQ racers are imported from Japan, thus the directions are all in Japanese... but never fear young otaku, we have included a handy \"Basic Instructions\" graphic to help you figure things out.</p>\n<h6>Product Features</h6>\n<ul>\n<li>Mini R/C Racers look just like Mario Kart Characters </li>\n<li>Imported from Japan </li>\n<li>Precision Steering Control and Handling </li>\n<li>Turbo Button </li>\n<li>Two Different Channels Let you Race with a Friend </li>\n<li>Batteries Included </li>\n<li>1.75\" inches in Length </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fac99%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (31,'en',4053,'R/C Airsoft Battle Tanks','<h5>A Full Functioned Remote Control Tank With Real Firepower!</h5>\n<p>Tired of using your imagination to fire your R/C tank at the enemy Bawls bottle which maliciously invaded your territorial sovereignty? Imagine no longer, and blast the enemy on your living room carpet with the powerful air motor turret on this R/C tank (uses self generated compressed air). Direct from Japan, this R/C battle tank is ready to bring your childhood battle simulation dreams to life in the comfort of your home or office. Like any childhood battle simulation dream, however, the Battle Tank is not appropriate for children - <strong>this toy is for grownups (or 32 year old children)</strong>.</p>\n<h6>Standard R/C Battle Tank Features</h6>\n<ul>\n<li>All Tread Suspension System </li>\n<li>Turret Maneuvers side to side (320 degrees) &amp; up or down (20 degrees). </li>\n<li>2-Level Forward speed control </li>\n<li>Tank Maneuvers forwards, backwards, spins and has a \'Super Spin\' mode. </li>\n<li>Selectable frequency (A,B,C) on both remote and tank allow up to three tanks to operate at the same time for your own office space wargames (1/24 Scale Green tank only). </li>\n<li>Load up to 40 pieces of Ammo into the tank at once. </li>\n<li>Shoot ammo in singles or in quick succession (1 per second). </li>\n<li>Can fire while on the move! </li>\n<li>Included Rechargeable Battery Pack </li>\n</ul>\n<h6>1/16th Scale Gray Tank also has the following Additional Features</h6>\n<ul>\n<li>Realistic sounds including engine start, tread squealing, and machine gun fire </li>\n<li>Simulated recoil firing mode </li>\n<li>Smoking Exhaust </li>\n<li>Digital Proportional Remote </li>\n</ul>\n<p>You can purchase additional ammo (150 bb\'s per bag) using the dropdown to your right!</p>\n<p><strong>NOTE: THIS IS NOT A TOY. THIS IS AN ADULT REMOTE CONTROLLED TANK WHICH SHOOTS HIGH VELOCITY 6MM PLASTIC AMMO UP TO 25 METERS. SAFETY GLASSES SHOULD BE USED AT ALL TIMES!</strong></p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2F6279%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (32,'en',4055,'SRV-1 Blackfin Mobile Surveillance Robot','<h5>Mars Rover for your Home</h5>\n<p>Explore the dangerous terrain of your home or office with the SRV-1 Mobile Robot. This palm sized bot packs tank-like treads a 1000MIPS 500MHz Blackfin BF537 processor and a mini video camera. Using 802.11b/g wi-fi the SRV-1 interfaces wirelessly with a remote PC. The Java-based console software includes a built-in web server to monitor and control the SRV-1 via a web browser from anywhere in the world, as well as archive video feeds on demand or on a scheduled basis. Get programming with fully open-source software for robot and host computer.</p>\n<p><strong>Important Note</strong><br />The SRV-1 Mobile Robot comes fully assembled and ready to use, but requires some technical knowledge of Java, networking and the command line to set-up the software. If you feel comfortable tinkering and have had experience configuring a basic web server you should be in fine shape. Read the <a href=\"http://www.surveyor.com/blackfin/SRV_setup_bf.html\"><strong>SRV-1 Set-Up Instructions</strong></a> and you\'ll have a good idea if you\'ve got what it takes.</p>\n<h6>Product Features</h6>\n<ul>\n<li>Mobile Robot is controlled via web browser with live video feed </li>\n<li>Teleoperate mode to drive robot around via console software or remotely via web browser </li>\n<li>Open Source design with full access to source code (GPL) and schematics </li>\n<li>Robot is fully programmable for autonomous operation </li>\n<li>Extensive software support through 3rd party applications </li>\n<li>Host software has built-in web server and video archiving </li>\n<li>Robot can run programs written in interpreted C and stored in onboard Flash </li>\n<li>Wireless remote control or viewing up to 100m indoors and 1000m outdoors (line of sight) </li>\n<li>Robot can be controlled from a terminal/console for easy testing </li>\n<li>Linux 2.6 support as well as \"bare metal\" programming with GNU bfin-elf-gcc </li>\n</ul>\n<h6>Hardware</h6>\n<ul>\n<li>Processor: 1000mips 500MHz Analog Devices Blackfin BF537, 32MB SDRAM, 4MB Flash, JTAG </li>\n<li>Camera: Omnivision OV9655 1.3 megapixel 160x128 to 1280x1024 resolution </li>\n<li>Robot Radio: Lantronix Matchport 802.11b/g WiFi </li>\n<li>Range: 100m indoors, 1000m line-of-site </li>\n<li>Sensors: 2 laser pointers for ranging, support for up to 4 Maxbotics ultrasonic ranging modules and various I2C sensors </li>\n<li>Drive: Tank-style treads with differential drive via four precision DC gearmotors (100:1 gear reduction) </li>\n<li>Speed: 20cm - 40cm per second (approx 1 foot/sec or .5 mile/hour) </li>\n<li>Chassis: Machined Aluminum </li>\n<li>Dimensions: 120mm long x 100mm wide x 80mm tall (5\" x 4\" x 3\") </li>\n<li>Weight: 350gm (12oz) </li>\n<li>Power: 7.2V 2AH Li-poly battery pack - 4+ hours per charge </li>\n<li>Charger: 100-240VAC 50/60Hz (US plug) </li>\n</ul>\n<h6>Software</h6>\n<ul>\n<li>Robot Firmware: easily updated, written in C language under GPL Open Source, compiled with GNU bfin-elf-gcc and bfin-uclinux-gcc toolchains </li>\n<li>Onboard User Programming: interpreter for \"small C\" language with special robot-specific commands are provided for running user programs from onboard Flash memory </li>\n<li>Development Tools: GNU toolchains via <a href=\"http://blackfin.uclinux.org/\">http://blackfin.uclinux.org</a> </li>\n<li>Console Software: Java based application, runs on Windows, MAC, Linux. <a href=\"http://www.surveyor.com/index_satcam.html\">WebcamSat</a> web server module built into console software, allows multiple simultaneous remote viewers via Internet </li>\n<li>Robot Control Protocol: <a href=\"http://www.surveyor.com/SRV_protocol.html\">Published here</a>. Easily used from other applications </li>\n<li>Third-party Software Support: <br /><br />- <strong>RoboRealm</strong> - <a href=\"http://www.roborealm.com/help/Surveyor_SRV1.php\">http://www.roborealm.com/help/Surveyor_SRV1.php</a> - The SRV-1 can now be directly controlled from RoboRealm, a very popular Windows-based machine vision software package for robots. The RoboRealm extensions for SRV-1 allow creation of scripts that combine image processing on live video feeds from the robot, e.g. color filtering, blob detection/tracking, edge detection/outlining and feature extraction, with decision processing and robot motion control, making it easy to create behaviors such as object location and tracking, obstacle avoidance, motion detection, notification, etc, with a web interface, and control can be scripted from C/C++, Python, Java, C#, Lisp, Visual Basic, WScript and COM through the RoboRealm API. <br /><br />- <strong>Microsoft Robotics Studio</strong> - <a href=\"http://www.surveyor.com/MSRS.html\">http://www.surveyor.com/MSRS.html</a> - Drivers for the SRV-1 in Microsoft Robotics Studio are now available. MSRS is a Windows-based environment for academic, hobbyist and commercial developers to create robotics applications across a wide variety of hardware. Key features and benefits include: end-to-end robotics development platform, lightweight services-oriented runtime, and a scalable / extensible platform. <br /><br />- <strong>Webots</strong> - <a href=\"http://www.cyberbotics.com/\">http://www.cyberbotics.com</a> - SRV-1 support is now included in Webots mobile robotics simulation software. Webots provides a rapid prototyping environment for modelling, programming and simulating mobile robots under Windows, Mac OS/X and Linux. The 3D modeling and physics are outstanding. </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2F8698%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (33,'en',4051,'MicroFly Tiny R/C Hovering UFO','<h5>Smallest Flying R/C Device Ever</h5>\n<p>When highly evolved nano-aliens descend upon our planet to mine our plethora of carbon monoxide gasses they\'ll surely be piloting something like this MicroFly Hovering UFO. This is by far the smallest flying R/C device we\'ve ever seen and it\'s damn impressive in the air.</p>\n<p>With a diameter not much larger than a silver dollar and a single propeller on the bottom, the MicroFly somehow manages to hover in the air as it spins and flashes its blue LED. The simple slider control allows only up-and down movement... but this makes it so easy to fly that anyone can instantly control it. Buy a few and create your own buzzing alien insect swarm... they use the same channel so you can control multiple MicroFly units from one remote.</p>\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr bgcolor=\"#999999\">\n<td align=\"center\">\n<p><span style=\"color: white; font-size: xx-small;\"><strong>MicroFly Tiny R/C Hovering UFO In Action</strong></span></p>\n</td>\n</tr>\n<tr>\n<td>\n<object width=\"300\" height=\"247\" data=\"http://www.youtube.com/v/Su8v2A42LH4\" type=\"application/x-shockwave-flash\">\n<param name=\"src\" value=\"http://www.youtube.com/v/Su8v2A42LH4\" />\n</object>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<h6>Product Features</h6>\n<ul>\n<li>Incredibly tiny flying r/c toy looks like a Mini-UFO </li>\n<li>Up/Down slider on remote controls hovering height </li>\n<li>Blue flashing LED on the top </li>\n<li>Charges from remote </li>\n<li>10 minute charge time. 5-7 minute fly time. </li>\n<li>Requires 6 AA batteries (not included) </li>\n<li>Dimensions: 65mm in diameter, 35mm high </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Frc%2Fa5ac%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (34,'en',4046,'Pentago','<h5>A Game for Smarties</h5>\n<p>Pentago was developed in Sweden: the land of Vikings, ABBA, and Ikea. Following the simplicity of design Sweden is known for, Pentago has clean lines and simple game play. In fact, it has just two play steps. First, put down a marble. Second, rotate a quadrant. Get five marbles in a row and you win. That\'s it. Game play may be simple, but winning can be as hard as a Viking\'s axe. Because you can rotate quadrants, your mind has to plan out moves in so many different directions.</p>\n<p>And just when you think you have everything thought out, your opponent rotates a quadrant and suddenly the whole board is different. Pentago is so cerebrally challenging, it won the Mensa Select Award in 2006 (as well as a bunch of other genius and game design awards). Each game board is handcrafted out of solid birch with aluminum accents and includes real glass marbles. Pentago is as beautiful as it is fun. And if you become a Pentago Master, no one will be able to deny you the title of \"genius!\"</p>\n<h6>Pentago</h6>\n<ul>\n<li>Includes: Birch and aluminum board, 36 glass marbles (18 black, 18 white), two birch marble holders, and instruction/strategy guide </li>\n<li>Board Dimensions: 5\" X 5\" X 1.25\" </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fgames%2F83ab%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.ftjcfx.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (35,'en',4047,'Rubik\'s Cube','<h5>Earth\'s Favorite Desktop Puzzle!</h5>\n<p>The date is 1974. The place? Budapest, Hungary. Erno Rubik, a passionate lecturer and admirer of geometry and 3D forms creates the world\'s most addictive and perfect puzzle - the Rubik\'s cube. It\'s now thirty years later and it\'s still the best selling puzzle in the Universe.</p>\n<p>If you grew up when the Rubik\'s cube made it\'s debut - you probably remember that every kid had one. Whether it was the full size or the keychain version they were as common in school as cell phones and GBAs are these days. Elementary school kids could be seen solving them with their feet on \'That\'s Incredible!\' and conspiracists believed the puzzle was an Eastern block tactic to distract American youth from their educations. In 1980 Cubaholics Anonymous was formally established. The eighties simply enjoyed RubikMania! Here we are in the 21st century and it\'s still just as addictive as it was thirty years ago.</p>\n<p>Think you can solve it? Think you have what it takes to beat the reigning world record for solving the Rubik\'s cube? The first world champion was an American high school student who took the Budapest World Championship in 1982 by solving the puzzle in just 22.95 seconds! The latest 2003 winner was Dan Knights of San Francisco who used the \'Fridrich\' system to beat the cube in just 20 seconds. Best of luck if you choose to delve into the realm of <a href=\"http://www.speedcubing.com/\" target=\"_BLANK\">competitive speedcubing</a>- you\'re gonna need it!</p>\n<p><em>How complex can it really be ThinkGeek?</em><br />There are 43 quintillion possible combinations with your Rubik\'s cube. That\'s 43 million million millions. There are about 30 million seconds in a year. You would need over a thousand million years assuming you could look at a thousand patterns every second just to see all the possible combinations. So if you are interested, we recommend you get started now and hope that cryonics becomes true science.</p>\n<ul>\n<li>Ages 8 and up </li>\n<li>Dimensions: 3\" cubed </li>\n<li>Comes complete with a hint and game suggestion book </li>\n<li>If you really want, you can easily find solutions on Google and Youtube </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fgames%2F69fe%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.lduhtrp.net/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (36,'en',4048,'Rubik\'s Mirror Blocks Cube','<h5>A puzzle to reflect on</h5>\n<p>In the future, everyone will only wear silver foil suits with big shoulder pads. We\'ll all have jobs with the word \"space\" in front of it (Space Accountant, Space Librarian, etc) and everything will be done with holograms. And in the future, Rubik\'s Cubes will also be shiny and silver. We tried to suck some from the future (using our ChronoSpatial Vaccuumatron), but something went wrong. We got the cubes, but they are a little . . . well, different.</p>\n<p>Actually, they are exactly as they should be: silver and weird. While it functions just like a regular Rubik\'s Cube, the <em>Rubik\'s Mirror Blocks Cube</em> has different sized blocks - which means it gets very distorted very fast. Instead of puzzling out simple colors, you\'ll be trying to fight with geometric space, which is great fun. What is also great fun is seeing the look on the faces of others as they fiddle with your cube. <em>Rubik\'s Mirror Blocks Cube</em> - the future is here.</p>\n<h6>Rubik\'s Mirror Blocks Cube</h6>\n<ul>\n<li>Works just like a regular Rubik\'s cube, but with different sized (and same colored) cubes, it gets really funky looking really fast. </li>\n<li>Includes: cube and stand </li>\n<li>Dimensions: 2.25\" cubed (duh) </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fgames%2Fb077%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (37,'en',4039,'Crystal Growing Kit','<h5>Space Age meets New Age</h5>\n<p>Since the dawn of time, shiny things have intrigued humankind. And ever since the first ape walked into a jewelry store, humans have loved crystals. They are delicate, they are beautiful, and understanding them is a key to advancing almost every branch of science. Now you can learn all about the beauty and wonder of crystals as you grow your own!</p>\n<p>This kit has everything you need to grow 4 different types of crystal formations. Three of them grow in clusters and the fourth is a solitary crystal specimen. Growing crystals is super easy too, so you\'ll be having fun and enjoying the glittering fruits of your labor in hours and days (which is incredible considering it takes the Earth millions and millions of years). Take that, Planet Earth! And this crystal kit is the gift that keeps on giving, as once you\'ve made your crystals, you can share (or sell) them. Learning and profit all rolled into one - isn\'t science grand?</p>\n<p><strong>Please Note:</strong> This kit also teaches you not to drink or eat chemicals included in crystal growing kits (so don\'t do it).</p>\n<h6>Crystal Growing Kit</h6>\n<ul>\n<li>Grow your own space age crystals: 1 single crystal and 3 different crystal clusters </li>\n<li>Includes: 1 bag of \"Citrine\" growing chemical (monoammonium phosphate), 1 bag of \"Ruby\" growing chemical (potassium aluminum sulfate), 3 crystal growing trays, 2 spatulas, instructions, and illustrated information booklet </li>\n<li>Crystals grow up to 4\" in diameter, but can be transferred into larger containers to continue growth </li>\n<li>Recommended for ages 12 and up </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2Fa72f%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (38,'en',4040,'LaQ Japanese Building Set','<h5>Imported Geometric Building Nirvana</h5>\n<p>Here at ThinkGeek we can never seem to get our hands on enough construction sets. From Lego, to Capsella... Erector to Lincoln Logs, we love them all. That\'s why we were pretty stoked to discover this unusual LaQ construction set from Japan. The unique modular snap together system employs square and triangular panels with five different joint types to hold them together. You\'ll enjoy mastering the intricacies of the construction combinations available. The genius is in the simplicity, and soon you\'ll be building 3D geometric constructions Lego can\'t touch.</p>\n<h6>Choose from three different Models</h6>\n<p><strong>Tyrannosaurus Set</strong><br />Builds a T-Rex, Plesiosaurus and Ammonite. All white parts. 320 pieces</p>\n<p><strong>Triceratops Set</strong><br />Builds a Triceratops, Mammoth and Allosaurus Head. All white parts. 320 pieces</p>\n<p><strong>Hamacron Set</strong><br />Larger set with 650 pieces of various colors. Includes 18 piece wheel set to add wheels to your creations.</p>\n<h6>Product Features</h6>\n<ul>\n<li>Unique construction set imported from Japan </li>\n<li>Innovative construction system uses square and triangular panels with five different joint types to hold them together </li>\n<li>Build complicated geometric structures easily </li>\n<li>Three different building sets to choose from </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2Fb8ca%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.awltovhc.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (39,'en',4041,'Game of Life Kit','<h5>Not <em>Your</em> Life... the simulation created by mathematician John Conway</h5>\n<p>When you hear \"The Game of Life\" do you think of the Milton Bradley classic board game? If so... stop reading this and go back to geek school. However if you remember the cellular automation simulation invented by John Conway in the 1970\'s then we\'ve got a cool little item for your DIY retro geek pleasure.</p>\n<p>The game of life is set-up as a grid of \"cells\" following only four simple rules:</p>\n<p><strong>1.</strong> Any live cell with fewer than two live neighbours dies, as if by loneliness.<br /><strong>2.</strong> Any live cell with more than three live neighbours dies, as if by overcrowding.<br /><strong>3.</strong> Any live cell with two or three live neighbours lives, unchanged, to the next generation.<br /><strong>4.</strong> Any dead cell with exactly three live neighbours comes to life.</p>\n<p>As the initiate amongst you no doubt recall, these rules lead to some cool evolving pixel based \"organisms\" like the glider, the blinker and the infamous glider factory. The Game of Life Kit allows you to build your own electronic LED based version of... the Game of life. You get a 4x4 grid of 16 LEDs that represents a small portion of the cellular grid. Push the button on the front and you reset the simulation and automatically populate it with random life. If all the cells die out completely the simulation automatically resets. The coolest part is that you can buy multiple kits and connect them together in any configuration to create a larger game board.</p>\n<p><strong>Important Note:</strong><br />Assembly is definitely required. You\'ll need a soldering iron, some solder, wire clippers and a small amount of skill. It will take you about 30 minutes to assemble the kit.</p>\n<h6>Product Features</h6>\n<ul>\n<li>Electronic kit runs the classic \"Game of Life\" simulation created by mathematician John Conway </li>\n<li>Each kit displays a 4x4 grid (16 LEDs) </li>\n<li>Connect as many kits as youd like, in any configuration, to create a larger game board </li>\n<li>On/Off button to save power, also for resetting the display </li>\n<li>Automatically resets if the colony has died or stagnated (regeneration) </li>\n<li>Assembly required. You will need a soldering iron, some solder, wire clippers and a small amount of skill. </li>\n<li>Runs off of 2 AA batteries (not included), but can be easily modified to run off of USB or AC Adapter power </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2Fae39%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.ftjcfx.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (40,'en',4044,'Classic Video Table Tennis Kit','<h5>Build Your Own Gaming Nostalgia</h5>\n<p>Blocky table tennis with a ball and two paddles was one of the first real video games to make it into mainstream culture. It took an awful lot of high-tech electronics in 1972 to create this classic game. Now however you can cobble together your very own Classic Video Table Tennis Game with a little determination and some basic soldering skillz. This kit contains a pre-printed circuit board and 39 various resistors, diodes, and capacitors. Solder everything together (following the instructions we hope) and plug the audio and video into any standard TV for instant Table Tennis action. You can play against another lowly human or challenge the computer at four different skill levels.</p>\n<p><strong>Please keep in mind you\'ll need to provide your own soldering iron, solder, philips head screwdriver and three AA batteries to complete this kit. We of course recommend the <a href=\"http://www.thinkgeek.com/gadgets/tools/69d3/\">Cold Heat Soldering Tool</a> to avoid those singed fingers.<br /></strong></p>\n<h6>Product Features &amp; Specifications</h6>\n<ul>\n<li>One or Two Players (Play against another human or the computer) </li>\n<li>Four Difficulty Levels </li>\n<li>Digital Controls </li>\n<li>Kit Contains All Electronic Parts Needed </li>\n<li>Tools Required and NOT Included: Soldering Iron, Solder, Small Philips Head Screwdriver </li>\n<li>Build Time: About 1 to 2 hours depending on skill level </li>\n<li>Connects to any Television (NTSC Only) using standard RCA style Video Cables (Not included) </li>\n<li>Requires 3 AA Batteries </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2F8546%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.ftjcfx.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (41,'en',4035,'DIY Music Box Kit','<h5>Time to Tinkle!</h5>\n<p>Music boxes came to prominence in the 19th Century, but their history dates back a few hundred more years. You see, there was this bell ringing dude who got tired of all the hard work. So, he decided to engineer a device to make his life a little more hip. It was a cylinder with metal studs. Each stud (as the cylinder rotated) operated cams which rang the bells. Further research determined that the first song played on this new system was the theme to Super Mario Brothers!</p>\n<p>And so that\'s the first song we made when we got our hands on this awesome little kit (albeit with one note wrong to adjust the key). This music box kit is a bit more open source, though. There are no pins, but a strip of paper, which you punch yourself. That\'s right, you punch all your own songs! No more listening to \"Memory\" - it\'s time for Koji Kondo\'s greatest hits (the genius behind many of Mario\'s and Link\'s most memorable themes). We\'d love to chat more, but it\'s time for us to compose a lullaby for Princess Peach.</p>\n<p><a href=\"http://www.thinkgeek.com/images/products/other/diy_smb.pdf\">Click here</a> to download a PDF blueprint of the Super Mario Brothers theme. \'Cause sharing is caring.</p>\n<ul>\n<li><strong>Music Box Kit</strong> <br />- <strong>Contents:</strong> Music Box Thing (2 octaves, key of C), Hole Punch, \"Happy Birthday\" Pre-Punched Strip, 3 Blank Strips, and Instructions. <br />- Each strip is approx. 1.75\" x 18.75\" - and 80lb paper (in case you want to make your own strips!) </li>\n<li><strong>Refill Pack</strong> <br />- 5 blank strips (approx. 1.75\" x 18.75\" - and 80lb paper). </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2F8f7f%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (42,'en',4034,'The Amazing Desktop Dinosaur Plant','<h5>A Pre-historic Evergreen That Just Won\'t Die</h5>\n<p>This one-of-a-kind plant has lived on the Earth for over 290 million years and has the ability to &ldquo;come back to life&rdquo; (much like the undead) over and over again for hundreds of years! Simply place this seemingly dead ball of foliage in water and within hours it transforms into a vibrant green blood-sucking evergreen. Ok, we are kidding about the blood-sucking part. It\'s still amazing though! It\'s also great for lazy folks since you can forget to water your Dinosaur plant whenever you want! It will simply dry up and hibernate for up to fifty years and will spring to life every time it is given water.</p>\n<p>Some Interesting Tidbits about your Dinosaur Plant:</p>\n<ul>\n<li>During the Carboniferous period these plants used to grow over 120 feet tall (bigger than a T-rex) </li>\n<li>When dry it curls up into a tight ball so that the wind can easily roll it to a new location or cubicle, hopefully closer to moisture. </li>\n<li>Retains 3% of its water when it is dehydrated. </li>\n<li>Grows to be about 4\" </li>\n<li>Enjoys life so much it survived the Ice Age</li>\n</ul>\n<h6>Kit includes:</h6>\n<ul>\n<li>Live Dinosaur Plant (<em>Selaginella Lepidophylla</em>) </li>\n<li>Bag of genuine Volcanic Lava Rock </li>\n<li>Display Bowl </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356324?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fscience%2F8039%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek.com</a><img src=\"http://www.tqlkg.com/image-3652263-10356324\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (43,'en',4027,'Inanimate Character Stickers','<h5>The word of the day is: Anthropomorphize!</h5>\n<p>We do it every day, though mostly without thinking about it - we get angry at the stapler that mangled our presentation, or the phone when it can\'t get a signal. We say we \"love this coffee mug,\" and sometimes we even imagine a face on the clock on the wall. It\'s called anthropomorphizing, and it\'s where we imbue human characteristics to inanimate objects.</p>\n<p>But are they really inanimate? Certain mythologies suggest that the more we use and include these objects in our daily lives, the more connected they become with our lives, thoughts and feelings. They are pleased when we use them, and are sad when they\'re discarded. Think back to that stuffed monkey doll you had when you were a kid. You threw him away one day, and there he sits - vacuously staring at rotting garbage in a landfill for all eternity. Not very nice at all!</p>\n<p>So some of these objects can be construed as to have a personality. Why not make it official? Stick a couple of googly eyes, and a goofy grin on a coffee mug, and hey-presto! Your happy smilin\' coffee-buddy is happy to let you drink from his skull! That letter-scale looks a little like Domokun, doesn\'t he? I think that tape dispenser has something evil on his mind&hellip; Hmm&hellip;</p>\n<p>Two sheets of eyes and mouths in various shapes, sizes and expressions, more than 100 stickers, are waiting for you to give life to the lifeless. That\'s sorta godlike!</p>\n<p>Product description is taken from <a title=\"ThinkGeek\" href=\"http://www.thinkgeek.com/\">www.thinkgeek.com</a>. You can buy this product <a href=\"http://www.thinkgeek.com/geektoys/cubegoodies/9866/\">here</a>.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F9866%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (44,'en',4020,'Tetrius Puzzle Game Magnets','<h5>Puzzle Gaming for your Fridge</h5>\n<p>Bring some classic puzzle style gaming to your fridge or whiteboard with this set of seven colorful magnets. Perfect for holding up your TPS reports, carry-out pizza menus and notes to self to \"play more puzzle games\", these magnets fit together in a most pleasing manner. Just watch out for when you\'ve stacked up all your pieces and are just waiting for that one long rod to clear four rows at once... when you realize these are only magnets.</p>\n<h6>Product Features</h6>\n<ul>\n<li>Puzzle gaming style magnets stick to most ferrous surfaces </li>\n<li>Perfect for your fridge or whiteboard </li>\n<li>Set of seven magnets </li>\n<li>Each magnet approximately 1\" high </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F9928%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.ftjcfx.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (45,'en',4011,'USB Webcam Rocket Launcher','<h5>Shoot while you Chat</h5>\n<p>Where would we be without Instant Messaging? It lets us stay in contact and chat with friends all over the world (especially delightful during work hours). But sometimes the urge to reach out and shoot someone is unbearable. And that\'s where the USB MSN Missile Launcher comes in. Don\'t be content with sharing images, jokes, and assorted links with your friends - it\'s time to share missiles!</p>\n<p>You\'ll have your USB MSN Missile Launcher set up within minutes and that\'s when the fun begins. For you see, as you chat you and your buddies can control each other\'s launcher. And since each USB MSN Missile Launcher has an integrated webcam (which can be used as just a webcam if you haven\'t any buddies), you\'ll know exactly when you are aimed at your buddy\'s head. Then fire away! The only downside is that your buddy can do the same to you! The upside is, you can always retaliate by turning your buddy\'s USM MSN Missile Launcher to face the door of his office and peg his boss in the gut when he/she comes to lecture your buddy about using IM for non-work-related matters. Ah the joys of progress . . . and revenge.</p>\n<h6>USB MSN Missile Launcher</h6>\n<ul>\n<li><strong>Includes:</strong> USB MSN Missile Launcher, 3 Foam Darts, Target, Software, Instructions </li>\n<li><strong>Features:</strong> <br />- Aim and fire at targets using the built-in webcam and MSN Messenger.<br />- Take control over your buddy\'s missile launcher and shoot them! <br />- Missile Launcher can pan left and right and tilt up and down. <br />- Sound effects (from your computer) when you launch your buddy\'s missiles. <br />- Can also be used as just a webcam. </li>\n<li><strong>USB Cord Length:</strong> 3 feet </li>\n<li><strong>Range</strong>: 15 feet </li>\n<li><strong>Software Compatibility:</strong> Windows XP/2000/Vista </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2Fa1c2%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.ftjcfx.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (46,'en',4014,'Levitating Desktop Globes','<h5>Levitation Is No Longer The Sole Domain Of Yogis Or Magicians!</h5>\n<p>These electromagnetic suspended globes are actually high-tech instruments. A magnetic field sensor continually measures the height at which the globes are suspended. This sensor feeds that data into a micro computer in the base of the unit. This computer then calibrates the electronic magnets at the top of the frame based on the distance between the globes and the top magnet to keep the globes correctly positioned. All in the blink of an eye! So not only do these look great on your desktop levitating in thin air, they are also technology marvels!</p>\n<p>When you initially get your globe and plug it in, it may take you a few minutes to correctly position the globe in between the base and top magnets. You\'ll figure it out quickly enough and before you know it, you\'ll be able to set it up in just a few seconds every time. Once it\'s levitating, you can even spin it slightly and watch the globes rotate for extend periods of time. Just like the real thing, except you get to control gravity ;)</p>\n<p>Your choice of either a 4\" or 8\" diameter globe. The 4\" model (8 inches tall including frame) features an attractive brushed metal silver frame. The 8\" globe (14.5 inches tall including frame) comes with a chrome finished frame with a black and multi-colored globe. The level of detail on the 8\" globe is greater than that of the 4\". Each comes with a power supply (US, 120V only). The 8\" model also features a red LED indicating power status built into the frame.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.tkqlhce.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F61da%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (47,'en',4017,'Levitron - Anti-Gravity Top','<h5>Yes. It\'s Levitating. Really.</h5>\n<p>Of course it\'s using MAGIC to do so - the magic of magnetic repulsion and attraction that is. The Levitron is a magnetic anti-gravity top that is just as fun to setup as it is to watch in motion.</p>\n<p>Basically, you just use the included top weights to adjust to the perfect weight (which varies from location to location and surface to surface), then you adjust the legs on the base. Then spin the top, slowly lift the lifter (with top on it) until the top escapes the platform and release! Voila. A top levitating in mid air. And depending on how well you set it up, it will spin up to five full minutes! Wave your hand underneath (or anything non-metallic), and amaze your family and co-workers! And all this with no batteries - sheer magnetic wizardry!</p>\n<p>Of course it is not a piece of cake to setup. You have to observe it carefully as you are making the appropriate adjustments to the weight and to the base. It can be challenging but the reward is well worth it. Move the Levitron to another location though and you\'ll have to recalibrate the weights and levelers! Take the challenge and master it. It may take you five minutes, it may take longer.</p>\n<p>Your Levitron arrives complete with:</p>\n<ul>\n<li>One Magnetic base w/three adjustable legs </li>\n<li>One floating top </li>\n<li>One lifter </li>\n<li>One spinning plane </li>\n<li>Several adjustment weights </li>\n<li>Instruction sheet &amp; Levitron Physics guide </li>\n</ul>\n<p>Your choice of either:</p>\n<ul>\n<li>Omega Edition - Red/Black Molded Plastic </li>\n<li>Note: The Cherry Wood Edition is no longer available. Sorry! </li>\n</ul>\n<p>Because of the high strength of the magnet in the base (and that it is purposely not shielded), ThinkGeek recommends you do not set the Levitron next to your monitor! Duh.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F5bb6%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.awltovhc.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (48,'en',4018,'ROMP (Random Oscillating Magnetic Pendulum)','<h5>Experience Two Dimensional Magnetic Energy At Your Desktop!</h5>\n<p>Designed to illustrate the chaotic and random forces that effect us all, ROMP is also just plain fun. ROMP\'s swinging pendulum darts and dodges through magnetic force fields that you setup using the included magnets. These periodic energy \'tugs\' cause the pendulum to erratically drift through the magnetic fields thus exhibiting total chaos (random systems, by nature, are unpredictable). Can you make order out of this chaos? If so, you could probably write a bestseller or do something important enough to not be sitting at your computer reading this very paragraph right now. If not, try anyways and, more importanly, have fun trying.</p>\n<p>Get a ROMP and experience true chaos at the comfort of your desktop!</p>\n<h6>ROMP</h6>\n<ul>\n<li><strong>Includes:</strong><br />- 9 magnets <br />- Powder coated metal base <br />- Swinging pendulum <br />- 90 degree post <br />- Instruction booklet with fun facts and games (like Basketball and Keep Away) to play with your ROMP </li>\n<li><strong>Dimensions:</strong> 4.75\" x 4.75\" x 8.75\" (assembled) </li>\n<li><strong>Magnet Type:</strong> Neodymium </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F6758%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (49,'en',4028,'Shock Ball','<h5>One potato, two potato, three potato . . . FRAK!</h5>\n<p>History lesson, boys and girls; everyone take their seats. In 1539, people were bored and poor. One day, Filius Giles of North Southwesteasternshire thought of a great way to pass the time. He took a chunk of lead (called a \"pohtaytoh\" by blacksmiths of the time) and heated it up. Gathering some friends, he dipped the pohtaytoh in lard with his tongs and threw it to his closest friend. The lard began melting away as each person threw it to another, hoping not to get burned by the \"hot pohtaytoh.\" Apparently, everyone had a blast, and the game has survived today.</p>\n<p>And now Hot Pohtaytoh has evolved into the Shock Ball. The Shock Ball plays just like the classic game - with one major alteration. This ball doesn\'t want you to win. It wants you to drop it and lose. And to get you to drop it, the Shock Ball (as you probably have guessed) shoots out random electrical shocks (and lights up to make the experience more enjoyable). If you can hold onto the Shock Ball, you will quickly become the Shock Ball Champ. And everyone will know you have nerves of steel and a very unique hairstyle (you know, from all the shocks). Shocking fun for everyone.</p>\n<p>The Shock Ball is approx. 2.5\" in diameter,has an auto off function (for a modicum of safety), and uses 2 AAA batteries (not included).</p>\n<p><strong>Warning: The Product Emits An Electric Shock. Keep out of reach of children. Not suitable for those under the age of 14. This is a novelty item, not a toy. May interfere with electrical devices such as pacemakers. </strong></p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F91b8%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (50,'en',4029,'Shocking Pen','<h5>The pen <em>is</em> mightier than the sword</h5>\n<p>This lovely desktop novelty needs little introduction, but we shall provide one so that we copywriters might keep our jobs. It\'s very simple. Just casually stick the shocking pen on your favorite co-worker\'s desk, then return to your own desk, go back to your or your ex\'s Facebook page, and patiently await. At some point, the presence of a novel looking thingy on the victims desk will stimulate their need to touch and interact with it. Much like a pickle might if it suddenly showed up on your desk.</p>\n<p>The victim will then pick up the pen, and, without even a nub of conscious motion, their thumb will immediately migrate to depress the top of the pen in hopes of discovering a fine new writing instrument. Instead, as they depress, they will complete a circuit that creates a small magnetic field, which then simulates an alternating current and provides relatively high voltage to the depression point on the thumb. It all happens in a split second, as will the screech and instant profanity, but the office memories will last a lifetime...</p>\n<p>Please note, this pen does not actually write as it contains no ink nor an appropriate tip to dispense said ink. So, I suppose we should have called it a \'Shocking Mock Pen\' instead of a \'Shocking Pen\' - but, oh well - you\'ll forgive us, right? To make you feel better we\'ll tell you that it\'s about 5.5 inches tall. Oh, and it\'s not for children. This is an adult novelty toy. Sorry Grandma, get Timmy <a href=\"http://www.thinkgeek.com/geektoys/games/69fe/\">this</a> instead.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2Fa4f4%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (51,'en',4057,'Lessons From the Top : The Search for America\'s Best Business Leaders [PDF]','<h5>Author: Thomas J. Neff</h5>\n<p>What does it really take to run a successful company today? Thomas Neff and James Citrin, U.S. chairman and managing director, respectively, of the Spencer Stuart executive-search firm, offer revealing answers in <em>Lessons from the Top: The Search for America\'s Best Business Leaders</em>. In 50 short but perceptive profiles, they identify and analyze the men and women who drive today\'s most successful corporations. As might be expected, the authors lean heavily on well-known CEOs such as Steve Case of America Online, Michael Dell of Dell Computer, and Howard Schultz of Starbucks. But they also look at a number who don\'t get the same publicity, including Fannie Mae\'s Frank Raines, the Gap\'s Don Fisher, and Autodesk\'s Carol Bartz. The result is a broad but surprisingly consistent palette of personalities and philosophies that in a concluding section Neff and Citrin highlight by synthesizing into 10 common traits (passion, intelligence, communication skill, high energy, controlled ego, inner peace, a defining background, strong family life, positive attitude, and a focus on \"doing the right things right\") and six core principles (live with integrity, develop a winning strategy, build a great management team, inspire employees, create a flexible organization, and implement relevant systems). This book is for managers and anyone else looking for the patterns of success, both in and out of business. <em>--Howard Rothman</em> <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<p><strong>From Publishers Weekly</strong><br />Headhunters Neff and Citrin of Spencer Stuart U.S. set out systematically to identify, profile, interview and capture the vision of the nation\'s top 50 CEOs. Through their company, they commissioned Gallup polls, gathered performance data and constructed a list of intangibles (\"showed the ability to overcome challenges,\" \"demonstrated consistent strength of character,\" etc.). The final results don\'t look all that different on the surface from countless other books purporting to offer the managerial motherlode, but in this case the difference is in the details. Interviews with AT&amp;T\'s Mike Armstrong, Charles Schwab, Martha Ingram (one of four women named), Louis Gerstner, Bill Gates and Bill Marriott are all illuminating, revealing complementary aspects of captaining the ship without making redundant observations. A few of the notions even seem worker-centered: Starbucks\' Howard Schultz points to the decision to provide equity and stock options to employees, even part-timers, as one of the main reasons why his company\'s attrition rate is below 60% annually (compared with the national average of 250%). The book is filled with such ideas, presented with a minimum of self-promotion from their purveyors. A final section of \"lessons learned\" offers a \"new definition of success\" that begins \"live with integrity and lead by example.\" As concise and clear a management guide as readers are likely to find, this is a great tip sheet on business leadership. (Aug.) <br />Copyright 1999 Reed Business Information, Inc. <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>Publisher:</strong> execubook.com (August 30, 2001)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005Q5J4?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005Q5J4\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005Q5J4\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (52,'en',4036,'What do I do Now? : Dr. Foster\'s 30 Laws of Great Decision Making [PDF]','<h5>Author: Charles Foster</h5>\n<p>Execubooks are eSummaries of books for mobile professionals, available in single-copy or by subscription, and optimally formatted for onscreen reading on laptops or handhelds - so you can stay abreast of leading business wisdom, wherever you have a moment! Do you panic at the thought of making an important decision in your life? When faced with the prospect of changing jobs, getting married, or moving to a new home, do you vacillate, procrastinate, and run, hoping that the need to make a choice will go away? Or do you jump in headfirst just to get the decision over with? If this sounds familiar, you are in luck, as Dr. Foster, Director of Boston\'s Chestnut Hill Institute, is here to help! Based on his twelve-year study, What Do I Do Now? presents 30 laws that great decision makers use to navigate their way successfully through tough choices.</p>\n<h6>About the Author</h6>\n<p><strong>Charles Foster, Ph.D., M.B.A.,</strong> is Director of The Chestnut Hill Institute, in Boston, and has been a clinician, consultant, and researcher for twenty-five years. He has lectured at Harvard Medical School and has conducted research under a grant from the National Institute of Mental Health. He is the author of <em>Truth Without Fear: How to Communicate Difficult News in Tough Situations.</em> He lives in Boston. <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>Publisher:</strong> execubook.com (August 30, 2001)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005Q5JE?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005Q5JE\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005Q5JE\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (53,'en',4061,'How to Use Google: The 30 Most Important Tips, Hacks and Tricks [PDF]','<h5>Author: Tod Sacerdoti</h5>\n<p>How to Use Google is a fun and detailed manual about all the Google functionality you didn\'t know existed! It is the most direct and instructional book about Google on the market today, and is written in an enjoyable magazine color format with high-quality screen shots.</p>\n<p>How to Use Google contains specific examples for each Tip, Hack or Trick and contains active HTML links that take you directly to the web as you are learning.</p>\n<p>What is Google and Why is it Important? Google is the world&rsquo;s largest and most powerful search engine. In other words, it&rsquo;s an incredible tool that allows you to locate a wide array of information on the Internet including websites, news, maps, phone numbers, stock quotes and much, much more.</p>\n<p>Google is important it is our gateway to access information online. Every day more information is available electronically and we continue to discover new reasons to need access to that information. In fact, many people are finding themselves accessing Google 10, 20 or even 100 times a day. So given the usefulness of Google and the increasing importance of Google in our lives, you would expect users to understand how to use Google and all of its services. Unfortunately, the opposite is true.</p>\n<p>Most Google users only know how to perform basic Google searches and are actually quite inefficient in these efforts. As a result, most users are wasting their time, energy and money. This book is designed for beginner to intermediate level Google users who want to be more productive with Google. We will begin with an overview of how to maximize Basic Search, and then progress through advanced techniques, other types of search, and information on other Google services. Lastly we will reveal some Google hacks and tricks for more expert users.</p>\n<h6>About the Author</h6>\n<p>TOD SACERDOTI is an author, entrepreneur and self-proclaimed Google addict. He holds an MBA from the Stanford Graduate School of Business and a passion for technology and media. He also shares a desire to further educational opportunities for others and 10% of the proceeds from this book will be donated to education-focused charities.</p>\n<p>Tod&rsquo;s next eBook project will be a new series called Titanium Summaries which will be launched this summer - so keep your eye out! Tod can be reached at tod@titaniumbooks.com.</p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>File Size:</strong> 371 KB</li>\n<li><strong>Digital:</strong> 12 pages</li>\n<li><strong>Publisher:</strong> Titanium Books (May 10, 2003)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B0000AUH95?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B0000AUH95\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B0000AUH95\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (54,'en',4015,'Bare Metal Bender Wind-up','<h5>Bite my shiny, metal ass!</h5>\n<p>Everybody loves a sarcastic, galactically saavy, cigar smoking, prank prone, selfish, beer drinking robot. Enter Bender. Bender was his mothers 1729th son. His father killed by a can opener, Bender went on to college and majored in Bending and minored in Robo-American studies. This Unit 22 Bending Unit is proof positive that every desktop needs a tin metal, intriguing wind-up robot before it can be considered complete. Yep.</p>\n<p>8.5\" tall wind up die cast interactive Bender, from Futurama (fully licensed). Twist the antenna on top of his head and his expression will change from \'normal\' bender to \'angry\' bender. Perfect to fend off unwanted visitors. Optionally insert an included cigar into his mouth for those times when Bender just needs to mellow down. Bare Metal Metal Bender includes a can of Mom\'s Old Fashioned Robot Oil and a cigar! And arms articulate! Wind up key to make Bender walk inserts into side and is removable (his arms will also swing while in motion!). Open up Bender\'s ribcage compartment and see what\'s going on inside! Or just wind him up and let him walk all over your TPS reports. He doesn\'t care. He\'s bender, you new best desktop friend with an attitude.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.kqzyfj.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F60d4%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (55,'en',4023,'DIY Drinking Strawz','<h5>Suck it!</h5>\n<p>Liquids are fun outside your body (like swimming in a lake or pre-set grape gelatin) and they are fun inside your body (like Bawls, Jolt, and the occasional Zima - just kidding). But really, the big problem comes when deciding how to make fun liquid get from outside your body to inside your body. Our favorite method is drinking. And when we drink, we like using straws. But sometimes a straight straw is boring. So we combined a few chemicals in a test tube and DIY Drinking Strawz were born.</p>\n<p>Composed of 44 dishwasher safe components, DIY Drinking Strawz guarantee that liquids get into your body exactly how you want them to. Want to mix drinks? Well, then build your straw to dip into two (or more) cups. Want to share? Then build an extra drinking branch. Want to mix drinks AND share? You can do that too!!! Amazing, ain\'t it? And hey, if you want to go nuts and really suck, you can buy more than one set and make a mondo-gigantic straw. You could be the Suck-Meister.</p>\n<p>Includes 44 dishwasher safe pieces: 20 flexible rubber connectors and 24 translucent straw pieces (12 - 5\" strawz and 12 - 2.875\" strawz).</p>\n<p><strong>Please Note:</strong> Colors may vary from those shown.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F9470%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (56,'en',4032,'Smart Mass Thinking Putty','<h5>The Thinking Persons Putty</h5>\n<p><strong>The Ultimate Stress Reduction</strong> office toy is here. Of course you remember playing with putty as a kid. Welp, this aint your kids putty. Adult sized, and as feature-rich as your favorite Operating System, the Smart Mass putty from ThinkGeek makes living life fun all over again. Like to fidget while sitting in front of the monitor? Enjoy being the envy of all those who surround you? Trying to make an impression on that new coder down the hall? Smart Mass putty will help...</p>\n<p><em>But ThinkGeek, What Will My Smart Mass Really Do ??</em></p>\n<ul>\n<li>It Bounces! </li>\n<li>It Stretches, Contorts &amp; Squishes ! </li>\n<li>It lifts comics! (as any self-respecting putty would) </li>\n<li>It Shears &amp; Tears ! <a href=\"http://www.thinkgeek.com/geektoys/cubegoodies/5ac8/detail/\">(learn how!)</a> </li>\n<li>It Even Shatters !!! <a href=\"http://www.thinkgeek.com/geektoys/cubegoodies/5ac8/detail/#shatter\">(learn why!)</a> </li>\n<li>It Drips From Ceilings <a href=\"http://www.thinkgeek.com/geektoys/cubegoodies/5ac8/detail/#drip\">(learn how!)</a> </li>\n</ul>\n<p><em>But ThinkGeek, Can I Achieve World Domination With My Smart Mass?</em></p>\n<ul>\n<li>Yes. Of course. All ThinkGeek products may be used to help you achieve World Domination. </li>\n</ul>\n<p>Our Smart Mass putty is just too much fun. Find out for yourselves how magically enticing and addictive playing with putty really is. As you find more and more ways to get creative with your putty, you\'ll, well, find more ways to get creative with everything! It\'s simply that stimulating!</p>\n<p>Your choice of either:</p>\n<ul>\n<li><strong>Sunburst (new!)</strong> - A Hypercolor! Heat sensitive putty. Jumpstart your day with some sunshine! Vibrant orange shifts into an awakening yellow with the touch of your hands or warmth from your coffee mug. Starts out Orange. </li>\n<li><strong>Twilight (new!)</strong> - A Hypercolor! Heat sensitive putty. Just as an evening sky melts into the dark of night, this putty\'s deep purple color disappears with a touch to reveal a fluorescent blue complexion. </li>\n<li><strong>Martian Sea</strong> - A color shifter that swirls deep clay reds and orange with a yellow to green sheen depending on the light. </li>\n<li><strong>Dark Matter</strong> - A swirling mass of matte black. Your very own personal, warpable, black hole. Has magnetic properties: pull out a thin strand of dark matter and hold it near a magnet! </li>\n<li><strong>Solar Blue</strong> - A very soothing and energetic, vibrant blue. Intoxicating. Cosmically rapturous... </li>\n<li><strong>Oil Slick</strong> - A color shifter, Oil slick will look different in different lighting. From golds and yellow to pinks and emerald greens all swirling intelligently... </li>\n<li><strong>Atomic Bronze</strong> - Lustrous comes to mind. Atomic bronze sparkles and commands attention. Your very own precious metal. Looks similar to Martian Sea, but has a much more metallic sheen to it. </li>\n<li><strong>Atmosphere</strong> - Another color shifter. Atmosphere will morph into Cerulean highlights and features rich deep purples. A veritable alien, breathing, living atmosphere... </li>\n<li><strong>Alien Ooze (glows!)</strong> - Military grade phosphors power this extremely powerful glowing mass. Charges in light or through UV sources. Amazingly bright when glowing. Ghost like when not performing... </li>\n</ul>\n<p>Each tin arrives with an adult sized one fifth of a pound of Smart Mass putty. Wow. The putty is non-toxic and doesn\'t leave any gooey residue! Get tins for everybody in the office and at home lest you may find yours missing...</p>\n<p><strong>Note:</strong> - Your Smart Mass may seem like it has a mind of its own occasionally. That\'s because it does. And when not being used, your Smart Mass putty prefers to live in its comfortable tin where it can best plot World Domination Schemes.</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F5ac8%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.awltovhc.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (57,'en',4025,'Walking Robot Pencil Sharpener','<h5>Semi-Automatic Graphite Enhancement Automaton</h5>\n<p>Here are two little known facts:</p>\n<ol>\n<li>The graphite pencil was first mass produced in the year 1662. </li>\n<li>The first pencil sharpeners weren\'t made until 1893, which left many people disappointed by their pencil purchases for over 200 years.</li>\n</ol>\n<p>The first fact is a real fact; the second fact is a fake fact (that is, a joke). What can we say, the Walking Robot Pencil Sharpener has a very dry and unusual sense of humor. Our robot told us the above facts. And then it sharpened our pencil and walked away.</p>\n<p>This is perhaps the coolest pencil sharpener ever to walk on its own. Just open its chest, insert a pencil, twist to sharpen, and remove your pointy pencil. By sharpening your pencil, you have secretly wound the 4\" robot\'s power core and it will now begin walking toward you (or in whatever direction it\'s facing). The shavings empty into the robot\'s cranial chamber, which can be easily opened for emptying. If you don\'t have a pencil handy, just use the included key to wind up the robot. Either way, your robot will walk with delight. Sorry if it proceeds to tell you bad jokes, though.</p>\n<p><strong>Please note:</strong>The robot will sharpen any regular pencil, but only some round pencils seem to wind it up. All hexagonal pencils, however, seem to give your robot the energy it needs to walk (pencils not included).</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.anrdoezrs.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fhomeoffice%2Fsupplies%2F8972%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.tqlkg.com/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (58,'en',4019,'Albert Einstein Action Figure','<h5>The Brains Over Brawn Action Figure</h5>\n<p>Dressed for intense classroom action, this Albert Einstein Action Figure stands with a piece of chalk in his hand, poised to explain relativity or do battle with the forces of entropy. Fits in nicely with any office, cube or dorm decor and features realistic disheveled hair! Very few people on this planet have ever had the ability to go as far as Einstein has in comprehending the fundamental principles of the Universe. So we here at ThinkGeek think of that as sort of a super power worthy of the title \'Superhero Action Figure\'. Sure he might not have been able to fly, breathe underwater or emit spider webs from his wrist - but he could melt your mind in a heartbeat with just a single attempt at explaining the Universe to you. Now that\'s power!</p>\n<p>\"The eternal mystery of the world is its comprehensibility.\" - <br /><em>Albert Einstein</em></p>\n<p>Your Albert Einstein action figure measures 5-1/4\" (13.3 cm) tall and is made out of hard vinyl. Illustrated blistercard included!</p>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.dpbolvw.net/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F71a4%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (59,'en',4024,'Yoda Plush Backpack','<h5>Keep Your Things Safe It Will</h5>\n<p>Ever since its release in 1980, <em>The Empire Strikes Back</em> has fueled the dreams of geeks across the world. Considered by many to be the best of the <em>Star Wars</em> saga, it was the first film where we really learned what it took to be a Jedi. Sure Obi Wan taught us a little in <em>A New Hope</em>, but it wasn\'t until we met Yoda that we truly realized how powerful the Force was and how hard it was to master. Yoda was the pinnacle of what a Jedi should be: mighty and humble, wise and peaceful. Even though he was tiny, we got glimpses of his immense power (really, I mean a whole X-Wing?!?)</p>\n<p>Once we saw Yoda, we all knew we wanted a Jedi master of our own - a Jedi master who would take us running in the woods, ride on our back, and teach us the ways of the Force. We tried strapping a cat to our back, but it wasn\'t the same. A few months ago, our geeky hearts almost leapt out of our bodies when we saw this officially licensed backpack. Yoda&hellip;on our back&hellip;teaching us&hellip;and carrying our stuff inside him. Perfect! And, to make it even better, the straps are adjustable and sized for adults. All you need now is a dirty, sleeveless t-shirt and some khaki\'s and you will be ready for your Jedi training to begin. <em>You will be. You will be.</em></p>\n<h6>Yoda Plush Backpack</h6>\n<ul>\n<li><strong>Yoda Height</strong>: 25\" (from foot to top of head) </li>\n<li><strong>Pocket Depth</strong>: 13.4\" </li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.jdoqocy.com/click-3652263-10356311?url=http%3A%2F%2Fwww.thinkgeek.com%2Fgeektoys%2Fcubegoodies%2F817c%2F%3Fref%3Dc\" target=\"_top\">ThinkGeek Cube Goodies</a><img src=\"http://www.lduhtrp.net/image-3652263-10356311\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (60,'en',4060,'Why Should Anyone Be Led by You? (HBR OnPoint Enhanced Edition) [PDF]','<h5>Author: Rob Goffee</h5>\n<p>This is an enhanced edition of the HBR article R00506, originally published in September/October 2000. HBR OnPoint articles save you time by enhancing an original Harvard Business Review article with an overview that draws out the main points and an annotated bibliography that points you to related resources. This enables you to scan, absorb, and share the management insights with others. We all know that leaders need vision and energy, but after an exhaustive review of the most influential theories on leadership--as well as workshops with thousands of leaders and aspiring leaders--the authors learned that great leaders also share four unexpected qualities: 1) They selectively reveal their weaknesses; 2) They rely heavily on intuition to gauge the appropriate timing and course of their actions; 3) They manage employees with \"tough empathy\"; and 4) They capitalize on their differences. All four qualities are necessary for inspirational leadership, but they cannot be used mechanically; they must be mixed and matched to meet the demands of particular situations. Most important, however, is that the qualities encourage authenticity among leaders. To be a true leader, the authors advise, \"Be yourself--more--with skill.\"</p>\n<h6>About the Author</h6>\n<p>Rob Goffee is Professor of Organizational Behavior at London Business School. Gareth Jones is Visiting Professor at INSEAD. He was formerly Director of Human Resources for the BBC. <em>--This text refers to the <a class=\"product\" href=\"http://www.amazon.com/gp/product/1578519713/ref=dp_proddesc_1?ie=UTF8&amp;n=551440\">Hardcover</a> edition.</em></p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>File Size:</strong> 250 KB</li>\n<li><strong>Digital:</strong> 11 pages</li>\n<li><strong>Publisher:</strong> Harvard Business Review (March 3, 2009)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005REIH?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005REIH\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005REIH\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (61,'en',4058,'Making the Technical Sale : A Handbook for Technical Sales Professionals [PDF]','<h5>Author: Rick Greenwald</h5>\n<p>The technical sales professional fills a valuable role in the sales cycle of a software product. This book discusses how technical sales is different from general sales, details the full range of skills needed by technical sales professionals, illuminates the typical tasks technical sales professionals handle, and explores the role these people play on the sales team. It covers basics such as presentation skills, working in a team, and time management; specifics such as creating and delivering demos, working with groups of prospects, handling objections, and competitive positioning; and the overall technical sales cycle. Sales and project managers, consultants, and technical sales professionals will benefit from the depth of training offered in this book. <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<h6>About the Author</h6>\n<p>James F. Milberry of Easton, Pennsylvania, is principal of Milberry Consulting Group. Before founding his own business, he spent several years as a technical sales force manager, most recently with software providers Revere Inc. and Ingres Corp. He has personally trained more than 700 sales consultants in his career. He is co-author with Richard E. Greenwald of \"The Oracle WebDB Bible.\" <em>--This text refers to an out of print or unavailable edition of this title.</em></p>\n<p>&nbsp;</p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>Publisher:</strong> Muska &amp; Lipman (April 1, 2001)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005Y1OM?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005Y1OM\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005Y1OM\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (62,'en',4062,'Windows 2000 Server System Administration Handbook [PDF]','<h5>Author: Syngress</h5>\n<p>A complete overview of the Windows 2000 Server operating system provides key assistance for NT4 professionals in administering and supporting the system. Its detailed coverage will enable administrators to differentiate the features and technology changes from Windows NT 4.0 to Windows 2000. Twelve chapters provide detailed coverage of Active Directory, Microsoft Management Console, and new Windows 2000 hardware support, as well as information on implementing new Windows 2000 security options and upgrading networks to the new platform.</p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>Publisher:</strong> Syngress (November 7, 1999)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000069290?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000069290\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000069290\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO xlite_product_translations VALUES (63,'en',4063,'IgoUgo Travel Report: Chicago: The Inside Scoop from Experienced Travelers [PDF]','<h5>Author: Dawn Peterson</h5>\n<p>Few cities rival Chicago in the skyline department-anyone who\'s been there will tell you it\'s got some of the world\'s most enthralling architecture-but it doesn\'t rest on its laurels as the birthplace of the skyscraper. The capital of the Midwest also boasts a gamut of top-rated museums, restaurants, and caf&eacute;s, and innumerable bars and nightclubs that pay homage to the city\'s strong jazz and blues heritage. Factor in the city\'s legacy as the training ground for stand-up comics like Jim Belushi and Bill Murray, and its heyday as Al Capone\'s headquarters, and it\'s easy to see why the Windy City makes such an exciting getaway.</p>\n<p><span style=\"text-decoration: underline;\">IgoUgo Travel Report: Chicago</span> is not your average travel guide. Every piece of advice found in these pages comes from regular travelers and locals-not paid professionals or PR people-which means pure, unfiltered opinions on what to see (and what to avoid) during your trip. You\'ll find first-hand details on attractions and day trips, along with restaurant, nightlife, and activity recommendations in all price ranges and for all interests. You\'ll also see our contributors\' original photographs and insider tips on every page-no other travel guide can say that.</p>\n<p>IgoUgo contributors hail from all over the globe and travel just about everywhere in between. These avid travelers share their adventures and snapshots in online journals at IgoUgo.com, the world\'s premier web-based travel community. Our 350,000+ members have written over 300,000 journal entries covering more than 4,000 destinations. IgoUgo\'s emphasis on personal, real-life travel experiences has garnered a Webby Award for \"Top Travel Website\" as well as accolades from <em>Yahoo! Internet Life</em>, which voted it \"Best Travel Community.\"</p>\n<h6>Product Details</h6>\n<ul>\n<li><strong>Format:</strong> Adobe Reader (PDF)</li>\n<li><strong>Printable:</strong> Yes. This title is printable</li>\n<li><strong>Mac OS Compatible:</strong> OS 9.x or later</li>\n<li><strong>Windows Compatible:</strong> Yes</li>\n<li><strong>Handheld Compatible:</strong> Yes. Adobe Reader is available for PalmOS, Pocket PC, and Symbian OS.</li>\n<li><strong>File Size:</strong> 877 KB</li>\n<li><strong>Digital:</strong> 25 pages</li>\n<li><strong>Publisher:</strong> IgoUgo (July 1, 2005)</li>\n</ul>\n<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000089302?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000089302\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000089302\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5000,'en',5000,'Altec Lansing BackBeat 906 Wireless Headphones','<p>For a superior on-the-go listening experience, the BackBeat 906 headphones give you outstanding sound quality and clear, convenient communications thanks to a built-in microphone. Even better, they include a Bluetooth adapter that lets you stream music wirelessly from your iPod or other audio device.</p>
<ul>
<li>Enjoy your music without headphone cords</li>
<li>Built-in mic lets you switch to calls on your iPhone</li>
<li>Impressively boosted bass</li>
<li>Adjustable headset for optimal comfort</li>
</ul>
<h5>Overview</h5>
<p>The BackBeat 906 stereo headphones deliver rich, full-spectrum stereo music along with Plantronics dual-mic AudioIQ voice technology for exceptional call clarity. And for the ultimate in wireless convenience, they offer a Bluetooth adapter for your iPod or other audio device.</p>
<h6>Stay in control</h6>
<p>Hear the world outside with a quick tap of the OpenMic button on the eartip or enhance your music using bass-boost feature. You can also adjust the volume or change tracks with other eartip controls.</p>
<h6>Take comfort to the max</h6>
<p>Personalize your fit with telescoping and swivel adjustments, not to mention the elimination of dangling cords.</p>
<h6>Features</h6>
<ul>
<li>Wirelessly listen to music as you walk, run, work or play</li>
<li>Separate your voice from the noise using dual-mic AudioIQ technology</li>
<li>Hear your outside world with a quick press of the OpenMic button</li>
<li>Enjoy the full dynamic range of your music using bass-boost feature</li>
<li>Personalize your fit with telescoping and swivel adjustments</li>
<li>Easily store this flexible design in your pocket or bag</li>
</ul>
<h6>Did you notice?</h6>
<p>Bluetooth adapter lets you listen to music from your iPod without a headphone cord.</p>
<h6>Apple recommends for...</h6>
<p>Enjoying the rich stereo sound of the music on your iPod while engaged in activities where headphone cords get in the way.</p>
<h6>Technical specifications</h6>
<ul>
<li>Noise isolation: Contoured, non-occluding eartips</li>
<li>Input sensitivity: 91 dB SPL/V @ 1 kHz</li>
<li>Frequency response: 80 Hz - 12 kHz (-10 dB)</li>
<li>Impedance: 17 Ohms</li>
<li>Speaker: 14mm with enhanced bass</li>
<li>Input connector: Stereo Bluetooth adapter with 3.5mm plug</li>
<li>Weight: 1.2 oz./34.02 g</li>
</ul>
<h6>What\'s in the box?</h6>
<ul>
<li>Altec Lansing BackBeat 906 Wireless Headphones</li>
<li>Bluetooth adapter</li>
<li>AC charger with Micro-USB connectors</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001TK3ACA?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001TK3ACA\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001TK3ACA\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TV703VC/A?fnode=MTY1NDA0Ng&amp;mco=NDM0NjI1Mw\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5001,'en',5001,'Apple Dock Connector to USB Cable','<p>Use the Dock Connector to USB Cable to charge and sync your iPhone or iPod with your Mac or Windows PC.</p>
<h5>Overview</h5>
<p>This USB 2.0 cable connects your iPhone or iPod &mdash; directly or through a Dock &mdash; to your computer\'s USB port for efficient syncing and charging or to the Apple USB Power Adapter for convenient charging from a wall outlet.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B0013K56PK?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B0013K56PK\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B0013K56PK\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MA591G/A?fnode=MTY1NDA0OQ&amp;mco=NzcxNzE4\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5002,'en',5002,'Apple Earphones with Remote and Mic','<h5>Overview</h5>
<p>The Apple Earphones with Remote and Mic take the acclaimed Apple iPod Earphones and add a control capsule, located on the cable of the right earpiece, that includes a microphone and three buttons.</p>
<p>Here\'s what you can do with this convenient remote:*</p>
<ul>
<li>Adjust the volume by pressing the + and - buttons.</li>
<li>Control music and video playback &mdash; including play/pause and next/previous &mdash; by pressing the center button.</li>
<li>Record voice memos on supported devices.</li>
<li>Answer and end calls.</li>
</ul>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>Apple Earphones with Remote and Mic</li>
</ul>
<h6>Compatibility Information</h6>
<ul>
<li>The remote and mic are supported only by iPod nano (4th generation), iPod classic (120GB), iPod touch (2nd generation), and iPhone 3G S. The remote is supported by iPod shuffle (3rd generation). Audio is supported by all iPod models. </li>
<li>Requires software version 1.0.3 for iPod nano (4th generation), 2.0.1 for iPod classic (120GB), and 2.2 or later for iPod touch (2nd generation). </li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001NABJ56?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001NABJ56\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001NABJ56\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MB770G/A?fnode=MTY1NDA3NA&amp;mco=MTgwNDU3Mw\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5003,'en',5003,'Apple In-Ear Headphones with Remote and Mic','<p>Hear every detail of your music every time you tune in with the Apple In-Ear Headphones with Remote and Mic. They offer pro audio performance and impressive sound isolation, and convenient buttons let you adjust the volume, control music and video playback and even answer or end calls on your iPhone.</p>
<h5>Overview</h5>
<h6>Rediscover your music.</h6>
<p>Put on the Apple In-Ear Headphones, select your favorite track, and hear musical details you never knew existed. It\'s almost like you\'re experiencing your music for the first time.</p>
<h6>Two drivers in each earpiece.</h6>
<p>Each earpiece contains two separate high-performance drivers &mdash; a woofer and a tweeter &mdash; resulting in rich, detailed, and accurate sound reproduction and amazing bass response for all types of music.</p>
<h6>Impressive sound isolation.</h6>
<p>Choose from three sizes of soft, silicone ear tips for a comfortable, stable fit. When inserted in your ear, they create a seal that blocks outside noise so you can get the most from your music.</p>
<h6>Take control.</h6>
<p>The control capsule located on the cable of the right earpiece includes a microphone and three buttons. Here\'s what you can do with this convenient remote:*</p>
<ul>
<li>Adjust volume by pressing the + and - buttons.</li>
<li>Control music and video playback &mdash; including play/pause and next/previous &mdash; by pressing the center button.</li>
<li>Record voice memos on supported devices.</li>
<li>Answer and end calls.</li>
</ul>
<h6>Steel mesh caps for protection.</h6>
<p>Stainless steel mesh caps protect the precision acoustic components from dust and debris. You can remove the caps for cleaning or replace them with an extra set that\'s included in the box.</p>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>Apple In-Ear Headphones with Remote and Mic</li>
<li>Carrying case</li>
<li>Three sets of silicone ear tips (small, medium, large)</li>
<li>Two replacement mesh caps</li>
</ul>
<h6>Technical Specifications</h6>
<ul>
<li>Frequency response: 5Hz to 21kHz</li>
<li>Impedance (at 100Hz): 23 ohms</li>
<li>Sensitivity (at 100Hz): 109 dB SPL/mW</li>
<li>Drivers: Custom two-way balanced armature (woofer and tweeter in each earpiece)</li>
</ul>
<h6>Length and Weight</h6>
<ul>
<li>Cable length: 1065 mm from audio jack to splitter; 330 mm to earpiece</li>
<li>Weight: 0.4 ounce (10.2 grams)</li>
</ul>
<h6>Connector</h6>
<ul>
<li>Four-conductor 3.5 mm audio jack</li>
</ul>
<h6>Compatibility Information</h6>
<p>The remote and mic are supported only by iPod nano (4th generation), iPod classic (120GB), iPod touch (2nd generation), and iPhone 3G S. The remote is supported by iPod shuffle (3rd generation). Audio is supported by all iPod models.</p>
<p>Requires software version 1.0.3 for iPod nano (4th generation), 2.0.1 for iPod classic (120GB), and 2.2 or later for iPod touch (2nd generation).</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001NABJ56?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001NABJ56\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001NABJ56\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MA850G/A?fnode=MTY1NDA3NA&amp;mco=MTczMjUyMw\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5004,'en',5004,'Apple iPod touch 32 GB','<h5>Technical Details</h5>
<ul>
<li>This player is the iPod touch, not the Apple iPhone</li>
<li>32 GB capacity for 7,000 songs, 10,000 photos, or 40 hours of video</li>
<li>Up to 36 hours of music playback or 6 hours of video playback when fully charged</li>
<li>3.5-inch widescreen multi-touch display with 480-by-320-pixel resolution</li>
<li>Supported audio formats: AAC, Protected AAC, MP3, MP3 VBR, Audible, Apple Lossless, AIFF, and WAV; supported video formats: H.264, MPEG-4; supported image file types: JPEG, BMP, GIF, TIFF, PSD (Mac only), and PNG</li>
</ul>
<h5>Product Details</h5>
<ul>
<li><strong>Product Dimensions: </strong>3 x 1.2 x 5 inches ; 8 ounces </li>
<li><strong>Shipping Weight:</strong> 1 pounds</li>
<li><strong>Shipping: </strong>Currently, item can be shipped only within the U.S.</li>
<li><strong>ASIN:</strong> B001FA1O0E</li>
<li><strong>Item model number:</strong> MB533LL/A</li>
</ul>
<h5>Product Description</h5>
<p>The iPod touch has always been an amazing iPod. And with its groundbreaking technologies--including a Multi-Touch screen, the accelerometer, and 3D graphics--and access to hundreds of games, iPod touch puts an amazing gaming experience in the palm of your hand. It comes in 8 GB, 16 GB, and 32 GB models with new volume controls and a built-in speaker. Play hours of music. Create a Genius Playlist of songs that go great together. Watch a movie. Surf the web. View rich HTML email. Find your location and get directions with Google Maps. Browse YouTube videos. And shop the App Store for games and applications.</p>
<p><img src=\"http://app.ecwid.com/image/5641?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Music</h6>
<p>Music on iPod touch not only sounds amazing, it looks amazing, too.</p>
<p><strong>Touch Your Music</strong><br />Remember what it felt like to flip through your CD or record collection? Cover Flow brings that feeling back. Just turn iPod touch on its side and flick through your music to find the album you want to hear. Tap the cover to flip it over and display a track list. Tap again to start the music. Even view the lyrics while you\'re listening.</p>
<p><strong>A Musical Genius</strong><br />Say you\'re listening to a song you really like and want to hear other tracks that go great with it. The new Genius feature finds the songs in your library that go great together and makes a Genius Playlist for you. You can listen to the playlist right away, save it for later, or even refresh it and give it another go. Count on Genius to create a mix you wouldn\'t have thought of yourself.</p>
<p><strong>Fill It Up</strong><br />Fill up your iPod touch with audio and video from your iTunes library. All you have to do is choose the playlists, videos, and other content you want to sync, and iTunes does the rest.</p>
<h6>Movies and TV Shows</h6>
<p>Movies and TV shows have never looked this good on a portable device.</p>
<p><strong>Everything\'s a Must-see</strong><br />Carry hours of video with you, and watch it on a crisp, clear 3.5-inch widescreen color display. Need ideas? From Hollywood blockbusters to independent favorites, there\'s something for everyone at the iTunes Store. Download and watch movies with a few clicks. Prefer TV shows? Buy a single episode or an entire season\'s worth all at once.</p>
<p><strong>In Control</strong><br />While watching your video, tap the display to bring up the onscreen controls. You can play/pause, view by chapter, and adjust the volume. You also can use the new volume controls on the left side of iPod touch. Want to switch between widescreen and full screen? Simply tap the display twice.</p>
<p><strong>Sync and Go</strong><br />Need some entertainment for your next flight or road trip? With iTunes on your Mac or PC, you can sit at your computer and choose the movies and TV shows you want to sync to your iPod touch.</p>
<h6>Games</h6>
<p>With its groundbreaking technologies, iPod touch puts an amazing gaming experience in the palm of your hand.</p>
<p><strong>Get in the Game</strong><br />Developers all over the world are creating exciting games unlike anything you\'ve ever seen on an iPod or mobile device. Many games come alive with stunning 3D graphics and immerse you in the action with the advanced technologies in iPod touch. There\'s even a built-in speaker, so you can hear all the action.</p>
<p><strong>Fingertip Control</strong><br />Many games for iPod touch use Multi-Touch to give you precise, fingertip control over game elements. Use your finger to drag your pieces around the board in chess or dice games. Or pinch to enlarge or shrink your view, rotate your character left or right, or just tap to make a selection.</p>
<p><strong>Tilt, Turn, and Go</strong><br />The built-in accelerometer actually responds to your movements, so you can tilt and turn your iPod touch to control the action. It\'s perfect for racing games--where your entire iPod touch acts as a steering wheel--and for tap-and-tilt games like Super Monkey Ball, in which your character rolls to your movements.</p>
<p><strong>The App Store</strong><br />Even if games aren\'t your thing, there\'s an iPod touch application for you. Thousands of applications in almost every category--entertainment, social networking, sports, photography, reference, and travel--are a tap away at the App Store.</p>
<h6>iTunes Wi-Fi Music Store</h6>
<p>Discover new music anywhere.</p>
<p><strong>Buy on the Fly</strong><br />The built-in wireless capability in iPod touch gives you access to the iTunes Wi-Fi Music Store, where you can choose from millions of songs with a tap. Browse New Releases, What\'s Hot, and Genres. Take a look at Top Songs and Top Albums. Or find exactly what you\'re looking for with a quick search. Play a 30-second preview of any song, then tap once to buy it. Your music starts downloading instantly, and you can keep tabs on its progress by tapping the Downloads button.</p>
<p><strong>Sync it Back</strong><br />When you connect iPod touch to your computer, the music you bought on-the-go syncs to your iTunes library. If you\'ve partially downloaded a song to iPod touch, your computer completes the download automatically.</p>
<p><strong>iPod touch at Starbucks</strong><br />If you have an iPod touch, an iPhone, or a computer with the latest version of iTunes, you get free Wi-Fi access to the iTunes Store and to Starbucks\' Now Playing content. Stroll into a participating Starbucks, and you\'re connected automatically.</p>
<h6>Home Screen</h6>
<p>Get instant access to whatever you need on your iPod touch.</p>
<p><strong>Customize Your Home Screen</strong><br />Arrange the icons on your Home screen any way you want. Even move them to another Home screen. Create up to nine Home screens for quick access to the games and applications you download from the App Store and to your Safari Web Clips.</p>
<p><strong>Go Home</strong><br />No matter where you are on iPod touch, you can press the Home button to return to the Home screen. You can go back to what you were doing at any time.</p>
<p><strong>Add Apps, Web Clips, and More</strong><br />Whenever you download an application from the App Store, a new icon appears on your Home screen. And if you check the same websites every day, just create Web Clips and you can access the sites directly from your Home screen with a single tap. Not happy with how they\'re organized? Reorder them any way you want by dragging them around the screen.</p>
<p><img src=\"http://app.ecwid.com/image/5647?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Safari</h6>
<p>iPod touch features Safari, the most advanced web browser ever on a portable device.</p>
<p><strong>Browse Anywhere</strong><br />The iPod touch is the only iPod with 802.11b/g wireless access to the web. Whenever you\'re connected via Wi-Fi, you can access your favorite websites to read news, check scores, pay bills, and go shopping.</p>
<p><strong>Search and Find</strong><br />iPod touch syncs your bookmarks from your PC or Mac, so you can access favorite sites quickly. It has Google and Yahoo! search built in, so it\'s easy to find what you\'re looking for on the web.</p>
<p><strong>Zoom with a View</strong><br />Get a closer look at any web page by zooming in and out with a tap or a pinch of the Multi-Touch display. View websites in portrait or landscape. Rotate iPod touch 90 degrees and the website rotates, too.</p>
<p><strong>Clip it.</strong><br />If you check a website frequently--a favorite newspaper, blog, or sports site--why not create a Home screen icon for it? Make Web Clips with Safari, and your favorite sites are always just a tap away.</p>
<p><img src=\"http://app.ecwid.com/image/5648?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Mail</h6>
<p>Email on iPod touch looks and works just like email on your computer.</p>
<p><strong>See it All</strong><br />iPod touch supports rich HTML email, so images and photos appear alongside text. And you see email attachments in their original formats, not stripped-down versions. Rotate, zoom, and pan in more than a dozen standard file and image formats, including PDF; Microsoft Word, Excel, and PowerPoint; and iWork.</p>
<p><strong>Access it All</strong><br />Access your email from popular providers--including MobileMe, Microsoft Exchange, Yahoo! Mail, Google Gmail, and AOL--and most industry-standard IMAP and POP mail systems.</p>
<p><strong>Send Fast</strong><br />iPod touch recognizes email addresses in different applications. If you run across an email address on a web page or a map listing, for example, just tap it; iPod touch opens a new message and addresses it for you.</p>
<p><strong>Type Smart</strong><br />With its built-in dictionary, the intelligent iPod touch keyboard predicts and suggests words as you type, making it fast and easy to write email.</p>
<p><img src=\"http://app.ecwid.com/image/5649?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Maps</h6>
<p>When you\'re connected via Wi-Fi, you can get directions, find local businesses, and check traffic.</p>
<p><strong>Find Yourself</strong><br />iPod touch finds your location using known Wi-Fi hotspots. It also finds points of interest by keyword: Search for \"coffee\" and iPod touch shows you every cafe nearby.</p>
<p><strong>Get Directions</strong><br />Just type in an address and get directions from wherever you are. View a list of turn-by-turn directions, or see a highlighted map route. You also can mark specific locations and find the best route between them.</p>
<p><strong>Enjoy the View</strong><br />Just like Google Maps on your computer, Maps on iPod touch lets you switch between views of Google map data, satellite images, and a hybrid of both. Multi-Touch makes the difference. Tap to zoom, pan, and change your view on the move.</p>
<p><strong>See Traffic</strong><br />Maps on iPod touch shows you live traffic information, indicating traffic speed along your route in easy-to-read green, red, and yellow highlights.</p>
<p><img src=\"http://app.ecwid.com/image/5650?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>YouTube</h6>
<p>Love to watch the latest YouTube videos? iPod touch gives you all the fun of the web\'s best videos--pocket-size.</p>
<p><strong>Share from Anywhere</strong><br />Email your favorite videos to your favorite people. Tap \"Share\" on any YouTube video detail page, and iPod touch creates an email with the video link already in it.</p>
<p><strong>Watch What You Want</strong><br />Explore Featured, Most Viewed, Most Recent, and Top Rated videos. Or search for the video you want with a keyword search. Once you find what you\'re looking for, bookmark it to watch later.</p>
<h6>Photos</h6>
<p>Carry up to 25,000 of your favorite photos everywhere.</p>
<p><img src=\"http://app.ecwid.com/image/5651?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<p><strong>Share Photos</strong><br />Show thousands of photos from the palm of your hand. Flick to scroll through thumbnails. Tap to view full screen. Rotate to see a photo in landscape. Pinch to zoom in or out. Play slideshows, complete with music and transitions. Email a photo to a friend, set it as your wallpaper, or share it in a MobileMe Gallery.</p>
<p><strong>Save Photos</strong><br />If you receive a great image in an email, save it to your photo library on iPod touch. Once there, it acts just like any other photo. You can set it as your wallpaper, share it on the web, or pass it on.</p>
<p><strong>Sync Photos</strong><br />iPod touch uses iTunes to sync photos you have in iPhoto on a Mac or Adobe Photoshop Elements and Adobe Photoshop Album on a PC. Just choose which photos or albums to sync to your iPod touch, then you can look at them--and share them--anywhere you go.</p>
<p><img src=\"http://app.ecwid.com/image/5652?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Calendar</h6>
<p>With iPod touch, it\'s easy to make plans and stay on schedule.</p>
<p><strong>Add Events</strong><br />Keep your schedule at your fingertips with iPod touch. Add events to your calendar. Set a custom alert. Write a note or two. Manage multiple color-coded calendars. And do it all with just a few taps.</p>
<p><strong>Stay in Sync</strong><br />Connect iPod touch to your computer, and the events that you\'ve created on-the-go automatically sync to Microsoft Outlook on a PC or iCal on a Mac. And all the events you\'ve added on your computer sync to iPod touch.</p>
<p><strong>Three Ways to View</strong><br />iPod touch gives you three ways to view your calendars. List view shows you all your appointments in the coming days as a comprehensive list, which you can scroll up and down. Day view displays one day\'s worth of appointments visually. And Month view offers an at-a-glance look at an entire month.</p>
<h6>Contacts</h6>
<p>Put names, email addresses, phone numbers, and more at your fingertips.</p>
<p><img src=\"http://app.ecwid.com/image/5653?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<p><strong>Make Contact</strong><br />Build your contacts list on your Mac with Address Book or on your PC with Microsoft Outlook, then sync everything to your iPod touch using iTunes. You also can add contact information directly to your iPod touch from maps, web pages, and email. Next time you sync, your computer is updated, too.</p>
<p><strong>Search Contacts</strong><br />If you have a lot of contacts, a quick search shows you a list of matching names. Or you can scroll up and down your entire list to find the right contact. Want to send them an email? Just tap an email address and the Mail application opens automatically.</p>
<p><strong>Organized by Groups</strong><br />If you keep your contacts organized into groups--such as co-workers, friends, family, and so on--iPod touch will, too. And iPod touch can hold more than just names, email addresses, and phone numbers. You also can track birthdays, websites, nicknames, and notes.</p>
<h6>Stocks, Weather, and Notes</h6>
<p>Stay on top of it all.</p>
<p><strong>Check Stocks</strong><br />Stocks on iPod touch shows you performance information for any stock you choose. When you want more details about a stock\'s performance, tap the Y! for instant access to Yahoo! Finance.</p>
<p><strong>Get Weather</strong><br />Check worldwide weather at home or away. Add the cities you want, then flick back and forth to get six-day forecasts for each. Tap the Y! to open a Yahoo! city guide that shows you what\'s happening, rain or shine.</p>
<p><strong>Take Notes</strong><br />Forget the pen and paper. Use Notes on iPod touch to write yourself a quick note and keep important information on hand. There\'s even a built-in email function that lets you send notes to yourself or others.</p>
<h6>Calculator</h6>
<p>iPod touch\'s calculator helps you settle the restaurant bill or keep track of your budget.</p>
<p><strong>Calculate Simply</strong><br />When you tap the Calculator icon, iPod touch shows you a simple application with addition, subtraction, multiplication, division, and memory functions. Use it just as you would a pocket calculator.</p>
<p><strong>Calculate Scientifically</strong><br />Your simple calculator doubles as a sophisticated scientific calculator. Just rotate it to landscape to access dozens of functions for solving complex science and math problems.</p>
<p><img src=\"http://app.ecwid.com/image/5654?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Nike + iPod</h6>
<p>Get the most out of your workout.</p>
<p><strong>Tune Your Run</strong><br />iPod touch now includes built-in Nike + iPod support. Just slip the Nike + iPod Sensor (available separately) into your Nike+ shoe and start your run. The sensor communicates wirelessly with your iPod touch, tracking your time, distance, and calories burned. It even gives you voice feedback on your progress.</p>
<p><strong>Tune Your Cardio Workout</strong><br />This feature also works with new cardio equipment available in many fitness centers. Just look for treadmills, ellipticals, stair steppers, and stationary bikes that are Nike + iPod compatible.</p>
<p><strong>Sync with Nikeplus.com</strong><br />When you get back to your computer, sync your iPod touch via iTunes and transfer your exercise data to nikeplus.com, where you can track your workouts, set goals, and challenge friends.</p>
<h6>Multi-Touch</h6>
<p>iPod touch features the same revolutionary interface as iPhone.</p>
<p><strong>Glide, Flick, Pinch</strong><br />Built to take full advantage of the large 3.5-inch display, the Multi-Touch touchscreen interface lets you control everything using only your fingers. So you can glide through albums with Cover Flow, flick through photos and enlarge them with a pinch, zoom in and out on a section of a web page, and control game elements precisely.</p>
<p><strong>How it Works</strong><br />The Multi-Touch display layers a protective shield over a capacitive panel that senses your touch using electrical fields. It then transmits that information to the LCD screen below it. iPod touch software enables the flick, tap, and pinch.</p>
<p><strong>Type with the Touchscreen Keyboard</strong><br />iPod touch features an intelligent touchscreen keyboard perfect for browsing the web in Safari, getting directions on a map, searching for videos on YouTube, finding music on the iTunes Wi-Fi Music Store, or adding new contacts. It analyzes keystrokes to suggest words as you type and correct spelling errors automatically. And because it\'s software based, it changes its keys to support typing in multiple languages.</p>
<h6>Accelerometer</h6>
<p>iPod touch responds to motion using a built-in accelerometer.</p>
<p><strong>Responds to Movement</strong><br />iPod touch detects when you rotate it from portrait to landscape, then automatically changes the contents of the display. So you immediately see the entire width of a web page, view a photo in its proper aspect ratio, or control a game using only your movements.</p>
<p><strong>How it Works</strong><br />The accelerometer inside iPod touch uses three elements: a silicon mass, a set of silicon springs, and an electrical current. The silicon springs measure the position of the silicon mass using the electrical current. Rotating iPod touch causes a fluctuation in the electrical current passing through the silicon springs. The accelerometer registers these fluctuations and tells iPod touch to adjust the display accordingly.</p>
<p><strong>Perfect for Gaming</strong><br />Accelerometer technology really shines when you play games because it immerses you in the action. It\'s perfect for racing games--where your entire iPod touch acts as a steering wheel--and for tap-and-tilt games like Super Monkey Ball, in which your character responds to your every movement.</p>
<h6>Wireless</h6>
<p>Connect iPod touch to the Internet anywhere there\'s a wireless network.</p>
<p><strong>Connect Automatically</strong><br />iPod touch locates nearby wireless hotspots, including protected networks. If you\'ve never used a particular network, it asks you to enter a password the first time, and it remembers the password from then on. So the next time you\'re within range, it connects automatically.</p>
<p><strong>Surf\'s Up</strong><br />Now you can send email from a coffee shop. Surf the web at the airport. Shop for games from your couch. Browse, buy, and download music from the iTunes Wi-Fi Music Store at select Starbucks locations or other wireless hotspots in your area.</p>
<h6>Read Kindle Books on the iPod touch</h6>
<ul>
<li>No Kindle required. </li>
<li>Get the best reading experience available on your iPhone or iPod touch. </li>
<li>No Kindle required. </li>
<li>Access your Kindle books even if you don\'t have your Kindle with you. </li>
<li>Automatically synchronizes your last page read between devices with Amazon Whispersync. </li>
<li>Adjust the text size, add bookmarks, and view the annotations you created on your Kindle. </li>
</ul>
<h6>Shop for Books on the Kindle Store on Your iPod touch</h6>
<ul>
<li>Buy a book from the Kindle Store, optimized for Safari, on your iPod touch or iPhone and get it auto-delivered wirelessly. </li>
<li>Search and browse more than 275,000 books, including more than 107 of 112 <em>New York Times</em> bestsellers. </li>
<li>Find <em>New York Times</em> bestsellers and new releases for $9.99, unless marked otherwise. </li>
<li>Get free book samples; read the first chapter for free before you decide to buy. </li>
<li>Books you purchase also can be read on a Kindle. </li>
<li>Kindle newspapers, magazines, and blogs are not currently available on the iPod touch or iPhone. </li>
</ul>
<h6>What\'s in the Box</h6>
<p>iPod touch 32 GB, earphones, USB 2.0 cable, dock adapter, polishing cloth, quick start guide</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002M3SOC4?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002M3SOC4\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002M3SOC4\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5005,'en',5005,'Apple iPod touch 8 GB','<h5>Technical Details</h5>
<ul>
<li>This player is the iPod touch, not the Apple iPhone</li>
<li>Upgrade your player with the iPhone 2.0 Software Update for iPod touch via iTunes for an additional fee</li>
<li>8 GB of storage provides approximately 1,750 songs; includes earphones, USB cable, dock adapter/connector, polishing cloth, and stand</li>
<li>3.5-inch widescreen multi-touch display; battery life provides up to 22 hours of music and up to 5 hours of video</li>
<li>New applications include email; maps; and widgets for weather, notes, and stocks</li>
</ul>
<h5>Product Details</h5>
<ul>
<li><strong>Product Dimensions: </strong>3.5 x 1.5 x 5.8 inches ; 2 pounds </li>
<li><strong>Shipping Weight:</strong> 1 pounds </li>
<li><strong>Shipping: </strong>Currently, item can be shipped only within the U.S.</li>
<li><strong>ASIN:</strong> B0012JCYPC</li>
<li><strong>Item model number:</strong> MA623LL/B</li>
</ul>
<h5>Product Description</h5>
<p>iPod touch has always been an amazing iPod. With great new applications, now iPod touch is even better. Watch a movie you rented from iTunes. View rich HTML email with graphics and photos displayed inline. Open PDF, Microsoft Word, and Microsoft Excel attachments. With Maps, find your location and get directions from there. See where you are on a map, a satellite image, or a combination of both. Make Web Clips for your Home screen so you can visit your favorite websites in just one tap. Fill up to nine Home screen pages with Web Clips and arrange them however you like. Browse YouTube videos, follow your stocks, check the weather, and take notes. With the new iPod touch, tap into even more.</p>
<p><img src=\"http://app.ecwid.com/image/5619?ownerid=1003\" border=\"0\" alt=\"\" align=\"top\" /></p>
<h6>Music, Movies, and More</h6>
<p><br />Flick through album covers and find your music. Download and watch your favorite movies, rentals, TV shows, and more from the iTunes Store. Tap into thousands of photos. All using incredible multi-touch technology on a beautiful 3.5-inch display.</p>
<p><strong>Music</strong><br />If a picture says a thousand words, think of what all the album art in your collection might say. With Cover Flow on iPod touch, flick through your music to find the album you want to hear. When you do, just tap the cover to flip it over and display a track list. Another tap starts the music. Even view the lyrics while you\'re listening to the track.</p>
<p><strong>Video</strong><br />The 3.5-inch display gives you video like you\'ve never seen on a portable device. Watch your favorite movie or rental from the iTunes Store. Catch up on TV shows anywhere. Enjoy video podcasts. Play music videos. All using multi-touch technology. With a tap, bring up onscreen controls to play/pause and view by chapter. Turn your iPod touch to switch between widescreen or full screen.</p>
<p><strong>Photos</strong><br />iPod touch holds up to 20,000 photos you sync via iTunes. Flick to scroll through thumbnails. Tap to view full screen. Rotate for landscape format. Or perform some sleight of hand by opening two fingers to zoom in. You can even play slideshows, complete with music and transitions. Set any photo as your wallpaper to personalize your iPod touch . . . with a touch.</p>
<p><img src=\"http://app.ecwid.com/image/5620?ownerid=1003\" border=\"0\" alt=\"\" align=\"top\" /></p>
<p><strong>iTunes Wi-Fi Music Store</strong><br />With iPod touch, discover new music anywhere. Its built-in wireless capability gives you access to the iTunes Wi-Fi Music Store, where you can buy songs with a tap. Browse New Releases, What\'s Hot, Genres, and Top 10 songs. Or find exactly what you\'re looking for with a quick search. Tap a song to preview it, tap Buy to purchase it. Even redeem your iTunes gift cards and gift certificates. All from anywhere you happen to be.</p>
<p><strong>Starbucks Music</strong><br />You walk into a Starbucks. Order your latte. While you wait, you hear a song wafting from the loudspeakers. You love it. So you get out your iPod touch and buy it over Wi-Fi. Just like that. The iTunes Wi-Fi Music Store on iPod touch tells you what\'s playing in select Starbucks and lets you buy it along with other featured Starbucks content. So you can sip, shop, and listen.</p>
<p><strong>Home Screen</strong><br />Make the iPod touch Home screen your own. Change what\'s in the Dock. Rearrange the icons. And make room for even more. You can add up to eight Home screen pages and fill them with Web Clips.</p>
<h6>Internet</h6>
<p><br />Surf the web. Send email. Get directions and find your location with Maps. Check stocks, weather, and more. iPod touch is not just an amazing iPod. It\'s the Internet in your pocket.</p>
<p><strong>Safari</strong><br />iPod touch is the only iPod with wireless access to the web. Safari is built in, so you see websites the way they were designed to be seen. Search the web using the touchscreen keyboard. Zoom in and out by tapping the multi-touch display. Switch between portrait or landscape view, depending on how you hold your iPod touch. Sync your bookmarks. Better yet, add them to your Home screen. iPod touch can automatically create a Web Clip on your Home screen from any of your favorite websites. So just one tap takes you directly there.</p>
<p><strong>YouTube</strong><br />Got a bit of a YouTube addiction? iPod touch feeds it from anywhere with a special YouTube player built right in. Watch featured videos, check out the most viewed, search for something specific, then bookmark your favorites for future reference. It\'s all the fun of YouTube--pocket-size.</p>
<p><strong>Mail</strong><br />iPod touch is the first iPod with Mail. And it\'s the best email you\'ve ever seen on a handheld device. This mail application lets you view rich HTML email with graphics and photos displayed inline, as well as PDF, Microsoft Word, and Microsoft Excel attachments.</p>
<p><strong>Maps</strong><br />Get directions and check traffic with Google Maps. Even find yourself, wherever you are. Using some local Wi-Fi networks (if Wi-Fi is turned on), iPod touch finds your approximate location and gives directions from there. Mark specific locations, find the best route between them, and search for points of interest along the way. With a hybrid map and satellite view, you can see major street names on top of satellite images.</p>
<p><strong>Widgets</strong><br />Widgets are small, incredibly handy applications you can use every day. Three of the most useful widgets now live front and center on your Home screen:</p>
<table border=\"0\">
<tbody>
<tr>
<td><img src=\"http://app.ecwid.com/image/5623?ownerid=1003\" alt=\"\" /><br /><strong>Weather</strong><br />Get a five-day weather forecast, including highs and lows, for cities around the world. Save your favorite locations so you can check the weather anytime.</td>
<td><img src=\"http://app.ecwid.com/image/5624?ownerid=1003\" alt=\"\" /><br /><strong>Notes</strong><br />Take notes, make a to-do list, or jot down a reminder using the easy-to-use touch keypad. Then save or email them.</td>
<td><img src=\"http://app.ecwid.com/image/5625?ownerid=1003\" alt=\"\" /><br /><strong>Stocks</strong><br />Check your stocks and track the market over one day, one week, one month, three months, six months, one year, or two years.</td>
</tr>
</tbody>
</table>
<h6>High Technology</h6>
<p><br />iPod touch features the same revolutionary interface as iPhone, the most advanced software ever engineered, and state-of-the-art technology. With the multi-touch display, you can control everything using only your fingers. If you rotate your iPod touch from portrait to landscape, the accelerometer automatically changes the way the content is displayed. And with wireless technology, you can connect to the Internet from any Wi-Fi network, anywhere you are.</p>
<p><strong>Multi-touch</strong><br />iPod touch features the same revolutionary interface as iPhone. Built to take full advantage of the large 3.5-inch display, the multi-touch interface lets you control everything using only your fingers. So you can glide through albums with Cover Flow, flick through photos and enlarge them with a pinch, or zoom in and out on a section of a web page. And iPod touch features a touchscreen QWERTY keyboard perfect for browsing the web in Safari, getting directions on a map, searching for videos on YouTube, finding music on the iTunes Wi-Fi Music Store, or adding new contacts.</p>
<p><strong>Ambient Light Sensor</strong><br />The iPod touch display has an ambient light sensor that automatically adjusts brightness to suit the ambient light in your surroundings. The result? A better experience for you and battery-saving efficiency for iPod touch.</p>
<p><strong>Wireless</strong><br />Connect to the Internet anywhere there\'s a Wi-Fi network. Send email from a coffee shop. Surf the web at the airport. Browse, buy, and download music from the iTunes Wi-Fi Music Store at select Starbucks locations or other wireless hot spots in your area. iPod touch finds wireless networks and connects you to the Internet.</p>
<p>&nbsp;</p>
<p><strong>Accelerometer</strong><br />An accelerometer detects when you rotate iPod touch from portrait to landscape, then automatically changes the contents of the display, so you immediately see the entire width of a web page, your music in Cover Flow, or a photo in its proper aspect ratio.</p>
<p><img src=\"http://app.ecwid.com/image/5629?ownerid=1003\" border=\"0\" alt=\"\" /></p>
<h6>Read Kindle Books on the iPod touch</h6>
<ul>
<li>No Kindle required. </li>
<li>Get the best reading experience available on your iPhone or iPod touch. </li>
<li>No Kindle required. </li>
<li>Access your Kindle books even if you don\'t have your Kindle with you. </li>
<li>Automatically synchronizes your last page read between devices with Amazon Whispersync. </li>
<li>Adjust the text size, add bookmarks, and view the annotations you created on your Kindle. </li>
</ul>
<p><strong>Shop for Books on the Kindle Store on Your iPod touch</strong></p>
<ul>
<li>Buy a book from the Kindle Store, optimized for Safari, on your iPod touch or iPhone and get it auto-delivered wirelessly. </li>
<li>Search and browse more than 275,000 books, including more than 107 of 112 <em>New York Times</em> bestsellers. </li>
<li>Find <em>New York Times</em> bestsellers and new releases for $9.99, unless marked otherwise. </li>
<li>Get free book samples; read the first chapter for free before you decide to buy. </li>
<li>Books you purchase also can be read on a Kindle. </li>
<li>Kindle newspapers, magazines, and blogs are not currently available on the iPod touch or iPhone.</li>
</ul>
<h6>What\'s in the Box</h6>
<p><br />8 GB iPod touch, earphones, USB 2.0 cable, dock adapter, polishing cloth, stand, quick start guide.</p>
<div style=\"display: block; padding: 24px 24px 24px 21px; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002M3SOBU?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002M3SOBU\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002M3SOBU\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" />, this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5006,'en',5006,'Apple TV','<h5>HD movie rentals, HD TV shows, and more. Coming to a widescreen TV near you.</h5>
<p>Mac + PC 802.11n; <br />Wi-Fi wireless; <br /><br /><strong>40GB</strong> hard drive for up to 50 hours of video; <br />OR <br /><strong>160GB</strong> hard drive for up to 200 hours of video Apple Remote</p>
<h6>HD movie rentals from your living room.</h6>
<p>With a few clicks of your remote, you can rent high-definition movies from the same place you watch them: your widescreen TV.</p>
<h6>The best TV shows in HD.</h6>
<p>Buy your favorite TV shows from leading networks &mdash; commercial free &mdash; and watch them in stunning HD anytime you want. It&rsquo;s &agrave; la carte high definition TV.</p>
<h6>All your music and photos, all in one place.</h6>
<p>With Apple TV, your home entertainment system becomes the best place to shop the iTunes Store, play your music, and show off digital photos in gorgeous slideshows set to your favorite songs.</p>
<h6>What&rsquo;s in the box</h6>
<ul>
<li>Apple TV</li>
<li>Apple Remote</li>
<li>Power cord</li>
<li>Printed documentation</li>
</ul>
<h6>Requirements</h6>
<ul>
<li>Widescreen (16 by 9) enhanced-definition or high-definition television with an HDMI, DVI, or component video input port</li>
<li>Wired or wireless network</li>
<li>iTunes Store account</li>
<li>Broadband Internet connection (fees may apply)</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000RQHAUA?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000RQHAUA\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000RQHAUA\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_ipod/family/apple_tv?mco=MTI3Njk\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5007,'en',5007,'Apple USB Power Adapter','<p>Use this ultracompact and convenient USB-based adapter to charge your iPhone or iPod at home, on the road, or whenever it\'s not connected to a computer.</p>
<h5>Overview</h5>
<p>Featuring a new, ultracompact design, this power adapter offers fast, efficient charging at home, in the office, or on the go. It works with any iPhone and all iPod models with a dock connector.</p>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>Apple USB Power Adapter</li>
<li>Apple Dock Connector to USB Cable</li>
</ul>
<p>Important note: This USB Power Adapter has fixed prongs for use in the U.S., Canada, Japan, Taiwan, and parts of Latin Americ</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001GQ3DP6?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001GQ3DP6\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001GQ3DP6\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MB352LL/B?fnode=MTY1NDA4NA&amp;mco=MTY1OTg1Nw\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5008,'en',5008,'Apple Wireless Mighty Mouse','<p>Now you can get the world-famous Mighty Mouse without the tail. With its Bluetooth technology, the wireless Mighty Mouse gives you complete freedom of movement with no cable clutter. It features the popular Scroll Ball that lets you move anywhere inside a document, without lifting a finger. And its laser tracking technology allows it to work on more surfaces with greater precision.</p>
<h6>Bluetooth technology, ultimate portability</h6>
<p>With its secure, reliable Bluetooth technology, wireless Mighty Mouse goes wherever you do. Pair it with any Bluetooth-enabled Mac to work untethered and uncluttered at your desk or on the go, and it operates with either one or two AA batteries &mdash; no bulky dock required.</p>
<h6>Laser tracking engine</h6>
<p>The wireless Mighty Mouse features a laser tracking engine that\'s up to 20 times more sensitive to surface details than traditional optical technology. That means it can track with precision on more surfaces than ever &mdash; even smooth or polished surfaces &mdash; with no mouse pad required.</p>
<h6>Innovative Scroll Ball and button</h6>
<p>Perfectly positioned to roll smoothly under just one finger, Mighty Mouse\'s Scroll Ball offers full 360-degree scrolling capability &mdash; up/down, left/right and diagonally. You can scroll long web pages, pan full-size images, maneuver around large spreadsheets, control a video timeline and more. And you can even click the Scroll Ball to access your favorite Mac OS X features such as Dashboard, Spotlight or Expos&eacute;.</p>
<h6>Touch-sensitive top shell</h6>
<p>It looks and feels like a sleek one-button mouse, but Mighty Mouse\'s smooth top shell hides a powerful secret: touch-sensitive technology under the shell detects which part of the mouse you\'re clicking, so you can both left-click and right-click. And if you prefer the simplicity of a classic one-button mouse, Mighty Mouse is up to the task. Just use the Mac OS X system preference pane to configure it how you want.</p>
<h6>Force-sensing side buttons</h6>
<p>When you give Mighty Mouse a squeeze, force-sensing side buttons can be configured to activate Mac OS X features such as Dashboard, Expos&eacute; or a whole host of other customizable features.</p>
<h6>Customizable</h6>
<p>Through the power of Mac OS X Tiger, Mighty Mouse gives you fingertip access to the special features you love. Simply use the system preference panel to configure Mighty Mouse in the way that makes you most powerful. Control the Scroll Ball\'s tracking, zoom and click functions, as well as the right, left and side click functions. And you even can set up different mouse profiles for every user account on your Mac.</p>
<h6>What\'s in the Box</h6>
<ul>
<li>Wireless Mighty Mouse</li>
<li>Two AA batteries</li>
<li>Printed and electronic documentation</li>
</ul>
<h6>System Requirements</h6>
<ul>
<li>A Bluetooth-enabled Macintosh computer</li>
<li>Mac OS X v10.4.8 or later</li>
<li>Existing keyboard and mouse for setup</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000B6D39I?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000B6D39I\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000B6D39I\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MB111LL/A/Wireless-Mighty-Mouse?fnode=MTY1NDA1Mg&amp;mco=NzQxNTc\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5009,'en',5009,'Bose QuietComfort® 2 Acoustic Noise Cancelling Headphones','<p>Bose QuietComfort 2 Acoustic Noise Cancelling Headphones combine the exceptional noise reduction technology of the original QuietComfort headphones with dramatically improved audio performance and enhanced convenience. Supports all iPod models.</p>
<ul>
<li>World-class audio with acclaimed noise-reducing technology</li>
<li>Comfortable around-ear style</li>
<li>AAA battery included</li>
<li>Fold-flat design for easy storage in the slim carrying case</li>
</ul>
<h5>Overview</h5>
<p>Experience the innovative combination of Bose\'s best noise reduction technology and best headphone audio performance with the QuietComfort&reg; 2 Acoustic Noise Cancelling headphones.</p>
<p>These premium headphones dramatically reduce unwanted noise, and advances in Bose&reg; technology make what you want to hear sound even better. Their ergonomic design allows for a comfortable fit and the fold-flat feature makes storage of these lightweight headphones even easier. Use them at home, in the office, on airplane flights, trains and buses--anywhere you enjoy listening to music.</p>
<h4>Features</h4>
<ul>
<li>Acclaimed noise reduction technology. Patented Bose noise reduction technology helps fade background distractions so you can enjoy more of your music.</li>
<li>Improved audio performance. TriPort&reg; (acoustic) headphone structure allows deeper, richer lows from a small headphone design. Proprietary Bose&reg; technology produces a richness of audio performance remarkable for headphones this small.</li>
<li>Comfortable fit. Their lightweight ergonomic design offers a fit so comfortable you may forget you\'re wearing them.</li>
<li>Enhanced styling for added convenience. They fold flat for easy storage in the slim carrying case. A single detachable audio cable gives you easy access to portable MP3-CD-DVD players, home stereos, laptop computers and in-flight entertainment systems.</li>
<li>Low battery life indicator with flashing light to give ample notification of remaining battery power, letting you know when remaining battery life is approximately five hours. One AAA battery affords 40 hours of normal use.</li>
</ul>
<h4>Specifications</h4>
<ul>
<li>Dimensions (HxW): Overall: 7.75 x 6.5 in./19.7 x 16.5 cm; Ear cushion outside: 3.8 x 3.0 in./9.7 x 7.6 cm</li>
<li>Weight (with cables): 6.9 oz./195.6 g</li>
</ul>
<h4>In the box</h4>
<ul>
<li>Bose&reg; QuietComfort&reg; 2 headhpones</li>
<li>Dual plug adapter</li>
<li>1/4-inch stereo phone adapter</li>
<li>5-foot extension cord</li>
<li>Portable carry case</li>
<li>One AAA battery</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000AP05BO?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000AP05BO\">Amazon</a><img height=\"1\" width=\"1\" border=\"0\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000AP05BO\" style=\"border: none !important; margin: 0px !important;\" /> or <a href=\"http://store.apple.com/us/product/T6832?mco=MjM1NDg\" title=\"Apple Store\" style=\"color: #1e7ec8; text-decoration: underline;\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5010,'en',5010,'Griffin PowerJolt Car Charger for iPhone or iPod (Black)','<p>Keep your iPhone or iPod fully charged on the road with the PowerJolt charger.</p>
<h5>Overview</h5>
<p>With its new low-profile design, PowerJolt is the easiest and most versatile way to power and charge your iPhone or iPod. Just plug PowerJolt into your cigarette lighter or 12V accessory socket and charge your device\'s battery during use.</p>
<h6>Features</h6>
<ul>
<li>Enjoy full use of your iPhone or iPod during car trips, and arrive with your battery fully charged</li>
<li>Amber/green charging status light</li>
<li>Detachable dock connector to USB Type A cable that you can also use to connect your iPhone or iPod to your computer</li>
<li>Easily replaceable 2 Amp automotive blade-type fuse to protect against spikes and surges</li>
<li>Works with iPhone, iPod models that feature full-size dock connector, and iPod shuffle (1st generation)</li>
</ul>
<h6>Specifications</h6>
<ul>
<li>Dimensions (LxWxH): 1.5 x 0.8 x 3.5 in./3.8 x 2.0 x 8.9 cm</li>
<li>Weight: 2.3 oz./65.2 g</li>
</ul>
<h6>What\'s In the box</h6>
<ul>
<li>PowerJolt car charger</li>
<li>USB Type A to dock connector cable</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000Y3YUOA?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000Y3YUOA\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000Y3YUOA\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TS267LL/A?fnode=MTY1NDA2MQ&amp;mco=MTQyMjk5NQ\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5011,'en',5011,'iHome iHM77 Stereo Mini Speakers','<p>Delivering sound beyond its size, the iHM77 is the perfect stereo speaker system for your MacBook, iPod, iPhone or any other notebook.</p>
<ul>
<li>Perfect for enhancing the movie/music experience on notebooks</li>
<li>Plays all iPod/iPhone models and other audio devices with 3.5 mm audio jack</li>
<li>Custom-tuned vacuum bass design for sizeable sound</li>
<li>Each speaker has a built-in amplifier powered by rechargeable battery</li>
</ul>
<h5>Overview</h5>
<p>The iHM77 speakers attach to each other magnetically in a capsule shape for the ultimate in portability. They separate and extend for surprisingly big stereo sound that must be heard to be believed. The iHM77 includes two amplified speakers with built-in rechargeable batteries, vacuum bass expansion, and USB charging, along with a protective carrying case.</p>
<h6>Features</h6>
<ul>
<li>Plays all iPod/iPhone models and other audio devices with 3.5 mm audio jack</li>
<li>Perfect for enhancing the movie/music experience on notebooks</li>
<li>Custom-tuned vacuum bass design for low frequency response and sound beyond size</li>
<li>Each speaker contains built-in amplifier powered by rechargeable battery</li>
<li>Two collapsible speakers attach magnetically for ultimate portability</li>
<li>Long-lasting built-in li-ion rechargeable batteries</li>
<li>Cable for charging speakers and audio output</li>
<li>Rubberized paint texture for amazing look and soft feel</li>
<li>Convenient protective carry case and cord wrap</li>
</ul>
<h6>Specifications</h6>
<ul>
<li>Impedance: 4 Ohms</li>
<li>Power: 1.75W x 2</li>
<li>Frequency response: 218Hz-20kHz</li>
<li>Signal-to-noise ratio: 52dB</li>
<li>Dimensions for each speaker (WxHxD): 1.42 x 1.42 x 0.63 in./3.6 x 3.6 x 1.6 cm</li>
<li>Weight: 0.56 oz./16.0 g</li>
</ul>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>iHome iHM77 speakers</li>
<li>Cable for charging/listening</li>
<li>Carrying case</li>
</ul>
<h6>Warranty</h6>
<p>90-day limited</p>
<p><small>Mfr. Part No.: iHM77SC</small></p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001JI820M?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001JI820M\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001JI820M\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU902VC/A?fnode=MTY1NDA5Mg&amp;mco=NjUzMzIzMg\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5012,'en',5012,'iHome iP41 Rotating Alarm Clock for iPhone or iPod','<p>Easily watch videos in landscape mode on your iPhone or iPod simply by rotating the iHome iP41. Also wake and sleep to the music on your iPhone or iPod. You can rest assured you\'ll get great sound.</p>
<ul>
<li>Charge and play your iPhone or iPod</li>
<li>Wake and sleep to iPod or iPhone</li>
<li>Watch videos on your iPhone or iPod</li>
</ul>
<h5>Overview</h5>
<p>Dock your iPhone or iPod and listen to the sound of your favorite music&mdash;beautifully reproduced by the iP41\'s Reson8 speaker technology and EXB expanded bass circuitry.</p>
<h6>Wake and sleep to your music or videos</h6>
<p>Now you can wake to a choice of music or videos on your iPhone or iPod. If you want to see the video in full screen mode, just rotate the iP41 onto its side.</p>
<h6>Features</h6>
<ul>
<li>Innovative design works upright or on its side</li>
<li>Wake or sleep to iPhone or iPod</li>
<li>Reson8 speaker technology for great sound in a compact unit</li>
<li>Universal dock charges your iPhone or iPod while it plays</li>
<li>EXB sound circuitry for extra bass power</li>
<li>DST switch for fast changes at start and end of Daylight Saving Time</li>
<li>Line-in jack to listen to other audio devices</li>
<li>Sleek space-saving design</li>
<li>Alarm reset turns off alarm and automatically rests it for same time the next day</li>
<li>Optional remote (sold separately) controls unit, navigates iPhone and iPod music functions</li>
</ul>
<h6>Did you notice?</h6>
<p>When you change the orientation of the overall unit, the LCD clock display also shifts so it\'s still easily readable.</p>
<h6>Apple Recommends for...</h6>
<p>Anyone who wants to occasionally watch a widescreen video on their iPhone or iPod while it\'s in the dock.</p>
<h6>Technical specifications</h6>
<ul>
<li>Dimensions (HxWxD): 6.29 x 6.45 x 3.34 in./16.0 x 16.4 x 8.5 cm</li>
<li>Weight: 1.5 lb./0.68 kg</li>
</ul>
<h6>What\'s in the box?</h6>
<ul>
<li>iHome iP41 Rotating Alarm Clock</li>
<li>Dock inserts to fit iPhone and specific iPod models</li>
<li>AC adapter</li>
<li>iPod nano and iPhone support cradles</li>
</ul>
<h6>Warranty</h6>
<p>One-year limited. (For details, please visit, www.iHomeaudio.com.)</p>
<p><small>Mfr. Part No.: iP41BC</small></p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001RYK2Q0?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001RYK2Q0\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001RYK2Q0\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TW030VC/A?fnode=MTY1NDA5Mg&amp;mco=NjM0MTk2Nw\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5013,'en',5013,'iMac','<table class=\"mac\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tbody>
<tr class=\"dark head\">
<td>&nbsp;</td>
<td>20-inch&nbsp;:&nbsp;2.66GHz</td>
<td>24-inch&nbsp;:&nbsp;2.66GHz</td>
<td>24-inch&nbsp;:&nbsp;2.93GHz</td>
<td>24-inch&nbsp;:&nbsp;3.06GHz</td>
</tr>
<tr>
<td class=\"left\">Price</td>
<td><strong>$1,199.00 </strong></td>
<td><strong>$1,499.00 </strong></td>
<td><strong>$1,799.00 </strong></td>
<td><strong>$2,199.00 </strong></td>
</tr>
<tr class=\"dark\">
<td class=\"left\">Processor</td>
<td colspan=\"2\">
<ul>
<li>2.66GHz Intel Core 2 Duo</li>
<li>1066MHz frontside bus</li>
<li>6MB shared L2 cache</li>
</ul>
</td>
<td>
<ul>
<li>2.93GHz Intel Core 2 Duo</li>
<li>1066MHz frontside bus</li>
<li>6MB shared L2 cache</li>
<li>Option: 3.06GHz Intel Core 2 Duo</li>
</ul>
</td>
<td>
<ul>
<li>3.06GHz Intel Core 2 Duo</li>
<li>1066MHz frontside bus</li>
<li>6MB shared L2 cache</li>
</ul>
</td>
</tr>
<tr>
<td class=\"left\">Display</td>
<td>
<ul>
<li>20-inch (viewable) glossy widescreen</li>
<li>1680 by 1050 pixels</li>
</ul>
</td>
<td colspan=\"3\">
<ul>
<li>24-inch (viewable) glossy widescreen</li>
<li>1920 by 1200 pixels</li>
</ul>
</td>
</tr>
<tr class=\"dark\">
<td class=\"left\">Memory</td>
<td>
<ul>
<li>2GB (two 1GB SO-DIMMs) of 1066MHz DDR3 SDRAM</li>
<li>Option: Up to 8GB</li>
</ul>
</td>
<td colspan=\"3\">
<ul>
<li>4GB (two 2GB SO-DIMMs) of 1066MHz DDR3 SDRAM</li>
<li>Option: Up to 8GB</li>
</ul>
</td>
</tr>
<tr>
<td class=\"left\">Hard&nbsp;drive</td>
<td>
<ul>
<li>320GB Serial ATA, 7200 rpm</li>
<li>Option: Up to 1TB</li>
</ul>
</td>
<td colspan=\"2\">
<ul>
<li>640GB Serial ATA, 7200 rpm</li>
<li>Option: Up to 1TB</li>
</ul>
</td>
<td>
<ul>
<li>1TB Serial ATA, 7200 rpm</li>
</ul>
</td>
</tr>
<tr class=\"dark\">
<td class=\"left\">Optical&nbsp;drive</td>
<td colspan=\"4\">
<ul>
<li>8x double-layer SuperDrive (DVD&plusmn;R DL/DVD&plusmn;RW/CD-RW) </li>
</ul>
</td>
</tr>
<tr>
<td class=\"left\">Graphics</td>
<td colspan=\"2\">
<ul>
<li>NVIDIA GeForce 9400M with 256MB of shared DDR3 memory</li>
</ul>
</td>
<td>
<ul>
<li>NVIDIA GeForce GT 120 with 256MB of GDDR3 memory</li>
<li>Options: NVIDIA GeForce GT 130 or ATI Radeon HD 4850 with 512MB of GDDR3 memory</li>
</ul>
</td>
<td>
<ul>
<li>NVIDIA GeForce GT 130 with 512MB of GDDR3 memory</li>
<li>Option: ATI Radeon HD 4850 with 512MB of GDDR3 memory</li>
</ul>
</td>
</tr>
<tr class=\"dark\">
<td class=\"left\">Video</td>
<td colspan=\"4\">
<ul>
<li>Built-in iSight camera</li>
<li>Mini DisplayPort output with support for DVI, dual-link DVI, and VGA video (requires adapters, sold separately)</li>
<li>Simultaneously supports full native resolution on the built-in display and up to 2560 by 1600 pixels on an external display</li>
</ul>
</td>
</tr>
<tr>
<td class=\"left\">Expansion</td>
<td colspan=\"4\">
<ul>
<li>Four USB 2.0 ports on iMac; two USB 2.0 ports on keyboard</li>
<li>One FireWire 800 port</li>
</ul>
</td>
</tr>
</tbody>
</table>
<h5>The all-in-one for everyone.</h5>
<ul>
<li><strong>A stunning display.</strong> <br />Your photos and movies will look bigger and more beautiful than ever on the 24-inch iMac&rsquo;s amazingly slim, glossy widescreen display. </li>
<li><strong>Ultrafast NVIDIA graphics.</strong><br />With advanced graphics performance, you have all the speed and power you need for the latest 3D games and graphics-intensive applications. </li>
<li><strong>Slim, all-in-one design.</strong><br />Elegant, simple, and stunning, iMac packs all its components &mdash; from the processor to the iSight camera &mdash; into an astoundingly thin, anodized aluminum frame. </li>
</ul>
<ul>
<li><strong>iLife &rsquo;09 included.</strong><br />iMac is made to create &mdash; it includes the latest versions of iPhoto, iMovie, GarageBand, iDVD, and iWeb. So make a movie or photo book, build a blog, compose a song, and more. iMac is ready when you are. </li>
<li><strong>iMac takes it all on.</strong><br />Offering an Intel Core 2 Duo processor at speeds up to 3.06GHz, twice the standard memory, and hard drive capacity up to 1TB, iMac is faster and more productive than ever. </li>
</ul>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>iMac</li>
<li>Apple Keyboard</li>
<li>Mighty Mouse</li>
<li>Power adapter and cord</li>
<li>Install/restore DVDs</li>
<li>Printed and electronic documentation</li>
</ul>
<h6>Included Software</h6>
<p><strong>Mac OS X v10.5 Leopard</strong></p>
<ul>
<li>Time Machine</li>
<li>Mail</li>
<li>iChat</li>
<li>Safari</li>
<li>Photo Booth</li>
<li>Front Row</li>
<li>Boot Camp</li>
</ul>
<p><strong>iLife &rsquo;09 Suite</strong></p>
<ul>
<li>iPhoto</li>
<li>iMovie</li>
<li>GarageBand</li>
<li>iWeb</li>
<li>iDVD</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001U0OGZ4?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001U0OGZ4\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001U0OGZ4\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/tab?node=home/shop_mac/family/imac&amp;tcid=tg_tabcontroller&amp;tab=1\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5014,'en',5014,'Incase Protective Cover for iPod touch (2nd Gen.)','<p>Protect your 2nd-generation iPod touch and complement its stylish look with this custom-molded protective cover. Its rubber exterior hugs your iPod touch and allows full functionality.</p>
<ul>
<li>Direct access to touchscreen</li>
<li>Gloss patterned motif</li>
<li>Form-fitting for protection</li>
</ul>
<h5>Overview</h5>
<p>Designed with easy-access and functionality in mind, the Protective Cover provides direct access to all iPod touch device features, including widescreen display and dock connector. The cover is constructed of form-fitting, injection molded material and features a custom topographic pattern exterior that keeps the iPod touch protected without adding bulk.</p>
<h6>Features</h6>
<ul>
<li>Excellent iPod touch protection </li>
<li>Easy access to all control and dock connector </li>
<li>Form-fitting construction </li>
<li>Allows charging while in case </li>
<li>Custom topographic pattern exterior</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002BDNZQU?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002BDNZQU\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002BDNZQU\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU162ZM/A?fnode=MTY1NDA4Mg&amp;mco=MjQyMDQyOA\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5015,'en',5015,'Incase Sports Armband for 120GB iPod classic','<p>Perfect for an active lifestyle, the Incase Sports Armband is crafted for complete functionality and protection of your iPod classic. Its clear cover gives you full access to the iPod screen and controls.</p>
<h5>Overview</h5>
<p>Constructed of lightweight, washable neoprene and reflective materials, the adjustable Incase Sports Armband is the functional and secure way to carry the iPod classic during your run or workout. The heavy-duty Velcro adjustment provides for a universal fit, while the clear cover allows convenient access to widescreen and touch controls.</p>
<h6>Features</h6>
<ul>
<li>Complete iPod protection</li>
<li>Form-fitting, washable neoprene construction</li>
<li>Reflective material for enhanced visibility in dark environments</li>
<li>Velcro adjustment for universal fit</li>
<li>Easy access to headphone jack, hold switch, and dock connector</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B0023G9OII?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B0023G9OII\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B0023G9OII\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU319ZM/A?fnode=MTY1NDA4Mg&amp;mco=MjMzNDU1OQ\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5016,'en',5016,'Incase Sports Armband for iPod nano (4th Gen.)','<p>Perfect for your active lifestyle, the Sports Armband is crafted for complete functionality and protection of your iPod nano. Offering a clear cover for complete access to the iPod screen and controls, this armband gives you a high-performance case that also delivers high functionality.</p>
<ul>
<li>Form-fitting neoprene construction</li>
<li>Quick and easy access to your iPod nano</li>
<li>Direct access to all iPod nano features, including click wheel, heaphone jack, and dock connector</li>
</ul>
<h5>Overview</h5>
<p>Constructed of lightweight, washable neoprene and reflective materials, the adjustable Incase Sports Armband is the functional and secure way to carry the iPod nano during your run or workout. The heavy-duty Velcro adjustment provides for a universal fit, while the clear cover allows convenient access to all the controls.</p>
<h6>Features</h6>
<ul>
<li>Complete iPod protection</li>
<li>Form-fitting, washable neoprene construction</li>
<li>Reflective material for enhanced visibility in dark environments</li>
<li>Velcro adjustment for universal fit</li>
<li>Easy access to headphone jack, hold switch, and dock connector</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001TABJZK?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001TABJZK\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001TABJZK\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU197ZM/A?fnode=MTY1NDA4Mg&amp;mco=MjkwODQ3OQ\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5017,'en',5017,'Incase Sports Armband for iPod touch 2nd Generation','<p>Perfect for an active lifestyle, the Incase Sports Armband is crafted for complete functionality and protection of your iPod touch. Its clear cover gives you full access to the iPod screen and controls.</p>
<h5>Overview</h5>
<p>Constructed of lightweight, washable neoprene and reflective materials, the adjustable Incase Sports Armband is the functional and secure way to carry the iPod touch during your run or workout. The heavy-duty Velcro adjustment provides for a universal fit, while the clear cover allows convenient access to the widescreen display and all controls.</p>
<h6>Features</h6>
<ul>
<li>Complete iPod protection </li>
<li>Form-fitting, washable neoprene construction </li>
<li>Reflective material for enhanced visibility in dark environments </li>
<li>Velcro adjustment for universal fit </li>
<li>Easy access to headphone jack, hold switch, and dock connector</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br /> But you can buy it at <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU160ZM/A?fnode=MTY1NDA4Mg&amp;mco=MjIwMTg0MA\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5018,'en',5018,'iPhone 3GS','<h5>Technical Specification</h5>
<h6>Size and weight</h6>
<ul>
<li>Height: <strong>4.5</strong> inches (115.5 mm)</li>
<li>Width: <strong>2.4</strong> inches (62.1 mm)</li>
<li>Depth: <strong>0.48</strong> inch (12.3 mm)</li>
<li>Weight: <strong>4.8</strong> ounces (135 grams)</li>
</ul>
<h6>Camera, photos, and video</h6>
<ul>
<li>3 megapixels</li>
<li>Autofocus</li>
<li>Tap to focus</li>
<li>Video recording, VGA up to 30 fps with audio</li>
<li>Photo and video geotagging</li>
<li>iPhone and third-party application integration</li>
</ul>
<h6>Display</h6>
<ul>
<li>3.5-inch (diagonal) widescreen Multi-Touch display</li>
<li>480-by-320-pixel resolution at 163 ppi</li>
<li>Fingerprint-resistant oleophobic coating</li>
<li>Support for display of multiple languages and characters simultaneously</li>
</ul>
<h6>Cellular and wireless</h6>
<ul>
<li>UMTS/HSDPA (850, 1900, 2100 MHz)</li>
<li>GSM/EDGE (850, 900, 1800, 1900 MHz)</li>
<li>Wi-Fi (802.11b/g)</li>
<li>Bluetooth 2.1 + EDR</li>
</ul>
<h6>Location</h6>
<ul>
<li>Assisted GPS</li>
<li>Digital compass</li>
<li>Wi-Fi</li>
<li>Cellular</li>
</ul>
<h6>Power and battery</h6>
<ul>
<li>Built-in rechargeable lithium-ion battery</li>
<li>Charging via USB to computer system or power adapter</li>
<li><dl><dt>Talk time:</dt><dd>Up to 12 hours on 2G</dd><dd>Up to 5 hours on 3G</dd></dl></li>
<li>Standby time: Up to 300 hours</li>
<li>Internet use: <br />Up to 5 hours on 3G <br />Up to 9 hours on Wi-Fi </li>
<li>Video playback: Up to 10 hours</li>
<li>Audio playback: Up to 30 hours</li>
</ul>
<h6>New features</h6>
<ul>
<li>Improved performance</li>
<li>Built-in video camera with editing</li>
<li>Voice Control</li>
<li>Compass</li>
<li>Cut, Copy, and Paste</li>
<li>MMS</li>
<li>Spotlight Search</li>
<li>Landscape Keyboard</li>
<li>Voice Memos</li>
</ul>
<h6>In the box</h6>
<ul>
<li>iPhone 3GS</li>
<li>Apple Earphones with Remote and Mic</li>
<li>Dock Connector to USB Cable</li>
<li>USB Power Adapter</li>
<li>Documentation</li>
<li>SIM eject tool</li>
</ul>
<p>
<object width=\"660\" height=\"500\" data=\"http://3dbin.com/ps/phntqbif\" type=\"application/x-shockwave-flash\">
<param name=\"allowscriptaccess\" value=\"always\" />
<param name=\"src\" value=\"http://3dbin.com/ps/phntqbif\" />
</object>
</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_iphone/family/iphone?mco=MTE2OTU\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5019,'en',5019,'iPod classic','<h5>One size fits all. The new iPod classic.</h5>
<h6>One word: Capacity.</h6>
<p>With 120GB of storage and up to 36 hours music playback, iPod classic lets you enjoy up to 30,000 songs or 150 hours of video &mdash; or a combination &mdash; wherever you go.</p>
<h6>A Genius in the house.</h6>
<p>With just a few clicks, the new Genius feature creates a playlist of tracks in your library that go great together.</p>
<h6>Enjoy the view.</h6>
<p>With Cover Flow, you can browse through your music collection by flipping through album art. Select an album to turn it over and see the track list.</p>
<h6>Classic beauty.</h6>
<p>Beautiful, durable, and sleek, iPod classic features an anodized aluminum and polished stainless steel enclosure with rounded edges. Choose quintessential silver or striking new black.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001F7AHXW?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001F7AHXW\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001F7AHXW\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_ipod/family/ipod_classic?mco=MTI4MDI\">Apple Store</a>, this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5020,'en',5020,'iPod nano','<h5>nano-chromatic. The all-new iPod nano.</h5>
<h6>Meet a musical Genius.</h6>
<p>With just a few clicks, the new Genius feature finds the songs on your iPod nano that go great together and makes a Genius Playlist for you.</p>
<h6>Rock and roll over.</h6>
<p>Thanks to the built-in accelerometer, you can rotate iPod nano to flip through album art with Cover Flow. Watch movies and TV shows in widescreen. And view photos in either portrait or landscape.</p>
<h6>Shake to shuffle.</h6>
<p>Just give iPod nano a shake and it shuffles to a different song in your music library.</p>
<h6>Curved ahead of the curve.</h6>
<p>iPod nano now comes in nine vibrant colors and a new curved aluminum and glass design. The crisp, bright picture makes watching movies and TV shows amazing.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001FA1NG4?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001FA1NG4\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001FA1NG4\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_ipod/family/ipod_nano?mco=MTE2NTc\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5021,'en',5021,'iPod nano Armband','<p>Available in gray, the all-new iPod nano Armband is the perfect iPod nano companion for a jog or a workout at the gym.</p>
<h5>Overview</h5>
<p>Exercise your right to a great soundtrack with the ultimate workout duo: an iPod nano and a flexible, lightweight armband.</p>
<p>Simply insert your iPod nano (4th generation) into the pocket, wrap the band around your arm or wrist, and secure it with the adjustable strap. The pocket even has space for the Nike + iPod Sport Kit receiver. Then plug your headphones into the jack and press Play. The 100 percent skip-free playback means you can run, ride, lift, and more without missing a moment of your favorite tunes.</p>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>iPod nano Armband</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002OHEIK0?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002OHEIK0\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002OHEIK0\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MB769G/A?fnode=MTY1NDA4Mg&amp;mco=MjE0NzkyOA\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5022,'en',5022,'iPod shuffle','<h5>The first music player that talks to you.</h5>
<p><strong>4GB</strong></p>
<ul>
<li>Small gets smaller.<br />The new iPod shuffle is amazingly small and even easier to use. The controls are now conveniently located on the earbud cord. It&rsquo;s so easy, you can use it with your eyes closed. </li>
<li>VoiceOver. It speaks for itself.<br />With the press of a button, VoiceOver tells you what song is playing and who\'s performing it. It tells you the names of your playlists, too. </li>
<li>1000 songs. Multiple playlists.<br />With 4GB of storage, iPod shuffle can now hold up to 1,000 songs.<sup>3</sup> So your music library can go with you. And you can now sync multiple playlists for the perfect mix for any mood. </li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002M3SOM4?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002M3SOM4\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002M3SOM4\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_ipod/family/ipod_shuffle?mco=MTE2NTQg\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5023,'en',5023,'Kensington PocketMouse','<h5>Overview</h5>
<p>Now optical technology goes wherever you do. This mobile optical mouse lets you take precision on the road.</p>
<ul>
<li>Ultra-compact mouse is easy to pack and use on the road</li>
<li>Improved convenience over laptop touchpad or pointing stick</li>
<li>DiamondEye optical technology tracks on virtually any surface</li>
<li>Stores easily in its own travel bag, eliminating tangled cords</li>
<li>USB connection for universal plug-and-play installation</li>
</ul>
<h6>System Requirements:</h6>
<p><strong>Mac:</strong></p>
<ul>
<li>Mac OS X, 10.1 or later</li>
<li>USB Port</li>
</ul>
<p><strong>Windows:</strong></p>
<ul>
<li>Windows 98, Me, 2000, or XP</li>
<li>USB port</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B00005U23V?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00005U23V\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B00005U23V\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TB070LL/A?fnode=MTY1NDA1Mg&amp;mco=MzE3Mzg4NA\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5024,'en',5024,'MacBook','<p><strong>2.13GHz</strong></p>
<ul>
<li>Intel Core 2 Duo </li>
<li>2GB DDR2 Memory </li>
<li>160GB hard drive<sup>1</sup> </li>
<li>NVIDIA GeForce 9400M graphics </li>
<li>Standard keyboard </li>
<li>White polycarbonate shell </li>
<li>13-inch </li>
</ul>
<p>With a durable polycarbonate shell and vibrant, glossy display, MacBook is eye-catching all over.</p>
<p>Powerful NVIDIA graphics provide outstanding performance for 3D games and applications.</p>
<p>MacBook comes standard with a 160GB hard drive, providing plenty of room for music, photos, videos, and more. Or upgrade to 500GB of storage.</p>
<p>The highly recyclable, energy-efficient MacBook is designed with the environment in mind.</p>
<h6>In the box</h6>
<ul>
<li>MacBook</li>
<li>MagSafe Power Adapter, AC wall plug, and power cord</li>
<li>Lithium-polymer battery</li>
<li>Display polishing cloth</li>
<li>Install/restore DVDs</li>
<li>Printed and electronic documentation</li>
</ul>
<h6>Included software</h6>
<p><strong>Mac OS X Leopard + iLife</strong></p>
<p>Every Mac comes with the world&rsquo;s most advanced operating system, as well as iPhoto, iMovie, GarageBand, and iWeb so you can do more with your photos, movies, and music.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001P05NJC?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001P05NJC\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001P05NJC\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_mac/family/macbook?mco=MTE2NTQ\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5025,'en',5025,'MacBook Pro 15-inch','<h6>15-inch: 2.53GHz</h6>
<ul>
<li>Intel Core 2 Duo</li>
<li>4GB Memory</li>
<li>250GB hard drive</li>
<li>SD card slot</li>
<li>Built-in 7-hour battery</li>
<li>NVIDIA GeForce 9400M graphic</li>
</ul>
<h6>15-inch: 2.66GHz</h6>
<ul>
<li>Intel Core 2 Duo</li>
<li>4GB Memory</li>
<li>320GB hard drive</li>
<li>SD card slot</li>
<li>Built-in 7-hour battery</li>
<li>NVIDIA GeForce 9400M + 9600M GT with 256MB</li>
</ul>
<h6>15-inch: 2.8GHz</h6>
<ul>
<li>Intel Core 2 Duo</li>
<li>4GB Memory</li>
<li>500GB hard drive</li>
<li>SD card slot</li>
<li>Built-in 7-hour battery</li>
<li>NVIDIA GeForce 9400M + 9600M GT with 512MB</li>
</ul>
<p>Carved from a single block of aluminum, MacBook Pro is slim, light, and durable enough to take anywhere.</p>
<p>Every new MacBook Pro features an advanced built-in battery that lasts up to 7 hours (8 hours on the 17-inch model) on a single charge.<sup>2</sup></p>
<p>High-performance NVIDIA graphics combine with a vivid LED-backlit display to give you a stunning viewing experience.</p>
<p>Highly recyclable and energy efficient, the new MacBook Pro is designed with the environment in mind.</p>
<h6>In the box</h6>
<ul>
<li>MacBook Pro</li>
<li>MagSafe Power Adapter, AC wall plug, and power cord</li>
<li>Install/restore DVDs</li>
<li>Display polishing cloth</li>
<li>Printed and electronic documentation</li>
</ul>
<h6>Included software</h6>
<p><strong>Mac OS X Leopard + iLife</strong></p>
<p>Every Mac comes with the world&rsquo;s most advanced operating system, as well as iPhoto, iMovie, GarageBand, and iWeb so you can do more with your photos, movies, and music.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002C745OQ?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002C745OQ\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002C745OQ\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_mac/family/macbook_pro?mco=MTIyMDI\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5026,'en',5026,'Mac mini','<h5>2.0GHz: 120GB</h5>
<ul>
<li>2.0GHz Intel Core 2 Duo</li>
<li>1GB memory</li>
<li>120GB hard drive</li>
<li>8x double-layer SuperDrive</li>
<li>NVIDIA GeForce 9400M graphics</li>
</ul>
<h5>2.0GHz: 320GB</h5>
<ul>
<li>2.0GHz Intel Core 2 Duo</li>
<li>2GB memory</li>
<li>320GB hard drive</li>
<li>8x double-layer SuperDrive</li>
<li>NVIDIA GeForce 9400M graphics</li>
</ul>
<h5>Faster. Greener. Still mini</h5>
<h6>Up to 5x faster NVIDIA graphics performance.</h6>
<p>The NVIDIA GeForce 9400M brings up to five times faster graphics performance to Mac mini.2 Perfect for your photos, movies, games, and applications.</p>
<h6>Faster Intel Core 2 Duo.</h6>
<p>Now there&rsquo;s even more power inside such a small space. With Intel Core 2 Duo processor speeds starting at 2.0GHz, blazing fast comes standard.</p>
<h6>Extra small. Extra green.</h6>
<p>Mac mini uses 45 percent less power than the previous model &mdash; less than 13W while it&rsquo;s idle. This makes Mac mini the most energy-efficient desktop computer in the world.</p>
<h6>iLife &rsquo;09 included.</h6>
<p>Mac mini is made to create &mdash; it includes the latest versions of iPhoto, iMovie, GarageBand, iWeb, and iDVD. So make a movie or photo book, build a blog, compose a song, and more.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001U0OFKU?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001U0OFKU\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001U0OFKU\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_mac/family/mac_mini?mco=MTI5MTI\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5027,'en',5027,'Mac Pro','<h5>Quad-Core</h5>
<ul>
<li>One 2.66GHz Quad-Core Intel Xeon \"Nehalem\" processor</li>
<li>3GB (three 1GB) memory</li>
<li>640GB hard drive</li>
<li>18x double-layer SuperDrive</li>
<li>NVIDIA GeForce GT 120 with 512MB</li>
</ul>
<h5>8-Core</h5>
<ul>
<li>Two 2.26GHz Quad-Core Intel Xeon \"Nehalem\" processors</li>
<li>6GB (six 1GB) memory</li>
<li>640GB hard drive</li>
<li>18x double-layer SuperDrive</li>
<li>NVIDIA GeForce GT 120 with 512MB</li>
</ul>
<h5>Beauty outside. Beast inside.</h5>
<h6>Next-generation Intel architecture.</h6>
<p>Get nearly 2x faster performance with the new Mac Pro, now powered by all-new Quad-Core Intel Xeon &ldquo;Nehalem&rdquo; processors. With a new system architecture, 8MB of fully shared L3 cache, an integrated memory controller, and 1066MHz DDR3 ECC memory, these processors deliver up to 2.4x greater memory bandwidth. And new Turbo Boost technology speeds up the processor when all cores aren&rsquo;t in use.</p>
<h6>Faster, more flexible graphics.</h6>
<p>The new Mac Pro features the fastest graphics ever on a Mac. Its standard configuration includes the up to 2.9x faster NVIDIA GeForce GT 120 with 512MB of GDDR3 memory. For even greater performance &mdash; up to 2x more &mdash; upgrade to the ATI Radeon HD 4870.</p>
<h6>Flexible display options.</h6>
<p>With a Mini DisplayPort and dual-link DVI port on every graphics card and room for up to four cards, the new Mac Pro provides dozens of display options.</p>
<h6>Redesigned interior.<br />Easier expansion.</h6>
<p>A completely redesigned interior makes it even easier to access and upgrade your Mac Pro. Snap in up to 32GB of memory. Pop in up to four PCI Express 2.0 expansion cards. Slide in up to 4TB of storage. Add in the new, easier-to-install Mac Pro RAID Card. All without turning the system over or struggling with tight spaces.</p>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001KMYT3G?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001KMYT3G\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001KMYT3G\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/browse/home/shop_mac/family/mac_pro?mco=MjI5MDE1\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5028,'en',5028,'Nike + iPod Sport Kit','<p>Transform your iPod nano, iPod touch (2nd generation), or iPhone 3G S into a personal workout coach with the Nike + iPod Sport Kit. This wireless sensor and receiver combination works exclusively with your Nike+ shoes and iPod nano, iPod touch, or iPhone 3G S to give you real-time feedback during workouts and let you track your performance on your Mac or PC.</p>
<h5>Overview</h5>
<p>Thanks to a unique partnership between Nike and Apple, your iPod nano, iPod touch (2nd generation), or iPhone 3G S becomes your coach. Your personal trainer. Your favorite workout companion.</p>
<h6>Tune</h6>
<p>Insert the wireless sensor inside the custom, built-in pocket beneath the insole of your Nike+ shoe, then plug the receiver into the Dock connector on your iPod nano. The iPod touch (2nd generation) and iPhone 3G S includes built-in support for the system &mdash; no receiver necessary.</p>
<h6>Run</h6>
<p>Now start your workout. As your run or walk, the sensor sends information to your device, tracking your time, distance, pace, and calories burned. If you choose, real-time, spoken feedback can even alert you to milestones throughout your workout.</p>
<h6>Sync</h6>
<p>Back at your computer, sync your iPod or iPhone 3G S to transfer your workout data to iTunes and nikeplus.com. There, you can evaluate your performance history, set goals, and even challenge other runners to a virtual race.</p>
<h6>What\'s in the Box</h6>
<ul>
<li>Wireless sensor for Nike+ shoes</li>
<li>Wireless receiver for iPod nano</li>
<li>Printed documentation</li>
</ul>
<h6>Requirements</h6>
<ul>
<li>iPod nano, iPod touch (2nd generation), or iPhone 3G S</li>
<li>Nike+ shoes</li>
<li>iPod nano software v1.2</li>
<li>iTunes 8.2 or later (available via free download)</li>
<li>A Mac with a USB 2.0 port and Mac OS X version 10.3.9 or later; or a PC with a USB 2.0 port and Windows 2000 (SP4) or XP Home or Professional (SP2)</li>
<li>Internet access and a free Nike.com account</li>
</ul>
<h6>Specifications</h6>
<p><strong>Sensor</strong></p>
<ul>
<li>Size: 1.37 x 0.95 x 0.30 inches</li>
<li>Weight: 0.23 ounce</li>
<li>Broadcast frequency: 2.4GHz</li>
</ul>
<p><strong>Receiver</strong></p>
<ul>
<li>Size: 1.03 x 0.62 x 0.22 inches</li>
<li>Weight: 0.12 ounce</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B000JVFKH8?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B000JVFKH8\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B000JVFKH8\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/MA365LL/D/Nike-iPod-Sport%20Kit?fnode=MTY1NDA3NA&amp;mco=Mjc1MDk\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5029,'en',5029,'Paul Frank Dots Julius Silicone Case for iPod touch (2nd Gen.)','<p>Safeguard your 2nd generation iPod touch with this form-fitting protective case from Paul Frank. It\'s a durable, stylish rubber surround that keeps your iPod looking as good as new, with a classic Paul Frank design.</p>
<ul>
<li>Custom fit for more grip without extra bulk</li>
<li>Access to all controls, touch screen and dock</li>
<li>Durable and flexible silicone construction</li>
</ul>
<h5>Overview</h5>
<p>Paul Frank brings you a fashionable way to protect your 2nd generation iPod touch. Made from super-durable silicone, this case hugs your iPod touch and keeps it safe from accidental damage while you\'re listening to your music on the go. Flexible, silicone style</p>
<p>It allows full functionality, with custom cutouts providing access to all touch controls, headphone jack, dock connector for easy in-case use. It\'s a great way to protect and add the Paul Frank style to your sleek iPod touch without extra bulk.</p>
<h6>Features</h6>
<ul>
<li>Simply slips on for easy use</li>
<li>Form-fitting for effective grip and protection without adding bulk</li>
<li>Access to all touch controls and dock connector and headphone jack</li>
<li>Durable, flexible silicone rubber construction</li>
<li>Stylish Paul Frank graphics</li>
</ul>
<h6>Did you notice?</h6>
<p>Custom cutouts provide access to all touch controls, dock connector and headphones jack for easy in-case use.</p>
<h6>Apple Recommends for...</h6>
<p>Adding the Paul Frank style to your sleek iPod touch to safeguard it from damage, without adding extra bulk.</p>
<h6>What\'s in the box?</h6>
<ul>
<li>Paul Frank Case for iPod touch (2nd Gen.)</li>
<li>Care instructions</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B002629F7Y?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B002629F7Y\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B002629F7Y\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TV756LL/A?fnode=MTY1NDA4Mg&amp;mco=NDQ5MDk3OQ\">Apple Store</a> , this is where we took description from.</div>','','','','');
INSERT INTO `xlite_product_translations` VALUES (5030,'en',5030,'Sennheiser PMX 80 Sport II Headphones for iPhone and iPod','<p>Sleek and sporty, the PMX 80 Sport II headphones are rugged enough to withstand your toughest workout. They&rsquo;re the perfect choice if you prefer a neckband-style fit.</p>
<h5>Overview</h5>
<p>The PMX 80 Sport II headphones give you the optimum fit and comfort of an ergonomic neckband and vertical transducer system. Their sweat-proof materials are built to handle indoor and outdoor sports with ease.</p>
<h6>Features</h6>
<ul>
<li>Ergonomic neckband system for the best possible fit</li>
<li>Powerful neodymium magnets for balanced, detailed sound reproduction</li>
<li>Sennheiser Basswind System delivers powerful bass response</li>
<li>Meticulous construction for serious durability</li>
<li>Reflective neckband to ensure extra visibility and safety outdoors</li>
<li>Sweat- and water-resistant materials</li>
<li>Washable under running water</li>
<li>Single-sided cable clip</li>
</ul>
<h6>Specifications</h6>
<ul>
<li>Nominal impedance: 64 Ohm</li>
<li>Jack plug: 0.14 in./3.5 mm stereo, right angled</li>
<li>Transducer principle (Headphones): Dynamic</li>
<li>Frequency response (headphones): 19Hz to 21 kHz</li>
<li>Mac spl: 121 dB (1kHz/1Vrms)</li>
<li>Thd: &lt;0.1%</li>
<li>Weight (without cable): Approx. 0.56 oz./16.0 g</li>
</ul>
<h6>What&rsquo;s in the Box</h6>
<ul>
<li>PMX 80 II Sport headphones</li>
<li>Cable clip</li>
</ul>
<div style=\"padding: 24px 24px 24px 21px; display: block; background-color: #ececec;\">This site is a demo site only. This product is not available for sale at our site. <br />But you can buy it at <a href=\"http://www.amazon.com/gp/product/B001GT185K?ie=UTF8&amp;tag=ecwid0b-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001GT185K\">Amazon</a><img style=\"border:none !important; margin:0px !important;\" src=\"http://www.assoc-amazon.com/e/ir?t=ecwid0b-20&amp;l=as2&amp;o=1&amp;a=B001GT185K\" border=\"0\" alt=\"\" width=\"1\" height=\"1\" /> or <a style=\"color: #1e7ec8; text-decoration: underline;\" title=\"Apple Store\" href=\"http://store.apple.com/us/product/TU136ZM/A?fnode=MTY1NDA0Ng&amp;mco=MjM0NDUyOQ\">Apple Store</a> , this is where we took description from.</div>','','','','');

--INSERT INTO xlite_inventories SET `inventory_id` = '4003', `amount` = '496', `enabled` = '1', `low_avail_limit` = '0';
INSERT INTO xlite_option_groups SET group_id = 4000, `product_id` = 4003, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4000, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4000, group_id = 4000, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4000, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4001, group_id = 4000, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4001, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4002, group_id = 4000, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4002, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4003, group_id = 4000, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4003, 'en', 'XL');

--INSERT INTO xlite_inventories SET `inventory_id` = '4004', `amount` = '965', `enabled` = '1', `low_avail_limit` = '50';
INSERT INTO xlite_option_groups SET group_id = 4001, `product_id` = 4004, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4001, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4004, group_id = 4001, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4004, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4005, group_id = 4001, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4005, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4006, group_id = 4001, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4006, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4007, group_id = 4001, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4007, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4008, group_id = 4001, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4008, 'en', 'XXL');

--INSERT INTO xlite_inventories SET `inventory_id` = '4009', `amount` = '172', `enabled` = '1', `low_avail_limit` = '150';
INSERT INTO xlite_option_groups SET group_id = 4002, `product_id` = 4009, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4002, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4009, group_id = 4002, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4009, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4010, group_id = 4002, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4010, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4011, group_id = 4002, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4011, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4012, group_id = 4002, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4012, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4013, group_id = 4002, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4013, 'en', 'XXL');
INSERT INTO xlite_option_surcharges SET option_id = 4013, `type` = 'price', modifier = -10,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4003, `product_id` = 159702, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4003, 'en', 'Color');
INSERT INTO xlite_options SET option_id = 4014, group_id = 4003, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4014, 'en', 'White');
INSERT INTO xlite_options SET option_id = 4015, group_id = 4003, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4015, 'en', 'Black');
INSERT INTO xlite_options SET option_id = 4016, group_id = 4003, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4016, 'en', 'Retro Red');
INSERT INTO xlite_options SET option_id = 4017, group_id = 4003, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4017, 'en', 'Retro Silver');

INSERT INTO xlite_option_groups SET group_id = 4004, `product_id` = 4005, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4004, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4018, group_id = 4004, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4018, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4019, group_id = 4004, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4019, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4020, group_id = 4004, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4020, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4021, group_id = 4004, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4021, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4022, group_id = 4004, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4022, 'en', 'XXL');

INSERT INTO xlite_option_groups SET group_id = 4005, `product_id` = 4008, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4005, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4023, group_id = 4005, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4023, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4024, group_id = 4005, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4024, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4025, group_id = 4005, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4025, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4026, group_id = 4005, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4026, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4027, group_id = 4005, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4027, 'en', 'XXL');
INSERT INTO xlite_option_surcharges SET option_id = 4027, `type` = 'price', modifier = 1,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4006, `product_id` = 4010, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4006, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4028, group_id = 4006, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4028, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4029, group_id = 4006, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4029, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4030, group_id = 4006, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4030, 'en', 'XL');

INSERT INTO xlite_option_groups SET group_id = 4007, `product_id` = 4007, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4007, 'en', 'Sleeve');
INSERT INTO xlite_options SET option_id = 4031, group_id = 4007, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4031, 'en', 'Short');
INSERT INTO xlite_options SET option_id = 4032, group_id = 4007, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4032, 'en', 'Long');
INSERT INTO xlite_option_surcharges SET option_id = 4032, `type` = 'price', modifier = 3,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4008, `product_id` = 4007, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4008, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4033, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4033, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4034, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4034, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4035, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4035, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4036, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4036, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4037, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4037, 'en', 'XXL');
INSERT INTO xlite_option_surcharges SET option_id = 4037, `type` = 'price', modifier = 1,  modifier_type = '$';
INSERT INTO xlite_options SET option_id = 4038, group_id = 4008, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4038, 'en', 'XXXL');
INSERT INTO xlite_option_surcharges SET option_id = 4038, `type` = 'price', modifier = 2,  modifier_type = '$';

--INSERT INTO xlite_inventories SET `inventory_id` = '4006', `amount` = '1994', `enabled` = '1', `low_avail_limit` = '50';
INSERT INTO xlite_option_groups SET group_id = 4009, `product_id` = 4006, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4009, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4039, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4039, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4040, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4040, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4041, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4041, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4042, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4042, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4043, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4043, 'en', 'XXL');
INSERT INTO xlite_option_surcharges SET option_id = 4043, `type` = 'price', modifier = 1,  modifier_type = '$';
INSERT INTO xlite_options SET option_id = 4044, group_id = 4009, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4044, 'en', 'XXXL');
INSERT INTO xlite_option_surcharges SET option_id = 4044, `type` = 'price', modifier = 2,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4010, `product_id` = 3002, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4010, 'en', 'Color');
INSERT INTO xlite_options SET option_id = 4045, group_id = 4010, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4045, 'en', 'Bimini');
INSERT INTO xlite_options SET option_id = 4046, group_id = 4010, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4046, 'en', 'Storm Blue');

INSERT INTO xlite_option_groups SET group_id = 4011, `product_id` = 3002, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4011, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4047, group_id = 4011, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4047, 'en', 'S');
INSERT INTO xlite_options SET option_id = 4048, group_id = 4011, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4048, 'en', 'M');
INSERT INTO xlite_options SET option_id = 4049, group_id = 4011, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4049, 'en', 'L');
INSERT INTO xlite_options SET option_id = 4050, group_id = 4011, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4050, 'en', 'XL');
INSERT INTO xlite_options SET option_id = 4051, group_id = 4011, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4051, 'en', 'XXL');

INSERT INTO xlite_option_groups SET group_id = 4012, `product_id` = 4052, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4012, 'en', 'Color');
INSERT INTO xlite_options SET option_id = 4052, group_id = 4012, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4052, 'en', 'Blue LED');
INSERT INTO xlite_options SET option_id = 4053, group_id = 4012, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4053, 'en', 'Red LED');

INSERT INTO xlite_option_groups SET group_id = 4013, `product_id` = 4053, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4013, 'en', 'Scale');
INSERT INTO xlite_options SET option_id = 4054, group_id = 4013, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4054, 'en', '1/24 Green');
INSERT INTO xlite_options SET option_id = 4055, group_id = 4013, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4055, 'en', '1/16 Gray');
INSERT INTO xlite_option_surcharges SET option_id = 4055, `type` = 'price', modifier = 50,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4014, `product_id` = 4040, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4014, 'en', 'Choice');
INSERT INTO xlite_options SET option_id = 4056, group_id = 4014, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4056, 'en', 'Basic Hamacron');
INSERT INTO xlite_option_surcharges SET option_id = 4056, `type` = 'price', modifier = 40,  modifier_type = '$';
INSERT INTO xlite_options SET option_id = 4057, group_id = 4014, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4057, 'en', 'T-Rex');
INSERT INTO xlite_options SET option_id = 4058, group_id = 4014, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4058, 'en', 'Triceratops');

INSERT INTO xlite_option_groups SET group_id = 4015, `product_id` = 4035, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4015, 'en', 'Choice');
INSERT INTO xlite_options SET option_id = 4059, group_id = 4015, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4059, 'en', 'Music Box Kit');
INSERT INTO xlite_options SET option_id = 4060, group_id = 4015, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4060, 'en', 'Refill Pack');
INSERT INTO xlite_option_surcharges SET option_id = 4060, `type` = 'price', modifier = -8,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4016, `product_id` = 4014, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4016, 'en', 'Size');
INSERT INTO xlite_options SET option_id = 4061, group_id = 4016, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4061, 'en', '4\'\' (Silver)');
INSERT INTO xlite_options SET option_id = 4062, group_id = 4016, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4062, 'en', '8\'\'');
INSERT INTO xlite_option_surcharges SET option_id = 4062, `type` = 'price', modifier = 30,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4017, `product_id` = 4023, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4017, 'en', 'Choice');
INSERT INTO xlite_options SET option_id = 4063, group_id = 4017, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4063, 'en', '1-pack');
INSERT INTO xlite_options SET option_id = 4064, group_id = 4017, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4064, 'en', '2-pack');
INSERT INTO xlite_option_surcharges SET option_id = 4064, `type` = 'price', modifier = 9,  modifier_type = '$';
INSERT INTO xlite_options SET option_id = 4065, group_id = 4017, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4065, 'en', '3-pack');
INSERT INTO xlite_option_surcharges SET option_id = 4065, `type` = 'price', modifier = 15,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 4018, `product_id` = 4032, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4018, 'en', 'Choice');
INSERT INTO xlite_options SET option_id = 4066, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4066, 'en', 'Solar Blue');
INSERT INTO xlite_options SET option_id = 4067, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4067, 'en', 'Dark Matter');
INSERT INTO xlite_options SET option_id = 4068, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4068, 'en', 'Atmosphere');
INSERT INTO xlite_options SET option_id = 4069, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4069, 'en', 'Aliene Ooze');
INSERT INTO xlite_options SET option_id = 4070, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4070, 'en', 'Martian Sea');
INSERT INTO xlite_options SET option_id = 4071, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4071, 'en', 'Sunburst');
INSERT INTO xlite_options SET option_id = 4072, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4072, 'en', 'Oil Slick');
INSERT INTO xlite_options SET option_id = 4073, group_id = 4018, enabled = 1;
INSERT INTO xlite_option_translations (id, code, name) VALUES (4073, 'en', 'Twilight');

-- Featured products
INSERT INTO xlite_featured_products VALUES (1,4006,1,10);
INSERT INTO xlite_featured_products VALUES (2,3002,1,20);
INSERT INTO xlite_featured_products VALUES (3,4059,1,30);
INSERT INTO xlite_featured_products VALUES (4,4043,1,40);
INSERT INTO xlite_featured_products VALUES (5,4020,1,50);
INSERT INTO xlite_featured_products VALUES (6,4047,1004,10);
INSERT INTO xlite_featured_products VALUES (7,4015,1004,20);
INSERT INTO xlite_featured_products VALUES (8,4012,1004,30);

UPDATE xlite_categories SET show_title = '0' WHERE category_id = '1004';

-- PRODUCT IMAGES

INSERT INTO `xlite_product_images` VALUES (NULL,4003,'demo_store_p4003.jpeg','image/jpeg',500,494,29749,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4059,'demo_store_p4059.jpeg','image/jpeg',240,240,57186,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4042,'demo_store_p4042.jpeg','image/jpeg',400,323,65760,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4045,'demo_store_p4045.jpeg','image/jpeg',400,408,51049,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4043,'demo_store_p4043.jpeg','image/jpeg',400,330,52150,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,159702,'demo_store_p159702.jpeg','image/jpeg',400,274,29414,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4049,'demo_store_p4049.jpeg','image/jpeg',400,392,22506,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4005,'demo_store_p4005.jpeg','image/jpeg',400,395,25085,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4008,'demo_store_p4008.jpeg','image/jpeg',385,500,127001,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4010,'demo_store_p4010.gif','image/gif',423,500,83550,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4007,'demo_store_p4007.jpeg','image/jpeg',389,500,55102,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4006,'demo_store_p4006.jpeg','image/jpeg',382,500,63238,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,3002,'demo_store_p3002.jpeg','image/jpeg',389,500,48294,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,159704,'demo_store_p159704.jpeg','image/jpeg',378,500,80309,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4052,'demo_store_p4052.jpeg','image/jpeg',388,500,65497,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4056,'demo_store_p4056.jpeg','image/jpeg',400,379,29951,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4054,'demo_store_p4054.jpeg','image/jpeg',400,391,29629,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4038,'demo_store_p4038.jpeg','image/jpeg',400,305,31425,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4033,'demo_store_p4033.jpeg','image/jpeg',400,412,57091,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4026,'demo_store_p4026.jpeg','image/jpeg',400,357,51846,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4050,'demo_store_p4050.jpeg','image/jpeg',400,300,76513,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_p4053.jpeg','image/jpeg',410,300,69309,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4055,'demo_store_p4055.jpeg','image/jpeg',400,417,24232,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4051,'demo_store_p4051.jpeg','image/jpeg',381,500,57396,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4046,'demo_store_p4046.jpeg','image/jpeg',400,330,21372,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4047,'demo_store_p4047.jpeg','image/jpeg',400,410,27543,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4048,'demo_store_p4048.gif','image/gif',400,374,179960,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4039,'demo_store_p4039.jpeg','image/jpeg',400,300,24107,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4040,'demo_store_p4040.jpeg','image/jpeg',400,349,18863,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4041,'demo_store_p4041.jpeg','image/jpeg',400,398,28252,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4044,'demo_store_p4044.jpeg','image/jpeg',400,383,31441,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4035,'demo_store_p4035.jpeg','image/jpeg',400,326,21306,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4034,'demo_store_p4034.jpeg','image/jpeg',400,312,36619,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4027,'demo_store_p4027.jpeg','image/jpeg',400,247,17379,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4020,'demo_store_p4020.jpeg','image/jpeg',373,500,70467,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4011,'demo_store_p4011.jpeg','image/jpeg',400,426,70431,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_p4014.jpeg','image/jpeg',220,223,10082,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4017,'demo_store_p4017.jpeg','image/jpeg',400,275,13260,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4018,'demo_store_p4018.jpeg','image/jpeg',347,500,59259,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4028,'demo_store_p4028.jpeg','image/jpeg',400,452,34363,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4029,'demo_store_p4029.jpeg','image/jpeg',134,500,34917,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4057,'demo_store_p4057.jpeg','image/jpeg',240,240,36720,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4036,'demo_store_p4036.jpeg','image/jpeg',240,240,36235,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4061,'demo_store_p4061.jpeg','image/jpeg',240,280,76142,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4015,'demo_store_p4015.jpeg','image/jpeg',297,500,59276,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4023,'demo_store_p4023.jpeg','image/jpeg',263,500,49640,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_p4032.jpeg','image/jpeg',401,500,36485,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4025,'demo_store_p4025.jpeg','image/jpeg',400,432,23147,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4019,'demo_store_p4019.jpeg','image/jpeg',212,500,51948,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_p4024.jpeg','image/jpeg',400,454,26961,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4060,'demo_store_p4060.jpeg','image/jpeg',240,240,51076,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4058,'demo_store_p4058.jpeg','image/jpeg',240,240,41450,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4062,'demo_store_p4062.jpeg','image/jpeg',360,440,104054,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4063,'demo_store_p4063.jpeg','image/jpeg',240,280,70307,1280310731,'','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,4004,'demo_store_d4004_1.jpeg','image/jpeg',220,355,14696,0,'','Front',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4004,'demo_store_p4004.jpeg','image/jpeg',489,500,115220,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4009,'demo_store_d4009_1.jpeg','image/jpeg',400,283,23325,0,'','Close up',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4009,'demo_store_d4009_2.jpeg','image/jpeg',400,491,23816,0,'','In use',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4009,'demo_store_p4009.gif','image/gif',400,473,111939,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4030,'demo_store_d4030_1.jpeg','image/jpeg',400,326,25880,0,'','Ninja warning issued',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4030,'demo_store_p4030.jpeg','image/jpeg',400,278,10810,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4031,'demo_store_d4031_1.jpeg','image/jpeg',400,551,24679,0,'','Stack Pack 3-Pack',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4031,'demo_store_p4031.jpeg','image/jpeg',298,500,53557,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4013,'demo_store_d4013_1.jpeg','image/jpeg',400,318,25319,0,'','On Desk',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4013,'demo_store_d4013_2.jpeg','image/jpeg',400,300,28271,0,'','Maintaining the Servers',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4013,'demo_store_p4013.jpeg','image/jpeg',355,500,48991,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4016,'demo_store_d4016_1.jpeg','image/jpeg',400,276,18074,0,'','Where a geek hangs his hat',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4016,'demo_store_p4016.jpeg','image/jpeg',233,500,50409,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4022,'demo_store_d4022_1.jpeg','image/jpeg',400,300,21329,0,'','Mug Henge!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4022,'demo_store_p4022.jpeg','image/jpeg',400,280,25544,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4021,'demo_store_d4021_1.jpeg','image/jpeg',400,300,30401,0,'','On desk',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4021,'demo_store_p4021.jpeg','image/jpeg',400,424,23118,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4012,'demo_store_d4012_1.jpeg','image/jpeg',400,370,28746,0,'','Retro Packaging',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4012,'demo_store_d4012_2.jpeg','image/jpeg',400,377,27496,0,'','It\'s fun',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4012,'demo_store_p4012.jpeg','image/jpeg',400,297,33532,0,'','',-1);
INSERT INTO `xlite_product_images` VALUES (NULL,4042,'demo_store_d4042_1.jpeg','image/jpeg',400,277,59968,0,'','Solar car in hand',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4042,'demo_store_d4042_2.jpeg','image/jpeg',400,257,54455,0,'','In packaging',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4045,'demo_store_d4045_1.jpeg','image/jpeg',400,279,20764,0,'','Pyramid Power!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,159702,'demo_store_d159702_1.jpeg','image/jpeg',400,140,15801,0,'','',0);
INSERT INTO `xlite_product_images` VALUES (NULL,159702,'demo_store_d159702_2.jpeg','image/jpeg',400,266,24827,0,'','Autonomous navigation',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4049,'demo_store_d4049_1.jpeg','image/jpeg',400,400,22224,0,'','Actual size',0);
INSERT INTO `xlite_product_images` VALUES (NULL,159704,'demo_store_d159704_1.gif','image/gif',400,428,794960,0,'','',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4052,'demo_store_d4052_1.jpeg','image/jpeg',400,303,21087,0,'','Precise control',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4052,'demo_store_d4052_2.jpeg','image/jpeg',400,295,17109,0,'','Smallest 3-channel copter',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4054,'demo_store_d4054_1.jpeg','image/jpeg',400,447,23671,0,'','Keep tight reigns on your tiny subject',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4038,'demo_store_d4038_1.jpeg','image/jpeg',550,308,79007,0,'','',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4033,'demo_store_d4033_1.jpeg','image/jpeg',400,457,51747,0,'','Various Shapes',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4050,'demo_store_d4050_1.jpeg','image/jpeg',400,272,66432,0,'','Mario Kart on your desk!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_d4053_1.jpeg','image/jpeg',400,300,11206,0,'','ammo',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_d4053_2.jpeg','image/jpeg',400,300,13482,0,'','Tank Dude, Ammo In Background',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_d4053_3.jpeg','image/jpeg',400,300,19442,0,'','Ammo Load Zone',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_d4053_4.jpeg','image/jpeg',286,400,8530,0,'','Remote',3);
INSERT INTO `xlite_product_images` VALUES (NULL,4053,'demo_store_d4053_5.jpeg','image/jpeg',400,632,42121,0,'','',4);
INSERT INTO `xlite_product_images` VALUES (NULL,4055,'demo_store_d4055_1.jpeg','image/jpeg',382,527,60774,0,'','Interface',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4055,'demo_store_d4055_2.jpeg','image/jpeg',400,278,17385,0,'','Office Moon mission',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4051,'demo_store_d4051_1.jpeg','image/jpeg',400,536,29007,0,'','Amazing tiny size!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4051,'demo_store_d4051_2.jpeg','image/jpeg',400,261,14372,0,'','Alien invasion!',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4046,'demo_store_d4046_1.jpeg','image/jpeg',400,396,29017,0,'','Rotating a Quadrant',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4048,'demo_store_d4048_1.jpeg','image/jpeg',400,282,54926,0,'','On desk',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4039,'demo_store_d4039_1.jpeg','image/jpeg',400,400,24886,0,'','Pour the solution . . . grow the crystals!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4039,'demo_store_d4039_2.jpeg','image/jpeg',400,400,55213,0,'','All the parts',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4040,'demo_store_d4040_1.jpeg','image/jpeg',500,147,69498,0,'','Boxes',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4041,'demo_store_d4041_1.jpeg','image/jpeg',400,265,21708,0,'','Parts',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4044,'demo_store_d4044_1.jpeg','image/jpeg',400,287,24787,0,'','What You Get',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4044,'demo_store_d4044_2.jpeg','image/jpeg',400,322,24819,0,'','Two-Player Action!',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4044,'demo_store_d4044_3.jpeg','image/jpeg',400,247,6002,0,'','Screenshot',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4027,'demo_store_d4027_1.jpeg','image/jpeg',400,313,21024,0,'','Don\'t Eat My Brains!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4027,'demo_store_d4027_2.jpeg','image/jpeg',400,300,19813,0,'','DOMOKUN!!',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_1.jpeg','image/jpeg',367,400,17515,0,'','Truly Levitating!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_2.jpeg','image/jpeg',313,500,18645,0,'','Closeup Of 4\" Globe',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_3.jpeg','image/jpeg',400,400,38573,0,'','4\" Globe Detail',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_4.jpeg','image/jpeg',400,520,33856,0,'','Closeup of 8\" Globe',3);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_5.jpeg','image/jpeg',400,400,31115,0,'','8\" Globe on Desk',4);
INSERT INTO `xlite_product_images` VALUES (NULL,4014,'demo_store_d4014_6.jpeg','image/jpeg',400,478,35479,0,'','8\" Globe Detail',5);
INSERT INTO `xlite_product_images` VALUES (NULL,4028,'demo_store_d4028_1.jpeg','image/jpeg',400,316,19497,0,'','It goes in your hand!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4015,'demo_store_d4015_1.jpeg','image/jpeg',220,164,6701,0,'','Robot Oil (Bare Metal Bender)',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4015,'demo_store_d4015_2.jpeg','image/jpeg',220,173,9345,0,'','Chest Opens! (Bare Metal Bender)',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4023,'demo_store_d4023_1.jpeg','image/jpeg',400,588,30120,0,'','What you get',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4023,'demo_store_d4023_2.jpeg','image/jpeg',400,300,32712,0,'','How to Mix Drinks',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_1.jpeg','image/jpeg',400,379,4637,0,'','Alien Ooze (Inert!)',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_2.jpeg','image/jpeg',400,400,9811,0,'','Alien Ooze (Glowing!)',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_3.jpeg','image/jpeg',400,379,15392,0,'','Atmosphere',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_4.jpeg','image/jpeg',400,379,18272,0,'','Atomic Bronze',3);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_5.jpeg','image/jpeg',400,379,10766,0,'','Dark Matter',4);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_6.jpeg','image/jpeg',400,413,19117,0,'','Martian Sea',5);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_7.jpeg','image/jpeg',400,379,15285,0,'','Oil Slick',6);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_8.jpeg','image/jpeg',400,379,13070,0,'','Solar Blue',7);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_9.jpeg','image/jpeg',400,355,13097,0,'','Sunburst (Morphs from Orange to Yellow)',8);
INSERT INTO `xlite_product_images` VALUES (NULL,4032,'demo_store_d4032_10.jpeg','image/jpeg',400,355,15065,0,'','Twilight (Morphs from Lt Blue to Dark Purple)',9);
INSERT INTO `xlite_product_images` VALUES (NULL,4025,'demo_store_d4025_1.jpeg','image/jpeg',400,264,11389,0,'','Your Pencil, Master!',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4025,'demo_store_d4025_2.jpeg','image/jpeg',400,351,19008,0,'','Sharpening a Pencil',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4025,'demo_store_d4025_3.jpeg','image/jpeg',400,397,91365,0,'','Brain (Pencil Shavings)',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4025,'demo_store_d4025_4.gif','image/gif',200,137,78383,0,'','',3);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_d4024_1.jpeg','image/jpeg',400,507,38153,0,'','Jedi Training',0);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_d4024_2.jpeg','image/jpeg',400,428,25557,0,'','Zippered Backpack',1);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_d4024_3.jpeg','image/jpeg',400,395,31686,0,'','Close Up',2);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_d4024_4.jpeg','image/jpeg',400,402,60090,0,'','Jedi Gettin\' Tough',3);
INSERT INTO `xlite_product_images` VALUES (NULL,4024,'demo_store_d4024_5.jpeg','image/jpeg',400,421,26850,0,'','Stuffed Backpack',4);
INSERT INTO `xlite_product_images` VALUES (NULL,5000,'demo_store_d5000.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5001,'demo_store_d5001.jpg','image/jpeg',185,210,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5002,'demo_store_d5002.jpg','image/jpeg',326,360,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5003,'demo_store_d5003.jpg','image/jpeg',326,360,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5004,'demo_store_d5004.jpg','image/jpeg',245,395,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5005,'demo_store_d5005.jpg','image/jpeg',500,500,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5006,'demo_store_d5006.jpg','image/jpeg',370,200,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5007,'demo_store_d5007.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5008,'demo_store_d5008.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5009,'demo_store_d5009.jpg','image/jpeg',326,402,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5010,'demo_store_d5010.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5011,'demo_store_d5011.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5012,'demo_store_d5012.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5013,'demo_store_d5013.jpg','image/jpeg',499,500,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5014,'demo_store_d5014.jpg','image/jpeg',326,350,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5015,'demo_store_d5015.jpg','image/jpeg',326,350,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5016,'demo_store_d5016.jpg','image/jpeg',326,350,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5017,'demo_store_d5017.jpg','image/jpeg',326,350,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5018,'demo_store_d5018.jpg','image/jpeg',500,477,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5019,'demo_store_d5019.jpg','image/jpeg',442,500,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5020,'demo_store_d5020.jpg','image/jpeg',442,500,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5021,'demo_store_d5021.jpg','image/jpeg',326,350,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5022,'demo_store_d5022.jpg','image/jpeg',500,450,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5023,'demo_store_d5023.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5024,'demo_store_d5024.jpg','image/jpeg',500,325,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5025,'demo_store_d5025.jpg','image/jpeg',500,290,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5026,'demo_store_d5026.jpg','image/jpeg',500,375,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5027,'demo_store_d5027.jpg','image/jpeg',350,300,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5028,'demo_store_d5028.jpg','image/jpeg',326,360,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5029,'demo_store_d5029.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
INSERT INTO `xlite_product_images` VALUES (NULL,5030,'demo_store_d5030.jpg','image/jpeg',326,326,0,'1280310731','','',-10);
