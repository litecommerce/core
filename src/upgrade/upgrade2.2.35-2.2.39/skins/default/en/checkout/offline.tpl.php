<?php

	$find_str = <<<EOT
        Element.innerHTML = "<b>Please wait while your order is processing...</b>";
EOT;
	$replace_str = <<<EOT
        Element.innerHTML = "<b>Please wait while your order is being processed...</b>";
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>