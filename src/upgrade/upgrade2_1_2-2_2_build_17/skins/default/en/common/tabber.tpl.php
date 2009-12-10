<?php
    $find_str = <<<EOT
<tr><td height=15>
<table  border=0 cellspacing=0 cellpadding=0 height=100%>
<tr height=17>
{foreach:pages,page}
{if:page.selected}
<td width="3" background="images/tab_a1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;&nbsp;&nbsp;<a class=PageLink href="{page.url:h}"><FONT class="tabSelected">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" background="images/tab_a2.gif">&nbsp;</td>
{else:}
<td width="1"></td>
<td width="3" background="images/tab_p1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;&nbsp;&nbsp;<a class=PageLinkDefault href="{page.url:h}"><FONT class="tabDefault">{page.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" background="images/tab_p2.gif">&nbsp;</td>
<td width="1"></td>
EOT;
    $replace_str = <<<EOT
<tr><td height=15>
<table  border=0 cellspacing=0 cellpadding=0 height=100%>
<tr height=17>
{foreach:widget.pages,tabPage}
{if:tabPage.selected}
<td width="3" background="images/tab_a1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;&nbsp;&nbsp;<a class=PageLink href="{tabPage.url:h}"><FONT class="tabSelected">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" background="images/tab_a2.gif">&nbsp;</td>
{else:}
<td width="1"></td>
<td width="3" background="images/tab_p1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;&nbsp;&nbsp;<a class=PageLinkDefault href="{tabPage.url:h}"><FONT class="tabDefault">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" background="images/tab_p2.gif">&nbsp;</td>
<td width="1"></td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<table border=0 cellspacing=1 cellpadding=20 width="100%" class=Center>
<tr>
<td class=Center>
{body.display()}
</td>
</tr>
</table>
EOT;
    $replace_str = <<<EOT
<table border=0 cellspacing=1 cellpadding=20 width="100%" class=Center>
<tr>
<td class=Center>
<widget template="{widget.body}">
</td>
</tr>
</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
