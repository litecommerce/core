<span IF="!products">
No products found on your query. Please try to {if:xlite.AdvancedSearchEnabled}<a href ="cart.php?target=advanced_search" class="FormButton"><u>re-formulate</u></a>{else:}re-formulate{end:} the query.
</span>
<span IF="products">
{if:xlite.AdvancedSearchEnabled&count}{dialog.count} {if:count=#1#}product{else:} products {end:} found. <a class="FormButton" href="cart.php?target=advanced_search"><u>Refine your search</u></a>{end:}
<widget class="CPager" data="{products}" name="pager">
<p FOREACH="pager.pageData,product">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="cart.php?target=product&product_id={product.product_id}&substring={substring:u}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL:h}" border=0 width=70></a> <br><a href="cart.php?target=product&product_id={product.product_id}&substring={substring:u}" IF="product.hasThumbnail()">See&nbsp;details&nbsp;<img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <a href="cart.php?target=product&product_id={product.product_id}&substring={substring:u}"><FONT class="ProductTitle">{product.name:h}</FONT></a>
        <br>
        <br>
        {truncate(product,#brief_description#,#300#):h}
        <br>
        <hr>
        <FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT>
		<!--AFTER PRICE-->
        <br><br>
        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><widget template="buy_now.tpl" product="{product}"></td>
            <td width="40">&nbsp;</td>
            <td><widget module="WishList" template="modules/WishList/add.tpl" href="cart.php?target=wishlist&action=add&product_id={product.product_id}"></td>
        </tr>
        </table>
		<!--AFTER BUY NOW-->
    </td>
</tr>
</table>
</p>

<widget name="pager">

</span>
