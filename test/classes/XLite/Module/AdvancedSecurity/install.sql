ALTER TABLE xlite_orders ADD secureDetails text;
ALTER TABLE xlite_orders ADD secureDetailsText text NOT NULL DEFAULT '';

INSERT INTO xlite_config VALUES ('gpg_home','Home directory where GnuPG public and secret keys will be stored. If no value is specified, &quot;GNUPGHOME&quot; environment variable is used.<br><b>WARNING! Make sure that home directory is not available from the outside web!</b>','','AdvancedSecurity',10,'text');
INSERT INTO xlite_config VALUES ('gpg_binary_path','GnuPG executable path. If no value is specified, AdvancedSecurity module will attempt to find GnuPG executable in your system automatically.<br><i>Example:<br>&nbsp;&nbsp;c:\\gnupg\\gpg.exe - for Windows<br>&nbsp;&nbsp;/usr/local/bin/gpg - for UNIX</i>','/usr/local/bin/gpg','AdvancedSecurity',20,'text');
UPDATE xlite_config SET comment= 'GnuPG executable path. If no value is specified, AdvancedSecurity module will attempt to find GnuPG executable in your system automatically.<br><i>Example:<br>&nbsp;&nbsp&nbsp;c:\\gnupg\\gpg.exe - for Windwos<br>&nbsp;&nbsp&nbsp;/usr/local/bin/gpg - for UNI' WHERE category='AdvancedSecurity' AND name='gpg_binary_path';
INSERT INTO xlite_config VALUES ('gpg_user_id','GnuPG user id. This is user id your GnuPG public and secret keys are built for.<br><i>Example: joe@foo.bar</i>','','AdvancedSecurity',30,'text');
INSERT INTO xlite_config VALUES ('gpg_crypt_mail','Encrypt admin order mail notifications','Y','AdvancedSecurity',40,'checkbox');
INSERT INTO xlite_config VALUES ('gpg_crypt_db','Encrypt order details stored in database','Y','AdvancedSecurity',50,'checkbox');
INSERT INTO xlite_config VALUES ('clr_mp_logoff','Clear master password after login and logoff','N','AdvancedSecurity',60,'checkbox');
REPLACE INTO xlite_config VALUES ('executable_cache', '', '', 'AdvancedSecurity', 0, 'serialized');
