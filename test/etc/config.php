; <?php /*
; WARNING: Do not change the line above
; $Id$ 
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
hostspec = ""
socket   = ""
port     = ""
database = ""
username = ""
password = ""
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
http_host = ""
https_host = ""
web_dir = ""


;
; -----------------
;  Logging details
; -----------------
;
[log_details]
type = file
;type = client
;type = "null"
name = "var/log/xlite.log.php"
level = PEAR_LOG_DEBUG
ident = "XLite"
suppress_errors = On
suppress_log_errors = Off

;
; Skin details
;
[skin_details]
skin = default
inifile = "layout.ini"
locale = en

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
memory_limit = "32M"

;
; Installer authcode.
; A person who do not know the auth code can not access the installation script.
; Installation authcode is created authomatically and stored in this section.
;
[installer_details]
auth_code = ""

; WARNING: Do not change the line below
; */ ?>
