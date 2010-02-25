{* Featured product list inside a category/central zone *}
<div FOREACH="category.featuredProducts,featuredProduct">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="cart.php?target=product&amp;sns_mode=featured_product&amp;product_id={featuredProduct.product.product_id}&amp;category_id={featuredProduct.product.category_id}" IF="featuredProduct.product.hasThumbnail()"><img src="{featuredProduct.product.thumbnailURL}" border=0 width=70 alt=""></a> <br><a href="cart.php?target=product&amp;sns_mode=featured_product&amp;product_id={featuredProduct.product.product_id}&amp;category_id={featuredProduct.product.category_id}" IF="featuredProduct.product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" border="0" align="middle" alt=""></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <table border="0">
        <tr>
            <td><a href="cart.php?target=product&amp;sns_mode=featured_product&amp;product_id={featuredProduct.product.product_id}&amp;category_id={featuredProduct.product.category_id}"><FONT class="ProductTitle">{featuredProduct.product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr>
            <td>{truncate(featuredProduct.product,#brief_description#,#300#):h}<br><hr></td>
        </tr>
        <tr>
            <td>
<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT>
<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{featuredProduct.product}" visible="{!getPriceNotificationSaved(featuredProduct.product.product_id)}">
<!--AFTER PRICE-->
                <br><br>
                <widget template="buy_now.tpl" product="{featuredProduct.product}">
<!--AFTER BUY NOW-->
            </td>

        </tr>
        </table>
    </td>
</tr>
</table>
</div>

<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl">
