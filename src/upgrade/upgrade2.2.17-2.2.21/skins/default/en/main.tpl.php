<?php

	$find_str = <<<EOT
<!-- [main_view] -->
EOT;
    $replace_str = <<<EOT
<!-- [main_view] -->
<TABLE border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD valign="top">
	<noscript>
		<table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
		<tr>
			<td align=center class=ErrorMessage nowrap>The requested action requires JavaScript.<br>Please enable Javascript in your web browser.</td>
		</tr>
		</table>
	</noscript>
	<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/cookie_validator.js"></script>
    </TD>
</TR>
</TABLE>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>