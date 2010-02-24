{* New Arrivals menu body *}
<table border="0" cellpadding="0" cellspacing="0">
<tr FOREACH="newArrivalsProducts,id,product">
    <td valign=top><b>{inc(id)}.</b></td>
    <td>&nbsp;</td>
    <td><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={product.category.category_id}" class="SidebarItems">{product.name:h}</a></td>
</tr>
<tr IF="additionalPresent">
    <td colspan=3><br><a href="cart.php?target=NewArrivals" onClick="this.blur()"><font color=gray><u>All new arrivals...</u></font></a></td>
</tr>
</table>

