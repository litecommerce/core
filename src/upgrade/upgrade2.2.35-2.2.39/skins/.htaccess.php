<?php

	$find_str = <<<EOT
<files "*.tpl">
Deny from all  
</files>
EOT;
	$replace_str = <<<EOT
<Files "*.tpl">
Deny from all  
</Files>
<Files "*.php">
Deny from all
</Files>
<Files "*.pl">
Deny from all 
</Files>
<Files "*.conf">
Deny from all
</Files>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
