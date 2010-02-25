{* New Arrivals menu body *}
<table border="0" cellpadding="0" cellspacing="0">
<tr FOREACH="getNewArrivalsProducts(),id,product">
    <td valign=top><b>{inc(id)}.</b></td>
    <td>&nbsp;</td>
    <td><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" class="SidebarItems">{product.name:h}</a></td>
</tr>
{* FIXME - this condition is never evaluates to "true" *}
<tr IF="additionalPresent">
    <td colspan=3><br><a href="{buildURL(#NewArrivals#,##)}" onClick="this.blur()"><font color=gray><u>All new arrivals...</u></font></a></td>
</tr>
</table>

