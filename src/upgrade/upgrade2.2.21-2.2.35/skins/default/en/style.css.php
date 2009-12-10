<?php

	$find_str = <<<EOT
}

BODY {
	margin-top: 0 px; 
	margin-bottom: 0 px; 
	margin-left: 0 px; 
	margin-right: 0 px; 
	background-color: #FFFFFF;
    margin: 0px; 
    padding: 0px;
EOT;

	$replace_str = <<<EOT
}

BODY {
	margin-top: 0px; 
	margin-bottom: 0px; 
	margin-left: 0px; 
	margin-right: 0px; 
	background-color: #FFFFFF;
    margin: 0px; 
    padding: 0px;
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
