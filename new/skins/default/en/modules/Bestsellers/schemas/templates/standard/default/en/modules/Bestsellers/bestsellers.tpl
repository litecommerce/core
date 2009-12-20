{* Bestsellers list inside a category/central zone *}
<table cellpadding="2" cellspacing="2" border="0">
<tr FOREACH="bestsellers,bestseller">
    <td IF="config.Bestsellers.bestsellers_thumbnails" valign="top" align="center" width="30">
    <!-- Product thumbnail -->
        <a href="cart.php?target=product&amp;sns_mode=bestseller&amp;product_id={bestseller.product_id}&amp;category_id={bestseller.category.category_id}" IF="bestseller.hasThumbnail()"><img src="cart.php?target=image&amp;action=product_thumbnail&amp;product_id={bestseller.product_id}" border=0 width=25 alt=""></a>
    </td>
    <td valign="top">
    <!-- Product details -->
        <a href="cart.php?target=product&amp;sns_mode=bestseller&amp;product_id={bestseller.product_id}&amp;category_id={bestseller.category.category_id}"><FONT class="ItemsList">{bestseller.name:h}</FONT></a><br>
        Price: {price_format(bestseller,#price#):r}
    </td>
</tr>
</table>
