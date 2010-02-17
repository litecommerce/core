{* Bestsellers list inside a category/central zone *}
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(bestsellers,3),row">
<tr>
    <td valign="middle" FOREACH="row,bestseller" align="center" width="33%">
        <widget visible="{bestseller}" template="common/product_thumbnail.tpl" href="cart.php?target=product&sns_mode=featured_product&product_id={bestseller.product_id}&category_id={bestseller.category_id}" thumbnail="{bestseller.thumbnailURL}" noimage="{!bestseller.hasThumbnail()}" width="100">
    </td>
</tr>
<tr>
    <td valign="top" FOREACH="row,bestseller" align="center" width="33%">
      <a href="cart.php?target=product&amp;sns_mode=featured_product&amp;product_id={bestseller.product_id}&amp;category_id={bestseller.category_id}" IF="bestseller" class="FeaturedList">{bestseller.name}</a>
    </td>
</tr>    
<tr>
    <td valign="top" FOREACH="row,bestseller" align="center" width="33%">
        <span IF="bestseller"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(bestseller,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {bestseller.priceMessage:h}</FONT><br><widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" visible="{!getPriceNotificationSaved(bestseller.product_id)}" product="{bestseller}"></span>
    </td>
</tr>
<!--AFTER PRICE-->
<tr>
    <td valign="top" FOREACH="row,bestseller" align="center" width="33%">
        <widget IF="bestseller" template="buy_now.tpl" product="{bestseller}"/>
   </td>  
</tr>
<!--AFTER BUY NOW-->
<tr><td colspan="3"><img src="images/spacer.gif" height="10" width="1" alt="" border="0"></td></tr>
</tbody>
</table>
