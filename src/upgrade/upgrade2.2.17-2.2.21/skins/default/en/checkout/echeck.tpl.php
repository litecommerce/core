<?php

	$find_str = "Element.innerHTML = \"Please wait while your order is processing...\";";
    $replace_str = "Element.innerHTML = \"<b>Please wait while your order is processing...</b>\";";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = "<td><font class=\"Star\">*</font></td>";
    $replace_str = "<td class=\"Star\">*</td>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = "<td width=\"10\"><font class=\"Star\">*</font></td>";
    $replace_str = "<td width=\"10\" class=\"Star\">*</td>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = "<td valign=\"top\"><font class=\"Star\">*</font></td>";
    $replace_str = "<td valign=\"top\" class=\"Star\">*</td>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = "document.checkout.submit();";
    $replace_str = "window.scroll(0, 100000);\n\t\tdocument.checkout.submit();";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>