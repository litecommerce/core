; <?php /*
; CruiseControl config file
; $Id$ 

[database_details]
hostspec = "localhost:/tmp/mysql-5.0.51.sock"
database = "xcart_xlite_cc"
username = "xcart"
password = "fjgQzklfthlj2489g"
persistent = Off

[host_details]
http_host = "xcart2-530.crtdev.local"
https_host = "xcart2-530.crtdev.local"
web_dir = "/~xcart/general/projects/xlite/source/src"

[log_details]
type = file
name = "var/log/xlite.log.php"
level = LOG_DEBUG
ident = "XLite"
suppress_errors = Off
suppress_logging_errors = Off

[session_details]
type = sql

[skin_details]
skin = default
inifile = "layout.ini"
locale = en

[HTML_Template_Flexy]
compileDir = "var/run/"
verbose = Off
max_forms_per_session = 100

[decorator_details]
compileDir = "var/run/classes/"
lockDir = "var/tmp/"

[filesystem_permissions]
permission_mode = 0
nonprivileged_permission_dir_all = "0777"
nonprivileged_permission_file_all = "0666"
nonprivileged_permission_dir = "0755"
nonprivileged_permission_file = "0644"
privileged_permission_dir = "0711"
privileged_permission_file = "0600"
privileged_permission_file_nonphp = "0644"

[profiler_details]
enabled = Off
eta = On

[recorder]
record_queries = off

[php_settings]
memory_limit = "32M"

[installer_details]
auth_code = ""

; */ ?>
