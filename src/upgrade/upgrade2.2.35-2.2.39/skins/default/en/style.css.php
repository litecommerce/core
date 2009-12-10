<?php

	$find_str = <<<EOT
	FONT-SIZE: 11px; COLOR: #4F5964; FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif
}

BODY {
	margin-top: 0px; 
EOT;
	$replace_str = <<<EOT
	FONT-SIZE: 11px; COLOR: #4F5964; FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif
}

HTML {
    height: 100%;
    margin:0;
    padding:0;
}

BODY {
    height: 100%;
	margin-top: 0px; 
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
TABLE.Container {
    HEIGHT: 100%;
}
EOT;
	$replace_str = <<<EOT
TABLE.Container {
    HEIGHT: 100%;

}

A.captcha {
    FONT-SIZE: 11px;
    TEXT-DECORATION: underline;
}
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>