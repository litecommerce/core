; <?php /*
; WARNING: Do not change the line above
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
table_prefix = "xlite_"

;
; ----------------------
;  Cache settings
; ----------------------
;
[cache]
; Type of cache used. Can take auto, memcache, apc, xcache, file values.
type=file
; Cache namespace
namespace=XLite
; List of memcache servers. Semicolon is used as a delimiter.
; Each server is specified with a host name and port number, divided
; by a colon. If the port is not specified, the default
; port 11211 is used.
servers=

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

[clean_urls]
enabled = off
; String with one or more chars.
; It will be used to autogenerate clean URLs.
; By default, only the "-" or "_" characters are allowed.
; Empty string is also allowed.
default_separator = "-"

;
; -----------------
;  Logging details
; -----------------
;
[log_details]
type = file
name = "var/log/xlite.log.php"
level = PEAR_LOG_WARNING
ident = "XLite"
suppress_errors = On
suppress_log_errors = Off

;
; Skin details
;
[skin_details]
skin = default
locale = en

;
; Profiler settings
;
[profiler_details]
enabled = Off
process_widgets = On
xdebug_log_trace = Off
show_messages_on_top = Off

;
; Debug log settings
;
[debug]
mark_templates = off

;
; Default image settings
;
[images]
default_image = "images/no_image.png"
default_image_width = 110
default_image_height = 110
unsharp_mask_filter_on_resize = off

; Installation path of Image Magick executables:
; for example:
; image_magick_path = "C:\\Program Files\\ImageMagick-6.7.0-Q16\\"   (in Windows)
; image_magick_path = "/usr/local/imagemagick/" (in Unix/Linux )
; You should consult with your hosting provider to find where Image Magick is installed
; If you leave it empty then PHP GD library will be used.
;
image_magick_path =

;
; Installer authcode.
; A person who do not know the auth code can not access the installation script.
; Installation authcode is created authomatically and stored in this section.
;
[installer_details]
auth_code = ""
shared_secret_key = ""

;
; Some options to optimize the store
;
[performance]
developer_mode = Off
skins_cache = off

;
; Decorator options
;
[decorator]
time_limit = 600
use_tokenizer = Off
disable_software_reset = Off
use_output = Off

;
; Error handling options
;
[error_handling]
page = "public/error.html"

;
; Marketplace
;
[marketplace]
url = "http://www.litecommerce.com/?q=api/"
log_data = Off
upgrade_step_time_limit = 240
send_shop_domain = On
banner_url = "http://www.litecommerce.com/banners"

;
; Language options
;
[language]
default = en

;
; AMQP server
;
[amqp]
host     = "localhost"
port     = 5672
user     = "guest"
password = "guest"
vhost    = "/"
exchange = "xlite"

;
; Other options
;
[other]
; Translation drive code - auto / gettext / db
translation_driver = auto
; Event driver code - auto / db / amqp
event_driver = auto

; WARNING: Do not change the line below
; */ ?>
