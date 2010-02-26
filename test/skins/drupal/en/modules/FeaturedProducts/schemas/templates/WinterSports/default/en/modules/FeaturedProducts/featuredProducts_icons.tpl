{* Featured products list (icons) *}
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.featuredProducts,3),row">
<tr>
    <td valign="middle" FOREACH="row,featuredProduct" align="center" width="33%">
        <widget visible="{featuredProduct}" template="common/product_thumbnail.tpl" href="cart.php?target=product&sns_mode=featured_product&product_id={featuredProduct.product.product_id}&category_id={featuredProduct.product.category_id}" thumbnail="{featuredProduct.product.thumbnailURL}" noimage="{!featuredProduct.product.hasThumbnail()}" width="100">
    </td>
</tr>
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
      <a href="cart.php?target=product&amp;sns_mode=featured_product&amp;product_id={featuredProduct.product.product_id}&amp;category_id={featuredProduct.product.category_id}" IF="featuredProduct" class="FeaturedList">{featuredProduct.product.name}</a>
    </td>
</tr>    
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
        <span IF="featuredProduct"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT><br><widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" visible="{!getPriceNotificationSaved(featuredProduct.product.product_id)}" product="{featuredProduct.product}"><!--Should be: widgets/modules_ProductAdviser_PriceNotification_category_button.gif--></span>
    </td>
</tr>
<!--AFTER PRICE-->
<tr>
    <td valign="top" FOREACH="row,featuredProduct" align="center" width="33%">
        <widget IF="featuredProduct" template="buy_now.tpl" product="{featuredProduct.product}"/>
   </td>  
</tr>
<!--AFTER BUY NOW-->
<tr><td colspan="3"><img src="images/spacer.gif" height="10" width="1" alt="" border="0"></td></tr>
</tbody>
</table>

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm"><!--Should be: widgets/modules_ProductAdviser_PriceNotification_notify_form.gif-->

