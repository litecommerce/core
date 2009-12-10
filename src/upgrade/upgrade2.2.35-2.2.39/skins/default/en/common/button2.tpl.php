<?php

	$find_str = <<<EOT
<TD valign="top" class="CommonButton2CenterBG" nowrap>&nbsp;&nbsp;<a href="{widget.href}" target="{widget.hrefTarget:r}" class="ButtonLink">{widget.label:h}</a>&nbsp;&nbsp;</TD>
EOT;
	$replace_str = <<<EOT
<TD valign="top" class="CommonButton2CenterBG" nowrap>&nbsp;&nbsp;<a href="{widget.href:t}" target="{widget.hrefTarget:r}" class="ButtonLink">{widget.label:h}</a>&nbsp;&nbsp;</TD>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>