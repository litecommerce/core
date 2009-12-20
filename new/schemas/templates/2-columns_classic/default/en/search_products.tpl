{* Product search form template *}
<TABLE IF="!xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form action="{shopURL(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr valign=middle>
    <td WIDTH=28>&nbsp;&nbsp;</td>
    <td>&nbsp;<img src="images/search.gif" width=19 height=19 align=absmiddle>&nbsp;</td>
    <td>
    <span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td>
<!-- [button] -->
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><IMG SRC="images/rect_button_1.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
<TD background="images/rect_button_bg.gif"><a href="javascript: document.search_form.submit()"><FONT class="FormButton">Search</FONT></a></TD>
<TD><IMG SRC="images/rect_button_2.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
</TR>
</TABLE>
<!-- [/button] -->
    </td>
    <td IF="advanced_search">
        &nbsp;&nbsp;<a href="cart.php?target=advanced_search" style="TEXT-DECORATION: underline;">Advanced search</a>
    </td>
    <td>&nbsp;&nbsp;</td>
</tr>
</form>
</TABLE>
<TABLE IF="xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form action="{shopURL(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr valign=middle>
    <td WIDTH=28>&nbsp;&nbsp;</td>
    <td>
    <span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td valign="middle">
<!-- [button] -->
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD background="images/rect_button_bg.gif"><a href="javascript: document.search_form.submit()" title="Search"><FONT class="FormButton"><img src="images/search.gif" width=18 height=18 align=absmiddle border="0"></FONT></a></TD>
</TR>
</TABLE>
<!-- [/button] -->
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif">&nbsp;<a href="cart.php?target=advanced_search" style="TEXT-DECORATION: underline;" title="Advanced search">Advanced</a>
    </td>
    <td>&nbsp;&nbsp;</td>
</tr>
</form>
</TABLE>
