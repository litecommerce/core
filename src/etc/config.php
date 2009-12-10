; <?php /*
; WARNING: Do not change the line above
; $Id: config.php,v 1.34 2009/09/13 12:56:00 fundaev Exp $ 
;
; +-------------------------------------+
; |   LiteCommerce configuration file   |
; +-------------------------------------+
;
; -----------------
;  About this file
; -----------------
;

;
; ----------------------
;  SQL Database details
; ----------------------
;
[database_details]
hostspec = "localhost"
database = "xlite"
username = "xlite"
password = "xlite"
persistent = Off

;
; -----------------------------------------------------------------------
;  LiteCommerce HTTP & HTTPS host and web directory where cart installed
; -----------------------------------------------------------------------
;
; NOTE:
; You should put here hostname ONLY without http:// or https:// prefixes
; Do not put slashes after the hostname
; Web dir is the directory in the URL, not the filesystem path
; Web dir must start with slash and have no slash at the end
; The only exception is when you configure for the root of the site,
; in which case you write single slash in it
;
; WARNING: Do not set the "$" sign before the parameter names!
;
; EXAMPLE 1:
;
;   http_host = "www.yourhost.com"
;   https_host = "www.securedirectories.com/yourhost.com"
;   web_dir = "/shop"
;
; will result in the following URLs:
;
;   http://www.yourhost.com/shop
;   https://www.securedirectories.com/yourhost.com/shop
;
;
; EXAMPLE 2:
;
;   http_host = "www.yourhost.com"
;   https_host = "www.yourhost.com"
;   web_dir = "/"
;
; will result in the following URLs:
;
;   http://www.yourhost.com/
;   https://www.yourhost.com/
;
[host_details]
http_host = "www.litecommerce.com"
https_host = "www.litecommerce.com"
web_dir = "/shop"


;
; -----------------
;  Logging details
; -----------------
;
[log_details]
;type = file
;type = debug
;type = client
type = "null"
name = "var/log/xlite.log.php"
level = LOG_DEBUG
ident = "XLite"
suppress_errors = Off
suppress_logging_errors = Off

;
; -----------------
;  Session details
; -----------------
;
; Possible session types:
;
;   sql
;   php
;   debug
;
[session_details]
type = sql

;
; Skin details
;
[skin_details]
skin = default
inifile = "layout.ini"
locale = en

;
; Template engine configuration options.
;
[HTML_Template_Flexy]
compileDir = "var/run/"
verbose = Off
max_forms_per_session = 100

;
; Classes decorator configuration options
;
[decorator_details]
compileDir = "var/run/classes/"
lockDir = "var/tmp/"

;
; Filesystem permissions
;
; permission_mode:
;     0 - mean that the non-privileged permissions should be used.
;         One should use this value if the web-user, PHP user and the
;         owner of LiteCommerce files are different.
;     1 - means that the web-user, PHP user and the owner of the
;         LiteCommerce files is the same. In this case the
;         privileged permissions are used.
; NOTE: The 1 value is more secure, but if you are not sure what web-user and PHP-user
;       are used on your server, you should use the 0 value for this option. If the web-user,
;       PHP-user and the files owner are different users on your server, usage of the 1 value
;       for this option causes problems with the store displaying.
[filesystem_permissions]
permission_mode = 0
nonprivileged_permission_dir_all = "0777"
nonprivileged_permission_file_all = "0666"
nonprivileged_permission_dir = "0755"
nonprivileged_permission_file = "0644"
privileged_permission_dir = "0711"
privileged_permission_file = "0600"
privileged_permission_file_nonphp = "0644"

;
; Profiler settings
;
[profiler_details]
enabled = Off
eta = On

[recorder]
record_queries = off

;
; ----------------------
;  PHP settings
; ----------------------
;
[php_settings]
memory_limit = "16M"

;
; Installer authcode.
; A person who do not know the auth code can not access the installation script.
; Installation authcode is created authomatically and stored in this section.
;
[installer_details]
auth_code = ""

; WARNING: Do not change the line below
; */ ?>
