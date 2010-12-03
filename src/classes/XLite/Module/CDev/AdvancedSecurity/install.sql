ALTER TABLE xlite_orders ADD secureDetails text;
ALTER TABLE xlite_orders ADD secureDetailsText text NOT NULL DEFAULT '';

INSERT INTO `xlite_config` VALUES (45,'clr_mp_logoff','AdvancedSecurity','checkbox',60,'N');
INSERT INTO `xlite_config` VALUES (109,'executable_cache','AdvancedSecurity','serialized',0,'');
INSERT INTO `xlite_config` VALUES (129,'gpg_binary_path','AdvancedSecurity','text',20,'/usr/local/bin/gpg');
INSERT INTO `xlite_config` VALUES (130,'gpg_crypt_db','AdvancedSecurity','checkbox',50,'Y');
INSERT INTO `xlite_config` VALUES (131,'gpg_crypt_mail','AdvancedSecurity','checkbox',40,'Y');
INSERT INTO `xlite_config` VALUES (132,'gpg_home','AdvancedSecurity','text',10,'');
INSERT INTO `xlite_config` VALUES (133,'gpg_user_id','AdvancedSecurity','text',30,'');

INSERT INTO `xlite_config_translations` VALUES (39,'en',45,'Clear master password after login and logoff','');
INSERT INTO `xlite_config_translations` VALUES (102,'en',129,'GnuPG executable path. If no value is specified, AdvancedSecurity module will attempt to find GnuPG executable in your system automatically.<br><i>Example:<br>&nbsp;&nbsp&nbsp;c:\\gnupg\\gpg.exe - for Windwos<br>&nbsp;&nbsp&nbsp;/usr/local/bin/gpg - for UNI','');
INSERT INTO `xlite_config_translations` VALUES (103,'en',130,'Encrypt order details stored in database','');
INSERT INTO `xlite_config_translations` VALUES (104,'en',131,'Encrypt admin order mail notifications','');
INSERT INTO `xlite_config_translations` VALUES (105,'en',132,'Home directory where GnuPG public and secret keys will be stored. If no value is specified, &quot;GNUPGHOME&quot; environment variable is used.<br><b>WARNING! Make sure that home directory is not available from the outside web!</b>','');
INSERT INTO `xlite_config_translations` VALUES (106,'en',133,'GnuPG user id. This is user id your GnuPG public and secret keys are built for.<br><i>Example: joe@foo.bar</i>','');

