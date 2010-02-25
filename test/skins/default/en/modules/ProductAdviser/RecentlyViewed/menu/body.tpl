{* Recently viewed menu body *}
<table border="0" cellpadding="0" cellspacing="0">
<tr FOREACH="recentliesProducts,id,product">
    <td valign=top><b>{inc(id)}.</b></td>
    <td>&nbsp;</td>
    <td><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a></td>
</tr>
<tr IF="additionalPresent">
    <td colspan=3><br><a href="{buildURL(#recently_viewed#)}" onClick="this.blur()"><font color=gray><u>All viewed...</u></font></a></td>
</tr>
</table>
