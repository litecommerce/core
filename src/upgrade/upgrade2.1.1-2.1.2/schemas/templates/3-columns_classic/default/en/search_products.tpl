<table border=0 cellpadding=0 cellspacing=0>
<form action="{shopURL(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr>
    <td>&nbsp;<img src="images/search.gif" width=19 height=19 align=absmiddle>&nbsp;</td>
    <td>
    <span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    <a href="javascript: document.search_form.submit()" class="SidebarItems"><input type="image" src="images/go_search.gif" border="0" align="absmiddle"></a>
    </td>
</tr>
<tr IF="advanced_search">
    <td width=19>&nbsp;</td>
    <td colspan=2><a href="cart.php?target=advanced_search">Advanced search</a></td>
</tr>
</form>
</table>
