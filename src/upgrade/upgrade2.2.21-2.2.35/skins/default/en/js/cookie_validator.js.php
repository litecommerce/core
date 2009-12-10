<?php

	$find_str = <<<EOT
	document.write("<table border=0 width=500 cellpadding=2 cellspacing=0 align=center>");
	document.write("<tr>");
	document.write("<td align=center class=ErrorMessage nowrap>");
	document.write("Your web browser is required to accept cookies to perform the requested action.<br>Please enable cookies in your web browser.");
	document.write("</td>");
	document.write("</tr>");
	document.write("</table>");
EOT;

	$replace_str = <<<EOT
	document.write("<table border=0 width=500 cellpadding=2 cellspacing=0 align=center>");
	document.write("<tr>");
	document.write("<td align=center class=ErrorMessage nowrap>");
	document.write("This site requires cookies to function properly.<br>Please enable cookies in your web browser.");
	document.write("</td>");
	document.write("</tr>");
	document.write("</table>");
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
