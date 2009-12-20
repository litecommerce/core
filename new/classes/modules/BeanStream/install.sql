ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO `xlite_payment_methods` VALUES ('beanstream_cc', 'BeanStream', 'Visa, Mastercard, American Express', 'beanstream_cc', 'a:4:{s:11:"merchant_id";s:0:"";s:7:"trnType";s:1:"P";s:8:"username";s:0:"";s:8:"password";s:0:"";}', 10, 0);
