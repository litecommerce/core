INSERT INTO `xlite_orders` SET order_id = 1, profile_id = 3, orig_profile_id = 2, total = '20.7599', subtotal = '17.9900', is_order = 1, date = 1283939344, status = 'P', taxes = 'a:1:{s:3:\"Tax\";s:4:\"0.00\";}';
INSERT INTO xlite_payment_transactions (transaction_id,order_id,method_id,method_name,method_local_name,status,value,type,note) VALUES (1,1,5,'MoneyOrdering','Money Ordering','S',20.7599,'sale','');
INSERT INTO `xlite_orders` SET order_id = 2, profile_id = 4, orig_profile_id = 2, total = '121.1137', subtotal = '116.9100', is_order = 1, date = 1283867845, status = 'C', taxes = 'a:1:{s:3:\"Tax\";s:4:\"0.00\";}';
INSERT INTO xlite_payment_transactions (transaction_id,order_id,method_id,method_name,method_local_name,status,value,type,note) VALUES (2,2,5,'MoneyOrdering','Money Ordering','S',121.1137,'sale','');

INSERT INTO `xlite_order_items` SET item_id=1, order_id=1, object_id=3002, object_type='product', name='Binary Mom', sku='00000', price='17.9900', amount=1, subtotal='17.9900', total='17.9900';
INSERT INTO `xlite_order_items` SET item_id=2, order_id=2, object_id=4059, object_type='product', name='Paint Shop Pro Web Graphics [PDF]', sku='00057', price='39.9500', amount=1, subtotal='39.9500', total='39.9500';
INSERT INTO `xlite_order_items` SET item_id=3, order_id=2, object_id=4003, object_type='product', name='Planet Express Babydoll', sku='00001', price='19.9900', amount=1, subtotal='19.9900', total='19.9900';
INSERT INTO `xlite_order_items` SET item_id=4, order_id=2, object_id=4008, object_type='product', name='Planet Express', sku='00006', price='18.9900', amount=1, subtotal='18.9900', total='18.9900';
INSERT INTO `xlite_order_items` SET item_id=5, order_id=2, object_id=4010, object_type='product', name='Wi-Fi Detector Shirt', sku='00008', price='19.9900', amount=1, subtotal='19.9900', total='19.9900';
INSERT INTO `xlite_order_items` SET item_id=6, order_id=2, object_id=3002, object_type='product', name='Binary Mom', sku='00000', price='17.9900', amount=1, subtotal='17.9900', total='17.9900';

INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (1,1,4010,4045,'Color','Bimini',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (2,1,4011,4047,'Size','S',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (3,3,4000,4000,'Size','S',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (4,4,4005,4023,'Size','S',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (5,5,4006,4028,'Size','S',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (6,6,4010,4045,'Color','Bimini',0);
INSERT INTO `xlite_order_item_options` (id,item_id,group_id,option_id,name,value,orderby) VALUES (7,6,4011,4047,'Size','S',0);


INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (12,1,'tax','Tax',1,1,'Tax','0.0000');
INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (11,1,'tax','shipping_tax',0,0,'shipping_tax','0.0000');
INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (10,1,'shipping','Shipping cost',1,1,'shipping','2.7699');
INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (36,2,'tax','Tax',1,1,'Tax','0.0000');
INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (35,2,'tax','shipping_tax',0,0,'shipping_tax','0.0000');
INSERT INTO `xlite_order_modifiers` (id,order_id,code,name,is_visible,is_summable,subcode,surcharge) VALUES (34,2,'shipping','Shipping cost',1,1,'shipping','4.2037');


