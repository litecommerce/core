{* Featured product list inside a category/central zone *}
<div FOREACH="category.featuredProducts,featuredProduct">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^featuredProduct.product.product_id,#category_id#^featuredProduct.product.category.category_id))}" IF="featuredProduct.product.hasThumbnail()"><img src="{featuredProduct.product.thumbnailURL}" border=0 width=70 alt=""></a>
        <br>
        <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^featuredProduct.product.product_id,#category_id#^featuredProduct.product.category.category_id))}" IF="featuredProduct.product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" border="0" align="middle" alt=""></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <table border="0">
        <tr>
            <td><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^featuredProduct.product.product_id,#category_id#^featuredProduct.product.category.category_id))}"><FONT class="ProductTitle">{featuredProduct.product.name:h}</FONT></a><br><br></td>
        </tr>
        <tr>
            <td>{truncate(featuredProduct.product,#brief_description#,#300#):h}<br><hr></td>
        </tr>
        <tr>
            <td>
<FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT>
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_CategoryButton" product="{featuredProduct.product}" />
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

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
