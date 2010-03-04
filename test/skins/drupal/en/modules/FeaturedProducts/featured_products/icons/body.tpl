{* Featured products list (icons) *}
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.featuredProducts,3),row">
<tr>
    <td valign="middle" FOREACH="row,featuredProduct" align="center" width="33%">
        <a IF="featuredProduct" href="{buildURL(#product#,##,_ARRAY_(#product_id#^featuredProduct.product.product_id,#category_id#^featuredProduct.product.category.category_id))}"><span IF="featuredProduct.product.hasThumbnail()"><img src="{featuredProduct.product.thumbnailURL}" border="0" width="100" alt=""></span><span IF="!featuredProduct.product.hasThumbnail()"><img src="images/no_image.gif" border="0" alt=""></span></a>&nbsp;
    </td>
</tr>
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
      <a IF="featuredProduct" href="{buildURL(#product#,##,_ARRAY_(#product_id#^featuredProduct.product.product_id,#category_id#^featuredProduct.product.category.category_id))}"><FONT class="ItemsList">{featuredProduct.product.name}</FONT></a>
    </td>
</tr>    
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
        <span IF="featuredProduct"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT><br><widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_CategoryButton" product="{featuredProduct.product}" /></span>
    </td>
</tr>
<!--AFTER PRICE-->
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
        <widget IF="featuredProduct" template="buy_now.tpl" product="{featuredProduct.product}"/>
   </td>  
</tr>
<!--AFTER BUY NOW-->
<tr><td colspan="3"><img src="images/spacer.gif" height="10" width="1" border="0" alt=""></td></tr>
</tbody>
</table>

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
