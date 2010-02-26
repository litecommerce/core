{* Product list inside a category ('table' mode) *}

<widget class="XLite_View_Pager" data="{category.products}" name="pager">

<table cellpadding="3" cellspacing="1" border="0" bgcolor="#cccccc" width="100%">
<tr FOREACH="pager.pageData,product" bgcolor="#ffffff">
	<td width="25">#{getRowNumber()}</td>
    <td width="100%"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle">{product.name:h}</FONT></a></td>
	<td nowrap IF="config.LayoutOrganizer.show_price"><FONT class="ProductPrice">{price_format(product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {product.priceMessage:h}</FONT><widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/category_button.tpl" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}"></td>
    <td nowrap><widget template="buy_now.tpl" product="{product}"></td>
</tr>
</table>

<widget name="pager">

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm">
