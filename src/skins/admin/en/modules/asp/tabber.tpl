<table border=0 width="100%" cellpadding=0 cellspacing=0 valign=top>
<tr><td height=15 align=right>
<table  border=0 cellspacing=0 cellpadding=0 height=100%>
<tr height=17>
{foreach:widget.asppages,tabPage}
{if:tabPage.selected}
<td width="3" height="27" background="images/tab_a1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;<img src="images/modules/asp/active/{tabPage.icon_path}" border="0" alt=""></td>
<td nowrap align=center valign=center background="images/tab_bg_a.gif">&nbsp;<a class=PageLink href="{tabPage.url:h}"><FONT class="tabSelected">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" height="27" background="images/tab_a2.gif">&nbsp;</td>
{else:}
<td width="1"></td>
<td width="3" height="27" background="images/tab_p1.gif"></td>
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;<img src="images/modules/asp/inactive/{tabPage.icon_path}" border="0" alt=""></td>
<td nowrap align=center valign=center background="images/tab_bg_p.gif">&nbsp;<a class=PageLinkDefault href="{tabPage.url:h}"><FONT class="tabDefault">{tabPage.header}</FONT></a>&nbsp;&nbsp;&nbsp;</td>
<td width="4" height="27" background="images/tab_p2.gif">&nbsp;</td>
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
