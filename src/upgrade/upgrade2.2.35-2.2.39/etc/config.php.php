<?php

	$find_str = <<<EOT
name = "var/log/xlite.log"
EOT;
	$replace_str = <<<EOT
name = "var/log/xlite.log.php"
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
verbose = Off
EOT;
	$replace_str = <<<EOT
verbose = Off
max_forms_per_session = 100
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
compileDir = "var/run/classes/"
EOT;
	$replace_str = <<<EOT
compileDir = "var/run/classes/"

;
; Filesystem permissions
;
[filesystem_permissions]
nonprivileged_permission_dir_all = "0777"
nonprivileged_permission_file_all = "0666"
nonprivileged_permission_dir = "0755"
nonprivileged_permission_file = "0644"
privileged_permission_dir = "0711"
privileged_permission_file = "0600"
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>