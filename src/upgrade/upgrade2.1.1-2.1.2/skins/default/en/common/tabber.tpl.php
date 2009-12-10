<?php
	$find_str = <<<EOT
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;&nbsp;&nbsp;<a class=PageLink href="{page.url}"><FONT class="tabSelected">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
EOT;
    $replace_str = <<<EOT
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;&nbsp;&nbsp;<a class=PageLink href="{page.url:h}"><FONT class="tabSelected">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;&nbsp;&nbsp;<a class=PageLinkDefault href="{page.url}"><FONT class="tabDefault">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
EOT;
    $replace_str = <<<EOT
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;&nbsp;&nbsp;<a class=PageLinkDefault href="{page.url:h}"><FONT class="tabDefault">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
