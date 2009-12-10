<table if="!xlite.AdvancedSearchEnabled" border=0 cellpadding=0 cellspacing=0>
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
<TABLE IF="xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form action="{shopURL(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr valign=middle>
    <td>&nbsp;<img src="images/search.gif" width=19 height=19 align=absmiddle>&nbsp;</td>
    <td><span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
        <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td>
    <!-- [button] -->
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <TR>
    <TD><IMG SRC="images/rect_button_1.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    <TD background="images/rect_button_bg.gif"><a href="javascript: document.search_form.submit()" title="Search"><FONT class="FormButton">Go</FONT></a></TD>
    <TD><IMG SRC="images/rect_button_2.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    </TR>
    </TABLE>
    <!-- [/button] -->
    </td>

    </td>
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif">&nbsp;<a href="cart.php?target=advanced_search" title="Advanced Search" style="TEXT-DECORATION: underline; font-size : 9px">Advanced</a></td>
    </td>
    <td>&nbsp;&nbsp;</td>
</tr>
</form>
</TABLE>
