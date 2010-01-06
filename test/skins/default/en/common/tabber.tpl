<table border=0 width="100%" cellpadding=0 cellspacing=0 valign=top>
<tr><td height=15>
<table  border=0 cellspacing=0 cellpadding=0 class="Container">
<tr height=17>
{foreach:widget.pages,tabPage}
{if:tabPage.selected}
<td width="3" class="TabberPageSelectedLeftBG"></td>
<td nowrap align=center valign=middle class="TabberPageSelectedCenterBG">&nbsp;&nbsp;&nbsp;<a class=PageLink href="{tabPage.url:h}"><FONT class="tabSelected">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" class="TabberPageSelectedRightBG">&nbsp;</td>
{else:}
<td width="1"></td>
<td width="3" class="TabberPageNormalLeftBG"></td>
<td nowrap align=center valign=middle class="TabberPageNormalCenterBG">&nbsp;&nbsp;&nbsp;<a class=PageLinkDefault href="{tabPage.url:h}"><FONT class="tabDefault">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" class="TabberPageNormalRightBG">&nbsp;</td>
<td width="1"></td>

{end:}
{end:}

</tr>
</table>
</tr>
<tr>
<td align=center class=CenterBorder>
<table border=0 cellspacing=2 cellpadding=0 width="100%">
<tr>
<td class=CenterBorder>
<table border=0 cellspacing=1 cellpadding=20 width="100%" class=Center>
<tr>
<td class=Center>
<widget template="{widget.body}">
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<br>
